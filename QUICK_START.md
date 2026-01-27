# Quick Start Guide - Smart Damage Assessment Backend

## إعداد سريع (Quick Setup)

### الخطوة 1: تثبيت المكتبات (Install Dependencies)

```bash
cd backend
composer install
npm install
```

### الخطوة 2: إعداد قاعدة البيانات (Setup Database)

```bash
# إنشاء قاعدة البيانات في MySQL
CREATE DATABASE smart_damage_assessment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# تشغيل الترحيلات
php artisan migrate

# تشغيل الـ Seeders
php artisan db:seed --class=UserSeeder
```

### الخطوة 3: إعداد Gemini API Key

```bash
# تحرير ملف .env
# أضف مفتاح API الخاص بك
GEMINI_API_KEY=your-actual-gemini-api-key-here
```

### الخطوة 4: إعداد Storage Link

```bash
php artisan storage:link
```

### الخطوة 5: تشغيل السيرفرات (Run Servers)

**Terminal 1 - PHP Server:**

```bash
php artisan serve
```

**Terminal 2 - Vite:**

```bash
npm run dev
```

**Terminal 3 - Queue Worker:**

```bash
php artisan queue:work
```

---

## اختبار سريع (Quick Test)

### 1. تسجيل الدخول (Login)

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password"}'
```

**النتيجة المتوقعة:**

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

### 2. إنشاء تقرير (Create Report)

```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "image=@test-image.jpg" \
  -F "latitude=36.2018" \
  -F "longitude=37.1342" \
  -F "raw_location=حلب السكري" \
  -F "raw_description=تضرر المبنى"
```

### 3. عرض التقارير (Get Reports)

```bash
curl -X GET http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## لوحة التحكم (Admin Panel)

1. افتح المتصفح على: `http://localhost:8000/login`
2. سجل الدخول باستخدام:
   - Email: `admin@test.com`
   - Password: `password`
3. انتقل إلى: `http://localhost:8000/admin/dashboard`

---

## بيانات الاختبار (Test Data)

### المستخدمون (Users)

- **Admin:** `admin@test.com` / `password`
- **Field User:** `user@test.com` / `password`

### مثال على طلب إنشاء تقرير (Create Report Example)

**Method:** POST
**URL:** `http://localhost:8000/api/reports`
**Headers:**

```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Body:**

```
image: [اختر ملف صورة]
latitude: 36.2018
longitude: 37.1342
raw_location: حلب السكري
raw_description: تضرر المبنى بشكل كبير
```

---

## مسارات API الرئيسية (Main API Routes)

| Method | Endpoint            | Description         | Auth Required |
| ------ | ------------------- | ------------------- | ------------- |
| POST   | `/api/login`        | Login               | No            |
| POST   | `/api/logout`       | Logout              | Yes           |
| GET    | `/api/me`           | Get current user    | Yes           |
| GET    | `/api/reports`      | Get user reports    | Yes           |
| POST   | `/api/reports`      | Create report       | Yes           |
| GET    | `/api/reports/{id}` | Get specific report | Yes           |

---

## مسارات لوحة التحكم (Admin Panel Routes)

| Method | Endpoint           | Description          |
| ------ | ------------------ | -------------------- |
| GET    | `/login`           | Login page           |
| GET    | `/admin/dashboard` | Dashboard with stats |
| GET    | `/admin/map`       | Map view             |
| GET    | `/admin/reports`   | All reports list     |

---

## استكشاف الأخطاء السريع (Quick Troubleshooting)

### مشكلة: الصور لا تظهر

```bash
php artisan storage:link
```

### مشكلة: Queue Worker لا يعمل

```bash
php artisan queue:work
```

### مشكلة: Gemini API يفشل

1. تأكد من صحة API Key في ملف `.env`
2. تحقق من سجلات الأخطاء:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### مشكلة: CORS Errors

أضف Middleware CORS في [`backend/bootstrap/app.php`](backend/bootstrap/app.php:1):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: \Illuminate\Http\Middleware\HandleCors::class);
})
```

---

## أوامر مفيدة (Useful Commands)

```bash
# عرض جميع المسارات
php artisan route:list

# مسح الكاش
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تشغيل الاختبارات
php artisan test

# عرض سجلات الأخطاء
tail -f storage/logs/laravel.log

# تحديث الـ autoload
composer dump-autoload

# تحسين الأداء
php artisan optimize
```

---

## الخطوات التالية (Next Steps)

1. ✅ اقرأ [`BACKEND_SETUP_GUIDE.md`](BACKEND_SETUP_GUIDE.md:1) للتوصيلات التفصيلية
2. ✅ اقرأ [`API_REFERENCE.md`](API_REFERENCE.md:1) للتفاصيل الكاملة عن الـ API
3. ✅ اختبر الـ API باستخدام Postman أو تطبيق الموبايل
4. ✅ استكشف لوحة التحكم للمدير
5. ✅ راقب Queue Workers للتأكد من معالجة التقارير

---

## دعم ومساعدة (Support)

للمزيد من المعلومات:

- 📖 [`BACKEND_SETUP_GUIDE.md`](BACKEND_SETUP_GUIDE.md:1) - دليل الإعداد الشامل
- 📚 [`API_REFERENCE.md`](API_REFERENCE.md:1) - مرجع API الكامل
- 📄 [`Laravel Backend Core.md`](Laravel%20Backend%20Core.md:1) - المعلومات الأساسية
- 🔍 [`AGENTS.md`](AGENTS.md:1) - إرشادات المطورين

---

## ملخص سريع (Quick Summary)

**المكونات الرئيسية:**

- ✅ Laravel 11 Backend
- ✅ Sanctum API Authentication
- ✅ Breeze Web Authentication
- ✅ Google Gemini AI Integration
- ✅ Queue System for Async Processing
- ✅ Admin Dashboard with Chart.js
- ✅ Image Upload & Storage

**النظام جاهز للاستخدام! 🚀**
