# إصلاح مشكلة الفلترة - حقول عربية عند اللغة الإنجليزية

## المشكلة
عند تبديل اللغة إلى الإنجليزية في صفحة `/admin/reports`، كانت عناصر `<select>` (القوائم المنسدلة) في قسم الفلترة تبقى بالنص العربي.

## السبب
دالة `applyLanguage` في `admin/layouts/app.blade.php:121` كانت تستخدم `el.textContent` لتحديث جميع العناصر بما فيها `<option>`، لكن المتصفحات لا تعيد رسم النص المعروض في زر الـ `<select>` (العنصر المحدد) بعد تحديث `textContent` للـ `<option>` مباشرة.

## الحل المطبّق
تم تعديل دالة `applyLanguage` في الملف `backend/resources/views/admin/layouts/app.blade.php`:

1. **استبعاد `<option>` من التحديث العام** - لتجنب التحديث المزدوج
2. **معالجة `<select>` بشكل منفصل** - تحديث نصوص جميع `<option>` داخل كل `<select>` ثم إعادة تعيين القيمة المحددة لإجبار المتصفح على إعادة الرسم:
   ```javascript
   document.querySelectorAll('select').forEach(select => {
       select.querySelectorAll('option[data-' + lang + ']').forEach(option => {
           option.textContent = option.getAttribute('data-' + lang);
       });
       const currentVal = select.value;
       select.value = '';
       select.value = currentVal; // إجبار إعادة الرسم
   });
   ```

## الملف المعدّل
- `backend/resources/views/admin/layouts/app.blade.php` - السطر 121-145
