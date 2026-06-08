<?php

namespace App\Http\Controllers\User;

use App\Services\GeminiService;
use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalReports = Report::where('user_id', $user->id)->count();
        $pendingReports = Report::where('user_id', $user->id)->where('status', 'pending')->count();
        $completedReports = Report::where('user_id', $user->id)->where('status', 'completed')->count();
        $recentReports = Report::where('user_id', $user->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('user.dashboard', compact(
            'totalReports',
            'pendingReports',
            'completedReports',
            'recentReports'
        ));
    }

    public function reports()
    {
        $userId = auth()->id();
        $query = Report::where('user_id', $userId);

        $totalReports = (clone $query)->count();
        $pendingReports = (clone $query)->where('status', 'pending')->count();
        $processingReports = (clone $query)->where('status', 'processing')->count();
        $completedReports = (clone $query)->where('status', 'completed')->count();
        $reports = (clone $query)->latest()->paginate(9);

        return view('user.reports.index', compact('reports', 'totalReports', 'pendingReports', 'processingReports', 'completedReports'));
    }

    public function create()
    {
        return view('user.reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'user_id' => auth()->id(),
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

        NotificationModel::createForUser(
            auth()->id(),
            'status',
            'تم إرسال البلاغ بنجاح',
            'Report Submitted Successfully',
            'تم إرسال البلاغ #' . $report->id . ' وهو قيد التحليل بالذكاء الاصطناعي',
            'Report #' . $report->id . ' has been submitted and is being analyzed by AI',
            $report->id
        );

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationModel::createForUser(
                $admin->id,
                'new_report',
                'بلاغ جديد',
                'New Report',
                'تم إرسال بلاغ جديد #' . $report->id . ' من ' . auth()->user()->name . ' - ' . $report->raw_location,
                'New report #' . $report->id . ' submitted by ' . auth()->user()->name . ' - ' . $report->raw_location,
                $report->id
            );
        }

        return redirect()->route('user.reports')->with('success', 'تم إرسال البلاغ بنجاح. سيتم تحليله بالذكاء الاصطناعي.');
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'profile_image' => 'nullable|image|max:5120',
            'current_password' => 'nullable|string|current_password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function destroyProfile(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function show(Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->load('user');
        return view('user.reports.show', compact('report'));
    }
}
