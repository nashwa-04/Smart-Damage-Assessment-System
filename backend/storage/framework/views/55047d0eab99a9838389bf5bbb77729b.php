<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع التقارير - نظام تقييم الأضرار الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <aside class="w-64 bg-gray-800 min-h-screen">
            <div class="p-4">
                <h1 class="text-white text-xl font-bold">لوحة التحكم</h1>
            </div>
            <nav>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">لوحة القيادة</a>
                <a href="<?php echo e(route('admin.map')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">عرض الخريطة</a>
                <a href="<?php echo e(route('admin.reports')); ?>" class="block px-4 py-2 text-white hover:bg-gray-700">التقارير</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">جميع التقارير</h2>
            
            <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <div class="mb-4">
                <a href="<?php echo e(route('admin.reports.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">إضافة تقرير جديد</a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعرف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الصورة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مستوى الضرر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($report->id); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $images = $report->images ?? [];
                                    $firstImage = count($images) > 0 ? $images[0] : $report->image_path;
                                    $imageCount = count($images);
                                ?>
                                <div class="flex flex-col space-y-2">
                                    <?php if($firstImage): ?>
                                        <img src="<?php echo e(asset('storage/' . $firstImage)); ?>" alt="صورة التقرير" class="w-16 h-16 object-cover rounded">
                                        <?php if($imageCount > 1): ?>
                                            <span class="text-xs text-gray-500">+ <?php echo e($imageCount - 1); ?> صور</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">بدون صورة</span>
                                    <?php endif; ?>
                                    <?php if($report->pdf_file): ?>
                                        <a href="<?php echo e(asset('storage/' . $report->pdf_file)); ?>" target="_blank" class="text-xs text-red-600 hover:text-red-800">
                                            📄 PDF
                                        </a>
                                    <?php endif; ?>
                                    <?php if($report->video_links && count($report->video_links) > 0): ?>
                                        <span class="text-xs text-blue-600">🎬 <?php echo e(count($report->video_links)); ?> فيديو</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($report->user->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($report->raw_location); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs <?php echo e($report->ai_damage_level === 'critical' ? 'bg-red-200 text-red-800' : ($report->ai_damage_level === 'high' ? 'bg-orange-200 text-orange-800' : ($report->ai_damage_level === 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800'))); ?>">
                                    <?php echo e($report->ai_damage_level === 'critical' ? 'حرج' : ($report->ai_damage_level === 'high' ? 'عالي' : ($report->ai_damage_level === 'medium' ? 'متوسط' : ($report->ai_damage_level === 'low' ? 'منخفض' : 'غير متوفر')))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs <?php echo e($report->status === 'completed' ? 'bg-green-200 text-green-800' : ($report->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : ($report->status === 'processing' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800'))); ?>">
                                    <?php echo e($report->status === 'completed' ? 'مكتمل' : ($report->status === 'pending' ? 'قيد الانتظار' : ($report->status === 'processing' ? 'قيد المعالجة' : 'مرفوض'))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($report->created_at->format('Y-m-d H:i')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.reports.show', $report)); ?>" class="px-3 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200">عرض التفاصيل</a>
                                <a href="<?php echo e(route('admin.reports.edit', $report)); ?>" class="px-3 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200">تعديل</a>
                                <form action="<?php echo e(route('admin.reports.destroy', $report)); ?>" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200">حذف</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">لا توجد تقارير</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($reports->hasPages()): ?>
            <div class="mt-4 flex justify-center">
                <?php echo e($reports->links()); ?>

            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH D:\Smart Damage Assessment System\backend\resources\views/admin/reports.blade.php ENDPATH**/ ?>