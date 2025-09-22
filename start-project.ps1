Write-Host "Starting CourierXpress Project..." -ForegroundColor Green

Write-Host "Clearing caches..." -ForegroundColor Yellow
Set-Location "d:\XAMPP\htdocs\CourierV2"
& "d:\xampp\php\php.exe" artisan config:clear
& "d:\xampp\php\php.exe" artisan route:clear
& "d:\xampp\php\php.exe" artisan view:clear

Write-Host "Starting development server..." -ForegroundColor Yellow
& "d:\xampp\php\php.exe" artisan serve

Write-Host "Project started successfully!" -ForegroundColor Green