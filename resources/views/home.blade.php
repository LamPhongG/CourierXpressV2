@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Hero Banner Section - Pure image -->
<div class="relative h-screen overflow-hidden">
    <!-- Fullscreen banner image -->
    <img src="{{ asset('images/1.jpg') }}" 
         alt="CourierXpress - Professional delivery service" 
         class="w-full h-full object-cover">
</div>

<!-- Tracking Section - Centered -->
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-sans text-gray-800 mb-4">Track Your Shipment</h2>
<p class="text-xl font-sans text-gray-600 mb-12">Enter your tracking code to view real-time delivery status</p>

            
            <!-- Enhanced tracking form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
                <form id="trackingForm" class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                    @csrf
                    <input type="text" id="tracking_id" name="tracking_id" placeholder="Enter tracking code (e.g., CX123456789)..."
                           class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 text-lg transition-all duration-300">
                    <button type="submit"
                            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 font-bold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>SEARCH</span>
                        </span>
                    </button>
                </form>
                
                <!-- Tracking Result Display -->
                <div id="trackingResult" class="mt-6 hidden">
                    <!-- Success Result -->
                    <div id="trackingSuccess" class="bg-green-50 border border-green-200 rounded-xl p-6 hidden relative">
                        <button onclick="closeTrackingResult()" class="absolute top-4 right-4 text-green-600 hover:text-green-800 text-xl font-bold">&times;</button>
                        <h4 class="text-green-800 font-bold mb-3 text-lg">✅ Order found!</h4>
                        <div id="orderDetails" class="text-green-700"></div>
                    </div>
                    
                    <!-- Error Result -->
                    <div id="trackingError" class="bg-red-50 border border-red-200 rounded-xl p-6 hidden">
                        <h4 class="text-red-800 font-bold mb-3 text-lg">❌ Order not found</h4>
                        <p class="text-red-700">The tracking code does not exist in our system. Please check your code and try again.</p>
                    </div>
                    
                    <!-- Loading -->
                    <div id="trackingLoading" class="bg-blue-50 border border-blue-200 rounded-xl p-6 hidden">
                        <h4 class="text-blue-800 font-bold mb-3 text-lg">🔍 Searching...</h4>
                        <p class="text-blue-700">Please wait a moment...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
/* Enhanced modal styles for better visibility and priority */
.modal-overlay {
    background: rgba(0, 0, 0, 0.9); /* Darker overlay for better contrast */
    backdrop-filter: blur(2px); /* Reduced blur for better clarity */
    z-index: 9999; /* Highest priority */
}

.modal-content {
    background: #1f2937; /* Dark gray background */
    border: 1px solid #4b5563; /* Visible border */
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    border-radius: 1rem; /* rounded-2xl equivalent */
    max-width: 28rem; /* max-w-md equivalent */
    width: 100%;
    margin: 1rem; /* mx-4 equivalent */
    padding: 2rem; /* p-8 equivalent */
    z-index: 10000; /* Highest priority */
    position: relative;
    opacity: 1; /* Ensure full opacity */
}

/* Ensure modal is always on top */
#authModal {
    z-index: 9999;
}

/* Improve text visibility */
.modal-content h2 {
    color: #f97316; /* orange-500 */
    font-weight: 600;
}

.modal-content p {
    color: #9ca3af; /* gray-400 */
}

.modal-content input {
    background-color: #1f2937; /* gray-800 */
    border: 1px solid #4b5563; /* gray-600 */
    color: #f9fafb; /* gray-50 */
}

.modal-content input::placeholder {
    color: #9ca3af; /* gray-400 */
}

.modal-content input:focus {
    border-color: #f97316; /* orange-500 */
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3);
}

/* Tab styling */
#loginTab, #registerTab {
    font-weight: 500;
}

#loginTab {
    color: #f97316; /* orange-500 */
    border-color: #f97316; /* orange-500 */
}

#registerTab {
    color: #9ca3af; /* gray-400 */
}

#registerTab:hover {
    color: #f9fafb; /* gray-50 */
}

/* Error message styling */
.bg-red-900 {
    background-color: #7f1d1d; /* More visible red */
}

.text-red-400 {
    color: #f87171; /* More visible red text */
}

/* Test account info styling */
.bg-blue-900 {
    background-color: #1e3a8a; /* More visible blue */
    border-color: #3b82f6; /* blue-500 */
}

.text-blue-200 {
    color: #bfdbfe; /* More visible blue text */
}

.text-blue-100 {
    color: #dbeafe; /* More visible blue text */
}

</style>



<!-- Login/Register Modal -->
<div id="authModal" class="fixed inset-0 z-50 modal-overlay hidden flex items-center justify-center">
    <div class="modal-content p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <!-- Modal Tabs -->
        <div class="flex justify-center mb-6">
            <button id="loginTab" class="px-6 py-2 text-white text-red-600 font-medium border-b-2 border-orange-500" onclick="switchTab('login')">
                Sign in
            </button>
            <button id="registerTab" class="px-6 py-2 text-gray-400 text-red-600 font-medium" onclick="switchTab('register')">
                Register
            </button>
        </div>
        
        <!-- Login Form -->
        <div id="loginForm">
            <div class="text-center mb-6">
                <h2 class="text-3xl text-red-600 font-medium text-white mb-2">Sign in</h2>
                <p class="text-cyan-200 text-red-600 font-medium">Sign in to your account</p>
            </div>
            
            <!-- Test Account Info -->
            <div class="bg-blue-900 bg-opacity-50 border border-blue-400 rounded-lg p-3 mb-4">
                <h3 class="text-xs font-medium text-blue-200 mb-1">📋 Test accounts:</h3>
                <div class="text-xs text-blue-100 space-y-1">
                    <p><strong>Admin:</strong> admin@courierxpress.com | 123456</p>
                    <p><strong>Customer:</strong> customer@courierxpress.com | 123456</p>
                    <p><strong>Agent:</strong> agent@courierxpress.com | 123456</p>
                    <p><strong>Shipper:</strong> shipper@courierxpress.com | 123456</p>
                </div>
            </div>
            
            <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Email -->
                <div>
                    <input id="login_email" name="email" type="email" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Email"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <input id="login_password" name="password" type="password" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- General Errors -->
                @if(session('error'))
                    <div class="bg-red-900 bg-opacity-50 border border-red-400 rounded-md p-3">
                        <p class="text-sm text-red-200">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-900 bg-opacity-50 border border-red-400 rounded-md p-3">
                        <ul class="text-sm text-red-200 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 rounded-lg text-red-600 font-medium hover:from-orange-400 hover:to-red-400 transition-all duration-300">
                    Sign in
                </button>
            </form>
        </div>
        
        <!-- Register Form -->
        <div id="registerForm" class="hidden">
            <div class="text-center mb-6">
                <h2 class="text-3xl text-red-600 font-medium text-white mb-2">Register</h2>
                <p class="text-cyan-200 text-red-600 font-medium">Create a new account</p>
            </div>
            
            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <input id="register_name" name="name" type="text" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Full name"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <input id="register_phone" name="phone" type="tel" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Phone number"
                           value="{{ old('phone') }}">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <input id="register_email" name="email" type="email" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Email"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <input id="register_password" name="password" type="password" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Password (minimum 8 characters)">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <input id="register_password_confirmation" name="password_confirmation" type="password" required 
                           class="w-full px-4 py-3 bg-gray-800 bg-opacity-80 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Confirm password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- General Errors -->
                @if(session('error'))
                    <div class="bg-red-900 bg-opacity-50 border border-red-400 rounded-md p-3">
                        <p class="text-sm text-red-200">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-900 bg-opacity-50 border border-red-400 rounded-md p-3">
                        <ul class="text-sm text-red-200 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 rounded-lg text-red-600 font-medium hover:from-green-400 hover:to-blue-400 transition-all duration-300 neon-glow cyber-glow">
                    Register
                </button>
            </form>
        </div>
        
        <button onclick="closeAuthModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
    </div>
</div>

<script>
// Tab switching functionality
function switchTab(tab) {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (tab === 'login') {
        loginTab.classList.add('border-b-2', 'border-orange-500', 'text-white');
        loginTab.classList.remove('text-gray-400');
        registerTab.classList.remove('border-b-2', 'border-orange-500', 'text-white');
        registerTab.classList.add('text-gray-400');
        
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    } else {
        registerTab.classList.add('border-b-2', 'border-orange-500', 'text-white');
        registerTab.classList.remove('text-gray-400');
        loginTab.classList.remove('border-b-2', 'border-orange-500', 'text-white');
        loginTab.classList.add('text-gray-400');
        
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    }
}

// Close modal function
function closeAuthModal() {
    document.getElementById('authModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('authModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAuthModal();
    }
});

// Handle form validation for both forms
function validateForm(formType) {
    if (formType === 'login') {
        const email = document.getElementById('login_email').value;
        const password = document.getElementById('login_password').value;
        
        if (!email || !password) {
            alert('Please fill in all required fields');
            return false;
        }
    } else if (formType === 'register') {
        const name = document.getElementById('register_name').value;
        const email = document.getElementById('register_email').value;
        const phone = document.getElementById('register_phone').value;
        const password = document.getElementById('register_password').value;
        const confirmPassword = document.getElementById('register_password_confirmation').value;
        
        if (!name || !email || !phone || !password || !confirmPassword) {
            alert('Please fill in all required fields');
            return false;
        }
        
        if (password !== confirmPassword) {
            alert('Password confirmation does not match');
            return false;
        }
        
        if (password.length < 8) {
            alert('Password must be at least 8 characters long');
            return false;
        }
    }
    return true;
}

// Handle tracking form submission
document.getElementById('trackingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const trackingId = document.getElementById('tracking_id').value.trim();
    
    if (!trackingId) {
        alert('Please enter a tracking code');
        return;
    }
    
    // Show loading state
    showTrackingResult('loading');
    
    // Make API call to check tracking
    fetch('/api/tracking/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tracking_id: trackingId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showTrackingResult('success', data.order);
        } else {
            showTrackingResult('error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showTrackingResult('error');
    });
});

// Function to show tracking results
function showTrackingResult(type, orderData = null) {
    const resultDiv = document.getElementById('trackingResult');
    const successDiv = document.getElementById('trackingSuccess');
    const errorDiv = document.getElementById('trackingError');
    const loadingDiv = document.getElementById('trackingLoading');
    
    // Hide all result divs first
    successDiv.classList.add('hidden');
    errorDiv.classList.add('hidden');
    loadingDiv.classList.add('hidden');
    
    // Show the result container
    resultDiv.classList.remove('hidden');
    
    switch(type) {
        case 'loading':
            loadingDiv.classList.remove('hidden');
            break;
        case 'success':
            successDiv.classList.remove('hidden');
            if (orderData) {
                document.getElementById('orderDetails').innerHTML = `
                    <p><strong>Tracking number:</strong> <span class="text-sm font-medium text-blue-600">${orderData.tracking_number}</span></p>
                    <p><strong>Status:</strong> ${getStatusInEnglish(orderData.status)}</p>
                    <p><strong>From:</strong> ${orderData.pickup_address}, ${orderData.pickup_city}</p>
                    <p><strong>To:</strong> ${orderData.delivery_address}, ${orderData.delivery_city}</p>
                    <p><strong>Created at:</strong> ${formatDate(orderData.created_at)}</p>
                `;
            }
            break;
        case 'error':
            errorDiv.classList.remove('hidden');
            break;
    }
}

// Helper function to translate status to English
function getStatusInEnglish(status) {
    const statusMap = {
        'pending': 'Pending',
        'confirmed': 'Confirmed',
        'picked_up': 'Picked up',
        'in_transit': 'In transit',
        'out_for_delivery': 'Out for delivery',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled',
        'returned': 'Returned'
    };
    return statusMap[status] || status;
}

// Helper function to format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US') + ' ' + date.toLocaleTimeString('en-US');
}

// Function to close tracking result
function closeTrackingResult() {
    const resultDiv = document.getElementById('trackingResult');
    resultDiv.classList.add('hidden');
}
</script>

@if($errors->any() || session('error'))
<script>
// Keep modal open if there are validation errors
window.addEventListener('DOMContentLoaded', function() {
    document.getElementById('authModal').classList.remove('hidden');
});
</script>
@endif


@endsection