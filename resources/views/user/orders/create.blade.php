    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Enhanced form styling for better visibility */
        .form-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .form-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .form-section-title i {
            margin-right: 0.75rem;
            color: #3b82f6;
            font-size: 1.125rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem !important;
            border: 2px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            font-size: 1rem !important;
            line-height: 1.5;
            background-color: #ffffff !important;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }
        
        .form-input:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
            background-color: #fafbff !important;
        }
        
        .form-input:hover {
            border-color: #9ca3af !important;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600 !important;
            color: #374151 !important;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }
        
        .service-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease-in-out;
            background: #ffffff;
            cursor: pointer;
        }
        
        .service-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .service-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }
        
        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .border-red-500 {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
    </style>

<!-- Page Header -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center">
            <a href="/user/orders" class="mr-4 text-white hover:text-blue-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold mb-2">Tạo đơn hàng mới</h1>
                <p class="text-blue-100">Nhập thông tin đầy đủ để tạo đơn hàng</p>
            </div>
        </div>
    </div>
</div>

    <!-- Main Content -->
    <div class="space-y-6">
        <form id="createOrderForm" class="space-y-6">
            <!-- Pickup Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Thông tin lấy hàng
                </h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="pickupName" class="form-label required">Tên người gửi</label>
                        <input type="text" id="pickupName" name="pickup_name" required class="form-input">
                    </div>
                    <div>
                        <label for="pickupPhone" class="form-label required">Số điện thoại</label>
                        <input type="tel" id="pickupPhone" name="pickup_phone" required class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="pickupAddress" class="form-label required">Địa chỉ</label>
                        <input type="text" id="pickupAddress" name="pickup_address" required class="form-input" placeholder="Nhập địa chỉ lấy hàng đầy đủ">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Vị trí trên bản đồ (kéo thả để chỉnh sửa)</label>
                    <div id="pickupMap" class="h-60 rounded-lg border-2 border-gray-200"></div>
                    <input type="hidden" id="pickupLat" name="pickup_lat">
                    <input type="hidden" id="pickupLng" name="pickup_lng">
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-shipping-fast"></i>
                    Thông tin giao hàng
                </h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="deliveryName" class="form-label required">Tên người nhận</label>
                        <input type="text" id="deliveryName" name="delivery_name" required class="form-input">
                    </div>
                    <div>
                        <label for="deliveryPhone" class="form-label required">Số điện thoại</label>
                        <input type="tel" id="deliveryPhone" name="delivery_phone" required class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="deliveryAddress" class="form-label required">Địa chỉ</label>
                        <input type="text" id="deliveryAddress" name="delivery_address" required class="form-input" placeholder="Nhập địa chỉ giao hàng đầy đủ">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Vị trí trên bản đồ (kéo thả để chỉnh sửa)</label>
                    <div id="deliveryMap" class="h-60 rounded-lg border-2 border-gray-200"></div>
                    <input type="hidden" id="deliveryLat" name="delivery_lat">
                    <input type="hidden" id="deliveryLng" name="delivery_lng">
                </div>
            </div>

            <!-- Package Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-box"></i>
                    Thông tin kiện hàng
                </h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="packageType" class="form-label required">Loại hàng</label>
                        <select id="packageType" name="package_type" required class="form-input">
                            <option value="" disabled selected>Chọn loại hàng</option>
                            <option value="document">Tài liệu</option>
                            <option value="parcel">Bưu kiện</option>
                            <option value="food">Thực phẩm</option>
                            <option value="fragile">Hàng dễ vỡ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div>
                        <label for="weight" class="form-label required">Khối lượng (kg)</label>
                        <input type="number" step="0.1" min="0.1" id="weight" name="weight" required class="form-input" placeholder="Ví dụ: 2.5">
                    </div>
                    <div>
                        <label for="value" class="form-label required">Giá trị hàng (VNĐ)</label>
                        <input type="number" min="0" id="value" name="value" required class="form-input" placeholder="Ví dụ: 500000">
                    </div>
                    <div>
                        <label for="codAmount" class="form-label">Thu hộ COD (VNĐ)</label>
                        <input type="number" min="0" id="codAmount" name="cod_amount" class="form-input" placeholder="Để trống nếu không có">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" class="form-input" placeholder="Ghi chú thêm về kiện hàng (nếu có)"></textarea>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-truck"></i>
                    Dịch vụ vận chuyển
                </h3>
                <div class="space-y-4">
                    <div id="shippingServices" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- Shipping services will be dynamically added here -->
                        <div class="text-center py-8 text-gray-500 sm:col-span-3">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Đang tính toán phí vận chuyển...</p>
                            <p class="text-sm mt-1">Vui lòng nhập đầy đủ thông tin để xem các gói dịch vụ</p>
                        </div>
                    </div>
                    <div id="shippingFee" class="text-xl font-medium text-gray-900 hidden bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                        Phí vận chuyển: <span id="shippingFeeAmount" class="text-blue-600 font-bold">0</span> đ
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Tạo đơn hàng
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let pickupMap, deliveryMap;
    let pickupMarker, deliveryMarker;
    
    $(document).ready(function() {
        initializeMaps();
        setupAddressSearch();
        setupServiceSelection();
        setupFormSubmission();
    });

    function initializeMaps() {
        // Initialize pickup map
        pickupMap = L.map('pickupMap').setView([10.7769, 106.7009], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMap);
        pickupMarker = L.marker([10.7769, 106.7009], {draggable: true}).addTo(pickupMap);
        
        // Initialize delivery map
        deliveryMap = L.map('deliveryMap').setView([10.7769, 106.7009], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(deliveryMap);
        deliveryMarker = L.marker([10.7769, 106.7009], {draggable: true}).addTo(deliveryMap);

        // Update coordinates on marker drag
        pickupMarker.on('dragend', function(e) {
            const latlng = e.target.getLatLng();
            $('#pickupLat').val(latlng.lat);
            $('#pickupLng').val(latlng.lng);
            updateShippingServices();
        });

        deliveryMarker.on('dragend', function(e) {
            const latlng = e.target.getLatLng();
            $('#deliveryLat').val(latlng.lat);
            $('#deliveryLng').val(latlng.lng);
            updateShippingServices();
        });
    }

    function setupAddressSearch() {
        // Setup geocoding for pickup address
        $('#pickupAddress').on('change', function() {
            geocodeAddress($(this).val(), pickupMap, pickupMarker, 'pickup');
        });

        // Setup geocoding for delivery address
        $('#deliveryAddress').on('change', function() {
            geocodeAddress($(this).val(), deliveryMap, deliveryMarker, 'delivery');
        });
    }

    function geocodeAddress(address, map, marker, type) {
        // Use Nominatim for geocoding
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                    
                    $(`#${type}Lat`).val(lat);
                    $(`#${type}Lng`).val(lng);
                    
                    updateShippingServices();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function setupServiceSelection() {
        // Update shipping services when package details change
        $('#weight, #value, #packageType').on('change', function() {
            updateShippingServices();
        });
    }

    function updateShippingServices() {
        const data = {
            pickup_lat: $('#pickupLat').val(),
            pickup_lng: $('#pickupLng').val(),
            delivery_lat: $('#deliveryLat').val(),
            delivery_lng: $('#deliveryLng').val(),
            weight: $('#weight').val(),
            value: $('#value').val(),
            package_type: $('#packageType').val()
        };

        // Skip if required data is missing
        if (!data.pickup_lat || !data.delivery_lat || !data.weight) {
            return;
        }

        fetch('/api/shipping/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                // If API fails, show default services
                showDefaultServices();
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.services) {
                renderShippingServices(data.services);
            } else {
                showDefaultServices();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDefaultServices();
        });
    }
    
    function showDefaultServices() {
        const defaultServices = [
            { id: 'standard', name: 'Gối chuẩn', description: 'Giao hàng trong 1-2 ngày', fee: 30000, estimated_time: '1-2 ngày', recommended: true },
            { id: 'fast', name: 'Gối nhanh', description: 'Giao hàng trong ngày', fee: 50000, estimated_time: 'Trong ngày', recommended: false },
            { id: 'express', name: 'Gối hỏa tốc', description: 'Giao hàng trong 2-4 giờ', fee: 80000, estimated_time: '2-4 giờ', recommended: false }
        ];
        renderShippingServices(defaultServices);
    }
    
    function renderShippingServices(services) {
        const servicesContainer = $('#shippingServices');
        servicesContainer.empty();

        services.forEach((service, index) => {
            const serviceCard = `
                <div class="service-card ${service.recommended ? 'selected' : ''}" data-service="${service.id}">
                    ${service.recommended ? '<div class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-blue-500 text-white px-2 py-1 text-xs rounded-full font-semibold">Khuyến nghị</div>' : ''}
                    <div class="flex items-center">
                        <input type="radio" name="shipping_service" value="${service.id}" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300" ${service.recommended ? 'checked' : ''}>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-gray-900 text-lg">${service.name}</label>
                                <span class="text-lg font-bold text-blue-600">${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(service.fee)}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">${service.description}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span>Thời gian: ${service.estimated_time}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            servicesContainer.append(serviceCard);
        });

        // Add click handlers for service cards
        $('.service-card').on('click', function() {
            $('.service-card').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
        });

        // Show shipping fee section
        $('#shippingFee').removeClass('hidden');
        const initialService = services.find(s => s.recommended) || services[0];
        $('#shippingFeeAmount').text(new Intl.NumberFormat('vi-VN').format(initialService.fee));

        // Update fee when service selection changes
        $('input[name="shipping_service"]').on('change', function() {
            const selectedService = services.find(s => s.id === $(this).val());
            if (selectedService) {
                $('#shippingFeeAmount').text(new Intl.NumberFormat('vi-VN').format(selectedService.fee));
            }
        });
    }

    function setupFormSubmission() {
        $('#createOrderForm').on('submit', function(e) {
            e.preventDefault();

            // Validate required fields with better visual feedback
            const requiredFields = ['pickupName', 'pickupPhone', 'pickupAddress', 'deliveryName', 'deliveryPhone', 'deliveryAddress', 'packageType', 'weight', 'value'];
            let isValid = true;
            let firstErrorField = null;
            
            // Clear all previous error states
            requiredFields.forEach(field => {
                $('#' + field).removeClass('border-red-500');
            });
            
            // Check each required field
            requiredFields.forEach(field => {
                const element = $('#' + field);
                const value = element.val();
                
                if (!value || value.toString().trim() === '') {
                    isValid = false;
                    element.addClass('border-red-500');
                    
                    if (!firstErrorField) {
                        firstErrorField = element;
                    }
                }
            });
            
            // Check shipping service selection
            if (!$('input[name="shipping_service"]:checked').length) {
                isValid = false;
                // Scroll to shipping services section
                if (!firstErrorField) {
                    $('html, body').animate({
                        scrollTop: $('#shippingServices').offset().top - 100
                    }, 500);
                }
            }
            
            if (!isValid) {
                if (firstErrorField) {
                    // Focus and scroll to first error field
                    firstErrorField.focus();
                    $('html, body').animate({
                        scrollTop: firstErrorField.offset().top - 100
                    }, 500);
                }
                
                alert('⚠️ Vui lòng điền đầy đủ các thông tin bắt buộc (đánh dấu *)!');
                return;
            }

            const formData = {
                pickup_name: $('#pickupName').val(),
                pickup_phone: $('#pickupPhone').val(),
                pickup_address: $('#pickupAddress').val(),
                pickup_lat: $('#pickupLat').val() || 10.7769,
                pickup_lng: $('#pickupLng').val() || 106.7009,
                delivery_name: $('#deliveryName').val(),
                delivery_phone: $('#deliveryPhone').val(),
                delivery_address: $('#deliveryAddress').val(),
                delivery_lat: $('#deliveryLat').val() || 10.7769,
                delivery_lng: $('#deliveryLng').val() || 106.7009,
                package_type: $('#packageType').val(),
                weight: $('#weight').val(),
                value: $('#value').val(),
                cod_amount: $('#codAmount').val() || 0,
                notes: $('#notes').val(),
                shipping_service: $('input[name="shipping_service"]:checked').val() || 'standard'
            };

            // Disable submit button to prevent double submission
            const submitBtn = $(this).find('button[type="submit"]');
            const originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true)
                     .html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang tạo...')
                     .addClass('opacity-75');

            fetch('/api/user/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                return response.json().then(data => {
                    if (response.ok) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Lỗi khi tạo đơn hàng');
                    }
                });
            })
            .then(data => {
                if (data.success && data.data && data.data.id) {
                    // Success - show detailed message
                    const message = `Đơn hàng được tạo thành công!\nMã vận đơn: ${data.data.tracking_number || data.data.id}`;
                    alert(message);
                    
                    // Redirect to order details or orders list
                    window.location.href = `/user/orders/${data.data.id}`;
                } else {
                    throw new Error(data.message || 'Phản hồi từ server không hợp lệ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                let errorMessage = 'Có lỗi xảy ra khi tạo đơn hàng!';
                
                if (error.message.includes('422') || error.message.includes('validation')) {
                    errorMessage = 'Vui lòng kiểm tra lại các thông tin đã nhập!';
                } else if (error.message.includes('401') || error.message.includes('403')) {
                    errorMessage = 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại!';
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                alert(errorMessage);
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.prop('disabled', false)
                         .html(originalHtml)
                         .removeClass('opacity-75');
            });
        });
    }
</script>
