@extends('admin.layouts.app')

@section('title', 'خريطة الأضرار - نظام تقييم الأضرار الذكي')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<!-- Header -->
<div class="mb-4 sm:mb-8 fade-in">
    <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-5">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shrink-0" style="background: linear-gradient(135deg, #0B0B45, #78A9C1);">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl sm:text-3xl font-bold" style="color: #0B0B45;" data-ar="خريطة الأضرار" data-en="Damage Map">خريطة الأضرار</h2>
                    <p class="mt-1 text-sm sm:text-base" style="color: rgba(11, 11, 69, 0.6);" data-ar="عرض جميع التقارير على الخريطة التفاعلية" data-en="View all reports on the interactive map">عرض جميع التقارير على الخريطة التفاعلية</p>
                </div>
            </div>
            <div class="flex items-center gap-3 sm:gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: #16a34a;"></span>
                    <span class="text-xs sm:text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="طفيف" data-en="Minor">طفيف</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: #ca8a04;"></span>
                    <span class="text-xs sm:text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="متوسط" data-en="Moderate">متوسط</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: #ea580c;"></span>
                    <span class="text-xs sm:text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="شديد" data-en="Severe">شديد</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: #dc2626;"></span>
                    <span class="text-xs sm:text-sm" style="color: rgba(11, 11, 69, 0.6);" data-ar="حرج" data-en="Critical">حرج</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Container -->
<div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in">
    <div id="map" style="height: 400px; width: 100%;"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([35.0, 38.0], 6);

    const tileLayers = {
        ar: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }),
        en: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        })
    };

    let currentTileLayer = tileLayers[localStorage.getItem('lang') || 'ar'];
    currentTileLayer.addTo(map);

    function switchTileLayer(lang) {
        if (currentTileLayer) map.removeLayer(currentTileLayer);
        currentTileLayer = tileLayers[lang] || tileLayers['ar'];
        currentTileLayer.addTo(map);
    }

    const reports = @json($reports);

    const colorMap = {
        'minor': '#22c55e',
        'low': '#22c55e',
        'moderate': '#eab308',
        'medium': '#eab308',
        'severe': '#f97316',
        'high': '#f97316',
        'critical': '#ef4444'
    };

    function getI18n(lang) {
        return {
            'report': lang === 'ar' ? 'تقرير' : 'Report',
            'location': lang === 'ar' ? 'الموقع:' : 'Location:',
            'damageLevel': lang === 'ar' ? 'مستوى الضرر:' : 'Damage Level:',
            'images': lang === 'ar' ? 'الصور:' : 'Images:',
            'image': lang === 'ar' ? 'الصورة:' : 'Image:',
            'downloadPdf': lang === 'ar' ? 'تحميل PDF' : 'Download PDF',
            'videoLinks': lang === 'ar' ? 'روابط الفيديو:' : 'Video Links:',
            'viewDetails': lang === 'ar' ? 'عرض التفاصيل' : 'View Details',
        };
    }

    function getDamageLevelLabels(lang) {
        return {
            'minor': lang === 'ar' ? 'طفيف' : 'Minor',
            'low': lang === 'ar' ? 'طفيف' : 'Minor',
            'moderate': lang === 'ar' ? 'متوسط' : 'Moderate',
            'medium': lang === 'ar' ? 'متوسط' : 'Moderate',
            'severe': lang === 'ar' ? 'شديد' : 'Severe',
            'high': lang === 'ar' ? 'شديد' : 'Severe',
            'critical': lang === 'ar' ? 'حرج' : 'Critical'
        };
    }

    const markers = [];

    function buildPopups(lang) {
        const i18n = getI18n(lang);
        const damageLevelLabels = getDamageLevelLabels(lang);

        markers.forEach(function(item) {
            const report = item.report;
            const color = colorMap[report.ai_damage_level] || '#64748b';

            let popupContent = '<div style="max-width: 320px; font-family: Cairo, sans-serif;">';
            popupContent += '<div style="background: linear-gradient(135deg, #0B0B45 0%, #78A9C1 100%); padding: 12px; border-radius: 8px 8px 0 0; margin: -12px -12px 12px -12px;">';
            popupContent += '<h3 style="color: white; margin: 0; font-size: 18px; font-weight: bold;">' + i18n.report + ' #' + report.id + '</h3>';
            popupContent += '</div>';
            popupContent += '<p style="margin: 8px 0; color: #0B0B45;"><strong>' + i18n.location + '</strong> ' + report.raw_location + '</p>';
            popupContent += '<p style="margin: 8px 0; color: #0B0B45;"><strong>' + i18n.damageLevel + '</strong> <span style="background: ' + color + '; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">' + (damageLevelLabels[report.ai_damage_level] || report.ai_damage_level) + '</span></p>';

            if (report.images && report.images.length > 0) {
                popupContent += '<div style="margin-top: 12px;"><strong style="color: #0B0B45;">' + i18n.images + '</strong></div>';
                popupContent += '<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">';
                report.images.forEach(function(img) {
                    const imageUrl = '{{ asset("storage") }}/' + img;
                    popupContent += '<a href="' + imageUrl + '" target="_blank"><img src="' + imageUrl + '" alt="image" style="width: 70px; height: 50px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></a>';
                });
                popupContent += '</div>';
            } else if (report.image_path) {
                const imageUrl = '{{ asset("storage") }}/' + report.image_path;
                popupContent += '<div style="margin-top: 12px;"><strong style="color: #0B0B45;">' + i18n.image + '</strong></div>';
                popupContent += '<a href="' + imageUrl + '" target="_blank"><img src="' + imageUrl + '" alt="report image" style="max-width: 200px; max-height: 150px; margin-top: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></a>';
            }

            if (report.pdf_file) {
                const pdfUrl = '{{ asset("storage") }}/' + report.pdf_file;
                popupContent += '<a href="' + pdfUrl + '" target="_blank" style="display: inline-block; background: #9C5D4D; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; margin-top: 12px;"><span style="margin-left: 6px;">📄</span> ' + i18n.downloadPdf + '</a>';
            }

            if (report.video_links && report.video_links.length > 0) {
                popupContent += '<div style="margin-top: 12px;"><strong style="color: #0B0B45;">' + i18n.videoLinks + '</strong></div>';
                popupContent += '<div style="margin-top: 8px;">';
                report.video_links.forEach(function(link) {
                    popupContent += '<a href="' + link + '" target="_blank" style="display: block; color: #78A9C1; font-size: 12px; margin-bottom: 4px;">' + link + '</a>';
                });
                popupContent += '</div>';
            }

            popupContent += '<a href="/admin/reports/' + report.id + '" style="display: block; background: linear-gradient(135deg, #C9A97C 0%, #78A9C1 100%); color: white; padding: 10px; border-radius: 8px; text-decoration: none; text-align: center; font-weight: bold; margin-top: 16px;">📋 ' + i18n.viewDetails + '</a>';
            popupContent += '</div>';

            item.marker.setPopupContent(popupContent);
        });
    }

    reports.forEach(report => {
        if (!report.ai_damage_level || !colorMap[report.ai_damage_level]) return;

        const color = colorMap[report.ai_damage_level];

        const marker = L.marker([report.latitude, report.longitude], {
            icon: L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + color + '; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map);

        marker.bindPopup('');
        markers.push({ marker: marker, report: report });
    });

    buildPopups(localStorage.getItem('lang') || 'ar');

    window.rebuildMapPopups = function(lang) {
        switchTileLayer(lang);
        buildPopups(lang);
    };
</script>
@endpush
