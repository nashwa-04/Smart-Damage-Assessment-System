@extends('admin.layouts.app')

@section('title', 'التقارير - نظام تقييم الأضرار الذكي')

@push('styles')
<style>
    .filter-toggle { transition: all 0.3s ease; }
    .filter-body { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease; }
    .filter-body.open { max-height: 500px; }
    .filter-input {
        background: rgba(255,255,255,0.6);
        border: 1.5px solid rgba(11, 11, 69, 0.08);
        transition: all 0.25s ease;
        font-size: 0.8rem;
        color: #0B0B45;
    }
    .filter-input:focus {
        background: #fff;
        border-color: #C9A97C;
        box-shadow: 0 0 0 3px rgba(201, 169, 124, 0.1);
        outline: none;
    }
    .filter-input::placeholder { color: rgba(11, 11, 69, 0.3); }
    .filter-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(11, 11, 69, 0.45);
        margin-bottom: 4px;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="mb-8 fade-in">
    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg bg-[#0B0B45]">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold" style="color: #0B0B45;" data-ar="جميع التقارير" data-en="All Reports">جميع التقارير</h2>
                    <p class="mt-1" style="color: rgba(11, 11, 69, 0.6);" data-ar="إدارة وعرض جميع التقارير" data-en="Manage and view all reports">إدارة وعرض جميع التقارير</p>
                </div>
            </div>
            <a href="{{ route('admin.reports.create') }}" class="px-6 py-3 text-white rounded-xl font-bold flex items-center gap-2 transition-all shadow-md hover:shadow-lg hover:opacity-90" style="background: linear-gradient(135deg, #C9A97C, #B08D5F);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-ar="تقرير جديد" data-en="New Report">تقرير جديد</span>
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-6 fade-in">
    <div class="rounded-2xl p-5 shadow-lg" style="background: rgba(145, 166, 138, 0.1); border: 2px solid rgba(145, 166, 138, 0.3);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #91A68A;">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <p class="font-bold text-lg" style="color: #4A6040;">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@php $activeFilters = collect(request()->only(['search', 'status', 'ai_damage_level', 'admin_approval_status', 'from_date', 'to_date', 'user_id']))->filter()->count(); @endphp

<div class="rounded-2xl shadow-lg overflow-hidden fade-in mb-6" style="background: #0B0B45;">
    <div class="px-6 py-4 flex items-center justify-between cursor-pointer filter-toggle" onclick="document.getElementById('filterBody').classList.toggle('open'); this.querySelector('.chevron').classList.toggle('rotate-180')">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <span class="font-bold text-sm" style="color: #FAFAFA;" data-ar="فلترة متقدمة" data-en="Advanced Filter">فلترة متقدمة</span>
            @if($activeFilters > 0)
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" style="background: #C9A97C; color: #0B0B45;">{{ $activeFilters }} <span data-ar="نشط" data-en="active">نشط</span></span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($activeFilters > 0)
                <a href="{{ route('admin.reports') }}" class="text-[10px] font-bold flex items-center gap-1" style="color: rgba(250,250,250,0.4);" onclick="event.stopPropagation()" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(250,250,250,0.4)'">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span data-ar="مسح الكل" data-en="Clear All">مسح الكل</span>
                </a>
            @endif
            <svg class="w-4 h-4 chevron transition-transform duration-300 {{ $activeFilters > 0 ? 'rotate-180' : '' }}" style="color: rgba(250,250,250,0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    <div id="filterBody" class="filter-body {{ $activeFilters > 0 ? 'open' : '' }}">
        <div class="px-6 pb-5 pt-2" style="border-top: 1px solid rgba(201, 169, 124, 0.1);">
            <form method="GET" action="{{ route('admin.reports') }}">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="filter-label block" data-ar="بحث" data-en="Search">بحث</label>
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2" style="color: rgba(11, 11, 69, 0.25);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" name="search" value="{{ request()->input('search') }}" placeholder="موقع، وصف، رقم..." data-ar-placeholder="موقع، وصف، رقم..." data-en-placeholder="Location, description, number..." class="filter-input w-full rounded-lg pr-9 pl-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="الحالة" data-en="Status">الحالة</label>
                        <select name="status" class="filter-input w-full rounded-lg px-3 py-2">
                            <option value="" data-ar="الكل" data-en="All">الكل</option>
                            <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }} data-ar="قيد الانتظار" data-en="Pending">قيد الانتظار</option>
                            <option value="processing" {{ request()->input('status') == 'processing' ? 'selected' : '' }} data-ar="قيد المعالجة" data-en="Processing">قيد المعالجة</option>
                            <option value="pending_approval" {{ request()->input('status') == 'pending_approval' ? 'selected' : '' }} data-ar="بانتظار الموافقة" data-en="Pending Approval">بانتظار الموافقة</option>
                            <option value="completed" {{ request()->input('status') == 'completed' ? 'selected' : '' }} data-ar="مكتمل" data-en="Completed">مكتمل</option>
                            <option value="approved" {{ request()->input('status') == 'approved' ? 'selected' : '' }} data-ar="تمت الموافقة" data-en="Approved">تمت الموافقة</option>
                            <option value="rejected" {{ request()->input('status') == 'rejected' ? 'selected' : '' }} data-ar="غير مقبول" data-en="Rejected">غير مقبول</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</label>
                        <select name="ai_damage_level" class="filter-input w-full rounded-lg px-3 py-2">
                            <option value="" data-ar="الكل" data-en="All">الكل</option>
                            <option value="minor" {{ request()->input('ai_damage_level') == 'minor' ? 'selected' : '' }} data-ar="طفيف" data-en="Minor">طفيف</option>
                            <option value="moderate" {{ request()->input('ai_damage_level') == 'moderate' ? 'selected' : '' }} data-ar="متوسط" data-en="Moderate">متوسط</option>
                            <option value="severe" {{ request()->input('ai_damage_level') == 'severe' ? 'selected' : '' }} data-ar="شديد" data-en="Severe">شديد</option>
                            <option value="critical" {{ request()->input('ai_damage_level') == 'critical' ? 'selected' : '' }} data-ar="حرج" data-en="Critical">حرج</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="الموافقة" data-en="Approval">الموافقة</label>
                        <select name="admin_approval_status" class="filter-input w-full rounded-lg px-3 py-2">
                            <option value="" data-ar="الكل" data-en="All">الكل</option>
                            <option value="pending" {{ request()->input('admin_approval_status') == 'pending' ? 'selected' : '' }} data-ar="لم يُراجع" data-en="Not Reviewed">لم يُراجع</option>
                            <option value="approved" {{ request()->input('admin_approval_status') == 'approved' ? 'selected' : '' }} data-ar="تمت الموافقة" data-en="Approved">تمت الموافقة</option>
                            <option value="rejected" {{ request()->input('admin_approval_status') == 'rejected' ? 'selected' : '' }} data-ar="مرفوض" data-en="Rejected">مرفوض</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="من تاريخ" data-en="From Date">من تاريخ</label>
                        <input type="date" name="from_date" value="{{ request()->input('from_date') }}" class="filter-input w-full rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="إلى تاريخ" data-en="To Date">إلى تاريخ</label>
                        <input type="date" name="to_date" value="{{ request()->input('to_date') }}" class="filter-input w-full rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="filter-label block" data-ar="المستخدم" data-en="User">المستخدم</label>
                        <select name="user_id" class="filter-input w-full rounded-lg px-3 py-2">
                            <option value="" data-ar="الكل" data-en="All">الكل</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->input('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 rounded-lg font-bold flex items-center justify-center gap-2 text-xs transition-all" style="background: #C9A97C; color: #0B0B45;" onmouseover="this.style.background='#B08D5F'" onmouseout="this.style.background='#C9A97C'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span data-ar="تطبيق الفلتر" data-en="Apply Filter">تطبيق الفلتر</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(to right, rgba(11, 11, 69, 0.9), #0B0B45);">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white">#</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="الصورة" data-en="Image">الصورة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="المستخدم" data-en="User">المستخدم</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="الموقع" data-en="Location">الموقع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</th>

                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="الموافقة" data-en="Approval">الموافقة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="التاريخ" data-en="Date">التاريخ</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-white" data-ar="الإجراءات" data-en="Actions">الإجراءات</th>
                </tr>
            </thead>
            <tbody style="border-top: 1px solid rgba(201, 169, 124, 0.4);">
                @forelse($reports as $report)
                <tr class="transition-colors" style="border-bottom: 1px solid rgba(201, 169, 124, 0.3);">
                    <td class="px-6 py-4">
                        <span class="font-bold" style="color: #C9A97C;">#{{ $report->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $images = $report->images ?? [];
                            $firstImage = count($images) > 0 ? $images[0] : $report->image_path;
                            $imageCount = count($images);
                        @endphp
                        <div class="flex items-center gap-3">
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" alt="صورة" class="w-14 h-14 object-cover rounded-xl shadow-md">
                                @if($imageCount > 1)
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full">+{{ $imageCount - 1 }}</span>
                                @endif
                            @else
                                <div class="w-14 h-14 bg-slate-200 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($report->pdf_file)
                                <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">PDF</span>
                            @endif
                            @if($report->video_links && count($report->video_links) > 0)
                                <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">فيديو</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium" style="color: #0B0B45;">{{ $report->user->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span style="color: rgba(11, 11, 69, 0.8);">{{ $report->raw_location }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $damageColors = [
                                'critical' => '#dc2626',
                                'severe' => '#ea580c',
                                'moderate' => '#ca8a04',
                                'minor' => '#16a34a',
                                'high' => '#dc2626',
                                'medium' => '#ca8a04',
                                'low' => '#16a34a'
                            ];
                            $damageLabels = [
                                'critical' => 'حرج',
                                'severe' => 'شديد',
                                'moderate' => 'متوسط',
                                'minor' => 'طفيف',
                                'high' => 'عالي',
                                'medium' => 'متوسط',
                                'low' => 'منخفض'
                            ];
                            $damageLabelsEn = [
                                'critical' => 'Critical',
                                'severe' => 'Severe',
                                'moderate' => 'Moderate',
                                'minor' => 'Minor',
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low'
                            ];
                            $colorHex = $damageColors[$report->ai_damage_level] ?? '#64748b';
                            $label = $damageLabels[$report->ai_damage_level] ?? 'غير محدد';
                            $labelEn = $damageLabelsEn[$report->ai_damage_level] ?? 'Unknown';
                            $scoreDisplay = $report->ai_damage_score ? $report->ai_damage_score . '/10' : '';
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-sm font-bold" style="color: {{ $colorHex }};" data-ar="{{ $label }}" data-en="{{ $labelEn }}">
                                {{ $label }}
                            </span>
                            @if($scoreDisplay)
                                <span class="text-xs font-bold" style="color: rgba(11, 11, 69, 0.4);">{{ $scoreDisplay }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $isAiRejected = $report->ai_damage_level === 'rejected';
                            $isAdminRejected = $report->admin_approval_status === 'rejected' && !is_null($report->approved_by);
                            
                            $approvalSubtext = null;
                            $approvalSubtextEn = null;
                            if ($isAiRejected && $isAdminRejected) {
                                $approvalLabel = 'مرفوض';
                                $approvalLabelEn = 'Rejected';
                                $approvalSubtext = 'بواسطة AI والأدمن';
                                $approvalSubtextEn = 'By AI & Admin';
                                $approvalHex = '#dc2626';
                            } elseif ($isAiRejected) {
                                $approvalLabel = 'مرفوض';
                                $approvalLabelEn = 'Rejected';
                                $approvalSubtext = 'بواسطة AI';
                                $approvalSubtextEn = 'By AI';
                                $approvalHex = '#dc2626';
                            } elseif ($isAdminRejected) {
                                $approvalLabel = 'مرفوض';
                                $approvalLabelEn = 'Rejected';
                                $approvalSubtext = 'بواسطة الأدمن';
                                $approvalSubtextEn = 'By Admin';
                                $approvalHex = '#dc2626';
                            } elseif ($report->admin_approval_status === 'approved') {
                                $approvalLabel = 'تمت الموافقة';
                                $approvalLabelEn = 'Approved';
                                $approvalHex = '#16a34a';
                            } else {
                                $approvalLabel = 'لم يُراجع';
                                $approvalLabelEn = 'Not Reviewed';
                                $approvalHex = '#64748b';
                            }
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-sm font-bold" style="color: {{ $approvalHex }};" data-ar="{{ $approvalLabel }}" data-en="{{ $approvalLabelEn }}">
                                {{ $approvalLabel }}
                            </span>
                            @if($approvalSubtext)
                                <span class="text-[10px] font-bold" style="color: rgba(11, 11, 69, 0.5);" data-ar="{{ $approvalSubtext }}" data-en="{{ $approvalSubtextEn }}">{{ $approvalSubtext }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 align-middle">
                        @php
                            $isToday = $report->created_at->isToday();
                            $isYesterday = $report->created_at->isYesterday();
                        @endphp
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: rgba(11, 11, 69, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex flex-col leading-tight">
                                @if($isToday)
                                    <span class="text-xs font-bold" style="color: #16a34a;" data-ar="اليوم" data-en="Today">اليوم</span>
                                @elseif($isYesterday)
                                    <span class="text-xs font-bold" style="color: #ca8a04;" data-ar="أمس" data-en="Yesterday">أمس</span>
                                @else
                                    <span class="text-xs font-bold" style="color: #0B0B45;">{{ $report->created_at->format('d M Y') }}</span>
                                @endif
                                <span class="text-[11px] font-mono" style="color: rgba(11, 11, 69, 0.4);">{{ $report->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.reports.show', $report) }}" title="View">
                                <svg class="w-5 h-5 transition-colors" style="color: rgba(11, 11, 69, 0.4);" onmouseover="this.style.color='#0B0B45'" onmouseout="this.style.color='rgba(11, 11, 69, 0.4)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.reports.edit', $report) }}" title="Edit">
                                <svg class="w-5 h-5 transition-colors" style="color: rgba(11, 11, 69, 0.4);" onmouseover="this.style.color='#C9A97C'" onmouseout="this.style.color='rgba(11, 11, 69, 0.4)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline-block" onsubmit="return confirmMessage('هل أنت متأكد من حذف هذا التقرير؟', 'Are you sure you want to delete this report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete">
                                    <svg class="w-5 h-5 transition-colors" style="color: rgba(11, 11, 69, 0.4);" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='rgba(11, 11, 69, 0.4)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-slate-500 text-lg font-medium" data-ar="لا توجد تقارير" data-en="No reports found">لا توجد تقارير</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reports->hasPages())
<div class="mt-6 flex justify-center">
    {{ $reports->links() }}
</div>
@endif
@endsection
