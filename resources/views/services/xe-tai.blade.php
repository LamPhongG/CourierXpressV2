@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.truck_service_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.truck_service_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.order_now') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Truck Types -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.truck_available_title') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_500kg') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_500kg_size') }}</li>
                        <li>• {{ __('messages.truck_500kg_suitable') }}</li>
                        <li>• {{ __('messages.truck_500kg_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 150.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_1ton') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_1ton_size') }}</li>
                        <li>• {{ __('messages.truck_1ton_suitable') }}</li>
                        <li>• {{ __('messages.truck_1ton_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 250.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_25ton') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_25ton_size') }}</li>
                        <li>• {{ __('messages.truck_25ton_suitable') }}</li>
                        <li>• {{ __('messages.truck_25ton_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 400.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.outstanding_advantages') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.on_time') }}</h3>
                    <p class="text-gray-600">{{ __('messages.on_time_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.professional_drivers') }}</h3>
                    <p class="text-gray-600">{{ __('messages.professional_drivers_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.comprehensive_insurance') }}</h3>
                    <p class="text-gray-600">{{ __('messages.comprehensive_insurance_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">24/7 support</h3>
                    <p class="text-gray-600">Always ready to assist you</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.need_truck_rental') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.free_consultation_quote') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.call_now') }}: {{ __('messages.phone_number') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.book_truck_online') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
