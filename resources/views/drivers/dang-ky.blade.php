@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.driver_registration_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.driver_registration_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.apply_now') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Benefits -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.benefits') }} {{ __('messages.for_drivers') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.high_income') }}</h3>
                    <p class="text-gray-600">{{ __('messages.income_8_15m') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.flexible_schedule') }}</h3>
                    <p class="text-gray-600">{{ __('messages.self_schedule_balance') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.comprehensive_insurance') }}</h3>
                    <p class="text-gray-600">{{ __('messages.social_insurance') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.support_247') }}</h3>
                    <p class="text-gray-600">{{ __('messages.always_ready_support') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Requirements -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.driver_requirements') }}</h2>
            
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">{{ __('messages.basic_requirements') }}</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.age_18_55') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.valid_license') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.own_vehicle') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.smartphone_required') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.clean_background') }}</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">{{ __('messages.desired_skills') }}</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.familiar_roads') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.good_communication') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.delivery_experience') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.smartphone_proficiency') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-gray-700">{{ __('messages.responsibility') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Process -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.registration_process') }}</h2>
            
            <div class="grid md:grid-cols-5 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.fill_form') }}</h3>
                    <p class="text-gray-600">{{ __('messages.personal_vehicle_info') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.submit_documents') }}</h3>
                    <p class="text-gray-600">{{ __('messages.upload_required_docs') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.interview') }}</h3>
                    <p class="text-gray-600">{{ __('messages.online_offline_interview') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.training') }}</h3>
                    <p class="text-gray-600">{{ __('messages.app_process_training') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">5</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.get_started') }}</h3>
                    <p class="text-gray-600">{{ __('messages.first_order') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_join') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.start_driver_journey') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.register_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.call_for_advice') }}: 1900 1234
                </button>
            </div>
        </div>
    </div>
</div>
@endsection