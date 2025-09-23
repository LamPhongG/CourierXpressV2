@extends('layouts.unified')

@section('title', 'Track Shipment - CourierXpress')

@section('navigation')
    <a href="/" class="text-gray-700 hover:text-red-600">Home</a>
    <a href="/tracking" class="text-red-600 font-medium">Track</a>
    <a href="/login" class="text-gray-700 hover:text-red-600">Sign in</a>
    <a href="/register" class="text-gray-700 hover:text-red-600">Sign up</a>
@endsection

@section('head')
    <style>
        .search-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .tracking-step {
            position: relative;
        }
        .tracking-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: #e5e7eb;
            z-index: 1;
        }
        .tracking-step:last-child::before {
            display: none;
        }
        .tracking-step.completed::before {
            background: #10b981;
        }
        .step-icon {
            position: relative;
            z-index: 2;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 3px solid #e5e7eb;
            background: white;
        }
        .tracking-step.completed .step-icon {
            border-color: #10b981;
            background: #10b981;
            color: white;
        }
        .result-card {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
    </style>
@endsection

@section('content')
    <!-- Search Section -->
    <div class="search-section py-16 mb-8">
        <div class="text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Track Shipment</h1>
            <p class="text-xl text-blue-100 mb-8">Enter your tracking number to follow your package status</p>
            
            <!-- Search Form -->
            <div class="max-w-xl mx-auto">
                <form id="trackingForm" class="flex gap-3">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="trackingNumber" 
                            name="tracking_number"
                            placeholder="Enter tracking number (e.g., CX000001)"
                            class="w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-transparent"
                            required
                            value="{{ $trackingNumber ?? '' }}"
                        >
                    </div>
                    <button 
                        type="submit" 
                        id="searchBtn"
                        class="px-6 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 transition-colors pulse-animation"
                    >
                        <span id="searchText">Search</span>
                    </button>
                </form>
            </div>

            <!-- Quick Search Examples -->
            <div class="mt-8">
                <p class="text-blue-100 mb-4">Example tracking numbers:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <button onclick="quickSearch('CX000001')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000001
                    </button>
                    <button onclick="quickSearch('CX000002')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000002
                    </button>
                    <button onclick="quickSearch('CX000003')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000003
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Prompt -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
            <div>
                <h3 class="text-blue-800 font-medium">Sign in for a better experience</h3>
                <p class="text-blue-600">Sign in to view order history, manage your profile, and more.</p>
                <div class="mt-2">
                    <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Sign in →</a>
                    <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">Create an account →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">Searching for order information...</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
            <div>
                <h3 class="text-red-800 font-medium">Order not found</h3>
                <p class="text-red-600" id="errorMessage">Please check the tracking number and try again.</p>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" class="hidden space-y-6">
        <!-- Order Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sender & Receiver Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user text-green-500 mr-2"></i>
                    Sender/Receiver Information
                </h3>
                <div id="senderReceiverInfo">
                    <!-- Details will be inserted here -->
                </div>
            </div>

            <!-- Package Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-box text-blue-500 mr-2"></i>
                    Package Information
                </h3>
                <div id="packageInfo">
                    <!-- Package details will be inserted here -->
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-route text-purple-500 mr-2"></i>
                Shipping Timeline
            </h3>
            <div id="timeline" class="space-y-8">
                <!-- Timeline steps will be inserted here -->
            </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-headset text-orange-500 mr-2"></i>
                Need help?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-blue-500 text-2xl mb-2"></i>
                    <p class="font-medium">Hotline</p>
                    <p class="text-sm text-gray-600">1900 1234</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-green-500 text-2xl mb-2"></i>
                    <p class="font-medium">Email</p>
                    <p class="text-sm text-gray-600">support@courierxpress.com</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fab fa-facebook-messenger text-purple-500 text-2xl mb-2"></i>
                    <p class="font-medium">Chat</p>
                    <p class="text-sm text-gray-600">Facebook Messenger</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('trackingForm');
        const searchText = document.getElementById('searchText');
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const resultsSection = document.getElementById('resultsSection');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            
            if (!trackingNumber) {
                alert('Please enter a tracking number');
                return;
            }
            
            searchOrder(trackingNumber);
        });

        // Auto search if tracking number is provided in URL
        const trackingNumber = document.getElementById('trackingNumber').value;
        if (trackingNumber) {
            searchOrder(trackingNumber);
        }
    });
    
    function quickSearch(trackingNumber) {
        document.getElementById('trackingNumber').value = trackingNumber;
        searchOrder(trackingNumber);
    }
    
    function searchOrder(trackingNumber) {
        // Show loading state
        showLoading();
        
        // Make API request
        fetch('/api/tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ tracking_number: trackingNumber })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                displayResults(data.data);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('An error occurred while searching. Please try again.');
        });
    }
    
    function showLoading() {
        document.getElementById('searchBtn').disabled = true;
        document.getElementById('searchText').textContent = 'Searching...';
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('errorState').classList.add('hidden');
        document.getElementById('resultsSection').classList.add('hidden');
    }
    
    function hideLoading() {
        document.getElementById('searchBtn').disabled = false;
        document.getElementById('searchText').textContent = 'Search';
        document.getElementById('loadingState').classList.add('hidden');
    }
    
    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorState').classList.remove('hidden');
    }
    
    function displayResults(data) {
        // Display sender/receiver info
        const senderReceiverInfo = document.getElementById('senderReceiverInfo');
        senderReceiverInfo.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">Sender</h4>
                    <p class="text-sm"><strong>Name:</strong> ${data.order.pickup_name}</p>
                    <p class="text-sm"><strong>Phone:</strong> ${data.order.pickup_phone}</p>
                    <p class="text-sm"><strong>Address:</strong> ${data.order.pickup_address}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Receiver</h4>
                    <p class="text-sm"><strong>Name:</strong> ${data.order.delivery_name}</p>
                    <p class="text-sm"><strong>Phone:</strong> ${data.order.delivery_phone}</p>
                    <p class="text-sm"><strong>Address:</strong> ${data.order.delivery_address}</p>
                </div>
            </div>
        `;
        
        // Display package info
        const packageInfo = document.getElementById('packageInfo');
        packageInfo.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm"><strong>Type:</strong> ${data.order.package_type || 'Unknown'}</p>
                    <p class="text-sm"><strong>Weight:</strong> ${data.order.weight || 'N/A'} kg</p>
                </div>
                <div>
                    <p class="text-sm"><strong>COD Amount:</strong> ${Number(data.order.cod_amount || 0).toLocaleString('en-US')} ₫</p>
                    <p class="text-sm"><strong>Shipping Fee:</strong> ${Number(data.order.shipping_fee || 0).toLocaleString('en-US')} ₫</p>
                </div>
            </div>
        `;
        
        // Display timeline
        displayTimeline(data.timeline);
        
        document.getElementById('resultsSection').classList.remove('hidden');
        
        // Update URL without page reload
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('tracking_number', data.order.tracking_number);
        window.history.pushState({}, '', newUrl);
    }
    
    function displayTimeline(timeline) {
        const timelineContainer = document.getElementById('timeline');
        
        if (!timeline || timeline.length === 0) {
            // Create default timeline based on order status
            timeline = [
                {
                    status: 'Order placed successfully',
                    description: 'The order has been created and is pending processing',
                    timestamp: new Date().toLocaleString('en-US'),
                    completed: true,
                    icon: 'fa-check'
                },
                {
                    status: 'Order confirmed',
                    description: 'The order has been confirmed and is being prepared for the shipper',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-clipboard-check'
                },
                {
                    status: 'Picking up',
                    description: 'The shipper is on the way to the pickup address',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-truck-pickup'
                },
                {
                    status: 'In transit',
                    description: 'The package is being transported to the delivery address',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-shipping-fast'
                },
                {
                    status: 'Delivered successfully',
                    description: 'The package has been delivered to the recipient',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-check-circle'
                }
            ];
        }
        
        timelineContainer.innerHTML = timeline.map((step, index) => `
            <div class="tracking-step ${step.completed ? 'completed' : ''} flex items-center">
                <div class="step-icon">
                    <i class="fas ${step.icon || 'fa-circle'} ${step.completed ? 'text-white' : 'text-gray-400'}"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="font-semibold text-gray-900">${step.status}</h4>
                    <p class="text-gray-600">${step.description}</p>
                    ${step.timestamp ? `<p class="text-sm text-gray-500">${step.timestamp}</p>` : ''}
                </div>
            </div>
        `).join('');
    }
</script>
@endsection