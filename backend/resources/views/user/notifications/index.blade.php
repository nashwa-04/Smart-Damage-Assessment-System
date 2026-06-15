<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإشعارات - نظام تقييم الأضرار</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background-color: #F3F2EF; color: #0B0B45; }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(11, 11, 69, 0.08);
        }
        .notif-item {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(11, 11, 69, 0.08);
            transition: all 0.2s ease;
            position: relative;
        }
        .notif-item.unread {
            border-inline-start: 4px solid #C9A97C;
            background: rgba(201, 169, 124, 0.06);
        }
        .notif-item:hover {
            border-color: rgba(201, 169, 124, 0.4);
            box-shadow: 0 8px 24px rgba(11, 11, 69, 0.08);
            transform: translateY(-2px);
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
        .type-icon {
            width: 38px; height: 38px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .type-icon {
                width: 44px; height: 44px;
            }
        }
        .type-approved { background: #dcfce7; color: #15803d; }
        .type-rejected { background: #fee2e2; color: #b91c1c; }
        .type-assessment { background: #dbeafe; color: #1d4ed8; }
        .type-new_report { background: #fef9c3; color: #a16207; }
        .type-status { background: #E8E6E1; color: #0B0B45; }
        .type-reminder { background: #ffedd5; color: #c2410c; }
        .mark-all-btn {
            background: linear-gradient(135deg, #0B0B45, #1a1a3e);
            color: #FAFAFA;
            transition: all 0.2s ease;
        }
        .mark-all-btn:hover {
            background: linear-gradient(135deg, #C9A97C, #B08D5F);
            color: #0B0B45;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.3s ease forwards; }
    </style>
</head>
<body class="min-h-screen">
    <nav style="background-color: #0B0B45 !important;" class="border-b border-[#0B0B45]/20 fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.reports') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-[#FAFAFA] hidden sm:block" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment System">نظام تقييم الأضرار</h1>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-3">
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
                    <a href="{{ route('user.notifications') }}" class="nav-icon-btn relative" title="الإشعارات">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <button class="lang-toggle px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="lang-text">English</span>
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
                <a href="{{ route('user.profile') }}" class="block text-[#FAFAFA]/80 text-sm py-2 border-b border-[#1a1a3e] hover:text-[#C9A97C] transition-colors">
                    <span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong>
                </a>
                <a href="{{ route('user.notifications') }}" class="block text-[#FAFAFA]/70 px-4 py-3 rounded-lg text-sm font-medium text-center flex items-center justify-center gap-2 hover:text-[#C9A97C] transition-colors border border-[#1a1a3e]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span data-ar="الإشعارات" data-en="Notifications">الإشعارات</span>
                    @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-gradient-to-r from-[#C9A97C] to-[#D4A24C] text-[#0B0B45]">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('user.reports.create') }}" class="block bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-3 rounded-lg text-sm font-bold text-center" data-ar="+ إضافة بلاغ" data-en="+ Add Report">+ إضافة بلاغ</a>
                <button class="lang-toggle w-full px-4 py-3 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-2 transition-colors" style="background: rgba(201,169,124,0.15); color: #C9A97C; border: 1px solid rgba(201,169,124,0.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
                    <span class="lang-text">English</span>
                </button>
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
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-8">
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-[#0B0B45]" data-ar="الإشعارات" data-en="Notifications">الإشعارات</h2>
                    <p class="text-[#0B0B45]/60 mt-2 text-sm sm:text-base" data-ar="جميع التحديثات والتنبيهات الخاصة ببلاغاتك" data-en="All updates and alerts regarding your reports">جميع التحديثات والتنبيهات الخاصة ببلاغاتك</p>
                </div>
                @if($unreadCount > 0)
                <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="mark-all-btn px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span data-ar="قراءة الكل" data-en="Mark All Read">قراءة الكل</span>
                    </button>
                </form>
                @endif
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-[#91A68A]/20 border border-[#91A68A]/30 rounded-xl text-[#0B0B45]">
                {{ session('success') }}
            </div>
            @endif

            @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notif)
                @php
                $typeClass = match($notif->type) {
                    'approved' => 'type-approved',
                    'rejected' => 'type-rejected',
                    'assessment' => 'type-assessment',
                    'new_report' => 'type-new_report',
                    'status' => 'type-status',
                    'reminder' => 'type-reminder',
                    default => 'type-status',
                };
                $typeIcon = match($notif->type) {
                    'approved' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'rejected' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    'assessment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
                    'new_report' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                    'reminder' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                };
                @endphp
                <a href="{{ route('user.notifications.read', $notif) }}" class="notif-item {{ $notif->is_read ? '' : 'unread' }} rounded-xl p-3 sm:p-4 flex items-start gap-3 sm:gap-4 animate-in" style="text-decoration: none; display: flex;" data-stagger="{{ $loop->index }}">
                    <div class="type-icon {{ $typeClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $typeIcon !!}
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-xs sm:text-sm font-bold text-[#0B0B45]" data-ar="{{ $notif->title_ar }}" data-en="{{ $notif->title_en }}">{{ $notif->title_ar }}</h3>
                            @if(!$notif->is_read)
                            <span class="w-2.5 h-2.5 rounded-full bg-[#C9A97C] flex-shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-[#0B0B45]/70 text-xs sm:text-sm leading-relaxed" data-ar="{{ $notif->message_ar }}" data-en="{{ $notif->message_en }}">{{ $notif->message_ar }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[#0B0B45]/40 text-xs flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                            @if($notif->report_id)
                            <span class="text-[#C9A97C] text-xs font-medium">#{{ $notif->report_id }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($notifications->lastPage() > 1)
            <div class="mt-8 flex justify-center">
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    @if($notifications->onFirstPage())
                    <span class="px-3 py-2 rounded-lg text-sm text-[#0B0B45]/30 border border-[#0B0B45]/10 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                    @else
                    <a href="{{ $notifications->previousPageUrl() }}" class="px-3 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    @foreach(range(1, $notifications->lastPage()) as $page)
                    @if($page == $notifications->currentPage())
                    <span class="px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-[#0B0B45] to-[#4D5A70] text-[#FAFAFA]">{{ $page }}</span>
                    @else
                    <a href="{{ $notifications->url($page) }}" class="px-4 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">{{ $page }}</a>
                    @endif
                    @endforeach

                    @if($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}" class="px-3 py-2 rounded-lg text-sm text-[#0B0B45] border border-[#0B0B45]/15 hover:border-[#C9A97C] hover:text-[#C9A97C] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    @else
                    <span class="px-3 py-2 rounded-lg text-sm text-[#0B0B45]/30 border border-[#0B0B45]/10 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            @else
            <div class="glass-card rounded-2xl p-4 sm:p-8 lg:p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <div class="w-20 h-20 bg-[#0B0B45]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#0B0B45]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-[#0B0B45] text-lg sm:text-xl font-bold mb-2" data-ar="لا توجد إشعارات" data-en="No Notifications">لا توجد إشعارات</h3>
                    <p class="text-[#0B0B45]/60 text-sm" data-ar="ستظهر هنا جميع التحديثات المتعلقة ببلاغاتك" data-en="All updates related to your reports will appear here">ستظهر هنا جميع التحديثات المتعلقة ببلاغاتك</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
    <script>
    let currentLang = localStorage.getItem('lang') || 'ar';
    function applyLanguage(lang) {
        document.documentElement.setAttribute('lang', lang);
        document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
        document.querySelectorAll('.lang-text').forEach(el => {
            el.textContent = lang === 'ar' ? 'English' : 'العربية';
        });
        document.querySelectorAll('[data-' + lang + ']').forEach(el => {
            el.textContent = el.getAttribute('data-' + lang);
        });
        document.title = lang === 'ar' ? 'الإشعارات - نظام تقييم الأضرار' : 'Notifications - Damage Assessment System';
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
