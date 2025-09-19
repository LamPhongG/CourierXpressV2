<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        secondary: "#F97316", 
                        accent: "#10B981"
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/admin/dashboard" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">CourierXpress</span>
                    </a>
                    <span class="ml-4 px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                        System Settings
                    </span>
                </div>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
                    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
                    <a href="/admin/users" class="text-gray-700 hover:text-red-600">Người dùng</a>
                    <a href="/admin/settings" class="text-red-600 font-medium">Cài đặt</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-shield text-red-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <button onclick="logout()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/admin/dashboard" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                        <span class="text-gray-500">Cài đặt hệ thống</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Cài đặt hệ thống</h1>
                <p class="text-purple-100">Quản lý và cấu hình các thiết lập của hệ thống CourierXpress</p>
            </div>
        </div>

        <!-- Settings Container -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Settings Navigation -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục cài đặt</h3>
                    <nav class="space-y-2">
                        <button onclick="switchCategory('general')" class="setting-tab w-full text-left px-4 py-3 rounded-lg bg-blue-600 text-white font-medium">
                            <i class="fas fa-cog mr-3"></i>Tổng quát
                        </button>
                        <button onclick="switchCategory('business')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-building mr-3"></i>Thông tin doanh nghiệp
                        </button>
                        <button onclick="switchCategory('shipping')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-truck mr-3"></i>Vận chuyển
                        </button>
                        <button onclick="switchCategory('notification')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-bell mr-3"></i>Thông báo
                        </button>
                        <button onclick="switchCategory('security')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-shield-alt mr-3"></i>Bảo mật
                        </button>
                    </nav>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <button onclick="exportSettings()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Xuất cài đặt
                        </button>
                        <button onclick="showImportModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-upload mr-2"></i>Nhập cài đặt
                        </button>
                        <button onclick="resetSettings()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-undo mr-2"></i>Đặt lại mặc định
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-lg">
                    <!-- Settings Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900" id="settingsTitle">Cài đặt tổng quát</h3>
                        <div class="flex space-x-2">
                            <button onclick="saveSettings()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-save mr-2"></i>Lưu thay đổi
                            </button>
                            <button onclick="loadSettings()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-refresh mr-2"></i>Tải lại
                            </button>
                        </div>
                    </div>

                    <!-- Settings Form -->
                    <div class="p-6">
                        <form id="settingsForm">
                            <div id="settingsContent">
                                <!-- Settings content will be loaded here -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Nhập cài đặt</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file cài đặt (JSON)</label>
                        <input type="file" id="settingsFile" accept=".json" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="importSettings()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Nhập
                    </button>
                    <button onclick="hideImportModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCategory = 'general';
        let currentSettings = {};

        const settingTemplates = {
            general: {
                title: 'Cài đặt tổng quát',
                fields: [
                    { key: 'app_name', label: 'Tên ứng dụng', type: 'text' },
                    { key: 'app_url', label: 'URL ứng dụng', type: 'url' },
                    { key: 'timezone', label: 'Múi giờ', type: 'select', options: ['UTC', 'Asia/Ho_Chi_Minh', 'Asia/Bangkok'] },
                    { key: 'locale', label: 'Ngôn ngữ mặc định', type: 'select', options: ['en', 'vi', 'hi'] },
                    { key: 'debug_mode', label: 'Chế độ debug', type: 'checkbox' }
                ]
            },
            business: {
                title: 'Thông tin doanh nghiệp',
                fields: [
                    { key: 'company_name', label: 'Tên công ty', type: 'text' },
                    { key: 'company_address', label: 'Địa chỉ', type: 'textarea' },
                    { key: 'company_phone', label: 'Số điện thoại', type: 'tel' },
                    { key: 'company_email', label: 'Email', type: 'email' },
                    { key: 'business_hours', label: 'Giờ làm việc', type: 'text' },
                    { key: 'working_days', label: 'Ngày làm việc', type: 'text' }
                ]
            },
            shipping: {
                title: 'Cài đặt vận chuyển',
                fields: [
                    { key: 'default_shipping_fee', label: 'Phí vận chuyển mặc định (VND)', type: 'number' },
                    { key: 'free_shipping_threshold', label: 'Ngưỡng miễn phí vận chuyển (VND)', type: 'number' },
                    { key: 'insurance_rate', label: 'Tỷ lệ bảo hiểm (%)', type: 'number', step: '0.01' },
                    { key: 'cod_fee_rate', label: 'Phí COD (%)', type: 'number', step: '0.01' },
                    { key: 'max_package_weight', label: 'Trọng lượng tối đa (kg)', type: 'number' },
                    { key: 'delivery_time_standard', label: 'Thời gian giao hàng tiêu chuẩn (giờ)', type: 'number' },
                    { key: 'delivery_time_express', label: 'Thời gian giao hàng nhanh (giờ)', type: 'number' }
                ]
            },
            notification: {
                title: 'Cài đặt thông báo',
                fields: [
                    { key: 'email_notifications', label: 'Thông báo email', type: 'checkbox' },
                    { key: 'sms_notifications', label: 'Thông báo SMS', type: 'checkbox' },
                    { key: 'push_notifications', label: 'Thông báo đẩy', type: 'checkbox' },
                    { key: 'order_status_updates', label: 'Cập nhật trạng thái đơn hàng', type: 'checkbox' },
                    { key: 'payment_confirmations', label: 'Xác nhận thanh toán', type: 'checkbox' },
                    { key: 'system_alerts', label: 'Cảnh báo hệ thống', type: 'checkbox' }
                ]
            },
            security: {
                title: 'Cài đặt bảo mật',
                fields: [
                    { key: 'session_timeout', label: 'Thời gian hết hạn phiên (phút)', type: 'number' },
                    { key: 'password_expiry_days', label: 'Thời hạn mật khẩu (ngày)', type: 'number' },
                    { key: 'max_login_attempts', label: 'Số lần đăng nhập tối đa', type: 'number' },
                    { key: 'lockout_duration', label: 'Thời gian khóa (phút)', type: 'number' },
                    { key: 'two_factor_auth', label: 'Xác thực 2 bước', type: 'checkbox' },
                    { key: 'ip_whitelist_enabled', label: 'Danh sách IP được phép', type: 'checkbox' }
                ]
            }
        };

        function switchCategory(category) {
            currentCategory = category;
            
            // Update active tab
            document.querySelectorAll('.setting-tab').forEach(tab => {
                tab.classList.remove('bg-blue-600', 'text-white');
                tab.classList.add('text-gray-700', 'hover:bg-gray-100');
            });
            event.target.classList.add('bg-blue-600', 'text-white');
            event.target.classList.remove('text-gray-700', 'hover:bg-gray-100');
            
            // Update title
            document.getElementById('settingsTitle').textContent = settingTemplates[category].title;
            
            // Load settings for this category
            loadSettings();
        }

        async function loadSettings() {
            try {
                const response = await fetch('/admin/api/settings');
                const data = await response.json();
                currentSettings = data;
                renderSettingsForm();
            } catch (error) {
                console.error('Error loading settings:', error);
                showNotification('Lỗi khi tải cài đặt', 'error');
            }
        }

        function renderSettingsForm() {
            const template = settingTemplates[currentCategory];
            const settings = currentSettings[currentCategory] || {};
            const content = document.getElementById('settingsContent');
            
            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
            
            template.fields.forEach(field => {
                const value = settings[field.key] || '';
                
                html += `<div class="col-span-1 ${field.type === 'textarea' ? 'md:col-span-2' : ''}">`;
                html += `<label class="block text-sm font-medium text-gray-700 mb-2">${field.label}</label>`;
                
                if (field.type === 'select') {
                    html += `<select name="${field.key}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">`;
                    field.options.forEach(option => {
                        html += `<option value="${option}" ${value === option ? 'selected' : ''}>${option}</option>`;
                    });
                    html += `</select>`;
                } else if (field.type === 'textarea') {
                    html += `<textarea name="${field.key}" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">${value}</textarea>`;
                } else if (field.type === 'checkbox') {
                    html += `<div class="flex items-center">`;
                    html += `<input type="checkbox" name="${field.key}" ${value ? 'checked' : ''} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">`;
                    html += `<span class="ml-2 text-sm text-gray-600">Bật</span>`;
                    html += `</div>`;
                } else {
                    const step = field.step ? `step="${field.step}"` : '';
                    html += `<input type="${field.type}" name="${field.key}" value="${value}" ${step} class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">`;
                }
                
                html += `</div>`;
            });
            
            html += '</div>';
            content.innerHTML = html;
        }

        async function saveSettings() {
            const form = document.getElementById('settingsForm');
            const formData = new FormData(form);
            const settings = {};
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                const field = settingTemplates[currentCategory].fields.find(f => f.key === key);
                if (field) {
                    if (field.type === 'checkbox') {
                        settings[key] = true;
                    } else if (field.type === 'number') {
                        settings[key] = parseFloat(value) || 0;
                    } else {
                        settings[key] = value;
                    }
                }
            }
            
            // Handle unchecked checkboxes
            settingTemplates[currentCategory].fields.forEach(field => {
                if (field.type === 'checkbox' && !formData.has(field.key)) {
                    settings[field.key] = false;
                }
            });
            
            try {
                const response = await fetch('/admin/api/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        category: currentCategory,
                        settings: settings
                    })
                });
                
                if (response.ok) {
                    showNotification('Cài đặt đã được lưu thành công', 'success');
                    loadSettings(); // Reload to show updated values
                } else {
                    const error = await response.json();
                    showNotification('Lỗi khi lưu cài đặt: ' + (error.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                showNotification('Lỗi khi lưu cài đặt', 'error');
            }
        }

        async function resetSettings() {
            if (!confirm('Bạn có chắc muốn đặt lại tất cả cài đặt về mặc định không?')) {
                return;
            }
            
            try {
                const response = await fetch('/admin/api/settings/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    showNotification('Cài đặt đã được đặt lại về mặc định', 'success');
                    loadSettings();
                } else {
                    showNotification('Lỗi khi đặt lại cài đặt', 'error');
                }
            } catch (error) {
                console.error('Error resetting settings:', error);
                showNotification('Lỗi khi đặt lại cài đặt', 'error');
            }
        }

        async function exportSettings() {
            try {
                const response = await fetch('/admin/api/settings/export');
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `courierxpress_settings_${new Date().toISOString().split('T')[0]}.json`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                showNotification('Cài đặt đã được xuất thành công', 'success');
            } catch (error) {
                console.error('Error exporting settings:', error);
                showNotification('Lỗi khi xuất cài đặt', 'error');
            }
        }

        function showImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function hideImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.getElementById('settingsFile').value = '';
        }

        async function importSettings() {
            const fileInput = document.getElementById('settingsFile');
            if (!fileInput.files[0]) {
                showNotification('Vui lòng chọn file để nhập', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('settings_file', fileInput.files[0]);
            
            try {
                const response = await fetch('/admin/api/settings/import', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                if (response.ok) {
                    showNotification('Cài đặt đã được nhập thành công', 'success');
                    hideImportModal();
                    loadSettings();
                } else {
                    const error = await response.json();
                    showNotification('Lỗi khi nhập cài đặt: ' + (error.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error importing settings:', error);
                showNotification('Lỗi khi nhập cài đặt', 'error');
            }
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }

        function logout() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
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

        // Initialize
        document.addEventListener('DOMContentLoaded', loadSettings);
    </script>
</body>
</html>