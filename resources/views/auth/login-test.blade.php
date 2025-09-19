<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Test Login Form
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Test real login functionality with proper session handling
                </p>
            </div>
            
            <!-- Login Form -->
            <form class="mt-8 space-y-6" action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Email address"
                               value="admin@courierxpress.com">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Password"
                               value="123456">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in as Admin
                    </button>
                </div>
            </form>

            <!-- Quick Test Buttons -->
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="testLogin('admin')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Test Admin
                    </button>
                    <button onclick="testLogin('agent')" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                        Test Agent
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="testLogin('customer')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Test User
                    </button>
                    <button onclick="testLogin('shipper')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Test Shipper
                    </button>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-gray-100 p-4 rounded">
                <h3 class="font-semibold mb-2">Debug Links:</h3>
                <div class="space-y-1 text-sm">
                    <a href="/test-dashboard-access" class="text-blue-600 hover:underline block">Check Auth Status</a>
                    <a href="/debug-current-user" class="text-blue-600 hover:underline block">Debug Current User</a>
                    <a href="/admin/dashboard" class="text-blue-600 hover:underline block">Admin Dashboard</a>
                    <a href="/login" class="text-blue-600 hover:underline block">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const credentials = {
            admin: { email: 'admin@courierxpress.com', password: '123456' },
            agent: { email: 'agent@courierxpress.com', password: '123456' },
            customer: { email: 'customer@courierxpress.com', password: '123456' },
            shipper: { email: 'shipper@courierxpress.com', password: '123456' }
        };

        function testLogin(role) {
            const creds = credentials[role];
            document.getElementById('email').value = creds.email;
            document.getElementById('password').value = creds.password;
        }
    </script>
</body>
</html>