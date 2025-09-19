<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Shipper Functions - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Test Shipper Functions</h1>
        
        <!-- Quick Login -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Quick Login as Shipper</h2>
            <button onclick="quickLoginShipper()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Login as Test Shipper
            </button>
            <div id="loginStatus" class="mt-4"></div>
        </div>

        <!-- Test API Endpoints -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test API Endpoints</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button onclick="testDashboardAPI()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-chart-bar mr-2"></i>Test Dashboard API
                </button>
                <button onclick="testOrdersAPI()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-list mr-2"></i>Test Orders API
                </button>
                <button onclick="testCurrentOrdersAPI()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                    <i class="fas fa-truck mr-2"></i>Test Current Orders API
                </button>
                <button onclick="testHistoryAPI()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-history mr-2"></i>Test History API
                </button>
            </div>
        </div>

        <!-- Test Results -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">Test Results</h2>
            <div id="testResults" class="space-y-4">
                <p class="text-gray-500">Click the buttons above to test different APIs</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Quick Navigation</h2>
            <div class="flex flex-wrap gap-4">
                <a href="/shipper/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>Shipper Dashboard
                </a>
                <a href="/shipper/orders" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-box mr-2"></i>Orders Management
                </a>
                <a href="/shipper/history" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-history mr-2"></i>Delivery History
                </a>
            </div>
        </div>
    </div>

    <script>
        // Quick login function
        async function quickLoginShipper() {
            try {
                showLoading('loginStatus', 'Đang đăng nhập...');
                
                const response = await fetch('/api/dev/quick-login-shipper', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('loginStatus').innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <strong>Đăng nhập thành công!</strong><br>
                            Tên: ${result.user.name}<br>
                            Email: ${result.user.email}<br>
                            Role: ${result.user.role}
                        </div>
                    `;
                } else {
                    showError('loginStatus', 'Đăng nhập thất bại: ' + result.message);
                }
            } catch (error) {
                console.error('Login error:', error);
                showError('loginStatus', 'Có lỗi xảy ra khi đăng nhập');
            }
        }

        // Test Dashboard API
        async function testDashboardAPI() {
            try {
                addTestResult('Testing Dashboard API...', 'info');
                
                const response = await fetch('/api/shipper/dashboard', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ Dashboard API: ' + JSON.stringify(result.data, null, 2), 'success');
                } else {
                    addTestResult('❌ Dashboard API Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Dashboard API error:', error);
                addTestResult('❌ Dashboard API Exception: ' + error.message, 'error');
            }
        }

        // Test Orders API
        async function testOrdersAPI() {
            try {
                addTestResult('Testing Orders API...', 'info');
                
                const response = await fetch('/api/shipper/orders/list', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ Orders API: Found ' + result.data.length + ' orders', 'success');
                    if (result.data.length > 0) {
                        addTestResult('Sample order: ' + JSON.stringify(result.data[0], null, 2), 'info');
                    }
                } else {
                    addTestResult('❌ Orders API Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Orders API error:', error);
                addTestResult('❌ Orders API Exception: ' + error.message, 'error');
            }
        }

        // Test Current Orders API
        async function testCurrentOrdersAPI() {
            try {
                addTestResult('Testing Current Orders API...', 'info');
                
                const response = await fetch('/api/shipper/current-orders', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ Current Orders API: Found ' + result.data.length + ' current orders', 'success');
                } else {
                    addTestResult('❌ Current Orders API Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Current Orders API error:', error);
                addTestResult('❌ Current Orders API Exception: ' + error.message, 'error');
            }
        }

        // Test History API
        async function testHistoryAPI() {
            try {
                addTestResult('Testing History API...', 'info');
                
                const response = await fetch('/api/shipper/delivery-history', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    addTestResult('✅ History API: Found ' + result.data.length + ' history records', 'success');
                } else {
                    addTestResult('❌ History API Error: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('History API error:', error);
                addTestResult('❌ History API Exception: ' + error.message, 'error');
            }
        }

        // Helper functions
        function addTestResult(message, type) {
            const container = document.getElementById('testResults');
            const div = document.createElement('div');
            
            let bgColor = 'bg-gray-100';
            if (type === 'success') bgColor = 'bg-green-100 text-green-800';
            if (type === 'error') bgColor = 'bg-red-100 text-red-800';
            if (type === 'info') bgColor = 'bg-blue-100 text-blue-800';
            
            div.className = `p-3 rounded ${bgColor}`;
            div.innerHTML = `<pre class="text-sm overflow-x-auto">${message}</pre>`;
            
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function showLoading(elementId, message) {
            document.getElementById(elementId).innerHTML = `
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    <i class="fas fa-spinner fa-spin mr-2"></i>${message}
                </div>
            `;
        }

        function showError(elementId, message) {
            document.getElementById(elementId).innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Lỗi:</strong> ${message}
                </div>
            `;
        }
    </script>
</body>
</html>