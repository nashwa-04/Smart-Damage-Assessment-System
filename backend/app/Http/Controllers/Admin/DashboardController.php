<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();
        $completedReports = Report::where('status', 'completed')->count();
        $pendingReports = Report::where('status', 'pending')->count();
        $totalUsers = User::count();

        // Damage level statistics
        $damageStats = Report::whereNotNull('ai_damage_level')
            ->selectRaw('ai_damage_level, COUNT(*) as count')
            ->groupBy('ai_damage_level')
            ->get()
            ->pluck('count', 'ai_damage_level')
            ->toArray();

        // Reports over time (last 7 days)
        $reportsOverTime = Report::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Reports by location (top 10 cities)
        $reportsByLocation = Report::selectRaw('ai_location, COUNT(*) as count')
            ->whereNotNull('ai_location')
            ->groupBy('ai_location')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->pluck('count', 'ai_location')
            ->toArray();

        // Status distribution
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
        $reports = Report::where('status', 'completed')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'latitude', 'longitude', 'ai_damage_level', 'raw_location']);

        return view('admin.map', compact('reports'));
    }

    public function reports()
    {
        $reports = Report::with('user')->latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }
}
