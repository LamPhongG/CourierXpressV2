<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CourierXpress')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#dc2626",
                        secondary: "#f97316", 
                        accent: "#10b981"
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('head')
</head>
<body class="bg-gray-50">
    <!-- Unified Header -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('images/bao.png') }}" alt="Logo" class="h-8 w-8 mr-2">
                        <span class="text-2xl font-bold cyberpunk-title">CourierXpress</span>
                    </a>
                    @if(isset($role_title))
                        <span class="ml-4 text-red-600 font-medium">{{ $role_title }}</span>
                    @endif
                </div>

                <!-- Navigation - Dynamic based on role -->
                <nav class="hidden md:flex items-center space-x-8">
                    @yield('navigation')
                </nav>

                <!-- User Info & Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Button (optional - can be yielded) -->
                    @hasSection('header_actions')
                        @yield('header_actions')
                    @endif
                    
                    <span class="text-gray-700" id="userName">{{ auth()->check() ? auth()->user()->name : 'User' }}</span>
                    
                    <!-- JavaScript Logout Button -->
                    <button onclick="logout()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                    </button>
                    
                    <!-- Alternative: Direct Form Logout (for testing) -->
                    <!-- 
                    <form method="POST" action="/eprojectv2/CourierXpress/Project/public/logout" style="display: inline;">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất (Test)
                        </button>
                    </form>
                    -->
                </div>
            </div>
        </div>
    </header>

    <!-- Sub Navigation (optional) -->
    @hasSection('sub_navigation')
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                @yield('sub_navigation')
            </div>
        </div>
    </nav>
    @endif

    <!-- Main Content -->
    <main class="@yield('container_class', 'max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8')">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                © 2024 CourierXpress. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Common Scripts -->
    <script>
        // Laravel session authentication check and localStorage sync
        @if(auth()->check())
        // Store Laravel session data in localStorage for compatibility
        const userData = {
            id: {{ auth()->user()->id }},
            name: "{{ auth()->user()->name }}",
            email: "{{ auth()->user()->email }}",
            role: "{{ auth()->user()->role }}",
            status: "{{ auth()->user()->status ?? 'active' }}"
        };
        
        localStorage.setItem('user_data', JSON.stringify(userData));
        localStorage.setItem('auth_token', 'laravel_session_' + userData.id);
        
        // Update user name in header
        document.addEventListener('DOMContentLoaded', function() {
            const userNameEl = document.getElementById('userName');
            if (userNameEl) {
                userNameEl.textContent = userData.name;
            }
        });
        @else
        // Clear localStorage if not authenticated and redirect
        localStorage.removeItem('user_data');
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
        @endif
        
        // Common functions
        function logout() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                // Show loading indicator
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang đăng xuất...';
                button.disabled = true;
                
                // Clear localStorage
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
                
                // Use Laravel logout
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Check authentication - now works with Laravel session
        function checkAuth(requiredRole = null) {
            @if(auth()->check())
            const user = {
                role: "{{ auth()->user()->role }}"
            };
            
            if (requiredRole && user.role !== requiredRole) {
                // Redirect to appropriate dashboard based on actual role
                if (user.role === 'admin') {
                    window.location.href = '/admin/dashboard';
                } else if (user.role === 'agent') {
                    window.location.href = '/agent/dashboard';
                } else if (user.role === 'shipper') {
                    window.location.href = '/shipper/dashboard';
                } else if (user.role === 'user') {
                    window.location.href = '/user/dashboard';
                } else {
                    window.location.href = '/login';
                }
            }
            @else
            // Not authenticated via Laravel session
            window.location.href = '/login';
            @endif
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND' 
            }).format(amount);
        }

        // Format date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
