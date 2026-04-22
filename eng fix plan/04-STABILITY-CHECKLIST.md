# ✅ قائمة فحص الاستقرار للنشر - Stability Checklist

> **المشروع:** Smart Damage Assessment System
> **تاريخ:** 2026-04-11

---

## 🔴 المرحلة 1: إصلاحات P0 الحرجة (BLOCKING)

> هذه الإصلاحات **مطلوبة** قبل أي تشغيل للنظام

### 1.1 إصلاح `bootstrap/app.php`
- [ ] إضافة `use Illuminate\Support\Facades\Route;`
- [ ] التحقق: `php artisan route:list` يعرض مسارات auth

### 1.2 إنشاء `config/services.php` (Gemini config)
- [ ] إضافة مفتاح `gemini` مع `api_key`, `model`, `timeout`
- [ ] التحقق: `php artisan tinker` → `config('services.gemini.api_key')` يعرض القيمة

### 1.3 إصلاح `GeminiService.php`
- [ ] استبدال `env('GEMINI_API_KEY')` بـ `config('services.gemini.api_key')`
- [ ] تحديث النموذج من `gemini-pro-vision` لـ `gemini-2.0-flash`
- [ ] إصلاح `file_get_contents()` ليستخدم `storage_path('app/public/'.$path)`
- [ ] إضافة فحص `file_exists()` قبل القراءة
- [ ] إضافة تحديد MIME type تلقائي
- [ ] التحقق: Queue worker يعالج job بدون أخطاء

### 1.4 إنشاء Migration: `email_verified_at`
- [ ] إنشاء migration يضيف `email_verified_at` لجدول `users`
- [ ] التحقق: `php artisan migrate` بدون أخطاء

### 1.5 إنشاء `UserFactory`
- [ ] إنشاء `database/factories/UserFactory.php`
- [ ] تعريف `definition()` مع كل الحقول
- [ ] إضافة state method `admin()` و `unverified()`
- [ ] التحقق: `php artisan test --filter=ProfileTest` يمر

### 1.6 إنشاء Migration: nullable columns
- [ ] تحويل `image_path` لـ nullable
- [ ] تحويل `raw_description` لـ nullable
- [ ] إضافة `composer require doctrine/dbal` إذا لزم
- [ ] التحقق: إنشاء تقرير بدون صورة لا يعطي خطأ

### 1.7 تشغيل Migrations
- [ ] `php artisan migrate:fresh --seed`
- [ ] التحقق: كل الجداول موجودة بالبنية الصحيحة

**✅ نقطة تحقق:** بعد إنهاء المرحلة 1، يجب أن يعمل:
```bash
php artisan test  # ← يجب أن يمر بنجاح
php artisan route:list  # ← يعرض كل المسارات
```

---

## 🟠 المرحلة 2: إصلاحات P1 العالية

### 2.1 إطلاق AI Job من Web Controllers
- [ ] إضافة `AnalyzeDamageJob::dispatch()` في `User\DashboardController::store()`
- [ ] إضافة `AnalyzeDamageJob::dispatch()` في `Admin\DashboardController::store()`
- [ ] التحقق: إنشاء تقرير من الويب → فحص `jobs` table

### 2.2 تنظيف الملفات عند الحذف (Admin)
- [ ] إضافة حذف الصور و PDF في `Admin\DashboardController::destroy()`
- [ ] التحقق: حذف تقرير → الملفات محذوفة من `storage/app/public/`

### 2.3 تأمين `/api/me`
- [ ] تعديل `AuthController::me()` ليعرض حقول محددة فقط
- [ ] إزالة `api_token` من الاستجابة
- [ ] التحقق: curl `/api/me` لا يعرض password أو api_token

### 2.4 إصلاح `ReportResource`
- [ ] استخدام `whenLoaded('user')` أو ضمان `with('user')` في الكنترولر
- [ ] التحقق: لا يوجد N+1 queries (فحص debug bar)

### 2.5 إصلاح هيكل JSON في API
- [ ] إضافة pagination في `ReportController::index()`
- [ ] التأكد من هيكل `{"data":[...],"meta":{...}}`
- [ ] التحقق: curl `/api/reports` يعرض الهيكل الصحيح

### 2.6 نقل كود الأدمن لـ `.env`
- [ ] إضافة `ADMIN_REGISTRATION_CODE` في `.env` و `.env.example`
- [ ] إضافة `'admin_code'` في `config/app.php`
- [ ] تعديل `AdminAuthController` ليقرأ من config
- [ ] التحقق: التسجيل كأدمن يعمل

**✅ نقطة تحقق:** تشغيل اختبارات API اليدوية كلها تمر.

---

## 🟡 المرحلة 3: إصلاحات P2

### 3.1 إعداد CORS
- [ ] إنشاء/تحديث `config/cors.php`
- [ ] التأكد من تحميل CORS middleware
- [ ] التحقق: طلب من Flutter لا يعطي CORS error

### 3.2 تحديث Scribe Config
- [ ] تفعيل `auth.enabled` و `auth.default`
- [ ] تعيين `in` لـ Bearer
- [ ] التحقق: `php artisan scribe:generate` ← التوثيق يظهر auth

### 3.3 إضافة Rate Limiting
- [ ] إضافة `throttle:5,1` على `/api/login`
- [ ] التحقق: 6 محاولات login تعطي 429

### 3.4 إضافة Endpoints ناقصة

#### POST /api/register
- [ ] إضافة method `register()` في `Api\AuthController`
- [ ] الحقول: name, email, password, password_confirmation
- [ ] التحقق: تسجيل مستخدم جديد من API يعمل

#### PUT /api/reports/{id}
- [ ] إضافة method `update()` في `Api\ReportController`
- [ ] التحقق: تعديل تقرير من API يعمل

#### PUT /api/profile
- [ ] إضافة method `updateProfile()` في `Api\AuthController`
- [ ] التحقق: تعديل الاسم والبريد والصورة من API يعمل

#### PUT /api/password
- [ ] إضافة method `updatePassword()` في `Api\AuthController`
- [ ] التحقق: تغيير كلمة المرور من API يعمل

#### GET /api/statistics
- [ ] إضافة method `statistics()` في `Api\ReportController`
- [ ] التحقق: إرجاع عدد التقارير بالحالات المختلفة

---

## 🔵 المرحلة 4: تحسينات P3

### 4.1 كتابة اختبارات Feature جديدة
- [ ] `tests/Feature/Api/AuthTest.php` (5 اختبارات)
- [ ] `tests/Feature/Api/ReportTest.php` (8 اختبارات)
- [ ] `tests/Feature/Api/AdminTest.php` (4 اختبارات)
- [ ] التحقق: `php artisan test` يمر 100%

### 4.2 كتابة اختبارات Unit
- [ ] `tests/Unit/GeminiServiceTest.php`
- [ ] `tests/Unit/ReportResourceTest.php`

### 4.3 تحسينات عامة
- [ ] إضافة `ReportFactory` للاختبارات
- [ ] تنظيم ملفات التوثيق المكررة في المجلد الجذري
- [ ] تحديث `README.md` بالمعلومات الجديدة
- [ ] إعادة توليد توثيق Scribe

---

## 🚀 المرحلة 5: التحقق النهائي

### 5.1 اختبارات شاملة
- [ ] `php artisan test` → 100% pass
- [ ] `php artisan migrate:fresh --seed` → بدون أخطاء
- [ ] Login/Logout API → يعمل
- [ ] Create Report API → يعمل مع صور
- [ ] Queue Worker → يعالج AI jobs
- [ ] Admin Dashboard Web → يعرض إحصائيات
- [ ] Admin Map → يعرض markers
- [ ] User Dashboard Web → يعمل

### 5.2 اختبار من Flutter
- [ ] Login → نجاح
- [ ] Get Reports → يعرض القائمة
- [ ] Create Report → record جديد
- [ ] Refresh → التقرير محدّث بنتائج AI

### 5.3 فحص أمان نهائي
- [ ] لا يوجد `env()` في كود غير config
- [ ] لا يوجد كلمات مرور مشفرة في الكود
- [ ] CORS مهيأ بشكل صحيح
- [ ] Rate limiting فعّال
- [ ] لا يوجد بيانات حساسة في API responses

---

## 📊 ملخص الملفات المتأثرة

### ملفات تحتاج تعديل (Modify):
```
backend/bootstrap/app.php
backend/app/Services/GeminiService.php
backend/app/Http/Controllers/Api/AuthController.php
backend/app/Http/Controllers/Api/ReportController.php
backend/app/Http/Controllers/Admin/DashboardController.php
backend/app/Http/Controllers/User/DashboardController.php
backend/app/Http/Controllers/Auth/AdminAuthController.php
backend/app/Http/Resources/ReportResource.php
backend/config/scribe.php
backend/routes/api.php
backend/.env.example
```

### ملفات جديدة (Create):
```
backend/config/services.php  (أو تعديل الموجود)
backend/database/factories/UserFactory.php
backend/database/migrations/2026_04_11_000001_add_email_verified_at_to_users_table.php
backend/database/migrations/2026_04_11_000002_fix_nullable_columns_in_reports.php
backend/tests/Feature/Api/AuthTest.php
backend/tests/Feature/Api/ReportTest.php
backend/tests/Feature/Api/AdminTest.php
```

### عدد الملفات:
- **تعديل:** 11 ملف
- **إنشاء:** 7 ملفات
- **المجموع:** 18 ملف

---

## ⏰ تقدير الوقت

| المرحلة | الوقت | الأولوية |
|---------|-------|----------|
| المرحلة 1 (P0) | 2-3 ساعات | 🔴 فوري |
| المرحلة 2 (P1) | 2-3 ساعات | 🟠 ضروري |
| المرحلة 3 (P2) | 1-2 ساعة | 🟡 مهم |
| المرحلة 4 (P3) | 1-2 ساعة | 🔵 مرغوب |
| المرحلة 5 (تحقق) | 1 ساعة | ✅ إلزامي |
| **المجموع** | **7-11 ساعة** | |
