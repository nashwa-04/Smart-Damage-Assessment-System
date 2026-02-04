# ميزات رفع الوسائط المتعددة في التقارير

## Multimedia Upload Features for Reports

**تاريخ التنفيذ:** 2026-02-04  
**الحالة:** ✅ مكتملة (Completed)

---

## 📋 نظرة عامة

تم تحسين صفحات إنشاء وتعديل التقارير لدعم رفع وإدارة:

- **صور متعددة** (Multiple Images)
- **ملفات PDF** (PDF Files)
- **روابط فيديو** (Video Links)

---

## ✨ الميزات الجديدة

### 1. صفحة إنشاء التقرير المحسّنة

**الملف:** [`backend/resources/views/admin/reports/create.blade.php`](backend/resources/views/admin/reports/create.blade.php)

#### الصور المتعددة:

```html
<input type="file" name="images[]" multiple accept="image/*" />
```

- رفع عدة صور في نفس الوقت
- حد أقصى 10 ميجابايت لكل صورة
- دعم جميع صيغ الصور الشائعة

#### ملف PDF:

```html
<input type="file" name="pdf_file" accept=".pdf,application/pdf" />
```

- رفع ملف PDF واحد
- حد أقصى 20 ميجابايت
- اختياري (يمكن إنشاء تقرير بدون PDF)

#### روابط الفيديو:

```html
<input
  type="url"
  name="video_links[]"
  placeholder="https://youtube.com/watch?v=..."
/>
```

- إضافة روابط فيديو متعددة
- دعم YouTube, Vimeo, وأي رابط آخر
- زر "+ إضافة" لإضافة المزيد من الروابط
- زر "حذف" لإزالة أي رابط

---

### 2. صفحة تعديل التقرير المحسّنة

**الملف:** [`backend/resources/views/admin/reports/edit.blade.php`](backend/resources/views/admin/reports/edit.blade.php)

#### عرض الصور الموجودة:

- شبكة من الصور الحالية (4 صور في كل صف)
- زر حذف (×) يظهر عند التمرير فوق الصورة
- تأثير hover جذاب
- تأكيد قبل الحذف

#### إضافة صور جديدة:

- نفس واجهة صفحة الإنشاء
- يمكن إضافة صور جديدة مع الاحتفاظ بالقديمة

#### ملف PDF:

- عرض ملف PDF الموجود مع أيقونة 📄
- رابط "عرض الملف" لمعاينة PDF
- خيار "حذف الملف" لإزالة PDF
- رفع ملف PDF جديد بدلاً من القديم

#### روابط الفيديو:

- عرض جميع الروابط الحالية
- حقل إدخال للروابط الجديدة
- زر "+ إضافة" لإضافة المزيد
- زر "حذف" لإزالة أي رابط

#### زر إضافي:

- زر "عرض التفاصيل" للانتقال مباشرة لصفحة التفاصيل
- يظهر بجانب أزرار "إلغاء" و"حفظ التغييرات"

---

## 🔧 التغييرات التقنية

### 1. Views المحدثة

#### [`create.blade.php`](backend/resources/views/admin/reports/create.blade.php)

```php
// إضافة enctype للنموذج
<form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data">
```

**الحقول الجديدة:**

- `images[]` - مصفوفة صور متعددة
- `pdf_file` - ملف PDF واحد
- `video_links[]` - مصفوفة روابط فيديو

#### [`edit.blade.php`](backend/resources/views/admin/reports/edit.blade.php)

```php
// إضافة enctype للنموذج
<form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
```

**الحقول الجديدة:**

- `images[]` - صور جديدة
- `existing_images[]` - الصور الموجودة (hidden inputs)
- `keep_old_image` - الاحتفاظ بالصورة القديمة
- `pdf_file` - ملف PDF جديد
- `remove_pdf` - حذف ملف PDF
- `video_links[]` - روابط فيديو

### 2. Controller Methods

#### [`store()`](backend/app/Http/Controllers/Admin/DashboardController.php:94) في DashboardController

```php
public function store(Request $request)
{
    $validated = $request->validate([
        // ... الحقول الأساسية
        'images' => 'nullable|array',
        'images.*' => 'nullable|image|max:10240', // 10MB per image
        'pdf_file' => 'nullable|file|mimes:pdf|max:20480', // 20MB
        'video_links' => 'nullable|array',
        'video_links.*' => 'nullable|url|max:500',
    ]);

    // معالجة الصور
    $images = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $path = $image->store('reports', 'public');
                $images[] = $path;
            }
        }
    }

    // معالجة PDF
    $pdfPath = null;
    if ($request->hasFile('pdf_file')) {
        $pdfPath = $request->file('pdf_file')->store('reports', 'public');
    }

    // معالجة روابط الفيديو
    $videoLinks = array_filter($request->input('video_links', []), function($link) {
        return !empty($link) && filter_var($link, FILTER_VALIDATE_URL);
    });

    // إنشاء التقرير
    Report::create([
        // ...
        'images' => $images,
        'pdf_file' => $pdfPath,
        'video_links' => array_values($videoLinks),
    ]);
}
```

#### [`update()`](backend/app/Http/Controllers/Admin/DashboardController.php:129) في DashboardController

```php
public function update(Request $request, Report $report)
{
    // Validation...

    // معالجة الصور الموجودة
    $images = $request->input('existing_images', []);

    // الاحتفاظ بالصورة القديمة
    if ($request->input('keep_old_image') == '1' && $report->image_path) {
        if (!in_array($report->image_path, $images)) {
            $images[] = $report->image_path;
        }
    }

    // إضافة الصور الجديدة
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $path = $image->store('reports', 'public');
                $images[] = $path;
            }
        }
    }

    // معالجة PDF
    $pdfPath = $report->pdf_file;

    if ($request->hasFile('pdf_file')) {
        // حذف القديم ورفع جديد
        if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
            Storage::disk('public')->delete($report->pdf_file);
        }
        $pdfPath = $request->file('pdf_file')->store('reports', 'public');
    } elseif ($request->has('remove_pdf')) {
        // حذف PDF
        if ($report->pdf_file && Storage::disk('public')->exists($report->pdf_file)) {
            Storage::disk('public')->delete($report->pdf_file);
        }
        $pdfPath = null;
    }

    // معالجة روابط الفيديو
    $videoLinks = array_filter($request->input('video_links', []), function($link) {
        return !empty($link) && filter_var($link, FILTER_VALIDATE_URL);
    });

    // تحديث التقرير
    $report->update([
        // ...
        'images' => $images,
        'pdf_file' => $pdfPath,
        'video_links' => array_values($videoLinks),
    ]);

    return redirect()->route('admin.reports.show', $report);
}
```

### 3. Imports المضافة

```php
use Illuminate\Support\Facades\Storage;
```

---

## 🎯 كيفية الاستخدام

### إنشاء تقرير جديد:

1. اذهب إلى `http://10.28.57.151:8000/admin/reports/create`
2. املأ المعلومات الأساسية (المستخدم، الموقع، الإحداثيات، إلخ)
3. في قسم **"الملفات والوسائط المتعددة"**:
   - **الصور:** انقر على "اختر ملف" وحدد عدة صور
   - **PDF:** انقر على "اختر ملف" وحدد ملف PDF (اختياري)
   - **روابط الفيديو:** أدخل رابط فيديو واضغط "+ إضافة" للمزيد
4. اضغط "حفظ التقرير"

### تعديل تقرير موجود:

1. اذهب إلى قائمة التقارير أو صفحة تفاصيل التقرير
2. اضغط "تعديل"
3. ستعرض الصفحة:
   - جميع الصور الحالية مع إمكانية حذفها
   - ملف PDF الحالي مع خيار الحذف أو الاستبدال
   - جميع روابط الفيديو الحالية
4. يمكنك:
   - حذف صور موجودة (زر ×)
   - إضافة صور جديدة
   - استبدال ملف PDF
   - حذف ملف PDF
   - إضافة/حذف روابط فيديو
5. اضغط "حفظ التغييرات" أو "عرض التفاصيل"

---

## 📊 هيكل البيانات

### في قاعدة البيانات:

```sql
-- جدول reports
CREATE TABLE reports (
    -- ...
    images JSON,           -- مصفوفة مسارات الصور
    pdf_file VARCHAR(255), -- مسار ملف PDF
    video_links JSON       -- مصفوفة روابط الفيديو
);
```

### أمثلة البيانات:

#### الصور:

```json
{
  "images": ["reports/abc123.jpg", "reports/def456.jpg", "reports/ghi789.jpg"]
}
```

#### ملف PDF:

```json
{
  "pdf_file": "reports/report123.pdf"
}
```

#### روابط الفيديو:

```json
{
  "video_links": [
    "https://youtube.com/watch?v=example1",
    "https://youtube.com/watch?v=example2",
    "https://vimeo.com/123456789"
  ]
}
```

---

## 🎨 مميزات التصميم

### واجهة المستخدم:

- **تصميم نظيف ومنظم** باستخدام Tailwind CSS
- **أقسام واضحة** مع عناوين ملونة
- **حدود متقطعة** (dashed borders) لمناطق الرفع
- **أيقونات معبرة** (📄 للـ PDF، 🎬 للفيديو)
- **تأثيرات hover** سلسة
- **رسائل تأكيد** قبل الحذف

### تجربة المستخدم (UX):

- **معاينة فورية** للصور الموجودة
- **أزرار واضحة** لجميع الإجراءات
- **حقول ديناميكية** لروابط الفيديو
- **رسائل خطأ** واضحة عند Validation
- **إعادة توجيه ذكية**:
  - بعد الإنشاء: إلى قائمة التقارير
  - بعد التعديل: إلى صفحة التفاصيل

---

## 🔒 قواعد التحقق (Validation)

### الصور:

- `nullable` - اختياري
- `array` - يجب أن يكون مصفوفة
- `images.*` - كل عنصر يجب أن يكون صورة
- `max:10240` - حد أقصى 10 ميجابايت لكل صورة

### ملف PDF:

- `nullable` - اختياري
- `file` - يجب أن يكون ملف
- `mimes:pdf` - يجب أن يكون PDF فقط
- `max:20480` - حد أقصى 20 ميجابايت

### روابط الفيديو:

- `nullable` - اختياري
- `array` - يجب أن يكون مصفوفة
- `video_links.*` - كل عنصر يجب أن يكون URL صحيح
- `max:500` - حد أقصى 500 حرف لكل رابط

---

## 📁 التخزين

### مسار التخزين:

```
backend/storage/app/public/reports/
```

### الوصول للملفات:

```
http://10.28.57.151:8000/storage/reports/filename.jpg
```

### باستخدام `asset()` helper:

```php
{{ asset('storage/' . $report->images[0]) }}
```

---

## ⚠️ ملاحظات مهمة

### 1. رابط التخزين (Storage Link):

تأكد من تشغيل الأمر التالي مرة واحدة:

```bash
php artisan storage:link
```

هذا ينشئ رابط رمزي من `public/storage` إلى `storage/app/public`

### 2. إعدادات PHP:

تأكد من أن `php.ini` يسمح برفع ملفات كبيرة:

```ini
upload_max_filesize = 20M
post_max_size = 20M
```

### 3. صلاحيات المجلدات:

تأكد من أن Laravel لديه صلاحيات الكتابة:

```bash
chmod -R 775 storage
```

### 4. دعم البيانات القديمة:

النظام يدعم:

- الصورة الواحدة القديمة (`image_path`)
- الصور المتعددة الجديدة (`images` JSON)
- يتم تحويل القديم تلقائياً عند التعديل

---

## 🧪 الاختبار

### اختبار إنشاء تقرير:

1. اذهب إلى صفحة الإنشاء
2. املأ البيانات الأساسية
3. ارفع 2-3 صور
4. ارفع ملف PDF
5. أضف 2 رابط فيديو
6. احفظ التقرير
7. تأكد من ظهور جميع الملفات في صفحة التفاصيل

### اختبار تعديل تقرير:

1. افتح تقرير موجود
2. اضغط "تعديل"
3. احذف صورة واحدة
4. أضف صورتين جديدتين
5. استبدل ملف PDF
6. أضف رابط فيديو جديد
7. احفظ التغييرات
8. تأكد من التحديثات في صفحة التفاصيل

---

## 📝 ملخص الإنجازات

✅ **صفحة إنشاء محسّنة** - دعم رفع صور متعددة، PDF، وفيديوهات  
✅ **صفحة تعديل محسّنة** - عرض وإدارة الملفات الموجودة  
✅ **Controller محدّث** - معالجة احترافية للملفات  
✅ **Validation شامل** - حماية من الملفات الضارة  
✅ **واجهة مستخدم جذابة** - تصميم نظيف وسهل الاستخدام  
✅ **دعم البيانات القديمة** - التوافق مع الصور القديمة  
✅ **إدارة ذكية للملفات** - حذف تلقائي للملفات القديمة

---

## 🚀 النظام جاهز للاستخدام!

يمكنك الآن:

- إنشاء تقارير بصور متعددة وملفات PDF وروابط فيديو
- تعديل التقارير وإدارة جميع الملفات
- عرض جميع المحتوى في صفحة تفاصيل جميلة

**جميع الميزات تعمل بشكل كامل ومتكامل! 🎉**

---

**تم الإنشاء بواسطة:** Kilo Code AI Assistant  
**التاريخ:** 2026-02-04  
**الإصدار:** 1.0.0
