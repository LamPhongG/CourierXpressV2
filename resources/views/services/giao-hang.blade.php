@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.delivery_service_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.delivery_service_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.order_now') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.why_choose_us') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_fast_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_fast_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_safe_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_safe_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_tracking_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_tracking_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Types -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.delivery_types_title') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_standard') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_standard_time') }}</p>
                    <p class="text-orange-600 font-bold">Từ 15.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_fast_service') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.same_day') }}</p>
                    <p class="text-orange-600 font-bold">Từ 25.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_express_service') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_express_time') }}</p>
                    <p class="text-orange-600 font-bold">Từ 45.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_cod') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_cod_desc') }}</p>
                    <p class="text-orange-600 font-bold">{{ __('messages.delivery_cod_fee') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_deliver') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.contact_for_quote') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.contact_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.download_app') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
