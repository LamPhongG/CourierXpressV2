@echo off
echo Starting CourierXpress Project...

echo Clearing caches...
cd /d "d:\Xampp\htdocs\CourierXpressv1\CourierXpressV2"
"d:\xampp\php\php.exe" artisan config:clear
"d:\xampp\php\php.exe" artisan route:clear
"d:\xampp\php\php.exe" artisan view:clear

echo Starting development server...
"d:\xampp\php\php.exe" artisan serve

echo Project started successfully!
pause