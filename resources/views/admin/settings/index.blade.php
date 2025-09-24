<!DOCTYPE html>
<html lang="en">
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
                    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Orders</a>
                    <a href="/admin/users" class="text-gray-700 hover:text-red-600">Users</a>
                    <a href="/admin/settings" class="text-red-600 font-medium">Settings</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-shield text-red-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <button onclick="logout()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
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
                        <span class="text-gray-500">System settings</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">System settings</h1>
                <p class="text-purple-100">Manage and configure CourierXpress system settings</p>
            </div>
        </div>

        <!-- Settings Container -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Settings Navigation -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings categories</h3>
                    <nav class="space-y-2">
                        <button onclick="switchCategory('general')" class="setting-tab w-full text-left px-4 py-3 rounded-lg bg-blue-600 text-white font-medium">
                            <i class="fas fa-cog mr-3"></i>General
                        </button>
                        <button onclick="switchCategory('business')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-building mr-3"></i>Business information
                        </button>
                        <button onclick="switchCategory('shipping')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-truck mr-3"></i>Shipping
                        </button>
                        <button onclick="switchCategory('notification')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-bell mr-3"></i>Notifications
                        </button>
                        <button onclick="switchCategory('security')" class="setting-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 font-medium">
                            <i class="fas fa-shield-alt mr-3"></i>Security
                        </button>
                    </nav>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <button onclick="exportSettings()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export settings
                        </button>
                        <button onclick="showImportModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-upload mr-2"></i>Import settings
                        </button>
                        <button onclick="resetSettings()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-undo mr-2"></i>Reset to defaults
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-lg">
                    <!-- Settings Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900" id="settingsTitle">General settings</h3>
                        <div class="flex space-x-2">
                            <button onclick="saveSettings()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-save mr-2"></i>Save changes
                            </button>
                            <button onclick="loadSettings()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-refresh mr-2"></i>Reload
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Import settings</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select settings file (JSON)</label>
                        <input type="file" id="settingsFile" accept=".json" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="importSettings()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Import
                    </button>
                    <button onclick="hideImportModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
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
                title: 'General settings',
                fields: [
                    { key: 'app_name', label: 'Application name', type: 'text' },
                    { key: 'app_url', label: 'Application URL', type: 'url' },
                    { key: 'timezone', label: 'Timezone', type: 'select', options: ['UTC', 'Asia/Ho_Chi_Minh', 'Asia/Bangkok'] },
                    { key: 'locale', label: 'Default language', type: 'select', options: ['en', 'vi', 'hi'] },
                    { key: 'debug_mode', label: 'Debug mode', type: 'checkbox' }
                ]
            },
            business: {
                title: 'Business information',
                fields: [
                    { key: 'company_name', label: 'Company name', type: 'text' },
                    { key: 'company_address', label: 'Company address', type: 'textarea' },
                    { key: 'company_phone', label: 'Phone number', type: 'tel' },
                    { key: 'company_email', label: 'Email', type: 'email' },
                    { key: 'business_hours', label: 'Business hours', type: 'text' },
                    { key: 'working_days', label: 'Working days', type: 'text' }
                ]
            },
            shipping: {
                title: 'Shipping settings',
                fields: [
                    { key: 'default_shipping_fee', label: 'Default shipping fee (VND)', type: 'number' },
                    { key: 'free_shipping_threshold', label: 'Free shipping threshold (VND)', type: 'number' },
                    { key: 'insurance_rate', label: 'Insurance rate (%)', type: 'number', step: '0.01' },
                    { key: 'cod_fee_rate', label: 'COD fee (%)', type: 'number', step: '0.01' },
                    { key: 'max_package_weight', label: 'Max package weight (kg)', type: 'number' },
                    { key: 'delivery_time_standard', label: 'Standard delivery time (hours)', type: 'number' },
                    { key: 'delivery_time_express', label: 'Express delivery time (hours)', type: 'number' }
                ]
            },
            notification: {
                title: 'Notification settings',
                fields: [
                    { key: 'email_notifications', label: 'Email notifications', type: 'checkbox' },
                    { key: 'sms_notifications', label: 'SMS notifications', type: 'checkbox' },
                    { key: 'push_notifications', label: 'Push notifications', type: 'checkbox' },
                    { key: 'order_status_updates', label: 'Order status updates', type: 'checkbox' },
                    { key: 'payment_confirmations', label: 'Payment confirmations', type: 'checkbox' },
                    { key: 'system_alerts', label: 'System alerts', type: 'checkbox' }
                ]
            },
            security: {
                title: 'Security settings',
                fields: [
                    { key: 'session_timeout', label: 'Session timeout (minutes)', type: 'number' },
                    { key: 'password_expiry_days', label: 'Password expiry (days)', type: 'number' },
                    { key: 'max_login_attempts', label: 'Max login attempts', type: 'number' },
                    { key: 'lockout_duration', label: 'Lockout duration (minutes)', type: 'number' },
                    { key: 'two_factor_auth', label: 'Two-factor authentication', type: 'checkbox' },
                    { key: 'ip_whitelist_enabled', label: 'Allowed IP whitelist', type: 'checkbox' }
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
                showNotification('Error loading settings', 'error');
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
                    html += `<span class="ml-2 text-sm text-gray-600">Enabled</span>`;
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
                    showNotification('Settings saved successfully', 'success');
                    loadSettings(); // Reload to show updated values
                } else {
                    const error = await response.json();
                    showNotification('Error saving settings: ' + (error.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                showNotification('Error saving settings', 'error');
            }
        }

        async function resetSettings() {
            if (!confirm('Are you sure you want to reset all settings to defaults?')) {
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
                    showNotification('Settings have been reset to defaults', 'success');
                    loadSettings();
                } else {
                    showNotification('Error resetting settings', 'error');
                }
            } catch (error) {
                console.error('Error resetting settings:', error);
                showNotification('Error resetting settings', 'error');
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
                showNotification('Settings exported successfully', 'success');
            } catch (error) {
                console.error('Error exporting settings:', error);
                showNotification('Error exporting settings', 'error');
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
                showNotification('Please select a file to import', 'error');
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
                    showNotification('Settings imported successfully', 'success');
                    hideImportModal();
                    loadSettings();
                } else {
                    const error = await response.json();
                    showNotification('Error importing settings: ' + (error.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error importing settings:', error);
                showNotification('Error importing settings', 'error');
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
            if (confirm('Are you sure you want to logout?')) {
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