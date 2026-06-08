<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل البلاغ - نظام تقييم الأضرار</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background-color: #F3F2EF; color: #0B0B45; }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(11, 11, 69, 0.08);
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
        }
        .info-box {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(11, 11, 69, 0.08);
        }
        .section-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .damage-bar-bg {
            background: #E8E6E1;
            border-radius: 9999px;
            height: 8px;
        }
        .damage-bar-fill {
            height: 8px;
            border-radius: 9999px;
            transition: width 0.5s ease;
        }
        .image-card {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(11, 11, 69, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .image-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(11, 11, 69, 0.12);
        }
        .image-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        #map {
            height: 300px;
            border-radius: 16px;
            border: 1px solid rgba(11, 11, 69, 0.08);
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
        .btn-back {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(11, 11, 69, 0.1);
            color: #0B0B45;
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: #C9A97C;
            color: white;
            border-color: #C9A97C;
        }
        .pdf-btn {
            background: linear-gradient(135deg, #C9A97C 0%, #B08D5F 100%);
            color: #0B0B45;
            transition: all 0.2s ease;
        }
        .pdf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201, 169, 124, 0.35);
        }
        .video-link {
            background: rgba(120, 169, 193, 0.08);
            border: 1px solid rgba(120, 169, 193, 0.2);
            color: #78A9C1;
            transition: all 0.2s ease;
        }
        .video-link:hover {
            background: rgba(120, 169, 193, 0.15);
            border-color: #78A9C1;
        }
    </style>
</head>
<body class="min-h-screen">
    <nav style="background-color: #0B0B45 !important;" class="fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-[#FAFAFA]" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment System">نظام تقييم الأضرار</h1>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                    <a href="{{ route('user.profile') }}" class="text-[#FAFAFA]/70 text-sm whitespace-nowrap hover:text-[#C9A97C] transition-all"><span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong></a>
                    <a href="{{ route('user.reports.create') }}" class="bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-2 rounded-lg text-sm font-bold inline-flex items-center gap-1.5 hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-md" data-ar="+ إضافة بلاغ" data-en="+ Add Report">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        + إضافة بلاغ
                    </a>
                    <button class="lang-toggle px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="lang-text">English</span>
                    </button>
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
                    <button class="lang-toggle px-2.5 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="lang-text">English</span>
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
                <a href="{{ route('user.profile') }}" class="block text-[#FAFAFA]/80 text-sm py-2 border-b border-[#1a1a3e] hover:text-[#C9A97C] transition-colors">
                    <span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong>
                </a>
                <a href="{{ route('user.reports.create') }}" class="block bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-3 rounded-lg text-sm font-bold text-center shadow-md" data-ar="+ إضافة بلاغ" data-en="+ Add Report">+ إضافة بلاغ</a>
                <a href="{{ route('user.notifications') }}" class="flex items-center gap-2 bg-[#1a1a3e] text-[#FAFAFA]/60 px-4 py-3 rounded-lg text-sm font-medium hover:text-[#C9A97C] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span data-ar="الإشعارات" data-en="Notifications">الإشعارات</span>
                    @if($unreadCount > 0)
                    <span class="bg-[#C9A97C] text-[#0B0B45] text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                <div class="flex justify-center">
                    <button class="lang-toggle px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="lang-text">English</span>
                    </button>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full bg-[#1a1a3e] text-[#FAFAFA]/60 px-4 py-3 rounded-lg text-sm font-medium text-center flex items-center justify-center gap-2 hover:text-[#C9A97C] transition-colors" data-ar="تسجيل الخروج" data-en="Logout">
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
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
            <div class="mb-6 p-4 bg-[#91A68A]/20 border border-[#91A68A]/30 rounded-xl text-[#0B0B45]">
                {{ session('success') }}
            </div>
            @endif

            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                <a href="{{ route('user.reports') }}" class="btn-back inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span data-ar="العودة لبلاغاتي" data-en="Back to Reports">العودة لبلاغاتي</span>
                </a>
                @php
                $statusColors = [
                    'pending' => 'background:#fef9c3;color:#a16207;border:1px solid #fde047',
                    'pending_approval' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #93c5fd',
                    'processing' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #93c5fd',
                    'approved' => 'background:#dcfce7;color:#15803d;border:1px solid #86efac',
                    'completed' => 'background:#dcfce7;color:#15803d;border:1px solid #86efac',
                    'rejected' => 'background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5',
                ];
                $statusLabels = [
                    'pending' => ['ar' => 'قيد الانتظار', 'en' => 'Pending'],
                    'pending_approval' => ['ar' => 'بانتظار الموافقة', 'en' => 'Pending Approval'],
                    'processing' => ['ar' => 'قيد المعالجة', 'en' => 'Processing'],
                    'approved' => ['ar' => 'تمت الموافقة', 'en' => 'Approved'],
                    'completed' => ['ar' => 'مكتمل', 'en' => 'Completed'],
                    'rejected' => ['ar' => 'غير مقبول', 'en' => 'Rejected'],
                ];
                @endphp
                <span class="px-4 py-2 rounded-xl text-sm font-medium"
                      style="{{ $statusColors[$report->status] ?? 'background:#f3f4f6;color:#4b5563;border:1px solid #d1d5db' }}"
                      data-ar="{{ $statusLabels[$report->status]['ar'] ?? $report->status }}"
                      data-en="{{ $statusLabels[$report->status]['en'] ?? $report->status }}">
                    {{ $statusLabels[$report->status]['ar'] ?? $report->status }}
                </span>
            </div>

            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl mb-6">
                <div class="flex items-center gap-4 mb-6 pb-5 border-b border-[#0B0B45]/10">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-xl flex items-center justify-center shadow-lg shadow-[#C9A97C]/25 shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-[#0B0B45]" data-ar="تفاصيل البلاغ #{{ $report->id }}" data-en="Report Details #{{ $report->id }}">تفاصيل البلاغ #{{ $report->id }}</h2>
                        <p class="text-[#0B0B45]/50 text-sm mt-0.5">{{ $report->created_at->format('Y/m/d - H:i') }}</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="section-icon bg-[#C9A97C]/15">
                                <svg class="w-5 h-5 text-[#C9A97C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="الموقع" data-en="Location">الموقع</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mr-8 sm:mr-12">
                            <div class="info-box rounded-xl p-4">
                                <p class="text-xs text-[#0B0B45]/40 mb-1" data-ar="الموقع الأصلي" data-en="Original Location">الموقع الأصلي</p>
                                <p class="text-[#0B0B45] font-medium text-sm">{{ $report->raw_location }}</p>
                            </div>
                            <div class="info-box rounded-xl p-4">
                                <p class="text-xs text-[#0B0B45]/40 mb-1" data-ar="الموقع المحلل (AI)" data-en="AI Location">الموقع المحلل (AI)</p>
                                @if($report->ai_location)
                                <p class="text-[#78A9C1] font-medium text-sm">{{ $report->ai_location }}</p>
                                @else
                                <p class="text-[#78A9C1] font-medium text-sm" data-ar="لم يتم التحليل بعد" data-en="Not yet analyzed">لم يتم التحليل بعد</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($report->raw_description)
                    <div>
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="section-icon bg-[#78A9C1]/15">
                                <svg class="w-5 h-5 text-[#78A9C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="الوصف" data-en="Description">الوصف</h3>
                        </div>
                        <div class="info-box rounded-xl p-4 mr-8 sm:mr-12">
                            <p class="text-[#0B0B45]/80 text-sm leading-relaxed">{{ $report->raw_description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($report->ai_analysis)
                    <div>
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="section-icon bg-[#91A68A]/15">
                                <svg class="w-5 h-5 text-[#91A68A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="تحليل الذكاء الاصطناعي" data-en="AI Analysis">تحليل الذكاء الاصطناعي</h3>
                        </div>
                        <div class="info-box rounded-xl p-4 mr-8 sm:mr-12">
                            <p class="text-[#0B0B45]/80 text-sm leading-relaxed">{{ $report->ai_analysis }}</p>
                        </div>
                    </div>
                    @endif

                    <div>
                        <div class="flex items-center gap-3 mb-2.5">
                            <div class="section-icon bg-[#9C5D4D]/15">
                                <svg class="w-5 h-5 text-[#9C5D4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</h3>
                        </div>
                        <div class="info-box rounded-xl p-4 mr-8 sm:mr-12">
                            @php
                            $damageLabels = [
                                'minor' => ['ar' => 'طفيف', 'en' => 'Minor'],
                                'low' => ['ar' => 'ضرر طفيف', 'en' => 'Low'],
                                'moderate' => ['ar' => 'متوسط', 'en' => 'Moderate'],
                                'medium' => ['ar' => 'ضرر متوسط', 'en' => 'Medium'],
                                'severe' => ['ar' => 'شديد', 'en' => 'Severe'],
                                'high' => ['ar' => 'ضرر كبير', 'en' => 'High'],
                                'critical' => ['ar' => 'ضرر كلي', 'en' => 'Critical'],
                            ];
                            $damageColors = [
                                'minor' => 'background:#dcfce7;color:#15803d;border:1px solid #86efac',
                                'low' => 'background:#dcfce7;color:#15803d;border:1px solid #86efac',
                                'moderate' => 'background:#fef9c3;color:#a16207;border:1px solid #fde047',
                                'medium' => 'background:#fef9c3;color:#a16207;border:1px solid #fde047',
                                'severe' => 'background:#ffedd5;color:#c2410c;border:1px solid #fdba74',
                                'high' => 'background:#ffedd5;color:#c2410c;border:1px solid #fdba74',
                                'critical' => 'background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5',
                            ];
                            $damageBarGradients = [
                                'minor' => 'linear-gradient(90deg, #86efac, #22c55e)',
                                'low' => 'linear-gradient(90deg, #86efac, #22c55e)',
                                'moderate' => 'linear-gradient(90deg, #fde047, #eab308)',
                                'medium' => 'linear-gradient(90deg, #fde047, #eab308)',
                                'severe' => 'linear-gradient(90deg, #fdba74, #f97316)',
                                'high' => 'linear-gradient(90deg, #fdba74, #f97316)',
                                'critical' => 'linear-gradient(90deg, #fca5a5, #ef4444)',
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
                            $level = $report->ai_damage_level;
                            @endphp
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-[#0B0B45]" data-ar="{{ $damageLabels[$level]['ar'] ?? 'غير محدد' }}" data-en="{{ $damageLabels[$level]['en'] ?? 'Undefined' }}">{{ $damageLabels[$level]['ar'] ?? 'غير محدد' }}</span>
                                @if($level && isset($damageColors[$level]))
                                <span class="px-3 py-1 rounded-full text-xs font-medium" style="{{ $damageColors[$level] }}">
                                    {{ $level }}
                                </span>
                                @else
                                <span class="text-[#0B0B45]/40 text-xs" data-ar="غير محدد" data-en="Undefined">غير محدد</span>
                                @endif
                            </div>
                            <div class="damage-bar-bg">
                                <div class="damage-bar-fill" style="width: {{ $damageBarWidths[$level] ?? '0%' }}; background: {{ $damageBarGradients[$level] ?? 'linear-gradient(90deg, #E8E6E1, #E8E6E1)' }};"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="section-icon bg-[#C9A97C]/15">
                        <svg class="w-5 h-5 text-[#C9A97C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="الموقع على الخريطة" data-en="Map Location">الموقع على الخريطة</h3>
                </div>
                @if($report->latitude && $report->longitude)
                <div id="map"></div>
                @else
                <div id="map" style="display:flex;align-items:center;justify-content:center;height:300px;border-radius:16px;border:1px solid rgba(11,11,69,0.08);background:rgba(255,255,255,0.6);">
                    <p style="text-align:center;padding:48px 0;color:#0B0B4540;" data-ar="الموقع غير محدد" data-en="Location not specified">الموقع غير محدد</p>
                </div>
                @endif
            </div>

            @if($report->images && count($report->images) > 0)
            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="section-icon bg-[#78A9C1]/15">
                        <svg class="w-5 h-5 text-[#78A9C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="الصور المرفقة" data-en="Attached Images">الصور المرفقة</h3>
                    <span class="mr-auto text-xs text-[#0B0B45]/40 bg-[#0B0B45]/5 px-2.5 py-1 rounded-full">{{ count($report->images) }} <span data-ar="صورة" data-en="Images">صورة</span></span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($report->images as $image)
                    <div class="image-card">
                        <img src="{{ asset('storage/' . $image) }}" alt="Report Image">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($report->pdf_file)
            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="section-icon bg-[#9C5D4D]/15">
                        <svg class="w-5 h-5 text-[#9C5D4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="ملف PDF" data-en="PDF File">ملف PDF</h3>
                </div>
                <a href="{{ asset('storage/' . $report->pdf_file) }}" target="_blank" class="pdf-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span data-ar="تحميل الملف" data-en="Download File">تحميل الملف</span>
                </a>
            </div>
            @endif

            @if($report->video_links && count($report->video_links) > 0)
            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-5">
                    <div class="section-icon bg-[#0B0B45]/10">
                        <svg class="w-5 h-5 text-[#0B0B45]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#0B0B45]/70 uppercase tracking-wide" data-ar="روابط الفيديو" data-en="Video Links">روابط الفيديو</h3>
                    <span class="mr-auto text-xs text-[#0B0B45]/40 bg-[#0B0B45]/5 px-2.5 py-1 rounded-full">{{ count($report->video_links) }} <span data-ar="رابط" data-en="Links">رابط</span></span>
                </div>
                <div class="space-y-2">
                    @foreach($report->video_links as $link)
                    <a href="{{ $link }}" target="_blank" class="video-link flex items-center gap-3 px-4 py-3 rounded-xl">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="text-sm truncate">{{ $link }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        @if($report->latitude && $report->longitude)
        var map = L.map('map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);
        @endif
    </script>
    <script>
let currentLang = localStorage.getItem('lang') || 'ar';
function applyLanguage(lang) {
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    const langBtns = document.querySelectorAll('.lang-text');
    langBtns.forEach(btn => btn.textContent = lang === 'ar' ? 'English' : 'العربية');
    document.querySelectorAll('[data-' + lang + ']').forEach(el => {
        el.textContent = el.getAttribute('data-' + lang);
    });
    document.title = lang === 'ar' ? 'تفاصيل البلاغ - نظام تقييم الأضرار' : 'Report Details - Damage Assessment System';
}
applyLanguage(currentLang);
document.querySelectorAll('.lang-toggle').forEach(btn => {
    btn.addEventListener('click', function() {
        currentLang = currentLang === 'ar' ? 'en' : 'ar';
        localStorage.setItem('lang', currentLang);
        applyLanguage(currentLang);
    });
});
    </script>
</body>
</html>
