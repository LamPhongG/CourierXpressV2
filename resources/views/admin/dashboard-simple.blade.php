<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Admin Dashboard</h1>
                    
                    @if(auth()->check())
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <strong>✅ Authentication Success!</strong><br>
                            Welcome, {{ auth()->user()->name }}<br>
                            Email: {{ auth()->user()->email }}<br>
                            Role: {{ auth()->user()->role }}<br>
                            Status: {{ auth()->user()->status }}
                        </div>
                        
                        <script>
                            // Store user data in localStorage for compatibility
                            const userData = {
                                id: {{ auth()->user()->id }},
                                name: "{{ auth()->user()->name }}",
                                email: "{{ auth()->user()->email }}",
                                role: "{{ auth()->user()->role }}",
                                status: "{{ auth()->user()->status }}"
                            };
                            
                            localStorage.setItem('user_data', JSON.stringify(userData));
                            localStorage.setItem('auth_token', 'laravel_session_' + userData.id);
                            
                            console.log('✅ User data stored in localStorage:', userData);
                        </script>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>❌ Not Authenticated</strong><br>
                            You are not logged in. Please <a href="/login" class="underline">login</a>.
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4">Navigation</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="/admin/dashboard" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Full Admin Dashboard
                            </a>
                            <a href="/login" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Login
                            </a>
                            <a href="/test-dashboard-access" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Test Dashboard Access
                            </a>
                            <a href="/debug-current-user" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Debug Current User
                            </a>
                        </div>
                    </div>
                    
                    <form method="POST" action="/logout" class="mt-6">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>