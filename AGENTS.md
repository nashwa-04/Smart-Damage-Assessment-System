# Developer Guidelines for Smart Damage Assessment System

## Response Protocol - بروتوكول الرد الصارم

### قاعدة الكتابة في ملف message.md
- **القاعدة الصارمة**: يجب على المساعد الرد على جميع الرسائل والاستفسارات عن طريق كتابة ملف `message.md` في المجلد الجذري للمشروع
- المسار: `D:\Smart Damage Assessment System\message.md`
- يجب إعادة كتابة الملف بالكامل عند كل رد جديد
- صيغة الملف: Markdown
- **ممنوع**: الرد المباشر في المحادثة - كل الردود يجب أن تكون في الملف حصراً
- هذه القاعدة صارمة ولا توجد استثناءات

---

## Build/Lint/Test Commands

### Laravel Backend
```bash
# Run all tests
php artisan test

# Run single test
php artisan test --filter testName

# Static analysis (if configured)
vendor/bin/phpstan analyse

# Database operations
php artisan migrate:fresh --seed
php artisan db:seed

# Queue workers for AI processing
php artisan queue:work

# Generate API documentation
php artisan scribe:generate

# Optimization
php artisan optimize
composer dump-autoload
```

### Flutter App
```bash
# Run all tests
flutter test

# Run single test
flutter test test_name_test.dart

# Static analysis
flutter analyze

# Format code
dart format .

# Build
flutter build apk
flutter build ios

# Dependencies
flutter pub get
flutter clean
```

## Project Structure

### Laravel Backend
```
app/
├── Http/
│   ├── Controllers/Api/    # Mobile API endpoints (AuthController, ReportController)
│   ├── Controllers/Admin/  # Admin panel endpoints (DashboardController)
│   ├── Requests/           # Form validation (StoreReportRequest)
│   └── Resources/          # API transformers (ReportResource)
├── Jobs/                   # Async tasks (ProcessReportWithAI)
├── Services/               # Business logic (GeminiService)
└── Models/                 # Eloquent models (User, Report)
```

### Flutter App
```
lib/
├── core/
│   ├── constants/          # API URLs, colors
│   └── network/            # Dio interceptors
├── models/                 # Data models (ReportModel)
├── services/              # API services (AuthService, ReportService)
├── providers/             # State management (AuthProvider, ReportProvider)
├── screens/               # UI screens (LoginScreen, HomeScreen, AddReportScreen)
└── main.dart             # App entry point
```

## Laravel Code Style Guidelines

### General Conventions
- Follow PSR-12 coding standard
- Use Laravel conventions: StudlyCase for classes, camelCase for methods
- Use type hints on all methods and properties
- Use dependency injection in constructors

### Controllers
- Separate API and Admin controllers into subdirectories
- Return JSON using `response()->json()`
- Use HTTP status codes: 201 (created), 200 (ok), 401 (unauthorized), 422 (validation), 500 (server error)
- Use Form Requests for validation logic
- Use API Resources for JSON transformation

### Models
- Define relationships explicitly (belongsTo, hasMany)
- Use casts for enum fields and dates
- Use protected $fillable for mass assignment
- Example: `protected $casts = ['ai_damage_level' => 'string'];`

### AI Processing Jobs
- All AI processing MUST use Laravel Queues (never block user requests)
- Handle API errors with try-catch blocks
- Log errors with proper context: `Log::error('AI processing failed', ['report_id' => $report->id, 'error' => $e->getMessage()])`
- Update report status: pending -> processing -> completed

### Documentation
- Write DocBlocks for all public methods
- Use Scribe annotations for API endpoints:
  - `@bodyParam image file required The damage image`
  - `@response 200 {"id": 1, "status": "pending"}`

## Flutter Code Style Guidelines

### General Conventions
- Use strong typing with null-safety
- Prefer `const` constructors for widgets
- Use Material Design 3 components
- Follow official Flutter style guide

### State Management
- Use Provider pattern for all state
- Create separate providers: AuthProvider, ReportProvider
- Use `ChangeNotifier` for state classes
- Use `Consumer` or `Provider.of` in widgets

### API Integration
- Use `dio` for all HTTP requests
- Configure Dio with interceptors for auth headers
- Use `FormData` for file uploads: `FormData.fromMap({'image': await MultipartFile.fromFile(image.path)})`
- Store tokens in `flutter_secure_storage`
- Handle errors with try-catch and show user-friendly messages

### Configuration
- Centralize API base URLs in `lib/core/api_config.dart`
- Detect platform for emulator vs real device:
  ```dart
  static String get baseUrl {
    if (Platform.isAndroid && kDebugMode) {
      return 'http://10.0.2.2:8000/api';
    }
    return 'http://$serverIp:8000/api';
  }
  ```
- Never hardcode URLs in services

## Naming Conventions

### Laravel
- Classes: PascalCase (ReportController, GeminiService, ProcessReportWithAI)
- Methods: camelCase (getReports, processWithAI, uploadImage)
- Variables: camelCase ($userId, $aiAnalysis, $imagePath)
- Database: snake_case (ai_damage_level, raw_location, user_id)
- Constants: UPPER_SNAKE_CASE (GEMINI_API_KEY)

### Flutter
- Classes: PascalCase (ReportModel, AuthService, LoginScreen)
- Methods: camelCase (uploadReport, login, getLocation)
- Variables: camelCase (userId, aiDamageLevel, imagePath)
- Files: snake_case (auth_service.dart, report_model.dart, login_screen.dart)
- Constants: lowerCamelCase (apiKey, baseUrl, primaryColor)

## Error Handling

### Laravel
- Wrap all external API calls in try-catch (Gemini API)
- Log errors with context using `Log::error()`
- Return appropriate HTTP status codes
- Never expose sensitive data in error messages
- Use Laravel's exception handler for unhandled errors

### Flutter
- Wrap all API calls in try-catch blocks
- Handle DioError types: timeout, connection error, 4xx, 5xx
- Show user-friendly error messages in SnackBars or AlertDialogs
- Log errors to console for debugging
- Handle network timeouts gracefully

## Import Organization

### Laravel
- Group imports: external libraries, internal classes
- One namespace per file
- Order: use statements, namespace declaration

### Flutter
- Import order: dart:, package:, relative imports
- Group related imports together
- Use relative imports for local files
- Remove unused imports

## Key Integration Points

### AI Processing
- All AI requests go through `GeminiService`
- Use Laravel Queues (AnalyzeDamageJob) for async processing
- Prompt format: "Analyze the image and text. 1. Normalize location in Syria. 2. Assess damage 1-10. 3. Extract description. Return JSON."
- Response must be JSON format with: normalized_location, damage_level, analysis_text

### Authentication
- Laravel: Use Sanctum tokens for API authentication
- Flutter: Store token in secure storage, add to Dio headers: `options.headers['Authorization'] = 'Bearer $token'`
- Token required for all API endpoints except /api/login

### File Uploads
- Use multipart/form-data for image uploads
- Store in storage/app/public/reports
- Run `php artisan storage:link` to make files public
- Compress images before upload if needed (Intervention Image)

## Testing Guidelines

### Laravel Tests
- Use Feature tests for API endpoints
- Use Factories for test data
- Test both success and error scenarios
- Mock external API calls (Gemini) in tests

### Flutter Tests
- Use widget tests for UI components
- Use integration tests for user flows
- Mock services for API tests
- Test error handling scenarios

## Environment Configuration

### Laravel (.env)
- GEMINI_API_KEY: Google Gemini API key
- DB_DATABASE: MySQL database name
- QUEUE_CONNECTION: redis or database

### Flutter (lib/core/api_config.dart)
- serverIp: Dynamic IP for local development
- Modify only this single file when network changes

## Code Quality Checklist

Before committing code:
- [ ] Run tests: `php artisan test` and `flutter test`
- [ ] Run linters: `flutter analyze` and `phpstan analyse` (if configured)
- [ ] Format code: `dart format .`
- [ ] Update API docs: `php artisan scribe:generate`
- [ ] Check error handling for all external API calls
- [ ] Ensure AI processing uses queues
- [ ] Verify proper use of Provider pattern in Flutter
