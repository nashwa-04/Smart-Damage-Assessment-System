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
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-white hover:bg-gray-700">لوحة القيادة</a>
                <a href="{{ route('admin.map') }}" class="block px-4 py-2 text-white hover:bg-gray-700">عرض الخريطة</a>
                <a href="{{ route('admin.reports') }}" class="block px-4 py-2 text-white hover:bg-gray-700">التقارير</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">جميع التقارير</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المعرف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مستوى الضرر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $report->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $report->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $report->raw_location }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs {{ $report->ai_damage_level === 'critical' ? 'bg-red-200 text-red-800' : ($report->ai_damage_level === 'high' ? 'bg-orange-200 text-orange-800' : ($report->ai_damage_level === 'medium' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800')) }}">
                                    {{ $report->ai_damage_level === 'critical' ? 'حرج' : ($report->ai_damage_level === 'high' ? 'عالي' : ($report->ai_damage_level === 'medium' ? 'متوسط' : ($report->ai_damage_level === 'low' ? 'منخفض' : 'غير متوفر'))) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs {{ $report->status === 'completed' ? 'bg-green-200 text-green-800' : ($report->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : ($report->status === 'processing' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800')) }}">
                                    {{ $report->status === 'completed' ? 'مكتمل' : ($report->status === 'pending' ? 'قيد الانتظار' : ($report->status === 'processing' ? 'قيد المعالجة' : 'مرفوض')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد تقارير</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $reports->links() }}
            </div>
            @endif
        </main>
    </div>
</body>
</html>
