<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function getSettings()
    {
        // For now, return default settings since we don't have a Settings model yet
        $settings = [
            'general' => [
                'app_name' => config('app.name', 'CourierXpress'),
                'app_url' => config('app.url', 'http://localhost'),
                'timezone' => config('app.timezone', 'UTC'),
                'locale' => config('app.locale', 'en'),
                'debug_mode' => config('app.debug', false),
            ],
            'business' => [
                'company_name' => 'CourierXpress',
                'company_address' => '123 Business Street, Ho Chi Minh City',
                'company_phone' => '+84 123 456 789',
                'company_email' => 'contact@courierxpress.com',
                'business_hours' => '8:00 AM - 6:00 PM',
                'working_days' => 'Monday - Saturday',
            ],
            'shipping' => [
                'default_shipping_fee' => 50000,
                'free_shipping_threshold' => 500000,
                'insurance_rate' => 0.02,
                'cod_fee_rate' => 0.01,
                'max_package_weight' => 50,
                'delivery_time_standard' => 24,
                'delivery_time_express' => 12,
            ],
            'notification' => [
                'email_notifications' => true,
                'sms_notifications' => true,
                'push_notifications' => true,
                'order_status_updates' => true,
                'payment_confirmations' => true,
                'system_alerts' => true,
            ],
            'security' => [
                'session_timeout' => 120,
                'password_expiry_days' => 90,
                'max_login_attempts' => 5,
                'lockout_duration' => 15,
                'two_factor_auth' => false,
                'ip_whitelist_enabled' => false,
            ]
        ];

        return response()->json($settings);
    }

    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|in:general,business,shipping,notification,security',
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = $request->category;
        $settings = $request->settings;

        // Validate specific settings based on category
        $categoryValidation = $this->getCategoryValidationRules($category);
        $categoryValidator = Validator::make($settings, $categoryValidation);

        if ($categoryValidator->fails()) {
            return response()->json(['errors' => $categoryValidator->errors()], 422);
        }

        // In a real implementation, you would save to database
        // For now, we'll just cache the settings temporarily
        Cache::put("admin_settings_{$category}", $settings, 3600);

        return response()->json([
            'message' => 'Settings updated successfully',
            'category' => $category,
            'settings' => $settings
        ]);
    }

    private function getCategoryValidationRules($category)
    {
        $rules = [
            'general' => [
                'app_name' => 'required|string|max:255',
                'app_url' => 'required|url',
                'timezone' => 'required|string',
                'locale' => 'required|string|in:en,vi,hi',
                'debug_mode' => 'boolean',
            ],
            'business' => [
                'company_name' => 'required|string|max:255',
                'company_address' => 'required|string|max:500',
                'company_phone' => 'required|string|max:20',
                'company_email' => 'required|email',
                'business_hours' => 'required|string',
                'working_days' => 'required|string',
            ],
            'shipping' => [
                'default_shipping_fee' => 'required|numeric|min:0',
                'free_shipping_threshold' => 'required|numeric|min:0',
                'insurance_rate' => 'required|numeric|min:0|max:1',
                'cod_fee_rate' => 'required|numeric|min:0|max:1',
                'max_package_weight' => 'required|numeric|min:1',
                'delivery_time_standard' => 'required|numeric|min:1',
                'delivery_time_express' => 'required|numeric|min:1',
            ],
            'notification' => [
                'email_notifications' => 'boolean',
                'sms_notifications' => 'boolean',
                'push_notifications' => 'boolean',
                'order_status_updates' => 'boolean',
                'payment_confirmations' => 'boolean',
                'system_alerts' => 'boolean',
            ],
            'security' => [
                'session_timeout' => 'required|numeric|min:5|max:480',
                'password_expiry_days' => 'required|numeric|min:30|max:365',
                'max_login_attempts' => 'required|numeric|min:3|max:10',
                'lockout_duration' => 'required|numeric|min:5|max:60',
                'two_factor_auth' => 'boolean',
                'ip_whitelist_enabled' => 'boolean',
            ]
        ];

        return $rules[$category] ?? [];
    }

    public function resetSettings(Request $request)
    {
        $category = $request->get('category');
        
        if ($category) {
            Cache::forget("admin_settings_{$category}");
            return response()->json(['message' => "Settings for {$category} have been reset to defaults"]);
        }

        // Reset all categories
        $categories = ['general', 'business', 'shipping', 'notification', 'security'];
        foreach ($categories as $cat) {
            Cache::forget("admin_settings_{$cat}");
        }

        return response()->json(['message' => 'All settings have been reset to defaults']);
    }

    public function exportSettings()
    {
        $settings = $this->getSettings()->getData();
        
        $fileName = 'courierxpress_settings_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($settings)
                   ->header('Content-Type', 'application/json')
                   ->header('Content-Disposition', "attachment; filename={$fileName}");
    }

    public function importSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings_file' => 'required|file|mimes:json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $file = $request->file('settings_file');
            $content = file_get_contents($file->getRealPath());
            $settings = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON file'], 422);
            }

            // Validate and save each category
            $categories = ['general', 'business', 'shipping', 'notification', 'security'];
            foreach ($categories as $category) {
                if (isset($settings[$category])) {
                    $rules = $this->getCategoryValidationRules($category);
                    $validator = Validator::make($settings[$category], $rules);
                    
                    if (!$validator->fails()) {
                        Cache::put("admin_settings_{$category}", $settings[$category], 3600);
                    }
                }
            }

            return response()->json(['message' => 'Settings imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import settings: ' . $e->getMessage()], 500);
        }
    }
}