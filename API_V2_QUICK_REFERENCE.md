# مرجع سريع - API V2.0

## Quick Reference Guide - Smart Damage Assessment System API

**الإصدار:** 2.0.0  
**التاريخ:** 2026-02-07

---

## 🚀 التغييرات السريعة

### الحقول الجديدة في API

| الحقل           | النوع    | الوصف              | مثال                          |
| --------------- | -------- | ------------------ | ----------------------------- |
| `images[]`      | File[]   | مصفوفة صور متعددة  | `image1.jpg, image2.jpg`      |
| `pdf_file`      | File     | ملف PDF واحد       | `report.pdf`                  |
| `video_links[]` | String[] | مصفوفة روابط فيديو | `["https://youtube.com/..."]` |

### الحقول الجديدة في الاستجابة

| الحقل         | النوع    | الوصف                                 |
| ------------- | -------- | ------------------------------------- |
| `images`      | String[] | مصفوفة URLs كاملة للصور               |
| `pdf_url`     | String?  | رابط URL كامل لملف PDF (قد يكون null) |
| `video_links` | String[] | مصفوفة روابط الفيديو                  |

---

## 📡 Endpoints الرئيسية

### 1. إنشاء تقرير (POST /api/reports)

```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer TOKEN" \
  -F "images[]=@image1.jpg" \
  -F "images[]=@image2.jpg" \
  -F "pdf_file=@report.pdf" \
  -F "video_links[]=https://youtube.com/watch?v=xxx" \
  -F "latitude=33.1162" \
  -F "longitude=35.8268" \
  -F "raw_location=القنيطرة" \
  -F "raw_description=وصف الضرر"
```

### 2. الحصول على التقارير (GET /api/reports)

```bash
curl -X GET http://localhost:8000/api/reports \
  -H "Authorization: Bearer TOKEN"
```

**الاستجابة:**

```json
{
  "id": 1,
  "images": [
    "http://localhost:8000/storage/reports/images/image1.jpg",
    "http://localhost:8000/storage/reports/images/image2.jpg"
  ],
  "pdf_url": "http://localhost:8000/storage/reports/docs/report.pdf",
  "video_links": [
    "https://youtube.com/watch?v=xxx"
  ],
  "location": {...},
  "description": {...},
  "damage_assessment": {...}
}
```

---

## 📦 Flutter Code Snippets

### إعداد FormData

```dart
var formData = FormData.fromMap({
  'images[]': [
    await MultipartFile.fromFile(image1.path),
    await MultipartFile.fromFile(image2.path),
  ],
  'pdf_file': await MultipartFile.fromFile(pdf.path),
  'video_links[]': ['https://youtube.com/watch?v=xxx'],
  'latitude': '33.1162',
  'longitude': '35.8268',
  'raw_location': 'القنيطرة',
  'raw_description': 'وصف الضرر',
});
```

### نموذج البيانات

```dart
class ReportModel {
  final List<String> images;      // 🔥 جديد
  final String? pdfUrl;            // 🔥 جديد
  final List<String> videoLinks;   // 🔥 جديد
  // ... باقي الحقول
}
```

---

## ⚠️ قيود الملفات

| النوع   | الحد الأقصى   | الصيغ المدعومة               |
| ------- | ------------- | ---------------------------- |
| الصور   | 10MB لكل صورة | JPEG, PNG, JPG, GIF          |
| PDF     | 20MB          | PDF فقط                      |
| الفيديو | -             | روابط URL فقط (لا رفع مباشر) |

---

## 🔒 أكواد الخطأ

| الكود | الوصف            |
| ----- | ---------------- |
| 413   | الملف كبير جداً  |
| 422   | بيانات غير صالحة |
| 401   | غير مصرح         |

---

## 📚 ملفات التوثيق الكاملة

1. [`API_DOCUMENTATION_V2.md`](API_DOCUMENTATION_V2.md) - دليل API كامل (إنجليزي)
2. [`API_DOCUMENTATION_V2_AR.md`](API_DOCUMENTATION_V2_AR.md) - دليل API كامل (عربي)
3. [`FRONTEND_API_INTEGRATION_GUIDE.md`](FRONTEND_API_INTEGRATION_GUIDE.md) - دليل التكامل مع Flutter

---

## ✅ التوافق

- ✅ متوافق مع الإصدار القديم
- ✅ يدعم `image` (صورة واحدة) و `images[]` (صور متعددة)
- ✅ تحويل تلقائي بين النظامين

---

**جاهز للتنفيذ! 🎉**
