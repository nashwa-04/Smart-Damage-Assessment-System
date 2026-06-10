# إصلاح مشكلة تحديث التقرير - 405 Method Not Allowed

## المشكلة
تطبيق Flutter يرسل `POST /api/reports/105` لتحديث تقرير، لكن Laravel لم يكن لديه راوت للتحديث، مما أدى لخطأ **405 Method Not Allowed**.

## الحل المطبق

### 1. إضافة راوت التحديث في `routes/api.php`
```php
// User routes
Route::match(['put', 'post'], '/reports/{id}', [ReportController::class, 'update']);

// Admin routes
Route::put('/reports/{id}', [ReportController::class, 'update']);
```

### 2. إضافة دالة `update()` في `ReportController`
- تتحقق من ملكية التقرير
- تحدث الصور/PDF/البيانات
- تعيد تشغيل تحليل Gemini AI
- ترجع التقرير المحدث

### 3. إنشاء `UpdateReportRequest`
- Validation مشابه لـ `StoreReportRequest` لكن بحقول `sometimes`

### 4. مسح الكاش
- `php artisan route:clear`
- `php artisan config:clear`
- `php artisan cache:clear`

## النتيجة
الآن `POST /api/reports/{id}` يعمل بشكل صحيح لتحديث التقارير.
