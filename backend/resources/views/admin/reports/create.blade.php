@extends('admin.layouts.app')

@section('title', 'إضافة تقرير جديد - نظام تقييم الأضرار الذكي')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .filter-toggle { transition: all 0.3s ease; }
    .filter-body { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease; }
    .filter-body.open { max-height: 500px; }
    .form-input {
        background: rgba(255,255,255,0.7);
        border: 1.5px solid rgba(11, 11, 69, 0.08);
        transition: all 0.25s ease;
        color: #0B0B45;
    }
    .form-input:focus {
        background: #fff;
        border-color: #C9A97C;
        box-shadow: 0 0 0 3px rgba(201, 169, 124, 0.1);
        outline: none;
    }
    .form-input::placeholder { color: rgba(11, 11, 69, 0.35); }
    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0B0B45;
        margin-bottom: 0.5rem;
        display: block;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="mb-4 sm:mb-6 lg:mb-8 fade-in">
    <div class="glass-card rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg bg-[#0B0B45] shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold" style="color: #0B0B45;" data-ar="إضافة تقرير جديد" data-en="Add New Report">إضافة تقرير جديد</h2>
                    <p class="mt-1 text-xs sm:text-sm lg:text-base" style="color: rgba(11, 11, 69, 0.6);" data-ar="قم بإنشاء تقرير جديد مع جميع التفاصيل" data-en="Create a new report with all details">قم بإنشاء تقرير جديد مع جميع التفاصيل</p>
                </div>
            </div>
            <a href="{{ route('admin.reports') }}" class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="color: rgba(11, 11, 69, 0.5);" onmouseover="this.style.color='#0B0B45'" onmouseout="this.style.color='rgba(11, 11, 69, 0.5)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span data-ar="رجوع" data-en="Back">رجوع</span>
            </a>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="mb-6 fade-in">
    <div class="rounded-2xl p-5 shadow-lg" style="background: rgba(156, 93, 77, 0.08); border: 1.5px solid rgba(156, 93, 77, 0.25);">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #9C5D4D;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-2" style="color: #7A4538;" data-ar="يرجى تصحيح الأخطاء التالية:" data-en="Please fix the following errors:">يرجى تصحيح الأخطاء التالية:</h3>
                <ul style="color: #9C5D4D;" class="space-y-1">
                    @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-2 text-sm">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: #9C5D4D;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Basic Info Card -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
        <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: #0B0B45;">
            <h3 class="text-base font-bold flex items-center gap-2" style="color: #FAFAFA;">
                <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span data-ar="المعلومات الأساسية" data-en="Basic Information">المعلومات الأساسية</span>
            </h3>
        </div>
        
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="user_id" class="form-label" data-ar="المستخدم" data-en="User">المستخدم</label>
                    <select name="user_id" id="user_id" class="form-input w-full rounded-xl px-4 py-3" required>
                        <option value="" data-ar="اختر مستخدم" data-en="Select user">اختر مستخدم</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="raw_location" class="form-label" data-ar="الموقع" data-en="Location">الموقع</label>
                    <input type="text" name="raw_location" id="raw_location" value="{{ old('raw_location') }}" class="form-input w-full rounded-xl px-4 py-3" placeholder="دمشق، حي الميدان" data-ar-placeholder="دمشق، حي الميدان" data-en-placeholder="Damascus, Al-Midan" required>
                </div>

                <div>
                    <label for="latitude" class="form-label" data-ar="خط العرض" data-en="Latitude">خط العرض</label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" class="form-input w-full rounded-xl px-4 py-3" placeholder="33.5138" required>
                </div>

                <div>
                    <label for="longitude" class="form-label" data-ar="خط الطول" data-en="Longitude">خط الطول</label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" class="form-input w-full rounded-xl px-4 py-3" placeholder="36.2765" required>
                </div>

                <!-- Map Location Picker -->
                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0" data-ar="الموقع على الخريطة" data-en="Map Location">الموقع على الخريطة</label>
                        <button type="button" onclick="getMyLocation()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all" style="background: rgba(120, 169, 193, 0.1); color: #78A9C1;" onmouseover="this.style.background='rgba(120,169,193,0.2)'" onmouseout="this.style.background='rgba(120,169,193,0.1)'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span data-ar="موقعي الحالي" data-en="My Location">موقعي الحالي</span>
                        </button>
                    </div>
                    <div id="locationMap" style="height: 300px; border-radius: 12px; border: 1.5px solid rgba(11, 11, 69, 0.08);"></div>
                    <p class="text-xs mt-2" style="color: rgba(11, 11, 69, 0.4);" data-ar="انقر على الخريطة لتحديد الموقع" data-en="Click on the map to set the location">انقر على الخريطة لتحديد الموقع</p>
                </div>

                <!-- AI Notice -->
                <div class="md:col-span-2">
                    <div class="rounded-xl p-4" style="background: rgba(201, 169, 124, 0.06); border: 1px solid rgba(201, 169, 124, 0.15);">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #C9A97C;">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm" style="color: #0B0B45;" data-ar="تقييم تلقائي بالذكاء الاصطناعي" data-en="Automatic AI Assessment">تقييم تلقائي بالذكاء الاصطناعي</p>
                                <p class="text-xs mt-0.5" style="color: rgba(11, 11, 69, 0.5);" data-ar="سيتم تحليل الصور وتحديد مستوى الضرر والحالة تلقائياً بعد إنشاء التقرير" data-en="Images will be analyzed and damage level determined automatically after creating the report">سيتم تحليل الصور وتحديد مستوى الضرر والحالة تلقائياً بعد إنشاء التقرير</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="raw_description" class="form-label" data-ar="الوصف" data-en="Description">الوصف</label>
                    <textarea name="raw_description" id="raw_description" rows="4" class="form-input w-full rounded-xl px-4 py-3 resize-none" placeholder="اكتب وصفاً تفصيلياً للضرر..." data-ar-placeholder="اكتب وصفاً تفصيلياً للضرر..." data-en-placeholder="Write a detailed description of the damage...">{{ old('raw_description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Images Card -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
        <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: #0B0B45;">
            <h3 class="text-base font-bold flex items-center gap-2" style="color: #FAFAFA;">
                <svg class="w-5 h-5" style="color: #78A9C1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span data-ar="الصور" data-en="Images">الصور</span>
            </h3>
        </div>
        
        <div class="p-4 sm:p-6">
            <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
            <div id="dropZone" class="border-2 border-dashed rounded-2xl p-8 text-center transition-all cursor-pointer" style="border-color: rgba(11, 11, 69, 0.08);" onclick="document.getElementById('images').click()" onmouseover="this.style.borderColor='#78A9C1';this.style.background='rgba(120,169,193,0.03)'" onmouseout="this.style.borderColor='rgba(11,11,69,0.08)';this.style.background='transparent'">
                <div class="upload-placeholder">
                <div class="w-16 h-16 mx-auto rounded-xl flex items-center justify-center mb-3" style="background: rgba(120, 169, 193, 0.1);">
                    <svg class="w-8 h-8" style="color: #78A9C1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <p class="font-bold mb-1" style="color: #0B0B45;" data-ar="اسحب الصور هنا أو انقر للاختيار" data-en="Drag images here or click to select">اسحب الصور هنا أو انقر للاختيار</p>
                <p class="text-xs mb-4" style="color: rgba(11, 11, 69, 0.4);" data-ar="JPG, PNG, GIF - حتى 10 صور" data-en="JPG, PNG, GIF - Up to 10 images">JPG, PNG, GIF - حتى 10 صور</p>
                <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-bold text-sm text-white" style="background: #78A9C1;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span data-ar="اختيار صور" data-en="Select Images">اختيار صور</span>
                </span>
            </div>
            <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-4"></div>
        </div>
    </div>

    <!-- PDF Card -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
        <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: #0B0B45;">
            <h3 class="text-base font-bold flex items-center gap-2" style="color: #FAFAFA;">
                <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span data-ar="ملف PDF" data-en="PDF File">ملف PDF</span>
            </h3>
        </div>
        
        <div class="p-4 sm:p-6">
            <div id="pdfDropZone" class="border-2 border-dashed rounded-2xl p-8 text-center transition-all" style="border-color: rgba(11, 11, 69, 0.08);" onmouseover="this.style.borderColor='#C9A97C';this.style.background='rgba(201,169,124,0.03)'" onmouseout="this.style.borderColor='rgba(11,11,69,0.08)';this.style.background='transparent'">
                <div class="w-16 h-16 mx-auto rounded-xl flex items-center justify-center mb-3" style="background: rgba(201, 169, 124, 0.1);">
                    <svg class="w-8 h-8" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="font-bold mb-1" style="color: #0B0B45;" data-ar="رفع ملف PDF جديد" data-en="Upload new PDF file">رفع ملف PDF جديد</p>
                <p class="text-xs mb-4" style="color: rgba(11, 11, 69, 0.4);" data-ar="الحد الأقصى: 20 ميجابايت" data-en="Max size: 20MB">الحد الأقصى: 20 ميجابايت</p>
                <p id="pdfFileName" class="text-sm font-bold mb-3 hidden" style="color: #C9A97C;"></p>
                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf,application/pdf" class="hidden">
                <label for="pdf_file" class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-bold text-sm transition-all text-white" style="background: #C9A97C;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span data-ar="اختيار ملف" data-en="Select File">اختيار ملف</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Video Links Card -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
        <div class="px-4 sm:px-6 py-3 sm:py-4" style="background: #0B0B45;">
            <h3 class="text-base font-bold flex items-center gap-2" style="color: #FAFAFA;">
                <svg class="w-5 h-5" style="color: #78A9C1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <span data-ar="روابط الفيديو" data-en="Video Links">روابط الفيديو</span>
            </h3>
        </div>
        
        <div class="p-4 sm:p-6">
            <div id="video-links-container" class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #78A9C1;">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="form-input flex-1 rounded-xl px-4 py-3">
                    <button type="button" onclick="addVideoLink()" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-opacity" style="background: #78A9C1;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-xs mt-4 text-center" style="color: rgba(11, 11, 69, 0.4);" data-ar="YouTube, Vimeo, أو أي رابط فيديو آخر" data-en="YouTube, Vimeo, or any other video link">YouTube, Vimeo, أو أي رابط فيديو آخر</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3 pt-2 fade-in">
        <a href="{{ route('admin.reports') }}" class="px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all" style="background: rgba(11, 11, 69, 0.06); color: rgba(11, 11, 69, 0.7);" onmouseover="this.style.background='rgba(11,11,69,0.1)'" onmouseout="this.style.background='rgba(11,11,69,0.06)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span data-ar="إلغاء" data-en="Cancel">إلغاء</span>
        </a>
        <button type="submit" class="px-6 py-3 text-white rounded-xl font-bold flex items-center gap-2 transition-all" style="background: #C9A97C;" onmouseover="this.style.background='#B08D5F'" onmouseout="this.style.background='#C9A97C'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span data-ar="حفظ التقرير" data-en="Save Report">حفظ التقرير</span>
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const locationMap = L.map('locationMap').setView([35.0, 38.0], 6);
    const createTileLayers = {
        ar: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }),
        en: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap &copy; CARTO' })
    };
    let createCurrentTile = createTileLayers[localStorage.getItem('lang') || 'ar'];
    createCurrentTile.addTo(locationMap);

    let createMarker = null;

    window.rebuildMapPopups = function(lang) {
        if (createCurrentTile) locationMap.removeLayer(createCurrentTile);
        createCurrentTile = createTileLayers[lang] || createTileLayers['ar'];
        createCurrentTile.addTo(locationMap);
    };

    locationMap.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        if (createMarker) locationMap.removeLayer(createMarker);
        createMarker = L.marker([lat, lng]).addTo(locationMap);
    });

    function getMyLocation() {
        var lang = localStorage.getItem('lang') || 'ar';
        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');

        function applyLocation(lat, lng, zoom, source) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            locationMap.setView([lat, lng], zoom);
            if (createMarker) locationMap.removeLayer(createMarker);
            createMarker = L.marker([lat, lng]).addTo(locationMap);
        }

        if (!navigator.geolocation) {
            fetchLocationByIPAdmin(applyLocation, lang);
            return;
        }
        navigator.geolocation.getCurrentPosition(function(pos) {
            applyLocation(pos.coords.latitude, pos.coords.longitude, 15, 'GPS');
        }, function(err) {
            fetchLocationByIPAdmin(applyLocation, lang);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    }

    function fetchLocationByIPAdmin(applyLocation, lang) {
        fetch('https://ipapi.co/json/')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.latitude && data.longitude) {
                    applyLocation(data.latitude, data.longitude, 12, 'IP');
                    var locInput = document.querySelector('input[name="raw_location"]');
                    if (locInput && !locInput.value) {
                        var parts = [];
                        if (data.city) parts.push(data.city);
                        if (data.region) parts.push(data.region);
                        if (data.country_name) parts.push(data.country_name);
                        if (parts.length > 0) locInput.value = parts.join(' - ');
                    }
                } else {
                    alert(lang === 'ar' ? 'تعذر تحديد موقعك. حدد الموقع يدوياً من الخريطة.' : 'Unable to detect your location. Set it manually on the map.');
                }
            })
            .catch(function() {
                alert(lang === 'ar' ? 'تعذر تحديد موقعك. حدد الموقع يدوياً من الخريطة.' : 'Unable to detect your location. Set it manually on the map.');
            });
    }

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    if (latInput.value && lngInput.value) {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            locationMap.setView([lat, lng], 13);
            createMarker = L.marker([lat, lng]).addTo(locationMap);
        }
    }
</script>
<script>
    const imagesInput = document.getElementById('images');
    const dropZone = document.getElementById('dropZone');
    const previewContainer = document.getElementById('imagePreview');

    if (dropZone) {
        dropZone.addEventListener('click', function(e) {
            if (e.target.tagName !== 'LABEL' && !e.target.closest('label')) {
                imagesInput.click();
            }
        });

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#78A9C1';
            this.style.background = 'rgba(120,169,193,0.06)';
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = 'rgba(11,11,69,0.08)';
            this.style.background = 'transparent';
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = 'rgba(11,11,69,0.08)';
            this.style.background = 'transparent';
            const dt = new DataTransfer();
            const existingFiles = imagesInput.files;
            for (let i = 0; i < existingFiles.length; i++) dt.items.add(existingFiles[i]);
            for (let i = 0; i < e.dataTransfer.files.length; i++) dt.items.add(e.dataTransfer.files[i]);
            imagesInput.files = dt.files;
            showPreviews(imagesInput.files);
        });
    }

    if (imagesInput) {
        imagesInput.addEventListener('change', function() {
            showPreviews(this.files);
        });
    }

    function showPreviews(files) {
        if (!previewContainer) return;
        previewContainer.innerHTML = '';
        for (let i = 0; i < files.length; i++) {
            if (!files[i].type.startsWith('image/')) continue;
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover rounded-lg"><button type="button" onclick="removeImage(this, ' + i + ')" class="absolute top-1 left-1 w-6 h-6 rounded-md flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity" style="background: rgba(156,93,77,0.9);"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(files[i]);
        }
        if (files.length > 0) {
            dropZone.querySelector('.upload-placeholder').style.display = 'none';
        }
    }

    function removeImage(btn, index) {
        const dt = new DataTransfer();
        const files = imagesInput.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) dt.items.add(files[i]);
        }
        imagesInput.files = dt.files;
        btn.parentElement.remove();
        if (imagesInput.files.length === 0) {
            dropZone.querySelector('.upload-placeholder').style.display = '';
        }
    }

    const pdfInput = document.getElementById('pdf_file');
    const pdfDropZone = document.getElementById('pdfDropZone');
    const pdfName = document.getElementById('pdfFileName');

    if (pdfDropZone) {
        pdfDropZone.addEventListener('click', function(e) {
            if (e.target.tagName !== 'LABEL' && !e.target.closest('label')) {
                pdfInput.click();
            }
        });
        pdfDropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor='#C9A97C'; this.style.background='rgba(201,169,124,0.04)'; });
        pdfDropZone.addEventListener('dragleave', function(e) { e.preventDefault(); this.style.borderColor='rgba(11,11,69,0.08)'; this.style.background='transparent'; });
        pdfDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor='rgba(11,11,69,0.08)'; this.style.background='transparent';
            if (e.dataTransfer.files.length > 0 && e.dataTransfer.files[0].type === 'application/pdf') {
                pdfInput.files = e.dataTransfer.files;
                if (pdfName) pdfName.textContent = e.dataTransfer.files[0].name;
            }
        });
    }

    if (pdfInput) {
        pdfInput.addEventListener('change', function() {
            if (this.files.length > 0 && pdfName) {
                pdfName.textContent = this.files[0].name;
                pdfName.classList.remove('hidden');
            }
        });
    }

    function addVideoLink() {
        const container = document.getElementById('video-links-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'flex items-center gap-3 fade-in';
        newDiv.innerHTML = `
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #78A9C1;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
            </div>
            <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="form-input flex-1 rounded-xl px-4 py-3">
            <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-opacity" style="background: #9C5D4D;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(newDiv);
    }
</script>
@endpush
