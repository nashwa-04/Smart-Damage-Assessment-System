@extends('admin.layouts.app')

@section('title', 'تعديل التقرير #' . $report->id)

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-3 sm:mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.reports') }}" class="w-10 h-10 rounded-xl bg-sand/10 flex items-center justify-center hover:bg-sand/20 transition-colors">
                        <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-charcoal" data-ar="تعديل التقرير" data-en="Edit Report">تعديل التقرير</h1>
                        <p class="text-charcoal/50 text-sm mt-1" data-ar="رقم التقرير: #{{ $report->id }}" data-en="Report #: #{{ $report->id }}">رقم التقرير: #{{ $report->id }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sage/10 text-sage rounded-xl hover:bg-sage/20 transition-colors text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span data-ar="معاينة" data-en="Preview">معاينة</span>
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Left Column - Main Form -->
            <div class="space-y-4">
                <!-- Basic Information Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-charcoal/10 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-charcoal/10 bg-sand/5">
                        <h2 class="text-lg font-bold text-charcoal flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-sand/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <span data-ar="معلومات التقرير" data-en="Report Information">معلومات التقرير</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- User -->
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="المستخدم" data-en="User">المستخدم</label>
                                <select name="user_id" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $report->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="الموقع" data-en="Location">الموقع</label>
                                <input type="text" name="raw_location" value="{{ old('raw_location', $report->raw_location) }}" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                            </div>

                            <!-- Coordinates -->
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="خط العرض" data-en="Latitude">خط العرض</label>
                                <input type="number" step="any" name="latitude" value="{{ old('latitude', $report->latitude) }}" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="خط الطول" data-en="Longitude">خط الطول</label>
                                <input type="number" step="any" name="longitude" value="{{ old('longitude', $report->longitude) }}" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="الوصف" data-en="Description">الوصف</label>
                                <textarea name="raw_description" rows="4" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm resize-none">{{ old('raw_description', $report->raw_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-charcoal/10 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-charcoal/10 bg-sage/5">
                        <h2 class="text-lg font-bold text-charcoal flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-sage/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </span>
                            <span data-ar="الصور والوسائط" data-en="Images & Media">الصور والوسائط</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Existing Images -->
                        @php
                            $existingImages = $report->images ?? [];
                            $hasOldImage = !empty($report->image_path) && !in_array($report->image_path, $existingImages);
                        @endphp

                        @if(count($existingImages) > 0 || $hasOldImage)
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-3" data-ar="الصور الحالية" data-en="Current Images">الصور الحالية</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach($existingImages as $image)
                                        <div class="relative group aspect-square rounded-xl overflow-hidden bg-charcoal/5">
                                            <img src="{{ asset('storage/' . $image) }}" alt="صورة" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/80 via-charcoal/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end justify-center pb-3">
                                                <button type="button" onclick="removeImage('{{ $image }}')" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors" data-ar="حذف" data-en="Delete">
                                                    حذف
                                                </button>
                                            </div>
                                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        </div>
                                    @endforeach
                                    @if($hasOldImage)
                                        <div class="relative group aspect-square rounded-xl overflow-hidden bg-charcoal/5">
                                            <img src="{{ asset('storage/' . $report->image_path) }}" alt="صورة" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/80 via-charcoal/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end justify-center pb-3">
                                                <button type="button" onclick="removeOldImage()" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors" data-ar="حذف" data-en="Delete">
                                                    حذف
                                                </button>
                                            </div>
                                            <input type="hidden" name="keep_old_image" value="1" id="keep_old_image">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Images -->
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-3" data-ar="إضافة صور جديدة" data-en="Add New Images">إضافة صور جديدة</label>
                            <div class="border-2 border-dashed border-sage/30 rounded-2xl p-8 text-center hover:border-sage/50 hover:bg-sage/5 transition-all cursor-pointer" onclick="document.getElementById('images').click()">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-sage/10 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-charcoal font-bold mb-1" data-ar="انقر لرفع الصور أو اسحبها هنا" data-en="Click to upload or drag images here">انقر لرفع الصور أو اسحبها هنا</p>
                                <p class="text-charcoal/50 text-sm" data-ar="JPG, PNG, GIF • حد أقصى 10 صور" data-en="JPG, PNG, GIF • Max 10 images">JPG, PNG, GIF • حد أقصى 10 صور</p>
                            </div>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                        </div>

                        <!-- PDF -->
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-3" data-ar="ملف PDF" data-en="PDF File">ملف PDF</label>
                            @if($report->pdf_file)
                                <div class="flex items-center justify-between p-4 bg-sand/5 rounded-xl border-2 border-sand/10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-sand flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-charcoal" data-ar="ملف PDF موجود" data-en="PDF file exists">ملف PDF موجود</p>
                                            <a href="{{ asset('storage/' . $report->pdf_file) }}" target="_blank" class="text-xs text-sage hover:text-sage/70" data-ar="عرض الملف" data-en="View File">عرض الملف</a>
                                        </div>
                                    </div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="remove_pdf" value="1" class="w-4 h-4 text-sand rounded focus:ring-sand/20">
                                        <span class="text-xs text-red-600 font-bold" data-ar="حذف" data-en="Delete">حذف</span>
                                    </label>
                                </div>
                            @endif
                            <div class="mt-3">
                                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="hidden">
                                <label for="pdf_file" class="inline-flex items-center gap-2 px-4 py-2 bg-sand/10 text-charcoal rounded-xl text-sm font-bold hover:bg-sand/20 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span data-ar="رفع PDF" data-en="Upload PDF">رفع PDF</span>
                                </label>
                            </div>
                        </div>

                        <!-- Video Links -->
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-3" data-ar="روابط الفيديو" data-en="Video Links">روابط الفيديو</label>
                            <div id="video-links-container" class="space-y-3">
                                @if($report->video_links && count($report->video_links) > 0)
                                    @foreach($report->video_links as $link)
                                        <div class="flex items-center gap-3 p-3 bg-charcoal/5 rounded-xl">
                                            <div class="w-10 h-10 rounded-lg bg-sage/10 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                </svg>
                                            </div>
                                            <input type="url" name="video_links[]" value="{{ $link }}" class="flex-1 px-4 py-2.5 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sage/50 text-sm">
                                            <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="flex items-center gap-3 p-3 border-2 border-dashed border-charcoal/20 rounded-xl">
                                    <div class="w-10 h-10 rounded-lg bg-charcoal/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." data-ar-placeholder="https://youtube.com/watch?v=..." data-en-placeholder="https://youtube.com/watch?v=..." class="flex-1 px-4 py-2.5 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sage/50 text-sm">
                                </div>
                            </div>
                            <button type="button" onclick="addVideoLink()" class="mt-3 inline-flex items-center gap-2 text-sage font-bold text-sm hover:text-sage/70">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span data-ar="إضافة رابط فيديو" data-en="Add Video Link">إضافة رابط فيديو</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings -->
            <div class="space-y-4">
                <!-- AI Assessment -->
                <div class="bg-white rounded-2xl shadow-sm border border-charcoal/10 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-charcoal/10 bg-gradient-to-r from-indigo-500/5 to-purple-500/5">
                        <h2 class="text-lg font-bold text-charcoal flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </span>
                            <span data-ar="تقييم AI" data-en="AI Assessment">تقييم AI</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6 space-y-5">
                        <!-- Current Assessment -->
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                            <p class="text-xs font-bold text-indigo-600 mb-2" data-ar="التقييم الحالي" data-en="Current Assessment">التقييم الحالي</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-indigo-700">{{ $report->ai_damage_level ?? 'غير محدد' }}</span>
                                <span class="text-3xl font-bold text-indigo-600">{{ $report->ai_damage_score ?? '-' }}/10</span>
                            </div>
                        </div>

                        <!-- Edit Assessment -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="تعديل المستوى" data-en="Edit Level">تعديل المستوى</label>
                                <select name="edit_ai_damage_level" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                                    <option value="" data-ar="اختر المستوى" data-en="Select Level">اختر المستوى</option>
                                    <option value="minor" {{ $report->ai_damage_level === 'minor' ? 'selected' : '' }} data-ar="طفيف" data-en="Minor">طفيف</option>
                                    <option value="moderate" {{ $report->ai_damage_level === 'moderate' ? 'selected' : '' }} data-ar="متوسط" data-en="Moderate">متوسط</option>
                                    <option value="severe" {{ $report->ai_damage_level === 'severe' ? 'selected' : '' }} data-ar="شديد" data-en="Severe">شديد</option>
                                    <option value="critical" {{ $report->ai_damage_level === 'critical' ? 'selected' : '' }} data-ar="حرج" data-en="Critical">حرج</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="الدرجة (1-10)" data-en="Score (1-10)">الدرجة (1-10)</label>
                                <input type="number" name="edit_ai_damage_score" min="1" max="10" value="{{ old('edit_ai_damage_score', $report->ai_damage_score) }}" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-charcoal mb-2" data-ar="تحليل AI" data-en="AI Analysis">تحليل AI</label>
                                <textarea name="edit_ai_analysis" rows="3" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm resize-none">{{ old('edit_ai_analysis', $report->ai_analysis) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Decision -->
                <div class="bg-white rounded-2xl shadow-sm border border-charcoal/10 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-charcoal/10 bg-gradient-to-r from-sand/10 to-sage/10">
                        <h2 class="text-lg font-bold text-charcoal flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-sand/20 to-sage/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <span data-ar="قرار الأدمن" data-en="Admin Decision">قرار الأدمن</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-2" data-ar="حالة الموافقة" data-en="Approval Status">حالة الموافقة</label>
                            <select name="edit_admin_approval_status" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                                <option value="" data-ar="اختر الحالة" data-en="Select Status">اختر الحالة</option>
                                <option value="pending" {{ $report->admin_approval_status === 'pending' || !$report->admin_approval_status ? 'selected' : '' }} data-ar="لم يُراجع" data-en="Not Reviewed">لم يُراجع</option>
                                <option value="approved" {{ $report->admin_approval_status === 'approved' ? 'selected' : '' }} data-ar="تمت الموافقة" data-en="Approved">تمت الموافقة</option>
                                <option value="rejected" {{ $report->admin_approval_status === 'rejected' ? 'selected' : '' }} data-ar="مرفوض" data-en="Rejected">مرفوض</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-2" data-ar="تقييمك (1-10)" data-en="Your Score (1-10)">تقييمك (1-10)</label>
                            <input type="number" name="edit_admin_damage_score" min="1" max="10" value="{{ old('edit_admin_damage_score', $report->admin_damage_score) }}" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-charcoal mb-2" data-ar="ملاحظات" data-en="Notes">ملاحظات</label>
                            <textarea name="edit_admin_notes" rows="3" class="w-full px-4 py-3 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sand/50 focus:ring-2 focus:ring-sand/20 transition-all text-sm resize-none" placeholder="أضف ملاحظاتك..." data-ar-placeholder="أضف ملاحظاتك..." data-en-placeholder="Add your notes...">{{ old('edit_admin_notes', $report->admin_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Reprocess with AI -->
                <div class="bg-white rounded-2xl shadow-sm border border-charcoal/10 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-charcoal/10 bg-gradient-to-r from-emerald-500/5 to-teal-500/5">
                        <h2 class="text-lg font-bold text-charcoal flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </span>
                            <span data-ar="إعادة المعالجة" data-en="Reprocess">إعادة المعالجة</span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-charcoal" data-ar="إعادة التحليل بالـ AI" data-en="Re-analyze with AI">إعادة التحليل بالـ AI</p>
                                <p class="text-xs text-charcoal/50 mt-1" data-ar="سيتم تحليل التقرير من جديد" data-en="The report will be re-analyzed">سيتم تحليل التقرير من جديد</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="reprocess_with_ai" value="1" class="sr-only peer" {{ old('reprocess_with_ai') ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-charcoal/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:right-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-teal-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-sand to-sage text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span data-ar="حفظ التغييرات" data-en="Save Changes">حفظ التغييرات</span>
                    </button>
                    <button type="button" onclick="if(confirmMessage('هل أنت متأكد من إلغاء التعديلات؟', 'Are you sure you want to cancel changes?')) window.location.href='{{ route('admin.reports') }}'" class="w-full px-6 py-3 bg-charcoal/5 text-charcoal rounded-xl font-bold hover:bg-charcoal/10 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span data-ar="إلغاء" data-en="Cancel">إلغاء</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function removeImage(imagePath) {
        if (confirmMessage('هل أنت متأكد من حذف هذه الصورة؟', 'Are you sure you want to delete this image?')) {
            const inputs = document.querySelectorAll('input[value="' + imagePath + '"]');
            inputs.forEach(input => {
                input.closest('.relative').remove();
            });
        }
    }

    function removeOldImage() {
        if (confirmMessage('هل أنت متأكد من حذف هذه الصورة؟', 'Are you sure you want to delete this image?')) {
            const input = document.getElementById('keep_old_image');
            input.value = '0';
            input.closest('.relative').remove();
        }
    }

    function addVideoLink() {
        const container = document.getElementById('video-links-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'flex items-center gap-3 p-3 bg-charcoal/5 rounded-xl';
        newDiv.innerHTML = `
            <div class="w-10 h-10 rounded-lg bg-charcoal/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." data-ar-placeholder="https://youtube.com/watch?v=..." data-en-placeholder="https://youtube.com/watch?v=..." class="flex-1 px-4 py-2.5 border-2 border-charcoal/10 rounded-xl focus:outline-none focus:border-sage/50 text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(newDiv);
    }
</script>
@endpush
