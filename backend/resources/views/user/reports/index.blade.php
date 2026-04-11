<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بلاغاتي - نظام تقييم الأضرار</title>
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
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); }
        .report-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .filter-btn { transition: all 0.2s ease; }
        .filter-btn.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-color: transparent;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.4s ease forwards; }
        .page-btn { transition: all 0.2s ease; }
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
                    <a href="{{ route('user.dashboard') }}" class="text-gray-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 hover:border-indigo-500 transition-all">
                        لوحة التحكم
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
                <h2 class="text-3xl font-bold text-white">بلاغاتي</h2>
                <p class="text-slate-400 mt-2">جميع البلاغات التي قمت بإضافتها</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-400">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card rounded-2xl p-6 stat-card">
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

                <div class="glass-card rounded-2xl p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm">قيد الانتظار</p>
                            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $pendingReports }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm">قيد المعالجة</p>
                            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $processingReports }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 stat-card">
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

            @if($reports->count() > 0)
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-2 flex-wrap">
                    <button onclick="filterReports('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 text-slate-300" data-filter="all">
                        الكل
                    </button>
                    <button onclick="filterReports('pending')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 text-slate-300" data-filter="pending">
                        قيد الانتظار
                    </button>
                    <button onclick="filterReports('processing')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 text-slate-300" data-filter="processing">
                        قيد المعالجة
                    </button>
                    <button onclick="filterReports('completed')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 text-slate-300" data-filter="completed">
                        مكتمل
                    </button>
                </div>
            </div>

            <div id="reports-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $report)
                @php
                $statusColors = [
                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                    'processing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'completed' => 'bg-green-500/20 text-green-400 border-green-500/30',
                    'rejected' => 'bg-red-500/20 text-red-400 border-red-500/30',
                ];
                $statusLabels = [
                    'pending' => 'قيد الانتظار',
                    'processing' => 'قيد المعالجة',
                    'completed' => 'مكتمل',
                    'rejected' => 'مرفوض',
                ];
                $damageColors = [
                    'critical' => 'bg-red-500/20 text-red-400 border-red-500/30',
                    'high' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                    'medium' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                    'low' => 'bg-green-500/20 text-green-400 border-green-500/30',
                ];
                $damageLabels = [
                    'critical' => 'حرج',
                    'high' => 'عالي',
                    'medium' => 'متوسط',
                    'low' => 'منخفض',
                ];
                $damageBarColors = [
                    'critical' => 'from-red-500 to-rose-600',
                    'high' => 'from-orange-500 to-amber-600',
                    'medium' => 'from-yellow-500 to-orange-500',
                    'low' => 'from-green-500 to-emerald-500',
                ];
                $damageBarWidths = [
                    'critical' => '100%',
                    'high' => '75%',
                    'medium' => '50%',
                    'low' => '25%',
                ];
                $damageLevel = $report->ai_damage_level;
                $hasImages = is_array($report->images) && count($report->images) > 0;
                $hasImage = $report->image_path && $report->image_path !== '';
                @endphp
                <div class="report-card rounded-2xl overflow-hidden animate-in" data-status="{{ $report->status }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    @if($hasImages)
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ asset('storage/' . $report->images[0]) }}" alt="صورة البلاغ" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$report->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}">
                                {{ $statusLabels[$report->status] ?? $report->status }}
                            </span>
                        </div>
                    </div>
                    @elseif($hasImage)
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ asset('storage/' . $report->image_path) }}" alt="صورة البلاغ" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$report->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}">
                                {{ $statusLabels[$report->status] ?? $report->status }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="relative h-44 bg-slate-800/50 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-slate-700/50 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <svg class="w-7 h-7 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 text-xs">لا توجد صورة</p>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$report->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}">
                                {{ $statusLabels[$report->status] ?? $report->status }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold text-sm leading-relaxed">{{ Str::limit($report->raw_location, 35) }}</h3>
                                @if($report->ai_location)
                                <p class="text-indigo-400 text-xs mt-1">{{ Str::limit($report->ai_location, 30) }}</p>
                                @endif
                            </div>
                            <span class="text-slate-500 text-xs whitespace-nowrap mr-2">#{{ $report->id }}</span>
                        </div>

                        @if($report->raw_description)
                        <p class="text-slate-400 text-xs mb-4 leading-relaxed">{{ Str::limit($report->raw_description, 80) }}</p>
                        @endif

                        @if($damageLevel && isset($damageLabels[$damageLevel]))
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-slate-400 text-xs">مستوى الضرر</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium border {{ $damageColors[$damageLevel] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}">
                                    {{ $damageLabels[$damageLevel] }}
                                </span>
                            </div>
                            <div class="w-full bg-slate-700/50 rounded-full h-2">
                                <div class="h-2 rounded-full bg-gradient-to-r {{ $damageBarColors[$damageLevel] ?? 'from-gray-500 to-gray-600' }}" style="width: {{ $damageBarWidths[$damageLevel] ?? '0%' }}"></div>
                            </div>
                        </div>
                        @else
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-slate-400 text-xs">مستوى الضرر</span>
                                <span class="text-slate-500 text-xs">غير محدد</span>
                            </div>
                            <div class="w-full bg-slate-700/50 rounded-full h-2">
                                <div class="h-2 rounded-full bg-slate-600" style="width: 0%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-3 border-t border-slate-700/50">
                            <div class="flex items-center gap-2 text-slate-500 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $report->created_at->format('Y/m/d') }}
                            </div>
                            <a href="{{ route('user.reports.show', $report) }}" class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 text-xs font-medium transition-all">
                                عرض التفاصيل
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="no-results" class="hidden text-center py-12">
                <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-slate-400">لا توجد بلاغات بهذه الحالة</p>
            </div>

            @if($reports->lastPage() > 1)
            <div class="mt-8 flex justify-center">
                <div class="flex items-center gap-2">
                    @if($reports->onFirstPage())
                    <span class="page-btn px-3 py-2 rounded-lg text-sm text-slate-600 border border-slate-700/50 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                    @else
                    <a href="{{ $reports->previousPageUrl() }}" class="page-btn px-3 py-2 rounded-lg text-sm text-slate-300 border border-slate-600 hover:border-indigo-500 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    @foreach(range(1, $reports->lastPage()) as $page)
                    @if($page == $reports->currentPage())
                    <span class="page-btn px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $reports->url($page) }}" class="page-btn px-4 py-2 rounded-lg text-sm text-slate-300 border border-slate-600 hover:border-indigo-500 hover:text-white transition-all">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach

                    @if($reports->hasMorePages())
                    <a href="{{ $reports->nextPageUrl() }}" class="page-btn px-3 py-2 rounded-lg text-sm text-slate-300 border border-slate-600 hover:border-indigo-500 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    @else
                    <span class="page-btn px-3 py-2 rounded-lg text-sm text-slate-600 border border-slate-700/50 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <div class="w-20 h-20 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-white text-xl font-bold mb-2">لا توجد بلاغات بعد</h3>
                    <p class="text-slate-400 text-sm mb-6">ابدأ بإضافة بلاغ جديد وسيتم تحليله بالذكاء الاصطناعي</p>
                    <a href="{{ route('user.reports.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة بلاغ جديد
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function filterReports(status) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.filter === status) {
                    btn.classList.add('active');
                }
            });

            const cards = document.querySelectorAll('.report-card');
            let visibleCount = 0;

            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noResults = document.getElementById('no-results');
            if (visibleCount === 0 && noResults) {
                noResults.classList.remove('hidden');
            } else if (noResults) {
                noResults.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
