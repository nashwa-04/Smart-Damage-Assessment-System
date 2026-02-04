# API Integration Test Suite - Smart Damage Assessment System

## Overview

This test suite provides comprehensive integration testing for the Smart Damage Assessment System API using automated cURL commands. The tests validate all critical endpoints with a focus on report creation using realistic Arabic data.

## Files Included

| File                                                               | Description                       | Platform      |
| ------------------------------------------------------------------ | --------------------------------- | ------------- |
| [`test_api_integration.sh`](test_api_integration.sh)               | Bash script for Linux/macOS/WSL   | Unix-like     |
| [`test_api_integration.bat`](test_api_integration.bat)             | Batch script for Windows          | Windows       |
| [`API_INTEGRATION_TEST_REPORT.md`](API_INTEGRATION_TEST_REPORT.md) | Detailed test report with results | Documentation |
| [`TEST_SUITE_README.md`](TEST_SUITE_README.md)                     | This file                         | Documentation |

## Quick Start

### Windows Users

```cmd
REM Run the Windows batch script
test_api_integration.bat
```

### Linux/macOS/WSL Users

```bash
# Make the script executable
chmod +x test_api_integration.sh

# Run the test
./test_api_integration.sh
```

## Prerequisites

### Required Tools

- **curl** - Command-line tool for transferring data with URLs
  - Windows: Usually pre-installed with Windows 10/11
  - Linux/macOS: Usually pre-installed
  - Install: `sudo apt install curl` (Linux)

### Optional Tools (Recommended)

- **jq** - Command-line JSON processor
  - Windows: Download from https://stedolan.github.io/jq/download/
  - Linux: `sudo apt install jq`
  - macOS: `brew install jq`

- **ImageMagick** - Image manipulation toolkit
  - Windows: Download from https://imagemagick.org/script/download.php#windows
  - Linux: `sudo apt install imagemagick`
  - macOS: `brew install imagemagick`

## Configuration

### Server Configuration

The test scripts are configured to use your server IP:

```bash
# In both .sh and .bat files
BASE_URL="http://10.28.57.151:8000/api"
```

To change the server URL, edit this line in the script file.

### Test User Credentials

```bash
EMAIL="user@test.com"
PASSWORD="password"
```

These credentials should exist in your database (created by [`UserSeeder.php`](backend/database/seeders/UserSeeder.php)).

## Test Coverage

The test suite covers the following endpoints:

| #   | Test          | Endpoint        | Method | Purpose                    |
| --- | ------------- | --------------- | ------ | -------------------------- |
| 1   | Setup         | N/A             | N/A    | Create test image          |
| 2   | Health Check  | `/`             | GET    | Verify API is running      |
| 3   | Login         | `/login`        | POST   | Authenticate and get token |
| 4   | Get User      | `/user`         | GET    | Verify token validity      |
| 5   | Create Report | `/reports`      | POST   | Upload damage report       |
| 6   | List Reports  | `/reports`      | GET    | Retrieve all reports       |
| 7   | Show Report   | `/reports/{id}` | GET    | Get specific report        |
| 8   | Delete Report | `/reports/{id}` | DELETE | Remove test report         |
| 9   | Logout        | `/logout`       | POST   | Terminate session          |

## Test Data

### Arabic Test Data

The test uses realistic Arabic data for the damage report:

**Location:**

```
دمشق - منطقة المزة - شارع الفيلات الغربية - بناء رقم 14
```

Translation: Damascus - Mazzeh District - Western Villas Street - Building No. 14

**Description:**

```
يوجد تصدع كبير في الجدار الخارجي الشمالي للمبنى يمتد من الطابق الأول حتى الثالث، مع تساقط أجزاء من الشرفة. يرجى التقييم العاجل لاحتمالية السقوط.
```

Translation: There is a large crack in the northern exterior wall of the building extending from the first floor to the third floor, with parts of the balcony falling off. Please evaluate urgently for the possibility of collapse.

**Coordinates:**

- Latitude: 33.5138 (Damascus, Syria)
- Longitude: 36.2765

## Understanding the Output

### Success Example

```
========================================
5. Create Report
========================================

[INFO] Sending report with Arabic data:
  Location: دمشق - منطقة المزة - شارع الفيلات الغربية - بناء رقم 14
  Coordinates: 33.5138, 36.2765
  Description: يوجد تصدع كبير في الجدار الخارجي الشمالي للمبنى يمتد من الطابق الأول حتى الثالث، مع تساقط أجزاء من الشرفة. يرجى التقييم العاجل لاحتمالية السقوط.

[OK] Report created successfully (HTTP 201)
[INFO] Report ID: 1
{"id":1,"user_id":1,"latitude":33.5138,"longitude":36.2765,...}
```

### Failure Example

```
========================================
3. Login
========================================

[FAIL] Login failed or no token found (HTTP 401)
{"message":"Invalid credentials"}
```

## Troubleshooting

### Common Issues

#### 1. Connection Refused

**Error:** `curl: (7) Failed to connect to 10.28.57.151 port 8000`

**Solution:**

- Ensure Laravel server is running: `php artisan serve --host=10.28.57.151 --port=8000`
- Check firewall settings
- Verify the IP address is correct

#### 2. Authentication Failed

**Error:** `{"message":"Invalid credentials"}`

**Solution:**

- Verify user exists in database: `php artisan tinker` then `User::all()`
- Run database seeder: `php artisan db:seed --class=UserSeeder`
- Check email and password in script match database

#### 3. Image Upload Failed

**Error:** `The image field is required`

**Solution:**

- Ensure test image is created successfully
- Check file permissions
- Verify multipart/form-data is being sent

#### 4. Arabic Text Garbled

**Error:** Arabic characters appear as `????` or garbled text

**Solution:**

- Verify database charset is UTF-8: Check `.env` file for `DB_CHARSET=utf8mb4`
- Ensure Laravel app locale is set correctly in `config/app.php`
- Check terminal encoding supports UTF-8

#### 5. Token Extraction Failed

**Error:** `[FAIL] Login succeeded but no token found in response`

**Solution:**

- Install `jq` for better JSON parsing
- Check API response format matches expected structure
- Verify Sanctum is properly configured

## Running Individual Tests

You can run individual cURL commands manually for debugging:

### Health Check

```bash
curl -X GET "http://10.28.57.151:8000/api/" \
  -H "Accept: application/json"
```

### Login

```bash
curl -X POST "http://10.28.57.151:8000/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@test.com","password":"password"}'
```

### Create Report (with token)

```bash
curl -X POST "http://10.28.57.151:8000/api/reports" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json" \
  -F "image=@damage_test.jpg" \
  -F "latitude=33.5138" \
  -F "longitude=36.2765" \
  -F "raw_location=دمشق - منطقة المزة" \
  -F "raw_description=تقرير اختبار"
```

## Customization

### Changing Test Data

Edit the test data in the script:

```bash
# In test_create_report function
LATITUDE="34.5678"
LONGITUDE="35.6789"
RAW_LOCATION="Your custom location"
RAW_DESCRIPTION="Your custom description"
```

### Adding New Tests

Add a new test function following this pattern:

```bash
test_new_endpoint() {
    print_header "10. New Test"

    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi

    RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL/new-endpoint" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")

    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')

    if [ "$HTTP_CODE" = "200" ]; then
        print_success "Test passed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 0
    else
        print_error "Test failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}
```

Then call it in the `main()` function.

## Continuous Integration

### GitHub Actions Example

```yaml
name: API Integration Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: smart_damage_assessment
        ports:
          - 3306:3306

    steps:
      - uses: actions/checkout@v2

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.2"
          extensions: mbstring, pdo, pdo_mysql

      - name: Install Dependencies
        run: |
          cd backend
          composer install
          cp .env.example .env
          php artisan key:generate

      - name: Run Migrations & Seed
        run: |
          cd backend
          php artisan migrate:fresh --seed

      - name: Start Laravel Server
        run: |
          cd backend
          php artisan serve --host=127.0.0.1 --port=8000 &
          sleep 5

      - name: Run Integration Tests
        run: |
          chmod +x test_api_integration.sh
          ./test_api_integration.sh
```

## Performance Testing

To test API performance, modify the script to measure response times:

```bash
test_with_timing() {
    START=$(date +%s%N)

    # Run your curl command here
    RESPONSE=$(curl -s -w "\n%{http_code}" ...)

    END=$(date +%s%N)
    DURATION=$(( (END - START) / 1000000 ))

    echo "Response time: ${DURATION}ms"
}
```

## Security Considerations

### Best Practices

1. **Never commit credentials**: Use environment variables
2. **Use HTTPS in production**: Change `BASE_URL` to use HTTPS
3. **Rotate test tokens**: Don't reuse tokens across test runs
4. **Clean up test data**: Always delete test reports after testing
5. **Limit test data**: Use minimal data to reduce exposure

### Environment Variables

Instead of hardcoding credentials, use environment variables:

```bash
# In .env file (not committed to git)
API_BASE_URL=http://10.28.57.151:8000/api
TEST_EMAIL=user@test.com
TEST_PASSWORD=password

# In script
source .env 2>/dev/null || true
BASE_URL=${API_BASE_URL:-http://localhost:8000/api}
EMAIL=${TEST_EMAIL:-user@test.com}
PASSWORD=${TEST_PASSWORD:-password}
```

## Additional Resources

- [Laravel API Documentation](API_DOCUMENTATION.md)
- [Backend Setup Guide](backend/SETUP.md)
- [Agent Guidelines](AGENTS.md)
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [cURL Manual](https://curl.se/docs/manpage.html)

## Support

For issues or questions:

1. Check the [Troubleshooting](#troubleshooting) section
2. Review the [API Documentation](API_DOCUMENTATION.md)
3. Check Laravel logs: `backend/storage/logs/laravel.log`
4. Verify database state: `php artisan tinker`

## License

This test suite is part of the Smart Damage Assessment System project.

---

**Last Updated:** 2026-01-27  
**Version:** 1.0.0  
**Test Environment:** Development (Local Server)  
**Server IP:** 10.28.57.151:8000
