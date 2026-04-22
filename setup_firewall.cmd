@echo off
echo ============================================
echo Smart Damage Assessment - Firewall Setup
echo ============================================
echo.
echo This script will add firewall rules to allow
echo external devices to connect to the backend.
echo.
echo YOU MUST RUN THIS AS ADMINISTRATOR!
echo Right-click this file and select "Run as administrator"
echo.
pause

echo Adding firewall rule for Laravel (port 8000)...
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000

echo Adding firewall rule for Vite (port 5173)...
netsh advfirewall firewall add rule name="Laravel Vite Dev" dir=in action=allow protocol=TCP localport=5173

echo.
echo Verifying rules...
netsh advfirewall firewall show rule name="Laravel Dev Server"
netsh advfirewall firewall show rule name="Laravel Vite Dev"

echo.
echo Done! Press any key to exit.
pause
