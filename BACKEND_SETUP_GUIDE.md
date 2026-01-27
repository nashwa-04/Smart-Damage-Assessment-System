# Smart Damage Assessment System - Backend Setup Guide

## نظرة عامة (Overview)

هذا دليل شامل لإعداد وتشغيل Backend نظام توثيق الأضرار باستخدام Laravel 11. النظام يتكون من:

- **API** لتطبيق الموبايل (محمي بـ Sanctum)
- **لوحة تحكم** للمدير (محمية بـ Breeze)
- **معالجة AI** باستخدام Google Gemini API
- **Queue System** للمعالجة غير المتزامنة

---

## 1. المتطلبات الأساسية (Prerequisites)

- PHP 8.2 أو أعلى
- Composer
- MySQL 5.7 أو أعلى
- Node.js 18 أو أعلى
- Git

---

## 2. تثبيت المكتبات (Install Dependencies)

بما أن المشروع قد تم إنشاؤه، تأكد من تثبيت المكتبات التالية:

```bash
# الانتقال إلى مجلد Backend
cd backend

# تثبيت مكتبات PHP
composer install

# تثبيت مكتبات Node.js
npm install

# تثبيت Breeze (لوحة تحكم الأدمن)
composer require laravel/breeze --dev
php artisan breeze:install blade

# تثبيت Sanctum (API Token Auth)
composer require laravel/sanctum
php artisan sanctum:install

# تثبيت Guzzle (للاتصال بـ Gemini API)
composer require guzzlehttp/guzzle

# تثبيت Intervention Image (معالجة الصور)
composer require intervention/image
```

---

## 3. إعداد قاعدة البيانات (Database Setup)

### 3.1 إنشاء قاعدة البيانات

```sql
CREATE DATABASE smart_damage_assessment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2 إعداد ملف `.env`

تأكد من إعدادات قاعدة البيانات في ملف [`backend/.env`](backend/.env:11):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_damage_assessment
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3.3 تشغيل الترحيلات (Migrations)

```bash
php artisan migrate
```

### 3.4 تشغيل الـ Seeders

```bash
php artisan db:seed --class=UserSeeder
```

**المستخدمون المُنشأون:**

- **Admin:** `admin@test.com` / `password`
- **Field User:** `user@test.com` / `password`

---

## 4. إعداد Google Gemini API

### 4.1 الحصول على API Key

1. اذهب إلى [Google AI Studio](https://makersuite.google.com/app/apikey)
2. قم بإنشاء API Key جديد
3. انسخ الـ API Key

### 4.2 إضافة API Key إلى `.env`

```env
GEMINI_API_KEY=your-actual-gemini-api-key-here
```

---

## 5. إعداد Storage Link

لجعل الصور متاحة للوصول العام:

```bash
php artisan storage:link
```

---

## 6. تشغيل خادم التطوير (Development Server)

### 6.1 تشغيل PHP Server

```bash
php artisan serve
```

الخادم سيعمل على: `http://localhost:8000`

### 6.2 تشغيل Vite (للموارد الثابتة)

```bash
npm run dev
```

### 6.3 تشغيل Queue Worker (للمعالجة غير المتزامنة)

```bash
php artisan queue:work
```

**ملاحظة:** في بيئة الإنتاج، استخدم Supervisor لإدارة Queue Workers.

---

## 7. هيكل المشروع (Project Structure)

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php      # تسجيل الدخول/الخروج للـ API
│   │   │   │   └── ReportController.php    # إدارة التقارير
│   │   │   └── Admin/
│   │   │       └── DashboardController.php  # لوحة تحكم الأدمن
│   │   ├── Requests/
│   │   │   └── StoreReportRequest.php     # التحقق من البيانات
│   │   └── Resources/
│   │       └── ReportResource.php          # تحويل البيانات إلى JSON
│   ├── Jobs/
│   │   └── AnalyzeDamageJob.php           # معالجة AI غير متزامنة
│   ├── Models/
│   │   ├── User.php                        # نموذج المستخدم
│   │   └── Report.php                      # نموذج التقرير
│   └── Services/
│       └── GeminiService.php               # خدمة الاتصال بـ Gemini API
├── database/
│   ├── migrations/
│   │   └── 2024_01_01_000001_create_users_and_reports_tables.php
│   └── seeders/
│       └── UserSeeder.php
├── routes/
│   ├── api.php                             # مسارات API
│   └── web.php                             # مسارات الويب
└── resources/views/
    └── admin/
        ├── dashboard.blade.php             # لوحة التحكم
        ├── map.blade.php                   # عرض الخريطة
        └── reports.blade.php               # قائمة التقارير
```

---

## 8. واجهات API (API Endpoints)

### 8.1 المصادقة (Authentication)

#### POST `/api/login`

تسجيل الدخول والحصول على Token

**Request:**

```json
{
  "email": "user@test.com",
  "password": "password"
}
```

**Response:**

```json
{
  "token": "1|xyz...",
  "user": {
    "id": 2,
    "name": "Field Officer",
    "email": "user@test.com",
    "role": "field_user"
  }
}
```

#### POST `/api/logout`

تسجيل الخروج (يتطلب Token)

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
  "message": "Logged out successfully"
}
```

#### GET `/api/me`

الحصول على بيانات المستخدم الحالي (يتطلب Token)

**Response:**

```json
{
  "id": 2,
  "name": "Field Officer",
  "email": "user@test.com",
  "role": "field_user"
}
```

### 8.2 التقارير (Reports)

#### GET `/api/reports`

الحصول على جميع تقارير المستخدم الحالي (يتطلب Token)

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "name": "Field Officer"
      },
      "image_url": "http://localhost:8000/storage/reports/image.jpg",
      "location": {
        "raw": "حلب السكري",
        "normalized": "Aleppo, Syria",
        "coordinates": {
          "latitude": 36.2018,
          "longitude": 37.1342
        }
      },
      "description": {
        "raw": "تضرر المبنى بشكل كبير",
        "ai_analysis": "Building shows significant structural damage..."
      },
      "damage_assessment": {
        "level": "high",
        "status": "completed"
      },
      "created_at": "2024-01-19 10:30:00",
      "updated_at": "2024-01-19 10:35:00"
    }
  ]
}
```

#### POST `/api/reports`

إنشاء تقرير جديد (يتطلب Token)

**Request (multipart/form-data):**

```
image: [file]
latitude: 36.2018
longitude: 37.1342
raw_location: حلب السكري
raw_description: تضرر المبنى بشكل كبير
```

**Response:**

```json
{
  "data": {
    "id": 1,
    "status": "pending",
    "message": "Report submitted successfully. Processing will start shortly."
  }
}
```

#### GET `/api/reports/{id}`

الحصول على تقرير محدد (يتطلب Token)

**Response:**

```json
{
  "data": {
    "id": 1,
    "user": {
      "id": 2,
      "name": "Field Officer"
    },
    "image_url": "http://localhost:8000/storage/reports/image.jpg",
    "location": {
      "raw": "حلب السكري",
      "normalized": "Aleppo, Syria",
      "coordinates": {
        "latitude": 36.2018,
        "longitude": 37.1342
      }
    },
    "description": {
      "raw": "تضرر المبنى بشكل كبير",
      "ai_analysis": "Building shows significant structural damage..."
    },
    "damage_assessment": {
      "level": "high",
      "status": "completed"
    },
    "created_at": "2024-01-19 10:30:00",
    "updated_at": "2024-01-19 10:35:00"
  }
}
```

---

## 9. واجهات لوحة التحكم (Admin Panel Routes)

### 9.1 المصادقة (Authentication)

باستخدام Laravel Breeze:

- **GET `/login`** - صفحة تسجيل الدخول
- **POST `/login`** - معالجة تسجيل الدخول
- **POST `/logout`** - تسجيل الخروج

### 9.2 لوحة التحكم (Dashboard)

#### GET `/admin/dashboard`

لوحة التحكم الرئيسية مع الإحصائيات

**المحتوى:**

- إجمالي عدد التقارير
- عدد التقارير المكتملة
- عدد التقارير المعلقة
- إجمالي عدد المستخدمين
- رسم بياني لتوزيع الأضرار
- قائمة بأحدث التقارير

#### GET `/admin/map`

عرض الخريطة مع مواقع التقارير

#### GET `/admin/reports`

قائمة جميع التقارير مع التصفح (Pagination)

---

## 10. سير العمل (Workflow)

### 10.1 إنشاء تقرير جديد

1. **المستخدم** يرسل طلب POST إلى `/api/reports` مع:

   - صورة الضرر
   - الإحداثيات (latitude, longitude)
   - الموقع (raw_location)
   - الوصف (raw_description)

2. **السيرفر** يحفظ الصورة في `storage/app/public/reports`

3. **السيرفر** ينشئ سجل في جدول `reports` بحالة `pending`

4. **السيرفر** يطلق Job (`AnalyzeDamageJob`) للمعالجة غير المتزامنة

5. **Queue Worker** يلتقط الـ Job ويرسل البيانات إلى Gemini API

6. **Gemini API** يحلل الصورة والنص ويعيد:

   - الموقع الموحد (normalized_location)
   - مستوى الضرر (damage_level: low/medium/high/critical)
   - التحليل النصي (analysis_text)

7. **السيرفر** يحدث سجل التقرير بالبيانات العائدة ويغير الحالة إلى `completed`

### 10.2 عرض التقارير

- **المستخدمون** يمكنهم رؤية تقاريرهم فقط عبر `/api/reports`
- **الأدمن** يمكنه رؤية جميع التقارير عبر لوحة التحكم `/admin/reports`

---

## 11. اختبار النظام (Testing)

### 11.1 اختبار API باستخدام Postman

1. **تسجيل الدخول:**

   - Method: POST
   - URL: `http://localhost:8000/api/login`
   - Body (JSON):
     ```json
     {
       "email": "user@test.com",
       "password": "password"
     }
     ```

2. **إنشاء تقرير:**

   - Method: POST
   - URL: `http://localhost:8000/api/reports`
   - Headers:
     ```
     Authorization: Bearer {token_from_login}
     Content-Type: multipart/form-data
     ```
   - Body (form-data):
     ```
     image: [select image file]
     latitude: 36.2018
     longitude: 37.1342
     raw_location: حلب السكري
     raw_description: تضرر المبنى
     ```

3. **عرض التقارير:**
   - Method: GET
   - URL: `http://localhost:8000/api/reports`
   - Headers:
     ```
     Authorization: Bearer {token_from_login}
     ```

### 11.2 اختبار لوحة التحكم

1. افتح المتصفح على: `http://localhost:8000/login`
2. سجل الدخول باستخدام: `admin@test.com` / `password`
3. انتقل إلى: `http://localhost:8000/admin/dashboard`

---

## 12. أوامر مفيدة (Useful Commands)

```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبار محدد
php artisan test --filter ReportTest

# مسح الكاش
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تحديث الـ autoload
composer dump-autoload

# تحسين الأداء
php artisan optimize

# عرض قائمة المسارات
php artisan route:list

# عرض سجلات الأخطاء
tail -f storage/logs/laravel.log

# إعادة تشغيل Queue Worker
php artisan queue:restart
```

---

## 13. استكشاف الأخطاء (Troubleshooting)

### 13.1 مشكلة: الصور لا تظهر

**الحل:**

```bash
php artisan storage:link
```

### 13.2 مشكلة: Queue Worker لا يعمل

**الحل:**

```bash
# تأكد من تشغيل Queue Worker
php artisan queue:work

# أو استخدم database queue
php artisan queue:work --queue=database
```

### 13.3 مشكلة: Gemini API يفشل

**الحل:**

1. تأكد من صحة API Key في ملف `.env`
2. تأكد من أن API Key لديه الصلاحيات المطلوبة
3. تحقق من سجلات الأخطاء:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### 13.4 مشكلة: CORS Errors

**الحل:** أضف Middleware CORS في [`backend/bootstrap/app.php`](backend/bootstrap/app.php:1):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: \Illuminate\Http\Middleware\HandleCors::class);
})
```

---

## 14. الإنتاج (Production)

### 14.1 إعداد بيئة الإنتاج

1. **تغيير `.env`:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

2. **تحسين الأداء:**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

3. **إعداد Queue Worker باستخدام Supervisor:**

إنشاء ملف `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/backend/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path-to-your-project/backend/storage/logs/worker.log
```

4. **إعادة تشغيل Supervisor:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 15. الأمان (Security)

### 15.1 حماية الـ API

- جميع مسارات API محمية بـ Sanctum Token
- يجب تضمين Token في Header: `Authorization: Bearer {token}`

### 15.2 التحقق من الصور

- الصور محدودة بحجم 10MB كحد أقصى
- يجب أن تكون من نوع صورة (jpg, png, etc.)

### 15.3 التحقق من الإحداثيات

- Latitude: بين -90 و 90
- Longitude: بين -180 و 180

---

## 16. الخلاصة (Conclusion)

النظام الآن جاهز للاستخدام! يمكنك:

1. **اختبار API** باستخدام Postman أو تطبيق الموبايل
2. **استخدام لوحة التحكم** للمدير عبر المتصفح
3. **مراقبة Queue Workers** للتأكد من معالجة التقارير
4. **عرض التقارير** على الخريطة مع تحليل AI

لأي استفسارات أو مشاكل، راجع قسم "استكشاف الأخطاء" أو سجلات Laravel في `storage/logs/laravel.log`.

---

## 17. روابط مفيدة (Useful Links)

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Breeze](https://laravel.com/docs/starter-kits)
- [Google Gemini API](https://ai.google.dev/docs)
- [Chart.js](https://www.chartjs.org/)
