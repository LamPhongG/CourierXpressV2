@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.driver_support_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.driver_support_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.contact_support') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Support for Drivers -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.support_for_drivers') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.technical_support') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.resolve_app_device') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.app_login_errors') }}</li>
                        <li>• {{ __('messages.gps_issues') }}</li>
                        <li>• {{ __('messages.payment_problems') }}</li>
                        <li>• {{ __('messages.app_updates') }}</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.financial_support') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.handle_income_payment') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.check_earnings') }}</li>
                        <li>• {{ __('messages.handle_payment_disputes') }}</li>
                        <li>• {{ __('messages.withdrawal_guidance') }}</li>
                        <li>• {{ __('messages.earnings_reports') }}</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.emergency_support') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.24_7_emergency') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.traffic_accidents') }}</li>
                        <li>• {{ __('messages.motorbike_breakdown') }}</li>
                        <li>• {{ __('messages.lost_damaged_parcels') }}</li>
                        <li>• {{ __('messages.customer_disputes') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.driver_support_contacts') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.driver_hotline') }}</h3>
                    <p class="text-3xl font-bold text-orange-600 mb-2">1900 5678</p>
                    <p class="text-gray-600">{{ __('messages.24_7_driver_priority') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.driver_email') }}</h3>
                    <p class="text-orange-600 font-semibold mb-2">driver@courierxpress.vn</p>
                    <p class="text-gray-600">{{ __('messages.response_1_hour') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.support_chat') }}</h3>
                    <button class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.chat_now') }}
                    </button>
                    <p class="text-gray-600 mt-2">{{ __('messages.inside_driver_app') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.always_by_your_side') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.professional_driver_support') }}</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                {{ __('messages.call_now') }}: 1900 5678
            </button>
        </div>
    </div>
</div>
@endsection