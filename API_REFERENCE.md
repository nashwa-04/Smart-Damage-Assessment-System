# API Reference - Smart Damage Assessment System

## Base URL

```
http://localhost:8000/api
```

## Authentication

All API endpoints (except `/login`) require authentication using Sanctum Token.

**Header:**

```
Authorization: Bearer {token}
```

---

## Endpoints

### Authentication

#### POST `/api/login`

Login and get API token.

**Request Body (JSON):**

```json
{
  "email": "user@test.com",
  "password": "password"
}
```

**Success Response (200):**

```json
{
  "token": "1|xyzabc123...",
  "user": {
    "id": 2,
    "name": "Field Officer",
    "email": "user@test.com",
    "role": "field_user"
  }
}
```

**Error Response (401):**

```json
{
  "error": "Invalid credentials"
}
```

---

#### POST `/api/logout`

Logout and invalidate token.

**Headers:**

```
Authorization: Bearer {token}
```

**Success Response (200):**

```json
{
  "message": "Logged out successfully"
}
```

---

#### GET `/api/me`

Get authenticated user details.

**Headers:**

```
Authorization: Bearer {token}
```

**Success Response (200):**

```json
{
  "id": 2,
  "name": "Field Officer",
  "email": "user@test.com",
  "role": "field_user",
  "email_verified_at": "2024-01-19T10:00:00.000000Z",
  "created_at": "2024-01-19T10:00:00.000000Z",
  "updated_at": "2024-01-19T10:00:00.000000Z"
}
```

---

### Reports

#### GET `/api/reports`

Get all reports for the authenticated user.

**Headers:**

```
Authorization: Bearer {token}
```

**Success Response (200):**

```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "name": "Field Officer"
      },
      "image_url": "http://localhost:8000/storage/reports/abc123.jpg",
      "location": {
        "raw": "حلب السكري",
        "normalized": "Aleppo, Syria",
        "coordinates": {
          "latitude": 36.2018,
          "longitude": 37.1342
        }
      },
      "description": {
        "raw": "تضرر المبنى بشكل كبير",
        "ai_analysis": "Building shows significant structural damage with collapsed walls and roof damage."
      },
      "damage_assessment": {
        "level": "high",
        "status": "completed"
      },
      "created_at": "2024-01-19 10:30:00",
      "updated_at": "2024-01-19 10:35:00"
    }
  ]
}
```

---

#### POST `/api/reports`

Create a new damage report.

**Headers:**

```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (multipart/form-data):**

```
image: [file] (required, max 10MB)
latitude: 36.2018 (required, number, -90 to 90)
longitude: 37.1342 (required, number, -180 to 180)
raw_location: "حلب السكري" (required, string, max 255 chars)
raw_description: "تضرر المبنى بشكل كبير" (optional, string, max 2000 chars)
```

**Success Response (201):**

```json
{
  "data": {
    "id": 1,
    "status": "pending",
    "message": "Report submitted successfully. Processing will start shortly."
  }
}
```

**Validation Error Response (422):**

```json
{
  "errors": {
    "image": ["The image field is required."],
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."],
    "raw_location": ["The location field is required."]
  }
}
```

**Error Response (401):**

```json
{
  "message": "Unauthenticated."
}
```

---

#### GET `/api/reports/{id}`

Get a specific report by ID.

**Headers:**

```
Authorization: Bearer {token}
```

**URL Parameters:**

- `id` (required) - The report ID

**Success Response (200):**

```json
{
  "data": {
    "id": 1,
    "user": {
      "id": 2,
      "name": "Field Officer"
    },
    "image_url": "http://localhost:8000/storage/reports/abc123.jpg",
    "location": {
      "raw": "حلب السكري",
      "normalized": "Aleppo, Syria",
      "coordinates": {
        "latitude": 36.2018,
        "longitude": 37.1342
      }
    },
    "description": {
      "raw": "تضرر المبنى بشكل كبير",
      "ai_analysis": "Building shows significant structural damage with collapsed walls and roof damage."
    },
    "damage_assessment": {
      "level": "high",
      "status": "completed"
    },
    "created_at": "2024-01-19 10:30:00",
    "updated_at": "2024-01-19 10:35:00"
  }
}
```

**Error Response (404):**

```json
{
  "message": "Report not found"
}
```

**Error Response (403):**

```json
{
  "message": "This action is unauthorized."
}
```

---

## Status Codes

| Code | Description      |
| ---- | ---------------- |
| 200  | Success          |
| 201  | Created          |
| 401  | Unauthorized     |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

---

## Report Status Values

| Status       | Description                                 |
| ------------ | ------------------------------------------- |
| `pending`    | Report submitted, waiting for AI processing |
| `processing` | AI analysis in progress                     |
| `completed`  | AI analysis completed successfully          |
| `rejected`   | AI analysis failed                          |

---

## Damage Level Values

| Level      | Description             |
| ---------- | ----------------------- |
| `low`      | Minor damage            |
| `medium`   | Moderate damage         |
| `high`     | Severe damage           |
| `critical` | Critical/extreme damage |

---

## User Roles

| Role         | Description                                   |
| ------------ | --------------------------------------------- |
| `admin`      | Administrator with full access to admin panel |
| `field_user` | Field officer who submits reports             |

---

## Example Workflow

### 1. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password"}'
```

### 2. Create Report

```bash
curl -X POST http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "image=@/path/to/image.jpg" \
  -F "latitude=36.2018" \
  -F "longitude=37.1342" \
  -F "raw_location=حلب السكري" \
  -F "raw_description=تضرر المبنى"
```

### 3. Get Reports

```bash
curl -X GET http://localhost:8000/api/reports \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 4. Get Specific Report

```bash
curl -X GET http://localhost:8000/api/reports/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 5. Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Error Handling

All error responses follow this format:

```json
{
  "message": "Error message here",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

For validation errors (422):

```json
{
  "errors": {
    "image": ["The image field is required."],
    "latitude": ["The latitude must be between -90 and 90."]
  }
}
```

---

## Rate Limiting

By default, Laravel Sanctum applies rate limiting. If you need to adjust, modify the configuration in [`config/sanctum.php`](config/sanctum.php).

---

## CORS

If you're calling the API from a different domain, ensure CORS is configured in [`config/cors.php`](config/cors.php).

---

## Testing with Postman

1. **Import the collection** (if available)
2. **Set environment variables:**
   - `base_url`: `http://localhost:8000/api`
   - `token`: (copy from login response)
3. **Use the token** in the Authorization header for all protected routes

---

## Notes

- All timestamps are in UTC
- Images are stored in `storage/app/public/reports`
- Image URLs are accessible via `/storage/reports/{filename}`
- AI processing is asynchronous and handled by Queue Workers
- Report status changes: `pending` → `processing` → `completed` or `rejected`
