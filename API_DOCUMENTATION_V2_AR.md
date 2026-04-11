# نظام تقييم الأضرار الذكي - دليل واجهة برمجة التطبيقات V2.0

## Smart Damage Assessment System - API Documentation

**تاريخ التحديث:** 2026-02-07  
**الإصدار:** 2.0.0  
**الحالة:** ✅ جاهز للإنتاج

---

## 📋 جدول المحتويات

1. [نظرة عامة](#نظرة-عامة)
2. [التغييرات الجديدة](#التغييرات-الجديدة)
3. [الإعدادات الأساسية](#الإعدادات-الأساسية)
4. [نقاط النهاية API Endpoints](#نقاط-النهاية-api-endpoints)
5. [نماذج البيانات](#نماذج-البيانات)
6. [أمثلة التكامل مع Flutter](#أمثلة-التكامل-مع-flutter)
7. [معالجة الأخطاء](#معالجة-الأخطاء)
8. [أكواد الحالة](#أكواد-الحالة)

---

## نظرة عامة

هذا الدليل يوثق واجهة برمجة التطبيقات (API) لنظام تقييم الأضرار الذكي بعد التحديثات الجديدة التي تدعم:

- ✅ **صور متعددة** (Multiple Images) - رفع عدة صور في تقرير واحد
- ✅ **ملفات PDF** - إرفاق ملفات PDF مع التقارير
- ✅ **روابط فيديو** - إضافة روابط فيديو خارجية (YouTube, Vimeo, etc.)

---

## التغييرات الجديدة

### 🔥 التحديثات الرئيسية

#### 1. دعم الصور المتعددة

- **القديم:** `image` (صورة واحدة فقط)
- **الجديد:** `images[]` (مصفوفة صور متعددة)
- **التوافق:** لا يزال يدعم `image` للحفاظ على التوافق مع الإصدارات القديمة

#### 2. دعم ملفات PDF

- **الجديد:** `pdf_file` (ملف PDF واحد)
- **الحد الأقصى:** 20 ميجابايت
- **اختياري:** يمكن إنشاء تقرير بدون PDF

#### 3. دعم روابط الفيديو

- **الجديد:** `video_links[]` (مصفوفة روابط)
- **دعم:** YouTube, Vimeo, وأي رابط URL صحيح
- **عدد غير محدود:** يمكن إضافة عدة روابط

#### 4. تحديث استجابة API

- تم إضافة الحقول الجديدة لاستجابة `/api/reports` و `/api/reports/{id}`
- تتضمن الاستجابة الآن: `images`, `pdf_url`, `video_links`

---

## الإعدادات الأساسية

### Base URL

```bash
# للتطوير المحلي
http://localhost:8000/api

# للإنتاج
https://your-domain.com/api
```

### Authentication

جميع النقاط المحمية تتطلب رمز Bearer Token في رأس التفويض:

```http
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**ملاحظة:** عند رفع الملفات، استخدم `multipart/form-data`:

```http
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: multipart/form-data
```

---

## نقاط النهاية API Endpoints

### 1. فحص الخدمة (Health Check)

**Endpoint:** `GET /api`

**Authentication:** غير مطلوب

**الطلب:**

```bash
curl -X GET http://localhost:8000/api
```

**الاستجابة (200 OK):**

```json
{
  "status": "ok",
  "message": "API is running",
  "version": "2.0.0",
  "timestamp": "2026-02-07T18:00:00+00:00"
}
```

---

### 2. تسجيل الدخول (Login)

**Endpoint:** `POST /api/login`

**Authentication:** غير مطلوب

**نوع المحتوى:** `application/json`

**نص الطلب:**

```json
{
  "email": "user@test.com",
  "password": "password"
}
```

**الاستجابة (200 OK):**

```json
{
  "token": "15|CPmOBrrF3uas2LHIE8fROB4PhWr3HrBwBuDN9PRX27162509",
  "user": {
    "id": 2,
    "name": "Field Officer",
    "email": "user@test.com",
    "role": "field_user"
  }
}
```

**استجابات الخطأ:**

- `401 Unauthorized`: بيانات الاعتماد غير صحيحة
- `422 Validation Error`: بيانات غير صالحة

---

### 3. تسجيل الخروج (Logout)

**Endpoint:** `POST /api/logout`

**Authentication:** مطلوب

**الطلب:**

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**الاستجابة (200 OK):**

```json
{
  "message": "Logged out successfully"
}
```

---

### 4. الحصول على المستخدم الحالي (Get Current User)

**Endpoint:** `GET /api/me`

**Authentication:** مطلوب

**الاستجابة (200 OK):**

```json
{
  "id": 2,
  "name": "Field Officer",
  "email": "user@test.com",
  "role": "field_user",
  "created_at": "2026-01-19T11:19:30.000000Z",
  "updated_at": "2026-01-19T11:19:30.000000Z"
}
```

---

### 5. الحصول على جميع التقارير (Get User Reports)

**Endpoint:** `GET /api/reports`

**Authentication:** مطلوب

**الاستجابة (200 OK):**

```json
[
  {
    "id": 54,
    "user": {
      "id": 2,
      "name": "Field Officer"
    },
    "images": [
      "http://localhost:8000/storage/reports/images/image1.jpg",
      "http://localhost:8000/storage/reports/images/image2.jpg",
      "http://localhost:8000/storage/reports/images/image3.jpg"
    ],
    "pdf_url": "http://localhost:8000/storage/reports/docs/report54.pdf",
    "video_links": [
      "https://youtube.com/watch?v=example1",
      "https://youtube.com/watch?v=example2"
    ],
    "location": {
      "raw": "القنيطرة - حي الأمل",
      "normalized": "القنيطرة",
      "coordinates": {
        "latitude": 33.1162,
        "longitude": 35.8268
      }
    },
    "description": {
      "raw": "أضرار في البنية التحتية للكهرباء",
      "ai_analysis": "أضرار كارثية في المباني، غير صالحة للسكن"
    },
    "damage_assessment": {
      "level": "critical",
      "status": "pending"
    },
    "created_at": "2026-01-18 16:08:42",
    "updated_at": "2026-01-19 12:36:42"
  }
]
```

**ملاحظة:** إذا كان التقرير يستخدم النظام القديم (صورة واحدة)، ستكون `images` تحتوي على صورة واحدة فقط.

---

### 6. إنشاء تقرير جديد (Create Report) ⭐

**Endpoint:** `POST /api/reports`

**Authentication:** مطلوب

**نوع المحتوى:** `multipart/form-data`

#### المعاملات (Parameters):

| المعامل           | النوع  | مطلوب | الحد الأقصى   | الوصف                         |
| ----------------- | ------ | ----- | ------------- | ----------------------------- |
| `images[]`        | file   | ❌\*  | 10MB لكل صورة | مصفوفة صور متعددة             |
| `image`           | file   | ❌\*  | 10MB          | صورة واحدة (للتوافق القديم)   |
| `pdf_file`        | file   | ❌    | 20MB          | ملف PDF واحد                  |
| `video_links[]`   | string | ❌    | -             | مصفوفة روابط فيديو            |
| `latitude`        | number | ✅    | -             | إحداثي العرض GPS              |
| `longitude`       | number | ✅    | -             | إحداثي الطول GPS              |
| `raw_location`    | string | ✅    | 255 حرف       | اسم الموقع كما أدخله المستخدم |
| `raw_description` | string | ❌    | 2000 حرف      | وصف إضافي                     |

- ⚠️ **ملاحظة:** يجب إما `images[]` أو `image` - واحد منهما مطلوب على الأقل.

#### قواعد التحقق (Validation Rules):

```php
'images' => 'nullable|array',
'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB per image
'image' => 'nullable|image|max:10240', // 10MB (legacy support)
'pdf_file' => 'nullable|mimes:pdf|max:20480', // 20MB
'video_links' => 'nullable|array',
'video_links.*' => 'nullable|url',
'latitude' => 'required|numeric|between:-90,90',
'longitude' => 'required|numeric|between:-180,180',
'raw_location' => 'required|string|max:255',
'raw_description' => 'nullable|string|max:2000',
```

#### مثال الطلب باستخدام Flutter:

```dart
// إعداد FormData مع ملفات متعددة
var formData = FormData.fromMap({
  // الصور المتعددة
  'images[]': [
    await MultipartFile.fromFile(image1.path, filename: 'image1.jpg'),
    await MultipartFile.fromFile(image2.path, filename: 'image2.jpg'),
    await MultipartFile.fromFile(image3.path, filename: 'image3.jpg'),
  ],

  // ملف PDF (اختياري)
  'pdf_file': await MultipartFile.fromFile(pdf.path, filename: 'report.pdf'),

  // روابط الفيديو
  'video_links[]': [
    'https://youtube.com/watch?v=example1',
    'https://youtube.com/watch?v=example2',
  ],

  // البيانات الأساسية
  'latitude': '33.1162',
  'longitude': '35.8268',
  'raw_location': 'القنيطرة - حي الأمل',
  'raw_description': 'أضرار في البنية التحتية للكهرباء',
});

// إرسال الطلب
var response = await dio.post(
  '/api/reports',
  data: formData,
  options: Options(
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'multipart/form-data',
    },
  ),
);
```

#### مثال الطلب باستخدام cURL:

```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "images[]=@/path/to/image1.jpg" \
  -F "images[]=@/path/to/image2.jpg" \
  -F "images[]=@/path/to/image3.jpg" \
  -F "pdf_file=@/path/to/report.pdf" \
  -F "video_links[]=https://youtube.com/watch?v=example1" \
  -F "video_links[]=https://youtube.com/watch?v=example2" \
  -F "latitude=33.1162" \
  -F "longitude=35.8268" \
  -F "raw_location=القنيطرة - حي الأمل" \
  -F "raw_description=أضرار في البنية التحتية للكهرباء"
```

#### الاستجابة (201 Created):

```json
{
  "data": {
    "id": 57,
    "status": "pending",
    "message": "Report submitted successfully. Processing will start shortly."
  }
}
```

#### استجابات الخطأ:

**422 Validation Error:**

```json
{
  "errors": {
    "images": ["The images field is required when image is not present."],
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."],
    "raw_location": ["The raw_location field is required."]
  }
}
```

**413 Payload Too Large:**

```json
{
  "errors": {
    "pdf_file": ["The pdf file may not be greater than 20480 kilobytes."]
  }
}
```

---

### 7. الحصول على تفاصيل تقرير (Get Report Details)

**Endpoint:** `GET /api/reports/{id}`

**Authentication:** مطلوب

**الاستجابة (200 OK):**

```json
{
  "id": 54,
  "user": {
    "id": 2,
    "name": "Field Officer"
  },
  "images": [
    "http://localhost:8000/storage/reports/images/ILiCMVWEWYlR4QxFNPMj8WBuXariv0H41Hr4yyMM.jpg",
    "http://localhost:8000/storage/reports/images/lCqk5Q6Y61edcE0yUjdZVGnoPvCxQ74tZ4vvtTsV.jpg",
    "http://localhost:8000/storage/reports/images/nLDznxFaSyP5DYmfOkahB1Jf6yHYOx4vFNeBBKf6.jpg"
  ],
  "pdf_url": "http://localhost:8000/storage/reports/docs/report54.pdf",
  "video_links": [
    "https://youtube.com/watch?v=example1",
    "https://youtube.com/watch?v=example2"
  ],
  "location": {
    "raw": "القنيطرة - حي الأمل",
    "normalized": "القنيطرة",
    "coordinates": {
      "latitude": 33.1162,
      "longitude": 35.8268
    }
  },
  "description": {
    "raw": "أضرار في البنية التحتية للكهرباء",
    "ai_analysis": "أضرار كارثية في المباني، غير صالحة للسكن"
  },
  "damage_assessment": {
    "level": "critical",
    "status": "pending"
  },
  "created_at": "2026-01-18 16:08:42",
  "updated_at": "2026-01-19 12:36:42"
}
```

**ملاحظات مهمة:**

- `images` هي مصفوفة URLs كاملة للصور
- `pdf_url` قد تكون `null` إذا لم يتم إرفاق ملف PDF
- `video_links` هي مصفوفة روابط فيديو (قد تكون فارغة)

**استجابة الخطأ (404 Not Found):**

```json
{
  "message": "Report not found"
}
```

---

### 8. حذف تقرير (Delete Report)

**Endpoint:** `DELETE /api/reports/{id}`

**Authentication:** مطلوب

**الاستجابة (200 OK):**

```json
{
  "message": "Report deleted successfully"
}
```

**ملاحظة:** سيتم حذف جميع الملفات المرتبطة (الصور، PDF) من الخادم.

---

## نماذج البيانات

### Report Status (حالة التقرير)

| الحالة       | الوصف                                    |
| ------------ | ---------------------------------------- |
| `pending`    | التقرير قيد الانتظار - لم تبدأ معالجة AI |
| `processing` | جاري المعالجة - AI يحلل البيانات         |
| `completed`  | مكتمل - انتهت المعالجة بنجاح             |
| `rejected`   | مرفوض - تقرير غير صالح أو مكرر           |

### Damage Level (مستوى الضرر)

| المستوى    | الوصف     | النطاق |
| ---------- | --------- | ------ |
| `low`      | ضرر طفيف  | 1-3    |
| `medium`   | ضرر متوسط | 4-6    |
| `high`     | ضرر شديد  | 7-8    |
| `critical` | ضرر حرج   | 9-10   |

### User Role (دور المستخدم)

| الدور        | الوصف                                 |
| ------------ | ------------------------------------- |
| `admin`      | مسؤول - صلاحيات كاملة                 |
| `field_user` | مستخدم ميداني - إنشاء وإدارة التقارير |

---

## أمثلة التكامل مع Flutter

### 1. إعداد API Configuration

```dart
// lib/core/api_config.dart
import 'dart:io';
import 'package:flutter/foundation.dart';

class ApiConfig {
  static String get baseUrl {
    if (Platform.isAndroid && kDebugMode) {
      return 'http://10.0.2.2:8000/api';
    }
    return 'http://YOUR_SERVER_IP:8000/api';
  }

  // يمكن تغيير IP فقط هنا عند تغيير الشبكة
  static String serverIp = '10.28.57.151'; // مثال
}
```

### 2. نموذج Report Model

```dart
// lib/models/report_model.dart
class ReportModel {
  final int id;
  final List<String> images;
  final String? pdfUrl;
  final List<String> videoLinks;
  final Location location;
  final Description description;
  final DamageAssessment damageAssessment;
  final String createdAt;
  final String updatedAt;

  ReportModel({
    required this.id,
    required this.images,
    this.pdfUrl,
    required this.videoLinks,
    required this.location,
    required this.description,
    required this.damageAssessment,
    required this.createdAt,
    required this.updatedAt,
  });

  factory ReportModel.fromJson(Map<String, dynamic> json) {
    return ReportModel(
      id: json['id'],
      images: List<String>.from(json['images'] ?? []),
      pdfUrl: json['pdf_url'],
      videoLinks: List<String>.from(json['video_links'] ?? []),
      location: Location.fromJson(json['location']),
      description: Description.fromJson(json['description']),
      damageAssessment: DamageAssessment.fromJson(json['damage_assessment']),
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }
}

class Location {
  final String raw;
  final String normalized;
  final Coordinates coordinates;

  Location({
    required this.raw,
    required this.normalized,
    required this.coordinates,
  });

  factory Location.fromJson(Map<String, dynamic> json) {
    return Location(
      raw: json['raw'],
      normalized: json['normalized'],
      coordinates: Coordinates.fromJson(json['coordinates']),
    );
  }
}

class Coordinates {
  final double latitude;
  final double longitude;

  Coordinates({required this.latitude, required this.longitude});

  factory Coordinates.fromJson(Map<String, dynamic> json) {
    return Coordinates(
      latitude: json['latitude'].toDouble(),
      longitude: json['longitude'].toDouble(),
    );
  }
}

class Description {
  final String raw;
  final String? aiAnalysis;

  Description({required this.raw, this.aiAnalysis});

  factory Description.fromJson(Map<String, dynamic> json) {
    return Description(
      raw: json['raw'] ?? '',
      aiAnalysis: json['ai_analysis'],
    );
  }
}

class DamageAssessment {
  final String level;
  final String status;

  DamageAssessment({required this.level, required this.status});

  factory DamageAssessment.fromJson(Map<String, dynamic> json) {
    return DamageAssessment(
      level: json['level'] ?? 'unknown',
      status: json['status'] ?? 'pending',
    );
  }
}
```

### 3. خدمة التقارير (Report Service)

```dart
// lib/services/report_service.dart
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/report_model.dart';
import '../core/api_config.dart';

class ReportService {
  final Dio _dio = Dio(BaseOptions(
    baseUrl: ApiConfig.baseUrl,
    connectTimeout: Duration(seconds: 30),
    receiveTimeout: Duration(seconds: 30),
  ));

  final _storage = FlutterSecureStorage();

  ReportService() {
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await _storage.read(key: 'auth_token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        return handler.next(options);
      },
    ));
  }

  // الحصول على جميع التقارير
  Future<List<ReportModel>> getReports() async {
    try {
      final response = await _dio.get('/reports');
      return (response.data as List)
          .map((json) => ReportModel.fromJson(json))
          .toList();
    } catch (e) {
      throw _handleError(e);
    }
  }

  // الحصول على تقرير واحد
  Future<ReportModel> getReport(int id) async {
    try {
      final response = await _dio.get('/reports/$id');
      return ReportModel.fromJson(response.data);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // إنشاء تقرير جديد مع الوسائط المتعددة
  Future<Map<String, dynamic>> createReport({
    required List<File> images,
    File? pdfFile,
    List<String> videoLinks = const [],
    required double latitude,
    required double longitude,
    required String rawLocation,
    String? rawDescription,
  }) async {
    try {
      // تحضير الملفات
      List<MultipartFile> imageFiles = await Future.wait(
        images.map((image) async {
          return await MultipartFile.fromFile(
            image.path,
            filename: image.path.split('/').last,
          );
        }).toList(),
      );

      // إعداد FormData
      var formData = FormData.fromMap({
        'images[]': imageFiles,
        if (pdfFile != null)
          'pdf_file': await MultipartFile.fromFile(
            pdfFile.path,
            filename: pdfFile.path.split('/').last,
          ),
        if (videoLinks.isNotEmpty)
          'video_links[]': videoLinks,
        'latitude': latitude,
        'longitude': longitude,
        'raw_location': rawLocation,
        if (rawDescription != null)
          'raw_description': rawDescription,
      });

      final response = await _dio.post(
        '/reports',
        data: formData,
        options: Options(
          contentType: 'multipart/form-data',
        ),
      );

      return response.data['data'];
    } catch (e) {
      throw _handleError(e);
    }
  }

  // حذف تقرير
  Future<void> deleteReport(int id) async {
    try {
      await _dio.delete('/reports/$id');
    } catch (e) {
      throw _handleError(e);
    }
  }

  // معالجة الأخطاء
  Exception _handleError(dynamic error) {
    if (error is DioException) {
      switch (error.type) {
        case DioExceptionType.connectionTimeout:
        case DioExceptionType.receiveTimeout:
          return Exception('انتهت مهلة الاتصال. يرجى المحاولة مرة أخرى.');
        case DioExceptionType.connectionError:
          return Exception('لا يمكن الاتصال بالخادم. تحقق من اتصال الإنترنت.');
        case DioExceptionType.badResponse:
          final statusCode = error.response?.statusCode;
          if (statusCode == 401) {
            return Exception('غير مصرح. يرجى تسجيل الدخول مرة أخرى.');
          } else if (statusCode == 422) {
            final errors = error.response?.data['errors'];
            return Exception('بيانات غير صالحة: ${errors.toString()}');
          } else if (statusCode == 413) {
            return Exception('حجم الملف كبير جداً. الحد الأقصى 20MB للـ PDF.');
          }
          return Exception('خطأ في الخادم: $statusCode');
        default:
          return Exception('حدث خطأ غير متوقع.');
      }
    }
    return Exception('حدث خطأ: ${error.toString()}');
  }
}
```

### 4. مثال على استخدام الخدمة في UI

```dart
// مثال: صفحة إنشاء تقرير
class CreateReportScreen extends StatefulWidget {
  @override
  _CreateReportScreenState createState() => _CreateReportScreenState();
}

class _CreateReportScreenState extends State<CreateReportScreen> {
  final _reportService = ReportService();
  List<File> _selectedImages = [];
  File? _selectedPdf;
  List<String> _videoLinks = [];
  final _linkController = TextEditingController();

  // ... باقي الكود

  Future<void> _submitReport() async {
    try {
      final result = await _reportService.createReport(
        images: _selectedImages,
        pdfFile: _selectedPdf,
        videoLinks: _videoLinks,
        latitude: _currentLocation.latitude,
        longitude: _currentLocation.longitude,
        rawLocation: _locationController.text,
        rawDescription: _descriptionController.text,
      );

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('تم إرسال التقرير بنجاح!')),
      );

      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('خطأ: ${e.toString()}')),
      );
    }
  }

  // ... دوال اختيار الصور والـ PDF
}
```

---

## معالجة الأخطاء

### أكواد الحالة الشائعة

| الكود | الوصف            | الحل المقترح                                     |
| ----- | ---------------- | ------------------------------------------------ |
| 200   | نجح الطلب        | -                                                |
| 201   | تم الإنشاء بنجاح | -                                                |
| 401   | غير مصرح         | تحقق من صحة التوكن                               |
| 404   | غير موجود        | تحقق من معرف التقرير                             |
| 413   | الملف كبير جداً  | قلل حجم الملفات (الحد: 10MB للصور، 20MB للـ PDF) |
| 422   | خطأ في التحقق    | تحقق من صحة البيانات المرسلة                     |
| 500   | خطأ في الخادم    | اتصل بالدعم الفني                                |

### مثال على معالجة الأخطاء في Flutter

```dart
try {
  final reports = await reportService.getReports();
  // تحديث الواجهة
} on DioException catch (e) {
  if (e.response?.statusCode == 401) {
    // إعادة توجيه لصفحة تسجيل الدخول
    Navigator.pushReplacementNamed(context, '/login');
  } else if (e.response?.statusCode == 422) {
    // عرض رسائل التحقق
    final errors = e.response?.data['errors'];
    showValidationErrors(errors);
  } else {
    // عرض رسالة خطأ عامة
    showErrorSnackBar('حدث خطأ أثناء تحميل البيانات');
  }
} catch (e) {
  showErrorSnackBar('حدث خطأ غير متوقع');
}
```

---

## ملاحظات مهمة للمطورين

### 1. التوافق مع الإصدارات القديمة

النظام يدعم:

- ✅ الصور المتعددة الجديدة (`images[]`)
- ✅ الصورة الواحدة القديمة (`image`)
- ✅ التحويل التلقائي بين النظامين

### 2. قيود الملفات

- **الصور:** حد أقصى 10 ميجابايت لكل صورة
- **PDF:** حد أقصى 20 ميجابايت
- **الصيغ المدعومة:**
  - الصور: JPEG, PNG, JPG, GIF
  - المستندات: PDF فقط

### 3. روابط الفيديو

- يجب أن تكون URLs صالحة
- تدعم YouTube, Vimeo, وأي منصة أخرى
- التحقق يتم باستخدام `filter_var($link, FILTER_VALIDATE_URL)`

### 4. معالجة AI

- يتم معالجة التقارير بشكل غير متزامن باستخدام Queues
- الحالة الافتراضية: `pending`
- بعد المعالجة: `processing` → `completed` أو `rejected`

### 5. التخزين

- مسار الصور: `storage/app/public/reports/images/`
- مسار PDF: `storage/app/public/reports/docs/`
- الوصول العام: `http://domain.com/storage/reports/...`

---

## بيانات الاختبار

### بيانات تسجيل الدخول

**مستخدم ميداني:**

- Email: `user@test.com`
- Password: `password`
- Role: `field_user`

**مسؤول:**

- Email: `admin@test.com`
- Password: `password`
- Role: `admin`

---

## التغييرات من الإصدار 1.0

### ما تم إضافته:

1. ✅ دعم رفع صور متعددة (`images[]`)
2. ✅ دعم ملفات PDF (`pdf_file`)
3. ✅ دعم روابط الفيديو (`video_links[]`)
4. ✅ تحديث استجابة API لتضمين الحقول الجديدة
5. ✅ تحسين معالجة الملفات في Controller
6. ✅ تحديث قواعد التحقق

### ما تم تغييره:

- تم تعديل نقطة النهاية `POST /api/reports` لقبول ملفات متعددة
- تم تحديث `ReportResource` لإرجاع `images`, `pdf_url`, `video_links`
- تم تحديث `StoreReportRequest` للتحقق من الحقول الجديدة

### التوافق:

- ✅ متوافق مع الإصدار القديم (يدعم `image` و `images`)
- ✅ لا حاجة لتغيير في الكود القديم
- ✅ يمكن الترقية التدريجية

---

## الدعم الفني

للحصول على الدعم أو الإبلاغ عن مشاكل:

- 📧 البريد الإلكتروني: support@example.com
- 📱 الهاتف: +963 XXX XXX XXX
- 🌐 الموقع: https://example.com/support

---

**تم إعداد هذا الدليل بواسطة:** Kilo Code AI Assistant  
**آخر تحديث:** 2026-02-07  
**الإصدار:** 2.0.0
