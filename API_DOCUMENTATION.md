# Smart Damage Assessment System - API Documentation

## Base URL

```
http://localhost:8000/api
```

## Authentication

All protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Endpoints

### 1. Health Check

Check if the API is running.

**Endpoint:** `GET /api`

**Authentication:** Not required

**Request:**

```bash
curl -X GET http://localhost:8000/api
```

**Response (200 OK):**

```json
{
  "status": "ok",
  "message": "API is running",
  "version": "1.0.0",
  "timestamp": "2026-01-19T13:11:31+00:00"
}
```

---

### 2. Login

Authenticate user and get access token.

**Endpoint:** `POST /api/login`

**Authentication:** Not required

**Request Body:**

```json
{
  "email": "user@test.com",
  "password": "password"
}
```

**Request Example:**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@test.com",
    "password": "password"
  }'
```

**Response (200 OK):**

```json
{
  "token": "15|CPmOBrrF3uas2LHIE8fROB4PhWr3HrBwBuDN9PRX27162509",
  "user": {
    "id": 2,
    "name": "Field Officer",
    "email": "user@test.com",
    "role": "field_user"
  }
}
```

**Response (401 Unauthorized):**

```json
{
  "error": "Invalid credentials"
}
```

**Response (422 Validation Error):**

```json
{
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

---

### 3. Logout

Invalidate the current user's token.

**Endpoint:** `POST /api/logout`

**Authentication:** Required

**Request:**

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Response (200 OK):**

```json
{
  "message": "Logged out successfully"
}
```

---

### 4. Get Current User

Get information about the authenticated user.

**Endpoint:** `GET /api/me`

**Authentication:** Required

**Request:**

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Response (200 OK):**

```json
{
  "id": 2,
  "name": "Field Officer",
  "email": "user@test.com",
  "role": "field_user",
  "api_token": null,
  "created_at": "2026-01-19T11:19:30.000000Z",
  "updated_at": "2026-01-19T11:19:30.000000Z"
}
```

---

### 5. Get User Reports

Get all reports created by the authenticated user.

**Endpoint:** `GET /api/reports`

**Authentication:** Required

**Request:**

```bash
curl -X GET http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Response (200 OK):**

```json
[
  {
    "id": 54,
    "user": {
      "id": 2,
      "name": "Field Officer"
    },
    "image_url": "http://localhost:8000/storage/reports/32.jpg",
    "location": {
      "raw": "القنيطرة - حي الأمل",
      "normalized": "القنيطرة",
      "coordinates": {
        "latitude": 33.1162,
        "longitude": 35.8268
      }
    },
    "description": {
      "raw": "أضرار في البنية التحتية للكهرباء",
      "ai_analysis": "أضرار كارثية في المباني، غير صالحة للسكن"
    },
    "damage_assessment": {
      "level": "critical",
      "status": "pending"
    },
    "created_at": "2026-01-18 16:08:42",
    "updated_at": "2026-01-19 12:36:42"
  }
]
```

---

### 6. Create Report

Submit a new damage report.

**Endpoint:** `POST /api/reports`

**Authentication:** Required

**Content-Type:** `multipart/form-data`

**Request Parameters:**

- `image` (file, required) - Damage image (max 10MB)
- `latitude` (number, required) - GPS latitude coordinate
- `longitude` (number, required) - GPS longitude coordinate
- `raw_location` (string, required) - Location name as entered by user
- `raw_description` (string, optional) - Additional description (max 2000 characters)

**Request Example (Flutter):**

```dart
var formData = FormData.fromMap({
  'image': await MultipartFile.fromFile(image.path),
  'latitude': '33.1162',
  'longitude': '35.8268',
  'raw_location': 'القنيطرة - حي الأمل',
  'raw_description': 'أضرار في البنية التحتية للكهرباء',
});

var response = await dio.post(
  '/api/reports',
  data: formData,
  options: Options(
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'multipart/form-data',
    },
  ),
);
```

**Request Example (curl):**

```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "image=@/path/to/image.jpg" \
  -F "latitude=33.1162" \
  -F "longitude=35.8268" \
  -F "raw_location=القنيطرة - حي الأمل" \
  -F "raw_description=أضرار في البنية التحتية للكهرباء"
```

**Response (201 Created):**

```json
{
  "data": {
    "id": 57,
    "status": "pending",
    "message": "Report submitted successfully. Processing will start shortly."
  }
}
```

**Response (422 Validation Error):**

```json
{
  "errors": {
    "image": ["The image field is required."],
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."],
    "raw_location": ["The raw_location field is required."]
  }
}
```

---

### 7. Get Report Details

Get details of a specific report created by the authenticated user.

**Endpoint:** `GET /api/reports/{id}`

**Authentication:** Required

**Request:**

```bash
curl -X GET http://localhost:8000/api/reports/54 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Response (200 OK):**

```json
{
  "id": 54,
  "user": {
    "id": 2,
    "name": "Field Officer"
  },
  "image_url": "http://localhost:8000/storage/reports/32.jpg",
  "location": {
    "raw": "القنيطرة - حي الأمل",
    "normalized": "القنيطرة",
    "coordinates": {
      "latitude": 33.1162,
      "longitude": 35.8268
    }
  },
  "description": {
    "raw": "أضرار في البنية التحتية للكهرباء",
    "ai_analysis": "أضرار كارثية في المباني، غير صالحة للسكن"
  },
  "damage_assessment": {
    "level": "critical",
    "status": "pending"
  },
  "created_at": "2026-01-18 16:08:42",
  "updated_at": "2026-01-19 12:36:42"
}
```

**Response (404 Not Found):**

```json
{
  "message": "Report not found"
}
```

---

### 8. Delete Report

Delete a specific report created by the authenticated user.

**Endpoint:** `DELETE /api/reports/{id}`

**Authentication:** Required

**Request:**

```bash
curl -X DELETE http://localhost:8000/api/reports/54 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

**Response (200 OK):**

```json
{
  "message": "Report deleted successfully"
}
```

**Response (404 Not Found):**

```json
{
  "message": "Report not found"
}
```

---

## Data Models

### Report Status

- `pending` - Report submitted, waiting for AI processing
- `processing` - AI analysis in progress
- `completed` - AI analysis completed
- `rejected` - Report rejected (invalid or duplicate)

### Damage Level

- `low` - Minor damage (1-3)
- `medium` - Moderate damage (4-6)
- `high` - Severe damage (7-8)
- `critical` - Critical damage (9-10)

### User Role

- `admin` - Administrator with full access
- `field_user` - Field officer who creates and manages reports

---

## Error Codes

| Status Code | Description                             |
| ----------- | --------------------------------------- |
| 200         | Success                                 |
| 201         | Created successfully                    |
| 401         | Unauthorized - Invalid or missing token |
| 404         | Not found - Resource doesn't exist      |
| 422         | Validation error - Invalid request data |
| 500         | Internal server error                   |

---

## Test Credentials

For testing purposes, use the following credentials:

**Field Officer:**

- Email: `user@test.com`
- Password: `password`
- Role: `field_user`

**Admin:**

- Email: `admin@test.com`
- Password: `password`
- Role: `admin`

---

## Flutter Integration Example

### API Configuration

```dart
class ApiConfig {
  static String get baseUrl {
    if (Platform.isAndroid && kDebugMode) {
      return 'http://10.0.2.2:8000/api';
    }
    return 'http://YOUR_SERVER_IP:8000/api';
  }
}
```

### Login Example

```dart
Future<String> login(String email, String password) async {
  final response = await dio.post(
    '${ApiConfig.baseUrl}/login',
    data: {
      'email': email,
      'password': password,
    },
  );

  final token = response.data['token'];

  // Store token securely
  await storage.write(key: 'auth_token', value: token);

  return token;
}
```

### Get Reports Example

```dart
Future<List<Report>> getReports(String token) async {
  final response = await dio.get(
    '${ApiConfig.baseUrl}/reports',
    options: Options(
      headers: {
        'Authorization': 'Bearer $token',
      },
    ),
  );

  return (response.data as List)
      .map((json) => Report.fromJson(json))
      .toList();
}
```

### Create Report Example

```dart
Future<Report> createReport(
  String token,
  File image,
  double latitude,
  double longitude,
  String rawLocation,
  String rawDescription,
) async {
  final formData = FormData.fromMap({
    'image': await MultipartFile.fromFile(image.path),
    'latitude': latitude.toString(),
    'longitude': longitude.toString(),
    'raw_location': rawLocation,
    'raw_description': rawDescription,
  });

  final response = await dio.post(
    '${ApiConfig.baseUrl}/reports',
    data: formData,
    options: Options(
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'multipart/form-data',
      },
    ),
  );

  return Report.fromJson(response.data['data']);
}
```

### Delete Report Example

```dart
Future<void> deleteReport(String token, int reportId) async {
  await dio.delete(
    '${ApiConfig.baseUrl}/reports/$reportId',
    options: Options(
      headers: {
        'Authorization': 'Bearer $token',
      },
    ),
  );
}
```

---

## Notes

1. **Authentication Token:** The token received from login must be included in the `Authorization` header for all protected endpoints.
2. **User Reports:** Users can only see and manage reports they created.
3. **Image Upload:** Images are stored in `storage/app/public/reports/` and accessible via `http://localhost:8000/storage/reports/filename.jpg`.
4. **AI Processing:** Report analysis is processed asynchronously via Laravel Queues. Status updates from `pending` → `processing` → `completed`.
5. **Rate Limiting:** Consider implementing rate limiting for production use.
6. **HTTPS:** Use HTTPS in production for secure communication.

---

## Version History

- **v1.0.0** (2026-01-19)
  - Initial API release
  - Authentication endpoints
  - Report management (CRUD)
  - Health check endpoint
  - Delete report functionality

---

## Support

For issues or questions, please contact the development team.
