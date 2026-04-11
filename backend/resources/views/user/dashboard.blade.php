<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام تقييم الأضرار</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e1b4b 100%); }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="hero-gradient min-h-screen">
    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-white">نظام تقييم الأضرار</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-gray-300">مرحباً، {{ auth()->user()->name }}</span>
                    <a href="{{ route('user.profile') }}" class="text-gray-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 hover:border-indigo-500 transition-all">
                        الملف الشخصي
                    </a>
                    <a href="{{ route('user.reports.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
                        + إضافة بلاغ جديد
                    </a>


                </div>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white">لوحة التحكم</h2>
                <p class="text-slate-400 mt-2">نظرة عامة على بلاغاتك</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-2xl p-6 stat-card transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm">إجمالي البلاغات</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $totalReports }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 stat-card transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm">قيد المعالجة</p>
                            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $pendingReports }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 stat-card transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm">مكتملة</p>
                            <p class="text-3xl font-bold text-green-400 mt-2">{{ $completedReports }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">آخر البلاغات</h3>
                    <a href="{{ route('user.reports') }}" class="text-indigo-400 hover:text-indigo-300 text-sm">عرض الكل</a>
                </div>

                @if($recentReports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="text-left text-slate-400 font-medium py-3 px-4">الموقع</th>
                                <th class="text-left text-slate-400 font-medium py-3 px-4">الحالة</th>
                                <th class="text-left text-slate-400 font-medium py-3 px-4">مستوى الضرر</th>
                                <th class="text-left text-slate-400 font-medium py-3 px-4">التاريخ</th>
                                <th class="text-left text-slate-400 font-medium py-3 px-4">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReports as $report)
                            <tr class="border-b border-slate-700/50 hover:bg-slate-800/50">
                                <td class="py-4 px-4 text-white">{{ Str::limit($report->raw_location, 30) }}</td>
                                <td class="py-4 px-4">
                                    @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                                        'processing' => 'bg-blue-500/20 text-blue-400',
                                        'completed' => 'bg-green-500/20 text-green-400',
                                        'rejected' => 'bg-red-500/20 text-red-400',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'قيد الانتظار',
                                        'processing' => 'قيد المعالجة',
                                        'completed' => 'مكتمل',
                                        'rejected' => 'مرفوض',
                                    ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$report->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                        {{ $statusLabels[$report->status] ?? $report->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-white">{{ $report->ai_damage_level ?? 'غير محدد' }}</td>
                                <td class="py-4 px-4 text-slate-400">{{ $report->created_at->format('Y/m/d') }}</td>
                                <td class="py-4 px-4">
                                    <a href="{{ route('user.reports.show', $report) }}" class="text-indigo-400 hover:text-indigo-300">عرض</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-slate-400">لا توجد بلاغات بعد</p>
                    <a href="{{ route('user.reports.create') }}" class="inline-block mt-4 px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all">
                        إضافة بلاغ جديد
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
