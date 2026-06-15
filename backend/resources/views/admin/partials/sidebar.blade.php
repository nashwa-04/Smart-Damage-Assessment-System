<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>
<aside x-show="sidebarOpen" class="admin-sidebar w-64 sm:w-72 shadow-2xl fixed top-0 right-0 h-full overflow-y-auto z-40 flex flex-col" style="background: #0B0B45;">
    <div class="p-6" style="border-bottom: 1px solid rgba(201, 169, 124, 0.15);">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3" @click="sidebarOpen = false">
                <div class="w-12 h-12 rounded-xl bg-sand flex items-center justify-center shadow-lg shadow-sand/30">
                    <svg class="w-7 h-7" style="color: #0B0B45;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold leading-tight" style="color: #FAFAFA;" data-ar="نظام تقييم الأضرار" data-en="Damage Assessment">نظام تقييم الأضرار</h1>
                    <span class="text-[10px] font-medium tracking-wider" style="color: #78A9C1;">SMART DAMAGE ASSESSMENT</span>
                </div>
            </a>
        </div>
    </div>

    <div class="px-2 pt-4">
        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 rounded-xl p-3 mx-2 transition-colors {{ request()->routeIs('admin.profile') ? 'active-profile border' : 'border border-transparent' }}" style="background: {{ request()->routeIs('admin.profile') ? 'rgba(201, 169, 124, 0.1)' : 'transparent' }}; {{ request()->routeIs('admin.profile') ? 'border-color: rgba(201, 169, 124, 0.2);' : 'border-color: transparent;' }}" onmouseover="if(!this.classList.contains('active-profile')){this.style.background='rgba(201, 169, 124, 0.08)'}" onmouseout="if(!this.classList.contains('active-profile')){this.style.background='{{ request()->routeIs('admin.profile') ? 'rgba(201, 169, 124, 0.1)' : 'transparent' }}'}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shadow-lg flex-shrink-0 overflow-hidden" style="background: #C9A97C;">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    {{ auth()->user()->name[0] ?? 'م' }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate" style="color: rgba(250,250,250,0.9);">{{ auth()->user()->name ?? 'المستخدم' }}</p>
                <p class="text-xs truncate mt-0.5" style="color: rgba(250,250,250,0.4);" data-ar="مدير النظام" data-en="System Admin">مدير النظام</p>
            </div>
            <svg class="w-4 h-4 flex-shrink-0" style="color: rgba(250,250,250,0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    </div>

    <nav class="p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="color: {{ request()->routeIs('admin.dashboard') ? '#FAFAFA' : 'rgba(250,250,250,0.6)' }};">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ request()->routeIs('admin.dashboard') ? 'rgba(255,255,255,0.15)' : 'rgba(255,255,255,0.08)' }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="font-medium" data-ar="لوحة القيادة" data-en="Dashboard">لوحة القيادة</span>
        </a>

        <a href="{{ route('admin.map') }}" @click="sidebarOpen = false" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.map') ? 'active' : '' }}" style="color: {{ request()->routeIs('admin.map') ? '#FAFAFA' : 'rgba(250,250,250,0.6)' }};">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ request()->routeIs('admin.map') ? 'rgba(255,255,255,0.15)' : 'rgba(255,255,255,0.08)' }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            <span class="font-medium" data-ar="الخريطة" data-en="Map">الخريطة</span>
        </a>

        <a href="{{ route('admin.reports') }}" @click="sidebarOpen = false" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ (request()->routeIs('admin.reports') || request()->routeIs('admin.reports.*')) ? 'active' : '' }}" style="color: {{ (request()->routeIs('admin.reports') || request()->routeIs('admin.reports.*')) ? '#FAFAFA' : 'rgba(250,250,250,0.6)' }};">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ (request()->routeIs('admin.reports') || request()->routeIs('admin.reports.*')) ? 'rgba(255,255,255,0.15)' : 'rgba(255,255,255,0.08)' }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="font-medium" data-ar="التقارير" data-en="Reports">التقارير</span>
        </a>

        <a href="{{ route('admin.reports.create') }}" @click="sidebarOpen = false" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.reports.create') ? 'active' : '' }}" style="color: {{ request()->routeIs('admin.reports.create') ? '#FAFAFA' : 'rgba(250,250,250,0.6)' }};">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: {{ request()->routeIs('admin.reports.create') ? 'rgba(255,255,255,0.15)' : 'rgba(255,255,255,0.08)' }};">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="font-medium" data-ar="تقرير جديد" data-en="New Report">تقرير جديد</span>
        </a>
    </nav>

    <div class="p-4 mt-4 hidden lg:block" style="border-top: 1px solid rgba(201, 169, 124, 0.15);">
        <div class="rounded-xl p-4" style="background: rgba(201, 169, 124, 0.06);">
            <h3 class="text-xs font-bold mb-3 uppercase tracking-wider" style="color: #C9A97C;" data-ar="إحصائيات سريعة" data-en="Quick Stats">إحصائيات سريعة</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-lg p-3 text-center" style="background: rgba(255,255,255,0.05);">
                    <p class="text-2xl font-bold" style="color: #C9A97C;">{{ \App\Models\Report::count() }}</p>
                    <p class="text-xs" style="color: rgba(250,250,250,0.4);" data-ar="تقرير" data-en="Reports">تقرير</p>
                </div>
                <div class="rounded-lg p-3 text-center" style="background: rgba(255,255,255,0.05);">
                    <p class="text-2xl font-bold" style="color: #78A9C1;">{{ \App\Models\User::count() }}</p>
                    <p class="text-xs" style="color: rgba(250,250,250,0.4);" data-ar="مستخدم" data-en="Users">مستخدم</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-auto" style="border-top: 1px solid rgba(201, 169, 124, 0.15);">
        <div class="px-4 py-3 flex items-center justify-between">
            <span class="text-xs font-medium" style="color: rgba(250,250,250,0.4);" data-ar="اللغة" data-en="Language">اللغة</span>
            <button id="langToggle" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all" style="background: rgba(201,169,124,0.15); color: #C9A97C; border: 1px solid rgba(201,169,124,0.25);" onmouseover="this.style.background='rgba(201,169,124,0.25)'" onmouseout="this.style.background='rgba(201,169,124,0.15)'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5H11"/></svg>
                <span id="langText">English</span>
            </button>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="px-2 py-2">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group" style="color: rgba(250,250,250,0.6); border: 1px solid transparent;" onmouseover="this.style.background='rgba(220,38,38,0.15)';this.style.borderColor='rgba(220,38,38,0.3)';this.style.color='#f87171';" onmouseout="this.style.background='transparent';this.style.borderColor='transparent';this.style.color='rgba(250,250,250,0.6)';">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all" style="background: rgba(255,255,255,0.08);" onmouseover="this.style.background='rgba(220,38,38,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <span class="font-medium" data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>