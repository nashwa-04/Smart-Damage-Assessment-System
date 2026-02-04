<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خريطة الأضرار - نظام تقييم الأضرار الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
            <h2 class="text-2xl font-bold mb-6">خريطة الأضرار</h2>
            <div id="map" class="h-96 rounded-lg shadow"></div>
        </main>
    </div>

    <script>
        const map = L.map('map').setView([35.0, 38.0], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const reports = @json($reports);

        const colorMap = {
            'low': 'green',
            'medium': 'orange',
            'high': 'red',
            'critical': 'darkred'
        };

        const damageLevelLabels = {
            'low': 'منخفض',
            'medium': 'متوسط',
            'high': 'عالي',
            'critical': 'حرج'
        };

        reports.forEach(report => {
            const color = colorMap[report.ai_damage_level] || 'blue';

            const marker = L.marker([report.latitude, report.longitude], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;"></div>`
                })
            }).addTo(map);

            let popupContent = `
                <div style="max-width: 300px;">
                    <strong>تقرير #${report.id}</strong><br>
                    الموقع: ${report.raw_location}<br>
                    مستوى الضرر: ${damageLevelLabels[report.ai_damage_level] || report.ai_damage_level}
                `;
            
            // عرض الصور المتعددة
            if (report.images && report.images.length > 0) {
                popupContent += `<br><div style="margin-top: 10px;"><strong>الصور:</strong></div>`;
                popupContent += `<div style="display: flex; overflow-x: auto; gap: 5px; margin-top: 5px;">`;
                report.images.forEach(img => {
                    const imageUrl = '{{ asset("storage") }}/' + img;
                    popupContent += `<a href="${imageUrl}" target="_blank"><img src="${imageUrl}" alt="صورة" style="width: 80px; height: 60px; object-fit: cover; border-radius: 3px;"></a>`;
                });
                popupContent += `</div>`;
            } else if (report.image_path) {
                // دعم الصورة القديمة
                const imageUrl = '{{ asset("storage") }}/' + report.image_path;
                popupContent += `<br><div style="margin-top: 10px;"><strong>الصورة:</strong></div>`;
                popupContent += `<a href="${imageUrl}" target="_blank"><img src="${imageUrl}" alt="صورة التقرير" style="max-width: 200px; max-height: 150px; margin-top: 5px; border-radius: 5px;"></a>`;
            }
            
            // عرض ملف PDF
            if (report.pdf_file) {
                const pdfUrl = '{{ asset("storage") }}/' + report.pdf_file;
                popupContent += `<br><div style="margin-top: 10px;"><strong>ملف PDF:</strong></div>`;
                popupContent += `<a href="${pdfUrl}" target="_blank" style="display: inline-block; background: #dc2626; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px;">📄 تحميل التقرير PDF</a>`;
            }
            
            // عرض روابط الفيديو
            if (report.video_links && report.video_links.length > 0) {
                popupContent += `<br><div style="margin-top: 10px;"><strong>روابط الفيديو:</strong></div>`;
                popupContent += `<ul style="margin: 5px 0; padding-left: 15px;">`;
                report.video_links.forEach(link => {
                    popupContent += `<li><a href="${link}" target="_blank" style="font-size: 12px;">${link}</a></li>`;
                });
                popupContent += `</ul>`;
            }
            
            // زر الدخول إلى تفاصيل التقرير
            popupContent += `<br><div style="margin-top: 15px; text-align: center;">`;
            popupContent += `<a href="/admin/reports/${report.id}" style="display: inline-block; background: #2563eb; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; width: 100%;">📋 الدخول إلى تفاصيل التقرير</a>`;
            popupContent += `</div>`;
            
            popupContent += `</div>`;
            marker.bindPopup(popupContent);
        });
    </script>
</body>
</html>
