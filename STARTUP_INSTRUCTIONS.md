# CourierXpress Project Startup Instructions

## Quick Startup

You can start the project using either of these methods:

### Method 1: Using the Batch File (Windows)
Double-click on `start-project.bat` file in the project root directory.

### Method 2: Using the PowerShell Script (Windows)
1. Right-click on `start-project.ps1` file
2. Select "Run with PowerShell"

### Method 3: Manual Command Line
Open a terminal/command prompt and run:

```bash
cd "d:\Xampp\htdocs\eprojectv2\Project - Copy"
"d:\xampp\php\php.exe" artisan serve
```

## What the Startup Script Does

1. Clears all Laravel caches (config, route, and view caches)
2. Starts the Laravel development server on http://127.0.0.1:8000

## Troubleshooting

If you encounter the "Unsupported cipher or incorrect key length" error:

1. Run the following command to regenerate the application key:
   ```bash
   "d:\xampp\php\php.exe" artisan key:generate
   ```

2. Clear all caches:
   ```bash
   "d:\xampp\php\php.exe" artisan config:clear
   "d:\xampp\php\php.exe" artisan route:clear
   "d:\xampp\php\php.exe" artisan view:clear
   ```

3. Restart the server:
   ```bash
   "d:\xampp\php\php.exe" artisan serve
   ```

## Accessing the Application

Once the server is running, you can access the application at:
- http://127.0.0.1:8000

Default test accounts:
- Admin: admin@courierxpress.com / 123456
- Customer: customer@courierxpress.com / 123456
- Agent: agent@courierxpress.com / 123456
- Shipper: shipper@courierxpress.com / 123456