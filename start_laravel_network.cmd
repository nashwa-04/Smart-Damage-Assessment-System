@echo off
REM Start Laravel server on network IP
echo Starting Laravel server on network IP: 10.28.57.151:8000
echo Other devices on the local network can access this server
echo.
cd backend
php artisan serve --host=10.28.57.151 --port=8000
pause
