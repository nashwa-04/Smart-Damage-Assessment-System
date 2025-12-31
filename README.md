بناءً على طلبك، قمت بإعادة هيكلة خطة المشروع بالكامل لتعتمد على **Flutter** للتطبيق الميداني و **Laravel** للواجهة الخلفية ولوحة التحكم، مع تفاصيل تقنية دقيقة للهيكلية والأدوات.

---

# مشروع نظام توثيق وتحليل الأضرار الذكي (Smart Damage Assessment)

## 1. نظرة عامة على المعمارية (Architecture Overview)

*   **Frontend (Mobile):** تطبيق Flutter يعمل على (Android & iOS) لجمع البيانات.
*   **Backend (API & Web):** إطار عمل Laravel يعمل كمخدم API للتطبيق، وكمنصة ويب للإدارة.
*   **AI Engine:** خدمة Google Gemini API معالجة الصور والنصوص.
*   **Database:** MySQL لتخزين البيانات.
*   **Infrastructure:** استضافة سحابية (VPS) أو محلية.

---

## 2. هيكلية قاعدة البيانات (Database Schema)

سنحتاج لجدولين رئيسيين بالإضافة لجدول المستخدمين.

### جدول: `users` (المستخدمون)
*   `id`
*   `name`
*   `email`
*   `password`
*   `role` (enum: 'admin', 'field_officer')
*   `api_token` (Sanctum Token)

### جدول: `reports` (التقارير - يحتوي البيانات الخام والمعالجة)
يجمع هذا الجدول مدخلات المستخدم وتحليلات الذكاء الاصطناعي.

| الحقل | النوع | الوصف |
| :--- | :--- | :--- |
| `id` | BigInt | المعرف الفريد |
| `user_id` | Foreign Key | الموظف الذي رفع التقرير |
| `image_path` | String | مسار الصورة المرفوعة |
| `latitude` | Double | إحداثيات GPS (خط العرض) |
| `longitude` | Double | إحداثيات GPS (خط الطول) |
| `raw_location_text` | String | اسم المنطقة كما كتبه المستخدم (مثلاً: "حلب السكري") |
| `raw_description` | Text | وصف المستخدم اليدوي |
| `ai_normalized_location`| String | (NULLABLE) الاسم الموحد بعد المعالجة (مثلاً: "محافظة حلب - حي السكري") |
| `ai_damage_level` | Enum | (NULLABLE) [low, medium, high, critical] |
| `ai_analysis_text` | Text | (NULLABLE) وصف الضرر الذي استخرجه الـ AI من الصورة |
| `status` | Enum | [pending, processing, completed, rejected] |
| `created_at` | Timestamp | وقت الإنشاء |

---

## 3. الواجهة الخلفية (Laravel Backend)

### أ. الأدوات والمكتبات (Dependencies)
*   **Laravel 10/11**
*   **Laravel Sanctum:** للمصادقة (Authentication) مع تطبيق Flutter.
*   **Gemini PHP Client** (أو استخدام HTTP Client الأصلي في Laravel).
*   **Scribe** أو **Swagger:** لتوثيق الـ API تلقائياً.
*   **Intervention Image:** لضغط الصور قبل إرسالها للـ AI.

### ب. هيكلية الملفات المقترحة (Folder Structure)

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php      // تسجيل دخول التطبيق
│   │   │   └── ReportController.php    // استلام التقارير وعرضها
│   │   └── Admin/
│   │       └── DashboardController.php // لوحة تحكم الويب
│   ├── Requests/
│   │   └── StoreReportRequest.php      // التحقق من صحة البيانات (Validation)
│   └── Resources/
│       └── ReportResource.php          // تشكيل رد الـ JSON
├── Jobs/
│   └── ProcessReportWithAI.php         // (هام) المعالجة الخلفية للذكاء الاصطناعي
├── Services/
│   └── GeminiService.php               // كلاس للاتصال بـ Google AI Studio
└── Models/
    └── Report.php
```

### ج. آلية عمل الـ AI (داخل `ProcessReportWithAI.php`)
لا تنتظر استجابة الـ AI أثناء رفع المستخدم للتقرير. استخدم **Queues**:
1.  المستخدم يرفع التقرير -> يحفظ في الداتابيس بـ `status = pending`.
2.  يتم إطلاق Job في الخلفية.
3.  الـ Job يرسل الصورة والنص لـ Gemini بالـ Prompt التالي:
    > "حلل الصورة والنص المرفق. 1. وحد اسم المنطقة الجغرافية في سوريا بناءً على التقسيم الإداري الرسمي. 2. قدر نسبة الضرر من 1 إلى 10. 3. استخرج وصفاً دقيقاً للضرر من الصورة. أعد النتيجة بصيغة JSON."
4.  تحديث السجل في الداتابيس بالبيانات الجديدة وتغيير الحالة لـ `completed`.

---

## 4. الواجهة الأمامية (Flutter App)

### أ. الأدوات والمكتبات (pubspec.yaml)
*   `dio`: للاتصال بالـ API (أفضل من http في إدارة الأخطاء).
*   `provider` أو `flutter_riverpod`: لإدارة الحالة (State Management).
*   `geolocator`: لجلب إحداثيات GPS.
*   `image_picker`: لفتح الكاميرا واختيار الصور.
*   `flutter_secure_storage`: لحفظ التوكن (Token) بأمان.
*   `google_maps_flutter`: لعرض الخرائط (اختياري).

### ب. هيكلية الملفات (Project Structure)

```text
lib/
├── core/
│   ├── constants/          // عناوين الـ API والألوان
│   └── network/            // إعدادات Dio Interceptors
├── models/
│   └── report_model.dart   // نموذج البيانات (JSON parsing)
├── services/
│   ├── auth_service.dart   // تسجيل الدخول
│   └── report_service.dart // إرسال التقارير وجلب القائمة
├── providers/              // إدارة الحالة
│   ├── auth_provider.dart
│   └── report_provider.dart
├── screens/
│   ├── auth/
│   │   └── login_screen.dart
│   ├── home/
│   │   └── home_screen.dart        // عرض قائمة التقارير وحالتها
│   └── report/
│       ├── add_report_screen.dart  // الواجهة الرئيسية للإدخال
│       └── report_detail_screen.dart
└── main.dart
```

### ج. شرح الواجهات (Screens Details)

#### 1. واجهة تسجيل الدخول (`login_screen.dart`)
*   **الأدوات:** `TextField` (إيميل/باسورد)، `ElevatedButton`.
*   **الوظيفة:** ترسل البيانات لـ Laravel، وتستقبل `token` يتم تخزينه محلياً.

#### 2. واجهة إضافة تقرير (`add_report_screen.dart`) - *الأهم*
تحتوي على نموذج (Form) مكون من:
*   **زر التقاط صورة:** يستخدم `ImagePicker`. عند التقاط الصورة، يتم عرضها في `Image.file`.
*   **حقل الموقع الجغرافي:**
    *   زر "تحديد موقعي": يستخدم `Geolocator` لجلب `lat, long` وتخزينها في متغيرات مخفية.
    *   حقل نصي "اسم المنطقة": يكتب فيه المتطوع الاسم يدوياً (سيتم تصحيحه لاحقاً بالـ AI).
*   **حقل الوصف:** `TextField` متعدد الأسطر لكتابة الملاحظات.
*   **زر إرسال:** يقوم بإنشاء `FormData` (يحوي الصورة والحقول) ويرسله عبر `Dio` للباك إند. يظهر `CircularProgressIndicator` أثناء الرفع.

#### 3. واجهة الرئيسية (`home_screen.dart`)
*   تعرض قائمة `ListView` للتقارير التي رفعها هذا المستخدم.
*   تستخدم `FutureBuilder` أو `Consumer` (Provider) لجلب البيانات.
*   تظهر "حالة التقرير" بألوان مختلفة (أصفر: قيد المعالجة، أخضر: تم التحليل).

---

## 5. لوحة التحكم (Laravel Web Admin)

يديرها المدير، وهي مبنية بـ **Blade Templates** مع **Bootstrap 5** أو **Tailwind CSS**.

### الصفحات والأدوات:

1.  **Dashboard (الرئيسية):**
    *   **مكتبة Chart.js:** لعرض "كعكة" (Pie Chart) لنسب الأضرار (مرتفع، متوسط، منخفض).
    *   عدادات (Cards): عدد التقارير الكلي، عدد التقارير التي تم تحليلها.

2.  **خريطة الأضرار (Map View):**
    *   **مكتبة Leaflet.js** (مجانية) أو **Google Maps JS API**.
    *   تقوم بجلب التقارير التي حالتها `completed` وتضع دبابيس (Markers) على الخريطة.
    *   لون الدبوس يعتمد على `ai_damage_level` (أحمر: خطر، برتقالي: متوسط).

3.  **جدول إدارة التقارير:**
    *   عرض جدول البيانات (DataTables).
    *   إمكانية تعديل النتيجة يدوياً إذا أخطأ الـ AI.
    *   زر "تصدير PDF/Excel".

---

## 6. توثيق الـ API (Documentation)

سنستخدم حزمة **Scribe** لتوليد صفحة توثيق كاملة ليفهم مبرمج الفلاتر كيفية التعامل مع الباك إند.

**طريقة الإعداد:**
1.  تنصيب الحزمة: `composer require --dev knuckleswtf/scribe`
2.  النشر: `php artisan vendor:publish --tag=scribe-config`
3.  التوليد: `php artisan scribe:generate`

**النتيجة:** صفحة HTML تحتوي على:
*   Endpoint: `POST /api/login`
*   Endpoint: `POST /api/reports` (يشرح أن الـ Body يجب أن يكون `multipart/form-data`).
*   Endpoint: `GET /api/reports`

---

## 7. الخطة التنفيذية (Step-by-Step Plan)

### المرحلة 1: إعداد الباك إند (Laravel)
1.  تثبيت Laravel وإعداد اتصال قاعدة البيانات.
2.  إنشاء الـ Migration للجداول (`reports`, `users`).
3.  إعداد Sanctum للمصادقة.
4.  إنشاء `ReportController` والـ Routes.
5.  تجربة الـ API بواسطة Postman (رفع صورة + نص).

### المرحلة 2: دمج الذكاء الاصطناعي (AI Integration)
1.  الحصول على API Key من Google AI Studio.
2.  إنشاء `GeminiService`.
3.  إنشاء Job: `ProcessReportWithAI`.
4.  ضبط الـ Queue Worker (`php artisan queue:work`) ليعمل في الخلفية.

### المرحلة 3: بناء تطبيق الموبايل (Flutter)
1.  تصميم الواجهات (UI) بشكل ثابت أولاً.
2.  ربط `auth_service` لتسجيل الدخول وتخزين التوكن.
3.  برمجة منطق التقاط الصورة وGPS.
4.  ربط `add_report_screen` مع الـ API.
5.  عرض حالة المعالجة في القائمة الرئيسية.

### المرحلة 4: لوحة التحكم والخرائط
1.  بناء واجهة الويب للمدير.
2.  دمج الخرائط لعرض النقاط المعالجة.
3.  اختبار النظام كاملاً (دورة كاملة: موبايل -> سيرفر -> AI -> لوحة تحكم).

هذا المخطط يضمن لك نظاماً متكاملاً، حديثاً (Flutter + Laravel)، وقابلاً للتوسع، مع توثيق دقيق لكل جزء.