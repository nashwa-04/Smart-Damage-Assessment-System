<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - نظام تقييم الأضرار</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Cairo', sans-serif; }

        .hero-bg { position: relative; overflow-x: hidden; }

        .hero-bg::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(217, 210, 197, 0.95) 0%, rgba(217, 210, 197, 0.85) 30%, rgba(242, 242, 242, 0.9) 100%);
            pointer-events: none; z-index: 0;
        }

        .hero-stone {
            position: absolute; top: 0; left: 0; width: 55%;             height: 100%;
            background: url('/storage/imge/rock.jpg') center center / cover no-repeat;
            filter: contrast(1.2) brightness(0.65) saturate(0.4);
            opacity: 0.4; pointer-events: none; z-index: 1; mix-blend-mode: multiply;
            -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0) 100%),
                              linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.7) 60%, rgba(0,0,0,0) 100%);
            mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0) 100%),
                        linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.7) 60%, rgba(0,0,0,0) 100%);
            -webkit-mask-composite: intersect; mask-composite: intersect;
        }

        .hero-stone-detail {
            position: absolute; top: 0; left: 0; width: 55%; height: 100%;
            pointer-events: none; z-index: 2; opacity: 0.2; mix-blend-mode: overlay;
            -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0) 100%),
                              linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.7) 60%, rgba(0,0,0,0) 100%);
            mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0) 100%),
                        linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.7) 60%, rgba(0,0,0,0) 100%);
            -webkit-mask-composite: intersect; mask-composite: intersect;
            background:
                radial-gradient(ellipse at 15% 25%, rgba(180,160,130,0.3) 0%, transparent 25%),
                radial-gradient(ellipse at 45% 65%, rgba(160,140,110,0.25) 0%, transparent 30%),
                radial-gradient(ellipse at 30% 85%, rgba(140,120,90,0.2) 0%, transparent 20%),
                radial-gradient(ellipse at 55% 15%, rgba(100,90,75,0.15) 0%, transparent 25%),
                radial-gradient(ellipse at 10% 55%, rgba(190,170,140,0.2) 0%, transparent 20%),
                radial-gradient(ellipse at 40% 40%, rgba(130,115,95,0.18) 0%, transparent 35%);
        }

        .glass { background: rgba(255,255,255,0.6); backdrop-filter: blur(20px); border: 1px solid rgba(45,62,78,0.1); }
        .glass-light { background: rgba(255,255,255,0.8); backdrop-filter: blur(15px); border: 1px solid rgba(45,62,78,0.15); }

        .btn-primary {
            background: #C9A97C; color: #0B0B45 !important;
            transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .btn-primary::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before { left: 100%; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(194,162,111,0.4); }

        .input-modern {
            background: rgba(255,255,255,0.8); border: 1px solid rgba(45,62,78,0.15);
            color: #2D3A50; transition: all 0.3s ease;
        }
        .input-modern::placeholder { color: rgba(45,62,78,0.5); }
        .input-modern:focus {
            background: rgba(255,255,255,1); border-color: #C9A97C;
            box-shadow: 0 0 0 3px rgba(201,169,124,0.2); outline: none;
        }

        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; z-index: 0; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #E8E6E1; }
        ::-webkit-scrollbar-thumb { background: #C9A97C; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #78A9C1; }
        html { scroll-behavior: smooth; }

        @media (max-width: 768px) {
            .hero-stone, .hero-stone-detail { width: 100%; opacity: 0.2; }
            .orb { filter: blur(60px); opacity: 0.15; }
        }
    </style>
</head>
<body class="hero-bg bg-beige">
    <div class="orb w-96 h-96 bg-sand top-[-10%] right-[-5%]"></div>
    <div class="orb w-80 h-80 bg-charcoal bottom-[10%] left-[-5%]"></div>
    <div class="orb w-64 h-64 bg-sage top-[40%] right-[30%] opacity-10"></div>
    <div class="hero-stone"></div>
    <div class="hero-stone-detail"></div>

    <nav class="fixed w-full z-50" style="background-color: #0B0B45;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-sand flex items-center justify-center shadow-lg shadow-sand/30">
                            <svg class="w-5 h-5 text-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-light leading-tight" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment">نظام تقييم الأضرار</span>
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <button id="langToggle" class="flex items-center gap-1 px-2 py-1.5 text-xs text-white/70 hover:text-white rounded-lg transition-all" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5H11m18 0h-2m-2 0h-2m-2 0h-2m-2 0h-2"/></svg>
                        <span id="langText">English</span>
                    </button>
                    <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors text-xs font-medium px-3 py-1.5" data-ar="الرئيسية" data-en="Home">الرئيسية</a>
                    <a href="{{ route('login') }}" class="px-4 py-1.5 text-xs font-medium text-white rounded-lg transition-all" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);" data-ar="تسجيل الدخول" data-en="Login">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-20 pb-4 px-4 flex items-center justify-center relative z-10">
        <div class="w-full max-w-md">
            <div class="glass rounded-3xl p-8 shadow-2xl">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-sand to-sage rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-charcoal mb-1" data-ar="إنشاء حساب جديد" data-en="Create Account">إنشاء حساب جديد</h2>
                    <p class="text-charcoal/70 text-sm" data-ar="انضم إلينا للمساعدة في تقييم الأضرار" data-en="Join us to help assess damage">انضم إلينا للمساعدة في تقييم الأضرار</p>
                </div>

                @if($errors->any())
                <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-charcoal mb-1.5" data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-3 input-modern rounded-xl" placeholder="أدخل اسمك الكامل">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal mb-1.5" data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 input-modern rounded-xl" placeholder="example@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal mb-1.5" data-ar="كلمة المرور" data-en="Password">كلمة المرور</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 input-modern rounded-xl" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal mb-1.5" data-ar="تأكيد كلمة المرور" data-en="Confirm Password">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 input-modern rounded-xl" placeholder="••••••••">
                        </div>
                        <button type="submit" class="w-full py-3.5 btn-primary font-bold rounded-xl text-base" data-ar="إنشاء الحساب" data-en="Create Account">
                            إنشاء الحساب
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-charcoal/10">
                    <p class="text-center text-charcoal/60 text-sm mb-3" data-ar="أو تابع باستخدام" data-en="Or continue with">أو تابع باستخدام</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.register') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 glass-light rounded-xl text-charcoal/70 hover:text-charcoal transition-all text-sm font-medium hover:shadow-md">
                            <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span data-ar="حساب أدمن" data-en="Admin Account">حساب أدمن</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 glass-light rounded-xl text-charcoal/70 hover:text-charcoal transition-all text-sm font-medium hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span data-ar="تسجيل الدخول" data-en="Login">تسجيل الدخول</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentLang = localStorage.getItem('lang') || 'ar';
        function applyLanguage(lang) {
            document.documentElement.setAttribute('lang', lang);
            document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            document.getElementById('langText').textContent = lang === 'ar' ? 'English' : 'العربية';
            document.querySelectorAll('[data-' + lang + ']').forEach(el => {
                el.textContent = el.getAttribute('data-' + lang);
            });
        }
        applyLanguage(currentLang);
        document.getElementById('langToggle').addEventListener('click', function() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';
            localStorage.setItem('lang', currentLang);
            applyLanguage(currentLang);
        });
    </script>
</body>
</html>