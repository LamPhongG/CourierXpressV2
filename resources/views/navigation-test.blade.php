<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Test - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Simple Header for Testing -->
    <header class="bg-orange-600 text-white shadow-md">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xl font-bold">CourierXpress Test</span>
                </div>
                <div class="flex space-x-4">
                    <!-- Simple Login Link -->
                    <a href="/login" 
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                       onclick="console.log('Login link clicked');">
                        LOGIN TEST
                    </a>
                    <!-- Simple Register Link -->
                    <a href="/register" 
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                       onclick="console.log('Register link clicked');">
                        REGISTER TEST
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Navigation Testing Page</h1>
            <p class="mb-4">This page tests if navigation to login and register pages work correctly.</p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold mb-3">Direct URL Tests:</h3>
                    <div class="space-y-2">
                        <a href="/login" class="block bg-orange-600 text-white text-center py-2 rounded hover:bg-orange-700">
                            Go to Login Page
                        </a>
                        <a href="/register" class="block bg-purple-600 text-white text-center py-2 rounded hover:bg-purple-700">
                            Go to Register Page
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold mb-3">JavaScript Navigation:</h3>
                    <div class="space-y-2">
                        <button onclick="navigateToLogin()" class="block w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            JavaScript to Login
                        </button>
                        <button onclick="navigateToRegister()" class="block w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                            JavaScript to Register
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
                <h4 class="font-semibold text-yellow-800">Debug Information:</h4>
                <div class="text-sm text-yellow-700 mt-2">
                    <p><strong>Current URL:</strong> <span id="current-url"></span></p>
                    <p><strong>Base URL:</strong> <span id="base-url"></span></p>
                    <p><strong>User Agent:</strong> <span id="user-agent"></span></p>
                </div>
            </div>

            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
                <h4 class="font-semibold text-blue-800">Instructions:</h4>
                <ol class="text-sm text-blue-700 mt-2 list-decimal list-inside space-y-1">
                    <li>Try clicking the "LOGIN TEST" and "REGISTER TEST" buttons in the header</li>
                    <li>Try the "Go to Login Page" and "Go to Register Page" buttons</li>
                    <li>Try the JavaScript navigation buttons</li>
                    <li>Open browser console (F12) to see any error messages</li>
                    <li>If none work, the issue might be with your XAMPP configuration</li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        // Debug information
        document.getElementById('current-url').textContent = window.location.href;
        document.getElementById('base-url').textContent = window.location.origin;
        document.getElementById('user-agent').textContent = navigator.userAgent;

        // JavaScript navigation functions
        function navigateToLogin() {
            console.log('Navigating to login via JavaScript...');
            window.location.href = '/login';
        }

        function navigateToRegister() {
            console.log('Navigating to register via JavaScript...');
            window.location.href = '/register';
        }

        // Log all clicks for debugging
        document.addEventListener('click', function(event) {
            if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON') {
                console.log('Element clicked:', event.target);
                console.log('Target href:', event.target.href || 'No href');
                console.log('Target onclick:', event.target.onclick || 'No onclick');
            }
        });

        // Check if navigation works
        console.log('Navigation test page loaded successfully');
        console.log('Testing links...');
        
        // Test if login/register pages are accessible
        fetch('/login', { method: 'HEAD' })
            .then(response => {
                console.log('Login page status:', response.status);
                if (response.ok) {
                    console.log('✓ Login page is accessible');
                } else {
                    console.log('✗ Login page returned error:', response.status);
                }
            })
            .catch(error => {
                console.log('✗ Error accessing login page:', error);
            });

        fetch('/register', { method: 'HEAD' })
            .then(response => {
                console.log('Register page status:', response.status);
                if (response.ok) {
                    console.log('✓ Register page is accessible');
                } else {
                    console.log('✗ Register page returned error:', response.status);
                }
            })
            .catch(error => {
                console.log('✗ Error accessing register page:', error);
            });
    </script>
</body>
</html>