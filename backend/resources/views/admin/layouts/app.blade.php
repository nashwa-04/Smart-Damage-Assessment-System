<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام تقييم الأضرار الذكي')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(45, 62, 78, 0.08);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(45, 62, 78, 0.12);
        }

        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background: rgba(194, 162, 111, 0.15);
            transform: translateX(-5px);
        }

        .sidebar-item.active {
            background: rgba(201, 169, 124, 0.12);
            border-right: 3px solid #C9A97C;
            color: #FAFAFA;
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

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E8E6E1;
        }

        ::-webkit-scrollbar-thumb {
            background: #C9A97C;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #78A9C1;
        }

        @media (min-width: 1024px) {
            .admin-sidebar {
                display: block !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-beige min-h-screen">
<div x-data="{ sidebarOpen: false }" class="min-h-screen">

@include('admin.partials.sidebar')

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="lg:mr-72 min-h-screen transition-all duration-300">
            <header class="sticky top-0 z-20 lg:hidden" style="background: #0B0B45; color: #FAFAFA;">
                <div class="flex items-center justify-between px-3 sm:px-4 py-3">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg transition-colors" style="color: rgba(250,250,250,0.7);" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-sm sm:text-base font-bold" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment">نظام تقييم الأضرار</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs hidden sm:inline" style="color: rgba(250,250,250,0.5);">{{ auth()->user()->name }}</span>
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-sand to-sage flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                            {{ auth()->user()->name[0] ?? 'م' }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-3 sm:p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        let currentLang = localStorage.getItem('lang') || 'ar';

        function confirmMessage(arMsg, enMsg) {
            const lang = localStorage.getItem('lang') || 'ar';
            return confirm(lang === 'ar' ? arMsg : enMsg);
        }
        function applyLanguage(lang) {
            document.documentElement.setAttribute('lang', lang);
            document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            const langBtn = document.getElementById('langText');
            if (langBtn) langBtn.textContent = lang === 'ar' ? 'English' : 'العربية';
            document.querySelectorAll('[data-' + lang + ']').forEach(el => {
                if (el.tagName === 'OPTION') return;
                el.textContent = el.getAttribute('data-' + lang);
            });
            document.querySelectorAll('select').forEach(select => {
                let hasI18nOptions = false;
                select.querySelectorAll('option[data-' + lang + ']').forEach(option => {
                    option.textContent = option.getAttribute('data-' + lang);
                    hasI18nOptions = true;
                });
                if (hasI18nOptions) {
                    const currentVal = select.value;
                    select.value = '';
                    select.value = currentVal;
                }
            });
            document.querySelectorAll('[data-' + lang + '-placeholder]').forEach(el => {
                el.setAttribute('placeholder', el.getAttribute('data-' + lang + '-placeholder'));
            });
            if (typeof window.rebuildMapPopups === 'function') {
                window.rebuildMapPopups(lang);
            }
        }
        applyLanguage(currentLang);
        document.querySelectorAll('#langToggle').forEach(btn => {
            btn.addEventListener('click', function() {
                currentLang = currentLang === 'ar' ? 'en' : 'ar';
                localStorage.setItem('lang', currentLang);
                applyLanguage(currentLang);
            });
        });
    </script>
</body>
</html>
