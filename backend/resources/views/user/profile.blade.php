<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - نظام تقييم الأضرار</title>
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
        }
        .input-style {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(11, 11, 69, 0.15);
            color: #0B0B45;
        }
        .input-style:focus {
            background: #ffffff;
            border-color: #C9A97C;
            outline: none;
            box-shadow: 0 0 0 3px rgba(201, 169, 124, 0.2);
        }
        .input-style::placeholder {
            color: rgba(11, 11, 69, 0.4);
        }
        .mobile-menu { transform: translateX(100%); transition: transform 0.3s ease; }
        .mobile-menu.active { transform: translateX(0); }
        .mobile-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .mobile-overlay.active { opacity: 1; pointer-events: auto; }
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
    </style>
</head>
<body class="min-h-screen">
    <nav style="background-color: #0B0B45 !important;" class="border-b border-[#0B0B45]/20 fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
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
                    <a href="{{ route('user.reports.create') }}" class="bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-2 rounded-lg text-sm font-bold inline-flex items-center gap-1.5 hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-md" data-ar="+ إضافة بلاغ" data-en="+ Add Report">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        + إضافة بلاغ
                    </a>
                    <button class="lang-toggle px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
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
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-icon-btn" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-2 md:hidden">
                    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                    <a href="{{ route('user.notifications') }}" class="nav-icon-btn" title="الإشعارات">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <button id="mobileMenuBtn" class="text-[#FAFAFA] p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobileOverlay" class="mobile-overlay md:hidden"></div>
        <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 w-72 h-full bg-[#0B0B45] z-50 p-6 md:hidden shadow-2xl">
            <button id="closeMobileMenu" class="absolute top-4 left-4 text-[#FAFAFA]/60 hover:text-[#FAFAFA]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="mt-12 space-y-3">
                <a href="{{ route('user.profile') }}" class="block text-[#FAFAFA]/80 text-sm py-2 border-b border-[#FAFAFA]/10 mb-4 hover:text-[#C9A97C] transition-all">
                    <span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong>
                </a>
                <a href="{{ route('user.reports.create') }}" class="block text-center py-2.5 bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] rounded-xl text-sm font-bold" data-ar="+ إضافة بلاغ" data-en="+ New Report">+ إضافة بلاغ</a>
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                <a href="{{ route('user.notifications') }}" class="flex items-center gap-2 py-2.5 text-[#FAFAFA]/80 hover:text-[#C9A97C] rounded-xl transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span data-ar="الإشعارات" data-en="Notifications">الإشعارات</span>
                    @if($unreadCount > 0)
                    <span class="notif-badge relative" style="position:relative; top:auto; right:auto;">{{ $unreadCount }}</span>
                    @endif
                </a>
                <hr class="border-[#FAFAFA]/10">
                <div class="flex justify-center">
                    <button class="lang-toggle px-3 py-1.5 rounded-lg text-xs font-bold" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span class="lang-text">English</span>
                    </button>
                </div>
                <hr class="border-[#FAFAFA]/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center py-2.5 text-[#FAFAFA]/80 hover:text-[#FAFAFA] hover:bg-[#1a1a3e] rounded-xl transition-all text-sm" data-ar="تسجيل الخروج" data-en="Sign Out">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 min-h-screen">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('user.dashboard') }}" class="w-10 h-10 rounded-xl flex items-center justify-center hover:opacity-80 transition-colors shrink-0" style="background: rgba(201, 169, 124, 0.1);">
                    <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#0B0B45]" data-ar="الملف الشخصي" data-en="Profile">الملف الشخصي</h2>
                    <p class="text-[#0B0B45]/60 mt-0.5 text-sm" data-ar="إدارة معلومات حسابك" data-en="Manage your account info">إدارة معلومات حسابك</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-[#91A68A]/20 border border-[#91A68A]/30 rounded-xl text-[#0B0B45]">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-[#9C5D4D]/10 border border-[#9C5D4D]/20 rounded-xl text-[#9C5D4D] text-sm">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 mb-6">
                <div class="flex flex-col items-center mb-8 pb-6 border-b border-[#0B0B45]/10">
                    <div class="relative group">
                        @if(auth()->user()->profile_image)
                            <img id="profile-avatar" src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}"
                                class="w-28 h-28 rounded-full object-cover border-4 border-[#C9A97C]/50">
                        @else
                            <div id="profile-avatar" class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-[#C9A97C]/50">
                                <span class="text-4xl font-bold text-[#78A9C1]">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <label for="profile_image" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-100 sm:opacity-0 sm:group-hover:opacity-100 cursor-pointer transition-opacity">
                            <svg class="w-7 h-7 text-[#C9A97C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-[#0B0B45]">{{ auth()->user()->name }}</h3>
                        <p class="text-[#0B0B45]/60 text-sm">{{ auth()->user()->email }}</p>
                        <p class="text-[#0B0B45]/50 text-xs mt-1"><span data-ar="عضو منذ" data-en="Member since">عضو منذ</span> {{ auth()->user()->created_at->format('Y/m/d') }}</p>
                    </div>
                </div>

                <form id="profileForm" method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="الاسم" data-en="Name">الاسم</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 transition-all"
                                data-ar-placeholder="أدخل اسمك" data-en-placeholder="Enter your name"
                                placeholder="أدخل اسمك">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 transition-all"
                                data-ar-placeholder="أدخل بريدك الإلكتروني" data-en-placeholder="Enter your email"
                                placeholder="أدخل بريدك الإلكتروني">
                        </div>

                        <div class="border-t border-[#0B0B45]/10 pt-6">
                            <h4 class="text-[#0B0B45] font-semibold mb-4" data-ar="تغيير كلمة المرور" data-en="Change Password">تغيير كلمة المرور</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="كلمة المرور الحالية" data-en="Current Password">كلمة المرور الحالية</label>
                                    <input type="password" name="current_password"
                                        class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 transition-all"
                                        data-ar-placeholder="أدخل كلمة المرور الحالية" data-en-placeholder="Enter current password"
                                        placeholder="أدخل كلمة المرور الحالية">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="كلمة المرور الجديدة" data-en="New Password">كلمة المرور الجديدة</label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 transition-all"
                                        data-ar-placeholder="أدخل كلمة المرور الجديدة" data-en-placeholder="Enter new password"
                                        placeholder="أدخل كلمة المرور الجديدة">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="تأكيد كلمة المرور" data-en="Confirm Password">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 transition-all"
                                        data-ar-placeholder="أعد إدخال كلمة المرور الجديدة" data-en-placeholder="Re-enter new password"
                                        placeholder="أعد إدخال كلمة المرور الجديدة">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#FAFAFA] font-semibold rounded-xl hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-lg shadow-[#C9A97C]/25" data-ar="حفظ التغييرات" data-en="Save Changes">
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('user.dashboard') }}" class="px-6 py-3 text-[#0B0B45]/60 hover:text-[#0B0B45] rounded-xl border border-[#0B0B45]/15 hover:border-[#C9A97C] transition-all text-sm" data-ar="إلغاء" data-en="Cancel">
                                إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h4 class="text-[#0B0B45] font-semibold" data-ar="تسجيل الخروج" data-en="Sign Out">تسجيل الخروج</h4>
                        <p class="text-[#0B0B45]/60 text-sm mt-1" data-ar="الخروج من حسابك والعودة لصفحة الدخول" data-en="Sign out of your account and return to login page">الخروج من حسابك والعودة لصفحة الدخول</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-[#0B0B45]/70 hover:text-[#0B0B45] rounded-xl border border-[#0B0B45]/15 hover:border-[#C9A97C] bg-[#0B0B45]/5 hover:bg-[#0B0B45]/10 transition-all text-sm font-medium" data-ar="تسجيل الخروج" data-en="Sign Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span data-ar="تسجيل الخروج" data-en="Sign Out">تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8">
                <h4 class="text-[#9C5D4D] font-semibold mb-2" data-ar="حذف الحساب" data-en="Delete Account">حذف الحساب</h4>
                <p class="text-[#0B0B45]/60 text-sm mb-4" data-ar="سيتم حذف حسابك وجميع بلاغاتك نهائياً" data-en="Your account and all your reports will be permanently deleted">سيتم حذف حسابك وجميع بلاغاتك نهائياً</p>
                <form id="deleteAccountForm" method="POST" action="{{ route('user.profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 sm:gap-4">
                        <div class="flex-1">
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 focus:outline-none focus:ring-2 focus:ring-[#9C5D4D]/30 transition-all"
                                data-ar-placeholder="أدخل كلمة المرور للتأكيد" data-en-placeholder="Enter password to confirm"
                                placeholder="أدخل كلمة المرور للتأكيد">
                        </div>
                        <button type="submit" id="deleteAccountBtn" class="px-6 py-3 bg-[#9C5D4D]/10 text-[#9C5D4D] border border-[#9C5D4D]/30 rounded-xl hover:bg-[#9C5D4D]/20 hover:border-[#9C5D4D] transition-all text-sm font-medium whitespace-nowrap" data-ar="حذف الحساب" data-en="Delete Account">
                            حذف الحساب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var mobileMenu = document.getElementById('mobileMenu');
        var mobileOverlay = document.getElementById('mobileOverlay');
        function openMobileMenu() {
            mobileMenu.classList.add('active');
            mobileOverlay.classList.add('active');
        }
        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            mobileOverlay.classList.remove('active');
        }
        document.getElementById('mobileMenuBtn').addEventListener('click', openMobileMenu);
        document.getElementById('closeMobileMenu').addEventListener('click', closeMobileMenu);
        mobileOverlay.addEventListener('click', closeMobileMenu);
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = input.closest('.relative');
                    const existingImg = container.querySelector('img#profile-avatar');
                    if (existingImg) {
                        existingImg.src = e.target.result;
                    } else {
                        const div = container.querySelector('div#profile-avatar');
                        if (div) {
                            const img = document.createElement('img');
                            img.id = 'profile-avatar';
                            img.src = e.target.result;
                            img.alt = '{{ auth()->user()->name }}';
                            img.className = 'w-28 h-28 rounded-full object-cover border-4 border-[#C9A97C]/50';
                            div.parentNode.replaceChild(img, div);
                        }
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var lang = localStorage.getItem('lang') || 'ar';
            var msg = lang === 'ar'
                ? 'هل أنت متأكد من حذف حسابك؟ لا يمكن التراجع عن هذا الإجراء.'
                : 'Are you sure you want to delete your account? This action cannot be undone.';
            if (confirm(msg)) {
                this.submit();
            }
        });
    </script>
    <script>
        let currentLang = localStorage.getItem('lang') || 'ar';
        function applyLanguage(lang) {
            document.documentElement.setAttribute('lang', lang);
            document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            const langBtns = document.querySelectorAll('.lang-text');
            langBtns.forEach(function(langBtn) {
                langBtn.textContent = lang === 'ar' ? 'English' : 'العربية';
            });
            document.querySelectorAll('[data-' + lang + ']').forEach(function(el) {
                el.textContent = el.getAttribute('data-' + lang);
            });
            document.querySelectorAll('[data-' + lang + '-placeholder]').forEach(function(el) {
                el.setAttribute('placeholder', el.getAttribute('data-' + lang + '-placeholder'));
            });
        }
        applyLanguage(currentLang);
        document.querySelectorAll('.lang-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentLang = currentLang === 'ar' ? 'en' : 'ar';
                localStorage.setItem('lang', currentLang);
                applyLanguage(currentLang);
            });
        });
    </script>
</body>
</html>
