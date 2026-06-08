<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\ReportResource;
use App\Services\GeminiService;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @group Report Management
 *
 * APIs for managing damage reports
 */
class ReportController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $reports = Report::with('user')->latest()->get();
        } else {
            $reports = $user->reports()->latest()->get();
        }

        return response()->json(ReportResource::collection($reports));
    }

    public function store(StoreReportRequest $request, GeminiService $geminiService): \Illuminate\Http\JsonResponse
    {
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports/images', 'public');
                $imagePaths[] = $path;
            }
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $imagePaths = [$path];
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('reports/docs', 'public');
        }

        $links = $request->input('video_links', []);
        $links = array_filter($links);

        $report = Report::create([
            'user_id' => auth()->id(),
            'image_path' => !empty($imagePaths) ? $imagePaths[0] : null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'pdf_file' => $pdfPath,
            'video_links' => !empty($links) ? $links : null,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'raw_location' => $request->raw_location,
            'raw_description' => $request->raw_description ?? '',
            'status' => 'processing',
        ]);

        try {
            $aiResult = $geminiService->analyzeDamage([
                'image_path' => $report->image_path,
                'images' => $report->images,
                'raw_location' => $report->raw_location,
                'raw_description' => $report->raw_description,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
            ]);

            $isRejected = ($aiResult['damage_category'] === 'rejected' || (isset($aiResult['is_relevant']) && !$aiResult['is_relevant']));

            $report->update([
                'ai_location' => $aiResult['normalized_location'],
                'ai_damage_score' => $aiResult['damage_score'],
                'ai_damage_level' => $aiResult['damage_category'],
                'ai_analysis' => $aiResult['analysis_text'],
                'status' => 'completed',
                'admin_approval_status' => $isRejected ? 'rejected' : 'pending'
            ]);

            Log::info('AI analysis completed synchronously', [
                'report_id' => $report->id,
                'is_rejected' => $isRejected
            ]);
        } catch (\Exception $e) {
            Log::error('AI analysis failed, using fallback', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
            ]);

            $fallbackScore = 5;
            $fallbackCategory = 'moderate';

            if (!empty($report->raw_description)) {
                $desc = mb_strtolower($report->raw_description);
                if (preg_match('/منهار|انهيار|دمار|تدمير|متهدم|خربان/i', $desc)) {
                    $fallbackScore = 9;
                    $fallbackCategory = 'critical';
                } elseif (preg_match('/شديد|كبير|خطير|متصدع|تشقق كبير/i', $desc)) {
                    $fallbackScore = 7;
                    $fallbackCategory = 'severe';
                } elseif (preg_match('/متوسط|بعض|جزئي|خفيف/i', $desc)) {
                    $fallbackScore = 5;
                    $fallbackCategory = 'moderate';
                } elseif (preg_match('/طفيف|بسيط|خدش|صغير/i', $desc)) {
                    $fallbackScore = 2;
                    $fallbackCategory = 'minor';
                }
            }

            $report->update([
                'ai_location' => $report->raw_location,
                'ai_damage_score' => $fallbackScore,
                'ai_damage_level' => $fallbackCategory,
                'ai_analysis' => 'تحليل ذكي: ' . $report->raw_description . ' (تقييم تلقائي بناءً على وصف المستخدم)',
                'status' => 'pending_approval',
            ]);
        }

        $report->refresh();

        return response()->json([
            'data' => [
                'id' => $report->id,
                'status' => $report->status,
                'damage_score' => $report->ai_damage_score,
                'damage_level' => $report->ai_damage_level,
                'ai_location' => $report->ai_location,
                'ai_analysis' => $report->ai_analysis,
                'message' => 'تم رفع التقرير وتحليل الضرر بنجاح.'
            ]
        ], 201);
    }

    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $report = Report::with('user')->findOrFail($id);
        } else {
            $report = $user->reports()->findOrFail($id);
        }

        return response()->json(ReportResource::make($report));
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $report = Report::findOrFail($id);
        } else {
            $report = $user->reports()->findOrFail($id);
        }
        
        if (!empty($report->images) && is_array($report->images)) {
            foreach ($report->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
        
        if ($report->image_path && Storage::disk('public')->exists($report->image_path)) {
            Storage::disk('public')->delete($report->image_path);
        }
        
        if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
            Storage::disk('public')->delete($report->pdf_file);
        }
        
        $report->delete();
        
        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }
}