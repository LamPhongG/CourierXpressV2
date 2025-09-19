<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Accounts - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-8 text-orange-600">
                🚚 CourierXpress - Test Accounts
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Admin Account -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-red-800 mb-4 flex items-center">
                        👨‍💼 Admin Account
                    </h2>
                    <div class="space-y-2">
                        <p><strong>Email:</strong> <code class="bg-red-100 px-2 py-1 rounded">admin@courierxpress.com</code></p>
                        <p><strong>Password:</strong> <code class="bg-red-100 px-2 py-1 rounded">123456</code></p>
                        <p><strong>Role:</strong> Admin</p>
                        <p class="text-sm text-red-600">Quản lý toàn hệ thống</p>
                    </div>
                </div>

                <!-- Agent Account -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-green-800 mb-4 flex items-center">
                        🏢 Agent Account
                    </h2>
                    <div class="space-y-2">
                        <p><strong>Email:</strong> <code class="bg-green-100 px-2 py-1 rounded">agent@courierxpress.com</code></p>
                        <p><strong>Password:</strong> <code class="bg-green-100 px-2 py-1 rounded">123456</code></p>
                        <p><strong>Role:</strong> Agent</p>
                        <p class="text-sm text-green-600">Xử lý đơn hàng tại chi nhánh</p>
                    </div>
                </div>

                <!-- Shipper Account -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-yellow-800 mb-4 flex items-center">
                        🏍️ Shipper Account
                    </h2>
                    <div class="space-y-2">
                        <p><strong>Email:</strong> <code class="bg-yellow-100 px-2 py-1 rounded">shipper@courierxpress.com</code></p>
                        <p><strong>Password:</strong> <code class="bg-yellow-100 px-2 py-1 rounded">123456</code></p>
                        <p><strong>Role:</strong> Shipper</p>
                        <p class="text-sm text-yellow-600">Giao hàng và cập nhật trạng thái</p>
                    </div>
                </div>

                <!-- Customer Account -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-blue-800 mb-4 flex items-center">
                        👥 Customer Account
                    </h2>
                    <div class="space-y-2">
                        <p><strong>Email:</strong> <code class="bg-blue-100 px-2 py-1 rounded">customer@courierxpress.com</code></p>
                        <p><strong>Password:</strong> <code class="bg-blue-100 px-2 py-1 rounded">123456</code></p>
                        <p><strong>Role:</strong> Customer</p>
                        <p class="text-sm text-blue-600">Tạo đơn và theo dõi</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-orange-800 mb-2">🔗 Quick Links</h3>
                    <div class="space-x-4">
                        <a href="/login" class="inline-block bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition">
                            Đăng nhập
                        </a>
                        <a href="/register" class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                            Đăng ký
                        </a>
                        <a href="/" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Trang chủ
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">📋 Hướng dẫn test</h3>
                    <ol class="text-sm text-gray-600 text-left list-decimal list-inside space-y-1">
                        <li>Copy email và password từ bảng trên</li>
                        <li>Truy cập trang <a href="/login" class="text-orange-600 underline">đăng nhập</a></li>
                        <li>Paste thông tin và đăng nhập</li>
                        <li>Kiểm tra dashboard tương ứng với role</li>
                        <li>Test các chức năng của từng vai trò</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Copy to clipboard functionality
        document.querySelectorAll('code').forEach(code => {
            code.addEventListener('click', () => {
                navigator.clipboard.writeText(code.textContent);
                code.classList.add('bg-green-200');
                setTimeout(() => code.classList.remove('bg-green-200'), 500);
            });
        });
    </script>
</body>
</html>