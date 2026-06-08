<?php

namespace App\Http\Controllers\Admin;

use App\Services\GeminiService;
use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();
        $completedReports = Report::where('status', 'completed')->count();
        $pendingReports = Report::where('status', 'pending')->count();
        $totalUsers = User::count();

        $damageStats = Report::whereNotNull('ai_damage_level')
            ->selectRaw('ai_damage_level, COUNT(*) as count')
            ->groupBy('ai_damage_level')
            ->get()
            ->pluck('count', 'ai_damage_level')
            ->toArray();

        $reportsOverTime = Report::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $reportsByLocation = Report::selectRaw('ai_location, COUNT(*) as count')
            ->whereNotNull('ai_location')
            ->groupBy('ai_location')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->pluck('count', 'ai_location')
            ->toArray();

        $statusStats = Report::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $recentReports = Report::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalReports',
            'completedReports',
            'pendingReports',
            'totalUsers',
            'damageStats',
            'reportsOverTime',
            'reportsByLocation',
            'statusStats',
            'recentReports'
        ));
    }

    public function map()
    {
        $reports = Report::whereNotIn('status', ['rejected'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'latitude', 'longitude', 'ai_damage_level', 'raw_location', 'image_path', 'images', 'pdf_file', 'video_links']);

        return view('admin.map', compact('reports'));
    }

    public function reports(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }

        $query = Report::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('raw_location', 'like', "%{$search}%")
                    ->orWhere('ai_location', 'like', "%{$search}%")
                    ->orWhere('raw_description', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('ai_damage_level')) {
            $query->where('ai_damage_level', $request->input('ai_damage_level'));
        }

        if ($request->filled('admin_approval_status')) {
            $query->where('admin_approval_status', $request->input('admin_approval_status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $reports = $query->latest()->paginate(20)->appends($request->query());

        $users = User::orderBy('name')->get();

        return view('admin.reports', compact('reports', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.reports.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'raw_location' => 'required|string|max:255',
            'raw_description' => 'nullable|string|max:2000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:10240',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'video_links' => 'nullable|array',
            'video_links.*' => 'nullable|url|max:500',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reports', 'public');
                    $images[] = $path;
                }
            }
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('reports', 'public');
        }

        $videoLinks = array_filter($request->input('video_links', []), function($link) {
            return !empty($link) && filter_var($link, FILTER_VALIDATE_URL);
        });

        $report = Report::create([
            'user_id' => $validated['user_id'],
            'raw_location' => $validated['raw_location'],
            'raw_description' => $validated['raw_description'] ?? '',
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'processing',
            'image_path' => !empty($images) ? $images[0] : null,
            'images' => !empty($images) ? $images : null,
            'pdf_file' => $pdfPath,
            'video_links' => array_values($videoLinks),
        ]);

        try {
            $geminiService = app(GeminiService::class);
            $result = $geminiService->analyzeDamage([
                'image_path' => $report->image_path,
                'raw_location' => $report->raw_location,
                'raw_description' => $report->raw_description,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
            ]);
            $report->update([
                'ai_location' => $result['normalized_location'],
                'ai_damage_score' => $result['damage_score'],
                'ai_damage_level' => $result['damage_category'],
                'ai_analysis' => $result['analysis_text'],
                'status' => 'pending_approval',
            ]);
        } catch (\Exception $e) {
            $report->update([
                'status' => 'pending_approval',
                'ai_damage_score' => 5,
                'ai_damage_level' => 'moderate',
                'ai_location' => $report->raw_location ?? 'غير محدد',
                'ai_analysis' => 'فشل التحليل التلقائي: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.reports')->with('success', 'تم إنشاء التقرير بنجاح. تم تحليل مستوى الضرر بالذكاء الاصطناعي.');
    }

    public function edit(Report $report)
    {
        $users = User::all();
        return view('admin.reports.edit', compact('report', 'users'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'raw_location' => 'required|string|max:255',
            'raw_description' => 'nullable|string|max:2000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'nullable|in:pending,processing,completed,rejected',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:10240',
            'existing_images' => 'nullable|array',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'remove_pdf' => 'nullable|boolean',
            'video_links' => 'nullable|array',
            'video_links.*' => 'nullable|url|max:500',
            'keep_old_image' => 'nullable|string',
            'edit_ai_damage_level' => 'nullable|string|in:minor,moderate,severe,critical,low,medium,high',
            'edit_ai_damage_score' => 'nullable|integer|min:1|max:10',
            'edit_ai_analysis' => 'nullable|string|max:5000',
            'edit_admin_approval_status' => 'nullable|in:pending,approved,rejected',
            'edit_admin_damage_score' => 'nullable|integer|min:1|max:10',
            'edit_admin_notes' => 'nullable|string|max:5000',
            'reprocess_with_ai' => 'nullable|boolean',
        ]);

        $images = $request->input('existing_images', []);

        if ($request->input('keep_old_image') == '1' && $report->image_path) {
            if (!in_array($report->image_path, $images)) {
                $images[] = $report->image_path;
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reports', 'public');
                    $images[] = $path;
                }
            }
        }

        $pdfPath = $report->pdf_file;

        if ($request->hasFile('pdf_file')) {
            if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
                Storage::disk('public')->delete($report->pdf_file);
            }
            $pdfPath = $request->file('pdf_file')->store('reports', 'public');
        } elseif ($request->has('remove_pdf')) {
            if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
                Storage::disk('public')->delete($report->pdf_file);
            }
            $pdfPath = null;
        }

        $videoLinks = array_filter($request->input('video_links', []), function($link) {
            return !empty($link) && filter_var($link, FILTER_VALIDATE_URL);
        });

        $updateData = [
            'user_id' => $validated['user_id'],
            'raw_location' => $validated['raw_location'],
            'raw_description' => $validated['raw_description'] ?? $report->raw_description,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => $validated['status'] ?? $report->status,
            'images' => $images,
            'pdf_file' => $pdfPath,
            'video_links' => array_values($videoLinks),
        ];

        if ($request->filled('edit_ai_damage_level')) {
            $updateData['ai_damage_level'] = $validated['edit_ai_damage_level'];
        }

        if ($request->filled('edit_ai_damage_score')) {
            $updateData['ai_damage_score'] = $validated['edit_ai_damage_score'];
        }

        if ($request->filled('edit_ai_analysis')) {
            $updateData['ai_analysis'] = $validated['edit_ai_analysis'];
        }

        if ($request->filled('edit_admin_approval_status')) {
            $updateData['admin_approval_status'] = $validated['edit_admin_approval_status'];
            if ($validated['edit_admin_approval_status'] === 'approved') {
                $updateData['status'] = 'approved';
                $updateData['approved_by'] = auth()->id();
                NotificationModel::createForUser(
                    $report->user_id, 'approved',
                    'تمت الموافقة على البلاغ', 'Report Approved',
                    'تمت الموافقة على البلاغ #' . $report->id . ' الخاص بك',
                    'Your report #' . $report->id . ' has been approved',
                    $report->id
                );
            } elseif ($validated['edit_admin_approval_status'] === 'rejected') {
                $updateData['status'] = 'rejected';
                $updateData['approved_by'] = auth()->id();
                NotificationModel::createForUser(
                    $report->user_id, 'rejected',
                    'تم رفض البلاغ', 'Report Rejected',
                    'تم رفض البلاغ #' . $report->id . ' الخاص بك',
                    'Your report #' . $report->id . ' has been rejected',
                    $report->id
                );
            }
        }

        if ($request->filled('edit_admin_damage_score')) {
            $updateData['admin_damage_score'] = $validated['edit_admin_damage_score'];
        }

        if ($request->filled('edit_admin_notes')) {
            $updateData['admin_notes'] = $validated['edit_admin_notes'];
        }

        $report->update($updateData);

        if ($request->has('reprocess_with_ai')) {
            try {
                $geminiService = app(GeminiService::class);
                $result = $geminiService->analyzeDamage([
                    'image_path' => !empty($images) ? $images[0] : $report->image_path,
                    'raw_location' => $report->raw_location,
                    'raw_description' => $report->raw_description,
                    'latitude' => $report->latitude,
                    'longitude' => $report->longitude,
                ]);
                $report->update([
                    'ai_location' => $result['normalized_location'],
                    'ai_damage_score' => $result['damage_score'],
                    'ai_damage_level' => $result['damage_category'],
                    'ai_analysis' => $result['analysis_text'],
                    'status' => 'pending_approval',
                ]);
            } catch (\Exception $e) {
                return redirect()->route('admin.reports.show', $report)->with('error', 'تم تحديث التقرير لكن فشل إعادة المعالجة بالذكاء الاصطناعي: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.reports.show', $report)->with('success', 'تم تحديث التقرير بنجاح.');
    }

    public function show(Report $report)
    {
        $report->load('user');
        return view('admin.reports.show', compact('report'));
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('admin.reports')->with('success', 'تم حذف التقرير بنجاح.');
    }

    public function approveReport(Report $report)
    {
        $report->update([
            'admin_approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'status' => 'approved',
        ]);

        NotificationModel::createForUser(
            $report->user_id,
            'approved',
            'تمت الموافقة على البلاغ',
            'Report Approved',
            'تمت الموافقة على البلاغ #' . $report->id . ' الخاص بك',
            'Your report #' . $report->id . ' has been approved',
            $report->id
        );

        return redirect()->route('admin.reports.show', $report)->with('success', 'تمت الموافقة على التقرير بنجاح.');
    }

    public function rejectReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:2000',
        ]);

        $report->update([
            'admin_approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'admin_notes' => $validated['admin_notes'],
            'status' => 'rejected',
        ]);

        NotificationModel::createForUser(
            $report->user_id,
            'rejected',
            'تم رفض البلاغ',
            'Report Rejected',
            'تم رفض البلاغ #' . $report->id . '. السبب: ' . $validated['admin_notes'],
            'Report #' . $report->id . ' has been rejected. Reason: ' . $validated['admin_notes'],
            $report->id
        );

        return redirect()->route('admin.reports.show', $report)->with('success', 'تم رفض التقرير بنجاح.');
    }

    public function updateDamageAssessment(Request $request, Report $report)
    {
        $validated = $request->validate([
            'admin_damage_score' => 'required|integer|min:1|max:10',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $report->update([
            'admin_damage_score' => $validated['admin_damage_score'],
            'admin_notes' => $validated['admin_notes'] ?? $report->admin_notes,
        ]);

        NotificationModel::createForUser(
            $report->user_id,
            'assessment',
            'تحديث تقييم الضرر',
            'Damage Assessment Updated',
            'تم تحديث تقييم الضرر للبلاغ #' . $report->id . ' - التقييم الجديد: ' . $validated['admin_damage_score'] . '/10',
            'Damage assessment updated for report #' . $report->id . ' - New score: ' . $validated['admin_damage_score'] . '/10',
            $report->id
        );

        return redirect()->route('admin.reports.show', $report)->with('success', 'تم تحديث تقييم مستوى الضرر بنجاح.');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'profile_image' => 'nullable|image|max:5120',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'تم تحديث ملفك الشخصي بنجاح');
    }
}
