# TASK: Configure Dynamic API Base URL in Flutter

لدي مشكلة أنني أعمل على Localhost، وعنوان الـ IP الخاص بجهازي (Server IP) يتغير باستمرار، مما يقطع اتصال تطبيق Flutter بالـ Backend.

أريد منك إنشاء ملف إعدادات خاص في Flutter لحل هذه المشكلة بذكاء.

1. أنشئ ملفاً باسم `lib/core/api_config.dart`.
2. داخل الملف، أنشئ كلاس `ApiConfig`.
3. أضف متغيراً ثابتاً `static const String serverIp = "192.168.1.X";` (حيث سأقوم أنا بتغيير هذا الرقم فقط يدوياً عند تغير الشبكة).
4. أضف دالة `static String get baseUrl` تقوم بإرجاع الرابط الكامل:
   - للـ Android Emulator استخدم: `http://10.0.2.2:8000/api`
   - للأجهزة الحقيقية استخدم: `http://$serverIp:8000/api`
   (استخدم `Platform.isAndroid` للتحقق).

5. اشرح لي أين يجب أن أستخدم `ApiConfig.baseUrl` داخل ملفات الـ Services (`auth_service.dart` و `report_service.dart`) التي أنشأتها سابقاً لضمان أن تغيير الـ IP في مكان واحد يعكس التغيير في التطبيق كاملاً.