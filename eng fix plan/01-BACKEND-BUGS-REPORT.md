# 🔧 تقرير أخطاء الباك إند - Backend Bugs Report

> **المشروع:** Smart Damage Assessment System
> **تاريخ التدقيق:** 2026-04-11
> **إطار العمل:** Laravel 11 + Sanctum 4 + PHP 8.2

---

## 🔴 BUG-01: `bootstrap/app.php` يستخدم `Route` facade بدون import

**الخطورة:** P0 - حرج
**الملف:** `backend/bootstrap/app.php` (السطر 14)

### المشكلة
```php
// السطر 14 - يستخدم Route بدون import
Route::middleware('web')
    ->group(base_path('routes/auth.php'));
```

`Route` facade غير مستورد في الملف. هذا يعني أن ملف `routes/auth.php` لن يتم تحميله أبداً، وبالتالي:
- صفحات تسجيل الدخول/التسجيل العادية (Breeze) لن تعمل
- مسارات التحقق من البريد الإلكتروني لن تعمل
- مسارات إعادة تعيين كلمة المرور لن تعمل

### الحل
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route; // ← إضافة هذا السطر

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )
    // ... باقي الكود
```

---

## 🔴 BUG-02: `GeminiService` يستخدم `env()` بدل `config()`

**الخطورة:** P0 - حرج
**الملف:** `backend/app/Services/GeminiService.php` (السطر 14)

### المشكلة
```php
public function __construct()
{
    $this->apiKey = env('GEMINI_API_KEY'); // ❌ خطأ
}
```

في الإنتاج عند تشغيل `php artisan config:cache`، دالة `env()` ترجع `null` لأن ملف `.env` لا يُقرأ بعد التخزين المؤقت. هذا سيؤدي لفشل كل طلبات AI.

### الحل

**الخطوة 1:** إنشاء ملف config جديد `backend/config/services.php` (أو إضافة للموجود):
```php
// config/services.php
return [
    // ... existing services
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'timeout' => env('GEMINI_TIMEOUT', 30),
    ],
];
```

**الخطوة 2:** تعديل `GeminiService`:
```php
public function __construct()
{
    $this->apiKey = config('services.gemini.api_key'); // ✅
}
```

---

## 🔴 BUG-03: نموذج Gemini AI متوقف (Deprecated)

**الخطورة:** P0 - حرج
**الملف:** `backend/app/Services/GeminiService.php` (السطر 25)

### المشكلة
```php
$response = Http::timeout(30)->post(
    "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key={$this->apiKey}",
    // ...
);
```

نموذج `gemini-pro-vision` تم إيقافه نهائياً. استدعاء هذا الـ API سيعطي خطأ 404 أو 400.

### الحل
```php
// استخدام النموذج الجديد
$model = config('services.gemini.model', 'gemini-2.0-flash');
$timeout = config('services.gemini.timeout', 30);

$response = Http::timeout($timeout)->post(
    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}",
    [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt],
                    ['text' => "Location: {$rawLocation}. Description: {$rawDescription}"],
                    [
                        'inline_data' => [
                            'mime_type' => $mimeType,
                            'data' => base64_encode(file_get_contents($fullPath))
                        ]
                    ]
                ]
            ]
        ]
    ]
);
```

---

## 🔴 BUG-04: `file_get_contents()` يستخدم مسار نسبي غير صالح

**الخطورة:** P0 - حرج
**الملف:** `backend/app/Services/GeminiService.php` (السطر 35)

### المشكلة
```php
'data' => base64_encode(file_get_contents($imagePath))
// ❌ $imagePath = "reports/xxx.jpg" (مسار نسبي)
// file_get_contents يحتاج مسار كامل مثل: C:\...\storage\app\public\reports\xxx.jpg
```

### الحل
```php
public function analyzeDamage(string $imagePath, string $rawLocation, string $rawDescription): array
{
    try {
        // تحويل المسار النسبي لمسار كامل
        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            Log::error('Image file not found', ['path' => $fullPath]);
            throw new \Exception('Image file not found: ' . $imagePath);
        }

        // تحديد نوع MIME تلقائياً
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

        // ... باقي الكود مع استخدام $fullPath
        'data' => base64_encode(file_get_contents($fullPath)) // ✅
```

---

## 🔴 BUG-05: عمود `email_verified_at` غير موجود في الـ Migration

**الخطورة:** P0 - حرج
**الملف:** `backend/database/migrations/2024_01_01_000001_create_users_and_reports_tables.php`

### المشكلة
الـ Migration الأصلي لا يحتوي على:
- `email_verified_at` (مطلوب من Laravel Breeze والاختبارات)
- User model يعمل cast لـ `'email_verified_at' => 'datetime'`
- `ProfileTest` يتحقق من `email_verified_at`

```php
// المفقود من schema:
$table->timestamp('email_verified_at')->nullable(); // ← غير موجود!
```

### الحل
إنشاء migration جديد:
```php
// database/migrations/2026_04_11_000001_add_email_verified_at_to_users_table.php
Schema::table('users', function (Blueprint $table) {
    $table->timestamp('email_verified_at')->nullable()->after('email');
});
```

---

## 🔴 BUG-06: `UserFactory` غير موجودة

**الخطورة:** P0 - حرج
**المجلد:** `backend/database/factories/` (مفقود بالكامل)

### المشكلة
- `ProfileTest.php` يستخدم `User::factory()->create()` في 5 اختبارات
- لا يوجد مجلد `database/factories/` أصلاً
- جميع الاختبارات ستفشل فوراً

### الحل
إنشاء `database/factories/UserFactory.php`:
```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'field_user',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

---

## 🔴 BUG-07: عمود `image_path` غير nullable في قاعدة البيانات

**الخطورة:** P0 - حرج
**الملف:** `backend/database/migrations/2024_01_01_000001_create_users_and_reports_tables.php` (السطر 25)

### المشكلة
```php
$table->string('image_path'); // ← NOT NULL
```

لكن في الكود:
- `Admin\DashboardController::store()` يضع `'image_path' => ''` (سلسلة فارغة)
- `User\DashboardController::store()` يضع `'image_path' => ''`
- `ReportController::store()` قد يضع `null` عند عدم وجود صورة

أيضاً `raw_description` هو `text` غير nullable لكن الفاليديشن يقبل `nullable`.

### الحل
إنشاء migration:
```php
// database/migrations/2026_04_11_000002_fix_nullable_columns_in_reports.php
Schema::table('reports', function (Blueprint $table) {
    $table->string('image_path')->nullable()->change();
    $table->text('raw_description')->nullable()->change();
});
```

⚠️ **ملاحظة:** يتطلب حزمة `doctrine/dbal` لتعديل الأعمدة:
```bash
composer require doctrine/dbal
```

---

## 🟠 BUG-08: Web Controllers لا تطلق `AnalyzeDamageJob`

**الخطورة:** P1 - عالي
**الملفات:**
- `backend/app/Http/Controllers/User/DashboardController.php` (السطر 85-96)
- `backend/app/Http/Controllers/Admin/DashboardController.php` (السطر 134-146)

### المشكلة
عند إنشاء تقرير من لوحة الويب (سواء Admin أو User)، لا يتم إطلاق `AnalyzeDamageJob`.
التقرير يُحفظ بحالة `pending` ولا يُحلل أبداً بالـ AI.

**فقط** API `ReportController::store()` يطلق الـ Job (السطر 105).

### الحل
إضافة dispatch بعد إنشاء التقرير في كلا الكنترولرين:

```php
// في User\DashboardController::store() بعد Report::create()
$report = Report::create([...]);
\App\Jobs\AnalyzeDamageJob::dispatch($report->id);

// في Admin\DashboardController::store() بعد Report::create()
$report = Report::create([...]);
\App\Jobs\AnalyzeDamageJob::dispatch($report->id);
```

---

## 🟠 BUG-09: Admin `destroy()` لا ينظف الملفات

**الخطورة:** P1 - عالي
**الملف:** `backend/app/Http/Controllers/Admin/DashboardController.php` (السطور 243-247)

### المشكلة
```php
public function destroy(Report $report)
{
    $report->delete(); // ❌ لا ينظف الصور أو PDF
    return redirect()->route('admin.reports')
        ->with('success', 'تم حذف التقرير بنجاح.');
}
```

### الحل
```php
public function destroy(Report $report)
{
    // حذف الصور المتعددة
    if (!empty($report->images) && is_array($report->images)) {
        foreach ($report->images as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
    }

    // حذف الصورة القديمة
    if ($report->image_path && Storage::disk('public')->exists($report->image_path)) {
        Storage::disk('public')->delete($report->image_path);
    }

    // حذف PDF
    if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
        Storage::disk('public')->delete($report->pdf_file);
    }

    $report->delete();
    return redirect()->route('admin.reports')
        ->with('success', 'تم حذف التقرير بنجاح.');
}
```

---

## 🟠 BUG-10: API `/me` يعرض بيانات حساسة

**الخطورة:** P1 - عالي
**الملف:** `backend/app/Http/Controllers/Api/AuthController.php` (السطور 81-84)

### المشكلة
```php
public function me(Request $request)
{
    return response()->json($request->user()); // ❌ يعرض كل شيء
}
```

رغم أن `password` و `remember_token` في `$hidden`، إلا أن `api_token` ليس كذلك ويتم عرضه.

### الحل
```php
public function me(Request $request)
{
    $user = $request->user();
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'profile_image' => $user->profile_image
            ? url('storage/' . $user->profile_image)
            : null,
        'created_at' => $user->created_at->toIso8601String(),
    ]);
}
```

---

## 🟠 BUG-11: `ReportResource` لا يتحقق من تحميل العلاقة

**الخطورة:** P1 - عالي
**الملف:** `backend/app/Http/Resources/ReportResource.php` (السطور 45-48)

### المشكلة
```php
'user' => [
    'id' => $this->user->id,    // ❌ قد يسبب N+1 أو خطأ
    'name' => $this->user->name,
],
```

### الحل
```php
'user' => $this->whenLoaded('user', function () {
    return [
        'id' => $this->user->id,
        'name' => $this->user->name,
    ];
}, [
    'id' => $this->user_id,
    'name' => null,
]),
```

أو ضمان تحميل العلاقة دائماً في الكنترولر:
```php
$reports = auth()->user()->reports()->with('user')->latest()->get();
```

---

## 🟠 BUG-12: هيكل JSON المزدوج في API

**الخطورة:** P1 - عالي
**الملف:** `backend/app/Http/Controllers/Api/ReportController.php` (السطر 39)

### المشكلة
```php
public function index(): \Illuminate\Http\JsonResponse
{
    $reports = auth()->user()->reports()->latest()->get();
    return response()->json(ReportResource::collection($reports));
    // النتيجة: [{"id":1,...}, {"id":2,...}]
    // بدلاً من: {"data": [{"id":1,...}, {"id":2,...}]}
}
```

`ReportResource::collection()` بطبيعتها تلف البيانات في `data`، ولكن `response()->json()` يزيل هذا اللف.

### الحل
```php
public function index(): \Illuminate\Http\JsonResponse
{
    $reports = auth()->user()
        ->reports()
        ->with('user')
        ->latest()
        ->paginate(20);

    return response()->json([
        'data' => ReportResource::collection($reports),
        'meta' => [
            'current_page' => $reports->currentPage(),
            'last_page' => $reports->lastPage(),
            'per_page' => $reports->perPage(),
            'total' => $reports->total(),
        ]
    ]);
}
```

---

## 🟠 BUG-13: كود الأدمن مشفّر في الكنترولر

**الخطورة:** P1 - أمني
**الملف:** `backend/app/Http/Controllers/Auth/AdminAuthController.php` (السطر 17)

### المشكلة
```php
protected string $adminCode = 'ADMIN2026'; // ❌ مشفّر في الكود
```

### الحل
نقل لـ `.env`:
```env
ADMIN_REGISTRATION_CODE=ADMIN2026
```

في الكنترولر:
```php
if ($request->admin_code !== config('app.admin_code')) {
```

في `config/app.php`:
```php
'admin_code' => env('ADMIN_REGISTRATION_CODE', 'CHANGE_ME'),
```

---

## 🟡 BUG-14: لا يوجد إعداد CORS

**الخطورة:** P2 - أمان/توافق
**الملف:** مفقود

### المشكلة
تطبيق Flutter على الموبايل يحتاج CORS headers للتواصل مع الـ API. في Laravel 11، CORS مُدار عبر middleware.

### الحل
في `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: [
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
    // ...
})
```

أو إنشاء `config/cors.php`:
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

---

## 🟡 BUG-15: Scribe Auth معطّل

**الخطورة:** P2 - توثيق
**الملف:** `backend/config/scribe.php` (السطر 106)

### المشكلة
```php
'auth' => [
    'enabled' => false,  // ❌
    'default' => false,
```

التوثيق لن يُظهر أن الـ endpoints تحتاج مصادقة Bearer token.

### الحل
```php
'auth' => [
    'enabled' => true,
    'default' => true,
    'in' => AuthIn::BEARER->value,
    'name' => 'Authorization',
    'use_value' => env('SCRIBE_AUTH_KEY'),
    'placeholder' => 'Bearer {YOUR_AUTH_TOKEN}',
    'extra_info' => 'احصل على التوكن من endpoint تسجيل الدخول: POST /api/login',
],
```

---

## 🟡 BUG-16: لا يوجد Rate Limiting

**الخطورة:** P2 - أمان
**الملف:** `backend/routes/api.php` (السطر 20)

### المشكلة
```php
Route::post('/login', [AuthController::class, 'login']);
// ❌ بدون rate limiting - عرضة لهجمات brute force
```

### الحل
في `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->throttleApi('60,1'); // 60 طلب/دقيقة
})
```

وفي `routes/api.php`:
```php
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 محاولات/دقيقة فقط
```

---

## 🟡 BUG-17 & 18: Endpoints ناقصة في الـ API

**الخطورة:** P2 - وظيفي
**الملف:** `backend/routes/api.php`

### Endpoints المفقودة

```php
// === مطلوب إضافتها ===

// 1. تسجيل مستخدم جديد
Route::post('/register', [AuthController::class, 'register']);

// 2. تحديث تقرير
Route::put('/reports/{id}', [ReportController::class, 'update']);

// 3. تعديل الملف الشخصي
Route::put('/profile', [AuthController::class, 'updateProfile']);

// 4. تغيير كلمة المرور
Route::put('/password', [AuthController::class, 'updatePassword']);

// 5. إحصائيات المستخدم
Route::get('/statistics', [ReportController::class, 'statistics']);
```

---

## 📊 ملخص الإصلاحات المطلوبة

| الملف | الإصلاحات |
|-------|-----------|
| `bootstrap/app.php` | إضافة `use Route`, CORS, rate limiting |
| `Services/GeminiService.php` | استبدال `env()` بـ `config()`, تحديث النموذج, إصلاح المسار |
| `config/services.php` | إضافة إعدادات Gemini |
| `database/migrations/` | إضافة 2 migrations جديدة |
| `database/factories/UserFactory.php` | إنشاء جديد |
| `Api/AuthController.php` | تأمين `/me`, إضافة `register`, `updateProfile` |
| `Api/ReportController.php` | إصلاح هيكل JSON, إضافة pagination, `update` |
| `Resources/ReportResource.php` | فحص تحميل العلاقة |
| `Admin/DashboardController.php` | تنظيف الملفات عند الحذف, إطلاق Job |
| `User/DashboardController.php` | إطلاق Job |
| `Auth/AdminAuthController.php` | نقل كود الأدمن لـ `.env` |
| `config/scribe.php` | تفعيل auth |
| `routes/api.php` | إضافة endpoints ناقصة, rate limiting |
