<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة تقرير جديد - نظام تقييم الأضرار الذكي</title>
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
            <h2 class="text-2xl font-bold mb-6">إضافة تقرير جديد</h2>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Selection -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">المستخدم</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="raw_location" class="block text-sm font-medium text-gray-700">الموقع</label>
                        <input type="text" name="raw_location" id="raw_location" value="{{ old('raw_location') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">خط العرض</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">خط الطول</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <!-- Damage Level -->
                    <div>
                        <label for="ai_damage_level" class="block text-sm font-medium text-gray-700">مستوى الضرر (AI)</label>
                        <select name="ai_damage_level" id="ai_damage_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">غير محدد</option>
                            <option value="low" {{ old('ai_damage_level') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('ai_damage_level') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('ai_damage_level') == 'high' ? 'selected' : '' }}>عالي</option>
                            <option value="critical" {{ old('ai_damage_level') == 'critical' ? 'selected' : '' }}>حرج</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">الحالة</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="raw_description" class="block text-sm font-medium text-gray-700">الوصف</label>
                        <textarea name="raw_description" id="raw_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('raw_description') }}</textarea>
                    </div>
                </div>

                <!-- Multimedia Section -->
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">الملفات والوسائط المتعددة</h3>
                    
                    <!-- Images Section -->
                    <div class="mb-6">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">الصور</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <label for="images" class="block text-sm text-gray-600 mb-2">رفع صور:</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">يمكنك اختيار عدة صور في نفس الوقت (حد أقصى 10 ميجابايت لكل صورة)</p>
                        </div>
                    </div>

                    <!-- PDF Section -->
                    <div class="mb-6">
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">ملف PDF (اختياري)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <label for="pdf_file" class="block text-sm text-gray-600 mb-2">رفع ملف PDF:</label>
                            <input type="file" name="pdf_file" id="pdf_file" accept=".pdf,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            <p class="text-xs text-gray-500 mt-1">حد أقصى 20 ميجابايت</p>
                        </div>
                    </div>

                    <!-- Video Links Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">روابط الفيديو (اختياري)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <label class="block text-sm text-gray-600 mb-2">إضافة روابط فيديو:</label>
                            <div id="video-links-container" class="space-y-2">
                                <div class="flex gap-2">
                                    <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button" onclick="addVideoLink()" class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">+ إضافة</button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">أمثلة: YouTube, Vimeo, أو أي رابط فيديو آخر</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">حفظ التقرير</button>
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
                <input type="url" name="video_links[]" placeholder="https://youtube.com/watch?v=..." class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">حذف</button>
            `;
            container.appendChild(newDiv);
        }
    </script>
</body>
</html>