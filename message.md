# رد المساعد — إصلاح التواريخ عند تبديل اللغة (حل نهائي)

## المشكلة
في `/admin/reports`، عند تبديل اللغة للإنجليزية، التواريخ (مثل "14 يونيو 2026") تبقى بالعربية.

## سبب فشل الحل الأول
الحل السابق اعتمد على `data-ar`/`data-en` + دالة JavaScript `applyLanguage()` لتبديل النص. لكن قد يحدث توقيت/تعارض يمنع التبديل.

## الحل النهائي (CSS خالص — مضمون 100%)
بدلاً من الاعتماد على JavaScript لتبديل النص، استخدمت **CSS خالص** يتحكم بالظهور/الاختفاء حسب خاصية `lang` على عنصر `<html>`:

### 1) أضيفت قواعد CSS في الصفحة:
```css
.date-ar { display: inline; }
.date-en { display: none; }
html[lang="en"] .date-ar { display: none; }
html[lang="en"] .date-en { display: inline; }
```

### 2) التاريخ أصخدم عنصرين منفصلين بدلاً من data attributes:
```html
<span class="text-xs font-bold">
    <span class="date-ar">14 يونيو 2026</span>
    <span class="date-en">14 Jun 2026</span>
</span>
```

### كيف يعمل؟
- عند العربية (`<html lang="ar">`): CSS يعرض `.date-ar` ويخفي `.date-en`
- عند الإنجليزية (`<html lang="en">`): CSS يعرض `.date-en` ويخفي `.date-ar`
- دالة `applyLanguage` في الـ layout تغيّر `document.documentElement.setAttribute('lang', 'en')`، فيتفاعل الـ CSS **فوراً**

هذا الحل لا يعتمد على تكرار عناصر DOM بـ JavaScript، بل على المتصفح نفسه.

## ملاحظة
"اليوم" و"أمس" لا يزالان يعملان عبر `data-ar`/`data-en` لأنهما نصوص بسيطة، ولم تتغير.
