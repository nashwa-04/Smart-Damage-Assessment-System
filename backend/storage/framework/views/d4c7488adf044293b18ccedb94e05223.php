<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام تقييم الأضرار الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <aside class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <h1 class="text-white text-xl font-bold">لوحة الإدارة</h1>
            </div>
            <nav>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">لوحة التحكم</a>
                <a href="<?php echo e(route('admin.map')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">عرض الخريطة</a>
                <a href="<?php echo e(route('admin.reports')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">التقارير</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">نظرة عامة على لوحة التحكم</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">إجمالي التقارير</h3>
                    <p class="text-3xl font-bold text-blue-600"><?php echo e($totalReports); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">مكتملة</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo e($completedReports); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">قيد الانتظار</h3>
                    <p class="text-3xl font-bold text-yellow-600"><?php echo e($pendingReports); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">إجمالي المستخدمين</h3>
                    <p class="text-3xl font-bold text-purple-600"><?php echo e($totalUsers); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">توزيع الأضرار</h3>
                    <canvas id="damageChart"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">حالة التقارير</h3>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">التقارير خلال آخر 7 أيام</h3>
                    <canvas id="timeChart"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">التقارير حسب الموقع</h3>
                    <canvas id="locationChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">أحدث التقارير</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-right py-2">المعرف</th>
                                <th class="text-right py-2">الموقع</th>
                                <th class="text-right py-2">مستوى الضرر</th>
                                <th class="text-right py-2">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b">
                                <td class="py-2"><?php echo e($report->id); ?></td>
                                <td class="py-2"><?php echo e($report->raw_location); ?></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs <?php echo e($report->ai_damage_level === 'critical' ? 'bg-red-200 text-red-800' : ($report->ai_damage_level === 'high' ? 'bg-orange-200 text-orange-800' : ($report->ai_damage_level === 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800'))); ?>">
                                        <?php echo e($report->ai_damage_level === 'critical' ? 'حرج' : ($report->ai_damage_level === 'high' ? 'عالي' : ($report->ai_damage_level === 'medium' ? 'متوسط' : ($report->ai_damage_level === 'low' ? 'منخفض' : 'غير متوفر')))); ?>

                                    </span>
                                </td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs <?php echo e($report->status === 'completed' ? 'bg-green-200 text-green-800' : ($report->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : ($report->status === 'processing' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800'))); ?>">
                                        <?php echo e($report->status === 'completed' ? 'مكتمل' : ($report->status === 'pending' ? 'قيد الانتظار' : ($report->status === 'processing' ? 'قيد المعالجة' : 'مرفوض'))); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">لا توجد تقارير بعد</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Damage Level Chart (Doughnut)
        const damageLabels = {
            'low': 'منخفض',
            'medium': 'متوسط',
            'high': 'عالي',
            'critical': 'حرج'
        };

        const damageData = {
            labels: <?php echo json_encode(array_keys($damageStats), 15, 512) ?>.map(level => damageLabels[level] || level),
            datasets: [{
                label: 'التقارير حسب مستوى الضرر',
                data: <?php echo json_encode(array_values($damageStats), 15, 512) ?>,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(249, 115, 22, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(234, 179, 8)',
                    'rgb(249, 115, 22)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('damageChart'), {
            type: 'doughnut',
            data: damageData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        textDirection: 'rtl'
                    }
                }
            }
        });

        // Status Chart (Pie)
        const statusLabels = {
            'pending': 'قيد الانتظار',
            'processing': 'قيد المعالجة',
            'completed': 'مكتمل',
            'rejected': 'مرفوض'
        };

        const statusData = {
            labels: <?php echo json_encode(array_keys($statusStats), 15, 512) ?>.map(status => statusLabels[status] || status),
            datasets: [{
                label: 'حالة التقارير',
                data: <?php echo json_encode(array_values($statusStats), 15, 512) ?>,
                backgroundColor: [
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderColor: [
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: statusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        textDirection: 'rtl'
                    }
                }
            }
        });

        // Reports Over Time Chart (Line)
        const timeData = {
            labels: <?php echo json_encode(array_keys($reportsOverTime), 15, 512) ?>,
            datasets: [{
                label: 'عدد التقارير',
                data: <?php echo json_encode(array_values($reportsOverTime), 15, 512) ?>,
                fill: true,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        };

        new Chart(document.getElementById('timeChart'), {
            type: 'line',
            data: timeData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Reports by Location Chart (Bar)
        const locationData = {
            labels: <?php echo json_encode(array_keys($reportsByLocation), 15, 512) ?>,
            datasets: [{
                label: 'عدد التقارير',
                data: <?php echo json_encode(array_values($reportsByLocation), 15, 512) ?>,
                backgroundColor: 'rgba(139, 92, 246, 0.7)',
                borderColor: 'rgb(139, 92, 246)',
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('locationChart'), {
            type: 'bar',
            data: locationData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php /**PATH D:\Smart Damage Assessment System\backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>