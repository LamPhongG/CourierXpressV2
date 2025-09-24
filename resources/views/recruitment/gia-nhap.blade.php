@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.join_us_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.join_us_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.see_all') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Job Categories -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.career_opportunities') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.technology') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.develop_products') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.software_engineer') }}</li>
                        <li>• {{ __('messages.mobile_developer') }}</li>
                        <li>• {{ __('messages.devops_engineer') }}</li>
                        <li>• {{ __('messages.data_scientist') }}</li>
                        <li>• {{ __('messages.ui_ux_designer') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_positions') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.operations') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.manage_optimize') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.operations_manager_role') }}</li>
                        <li>• {{ __('messages.supply_chain_specialist') }}</li>
                        <li>• {{ __('messages.warehouse_manager') }}</li>
                        <li>• {{ __('messages.quality_assurance') }}</li>
                        <li>• {{ __('messages.process_improvement') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_positions') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.business') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.market_development') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.sales_manager') }}</li>
                        <li>• {{ __('messages.business_development') }}</li>
                        <li>• {{ __('messages.customer_success') }}</li>
                        <li>• {{ __('messages.marketing_specialist') }}</li>
                        <li>• {{ __('messages.account_manager') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_positions') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.why_choose_courierxpress') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.attractive_salary') }}</h3>
                    <p class="text-gray-600">{{ __('messages.competitive_salary') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.fast_growth') }}</h3>
                    <p class="text-gray-600">{{ __('messages.clear_promotion') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.comprehensive_training') }}</h3>
                    <p class="text-gray-600">{{ __('messages.training_program') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.great_culture') }}</h3>
                    <p class="text-gray-600">{{ __('messages.friendly_environment') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Process -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.recruitment_process') }}</h2>
            
            <div class="grid md:grid-cols-5 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.apply') }}</h3>
                    <p class="text-gray-600">{{ __('messages.submit_cv') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.cv_screening') }}</h3>
                    <p class="text-gray-600">{{ __('messages.evaluate_profile') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.interview') }}</h3>
                    <p class="text-gray-600">{{ __('messages.interview_rounds') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.probation') }}</h3>
                    <p class="text-gray-600">{{ __('messages.probation_period') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">5</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.official') }}</h3>
                    <p class="text-gray-600">{{ __('messages.sign_contract') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_join_team') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.send_cv_today') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.send_cv_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.email') }}: career@courierxpress.vn
                </button>
            </div>
        </div>
    </div>
</div>
@endsection