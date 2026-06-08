<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeDamageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $reportId;
    protected int $tries = 3;
    protected int $timeout = 120;

    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    public function handle(GeminiService $geminiService)
    {
        $report = Report::find($this->reportId);

        if (!$report) {
            Log::warning('Report not found for AI processing', ['report_id' => $this->reportId]);
            return;
        }

        $allImages = [];
        if (!empty($report->image_path)) {
            $allImages[] = $report->image_path;
        }
        if (!empty($report->images) && is_array($report->images)) {
            $allImages = array_unique(array_merge($allImages, $report->images));
        }

        if (empty($allImages) && empty($report->raw_description)) {
            Log::warning('No data found for AI analysis (no images and no description)', ['report_id' => $this->reportId]);
            $report->update([
                'status' => 'completed',
                'ai_analysis' => 'لم يتم التحليل: لا توجد بيانات كافية (صور أو وصف)',
                'ai_damage_level' => 'rejected',
                'ai_location' => $report->raw_location ?? 'غير محدد',
            ]);
            return;
        }

        try {
            $report->update(['status' => 'processing']);

            $result = $geminiService->analyzeDamage([
                'image_path' => $report->image_path,
                'images' => $report->images,
                'raw_location' => $report->raw_location,
                'raw_description' => $report->raw_description,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
            ]);

            if ($result['damage_level'] === 'rejected' || (isset($result['is_relevant']) && !$result['is_relevant'])) {
                $report->update([
                    'ai_location' => $result['normalized_location'],
                    'ai_damage_level' => 'rejected',
                    'ai_damage_score' => 0,
                    'ai_analysis' => $result['analysis_text'],
                    'status' => 'completed',
                    'admin_approval_status' => 'rejected'
                ]);
                
                Log::info('Report rejected by AI: No damage detected', [
                    'report_id' => $this->reportId,
                    'location' => $result['normalized_location']
                ]);
                return;
            }

            $report->update([
                'ai_location' => $result['normalized_location'],
                'ai_damage_level' => $result['damage_level'],
                'ai_damage_score' => $result['damage_score'] ?? 5,
                'ai_analysis' => $result['analysis_text'],
                'status' => 'completed',
            ]);

            Log::info('AI analysis completed successfully', [
                'report_id' => $this->reportId,
                'damage_level' => $result['damage_level'],
                'location' => $result['normalized_location']
            ]);

        } catch (\Exception $e) {
            $report->update([
                'status' => 'completed',
                'ai_analysis' => 'فشل التحليل: ' . $e->getMessage(),
                'ai_damage_level' => 'medium',
                'ai_location' => $report->raw_location ?? 'غير محدد',
            ]);

            Log::error('AI processing failed - fallback values applied', [
                'report_id' => $this->reportId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function failed(\Throwable $exception)
    {
        $report = Report::find($this->reportId);
        if ($report) {
            $report->update([
                'status' => 'completed',
                'ai_analysis' => 'فشل التحليل: ' . $exception->getMessage(),
                'ai_damage_level' => 'medium',
                'ai_location' => $report->raw_location ?? 'غير محدد',
            ]);
        }
    }
}
