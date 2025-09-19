# 📊 CourierXpress Data Creation Guide

## 🎯 Overview
This comprehensive guide will help you create detailed sample data for the CourierXpress project to demonstrate all system features and workflows.

## 🗂️ Prerequisites
- XAMPP server running (Apache + MySQL)
- CourierXpress database created and migrated
- Laravel development server ready to run

## 📋 Step-by-Step Data Creation Process

### 1. **Start the Project**
```bash
# Navigate to project directory
cd c:\xampp\htdocs\CourierXpress\Project

# Start Laravel development server
php artisan serve
```

### 2. **Database Setup**
```bash
# Run migrations to create all tables
php artisan migrate:fresh

# Seed basic data (users, shipping services, sample orders)
php artisan db:seed
```

## 👥 User Accounts Data Structure

### Admin Accounts
```sql
-- Admin users with full system access
INSERT INTO users (name, email, phone, password, role, status, city, created_at, updated_at) VALUES
('System Administrator', 'admin@courierxpress.com', '0901000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', 'Hồ Chí Minh', NOW(), NOW()),
('Super Admin', 'superadmin@courierxpress.com', '0901000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', 'Hà Nội', NOW(), NOW());
```

### Agent Accounts
```sql
-- Regional agents for order processing
INSERT INTO users (name, email, phone, password, role, status, city, is_online, created_at, updated_at) VALUES
('Nguyễn Văn Agent HCM', 'agent.hcm@courierxpress.com', '0902000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 'active', 'Hồ Chí Minh', true, NOW(), NOW()),
('Trần Thị Agent HN', 'agent.hn@courierxpress.com', '0902000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 'active', 'Hà Nội', true, NOW(), NOW()),
('Lê Văn Agent DN', 'agent.dn@courierxpress.com', '0902000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 'active', 'Đà Nẵng', false, NOW(), NOW());
```

### Shipper Accounts
```sql
-- Active delivery shippers
INSERT INTO users (name, email, phone, password, role, status, city, is_online, created_at, updated_at) VALUES
('Shipper Nguyễn Văn A', 'shipper.a@courierxpress.com', '0903000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'shipper', 'active', 'Hồ Chí Minh', true, NOW(), NOW()),
('Shipper Trần Văn B', 'shipper.b@courierxpress.com', '0903000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'shipper', 'active', 'Hồ Chí Minh', true, NOW(), NOW()),
('Shipper Lê Thị C', 'shipper.c@courierxpress.com', '0903000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'shipper', 'active', 'Hà Nội', true, NOW(), NOW()),
('Shipper Phạm Văn D', 'shipper.d@courierxpress.com', '0903000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'shipper', 'busy', 'Đà Nẵng', true, NOW(), NOW()),
('Shipper Hoàng Thị E', 'shipper.e@courierxpress.com', '0903000005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'shipper', 'active', 'Hồ Chí Minh', false, NOW(), NOW());
```

### Customer Accounts
```sql
-- Regular customers with various profiles
INSERT INTO users (name, email, phone, password, role, status, city, created_at, updated_at) VALUES
('Khách hàng Nguyễn Văn Nam', 'customer1@example.com', '0904000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Hồ Chí Minh', NOW(), NOW()),
('Khách hàng Trần Thị Lan', 'customer2@example.com', '0904000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Hà Nội', NOW(), NOW()),
('Khách hàng Lê Văn Hùng', 'customer3@example.com', '0904000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Đà Nẵng', NOW(), NOW()),
('Doanh nghiệp ABC Corp', 'business@abccorp.com', '0904000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Hồ Chí Minh', NOW(), NOW()),
('Khách hàng VIP Premium', 'vip@premium.com', '0904000005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', 'Hồ Chí Minh', NOW(), NOW());
```

## 🚚 Shipping Services Configuration

### Complete Service Types
```sql
INSERT INTO shipping_services (name, code, description, base_price, price_per_km, price_per_kg, estimated_delivery_time, is_active, created_at, updated_at) VALUES
-- Standard services
('Giao hàng tiêu chuẩn', 'STANDARD', 'Giao hàng trong 1-2 ngày làm việc, phù hợp với hàng hóa thông thường', 20000.00, 2000.00, 5000.00, 48, true, NOW(), NOW()),
('Giao hàng nhanh', 'EXPRESS', 'Giao hàng trong ngày hoặc 24h, ưu tiên xử lý', 35000.00, 3000.00, 7000.00, 24, true, NOW(), NOW()),
('Giao hàng siêu tốc', 'SUPER_EXPRESS', 'Giao hàng trong 2-4 giờ, dành cho hàng khẩn cấp', 50000.00, 5000.00, 10000.00, 4, true, NOW(), NOW()),

-- Specialized services
('Giao hàng đồ ăn', 'FOOD_DELIVERY', 'Dịch vụ chuyên biệt cho đồ ăn, giữ nhiệt độ', 15000.00, 1500.00, 3000.00, 2, true, NOW(), NOW()),
('Giao hàng dễ vỡ', 'FRAGILE', 'Dịch vụ cho hàng dễ vỡ, đóng gói đặc biệt', 40000.00, 4000.00, 8000.00, 24, true, NOW(), NOW()),
('Giao hàng giá trị cao', 'HIGH_VALUE', 'Dành cho hàng hóa có giá trị cao, bảo hiểm kèm theo', 60000.00, 6000.00, 12000.00, 12, true, NOW(), NOW()),
('Giao hàng tài liệu', 'DOCUMENT', 'Chuyên giao tài liệu, hợp đồng quan trọng', 25000.00, 2500.00, 1000.00, 24, true, NOW(), NOW());
```

## 📦 Comprehensive Order Data

### Orders with Various Status and Scenarios
```sql
-- Recent orders showcasing different statuses
INSERT INTO orders (tracking_number, user_id, agent_id, shipper_id, pickup_name, pickup_phone, pickup_address, pickup_ward, pickup_district, pickup_city, pickup_latitude, pickup_longitude, delivery_name, delivery_phone, delivery_address, delivery_ward, delivery_district, delivery_city, delivery_latitude, delivery_longitude, package_type, weight, length, width, height, value, cod_amount, notes, shipping_service_id, shipping_fee, insurance_fee, total_fee, payment_method, payment_status, status, confirmed_at, assigned_at, pickup_at, picked_up_at, in_transit_at, delivering_at, completed_at, created_at, updated_at) VALUES

-- Order 1: Completed delivery with rating
('CX2025090601', 1, 1, 1, 'Cửa hàng Tech Store', '0901111111', '123 Nguyễn Trãi, P.1', 'Phường 1', 'Quận 1', 'Hồ Chí Minh', 10.762622, 106.660172, 'Anh Minh Khách hàng', '0902222222', '456 Lê Lợi, P.Bến Nghé', 'Phường Bến Nghé', 'Quận 1', 'Hồ Chí Minh', 10.770000, 106.700000, 'parcel', 2.50, 30.00, 20.00, 15.00, 2000000.00, 0.00, 'Laptop Dell XPS 13 - Cẩn thận với hàng điện tử', 2, 45000.00, 20000.00, 65000.00, 'bank_transfer', 'paid', 'delivered', '2025-09-05 08:00:00', '2025-09-05 09:00:00', '2025-09-05 10:00:00', '2025-09-05 10:30:00', '2025-09-05 11:00:00', '2025-09-05 14:00:00', '2025-09-05 14:30:00', '2025-09-05 07:00:00', NOW()),

-- Order 2: Currently in transit
('CX2025090602', 2, 1, 2, 'Nhà thuốc An Khang', '0903333333', '789 Cách Mạng Tháng 8, P.6', 'Phường 6', 'Quận 3', 'Hồ Chí Minh', 10.782000, 106.692000, 'Bà Lan Nguyễn', '0904444444', '321 Hai Bà Trưng, P.Đa Kao', 'Phường Đa Kao', 'Quận 1', 'Hồ Chí Minh', 10.788000, 106.698000, 'document', 0.30, 25.00, 18.00, 2.00, 50000.00, 25000.00, 'Thuốc prescription - Giao trực tiếp cho bệnh nhân', 3, 55000.00, 0.00, 55000.00, 'cash', 'pending', 'in_transit', '2025-09-06 08:30:00', '2025-09-06 09:30:00', '2025-09-06 10:00:00', '2025-09-06 10:15:00', '2025-09-06 11:00:00', NULL, NULL, '2025-09-06 08:00:00', NOW()),

-- Order 3: Assigned to shipper
('CX2025090603', 3, 2, 3, 'Cửa hàng Hoa Tươi', '0905555555', '159 Đồng Khởi, P.Bến Nghé', 'Phường Bến Nghé', 'Quận 1', 'Hồ Chí Minh', 10.771000, 106.704000, 'Anh Tuấn Office', '0906666666', '753 Nguyễn Văn Linh, P.Tân Thuận Đông', 'Phường Tân Thuận Đông', 'Quận 7', 'Hồ Chí Minh', 10.740000, 106.720000, 'fragile', 1.20, 40.00, 30.00, 25.00, 800000.00, 0.00, 'Hoa khai trương - Giao trước 10h sáng', 5, 65000.00, 8000.00, 73000.00, 'e_wallet', 'paid', 'assigned', '2025-09-06 07:00:00', '2025-09-06 08:00:00', NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:30:00', NOW()),

-- Order 4: Pending confirmation
('CX2025090604', 4, NULL, NULL, 'Công ty TNHH ABC', '0907777777', '951 Trần Hưng Đạo, P.5', 'Phường 5', 'Quận 5', 'Hồ Chí Minh', 10.755000, 106.675000, 'Ms. Sarah Johnson', '0908888888', '147 Pasteur, P.Võ Thị Sáu', 'Phường Võ Thị Sáu', 'Quận 3', 'Hồ Chí Minh', 10.779000, 106.695000, 'document', 0.50, 30.00, 21.00, 1.50, 100000.00, 0.00, 'Hợp đồng quốc tế - Cần giao trong giờ hành chính', 7, 35000.00, 0.00, 35000.00, 'bank_transfer', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 09:00:00', NOW()),

-- Order 5: Food delivery in progress
('CX2025090605', 5, 1, 1, 'Nhà hàng Phở Hà Nội', '0909999999', '357 Điện Biên Phủ, P.4', 'Phường 4', 'Quận 3', 'Hồ Chí Minh', 10.786000, 106.691000, 'Anh Minh Đỗ', '0911111111', '852 Nguyễn Thị Minh Khai, P.Đa Kao', 'Phường Đa Kao', 'Quận 1', 'Hồ Chí Minh', 10.789000, 106.699000, 'food', 1.80, 35.00, 25.00, 15.00, 150000.00, 150000.00, 'Cơm trưa cho văn phòng - Giữ ấm', 4, 18000.00, 0.00, 18000.00, 'cash', 'pending', 'pickup', '2025-09-06 11:00:00', '2025-09-06 11:15:00', '2025-09-06 11:30:00', NULL, NULL, NULL, NULL, '2025-09-06 10:45:00', NOW());
```

## 📍 Order Status History Data

### Detailed Status Tracking
```sql
INSERT INTO order_status_history (order_id, user_id, status, notes, latitude, longitude, created_at, updated_at) VALUES
-- Order 1 complete history
(1, 1, 'pending', 'Đơn hàng được tạo bởi khách hàng', NULL, NULL, '2025-09-05 07:00:00', '2025-09-05 07:00:00'),
(1, 1, 'confirmed', 'Agent xác nhận và xử lý đơn hàng', NULL, NULL, '2025-09-05 08:00:00', '2025-09-05 08:00:00'),
(1, 1, 'assigned', 'Đã phân công shipper Nguyễn Văn A', NULL, NULL, '2025-09-05 09:00:00', '2025-09-05 09:00:00'),
(1, 1, 'pickup', 'Shipper đang di chuyển đến điểm lấy hàng', 10.760000, 106.658000, '2025-09-05 10:00:00', '2025-09-05 10:00:00'),
(1, 1, 'picked_up', 'Đã lấy hàng thành công tại Tech Store', 10.762622, 106.660172, '2025-09-05 10:30:00', '2025-09-05 10:30:00'),
(1, 1, 'in_transit', 'Hàng đang được vận chuyển', 10.765000, 106.665000, '2025-09-05 11:00:00', '2025-09-05 11:00:00'),
(1, 1, 'delivering', 'Shipper đang giao hàng cho khách', 10.768000, 106.698000, '2025-09-05 14:00:00', '2025-09-05 14:00:00'),
(1, 1, 'delivered', 'Giao hàng thành công cho Anh Minh', 10.770000, 106.700000, '2025-09-05 14:30:00', '2025-09-05 14:30:00'),

-- Order 2 current progress
(2, 2, 'pending', 'Đơn hàng thuốc prescription được tạo', NULL, NULL, '2025-09-06 08:00:00', '2025-09-06 08:00:00'),
(2, 1, 'confirmed', 'Agent xác nhận đơn hàng thuốc', NULL, NULL, '2025-09-06 08:30:00', '2025-09-06 08:30:00'),
(2, 1, 'assigned', 'Phân công cho shipper Trần Văn B', NULL, NULL, '2025-09-06 09:30:00', '2025-09-06 09:30:00'),
(2, 2, 'pickup', 'Đang đi lấy hàng tại nhà thuốc', 10.780000, 106.690000, '2025-09-06 10:00:00', '2025-09-06 10:00:00'),
(2, 2, 'picked_up', 'Đã lấy thuốc thành công', 10.782000, 106.692000, '2025-09-06 10:15:00', '2025-09-06 10:15:00'),
(2, 2, 'in_transit', 'Đang vận chuyển thuốc cho bệnh nhân', 10.785000, 106.695000, '2025-09-06 11:00:00', '2025-09-06 11:00:00');
```

## ⭐ Rating and Feedback Data

### Customer Reviews
```sql
INSERT INTO ratings (order_id, customer_id, shipper_id, rating, comment, delivery_rating, communication_rating, timeliness_rating, created_at, updated_at) VALUES
-- Excellent service rating
(1, 1, 1, 5, 'Dịch vụ xuất sắc! Shipper rất nhiệt tình và chuyên nghiệp. Hàng được giao đúng hẹn và trong tình trạng hoàn hảo. Sẽ sử dụng lại dịch vụ.', 5, 5, 5, '2025-09-05 15:00:00', '2025-09-05 15:00:00');
```

## 📱 Order Tracking Data

### Real-time Tracking Information
```sql
INSERT INTO order_trackings (order_id, status, notes, location, updated_by, created_at, updated_at) VALUES
-- Order 1 complete tracking
(1, 'confirmed', 'Đơn hàng được xác nhận bởi agent HCM', 'Trung tâm xử lý Quận 1', 1, '2025-09-05 08:00:00', '2025-09-05 08:00:00'),
(1, 'assigned', 'Phân công shipper Nguyễn Văn A', 'Trung tâm phân phối', 1, '2025-09-05 09:00:00', '2025-09-05 09:00:00'),
(1, 'pickup', 'Shipper đang di chuyển đến Tech Store', 'Trên đường đến 123 Nguyễn Trãi', 1, '2025-09-05 10:00:00', '2025-09-05 10:00:00'),
(1, 'picked_up', 'Đã nhận hàng từ Tech Store', '123 Nguyễn Trãi, Quận 1', 1, '2025-09-05 10:30:00', '2025-09-05 10:30:00'),
(1, 'in_transit', 'Đang vận chuyển đến Quận 1', 'Đường Hai Bà Trưng', 1, '2025-09-05 11:00:00', '2025-09-05 11:00:00'),
(1, 'delivering', 'Chuẩn bị giao hàng cho khách', 'Gần 456 Lê Lợi', 1, '2025-09-05 14:00:00', '2025-09-05 14:00:00'),
(1, 'delivered', 'Giao hàng thành công', '456 Lê Lợi, Quận 1', 1, '2025-09-05 14:30:00', '2025-09-05 14:30:00'),

-- Order 2 current tracking
(2, 'confirmed', 'Đơn hàng thuốc được xác nhận', 'Trung tâm xử lý', 1, '2025-09-06 08:30:00', '2025-09-06 08:30:00'),
(2, 'assigned', 'Phân công shipper chuyên biệt', 'Trung tâm phân phối', 1, '2025-09-06 09:30:00', '2025-09-06 09:30:00'),
(2, 'pickup', 'Đang lấy hàng tại nhà thuốc', 'Nhà thuốc An Khang', 2, '2025-09-06 10:00:00', '2025-09-06 10:00:00'),
(2, 'picked_up', 'Đã nhận thuốc an toàn', '789 Cách Mạng Tháng 8', 2, '2025-09-06 10:15:00', '2025-09-06 10:15:00'),
(2, 'in_transit', 'Đang giao thuốc cho bệnh nhân', 'Trên đường Hai Bà Trưng', 2, '2025-09-06 11:00:00', '2025-09-06 11:00:00');
```

## 🔧 Data Creation Script

### Automated Data Creation
Create a file `create_comprehensive_data.php` in the project root:

```php
<?php
// File: create_comprehensive_data.php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Database configuration
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'courierxpress',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Run all data creation queries
echo "Creating comprehensive data for CourierXpress...\n";

// Execute all SQL statements from above sections
// (Include all the INSERT statements from each section)

echo "✅ All comprehensive data created successfully!\n";
echo "📊 Data Summary:\n";
echo "   - Users: 12 accounts (2 admin, 3 agents, 5 shippers, 5 customers)\n";
echo "   - Shipping Services: 7 service types\n";
echo "   - Orders: 5 orders with various statuses\n";
echo "   - Status History: Complete tracking for all orders\n";
echo "   - Ratings: Customer feedback data\n";
echo "   - Order Trackings: Real-time tracking information\n";
?>
```

## 🚀 Quick Start Commands

### Execute All Data Creation
```bash
# Method 1: Using Laravel Seeder (Recommended)
php artisan migrate:fresh --seed

# Method 2: Using custom script
php create_comprehensive_data.php

# Method 3: Manual SQL execution via phpMyAdmin
# Copy and paste SQL queries from each section above
```

## 🎯 Testing Scenarios

### Login and Test Each Role
1. **Admin Access:**
   - Login: admin@courierxpress.com / 123456
   - Test: Dashboard statistics, user management, system settings

2. **Agent Workflow:**
   - Login: agent.hcm@courierxpress.com / 123456
   - Test: Order processing, status updates, customer communication

3. **Shipper Operations:**
   - Login: shipper.a@courierxpress.com / 123456
   - Test: Order pickup, delivery tracking, status updates

4. **Customer Experience:**
   - Login: customer1@example.com / 123456
   - Test: Order creation, tracking, rating system

## 📊 Expected Results

After creating this comprehensive data, you will have:
- **12 User Accounts** across all roles with realistic profiles
- **7 Shipping Services** covering various delivery needs
- **5 Sample Orders** demonstrating complete workflow scenarios
- **Complete Order Tracking** with GPS coordinates and status history
- **Customer Ratings** for service quality evaluation
- **Real-time Data** for testing all system features

## 🔍 Verification Steps

### Confirm Data Creation Success
1. Check user accounts: `SELECT * FROM users;`
2. Verify orders: `SELECT * FROM orders;`
3. Review tracking: `SELECT * FROM order_trackings;`
4. Test login for each role type
5. Verify dashboard data displays correctly
6. Test order creation and tracking workflow

---
*This guide provides comprehensive data for testing all CourierXpress features and workflows.*
*Last Updated: 2025-09-06*