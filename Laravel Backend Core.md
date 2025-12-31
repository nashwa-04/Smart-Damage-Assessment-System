# TASK: Create Full Laravel Backend for Smart Damage Assessment System

أريد منك كتابة كود كامل لمشروع Laravel (الإصدار الأخير) يعمل كـ Backend لنظام توثيق الأضرار. النظام يتكون من API لتطبيق موبايل ولوحة تحكم للمدير.

### 1. Setup & Dependencies
افترض أنني قمت بتنفيذ الأمر: `composer create-project laravel/laravel damage-assessment-backend`.
زودني بأوامر لتثبيت المكتبات التالية:
- `laravel/breeze` (للوحة تحكم الأدمن Web Auth).
- `laravel/sanctum` (لـ API Token Auth).
- `guzzlehttp/guzzle` (للاتصال بـ Gemini API).

### 2. Database Schema & Seeders
أنشئ ملف Migration واحد شامل للجداول التالية:
- `users`: (id, name, email, password, role [admin/field_user], api_token).
- `reports`:
    - id, user_id (FK)
    - image_path (string)
    - latitude, longitude (decimal 10,8)
    - raw_location (string input by user)
    - raw_description (text input by user)
    - ai_location (string nullable)
    - ai_damage_level (enum: low, medium, high, critical nullable)
    - ai_analysis (text nullable)
    - status (enum: pending, processed)
    - timestamps
- أنشئ `UserSeeder` يضيف حساب Admin (admin@test.com) وحساب Field User (user@test.com).

### 3. Models
أنشئ ملف `app/Models/Report.php` مع علاقة `belongsTo` للمستخدم، و `Casts` للحقول اللازمة.

### 4. Logic & Controllers
أريد الملفات التالية بكود كامل:

**A. API Controller (`app/Http/Controllers/Api/ReportController.php`):**
- `store()`: لاستلام البيانات من الفلاتر (صورة + نص + موقع)، حفظ الصورة في `storage/app/public/reports`، إنشاء سجل في الداتابيس بحالة 'pending'، وإطلاق Job للمعالجة.
- `index()`: لإرجاع تقارير المستخدم الحالي فقط.

**B. Admin Controller (`app/Http/Controllers/Admin/DashboardController.php`):**
- `index()`: يعيد View فيها إحصائيات (عدد التقارير، توزيع الضرر).
- `map()`: يعيد View تعرض الخريطة.

**C. AI Job (`app/Jobs/AnalyzeDamageJob.php`):**
- هذا الـ Job يستقبل `report_id`.
- يقوم بإرسال الصورة والنص إلى Google Gemini API (اكتب كود وهمي للطلب يفترض وجود API KEY في `.env`).
- يحدث سجل التقرير بالبيانات العائدة من الـ AI ويغير الحالة إلى `processed`.

### 5. Routes
- `routes/api.php`: مسارات محمية بـ Sanctum لرفع وجلب التقارير.
- `routes/web.php`: مسارات محمية بـ Breeze للوحة تحكم الأدمن.

### 6. Views (Blade)
زودني بكود HTML/Blade مبسط لملف `resources/views/admin/dashboard.blade.php` يحتوي على جدول يعرض التقارير وكود JavaScript بسيط لاستخدام `Chart.js` لعرض رسم بياني لتوزيع الأضرار.