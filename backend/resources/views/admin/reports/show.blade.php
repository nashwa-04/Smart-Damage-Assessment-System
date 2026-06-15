@extends('admin.layouts.app')

@section('title', 'تفاصيل التقرير #' . $report->id)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }
    .image-card {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .image-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    .image-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .lightbox.active {
        display: flex;
    }
    .section-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(11, 11, 69, 0.08);
    }
    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 10px;
        border-radius: 5px;
        background: linear-gradient(to right, #0B0B45, #C9A97C);
        outline: none;
    }
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #C9A97C;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    input[type="range"]::-moz-range-thumb {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #C9A97C;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div>
    <!-- Header -->
    <div class="mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports') }}" class="w-10 h-10 rounded-xl flex items-center justify-center hover:opacity-80 transition-colors" style="background: rgba(201, 169, 124, 0.1);">
                    <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold" style="color: #0B0B45;" data-ar="التقرير #{{ $report->id }}" data-en="Report #{{ $report->id }}">التقرير #{{ $report->id }}</h1>
                    <p class="text-xs mt-0.5" style="color: rgba(11, 11, 69, 0.5);">{{ $report->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.edit', $report) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg hover:opacity-80 transition-colors text-xs font-bold" style="background: rgba(120, 169, 193, 0.1); color: #78A9C1;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span data-ar="تعديل" data-en="Edit">تعديل</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4">
        <div class="rounded-xl p-4 flex items-center gap-3" style="background: rgba(145, 166, 138, 0.1); border: 1px solid rgba(145, 166, 138, 0.3);">
            <svg class="w-5 h-5 flex-shrink-0" style="color: #5A7050;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm font-bold" style="color: #4A6040;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Approval Actions - Always Visible -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(201, 169, 124, 0.05);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-ar="إجراءات الموافقة" data-en="Approval Actions">إجراءات الموافقة</span>
                    </h2>
                </div>
                <div class="p-4 sm:p-5">
                    @if($report->admin_approval_status === 'approved')
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(145, 166, 138, 0.2);">
                                <svg class="w-6 h-6" style="color: #5A7050;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold" style="color: #0B0B45;" data-ar="تمت الموافقة على التقرير بواسطة: {{ $report->approvedBy ? $report->approvedBy->name : 'غير معروف' }}" data-en="Report approved by: {{ $report->approvedBy ? $report->approvedBy->name : 'Unknown' }}">تمت الموافقة على التقرير بواسطة: <span style="color: #78A9C1;">{{ $report->approvedBy ? $report->approvedBy->name : 'غير معروف' }}</span></p>
                                @if($report->admin_notes)
                                    <p class="text-sm mt-1" style="color: rgba(11, 11, 69, 0.6);"><strong data-ar="ملاحظات:" data-en="Notes:">ملاحظات:</strong> {{ $report->admin_notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button onclick="openRejectModal()" class="flex-1 px-5 py-4 text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #9C5D4D, #7A4538);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span data-ar="تغيير قرار الرفض" data-en="Change to Rejected">تغيير قرار الرفض</span>
                            </button>
                        </div>
                    @elseif($report->admin_approval_status === 'rejected')
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(156, 93, 77, 0.15);">
                                <svg class="w-6 h-6" style="color: #9C5D4D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold" style="color: #0B0B45;" data-ar="تم رفض التقرير بواسطة: {{ $report->approvedBy ? $report->approvedBy->name : 'غير معروف' }}" data-en="Report rejected by: {{ $report->approvedBy ? $report->approvedBy->name : 'Unknown' }}">تم رفض التقرير بواسطة: <span style="color: #78A9C1;">{{ $report->approvedBy ? $report->approvedBy->name : 'غير معروف' }}</span></p>
                                @if($report->admin_notes)
                                    <p class="text-sm mt-1" style="color: rgba(11, 11, 69, 0.6);"><strong data-ar="ملاحظات:" data-en="Notes:">ملاحظات:</strong> {{ $report->admin_notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <form action="{{ route('admin.reports.approve', $report) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-5 py-4 text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #91A68A, #7A8F70);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span data-ar="الموافقة" data-en="Approve">الموافقة</span>
                                </button>
                            </form>
                            <button onclick="openRejectModal()" class="flex-1 px-5 py-4 text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #9C5D4D, #7A4538);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span data-ar="رفض التقرير" data-en="Reject Report">رفض التقرير</span>
                            </button>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-4">
                            <form action="{{ route('admin.reports.approve', $report) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-5 py-4 text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #91A68A, #7A8F70);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span data-ar="الموافقة على التقرير" data-en="Approve Report">الموافقة على التقرير</span>
                                </button>
                            </form>
                            <button onclick="openRejectModal()" class="flex-1 px-5 py-4 text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #9C5D4D, #7A4538);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span data-ar="رفض التقرير" data-en="Reject Report">رفض التقرير</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Report Info -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(201, 169, 124, 0.05);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-ar="معلومات التقرير" data-en="Report Information">معلومات التقرير</span>
                    </h2>
                </div>
                <div class="p-4 sm:p-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(11, 11, 69, 0.05);">
                            <dt class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="المستخدم" data-en="User">المستخدم</dt>
                            <dd class="font-bold" style="color: #0B0B45;">{{ $report->user->name }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(11, 11, 69, 0.05);">
                            <dt class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="تاريخ الإنشاء" data-en="Created At">تاريخ الإنشاء</dt>
                            <dd style="color: #0B0B45;">{{ $report->created_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(11, 11, 69, 0.05);">
                            <dt class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="الموقع" data-en="Location">الموقع</dt>
                            <dd style="color: #0B0B45;">{{ $report->raw_location }}</dd>
                        </div>
                        @if($report->ai_location)
                        <div class="flex justify-between items-center py-3 border-b" style="border-color: rgba(11, 11, 69, 0.05);">
                            <dt class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="الموقع المحسّن" data-en="Enhanced Location">الموقع المحسّن</dt>
                            <dd class="font-bold" style="color: #78A9C1;">{{ $report->ai_location }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between items-center py-3">
                            <dt class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="الحالة" data-en="Status">الحالة</dt>
                            <dd>
                                @php
                                    $statusColors = [
                                        'completed' => '#16a34a',
                                        'pending_approval' => '#2563eb',
                                        'pending' => '#ca8a04',
                                        'processing' => '#4f46e5',
                                        'approved' => '#059669',
                                        'rejected' => '#dc2626'
                                    ];
                                    $statusLabels = [
                                        'completed' => 'مكتمل',
                                        'pending_approval' => 'بانتظار الموافقة',
                                        'pending' => 'قيد الانتظار',
                                        'processing' => 'قيد المعالجة',
                                        'approved' => 'تمت الموافقة',
                                        'rejected' => 'غير مقبول'
                                    ];
                                    $statusLabelsEn = [
                                        'completed' => 'Completed',
                                        'pending_approval' => 'Pending Approval',
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected'
                                    ];
                                    $statusColor = $statusColors[$report->status] ?? '#64748b';
                                    $statusLabel = $statusLabels[$report->status] ?? 'غير محدد';
                                    $statusLabelEn = $statusLabelsEn[$report->status] ?? 'Unknown';
                                @endphp
                                <span class="text-sm font-bold" style="color: {{ $statusColor }};" data-ar="{{ $statusLabel }}" data-en="{{ $statusLabelEn }}">
                                    {{ $statusLabel }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Images -->
            @php
                $images = $report->images ?? [];
                $hasImages = count($images) > 0;
                $hasOldImage = !empty($report->image_path);
            @endphp
            @if($hasImages || $hasOldImage)
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(120, 169, 193, 0.05);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #78A9C1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span data-ar="صور التقرير ({{ $hasImages ? count($images) : 0 }}{{ $hasOldImage ? '+1' : '' }})" data-en="Report Images ({{ $hasImages ? count($images) : 0 }}{{ $hasOldImage ? '+1' : '' }})">صور التقرير ({{ $hasImages ? count($images) : 0 }}{{ $hasOldImage ? '+1' : '' }})</span>
                    </h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="image-grid">
                        @if($hasImages)
                            @foreach($images as $image)
                                <div class="image-card" onclick="openLightbox('{{ asset('storage/' . $image) }}')">
                                    <img src="{{ asset('storage/' . $image) }}" alt="صورة">
                                </div>
                            @endforeach
                        @endif
                        @if($hasOldImage && !in_array($report->image_path, $images))
                            <div class="image-card" onclick="openLightbox('{{ asset('storage/' . $report->image_path) }}')">
                                <img src="{{ asset('storage/' . $report->image_path) }}" alt="صورة">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Map -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(120, 169, 193, 0.05);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #78A9C1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span data-ar="الموقع على الخريطة" data-en="Location on Map">الموقع على الخريطة</span>
                    </h2>
                </div>
                <div class="p-4 sm:p-5">
                    <div id="mini-map" style="height: 350px; border-radius: 12px;"></div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
            <!-- AI Damage Level -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(11, 11, 69, 0.03);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #0B0B45;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span data-ar="مستوى الضرر (AI)" data-en="Damage Level (AI)">مستوى الضرر (AI)</span>
                    </h2>
                </div>
                <div class="p-6 text-center">
                    @php
                        $damageColors = [
                            'minor' => '#16a34a',
                            'moderate' => '#ca8a04',
                            'severe' => '#dc2626',
                            'critical' => '#ea580c',
                            'low' => '#16a34a',
                            'medium' => '#ca8a04',
                            'high' => '#dc2626'
                        ];
                        $damageLabels = [
                            'minor' => 'طفيف',
                            'moderate' => 'متوسط',
                            'severe' => 'شديد',
                            'critical' => 'حرج',
                            'low' => 'منخفض',
                            'medium' => 'متوسط',
                            'high' => 'عالي'
                        ];
                        $damageLabelsEn = [
                            'minor' => 'Minor',
                            'moderate' => 'Moderate',
                            'severe' => 'Severe',
                            'critical' => 'Critical',
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High'
                        ];
                        $damageColor = $damageColors[$report->ai_damage_level] ?? '#64748b';
                        $label = $damageLabels[$report->ai_damage_level] ?? $report->ai_damage_level ?? 'غير محدد';
                        $labelEn = $damageLabelsEn[$report->ai_damage_level] ?? 'Unknown';
                        $score = $report->ai_damage_score ?? 5;
                    @endphp
                    <div class="inline-block text-white px-8 py-4 rounded-xl text-2xl font-bold shadow-lg mb-4" style="background-color: {{ $damageColor }};" data-ar="{{ $label }}" data-en="{{ $labelEn }}">
                        {{ $label }}
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <span class="text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="التقييم الرقمي" data-en="Score">التقييم الرقمي</span>
                            <span class="text-2xl font-bold" style="color: #0B0B45;">{{ $score }}/10</span>
                        </div>
                        <div class="w-full max-w-[200px] mx-auto rounded-full h-3" style="background: rgba(11, 11, 69, 0.1);">
                            <div class="h-3 rounded-full transition-all" style="width: {{ ($score / 10) * 100 }}%; background-color: {{ $damageColor }};"></div>
                        </div>
                    </div>
                    <p class="text-xs" style="color: rgba(11, 11, 69, 0.5);" data-ar="تم التحديد بواسطة الذكاء الاصطناعي" data-en="Determined by AI">تم التحديد بواسطة الذكاء الاصطناعي</p>
                </div>
            </div>

            <!-- Admin Assessment -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08); background: rgba(201, 169, 124, 0.05);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-ar="تقييم الأدمن" data-en="Admin Assessment">تقييم الأدمن</span>
                    </h2>
                </div>
                <div class="p-4 sm:p-6">
                    @if($report->admin_damage_score)
                        <div class="text-center mb-4">
                            <div class="text-4xl font-bold mb-2" style="color: #C9A97C;">{{ $report->admin_damage_score }}/10</div>
                            <div class="rounded-lg p-4" style="background: linear-gradient(to right, rgba(201, 169, 124, 0.1), rgba(120, 169, 193, 0.1)); border: 1px solid rgba(201, 169, 124, 0.2);">
                                <p class="text-xs mb-2" style="color: rgba(11, 11, 69, 0.6);" data-ar="التقييم النهائي" data-en="Final Score">التقييم النهائي</p>
                                @php
                                    $finalScore = round((($report->ai_damage_score ?? 5) + $report->admin_damage_score) / 2);
                                @endphp
                                <div class="text-3xl font-bold" style="color: #C9A97C;">{{ $finalScore }}/10</div>
                            </div>
                        </div>
                        @if($report->admin_notes)
                            <div class="mt-4 p-3 rounded-lg" style="background: rgba(11, 11, 69, 0.05);">
                                <p class="text-xs mb-1" style="color: rgba(11, 11, 69, 0.6);" data-ar="ملاحظات الأدمن" data-en="Admin Notes">ملاحظات الأدمن</p>
                                <p class="text-sm" style="color: #0B0B45;">{{ $report->admin_notes }}</p>
                            </div>
                        @endif
                    @else
                        <form action="{{ route('admin.reports.damage-assessment', $report) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="تقييمك الشخصي (1-10)" data-en="Your Score (1-10)">تقييمك الشخصي (1-10)</label>
                                <input type="range" name="admin_damage_score" id="admin_damage_score" min="1" max="10" step="1" value="{{ $report->ai_damage_score ?? 5 }}" class="w-full rounded-lg appearance-none cursor-pointer" oninput="updateScoreDisplay(this.value)">
                                <div class="flex justify-between text-xs mt-2" style="color: rgba(11, 11, 69, 0.5);">
                                    <span>1</span>
                                    <span id="score-display" class="text-2xl font-bold" style="color: #C9A97C;">{{ $report->ai_damage_score ?? 5 }}</span>
                                    <span>10</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="ملاحظاتك" data-en="Your Notes">ملاحظاتك</label>
                                <textarea name="admin_notes" rows="3" class="w-full px-4 py-3 border-2 rounded-lg focus:outline-none transition-all text-sm resize-none" style="border-color: rgba(11, 11, 69, 0.1);" placeholder="أضف ملاحظاتك حول تقييم الضرر..." data-ar-placeholder="أضف ملاحظاتك حول تقييم الضرر..." data-en-placeholder="Add your notes about the damage assessment...">{{ $report->admin_notes ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="w-full px-5 py-3 text-white rounded-lg font-bold hover:opacity-90 transition-all text-sm" style="background: linear-gradient(135deg, #C9A97C, #78A9C1);">
                                <span data-ar="حفظ التقييم" data-en="Save Assessment">حفظ التقييم</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Description & Analysis -->
            <div class="section-card rounded-xl shadow-sm">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b" style="border-color: rgba(11, 11, 69, 0.08);">
                    <h2 class="text-base font-bold flex items-center gap-2" style="color: #0B0B45;">
                        <svg class="w-5 h-5" style="color: #0B0B45;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span data-ar="الوصف والتحليل" data-en="Description & Analysis">الوصف والتحليل</span>
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($report->raw_description)
                    <div>
                        <h3 class="text-xs font-bold mb-2" style="color: rgba(11, 11, 69, 0.6);" data-ar="الوصف الأصلي" data-en="Original Description">الوصف الأصلي</h3>
                        <p class="text-sm p-3 rounded-lg" style="color: #0B0B45; background: rgba(11, 11, 69, 0.05);">{{ $report->raw_description }}</p>
                    </div>
                    @endif
                    @if($report->ai_analysis)
                    <div>
                        <h3 class="text-xs font-bold mb-2" style="color: rgba(11, 11, 69, 0.6);" data-ar="تحليل الذكاء الاصطناعي" data-en="AI Analysis">تحليل الذكاء الاصطناعي</h3>
                        <p class="text-sm p-3 rounded-lg" style="color: #0B0B45; background: rgba(120, 169, 193, 0.08); border: 1px solid rgba(120, 169, 193, 0.15);">{{ $report->ai_analysis }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('admin.reports.edit', $report) }}" class="w-full px-5 py-4 text-white rounded-lg font-bold shadow-md hover:shadow-lg hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm" style="background: linear-gradient(135deg, #C9A97C, #78A9C1);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span data-ar="تعديل التقرير" data-en="Edit Report">تعديل التقرير</span>
                </a>
                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirmMessage('هل أنت متأكد من حذف هذا التقرير؟', 'Are you sure you want to delete this report?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-3 rounded-lg font-bold hover:opacity-80 transition-colors flex items-center justify-center gap-2 text-sm" style="background: rgba(156, 93, 77, 0.1); color: #9C5D4D;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span data-ar="حذف التقرير" data-en="Delete Report">حذف التقرير</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white px-4 py-2 rounded-lg text-sm" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px);" onclick="closeLightbox()" data-ar="إغلاق" data-en="Close">إغلاق</button>
    <img id="lightbox-img" src="" alt="صورة مكبرة">
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background: rgba(0, 0, 0, 0.5);">
    <div class="rounded-xl shadow-2xl max-w-md w-full" style="background: #FAFAFA;">
        <div class="px-6 py-4 rounded-t-xl" style="background: linear-gradient(135deg, #9C5D4D, #7A4538);">
            <h3 class="text-base font-bold text-white" data-ar="رفض التقرير" data-en="Reject Report">رفض التقرير</h3>
        </div>
        <form action="{{ route('admin.reports.reject', $report) }}" method="POST" class="p-5">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" style="color: #0B0B45;" data-ar="سبب الرفض" data-en="Rejection Reason">سبب الرفض</label>
                <textarea name="admin_notes" id="reject_notes" rows="4" class="w-full px-4 py-3 border-2 rounded-lg focus:outline-none transition-all resize-none" style="border-color: rgba(11, 11, 69, 0.1);" placeholder="اشرح سبب الرفض..." data-ar-placeholder="اشرح سبب الرفض..." data-en-placeholder="Explain the reason for rejection..." required></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 rounded-lg font-bold hover:opacity-80 transition-colors text-sm" style="background: rgba(11, 11, 69, 0.1); color: #0B0B45;" data-ar="إلغاء" data-en="Cancel">إلغاء</button>
                <button type="submit" class="flex-1 px-4 py-2.5 text-white rounded-lg font-bold hover:opacity-90 transition-all text-sm" style="background: linear-gradient(135deg, #9C5D4D, #7A4538);" data-ar="تأكيد الرفض" data-en="Confirm Rejection">تأكيد الرفض</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if($report->latitude && $report->longitude)
    const miniMap = L.map('mini-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 13);
    const showTileLayers = {
        ar: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }),
        en: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap &copy; CARTO' })
    };
    let showCurrentTile = showTileLayers[localStorage.getItem('lang') || 'ar'];
    showCurrentTile.addTo(miniMap);
    L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(miniMap);

    window.rebuildMapPopups = function(lang) {
        if (showCurrentTile) miniMap.removeLayer(showCurrentTile);
        showCurrentTile = showTileLayers[lang] || showTileLayers['ar'];
        showCurrentTile.addTo(miniMap);
    };
    @endif

    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.add('active');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('reject_notes').value = '';
    }

    function updateScoreDisplay(value) {
        document.getElementById('score-display').textContent = value;
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeLightbox();
            closeRejectModal();
        }
    });
</script>
@endpush
