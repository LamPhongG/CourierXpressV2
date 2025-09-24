@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.community_support_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.community_support_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.join_community') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Community Features -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.community_features') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.discussion_forum') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.discussion_forum_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.qa_service') }}</li>
                        <li>• {{ __('messages.tips_tricks') }}</li>
                        <li>• {{ __('messages.product_feedback') }}</li>
                        <li>• {{ __('messages.user_connect') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.join_discussion') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.tutorial_library') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.tutorial_library_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.app_guide') }}</li>
                        <li>• {{ __('messages.video_tutorials') }}</li>
                        <li>• {{ __('messages.detailed_faq') }}</li>
                        <li>• {{ __('messages.best_practices') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_guides') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.online_support') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.online_support_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.live_chat') }}</li>
                        <li>• {{ __('messages.247_hotline') }}</li>
                        <li>• {{ __('messages.email_support') }}</li>
                        <li>• {{ __('messages.remote_assistance') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.contact_support') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.reward_program') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.reward_program_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.activity_points') }}</li>
                        <li>• {{ __('messages.discount_vouchers') }}</li>
                        <li>• {{ __('messages.special_gifts') }}</li>
                        <li>• {{ __('messages.vip_benefits') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_rewards') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.community_events') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.community_events_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.training_workshops') }}</li>
                        <li>• {{ __('messages.regular_meetups') }}</li>
                        <li>• {{ __('messages.specialized_webinars') }}</li>
                        <li>• {{ __('messages.contests_giveaways') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.view_events') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">{{ __('messages.ideas_feedback') }}</h3>
                    <p class="text-gray-600 text-center mb-6">{{ __('messages.ideas_feedback_desc') }}</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('messages.new_features') }}</li>
                        <li>• {{ __('messages.bug_reports') }}</li>
                        <li>• {{ __('messages.service_reviews') }}</li>
                        <li>• {{ __('messages.idea_voting') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.submit_feedback') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Community Stats -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.courierxpress_community') }}</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">50K+</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">{{ __('messages.members') }}</h3>
                    <p class="text-gray-600">{{ __('messages.active_users') }}</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">1.2K</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">{{ __('messages.posts') }}</h3>
                    <p class="text-gray-600">{{ __('messages.per_week') }}</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">98%</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">{{ __('messages.satisfied') }}</h3>
                    <p class="text-gray-600">{{ __('messages.positive_ratings') }}</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">24/7</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">{{ __('messages.support') }}</h3>
                    <p class="text-gray-600">{{ __('messages.always_ready') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.recent_activities') }}</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.latest_discussions') }}</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.how_to_track') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('messages.by_user_a') }} • {{ __('messages.2_hours_ago') }}</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.tracking_discussion') }}</p>
                        </div>

                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.delivery_saving_tips') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('messages.by_user_b') }} • {{ __('messages.5_hours_ago') }}</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.saving_tips_discussion') }}</p>
                        </div>

                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.new_features_feedback') }}</h4>
                            <p class="text-sm text-gray-600">{{ __('messages.by_user_c') }} • {{ __('messages.1_day_ago') }}</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.features_feedback_discussion') }}</p>
                        </div>
                    </div>
                    <button class="mt-6 text-orange-600 font-semibold hover:text-teal-700">{{ __('messages.view_all') }} →</button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.upcoming_events') }}</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.logistics_workshop') }}</h4>
                            <p class="text-sm text-gray-600">15/12/2024 • 14:00 - 16:00</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.workshop_desc') }}</p>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.q4_meetup') }}</h4>
                            <p class="text-sm text-gray-600">20/12/2024 • 18:00 - 21:00</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.meetup_desc') }}</p>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">{{ __('messages.feature_contest') }}</h4>
                            <p class="text-sm text-gray-600">01/01/2025 - 31/01/2025</p>
                            <p class="text-sm text-gray-700 mt-2">{{ __('messages.contest_desc') }}</p>
                        </div>
                    </div>
                    <button class="mt-6 text-orange-600 font-semibold hover:text-teal-700">{{ __('messages.event_calendar') }} →</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.join_community_today') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.connect_with_users') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.sign_up_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.learn_more') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection