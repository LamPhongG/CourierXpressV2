@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section - Completely Translated -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.moving_service_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.moving_service_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.book_service') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Service Packages - Completely Translated -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.moving_packages_title') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Basic Package -->
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-500">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.basic_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 500K</p>
                        <p class="text-gray-500">{{ __('messages.basic_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.truck_500kg_1ton') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.2_staff') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.inner_city_transport') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.basic_insurance') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.choose_package') }}
                    </button>
                </div>

                <!-- Standard Package -->
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-600 relative">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-orange-600 text-white px-4 py-1 rounded-full text-sm font-bold">{{ __('messages.popular') }}</span>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.standard_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 1.2M</p>
                        <p class="text-gray-500">{{ __('messages.standard_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.truck_1ton_25ton') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.4_staff') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.free_packing') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.comprehensive_insurance_full') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.basic_old_house_cleaning') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.choose_package') }}
                    </button>
                </div>

                <!-- VIP Package -->
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-700">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.vip_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 2.5M</p>
                        <p class="text-gray-500">{{ __('messages.vip_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.large_truck_as_needed') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.6_plus_staff') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.packing_arranging_new_house') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.premium_insurance') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.complete_cleaning_both_houses') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.choose_package') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Section - Completely Translated -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.moving_process_title') }}</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.survey') }}</h3>
                    <p class="text-gray-600">{{ __('messages.survey_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.packing') }}</h3>
                    <p class="text-gray-600">{{ __('messages.packing_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.transport') }}</h3>
                    <p class="text-gray-600">{{ __('messages.transport_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.completion') }}</h3>
                    <p class="text-gray-600">{{ __('messages.completion_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Customer Services - Completely Translated -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.individual_services') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
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
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0V3a1 1 0 011-1h6a1 1 0 011 1v1M7 4h10l1 16H6L7 4z"></path>
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
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.order_now') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enterprise Solutions - Completely Translated -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.business_solutions') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.ecommerce_fulfillment') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.ecommerce_fulfillment_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.inventory_management') }}</li>
                        <li>• {{ __('messages.professional_packaging') }}</li>
                        <li>• {{ __('messages.multichannel_delivery') }}</li>
                        <li>• {{ __('messages.detailed_reports') }}</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.supply_chain_management') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.supply_chain_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.demand_planning') }}</li>
                        <li>• {{ __('messages.vendor_management') }}</li>
                        <li>• {{ __('messages.route_optimization') }}</li>
                        <li>• {{ __('messages.realtime_monitoring') }}</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.b2b_distribution') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.b2b_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• {{ __('messages.scheduled_delivery') }}</li>
                        <li>• {{ __('messages.large_order_processing') }}</li>
                        <li>• {{ __('messages.flexible_payment') }}</li>
                        <li>• {{ __('messages.document_support') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section - Completely Translated -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_move') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.free_consultation_special_offer') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.contact_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.see_more_services') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
