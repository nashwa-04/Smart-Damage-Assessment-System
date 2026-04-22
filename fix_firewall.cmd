@echo off
echo ============================================
echo  Smart Damage Assessment - Firewall Fix
echo  This script requires Administrator privileges
echo ============================================
echo.
echo Adding firewall rule to allow port 8000...
netsh advfirewall firewall add rule name="Laravel Backend - Port 8000" dir=in action=allow protocol=TCP localport=8000 profile=any
echo.
if %errorlevel% equ 0 (
    echo SUCCESS: Firewall rule added successfully!
) else (
    echo FAILED: Make sure you right-click and "Run as Administrator"
)
echo.
echo Adding firewall rule for port 5173 (Vite dev server)...
netsh advfirewall firewall add rule name="Vite Dev Server - Port 5173" dir=in action=allow protocol=TCP localport=5173 profile=any
echo.
pause