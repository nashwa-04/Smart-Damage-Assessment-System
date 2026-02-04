<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل التقرير - نظام تقييم الأضرار الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for better input visibility */
        .form-input {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 
                   focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                   transition-all duration-200 shadow-sm;
        }
        
        .form-input:hover {
            @apply border-gray-400;
        }
        
        .form-select {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 
                   focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                   transition-all duration-200 shadow-sm cursor-pointer;
        }
        
        .form-select:hover {
            @apply border-gray-400;
        }
        
        .form-textarea {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 
                   focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                   transition-all duration-200 shadow-sm resize-y;
        }
        
        .form-textarea:hover {
            @apply border-gray-400;
        }
        
        .form-label {
            @apply block text-sm font-semibold text-gray-700 mb-2;
        }
        
        .form-section {
            @apply bg-white rounded-xl shadow-lg p-6 border border-gray-200;
        }
        
        .section-title {
            @apply text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500;
        }
        
        .input-group {
            @apply space-y-1;
        }
        
        .multimedia-zone {
            @apply border-2 border-dashed border-blue-300 rounded-xl p-6 
                   bg-gradient-to-br from-blue-50 to-white 
                   hover:border-blue-400 hover:from-blue-100 hover:to-white
                   transition-all duration-200;
        }
        
        .image-preview {
            @apply relative group rounded-lg overflow-hidden shadow-md border-2 border-gray-200 
                   hover:border-blue-400 transition-all duration-200;
        }
        
        .btn-primary {
            @apply px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg 
                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                   transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-secondary {
            @apply px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg 
                   hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 
                   transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-success {
            @apply px-6 py-3 bg-green-600 text-white font-semibold rounded-lg 
                   hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 
                   transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-danger {
            @apply px-3 py-2 bg-red-600 text-white font-medium rounded-lg 
                   hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 
                   transition-all duration-200 text-sm;
        }
        
        .btn-add {
            @apply px-4 py-2 bg-green-600 text-white font-medium rounded-lg 
                   hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 
                   transition-all duration-200 text-sm shadow-sm;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 min-h-screen shadow-xl">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-white text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    لوحة التحكم
                </h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-white rounded-lg hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    لوحة القيادة
                </a>
                <a href="{{ route('admin.map') }}" class="flex items-center gap-3 px-4 py-3 text-white rounded-lg hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    عرض الخريطة
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 text-white rounded-lg bg-blue-600 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    التقارير
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل التقرير #{{ $report->id }}
                </h2>
                <p class="text-gray-600">قم بتحديث معلومات التقرير والملفات المرتبطة به</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-300 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-800 mb-2">يرجى تصحيح الأخطاء التالية:</h3>
                            <ul class="list-disc list-inside text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data" class="form-section">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Selection -->
                    <div class="input-group">
                        <label for="user_id" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            المستخدم
                        </label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $report->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="input-group">
                        <label for="raw_location" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            الموقع
                        </label>
                        <input type="text" name="raw_location" id="raw_location" value="{{ old('raw_location', $report->raw_location) }}" class="form-input" placeholder="مثال: دمشق، حي الميدان" required>
                    </div>

                    <!-- Latitude -->
                    <div class="input-group">
                        <label for="latitude" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            خط العرض
                        </label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude) }}" class="form-input" placeholder="مثال: 33.5138" required>
                    </div>

                    <!-- Longitude -->
                    <div class="input-group">
                        <label for="longitude" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            خط الطول
                        </label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}" class="form-input" placeholder="مثال: 36.2765" required>
                    </div>

                    <!-- Damage Level -->
                    <div class="input-group">
                        <label for="ai_damage_level" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            مستوى الضرر (AI)
                        </label>
                        <select name="ai_damage_level" id="ai_damage_level" class="form-select">
                            <option value="">غير محدد</option>
                            <option value="low" {{ old('ai_damage_level', $report->ai_damage_level) == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('ai_damage_level', $report->ai_damage_level) == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('ai_damage_level', $report->ai_damage_level) == 'high' ? 'selected' : '' }}>عالي</option>
                            <option value="critical" {{ old('ai_damage_level', $report->ai_damage_level) == 'critical' ? 'selected' : '' }}>حرج</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="input-group">
                        <label for="status" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            الحالة
                        </label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ old('status', $report->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ old('status', $report->status) == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ old('status', $report->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="rejected" {{ old('status', $report->status) == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="input-group md:col-span-2">
                        <label for="raw_description" class="form-label flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            الوصف
                        </label>
                        <textarea name="raw_description" id="raw_description" rows="4" class="form-textarea" placeholder="اكتب وصفاً تفصيلياً للضرر هنا...">{{ old('raw_description', $report->raw_description) }}</textarea>
                    </div>
                </div>

                <!-- Multimedia Section -->
                <div class="mt-8 border-t-2 border-gray-200 pt-8">
                    <h3 class="section-title flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        الملفات والوسائط المتعددة
                    </h3>
                    
                    <!-- Images Section -->
                    <div class="mb-8">
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            الصور
                        </label>
                        
                        @php
                            $existingImages = $report->images ?? [];
                            $hasOldImage = !empty($report->image_path) && !in_array($report->image_path, $existingImages);
                        @endphp
                        
                        @if(count($existingImages) > 0 || $hasOldImage)
                            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    الصور الحالية:
                                </p>
                                <div class="grid grid-cols-4 gap-4">
                                    @foreach($existingImages as $image)
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/' . $image) }}" alt="صورة" class="w-full h-24 object-cover">
                                            <button type="button" onclick="removeImage('{{ $image }}')" class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-7 h-7 text-sm hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        </div>
                                    @endforeach
                                    @if($hasOldImage)
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/' . $report->image_path) }}" alt="صورة" class="w-full h-24 object-cover">
                                            <button type="button" onclick="removeOldImage()" class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-7 h-7 text-sm hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="keep_old_image" value="1" id="keep_old_image">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="multimedia-zone">
                            <div class="flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <label for="images" class="block text-sm font-semibold text-gray-700 mb-3 text-center">إضافة صور جديدة:</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-3 text-center">يمكنك اختيار عدة صور في نفس الوقت • يدعم: JPG, PNG, GIF</p>
                        </div>
                    </div>

                    <!-- PDF Section -->
                    <div class="mb-8">
                        <label for="pdf_file" class="form-label flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            ملف PDF
                        </label>
                        
                        @if($report->pdf_file)
                            <div class="mb-4 p-4 bg-red-50 rounded-xl border-2 border-red-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-red-100 p-3 rounded-lg">
                                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">ملف PDF موجود</p>
                                            <a href="{{ asset('storage/' . $report->pdf_file) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                عرض الملف
                                            </a>
                                        </div>
                                    </div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="remove_pdf" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <span class="text-sm font-medium text-red-600">حذف الملف</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        
                        <div class="multimedia-zone" style="background: linear-gradient(to bottom right, #fef2f2, white);">
                            <div class="flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <label for="pdf_file" class="block text-sm font-semibold text-gray-700 mb-3 text-center">رفع ملف PDF جديد:</label>
                            <input type="file" name="pdf_file" id="pdf_file" accept=".pdf,application/pdf" class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-3 text-center">الحد الأقصى: 10 ميجابايت</p>
                        </div>
                    </div>

                    <!-- Video Links Section -->
                    <div class="mb-6">
                        <label class="form-label flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            روابط الفيديو
                        </label>
                        
                        @if($report->video_links && count($report->video_links) > 0)
                            <div class="mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    الروابط الحالية:
                                </p>
                                <div id="existing-videos" class="space-y-2">
                                    @foreach($report->video_links as $index => $link)
                                        <div class="flex items-center gap-2 p-3 bg-white rounded-lg border-2 border-gray-200 hover:border-purple-300 transition-all duration-200">
                                            <input type="text" value="{{ $link }}" readonly class="flex-1 text-sm bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none">
                                            <button type="button" onclick="this.parentElement.remove()" class="btn-danger">حذف</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="multimedia-zone" style="background: linear-gradient(to bottom right, #faf5ff, white);">
                            <div class="flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">إضافة روابط فيديو جديدة:</label>
                            <div id="video-links-container" class="space-y-3">
                                <div class="flex gap-2">
                                    <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="flex-1 form-input">
                                    <button type="button" onclick="addVideoLink()" class="btn-add flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        إضافة
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3 text-center">أمثلة: YouTube, Vimeo, أو أي رابط فيديو آخر</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t-2 border-gray-200 flex flex-wrap justify-end gap-3">
                    <a href="{{ route('admin.reports') }}" class="btn-secondary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        إلغاء
                    </a>
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn-success flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        عرض التفاصيل
                    </a>
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        function addVideoLink() {
            const container = document.getElementById('video-links-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'flex gap-2';
            newDiv.innerHTML = `
                <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="flex-1 form-input">
                <button type="button" onclick="this.parentElement.remove()" class="btn-danger">حذف</button>
            `;
            container.appendChild(newDiv);
        }

        function removeImage(imagePath) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                const input = document.querySelector(`input[value="${imagePath}"]`);
                if (input) {
                    const previewDiv = input.closest('.image-preview');
                    if (previewDiv) {
                        previewDiv.style.opacity = '0.5';
                        previewDiv.style.pointerEvents = 'none';
                        setTimeout(() => previewDiv.remove(), 300);
                    }
                }
            }
        }

        function removeOldImage() {
            if (confirm('هل أنت متأكد من حذف الصورة القديمة؟')) {
                const checkbox = document.getElementById('keep_old_image');
                if (checkbox) {
                    checkbox.value = '0';
                    const previewDiv = checkbox.closest('.image-preview');
                    if (previewDiv) {
                        previewDiv.style.opacity = '0.5';
                        previewDiv.style.pointerEvents = 'none';
                    }
                }
            }
        }
    </script>
</body>
</html>