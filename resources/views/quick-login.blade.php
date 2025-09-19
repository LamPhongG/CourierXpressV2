<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập nhanh - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Đăng nhập nhanh</h1>
            
            <div class="space-y-4">
                <button onclick="quickLogin()" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-user mr-2"></i>
                    Đăng nhập với tài khoản User
                </button>
                
                <div id="status" class="text-center text-sm"></div>
                
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold mb-2">Hướng dẫn:</h3>
                    <ol class="text-sm text-gray-600 space-y-1">
                        <li>1. Nhấn nút "Đăng nhập với tài khoản User"</li>
                        <li>2. Đợi thông báo đăng nhập thành công</li>
                        <li>3. Chuyển đến trang tạo đơn hàng</li>
                        <li>4. Thử tạo đơn hàng mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function quickLogin() {
            const statusDiv = document.getElementById('status');
            const button = event.target;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang đăng nhập...';
            statusDiv.innerHTML = '<div class="text-blue-600">Đang xử lý đăng nhập...</div>';
            
            try {
                const response = await fetch('/api/dev/login-user-with-session');
                const data = await response.json();
                
                if (data.success) {
                    statusDiv.innerHTML = '<div class="text-green-600">✅ ' + data.message + '</div>';
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/user/create-order';
                    }, 2000);
                } else {
                    statusDiv.innerHTML = '<div class="text-red-600">❌ Lỗi: ' + data.message + '</div>';
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-user mr-2"></i>Đăng nhập với tài khoản User';
                }
            } catch (error) {
                statusDiv.innerHTML = '<div class="text-red-600">❌ Lỗi kết nối: ' + error.message + '</div>';
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-user mr-2"></i>Đăng nhập với tài khoản User';
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>