@extends('admin.layouts.app')

@section('title', 'لوحة القيادة - نظام تقييم الأضرار الذكي')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="mb-8 fade-in">
    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">لوحة القيادة</h2>
                    <p class="text-slate-500 mt-1">نظرة عامة على التقارير والإحصائيات</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="pulse-dot w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-sm text-slate-500">متصل</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in">
    <!-- Total Reports -->
    <div class="stat-card glass-card rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-slate-500 text-sm">إجمالي التقارير</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalReports }}</p>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="stat-card glass-card rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-slate-500 text-sm">مكتملة</p>
                <p class="text-3xl font-bold text-green-600">{{ $completedReports }}</p>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="stat-card glass-card rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-slate-500 text-sm">قيد الانتظار</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingReports }}</p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="stat-card glass-card rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-slate-500 text-sm">المستخدمين</p>
                <p class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 fade-in">
    <!-- Damage Distribution -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 11H9V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                توزيع الأضرار
            </h3>
        </div>
        <div class="p-6">
            <canvas id="damageChart"></canvas>
        </div>
    </div>

    <!-- Reports Status -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                حالة التقارير
            </h3>
        </div>
        <div class="p-6">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 fade-in">
    <!-- Reports Over Time -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                التقارير خلال آخر 7 أيام
            </h3>
        </div>
        <div class="p-6">
            <canvas id="timeChart"></canvas>
        </div>
    </div>

    <!-- Reports by Location -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                التقارير حسب الموقع
            </h3>
        </div>
        <div class="p-6">
            <canvas id="locationChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Reports Table -->
<div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
    <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-6 py-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            أحدث التقارير
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold text-slate-600">المعرف</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-slate-600">الموقع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-slate-600">مستوى الضرر</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-slate-600">الحالة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold text-slate-600">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($recentReports as $report)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-indigo-600">#{{ $report->id }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $report->raw_location }}</td>
                    <td class="px-6 py-4">
                        @php
                            $damageColors = [
                                'critical' => 'bg-red-100 text-red-700 border-red-200',
                                'high' => 'bg-orange-100 text-orange-700 border-orange-200',
                                'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'low' => 'bg-green-100 text-green-700 border-green-200'
                            ];
                            $damageLabels = [
                                'critical' => 'حرج',
                                'high' => 'عالي',
                                'medium' => 'متوسط',
                                'low' => 'منخفض'
                            ];
                            $colorClass = $damageColors[$report->ai_damage_level] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            $label = $damageLabels[$report->ai_damage_level] ?? 'غير محدد';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                            {{ $label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200'
                            ];
                            $statusLabels = [
                                'completed' => 'مكتمل',
                                'pending' => 'قيد الانتظار',
                                'processing' => 'قيد المعالجة',
                                'rejected' => 'مرفوض'
                            ];
                            $statusColor = $statusColors[$report->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            $statusLabel = $statusLabels[$report->status] ?? 'غير محدد';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-sm">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد تقارير بعد</td>
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
    // Damage Level Chart
    const damageLabels = {
        'low': 'منخفض',
        'medium': 'متوسط',
        'high': 'عالي',
        'critical': 'حرج'
    };

    new Chart(document.getElementById('damageChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($damageStats)).map(level => damageLabels[level] || level),
            datasets: [{
                data: @json(array_values($damageStats)),
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(234, 179, 8)',
                    'rgb(249, 115, 22)',
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
                    rtl: true,
                    labels: {
                        font: { family: 'Cairo' }
                    }
                }
            }
        }
    });

    // Status Chart
    const statusLabels = {
        'pending': 'قيد الانتظار',
        'processing': 'قيد المعالجة',
        'completed': 'مكتمل',
        'rejected': 'مرفوض'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: @json(array_keys($statusStats)).map(status => statusLabels[status] || status),
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
                    rtl: true,
                    labels: {
                        font: { family: 'Cairo' }
                    }
                }
            }
        }
    });

    // Reports Over Time Chart
    new Chart(document.getElementById('timeChart'), {
        type: 'line',
        data: {
            labels: @json(array_keys($reportsOverTime)),
            datasets: [{
                label: 'عدد التقارير',
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
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Reports by Location Chart
    new Chart(document.getElementById('locationChart'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($reportsByLocation)),
            datasets: [{
                label: 'عدد التقارير',
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
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endpush