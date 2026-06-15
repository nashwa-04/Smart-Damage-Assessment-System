@extends('admin.layouts.app')

@section('title', 'لوحة القيادة - نظام تقييم الأضرار الذكي')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -12px rgba(45, 62, 78, 0.2);
    }
    .chart-header {
        background: linear-gradient(135deg, #0B0B45 0%, rgba(11, 11, 69, 0.9) 100%);
    }
</style>
@endpush

@section('content')
<div class="mb-4 sm:mb-5 fade-in">
    <div class="glass-card rounded-2xl p-3 sm:p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-sand to-sage flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-charcoal" data-ar="لوحة القيادة" data-en="Dashboard">لوحة القيادة</h2>
                    <p class="text-charcoal/50 text-[10px] sm:text-xs" data-ar="نظرة عامة على التقارير والإحصائيات" data-en="Overview of reports and statistics">نظرة عامة على التقارير والإحصائيات</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="pulse-dot w-2 h-2 rounded-full bg-sage"></span>
                <span class="text-[10px] sm:text-xs text-charcoal/50" data-ar="متصل" data-en="Online">متصل</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8 fade-in">
    <div class="stat-card glass-card rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-5 shadow-lg">
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
            <div class="w-9 h-9 sm:w-11 sm:h-11 lg:w-12 lg:h-12 shrink-0 rounded-lg sm:rounded-xl bg-gradient-to-br from-sand to-sand/80 flex items-center justify-center shadow-lg shadow-sand/20">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-charcoal/60 text-[10px] sm:text-xs lg:text-sm truncate" data-ar="إجمالي التقارير" data-en="Total Reports">إجمالي التقارير</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-sand mt-0.5">{{ $totalReports }}</p>
            </div>
        </div>
    </div>

    <div class="stat-card glass-card rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-5 shadow-lg">
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
            <div class="w-9 h-9 sm:w-11 sm:h-11 lg:w-12 lg:h-12 shrink-0 rounded-lg sm:rounded-xl bg-gradient-to-br from-sage to-sage/80 flex items-center justify-center shadow-lg shadow-sage/20">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-charcoal/60 text-[10px] sm:text-xs lg:text-sm truncate" data-ar="مكتملة" data-en="Completed">مكتملة</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-sage mt-0.5">{{ $completedReports }}</p>
            </div>
        </div>
    </div>

    <div class="stat-card glass-card rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-5 shadow-lg">
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
            <div class="w-9 h-9 sm:w-11 sm:h-11 lg:w-12 lg:h-12 shrink-0 rounded-lg sm:rounded-xl bg-gradient-to-br from-charcoal to-charcoal/80 flex items-center justify-center shadow-lg shadow-charcoal/20">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-charcoal/60 text-[10px] sm:text-xs lg:text-sm truncate" data-ar="قيد الانتظار" data-en="Pending">قيد الانتظار</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-charcoal mt-0.5">{{ $pendingReports }}</p>
            </div>
        </div>
    </div>

    <div class="stat-card glass-card rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-5 shadow-lg">
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
            <div class="w-9 h-9 sm:w-11 sm:h-11 lg:w-12 lg:h-12 shrink-0 rounded-lg sm:rounded-xl bg-gradient-to-br from-charcoal/80 to-charcoal/60 flex items-center justify-center shadow-lg shadow-charcoal/20">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-charcoal/60 text-[10px] sm:text-xs lg:text-sm truncate" data-ar="المستخدمين" data-en="Users">المستخدمين</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-bold text-charcoal mt-0.5">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8 fade-in">
    <div class="glass-card rounded-xl sm:rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
        <div class="chart-header px-4 sm:px-6 py-3 sm:py-4">
            <h3 class="text-sm sm:text-lg font-bold text-light flex items-center gap-2">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 11H9V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                <span data-ar="توزيع الأضرار" data-en="Damage Distribution">توزيع الأضرار</span>
            </h3>
        </div>
        <div class="p-6 flex-1 flex items-center justify-center">
            <div class="w-full h-48 relative flex justify-center">
                <canvas id="damageChart"></canvas>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-xl sm:rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
        <div class="chart-header px-4 sm:px-6 py-3 sm:py-4">
            <h3 class="text-sm sm:text-lg font-bold text-light flex items-center gap-2">
                <svg class="w-5 h-5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span data-ar="حالة التقارير" data-en="Report Status">حالة التقارير</span>
            </h3>
        </div>
        <div class="p-6 flex-1 flex items-center justify-center">
            <div class="w-full h-48 relative flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-xl sm:rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
        <div class="chart-header px-4 sm:px-6 py-3 sm:py-4">
            <h3 class="text-sm sm:text-lg font-bold text-light flex items-center gap-2">
                <svg class="w-5 h-5 text-sand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <span data-ar="التقارير خلال آخر 7 أيام" data-en="Reports Last 7 Days">التقارير خلال آخر 7 أيام</span>
            </h3>
        </div>
        <div class="p-4 sm:p-6 flex-1 flex items-center justify-center">
            <div class="w-full h-48 relative flex justify-center">
                <canvas id="timeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
        <div class="chart-header px-6 py-4">
            <h3 class="text-lg font-bold text-light flex items-center gap-2">
                <svg class="w-5 h-5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span data-ar="التقارير حسب الموقع" data-en="Reports by Location">التقارير حسب الموقع</span>
            </h3>
        </div>
        <div class="p-6 flex-1 flex items-center justify-center">
            <div class="w-full h-48 relative flex justify-center">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
    <div class="px-6 py-4 flex items-center justify-between" style="background: linear-gradient(to right, rgba(11, 11, 69, 0.9), #0B0B45);">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5" style="color: #C9A97C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span data-ar="أحدث التقارير" data-en="Recent Reports">أحدث التقارير</span>
        </h3>
        <a href="{{ route('admin.reports') }}" class="text-xs font-bold flex items-center gap-1 px-3 py-1.5 rounded-lg transition-all" style="background: rgba(201,169,124,0.15); color: #C9A97C;" onmouseover="this.style.background='rgba(201,169,124,0.3)'" onmouseout="this.style.background='rgba(201,169,124,0.15)'">
            <span data-ar="عرض الكل" data-en="View All">عرض الكل</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(to right, rgba(11, 11, 69, 0.05), rgba(11, 11, 69, 0.08));">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70">#</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="الصورة" data-en="Image">الصورة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="المستخدم" data-en="User">المستخدم</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="الموقع" data-en="Location">الموقع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="مستوى الضرر" data-en="Damage Level">مستوى الضرر</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="الموافقة" data-en="Approval">الموافقة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="التاريخ" data-en="Date">التاريخ</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-charcoal/70" data-ar="الإجراءات" data-en="Actions">الإجراءات</th>
                </tr>
            </thead>
            <tbody style="border-top: 1px solid rgba(201, 169, 124, 0.4);">
                @forelse($recentReports as $report)
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
                                <img src="{{ asset('storage/' . $firstImage) }}" alt="Report" class="w-14 h-14 object-cover rounded-xl shadow-md">
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
                            $dashDamageColors = [
                                'critical' => '#dc2626', 'severe' => '#ea580c', 'moderate' => '#ca8a04',
                                'minor' => '#16a34a', 'high' => '#dc2626', 'medium' => '#ca8a04', 'low' => '#16a34a'
                            ];
                            $dashDamageLabels = [
                                'critical' => 'حرج', 'severe' => 'شديد', 'moderate' => 'متوسط',
                                'minor' => 'طفيف', 'high' => 'عالي', 'medium' => 'متوسط', 'low' => 'منخفض'
                            ];
                            $dashDamageLabelsEn = [
                                'critical' => 'Critical', 'severe' => 'Severe', 'moderate' => 'Moderate',
                                'minor' => 'Minor', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'
                            ];
                            $dashColorHex = $dashDamageColors[$report->ai_damage_level] ?? '#64748b';
                            $dashLabel = $dashDamageLabels[$report->ai_damage_level] ?? 'غير محدد';
                            $dashLabelEn = $dashDamageLabelsEn[$report->ai_damage_level] ?? 'Unknown';
                            $dashScoreDisplay = $report->ai_damage_score ? $report->ai_damage_score . '/10' : '';
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-sm font-bold" style="color: {{ $dashColorHex }};" data-ar="{{ $dashLabel }}" data-en="{{ $dashLabelEn }}">{{ $dashLabel }}</span>
                            @if($dashScoreDisplay)
                                <span class="text-xs font-bold" style="color: rgba(11, 11, 69, 0.4);">{{ $dashScoreDisplay }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $dashIsAiRejected = $report->ai_damage_level === 'rejected';
                            $dashIsAdminRejected = $report->admin_approval_status === 'rejected' && !is_null($report->approved_by);
                            $dashApprovalSubtext = null;
                            $dashApprovalSubtextEn = null;
                            if ($dashIsAiRejected && $dashIsAdminRejected) {
                                $dashApprovalLabel = 'مرفوض';
                                $dashApprovalLabelEn = 'Rejected';
                                $dashApprovalSubtext = 'بواسطة AI والأدمن';
                                $dashApprovalSubtextEn = 'By AI & Admin';
                                $dashApprovalHex = '#dc2626';
                            } elseif ($dashIsAiRejected) {
                                $dashApprovalLabel = 'مرفوض';
                                $dashApprovalLabelEn = 'Rejected';
                                $dashApprovalSubtext = 'بواسطة AI';
                                $dashApprovalSubtextEn = 'By AI';
                                $dashApprovalHex = '#dc2626';
                            } elseif ($dashIsAdminRejected) {
                                $dashApprovalLabel = 'مرفوض';
                                $dashApprovalLabelEn = 'Rejected';
                                $dashApprovalSubtext = 'بواسطة الأدمن';
                                $dashApprovalSubtextEn = 'By Admin';
                                $dashApprovalHex = '#dc2626';
                            } elseif ($report->admin_approval_status === 'approved') {
                                $dashApprovalLabel = 'تمت الموافقة';
                                $dashApprovalLabelEn = 'Approved';
                                $dashApprovalHex = '#16a34a';
                            } else {
                                $dashApprovalLabel = 'لم يُراجع';
                                $dashApprovalLabelEn = 'Not Reviewed';
                                $dashApprovalHex = '#64748b';
                            }
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-sm font-bold" style="color: {{ $dashApprovalHex }};" data-ar="{{ $dashApprovalLabel }}" data-en="{{ $dashApprovalLabelEn }}">{{ $dashApprovalLabel }}</span>
                            @if($dashApprovalSubtext)
                                <span class="text-[10px] font-bold" style="color: rgba(11, 11, 69, 0.5);" data-ar="{{ $dashApprovalSubtext }}" data-en="{{ $dashApprovalSubtextEn }}">{{ $dashApprovalSubtext }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 align-middle">
                        @php
                            $dashIsToday = $report->created_at->isToday();
                            $dashIsYesterday = $report->created_at->isYesterday();
                        @endphp
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" style="color: rgba(11, 11, 69, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex flex-col leading-tight">
                                @if($dashIsToday)
                                    <span class="text-xs font-bold" style="color: #16a34a;" data-ar="اليوم" data-en="Today">اليوم</span>
                                @elseif($dashIsYesterday)
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dashLang = localStorage.getItem('lang') || 'ar';
    const isAr = dashLang === 'ar';

    const damageLabelsMap = {
        'minor': isAr ? 'طفيف' : 'Minor',
        'moderate': isAr ? 'متوسط' : 'Moderate',
        'severe': isAr ? 'شديد' : 'Severe',
        'critical': isAr ? 'حرج' : 'Critical'
    };

    const damageColorsMap = {
        'minor': { bg: 'rgba(34, 197, 94, 0.8)', border: 'rgb(34, 197, 94)' },
        'moderate': { bg: 'rgba(234, 179, 8, 0.8)', border: 'rgb(234, 179, 8)' },
        'severe': { bg: 'rgba(249, 115, 22, 0.8)', border: 'rgb(249, 115, 22)' },
        'critical': { bg: 'rgba(239, 68, 68, 0.8)', border: 'rgb(239, 68, 68)' }
    };

    const rawDamageKeys = @json(array_keys($damageStats));
    const rawDamageValues = @json(array_values($damageStats));
    const damageKeys = [];
    const damageValues = [];
    const damageBgColors = [];
    const damageBorderColors = [];

    const validLevels = ['minor', 'moderate', 'severe', 'critical'];
    rawDamageKeys.forEach((key, i) => {
        if (validLevels.includes(key)) {
            damageKeys.push(damageLabelsMap[key] || key);
            damageValues.push(rawDamageValues[i]);
            damageBgColors.push(damageColorsMap[key].bg);
            damageBorderColors.push(damageColorsMap[key].border);
        }
    });

    new Chart(document.getElementById('damageChart'), {
        type: 'doughnut',
        data: {
            labels: damageKeys,
            datasets: [{
                data: damageValues,
                backgroundColor: damageBgColors,
                borderColor: damageBorderColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: isAr,
                    labels: {
                        font: { family: 'Cairo' },
                        color: '#2D3A50'
                    }
                }
            }
        }
    });

    const statusLabelsMap = {
        'pending': isAr ? 'قيد الانتظار' : 'Pending',
        'pending_approval': isAr ? 'بانتظار الموافقة' : 'Pending Approval',
        'processing': isAr ? 'قيد المعالجة' : 'Processing',
        'approved': isAr ? 'تمت الموافقة' : 'Approved',
        'completed': isAr ? 'مكتمل' : 'Completed',
        'rejected': isAr ? 'غير مقبول' : 'Rejected'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: @json(array_keys($statusStats)).map(status => statusLabelsMap[status] || status),
            datasets: [{
                data: @json(array_values($statusStats)),
                backgroundColor: [
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: isAr,
                    labels: {
                        font: { family: 'Cairo' },
                        color: '#2D3A50'
                    }
                }
            }
        }
    });

    const reportCountLabel = isAr ? 'عدد التقارير' : 'Report Count';

    new Chart(document.getElementById('timeChart'), {
        type: 'line',
        data: {
            labels: @json(array_keys($reportsOverTime)),
            datasets: [{
                label: reportCountLabel,
                data: @json(array_values($reportsOverTime)),
                fill: true,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#2D3A50' },
                    grid: { color: 'rgba(45, 62, 78, 0.06)' }
                },
                x: {
                    ticks: { color: '#2D3A50' },
                    grid: { color: 'rgba(45, 62, 78, 0.06)' }
                }
            }
        }
    });

    new Chart(document.getElementById('locationChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($reportsByLocation)),
            datasets: [{
                label: reportCountLabel,
                data: @json(array_values($reportsByLocation)),
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: 'rgb(139, 92, 246)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#2D3A50' },
                    grid: { color: 'rgba(45, 62, 78, 0.06)' }
                },
                x: {
                    ticks: { color: '#2D3A50' },
                    grid: { color: 'rgba(45, 62, 78, 0.06)' }
                }
            }
        }
    });
</script>
@endpush