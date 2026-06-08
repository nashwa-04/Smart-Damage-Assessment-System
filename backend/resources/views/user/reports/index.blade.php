<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بلاغاتي - نظام تقييم الأضرار</title>
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
            box-shadow: 0 20px 40px -12px rgba(11, 11, 69, 0.12);
        }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); }
        .report-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(11, 11, 69, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        .report-card:hover {
            transform: translateY(-4px);
            border-color: rgba(201, 169, 124, 0.5);
            box-shadow: 0 20px 40px rgba(11, 11, 69, 0.15);
        }
        .report-card .quick-actions {
            opacity: 1;
        }
        @media (hover: hover) {
        .report-card .quick-actions {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .report-card:hover .quick-actions {
            opacity: 1;
        }
        @media (max-width: 768px) {
            .report-card .quick-actions {
                opacity: 1;
            }
        }
        }
        .filter-btn {
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(11, 11, 69, 0.15);
            color: #0B0B45;
            cursor: pointer;
        }
        .filter-btn:hover {
            border-color: #C9A97C;
            color: #C9A97C;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #0B0B45 0%, #1a1a3e 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(11, 11, 69, 0.3);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.4s ease forwards; }
        .page-btn { transition: all 0.2s ease; }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid transparent;
            backdrop-filter: blur(8px);
        }
        .damage-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .btn-add-report {
            background: linear-gradient(135deg, #C9A97C, #B08D5F);
            color: #0B0B45;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(201, 169, 124, 0.35);
        }
        .btn-add-report:hover {
            background: linear-gradient(135deg, #D6B570, #C9A97C);
            box-shadow: 0 6px 20px rgba(201, 169, 124, 0.45);
            transform: translateY(-1px);
        }
        .nav-icon-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
            background: #1a1a3e;
            color: rgba(250, 250, 250, 0.6);
            border: 1px solid transparent;
            position: relative;
        }
        .nav-icon-btn:hover {
            color: #C9A97C;
            border-color: #C9A97C;
        }
        .notif-badge {
            position: absolute;
            top: -4px; right: -4px;
            width: 16px; height: 16px;
            background: linear-gradient(135deg, #C9A97C, #D4A24C);
            color: #0B0B45;
            font-size: 9px; font-weight: 800;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #0B0B45;
        }
    </style>
</head>
<body class="min-h-screen">
    <nav style="background-color: #0B0B45 !important;" class="border-b border-[#0B0B45]/20 fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-[#FAFAFA] hidden sm:block" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment System">نظام تقييم الأضرار</h1>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('user.profile') }}" class="text-[#FAFAFA]/70 text-sm whitespace-nowrap hover:text-[#C9A97C] transition-all"><span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong></a>
                    <a href="{{ route('user.reports.create') }}" class="btn-add-report px-4 py-2 rounded-lg text-sm font-bold inline-flex items-center gap-1.5" data-ar="+ إضافة بلاغ" data-en="+ Add Report">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        + إضافة بلاغ
                    </a>
                    <button id="langToggle" class="px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span id="langText">English</span>
                    </button>
                    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                    <a href="{{ route('user.notifications') }}" class="nav-icon-btn" title="الإشعارات">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-icon-btn" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="md:hidden flex items-center gap-2">
                    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                    <a href="{{ route('user.notifications') }}" class="nav-icon-btn" style="width:32px;height:32px;" title="الإشعارات">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="notif-badge" style="width:14px;height:14px;font-size:8px;top:-3px;right:-3px;">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <button class="langToggleBtn px-2 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="langTextSpan">English</span>
                    </button>
                    <button id="mobileMenuBtn" class="text-[#FAFAFA] p-2 rounded-lg hover:bg-[#1a1a3e] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="hidden md:hidden bg-[#0B0B45] border-t border-[#1a1a3e] shadow-xl">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('user.profile') }}" class="block text-[#FAFAFA]/80 text-sm py-2 border-b border-[#1a1a3e] hover:text-[#C9A97C] transition-all">
                    <span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong>
                </a>
                <a href="{{ route('user.notifications') }}" class="block bg-[#1a1a3e] text-[#FAFAFA]/80 px-4 py-3 rounded-lg text-sm font-medium text-center hover:text-[#C9A97C] transition-all" data-ar="الإشعارات" data-en="Notifications">
                    الإشعارات @if($unreadCount > 0)<span class="inline-block mr-1 px-2 py-0.5 rounded-full text-xs font-bold" style="background:linear-gradient(135deg,#C9A97C,#D4A24C);color:#0B0B45;">{{ $unreadCount }}</span>@endif
                </a>
                <a href="{{ route('user.reports.create') }}" class="block btn-add-report px-4 py-3 rounded-lg text-sm font-bold text-center" data-ar="+ إضافة بلاغ" data-en="+ Add Report">+ إضافة بلاغ</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full bg-[#1a1a3e] text-[#FAFAFA]/60 px-4 py-3 rounded-lg text-sm font-medium text-center flex items-center justify-center gap-2 hover:text-[#C9A97C] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-[#0B0B45]" data-ar="بلاغاتي" data-en="My Reports">بلاغاتي</h2>
                <p class="text-[#0B0B45]/60 mt-2" data-ar="جميع البلاغات التي قمت بإضافتها" data-en="All reports you have submitted">جميع البلاغات التي قمت بإضافتها</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-[#91A68A]/20 border border-[#91A68A]/30 rounded-xl text-[#0B0B45]">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-8">
                <div class="glass-card rounded-xl sm:rounded-2xl p-4 sm:p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#0B0B45]/60 text-xs sm:text-sm" data-ar="إجمالي البلاغات" data-en="Total Reports">إجمالي البلاغات</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#0B0B45] mt-1 sm:mt-2">{{ $totalReports }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-[#0B0B45] to-[#1e293b] rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-xl sm:rounded-2xl p-4 sm:p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#0B0B45]/60 text-xs sm:text-sm" data-ar="قيد الانتظار" data-en="Pending">قيد الانتظار</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#D6B570] mt-1 sm:mt-2">{{ $pendingReports }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-[#D6B570] to-[#C9A97C] rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-xl sm:rounded-2xl p-4 sm:p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#0B0B45]/60 text-xs sm:text-sm" data-ar="قيد المعالجة" data-en="Processing">قيد المعالجة</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#78A9C1] mt-1 sm:mt-2">{{ $processingReports }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-[#78A9C1] to-[#5A8FA8] rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-xl sm:rounded-2xl p-4 sm:p-6 stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#0B0B45]/60 text-xs sm:text-sm" data-ar="مكتملة" data-en="Completed">مكتملة</p>
                            <p class="text-2xl sm:text-3xl font-bold text-[#91A68A] mt-1 sm:mt-2">{{ $completedReports }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-14 sm:h-14 bg-gradient-to-br from-[#91A68A] to-[#7A8F70] rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            @if($reports->count() > 0)
            <div class="mb-6">
                <div class="flex items-center gap-2 flex-wrap">
                    <button onclick="filterReports('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium" data-filter="all" data-ar="الكل" data-en="All">
                        الكل
                    </button>
                    <button onclick="filterReports('pending')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium" data-filter="pending" data-ar="قيد الانتظار" data-en="Pending">
                        قيد الانتظار
                    </button>
                    <button onclick="filterReports('pending_approval')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium" data-filter="pending_approval" data-ar="بانتظار الموافقة" data-en="Pending Approval">
                        بانتظار الموافقة
                    </button>
                    <button onclick="filterReports('processing')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium" data-filter="processing" data-ar="قيد المعالجة" data-en="Processing">
                        قيد المعالجة
                    </button>
                    <button onclick="filterReports('approved')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium" data-filter="approved" data-ar="تمت الموافقة" data-en="Approved">
                        تمت الموافقة
                    </button>
                    <button onclick="filterReports('completed')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium" data-filter="completed" data-ar="مكتمل" data-en="Completed">
                        مكتمل
                    </button>
                </div>
            </div>

            <div id="reports-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $report)
                @php
                $statusBadgeColors = [
                    'pending'    => 'background:#fef9c3; color:#a16207; border-color:#fde68a;',
                    'pending_approval' => 'background:#dbeafe; color:#1d4ed8; border-color:#bfdbfe;',
                    'processing' => 'background:#dbeafe; color:#1d4ed8; border-color:#bfdbfe;',
                    'approved'   => 'background:#dcfce7; color:#15803d; border-color:#bbf7d0;',
                    'completed'  => 'background:#dcfce7; color:#15803d; border-color:#bbf7d0;',
                    'rejected'   => 'background:#fee2e2; color:#b91c1c; border-color:#fecaca;',
                ];
                $statusLabels = [
                    'pending' => ['ar' => 'قيد الانتظار', 'en' => 'Pending'],
                    'pending_approval' => ['ar' => 'بانتظار الموافقة', 'en' => 'Pending Approval'],
                    'processing' => ['ar' => 'قيد المعالجة', 'en' => 'Processing'],
                    'approved' => ['ar' => 'تمت الموافقة', 'en' => 'Approved'],
                    'completed' => ['ar' => 'مكتمل', 'en' => 'Completed'],
                    'rejected' => ['ar' => 'غير مقبول', 'en' => 'Rejected'],
                ];
                $damageBadgeColors = [
                    'minor' => 'background:#dcfce7; color:#15803d; border-color:#bbf7d0;',
                    'low' => 'background:#dcfce7; color:#15803d; border-color:#bbf7d0;',
                    'moderate' => 'background:#fef9c3; color:#a16207; border-color:#fde68a;',
                    'medium' => 'background:#fef9c3; color:#a16207; border-color:#fde68a;',
                    'severe' => 'background:#ffedd5; color:#c2410c; border-color:#fed7aa;',
                    'high' => 'background:#ffedd5; color:#c2410c; border-color:#fed7aa;',
                    'critical' => 'background:#fee2e2; color:#b91c1c; border-color:#fecaca;',
                ];
                $damageLabels = [
                    'minor' => ['ar' => 'طفيف', 'en' => 'Minor'],
                    'low' => ['ar' => 'سليم', 'en' => 'Low'],
                    'moderate' => ['ar' => 'متوسط', 'en' => 'Moderate'],
                    'medium' => ['ar' => 'ضرر جزئي', 'en' => 'Medium'],
                    'severe' => ['ar' => 'شديد', 'en' => 'Severe'],
                    'high' => ['ar' => 'ضرر جزئي', 'en' => 'High'],
                    'critical' => ['ar' => 'ضرر كلي', 'en' => 'Critical'],
                ];
                $damageBarColors = [
                    'minor' => 'from-[#16a34a] to-[#15803d]',
                    'low' => 'from-[#16a34a] to-[#15803d]',
                    'moderate' => 'from-[#eab308] to-[#ca8a04]',
                    'medium' => 'from-[#eab308] to-[#ca8a04]',
                    'severe' => 'from-[#f97316] to-[#ea580c]',
                    'high' => 'from-[#f97316] to-[#ea580c]',
                    'critical' => 'from-[#ef4444] to-[#dc2626]',
                ];
                $damageBarWidths = [
                    'minor' => '10%',
                    'low' => '25%',
                    'moderate' => '40%',
                    'medium' => '50%',
                    'severe' => '65%',
                    'high' => '75%',
                    'critical' => '100%',
                ];
                $damageLevel = $report->ai_damage_level;
                $statusBadgeStyle = $statusBadgeColors[$report->status] ?? 'background:#f3f4f6; color:#374151; border-color:#e5e7eb;';
                $statusLabelData = $statusLabels[$report->status] ?? ['ar' => $report->status, 'en' => $report->status];
                $damageBadgeStyle = $damageBadgeColors[$damageLevel] ?? 'background:#f3f4f6; color:#374151; border-color:#e5e7eb;';
                $damageLabelData = $damageLabels[$damageLevel] ?? ['ar' => 'غير محدد', 'en' => 'Undefined'];
                $damageBarColorClass = $damageBarColors[$damageLevel] ?? 'from-[#0B0B45] to-[#1a1a3e]';
                $damageBarWidthVal = $damageBarWidths[$damageLevel] ?? '0%';
                $hasImages = is_array($report->images) && count($report->images) > 0;
                $hasImage = $report->image_path && $report->image_path !== '';
                @endphp
                <div class="report-card rounded-2xl overflow-hidden animate-in" data-status="{{ $report->status }}" data-stagger="{{ $loop->index }}">
                    @if($hasImages)
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ asset('storage/' . $report->images[0]) }}" alt="صورة البلاغ" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B0B45]/70 to-transparent"></div>
                        <div class="absolute top-3 right-3">
                            <span class="status-badge" style="{{ $statusBadgeStyle }}" data-ar="{{ $statusLabelData['ar'] }}" data-en="{{ $statusLabelData['en'] }}">
                                {{ $statusLabelData['ar'] }}
                            </span>
                        </div>
                        <div class="quick-actions absolute bottom-3 left-3 flex items-center gap-2">
                            <a href="{{ route('user.reports.show', $report) }}" class="w-8 h-8 rounded-lg bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all" title="عرض">
                                <svg class="w-4 h-4 text-[#0B0B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </div>
                    </div>
                    @elseif($hasImage)
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ asset('storage/' . $report->image_path) }}" alt="صورة البلاغ" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B0B45]/70 to-transparent"></div>
                        <div class="absolute top-3 right-3">
                            <span class="status-badge" style="{{ $statusBadgeStyle }}" data-ar="{{ $statusLabelData['ar'] }}" data-en="{{ $statusLabelData['en'] }}">
                                {{ $statusLabelData['ar'] }}
                            </span>
                        </div>
                        <div class="quick-actions absolute bottom-3 left-3 flex items-center gap-2">
                            <a href="{{ route('user.reports.show', $report) }}" class="w-8 h-8 rounded-lg bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all" title="عرض">
                                <svg class="w-4 h-4 text-[#0B0B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="relative h-44 bg-[#E8E6E1]/80 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-[#0B0B45]/10 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <svg class="w-7 h-7 text-[#0B0B45]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-[#0B0B45]/50 text-xs" data-ar="لا توجد صورة" data-en="No Image">لا توجد صورة</p>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="status-badge" style="{{ $statusBadgeStyle }}" data-ar="{{ $statusLabelData['ar'] }}" data-en="{{ $statusLabelData['en'] }}">
                                {{ $statusLabelData['ar'] }}
                            </span>
                        </div>
                        <div class="quick-actions absolute bottom-3 left-3 flex items-center gap-2">
                            <a href="{{ route('user.reports.show', $report) }}" class="w-8 h-8 rounded-lg bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all" title="عرض">
                                <svg class="w-4 h-4 text-[#0B0B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-[#0B0B45] font-semibold text-sm leading-relaxed truncate">{{ Str::limit($report->raw_location, 35) }}</h3>
                                @if($report->ai_location)
                                <p class="text-[#78A9C1] text-xs mt-1 truncate">{{ Str::limit($report->ai_location, 30) }}</p>
                                @endif
                            </div>
                            <span class="text-[#0B0B45]/50 text-xs whitespace-nowrap mr-2">#{{ $report->id }}</span>
                        </div>

                        @if($report->raw_description)
                        <p class="text-[#0B0B45]/70 text-xs mb-4 leading-relaxed line-clamp-2">{{ Str::limit($report->raw_description, 100) }}</p>
                        @endif

                        @if($damageLevel && isset($damageLabels[$damageLevel]))
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[#0B0B45]/60 text-xs" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</span>
                                <span class="damage-badge" style="{{ $damageBadgeStyle }}" data-ar="{{ $damageLabelData['ar'] }}" data-en="{{ $damageLabelData['en'] }}">
                                    {{ $damageLabelData['ar'] }}
                                </span>
                            </div>
                            <div class="w-full bg-[#E8E6E1] rounded-full h-2">
                                <div class="damage-bar h-2 rounded-full bg-gradient-to-r {{ $damageBarColorClass }}" data-width="{{ $damageBarWidthVal }}"></div>
                            </div>
                        </div>
                        @else
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[#0B0B45]/60 text-xs" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</span>
                                <span class="text-[#0B0B45]/50 text-xs" data-ar="غير محدد" data-en="Undefined">غير محدد</span>
                            </div>
                            <div class="w-full bg-[#E8E6E1] rounded-full h-2">
                                <div class="h-2 rounded-full bg-[#C9A97C]/30" style="width: 0%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-[#0B0B45]/8">
                            <div class="flex items-center gap-1.5 text-[#0B0B45]/60 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $report->created_at->format('Y/m/d') }}
                            </div>
                            <a href="{{ route('user.reports.show', $report) }}" class="inline-flex items-center gap-1 text-[#C9A97C] hover:text-[#B08D5F] text-xs font-bold transition-all">
                                <span data-ar="عرض التفاصيل" data-en="View Details">عرض التفاصيل</span>
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
                <svg class="w-16 h-16 text-[#0B0B45]/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-[#0B0B45]/60" data-ar="لا توجد بلاغات بهذه الحالة" data-en="No reports with this status">لا توجد بلاغات بهذه الحالة</p>
            </div>

            @if($reports->lastPage() > 1)
            <div class="mt-8 flex justify-center">
                <div class="flex items-center gap-2">
                    @if($reports->onFirstPage())
                    <span class="page-btn px-3 py-2 rounded-lg text-sm text-[#0B0B45]/30 border border-[#0B0B45]/10 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                    @else
                    <a href="{{ $reports->previousPageUrl() }}" class="page-btn px-3 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    @foreach(range(1, $reports->lastPage()) as $page)
                    @if($page == $reports->currentPage())
                    <span class="page-btn px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-[#0B0B45] to-[#4D5A70] text-[#FAFAFA]">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $reports->url($page) }}" class="page-btn px-4 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach

                    @if($reports->hasMorePages())
                    <a href="{{ $reports->nextPageUrl() }}" class="page-btn px-3 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    @else
                    <span class="page-btn px-3 py-2 rounded-lg text-sm text-[#0B0B45]/30 border border-[#0B0B45]/10 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <div class="w-20 h-20 bg-[#0B0B45]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#0B0B45]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-[#0B0B45] text-xl font-bold mb-2" data-ar="لا توجد بلاغات بعد" data-en="No Reports Yet">لا توجد بلاغات بعد</h3>
                    <p class="text-[#0B0B45]/60 text-sm mb-6" data-ar="ابدأ بإضافة بلاغ جديد وسيتم تحليله بالذكاء الاصطناعي" data-en="Start by adding a new report and it will be analyzed by AI">ابدأ بإضافة بلاغ جديد وسيتم تحليله بالذكاء الاصطناعي</p>
                    <a href="{{ route('user.reports.create') }}" class="btn-add-report inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span data-ar="إضافة بلاغ جديد" data-en="Add New Report">إضافة بلاغ جديد</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

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

        document.querySelectorAll('.damage-bar').forEach(bar => {
            const w = bar.dataset.width;
            if (w) bar.style.width = w;
        });
    </script>
    <script>
let currentLang = localStorage.getItem('lang') || 'ar';
function applyLanguage(lang) {
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    document.querySelectorAll('#langText').forEach(function(el) {
        el.textContent = lang === 'ar' ? 'English' : 'العربية';
    });
    document.querySelectorAll('[data-' + lang + ']').forEach(function(el) {
        el.textContent = el.getAttribute('data-' + lang);
    });
}
applyLanguage(currentLang);
document.querySelectorAll('#langToggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        currentLang = currentLang === 'ar' ? 'en' : 'ar';
        localStorage.setItem('lang', currentLang);
        applyLanguage(currentLang);
    });
});
    </script>
</body>
</html>
