<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل التقرير #{{ $report->id }} - نظام تقييم الأضرار الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .image-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .image-item:hover {
            transform: scale(1.05);
        }
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
        }
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .lightbox.active {
            display: flex;
        }
        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">تفاصيل التقرير #{{ $report->id }}</h1>
                    <p class="text-gray-600 mt-1">تم الإنشاء: {{ $report->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                        ← العودة للتقارير
                    </a>
                    <a href="{{ route('admin.reports.edit', $report) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        تعديل التقرير
                    </a>
                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            حذف التقرير
                        </button>
                    </form>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">معلومات أساسية</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">معرف التقرير:</span>
                            <span class="font-semibold">#{{ $report->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المستخدم:</span>
                            <span class="font-semibold">{{ $report->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-semibold">{{ $report->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">آخر تحديث:</span>
                            <span class="font-semibold">{{ $report->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الحالة:</span>
                            <span class="px-3 py-1 rounded text-sm font-semibold
                                {{ $report->status === 'completed' ? 'bg-green-200 text-green-800' : 
                                   ($report->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                   ($report->status === 'processing' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800')) }}">
                                {{ $report->status === 'completed' ? 'مكتمل' : 
                                   ($report->status === 'pending' ? 'قيد الانتظار' : 
                                   ($report->status === 'processing' ? 'قيد المعالجة' : 'مرفوض')) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Location Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">معلومات الموقع</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600 block mb-1">الموقع الأصلي:</span>
                            <span class="font-semibold text-lg">{{ $report->raw_location }}</span>
                        </div>
                        @if($report->ai_location)
                        <div>
                            <span class="text-gray-600 block mb-1">الموقع المُحسّن (AI):</span>
                            <span class="font-semibold text-lg">{{ $report->ai_location }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">خط العرض:</span>
                            <span class="font-semibold">{{ number_format($report->latitude, 6) }}°</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">خط الطول:</span>
                            <span class="font-semibold">{{ number_format($report->longitude, 6) }}°</span>
                        </div>
                        <div id="mini-map" class="h-48 rounded-lg mt-4"></div>
                    </div>
                </div>

                <!-- Damage Level Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">مستوى الضرر</h2>
                    <div class="text-center py-6">
                        @php
                            $damageColors = [
                                'low' => 'bg-green-500',
                                'medium' => 'bg-yellow-500',
                                'high' => 'bg-orange-500',
                                'critical' => 'bg-red-600'
                            ];
                            $damageLabels = [
                                'low' => 'منخفض',
                                'medium' => 'متوسط',
                                'high' => 'عالي',
                                'critical' => 'حرج'
                            ];
                            $colorClass = $damageColors[$report->ai_damage_level] ?? 'bg-gray-500';
                            $label = $damageLabels[$report->ai_damage_level] ?? 'غير محدد';
                        @endphp
                        <div class="inline-block {{ $colorClass }} text-white px-8 py-4 rounded-full text-2xl font-bold mb-4">
                            {{ $label }}
                        </div>
                        <p class="text-gray-600 mt-4">مستوى الضرر المُحدد بواسطة الذكاء الاصطناعي</p>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">الوصف والتحليل</h2>
                    <div class="space-y-4">
                        @if($report->raw_description)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">الوصف الأصلي:</h3>
                            <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $report->raw_description }}</p>
                        </div>
                        @endif
                        @if($report->ai_analysis)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">تحليل الذكاء الاصطناعي:</h3>
                            <p class="text-gray-600 bg-blue-50 p-3 rounded">{{ $report->ai_analysis }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Images Gallery -->
            @php
                $images = $report->images ?? [];
                $hasImages = count($images) > 0;
                $hasOldImage = !empty($report->image_path);
            @endphp
            @if($hasImages || $hasOldImage)
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    صور التقرير
                    @if($hasImages)
                        <span class="text-sm font-normal text-gray-500">({{ count($images) }} صورة)</span>
                    @endif
                </h2>
                <div class="image-gallery">
                    @if($hasImages)
                        @foreach($images as $image)
                        <div class="image-item">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="صورة التقرير" 
                                 onclick="openLightbox('{{ asset('storage/' . $image) }}')">
                        </div>
                        @endforeach
                    @endif
                    @if($hasOldImage && !in_array($report->image_path, $images))
                        <div class="image-item">
                            <img src="{{ asset('storage/' . $report->image_path) }}" 
                                 alt="صورة التقرير" 
                                 onclick="openLightbox('{{ asset('storage/' . $report->image_path) }}')">
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- PDF File -->
            @if($report->pdf_file)
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">ملف PDF</h2>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <p class="text-gray-600 mb-2">ملف التقرير بصيغة PDF</p>
                        <div class="flex gap-3">
                            <a href="{{ asset('storage/' . $report->pdf_file) }}" 
                               target="_blank"
                               class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition inline-flex items-center gap-2">
                                📄 عرض PDF
                            </a>
                            <a href="{{ asset('storage/' . $report->pdf_file) }}" 
                               download
                               class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition inline-flex items-center gap-2">
                                ⬇️ تحميل PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Video Links -->
            @if($report->video_links && count($report->video_links) > 0)
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    روابط الفيديو
                    <span class="text-sm font-normal text-gray-500">({{ count($report->video_links) }} فيديو)</span>
                </h2>
                <div class="space-y-3">
                    @foreach($report->video_links as $index => $link)
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded hover:bg-gray-100 transition">
                        <div class="flex-1">
                            <span class="text-gray-600 font-semibold">فيديو #{{ $index + 1 }}:</span>
                            <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:text-blue-800 mr-2 break-all">
                                {{ $link }}
                            </a>
                        </div>
                        <a href="{{ $link }}" target="_blank" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                            🎬 مشاهدة
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </main>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()">✕ إغلاق</button>
        <img id="lightbox-img" src="" alt="صورة مكبرة">
    </div>

    <script>
        // Initialize mini map
        @if($report->latitude && $report->longitude)
        const miniMap = L.map('mini-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(miniMap);
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(miniMap);
        @endif

        // Lightbox functions
        function openLightbox(imageSrc) {
            document.getElementById('lightbox-img').src = imageSrc;
            document.getElementById('lightbox').classList.add('active');
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
        }

        // Close lightbox on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
</body>
</html>
