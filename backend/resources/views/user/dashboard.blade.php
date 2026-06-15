<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام تقييم الأضرار</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Cairo', sans-serif; }

        body {
            background-color: #F3F2EF;
            color: #0B0B45;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(11, 11, 69, 0.08);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 16px 40px -12px rgba(11, 11, 69, 0.12);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -12px rgba(11, 11, 69, 0.15);
        }

        .section-header {
            background: linear-gradient(135deg, #0B0B45 0%, #1a1a3e 100%);
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .nav-btn {
            transition: all 0.2s ease;
            background: #1a1a3e;
            color: #FAFAFA;
            border: 1px solid #C9A97C;
        }

        .nav-btn:hover {
            background: #C9A97C;
            color: #0B0B45;
            border-color: #C9A97C;
        }

        .btn-primary {
            background: linear-gradient(135deg, #C9A97C 0%, #B08D5F 100%);
            color: #0B0B45;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 24px rgba(201, 169, 124, 0.35);
            transform: translateY(-1px);
        }

        .badge-minor {
            background: rgba(145, 166, 138, 0.15);
            color: #5A8A50;
            border: 1px solid rgba(145, 166, 138, 0.25);
        }

        .badge-critical {
            background: rgba(156, 93, 77, 0.12);
            color: #9C5D4D;
            border: 1px solid rgba(156, 93, 77, 0.2);
        }

        .badge-severe {
            background: rgba(214, 181, 112, 0.18);
            color: #B8942E;
            border: 1px solid rgba(214, 181, 112, 0.3);
        }

        .badge-high {
            background: rgba(214, 181, 112, 0.15);
            color: #B8942E;
            border: 1px solid rgba(214, 181, 112, 0.25);
        }

        .badge-moderate {
            background: rgba(201, 169, 124, 0.14);
            color: #C9A97C;
            border: 1px solid rgba(201, 169, 124, 0.22);
        }

        .badge-medium {
            background: rgba(201, 169, 124, 0.12);
            color: #C9A97C;
            border: 1px solid rgba(201, 169, 124, 0.2);
        }

        .badge-low {
            background: rgba(145, 166, 138, 0.15);
            color: #7A9473;
            border: 1px solid rgba(145, 166, 138, 0.25);
        }

        .badge-pending {
            background: rgba(214, 181, 112, 0.12);
            color: #B8942E;
            border: 1px solid rgba(214, 181, 112, 0.2);
        }

        .badge-pending_approval {
            background: rgba(120, 169, 193, 0.12);
            color: #4A7A8F;
            border: 1px solid rgba(120, 169, 193, 0.2);
        }

        .badge-processing {
            background: rgba(120, 169, 193, 0.12);
            color: #5A8DA8;
            border: 1px solid rgba(120, 169, 193, 0.2);
        }

        .badge-approved {
            background: rgba(145, 166, 138, 0.18);
            color: #5A8A50;
            border: 1px solid rgba(145, 166, 138, 0.28);
        }

        .badge-completed {
            background: rgba(145, 166, 138, 0.15);
            color: #7A9473;
            border: 1px solid rgba(145, 166, 138, 0.25);
        }

        .badge-rejected {
            background: rgba(156, 93, 77, 0.12);
            color: #9C5D4D;
            border: 1px solid rgba(156, 93, 77, 0.2);
        }

        .badge-unknown {
            background: rgba(11, 11, 69, 0.08);
            color: #0B0B45;
            border: 1px solid rgba(11, 11, 69, 0.12);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F3F2EF;
        }

        ::-webkit-scrollbar-thumb {
            background: #C9A97C;
            border-radius: 4px;
        }

        .nav-link-mobile {
            display: block;
            padding: 0.75rem 1rem;
            color: #FAFAFA;
            border-radius: 0.5rem;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
            background: rgba(26, 26, 62, 0.5);
            border: 1px solid rgba(201, 169, 124, 0.2);
        }
        .nav-link-mobile:hover {
            background: #C9A97C;
            color: #0B0B45;
            border-color: #C9A97C;
        }
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
    </style>
</head>
<body class="min-h-screen">
    <nav style="background-color: #0B0B45 !important;" class="fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h1 class="text-base sm:text-xl font-bold text-[#FAFAFA]">نظام تقييم الأضرار</h1>
                </div>

                <!-- Desktop nav buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <span class="text-[#FAFAFA]/80 text-sm">مرحباً، <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong></span>
                    <a href="{{ route('user.profile') }}" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium">
                        الملف الشخصي
                    </a>
                    <a href="{{ route('user.reports') }}" class="nav-btn px-4 py-2 rounded-lg text-sm font-medium">
                        بلاغاتي
                    </a>
                    <a href="{{ route('user.reports.create') }}" class="bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-2 rounded-lg text-sm font-bold hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-md">
                        + إضافة بلاغ
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors bg-[#1a1a3e] text-[#FAFAFA]/60 hover:text-[#C9A97C] hover:border-[#C9A97C] border border-transparent" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Mobile hamburger button -->
                <button id="mobileMenuBtn" class="md:hidden text-[#FAFAFA] p-2 rounded-lg hover:bg-[#1a1a3e] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu panel -->
        <div id="mobileMenu" class="hidden md:hidden bg-[#0B0B45] border-t border-[#1a1a3e] shadow-xl">
            <div class="px-4 py-4 space-y-3">
                <div class="text-[#FAFAFA]/80 text-sm py-2 border-b border-[#1a1a3e]">مرحباً، <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong></div>
                <a href="{{ route('user.profile') }}" class="block nav-btn px-4 py-3 rounded-lg text-sm font-medium text-center">
                    الملف الشخصي
                </a>
                <a href="{{ route('user.dashboard') }}" class="block nav-btn px-4 py-3 rounded-lg text-sm font-medium text-center">
                    لوحة التحكم
                </a>
                <a href="{{ route('user.reports') }}" class="block nav-btn px-4 py-3 rounded-lg text-sm font-medium text-center">
                    بلاغاتي
                </a>
                <a href="{{ route('user.reports.create') }}" class="block bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-3 rounded-lg text-sm font-bold text-center shadow-md">
                    + إضافة بلاغ
                </a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full nav-btn px-4 py-3 rounded-lg text-sm font-medium text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-5 fade-in">
                <div class="glass-card rounded-2xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md bg-gradient-to-br from-[#C9A97C] to-[#B08D5F]">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#0B0B45]">لوحة التحكم</h2>
                                <p class="text-xs text-[#0B0B45]/50">نظرة عامة على بلاغاتك</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="pulse-dot w-2 h-2 rounded-full bg-[#78A9C1]"></span>
                            <span class="text-xs text-[#0B0B45]/50">متصل</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-8 fade-in">
                <div class="stat-card glass-card rounded-2xl p-4 lg:p-5 shadow-lg">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div class="w-11 h-11 lg:w-12 lg:h-12 shrink-0 rounded-xl flex items-center justify-center shadow-lg bg-gradient-to-br from-[#C9A97C] to-[#B08D5F]">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs lg:text-sm truncate text-[#0B0B45]/60">إجمالي البلاغات</p>
                            <p class="text-xl lg:text-2xl font-bold mt-0.5 text-[#C9A97C]">{{ $totalReports }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card glass-card rounded-2xl p-4 lg:p-5 shadow-lg">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div class="w-11 h-11 lg:w-12 lg:h-12 shrink-0 rounded-xl flex items-center justify-center shadow-lg bg-gradient-to-br from-[#0B0B45] to-[#1a1a3e]">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#C9A97C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs lg:text-sm truncate text-[#0B0B45]/60">قيد الانتظار</p>
                            <p class="text-xl lg:text-2xl font-bold mt-0.5 text-[#0B0B45]">{{ $pendingReports }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card glass-card rounded-2xl p-4 lg:p-5 shadow-lg">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div class="w-11 h-11 lg:w-12 lg:h-12 shrink-0 rounded-xl flex items-center justify-center shadow-lg bg-gradient-to-br from-[#78A9C1] to-[#5A8FA8]">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs lg:text-sm truncate text-[#0B0B45]/60">مكتملة</p>
                            <p class="text-xl lg:text-2xl font-bold mt-0.5 text-[#78A9C1]">{{ $completedReports }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
                <div class="section-header px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center gap-2 text-[#FAFAFA]">
                        <svg class="w-5 h-5 text-[#C9A97C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        آخر البلاغات
                    </h3>
                    <a href="{{ route('user.reports') }}" class="text-xs sm:text-sm transition-colors text-[#C9A97C] hover:text-[#FAFAFA]">عرض الكل</a>
                </div>

                @if($recentReports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px]">
                        <thead class="bg-[#0B0B45]/5">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-bold text-[#0B0B45]/70">الموقع</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-bold text-[#0B0B45]/70">الحالة</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-bold text-[#0B0B45]/70">مستوى الضرر</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-bold text-[#0B0B45]/70">التاريخ</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-bold text-[#0B0B45]/70">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReports as $report)
                            @php
                            $statusBadge = [
                                'approved' => 'badge-approved',
                                'completed' => 'badge-completed',
                                'pending' => 'badge-pending',
                                'pending_approval' => 'badge-pending_approval',
                                'processing' => 'badge-processing',
                                'rejected' => 'badge-rejected',
                            ];
                            $statusLabels = [
                                'pending' => 'قيد الانتظار',
                                'pending_approval' => 'بانتظار الموافقة',
                                'processing' => 'قيد المعالجة',
                                'approved' => 'تمت الموافقة',
                                'completed' => 'مكتمل',
                                'rejected' => 'غير مقبول',
                            ];
                            $damageBadge = [
                                'minor' => 'badge-minor',
                                'critical' => 'badge-critical',
                                'severe' => 'badge-severe',
                                'high' => 'badge-high',
                                'moderate' => 'badge-moderate',
                                'medium' => 'badge-medium',
                                'low' => 'badge-low',
                            ];
                            $damageLabels = [
                                'minor' => 'طفيف',
                                'critical' => 'حرج',
                                'severe' => 'شديد',
                                'high' => 'عالي',
                                'moderate' => 'متوسط',
                                'medium' => 'متوسط',
                                'low' => 'منخفض',
                            ];
                            @endphp
                            <tr class="transition-colors border-b border-[#0B0B45]/5 hover:bg-[#0B0B45]/5">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-[#0B0B45]/80">{{ Str::limit($report->raw_location, 30) }}</td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBadge[$report->status] ?? 'badge-unknown' }}">
                                        {{ $statusLabels[$report->status] ?? $report->status }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $damageBadge[$report->ai_damage_level] ?? 'badge-unknown' }}">
                                        {{ $damageLabels[$report->ai_damage_level] ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-[#0B0B45]/50">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    <a href="{{ route('user.reports.show', $report) }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-medium transition-colors text-[#C9A97C] hover:text-[#B08D5F]">
                                        عرض
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-[#0B0B45]/5">
                        <svg class="w-8 h-8 text-[#0B0B45]/25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold mb-2 text-[#0B0B45]/60">لا توجد بلاغات بعد</h3>
                    <p class="text-xs sm:text-sm mb-6 text-[#0B0B45]/40">ابدأ بإضافة بلاغ جديد وسيتم تحليله بالذكاء الاصطناعي</p>
                    <a href="{{ route('user.reports.create') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-xl text-xs sm:text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة بلاغ جديد
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
