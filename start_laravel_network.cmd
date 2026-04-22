@echo off
REM Start Laravel server on all network interfaces
echo Starting Laravel server on 0.0.0.0:8000 (all interfaces)
echo Other devices on the local network can access this server
echo.
cd backend
php artisan serve --host=0.0.0.0 --port=8000
pause

