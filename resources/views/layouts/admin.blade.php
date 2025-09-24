<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - CourierXpress')</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/dashboard.css') }}">
    
    <!-- CSS Variables -->
    <style>
        :root {
            --primary: #00b14f;
            --primary-dark: #009640;
            --secondary: #ffa500;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --text-primary: #333333;
            --text-secondary: #666666;
            --text-light: #ffffff;
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --border: #e5e5e5;
        }
        
        .admin-sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
        }
        
        .stat-card {
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .chart-card {
            background: var(--bg-primary);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table-card {
            background: var(--bg-primary);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--text-light);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .sidebar-menu-item {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu-item:hover,
        .sidebar-menu-item.active {
            color: var(--text-light);
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--text-light);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="admin-sidebar w-64 fixed h-full">
            <div class="p-4">
                <a href="/admin/dashboard" class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-shipping-fast text-primary text-xl"></i>
                    </div>
                    <span class="text-white text-xl font-bold">CourierXpress</span>
                </a>

                <nav>
                    <a href="/admin/dashboard" class="sidebar-menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="/admin/orders" class="sidebar-menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
                        <i class="fas fa-box w-6"></i>
                        <span class="ml-3">Orders</span>
                    </a>
                    <a href="/admin/users" class="sidebar-menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span class="ml-3">Users</span>
                    </a>
                    <a href="/admin/drivers" class="sidebar-menu-item {{ request()->is('admin/drivers*') ? 'active' : '' }}">
                        <i class="fas fa-truck w-6"></i>
                        <span class="ml-3">Drivers</span>
                    </a>
                    <a href="/admin/tracking" class="sidebar-menu-item {{ request()->is('admin/tracking*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt w-6"></i>
                        <span class="ml-3">Tracking</span>
                    </a>
                    <a href="/admin/reports" class="sidebar-menu-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar w-6"></i>
                        <span class="ml-3">Reports</span>
                    </a>
                    <a href="/admin/settings" class="sidebar-menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog w-6"></i>
                        <span class="ml-3">Settings</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 w-full min-h-screen">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="flex items-center">
                            <div class="relative">
                                <button id="notificationBtn" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <i class="fas fa-bell"></i>
                                    <span id="notificationBadge" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                                </button>
                            </div>
                            <div class="ml-4 relative">
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-700">Admin</span>
                                    <button id="logoutBtn" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @yield('scripts')
    <script>
        // Notification handling
        document.getElementById('notificationBtn').addEventListener('click', function() {
            // Add notification functionality here
        });

        // Logout handling
        document.getElementById('logoutBtn').addEventListener('click', function() {
            // Add logout functionality here
        });
    </script>
</body>
</html>
