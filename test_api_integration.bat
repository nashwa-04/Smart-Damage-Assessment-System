@echo off
REM ============================================================================
REM Smart Damage Assessment System - API Integration Test Script (Windows)
REM ============================================================================
REM This script performs a full integration test of the Laravel API endpoints
REM using cURL commands with automatic token extraction and management.
REM ============================================================================

setlocal enabledelayedexpansion

REM Configuration
set BASE_URL=http://10.28.57.151:8000/api
set EMAIL=user@test.com
set PASSWORD=password
set TEST_IMAGE=damage_test.jpg

REM Global variables
set TOKEN=
set REPORT_ID=
set TOTAL_TESTS=0
set PASSED_TESTS=0
set FAILED_TESTS=0

REM ============================================================================
REM Helper Functions
REM ============================================================================

:print_header
echo.
echo ========================================
echo %~1
echo ========================================
echo.
goto :eof

:print_success
echo [OK] %~1
goto :eof

:print_error
echo [FAIL] %~1
goto :eof

:print_info
echo [INFO] %~1
goto :eof

REM ============================================================================
REM Test Functions
REM ============================================================================

:setup_test_image
call :print_header "1. Setup - Creating Test Image"

if exist "%TEST_IMAGE%" (
    call :print_info "Test image already exists, skipping creation"
    goto :eof
)

REM Create a minimal test image using PowerShell
powershell -Command "& {Add-Type -AssemblyName System.Drawing; $bmp = New-Object System.Drawing.Bitmap 100,100; $graphics = [System.Drawing.Graphics]::FromImage($bmp); $graphics.Clear([System.Drawing.Color]::Blue); $bmp.Save('%TEST_IMAGE%', [System.Drawing.Imaging.ImageFormat]::Jpeg); $graphics.Dispose(); $bmp.Dispose();}"

if exist "%TEST_IMAGE%" (
    call :print_success "Test image created using PowerShell: %TEST_IMAGE%"
) else (
    call :print_error "Failed to create test image"
)
goto :eof

:test_health_check
call :print_header "2. Health Check"

curl -s -w "%%{http_code}" -o response.json "%BASE_URL%/"

set /p HTTP_CODE=<response.json
set BODY=
for /f "delims=" %%a in (response.json) do set BODY=%%a

if "%HTTP_CODE%"=="200" (
    call :print_success "Health check passed (HTTP %HTTP_CODE%)"
    type response.json
) else (
    call :print_error "Health check failed (HTTP %HTTP_CODE%)"
)
goto :eof

:test_login
call :print_header "3. Login"

curl -s -w "%%{http_code}" -o response.json -X POST "%BASE_URL%/login" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"%EMAIL%\",\"password\":\"%PASSWORD%\"}"

set /p HTTP_CODE=<response.json

REM Extract token using PowerShell
for /f "tokens=*" %%a in ('powershell -Command "$content = Get-Content response.json -Raw; if ($content -match '\"token\"[\":\"]+([^\"]+)') { $matches[1] }"') do set TOKEN=%%a

if not "%TOKEN%"=="" (
    call :print_success "Login successful (HTTP %HTTP_CODE%)"
    call :print_info "Token extracted: %TOKEN:~0,20%..."
    type response.json
) else (
    call :print_error "Login failed or no token found (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_get_user
call :print_header "4. Get User Data"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

curl -s -w "%%{http_code}" -o response.json -X GET "%BASE_URL%/user" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json"

set /p HTTP_CODE=<response.json

if "%HTTP_CODE%"=="200" (
    call :print_success "Get user data successful (HTTP %HTTP_CODE%)"
    type response.json
) else (
    call :print_error "Get user data failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_create_report
call :print_header "5. Create Report"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

if not exist "%TEST_IMAGE%" (
    call :print_error "Test image not found: %TEST_IMAGE%"
    goto :eof
)

REM Realistic Arabic data for damage report
set LATITUDE=33.5138
set LONGITUDE=36.2765
set RAW_LOCATION=دمشق - منطقة المزة - شارع الفيلات الغربية - بناء رقم 14
set RAW_DESCRIPTION=يوجد تصدع كبير في الجدار الخارجي الشمالي للمبنى يمتد من الطابق الأول حتى الثالث، مع تساقط أجزاء من الشرفة. يرجى التقييم العاجل لاحتمالية السقوط.

call :print_info "Sending report with Arabic data:"
echo   Location: %RAW_LOCATION%
echo   Coordinates: %LATITUDE%, %LONGITUDE%
echo   Description: %RAW_DESCRIPTION%

curl -s -w "%%{http_code}" -o response.json -X POST "%BASE_URL%/reports" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json" ^
  -F "image=@%TEST_IMAGE%" ^
  -F "latitude=%LATITUDE%" ^
  -F "longitude=%LONGITUDE%" ^
  -F "raw_location=%RAW_LOCATION%" ^
  -F "raw_description=%RAW_DESCRIPTION%"

set /p HTTP_CODE=<response.json

REM Extract report ID using PowerShell
for /f "tokens=*" %%a in ('powershell -Command "$content = Get-Content response.json -Raw; if ($content -match '\"id\":(\d+)') { $matches[1] }"') do set REPORT_ID=%%a

if "%HTTP_CODE%"=="201" (
    call :print_success "Report created successfully (HTTP %HTTP_CODE%)"
    call :print_info "Report ID: %REPORT_ID%"
    type response.json
) else if "%HTTP_CODE%"=="200" (
    call :print_success "Report created successfully (HTTP %HTTP_CODE%)"
    call :print_info "Report ID: %REPORT_ID%"
    type response.json
) else (
    call :print_error "Create report failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_list_reports
call :print_header "6. List Reports"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

curl -s -w "%%{http_code}" -o response.json -X GET "%BASE_URL%/reports" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json"

set /p HTTP_CODE=<response.json

if "%HTTP_CODE%"=="200" (
    call :print_success "List reports successful (HTTP %HTTP_CODE%)"
    type response.json
) else (
    call :print_error "List reports failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_show_report
call :print_header "7. Show Report Details"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

if "%REPORT_ID%"=="" (
    call :print_error "No report ID available"
    goto :eof
)

curl -s -w "%%{http_code}" -o response.json -X GET "%BASE_URL%/reports/%REPORT_ID%" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json"

set /p HTTP_CODE=<response.json

if "%HTTP_CODE%"=="200" (
    call :print_success "Show report successful (HTTP %HTTP_CODE%)"
    type response.json
) else (
    call :print_error "Show report failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_delete_report
call :print_header "8. Delete Report"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

if "%REPORT_ID%"=="" (
    call :print_error "No report ID available"
    goto :eof
)

curl -s -w "%%{http_code}" -o response.json -X DELETE "%BASE_URL%/reports/%REPORT_ID%" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json"

set /p HTTP_CODE=<response.json

if "%HTTP_CODE%"=="200" (
    call :print_success "Delete report successful (HTTP %HTTP_CODE%)"
    type response.json
) else if "%HTTP_CODE%"=="204" (
    call :print_success "Delete report successful (HTTP %HTTP_CODE%)"
) else (
    call :print_error "Delete report failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

:test_logout
call :print_header "9. Logout"

if "%TOKEN%"=="" (
    call :print_error "No token available"
    goto :eof
)

curl -s -w "%%{http_code}" -o response.json -X POST "%BASE_URL%/logout" ^
  -H "Authorization: Bearer %TOKEN%" ^
  -H "Accept: application/json"

set /p HTTP_CODE=<response.json

if "%HTTP_CODE%"=="200" (
    call :print_success "Logout successful (HTTP %HTTP_CODE%)"
    type response.json
    set TOKEN=
) else (
    call :print_error "Logout failed (HTTP %HTTP_CODE%)"
    type response.json
)
goto :eof

REM ============================================================================
REM Main Execution
REM ============================================================================

:main
call :print_header "Smart Damage Assessment System - API Integration Test"
echo Base URL: %BASE_URL%
echo Test User: %EMAIL%
echo Timestamp: %date% %time%

REM Run tests
call :setup_test_image
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_health_check
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_login
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_get_user
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_create_report
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_list_reports
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_show_report
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_delete_report
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

call :test_logout
set /a TOTAL_TESTS+=1
if errorlevel 1 (
    set /a FAILED_TESTS+=1
) else (
    set /a PASSED_TESTS+=1
)

REM Print summary
call :print_header "Test Execution Summary"
echo Total Tests: %TOTAL_TESTS%
echo Passed: %PASSED_TESTS%
echo Failed: %FAILED_TESTS%

REM Cleanup
if exist "%TEST_IMAGE%" (
    call :print_info "Cleaning up test image..."
    del "%TEST_IMAGE%"
)

if exist response.json (
    del response.json
)

if %FAILED_TESTS%==0 (
    echo.
    echo [OK] All tests passed successfully!
    exit /b 0
) else (
    echo.
    echo [FAIL] Some tests failed. Please check the output above.
    exit /b 1
)
