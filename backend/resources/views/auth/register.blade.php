<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - نظام تقييم الأضرار</title>
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
        .input-style {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-style:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(99, 102, 241, 0.5);
        }
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
                    <h1 class="text-xl font-bold text-white" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment System">نظام تقييم الأضرار</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button id="langToggle" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-300 hover:text-white border border-slate-600 rounded-lg hover:border-indigo-500 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5H11m18 0h-2m-2 0h-2m-2 0h-2m-2 0h-2"/>
                        </svg>
                        <span id="langText">English</span>
                    </button>
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium border border-slate-600 hover:border-indigo-500 transition-all" data-ar="الصفحة الرئيسية" data-en="Home">
                        الصفحة الرئيسية
                    </a>
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/25" data-ar="تسجيل الدخول" data-en="Login">
                        تسجيل الدخول
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-24 pb-12 px-4 min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-40 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-md relative">
            <div class="glass-card rounded-2xl p-8 shadow-2xl">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white" data-ar="إنشاء حساب جديد" data-en="Create Account">إنشاء حساب جديد</h2>
                    <p class="text-slate-400 mt-2" data-ar="انضم إلينا للمساعدة في تقييم الأضرار" data-en="Join us to help assess damages">انضم إلينا للمساعدة في تقييم الأضرار</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-3 input-style rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"
                                placeholder="أدخل اسمك الكامل" data-ar-placeholder="أدخل اسمك الكامل" data-en-placeholder="Enter your full name">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 input-style rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"
                                placeholder="example@email.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" data-ar="كلمة المرور" data-en="Password">كلمة المرور</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 input-style rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"
                                placeholder="••••••••">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2" data-ar="تأكيد كلمة المرور" data-en="Confirm Password">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 input-style rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"
                                placeholder="••••••••">
                        </div>

<button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/25" data-ar="إنشاء الحساب" data-en="Create Account">
إنشاء الحساب
</button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-700/50">
                    <p class="text-center text-slate-400 text-sm mb-4" data-ar="أو تابع باستخدام" data-en="Or continue with">أو تابع باستخدام</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.register') }}" class="flex items-center justify-center gap-2 py-3 px-4 bg-slate-700/30 hover:bg-slate-700/50 border border-slate-600 rounded-xl text-slate-300 hover:text-white transition-all text-sm">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span data-ar="حساب أدمن" data-en="Admin Account">حساب أدمن</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 py-3 px-4 bg-indigo-500/20 hover:bg-indigo-500/30 border border-indigo-500/30 rounded-xl text-indigo-300 hover:text-indigo-200 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
const html = document.documentElement;
const langText = document.getElementById('langText');
html.setAttribute('lang', lang);
html.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
langText.textContent = lang === 'ar' ? 'English' : 'العربية';

document.querySelectorAll('[data-' + lang + ']').forEach(el => {
el.textContent = el.getAttribute('data-' + lang);
});

document.querySelectorAll('[data-' + lang + '-placeholder]').forEach(el => {
el.setAttribute('placeholder', el.getAttribute('data-' + lang + '-placeholder'));
});

document.title = lang === 'ar' ? 'إنشاء حساب - نظام تقييم الأضرار' : 'Create Account - Damage Assessment System';
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
