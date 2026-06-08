<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ar="إضافة بلاغ جديد - نظام تقييم الأضرار" data-en="New Report - Damage Assessment System">إضافة بلاغ جديد - نظام تقييم الأضرار</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body {
            background-color: #F5F1EB;
            color: #0B0B45;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
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
        #map { height: 400px; border-radius: 12px; border: 1px solid rgba(11, 11, 69, 0.1); }
        @media (max-width: 640px) {
            #map { height: 250px; }
        }
        .user-avatar {
            background: linear-gradient(135deg, #C9A97C 0%, #B08D5F 100%);
        }
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
        html[dir="ltr"] .mobile-menu {
            transform: translateX(-100%);
        }
        html[dir="ltr"] .mobile-menu.active {
            transform: translateX(0);
        }
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 49;
        }
        .mobile-overlay.active {
            display: block;
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
    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp

    <nav style="background-color: #0B0B45 !important;" class="fixed w-full z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
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
                    <a href="{{ route('user.profile') }}" class="text-[#FAFAFA]/70 text-sm whitespace-nowrap hover:text-[#C9A97C] transition-all"><span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong></a>
                    <a href="{{ route('user.reports.create') }}" class="bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] px-4 py-2 rounded-lg text-sm font-bold inline-flex items-center gap-1.5 hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span data-ar="+ إضافة بلاغ" data-en="+ Add Report">+ إضافة بلاغ</span>
                    </a>
                    <button id="langToggleDesktop" class="px-3 py-1.5 rounded-lg text-xs font-bold lang-toggle" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                        <span id="langTextDesktop" class="lang-text">English</span>
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

                <div class="flex md:hidden items-center gap-2">
                    <a href="{{ route('user.notifications') }}" class="nav-icon-btn" title="الإشعارات">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <button id="mobileMenuBtn" class="text-[#FAFAFA] p-2 rounded-lg hover:bg-[#1a1a3e] transition-colors" aria-label="القائمة">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div id="mobileOverlay" class="mobile-overlay"></div>

    <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 w-72 h-full bg-[#0B0B45] z-50 p-6 md:hidden shadow-2xl overflow-y-auto">
        <button id="closeMobileMenu" class="absolute top-4 left-4 text-[#FAFAFA]/70 hover:text-[#FAFAFA]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="mt-12 space-y-3">
            <a href="{{ route('user.profile') }}" class="block text-[#FAFAFA]/80 text-sm py-2 border-b border-[#FAFAFA]/10 mb-4 hover:text-[#C9A97C] transition-all">
                <span data-ar="مرحباً،" data-en="Hello,">مرحباً،</span> <strong class="text-[#FAFAFA]">{{ auth()->user()->name }}</strong>
            </a>
            <a href="{{ route('user.notifications') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl text-[#FAFAFA]/80 hover:text-[#FAFAFA] hover:bg-[#1a1a3e] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span data-ar="الإشعارات" data-en="Notifications">الإشعارات</span>
                @if($unreadCount > 0)
                <span class="inline-flex items-center justify-center w-5 h-5 text-[9px] font-bold rounded-full bg-gradient-to-r from-[#C9A97C] to-[#D4A24C] text-[#0B0B45]">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('user.reports.create') }}" class="block py-3 px-4 rounded-xl bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] font-bold text-center" data-ar="+ إضافة بلاغ" data-en="+ Add Report">+ إضافة بلاغ</a>
            <div class="pt-2">
                <button id="langToggleMobile" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-left lang-toggle" style="background: rgba(201,169,124,0.15); color: #C9A97C;">
                    <span id="langTextMobile" class="lang-text">English</span>
                </button>
            </div>
            <hr class="border-[#FAFAFA]/10 my-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-right py-3 px-4 rounded-xl text-[#FAFAFA]/80 hover:text-[#FAFAFA] hover:bg-[#1a1a3e] transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </div>

    <div class="pt-24 pb-12 px-4 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#C9A97C] to-[#B08D5F] rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-[#0B0B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-[#0B0B45]" data-ar="إضافة بلاغ جديد" data-en="New Report">إضافة بلاغ جديد</h2>
                    <p class="text-[#0B0B45]/60 mt-2" data-ar="أدخل تفاصيل البلاغ - سيتم تحليل الصور وتحديد مستوى الضرر تلقائياً بالذكاء الاصطناعي" data-en="Enter report details - images will be analyzed and damage level determined automatically with AI">أدخل تفاصيل البلاغ - سيتم تحليل الصور وتحديد مستوى الضرر تلقائياً بالذكاء الاصطناعي</p>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 bg-[#9C5D4D]/10 border border-[#9C5D4D]/20 rounded-xl text-[#9C5D4D] text-sm">
                    @foreach($errors->all() as $error)
                    <p data-ar="يرجى تصحيح الأخطاء أدناه" data-en="Please correct the errors below">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('user.reports.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="الموقع *" data-en="Location *">الموقع *</label>
                            <input type="text" name="raw_location" value="{{ old('raw_location') }}" required
                                class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 focus:outline-none focus:ring-2 focus:ring-[#C9A97C]/50 transition-all"
                                placeholder="مثال: دمشق - المزة" data-ar-placeholder="مثال: دمشق - المزة" data-en-placeholder="Example: Damascus - Mezzeh">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="الوصف" data-en="Description">الوصف</label>
                            <textarea name="raw_description" rows="4"
                                class="w-full px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 focus:outline-none focus:ring-2 focus:ring-[#C9A97C]/50 transition-all"
                                placeholder="اكتب وصفاً للأضرار..." data-ar-placeholder="اكتب وصفاً للأضرار..." data-en-placeholder="Describe the damage...">{{ old('raw_description') }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-[#0B0B45]/80" data-ar="الموقع على الخريطة *" data-en="Map Location *">الموقع على الخريطة *</label>
                                <button type="button" id="getLocationBtn" onclick="getCurrentLocation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span data-ar="تحديد موقعي الحالي" data-en="Get My Location">تحديد موقعي الحالي</span>
                                </button>
                            </div>
                            <div id="map" class="mb-2"></div>
                            <div id="locationStatus" class="hidden mb-2 p-3 rounded-xl text-sm font-medium"></div>
                            <p class="text-[#0B0B45]/50 text-sm" data-ar="انقر على الخريطة لتحديد الموقع أو استخدم زر تحديد موقعك الحالي" data-en="Click on the map to set location or use the Get My Location button">انقر على الخريطة لتحديد الموقع أو استخدم زر تحديد موقعك الحالي</p>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="الصور" data-en="Images">الصور</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                class="w-full px-4 py-3 input-style rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#0B0B45] file:text-[#FAFAFA] hover:file:bg-[#1a1a3e] transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="ملف PDF" data-en="PDF File">ملف PDF</label>
                            <input type="file" name="pdf_file" accept=".pdf"
                                class="w-full px-4 py-3 input-style rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#0B0B45] file:text-[#FAFAFA] hover:file:bg-[#1a1a3e] transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#0B0B45]/80 mb-2" data-ar="روابط الفيديو" data-en="Video Links">روابط الفيديو</label>
                            <div id="video-links">
                                <div class="flex gap-2 mb-2">
                                    <input type="url" name="video_links[]"
                                        class="flex-1 px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 focus:outline-none focus:ring-2 focus:ring-[#C9A97C]/50 transition-all"
                                        placeholder="https://youtube.com/watch?v=...">
                                </div>
                            </div>
                            <button type="button" onclick="addVideoLink()" class="text-[#C9A97C] hover:text-[#B08D5F] text-sm mt-2 transition-all">
                                <span data-ar="+ إضافة رابط فيديو آخر" data-en="+ Add another video link">+ إضافة رابط فيديو آخر</span>
                            </button>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#C9A97C] to-[#B08D5F] text-[#0B0B45] font-bold rounded-xl hover:from-[#D6B570] hover:to-[#C9A97C] transition-all shadow-lg shadow-[#C9A97C]/25" data-ar="إرسال البلاغ" data-en="Submit Report">
                            إرسال البلاغ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([33.5, 36.3], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;
        function setMarker(lat, lng, zoom) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], zoom || 15);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
        });

        function getCurrentLocation() {
            var btn = document.getElementById('getLocationBtn');
            var statusEl = document.getElementById('locationStatus');
            var lang = localStorage.getItem('lang') || 'ar';

            if (!navigator.geolocation) {
                statusEl.className = 'mb-2 p-3 rounded-xl text-sm font-medium bg-[#fee2e2] text-[#b91c1c]';
                statusEl.textContent = lang === 'ar' ? 'متصفحك لا يدعم تحديد الموقع الجغرافي' : 'Your browser does not support geolocation';
                statusEl.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.style.opacity = '0.6';
            statusEl.className = 'mb-2 p-3 rounded-xl text-sm font-medium bg-[#dbeafe] text-[#1d4ed8]';
            statusEl.textContent = lang === 'ar' ? 'جارٍ تحديد موقعك الحالي...' : 'Detecting your current location...';
            statusEl.classList.remove('hidden');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    setMarker(lat, lng, 16);

                    statusEl.className = 'mb-2 p-3 rounded-xl text-sm font-medium bg-[#dcfce7] text-[#15803d]';
                    statusEl.innerHTML = '<svg style="display:inline;vertical-align:middle;width:16px;height:16px;margin-left:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ' + (lang === 'ar' ? 'تم تحديد موقعك بنجاح' : 'Location detected successfully');

                    var locInput = document.querySelector('input[name="raw_location"]');
                    if (locInput && !locInput.value) {
                        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=' + (lang === 'ar' ? 'ar' : 'en'))
                            .then(function(r) { return r.json(); })
                            .then(function(data) {
                                if (data && data.display_name) {
                                    locInput.value = data.display_name;
                                }
                            })
                            .catch(function() {});
                    }

                    btn.disabled = false;
                    btn.style.opacity = '1';
                },
                function(error) {
                    var msg = lang === 'ar' ? 'تعذر تحديد موقعك' : 'Unable to detect your location';
                    if (error.code === 1) msg = lang === 'ar' ? 'تم رفض إذن تحديد الموقع' : 'Location permission denied';
                    else if (error.code === 2) msg = lang === 'ar' ? 'الموقع غير متاح' : 'Location unavailable';
                    else if (error.code === 3) msg = lang === 'ar' ? 'انتهت مهلة تحديد الموقع' : 'Location request timed out';

                    statusEl.className = 'mb-2 p-3 rounded-xl text-sm font-medium bg-[#fee2e2] text-[#b91c1c]';
                    statusEl.textContent = msg;
                    btn.disabled = false;
                    btn.style.opacity = '1';
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        function addVideoLink() {
            var div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = '<input type="url" name="video_links[]" class="flex-1 px-4 py-3 input-style rounded-xl placeholder-[#0B0B45]/40 focus:outline-none focus:ring-2 focus:ring-[#C9A97C]/50 transition-all" placeholder="https://youtube.com/watch?v=...">';
            document.getElementById('video-links').appendChild(div);
        }
    </script>

    <script>
    (function() {
        var mobileMenu = document.getElementById('mobileMenu');
        var mobileOverlay = document.getElementById('mobileOverlay');
        var mobileMenuBtn = document.getElementById('mobileMenuBtn');
        var closeMobileMenuBtn = document.getElementById('closeMobileMenu');

        function openMobileMenu() {
            mobileMenu.classList.add('active');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }
        if (closeMobileMenuBtn) {
            closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
        }
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenu);
        }

        var menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', closeMobileMenu);
        });
    })();

    let currentLang = localStorage.getItem('lang') || 'ar';
    function applyLanguage(lang) {
        document.documentElement.setAttribute('lang', lang);
        document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
        document.querySelectorAll('.lang-text').forEach(function(el) {
            el.textContent = lang === 'ar' ? 'English' : 'العربية';
        });
        document.querySelectorAll('[data-' + lang + ']').forEach(function(el) {
            var newText = el.getAttribute('data-' + lang);
            var hasChildElements = false;
            for (var i = 0; i < el.childNodes.length; i++) {
                if (el.childNodes[i].nodeType === 1) {
                    hasChildElements = true;
                    break;
                }
            }
            if (hasChildElements) {
                for (var i = 0; i < el.childNodes.length; i++) {
                    if (el.childNodes[i].nodeType === 3 && el.childNodes[i].textContent.trim() !== '') {
                        el.childNodes[i].textContent = newText;
                        break;
                    }
                }
            } else {
                el.textContent = newText;
            }
        });
        document.querySelectorAll('[data-' + lang + '-placeholder]').forEach(el => {
            el.setAttribute('placeholder', el.getAttribute('data-' + lang + '-placeholder'));
        });
        var mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            if (lang === 'en') {
                mobileMenu.style.right = 'auto';
                mobileMenu.style.left = '0';
            } else {
                mobileMenu.style.left = 'auto';
                mobileMenu.style.right = '0';
            }
        }
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