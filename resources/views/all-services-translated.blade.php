@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.individual_services') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.diverse_delivery_transport') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.order_now') }}
                </button>
            </div>
        </div>
    </div>

    <!-- All Services Grid - Exactly like your image -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Shopping Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.shopping_delivery') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.shopping_delivery_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.same_day_delivery') }}</li>
                        <li>• {{ __('messages.check_before_payment') }}</li>
                        <li>• {{ __('messages.delivery_fee_from') }}</li>
                        <li>• {{ __('messages.cod_support') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <!-- Document Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.document_delivery') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.document_delivery_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.within_2_hours') }}</li>
                        <li>• {{ __('messages.absolute_security') }}</li>
                        <li>• {{ __('messages.recipient_confirmation') }}</li>
                        <li>• {{ __('messages.real_time_tracking') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <!-- Gift Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.gift_delivery') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.gift_delivery_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.beautiful_packaging') }}</li>
                        <li>• {{ __('messages.greeting_card') }}</li>
                        <li>• {{ __('messages.timely_delivery') }}</li>
                        <li>• {{ __('messages.photo_confirmation') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <!-- Personal Shopping -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.personal_shopping') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.personal_shopping_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.buy_on_request') }}</li>
                        <li>• {{ __('messages.product_consultation') }}</li>
                        <li>• {{ __('messages.flexible_payment') }}</li>
                        <li>• {{ __('messages.reasonable_service_fee') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <!-- Personal Item Moving -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.personal_item_moving') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.personal_item_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.packing_support') }}</li>
                        <li>• {{ __('messages.item_protection') }}</li>
                        <li>• {{ __('messages.suitable_vehicle') }}</li>
                        <li>• {{ __('messages.reasonable_price') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <!-- Scheduled Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.scheduled_delivery_service') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.scheduled_delivery_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.flexible_scheduling') }}</li>
                        <li>• {{ __('messages.advance_reminder') }}</li>
                        <li>• {{ __('messages.on_time_delivery') }}</li>
                        <li>• {{ __('messages.easy_changes') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works - 100% Translated -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.how_it_works') }}</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.order_now') }}</h3>
                    <p class="text-gray-600">{{ __('messages.order_via_app_website') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.confirmation') }}</h3>
                    <p class="text-gray-600">{{ __('messages.receive_confirmation_driver_info') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.tracking') }}</h3>
                    <p class="text-gray-600">{{ __('messages.real_time_tracking') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.completion') }}</h3>
                    <p class="text-gray-600">{{ __('messages.receive_and_payment') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_experience') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.download_app_or_call') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.download_app') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.call_now') }}: {{ __('messages.phone_number') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
