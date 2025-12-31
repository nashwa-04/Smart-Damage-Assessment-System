# TASK: Create Complete Flutter App for Field Reporting

أريد كود مشروع Flutter كامل (المجلد `lib` فقط) لتوثيق الأضرار.

### 1. Dependencies (`pubspec.yaml`)
حدد المكتبات المطلوبة: `dio`, `provider`, `geolocator`, `image_picker`, `flutter_secure_storage`.

### 2. File Structure
أريد الكود للملفات التالية بالتفصيل:

**A. `lib/main.dart`:**
- إعداد `MultiProvider` (AuthProvider, ReportProvider).
- التوجيه الأساسي (LoginScreen إذا لم يكن مسجلاً، و HomeScreen إذا كان مسجلاً).

**B. `lib/services/auth_service.dart`:**
- دالة `login(email, password)` تتصل بـ Laravel API وتحفظ التوكن.

**C. `lib/services/report_service.dart`:**
- دالة `uploadReport(File image, String location, String description, double lat, double long)` تستخدم `FormData` مع `Dio`.

**D. `lib/screens/login_screen.dart`:**
- واجهة بسيطة لإدخال الإيميل وكلمة السر.

**E. `lib/screens/home_screen.dart`:**
- تعرض قائمة التقارير السابقة وحالتها (Pending/Processed) باستخدام `ListView`.
- زر عائم (FAB) ينقل لصفحة إضافة تقرير.

**F. `lib/screens/add_report_screen.dart`:**
- أهم واجهة.
- زر لالتقاط صورة (يعرض الصورة المصغرة).
- زر "Get Location" يجلب الإحداثيات ويخزنها.
- حقل نصي لاسم المنطقة وحقل للوصف.
- زر "Submit" يرسل البيانات ويظهر مؤشر تحميل.

### ملاحظة هامة:
اجعل تصميم الواجهات نظيفاً (Clean UI) باستخدام Material Design 3.