@extends('admin.layouts.app')

@section('title', 'الملف الشخصي')

@push('styles')
<style>
    .profile-image-preview { transition: transform 0.3s ease; }
    .profile-image-preview:hover { transform: scale(1.02); }
    .form-input {
        background: #F8F7F5;
        border: 1.5px solid rgba(11, 11, 69, 0.1);
        transition: all 0.2s ease;
        color: #0B0B45;
    }
    .form-input:focus {
        background: #fff;
        border-color: #C9A97C;
        box-shadow: 0 0 0 3px rgba(201, 169, 124, 0.15);
        outline: none;
    }
    .form-input::placeholder { color: rgba(11, 11, 69, 0.3); }
    .card-shadow {
        box-shadow: 0 8px 30px rgba(11, 11, 69, 0.04);
        border: 1px solid rgba(11, 11, 69, 0.05);
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto pt-2">
    @if(session('success'))
    <div class="mb-6 fade-in">
        <div class="rounded-xl p-4 flex items-center gap-4 shadow-sm" style="background: rgba(145, 166, 138, 0.1); border: 1px solid rgba(145, 166, 138, 0.2);">
            <p class="font-bold text-sm" style="color: #4A6040;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl p-8 mb-6 card-shadow fade-in">
        <div class="flex flex-col items-center mb-8 pb-6 border-b" style="border-color: rgba(11, 11, 69, 0.1);">
            <div class="relative group">
                <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 profile-image-preview bg-white overflow-hidden" style="border-color: rgba(201, 169, 124, 0.5);">
                    @if(auth()->user()->profile_image)
                        <img id="profile-avatar" src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="صورة الملف الشخصي" class="w-full h-full object-cover">
                    @else
                        <div id="profile-avatar" class="w-full h-full flex items-center justify-center" style="background: #C9A97C;">
                            <span class="text-white text-4xl font-bold">{{ auth()->user()->name[0] ?? 'م' }}</span>
                        </div>
                    @endif
                </div>
                <label for="profile_image" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                    <svg class="w-7 h-7" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </label>
            </div>

            <div class="text-center mt-4">
                <h3 class="text-xl font-bold" style="color: #0B0B45;">{{ auth()->user()->name }}</h3>
                <p class="text-sm mt-1" style="color: rgba(11, 11, 69, 0.6);">{{ auth()->user()->email }}</p>
                <p class="text-xs mt-1" style="color: rgba(11, 11, 69, 0.5);"><span data-ar="نوع الحساب:" data-en="Account type:">نوع الحساب:</span> <span style="color: #C9A97C; font-weight: bold;" data-ar="مدير النظام" data-en="System Admin">مدير النظام</span> | <span data-ar="عضو منذ" data-en="Member since">عضو منذ</span> {{ auth()->user()->created_at->format('Y/m/d') }}</p>
                
                @if(auth()->user()->profile_image)
                    <div class="mt-4">
                        <button type="button" onclick="removeProfileImage()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all hover:opacity-90 shadow-sm" style="background: rgba(156, 93, 77, 0.1); color: #9C5D4D;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span data-ar="حذف الصورة" data-en="Remove Photo">حذف الصورة</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="previewImage(this)">
            <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0">

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="الاسم" data-en="Name">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="form-input w-full px-4 py-3 rounded-xl text-sm font-medium" placeholder="أدخل اسمك" data-ar-placeholder="أدخل اسمك" data-en-placeholder="Enter your name">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="form-input w-full px-4 py-3 rounded-xl text-sm font-medium text-left" dir="ltr" placeholder="أدخل بريدك الإلكتروني" data-ar-placeholder="أدخل بريدك الإلكتروني" data-en-placeholder="Enter your email">
                </div>

                <div class="pt-6" style="border-top: 1px solid rgba(11, 11, 69, 0.1);">
                    <h4 class="font-bold mb-4" style="color: #0B0B45;" data-ar="تغيير كلمة المرور" data-en="Change Password">تغيير كلمة المرور</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="كلمة المرور الحالية" data-en="Current Password">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="أدخل كلمة المرور الحالية" data-ar-placeholder="أدخل كلمة المرور الحالية" data-en-placeholder="Enter current password">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="كلمة المرور الجديدة" data-en="New Password">كلمة المرور الجديدة</label>
                            <input type="password" name="new_password" class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="أدخل كلمة المرور الجديدة" data-ar-placeholder="أدخل كلمة المرور الجديدة" data-en-placeholder="Enter new password">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="تأكيد كلمة المرور الجديدة" data-en="Confirm New Password">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="new_password_confirmation" class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="أعد كتابة كلمة المرور الجديدة" data-ar-placeholder="أعد كتابة كلمة المرور الجديدة" data-en-placeholder="Re-enter new password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 text-white rounded-xl font-bold text-sm transition-all hover:opacity-90 shadow-md hover:shadow-lg" style="background: linear-gradient(135deg, #0B0B45, #1a1a5c);">
                        <span data-ar="حفظ التغييرات" data-en="Save Changes">حفظ التغييرات</span>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl font-bold text-sm transition-all hover:opacity-80 border" style="color: rgba(11, 11, 69, 0.6); border-color: rgba(11, 11, 69, 0.15);">
                        <span data-ar="إلغاء" data-en="Cancel">إلغاء</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function removeProfileImage() {
        const lang = localStorage.getItem('lang') || 'ar';
        const msg = lang === 'ar' ? 'هل أنت متأكد من حذف صورتك الشخصية؟' : 'Are you sure you want to remove your profile photo?';
        if (confirm(msg)) {
            document.getElementById('remove_profile_image').value = '1';
            document.querySelector('form').submit();
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const existingImg = document.querySelector('img#profile-avatar');
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else {
                    const div = document.querySelector('div#profile-avatar');
                    if (div) {
                        const img = document.createElement('img');
                        img.id = 'profile-avatar';
                        img.src = e.target.result;
                        img.alt = '{{ auth()->user()->name }}';
                        img.className = 'w-full h-full object-cover';
                        div.parentNode.replaceChild(img, div);
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
