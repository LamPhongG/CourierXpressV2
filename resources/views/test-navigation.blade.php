@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Test Navigation Links</h2>
            
            <div class="space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold mb-4">Test Login & Register Links</h3>
                    
                    <!-- Test Link 1: Direct URL -->
                    <div class="mb-4">
                        <a href="/login" 
                           class="block w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 mb-2">
                            Direct URL: /login
                        </a>
                        <a href="/register" 
                           class="block w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                            Direct URL: /register
                        </a>
                    </div>
                    
                    <!-- Test Link 2: Laravel route() helper -->
                    <div class="mb-4">
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700 mb-2">
                            Laravel route('login'): {{ route('login') }}
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                            Laravel route('register'): {{ route('register') }}
                        </a>
                    </div>
                    
                    <!-- Test Link 3: With JavaScript event -->
                    <div class="mb-4">
                        <button onclick="window.location.href='/login'" 
                                class="block w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 mb-2">
                            JavaScript: window.location.href='/login'
                        </button>
                        <button onclick="window.location.href='/register'" 
                                class="block w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                            JavaScript: window.location.href='/register'
                        </button>
                    </div>
                    
                    <!-- Debug Information -->
                    <div class="mt-6 p-4 bg-gray-50 rounded text-xs text-left">
                        <h4 class="font-semibold mb-2">Debug Information:</h4>
                        <p><strong>Current URL:</strong> {{ url()->current() }}</p>
                        <p><strong>Base URL:</strong> {{ url('/') }}</p>
                        <p><strong>Route Login:</strong> {{ route('login') }}</p>
                        <p><strong>Route Register:</strong> {{ route('register') }}</p>
                        <p><strong>Language:</strong> {{ app()->getLocale() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
console.log('Navigation test page loaded');
console.log('Login route:', '{{ route("login") }}');
console.log('Register route:', '{{ route("register") }}');

// Test if click events work
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && (e.target.href.includes('login') || e.target.href.includes('register'))) {
        console.log('Navigation link clicked:', e.target.href);
        console.log('Target element:', e.target);
    }
});
</script>
@endsection