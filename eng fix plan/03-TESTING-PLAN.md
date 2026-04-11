# 🧪 خطة الاختبارات والتحقق - Testing Plan

> **المشروع:** Smart Damage Assessment System
> **تاريخ:** 2026-04-11

---

## 📋 المرحلة 1: اختبارات ما قبل الإصلاح (التحقق من المشاكل)

### 1.1 التحقق من كل مشكلة مكتشفة

| # | الاختبار | النتيجة المتوقعة (قبل الإصلاح) |
|---|----------|-------------------------------|
| 1 | `php artisan route:list` | ❌ مسارات auth.php مفقودة |
| 2 | `php artisan config:cache && php artisan tinker` ← `config('services.gemini')` | ❌ null (لا يوجد config) |
| 3 | `php artisan test` | ❌ فشل (UserFactory مفقودة) |
| 4 | إرسال تقرير من الويب → فحص `jobs` table | ❌ لا يوجد job (لم يُطلق) |
| 5 | `curl -X GET http://localhost:8000/api/me -H "Authorization: Bearer TOKEN"` | ❌ يعرض `api_token` |

---

## 📋 المرحلة 2: اختبارات بعد الإصلاح

### 2.1 اختبارات البنية (Structural Tests)

```bash
# ✅ التحقق من عدم وجود أخطاء syntax
php artisan route:list --columns=method,uri,name,action

# ✅ التحقق من قاعدة البيانات
php artisan migrate:fresh --seed

# ✅ التحقق من التخزين المؤقت
php artisan config:cache
php artisan config:clear

# ✅ تشغيل جميع الاختبارات
php artisan test
```

### 2.2 اختبارات API يدوية (Manual API Tests)

#### Test 1: Health Check
```bash
curl -s http://localhost:8000/api/ | python -m json.tool
# متوقع: {"status":"ok","message":"API is running","version":"1.0.0"}
```

#### Test 2: Login
```bash
# نجاح
curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password"}' | python -m json.tool
# متوقع: {"token":"...","user":{...}}

# فشل
curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"wrong@test.com","password":"wrong"}' | python -m json.tool
# متوقع: {"error":"Invalid credentials"} (401)
```

#### Test 3: Get Reports
```bash
TOKEN="1|xxxx"  # من نتيجة Login
curl -s -X GET http://localhost:8000/api/reports \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | python -m json.tool
# متوقع: {"data":[...],"meta":{...}}
```

#### Test 4: Create Report
```bash
curl -s -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer $TOKEN" \
  -F "images[]=@/path/to/image.jpg" \
  -F "latitude=36.2018" \
  -F "longitude=37.1342" \
  -F "raw_location=حلب السكري" \
  -F "raw_description=أضرار في المبنى" | python -m json.tool
# متوقع: {"data":{"id":X,"status":"pending","message":"..."}} (201)
```

#### Test 5: Get Me (بعد الإصلاح)
```bash
curl -s -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | python -m json.tool
# متوقع: لا يحتوي على password أو api_token
```

#### Test 6: Admin Endpoints
```bash
ADMIN_TOKEN="1|xxxx"  # من login بحساب admin
curl -s -X GET http://localhost:8000/api/admin/users \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Accept: application/json" | python -m json.tool
# متوقع: {"success":true,"data":[...],"meta":{...}}

# Test with field_user token (should fail)
curl -s -X GET http://localhost:8000/api/admin/users \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | python -m json.tool
# متوقع: {"success":false,"message":"Unauthorized..."} (403)
```

#### Test 7: Validation
```bash
curl -s -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{}' | python -m json.tool
# متوقع: {"errors":{"latitude":[...],"raw_location":[...]}} (422)
```

#### Test 8: Unauthorized Access
```bash
curl -s -X GET http://localhost:8000/api/reports \
  -H "Accept: application/json" | python -m json.tool
# متوقع: {"message":"Unauthenticated."} (401)
```

---

### 2.3 اختبارات الـ AI Pipeline

```bash
# 1. إنشاء تقرير مع صورة
curl -s -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer $TOKEN" \
  -F "images[]=@test_image.jpg" \
  -F "latitude=36.2" \
  -F "longitude=37.1" \
  -F "raw_location=حلب" | python -m json.tool
# حفظ الـ ID

# 2. تشغيل Queue Worker
php artisan queue:work --once

# 3. فحص حالة التقرير
curl -s -X GET http://localhost:8000/api/reports/{ID} \
  -H "Authorization: Bearer $TOKEN" | python -m json.tool
# متوقع: status = "completed" أو "rejected"
# متوقع: damage_assessment.level != null (إذا completed)
```

---

### 2.4 اختبارات الأمان (Security Tests)

| # | الاختبار | النتيجة المتوقعة |
|---|----------|------------------|
| 1 | طلب بدون token | 401 Unauthenticated |
| 2 | طلب admin endpoint بـ field_user token | 403 Unauthorized |
| 3 | محاولة رؤية تقرير مستخدم آخر | 404 Not Found |
| 4 | رفع ملف .exe بدل صورة | 422 Validation Error |
| 5 | رفع صورة > 10MB | 422 Validation Error |
| 6 | 10 محاولات login خاطئة | 429 Too Many Requests |
| 7 | SQL Injection في search | لا تأثير (Eloquent محمي) |
| 8 | `/api/me` لا يكشف password hash | ✅ محمي |

---

### 2.5 اختبارات لوحة الويب (Web Dashboard)

| # | المسار | التحقق |
|---|--------|--------|
| 1 | `/` | الصفحة الرئيسية أو redirect |
| 2 | `/admin/login` | نموذج تسجيل دخول |
| 3 | `/admin/dashboard` | إحصائيات + رسوم بيانية |
| 4 | `/admin/reports` | جدول التقارير |
| 5 | `/admin/map` | خريطة مع markers |
| 6 | `/admin/reports/create` | نموذج إنشاء تقرير |
| 7 | `/admin/reports/{id}` | تفاصيل التقرير |
| 8 | `/admin/reports/{id}/edit` | تعديل التقرير |
| 9 | `/user/dashboard` | لوحة المستخدم |
| 10 | `/user/reports` | تقارير المستخدم |
| 11 | `/user/reports/create` | إنشاء تقرير |
| 12 | `/user/profile` | الملف الشخصي |

---

## 📋 المرحلة 3: كتابة اختبارات آلية جديدة

### 3.1 اختبارات API مطلوبة (Feature Tests)

```php
// tests/Feature/Api/AuthTest.php
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    public function test_user_cannot_login_with_invalid_credentials()
    public function test_user_can_logout()
    public function test_me_endpoint_returns_user_data()
    public function test_me_endpoint_hides_sensitive_fields()
}

// tests/Feature/Api/ReportTest.php
class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_own_reports()
    public function test_user_cannot_see_other_users_reports()
    public function test_user_can_create_report_with_images()
    public function test_user_can_create_report_with_single_image()
    public function test_report_creation_validates_required_fields()
    public function test_report_creation_dispatches_ai_job()
    public function test_user_can_delete_own_report()
    public function test_report_response_has_correct_structure()
}

// tests/Feature/Api/AdminTest.php
class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users()
    public function test_field_user_cannot_access_admin_endpoints()
    public function test_admin_can_create_user()
    public function test_admin_cannot_delete_self()
}
```

### 3.2 اختبارات Unit مطلوبة

```php
// tests/Unit/GeminiServiceTest.php
class GeminiServiceTest extends TestCase
{
    public function test_parse_response_extracts_damage_level()
    public function test_parse_response_handles_malformed_json()
    public function test_parse_response_defaults_to_medium()
}

// tests/Unit/ReportResourceTest.php
class ReportResourceTest extends TestCase
{
    public function test_resource_formats_images_correctly()
    public function test_resource_handles_legacy_image_path()
    public function test_resource_handles_null_ai_fields()
}
```

---

## ✅ قائمة فحص نهائية

### قبل النشر (Pre-deployment Checklist)

- [ ] `php artisan test` يمر بنجاح (100%)
- [ ] `php artisan route:list` يعرض كل المسارات
- [ ] `php artisan migrate:fresh --seed` بدون أخطاء
- [ ] API login يعمل
- [ ] API create report يعمل
- [ ] Queue worker يعالج الـ jobs
- [ ] Admin dashboard يظهر البيانات
- [ ] Map page تعمل
- [ ] CORS يسمح لطلبات Flutter
- [ ] `storage:link` تم تنفيذه
- [ ] `.env` يحتوي على `GEMINI_API_KEY` صالح
- [ ] لا يوجد `env()` في كود غير config files
