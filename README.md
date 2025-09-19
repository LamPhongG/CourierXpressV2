# CourierXpress - Hệ thống quản lý vận chuyển

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)

CourierXpress là một hệ thống quản lý vận chuyển toàn diện được xây dựng trên Laravel, hỗ trợ đa ngôn ngữ và nhiều vai trò người dùng.

## ✨ Tính năng chính

### 🎯 Quản lý đơn hàng
- Tạo và theo dõi đơn hàng theo thời gian thực
- Hệ thống trạng thái đơn hàng chi tiết (11 trạng thái)
- Tính toán phí vận chuyển tự động
- Hỗ trợ thanh toán COD

### 👥 Hệ thống đa vai trò
- **Admin**: Quản lý toàn hệ thống
- **Agent**: Xử lý đơn hàng tại chi nhánh
- **Shipper**: Giao hàng và cập nhật trạng thái
- **Customer**: Tạo đơn và theo dõi

### 🌍 Đa ngôn ngữ
- Tiếng Việt (mặc định)
- Tiếng Anh  
- Tiếng Hindi

### 📱 Giao diện responsive
- Thiết kế mobile-first với Tailwind CSS
- Dashboard riêng cho từng vai trò
- Real-time tracking với bản đồ

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS 4.0, Alpine.js
- **Database**: MySQL 8.0+
- **Build Tools**: Vite, NPM
- **Testing**: PHPUnit

## 📋 Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.0
- Web server (Apache/Nginx)

## 🚀 Cài đặt

### 1. Clone repository
```bash
git clone https://github.com/your-username/courierxpress.git
cd courierxpress/Project
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa file `.env` với thông tin database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=courierxpress
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Tạo database và chạy migration
```bash
php artisan migrate
php artisan db:seed
```

### 5. Build assets
```bash
npm run build
# Hoặc cho development:
npm run dev
```

### 6. Khởi chạy server
```bash
php artisan serve
```

Truy cập http://localhost:8000

## 👤 Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với:

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | admin@courierxpress.com | **123456** |
| Agent | agent@courierxpress.com | **123456** |
| Shipper | shipper@courierxpress.com | **123456** |
| Customer | customer@courierxpress.com | **123456** |

### 🔗 Quick Test
Truy cập [/test-accounts](http://localhost:8000/test-accounts) để xem thông tin tài khoản và test nhanh!

## 🏗️ Cấu trúc dự án

```
Project/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controllers chính
│   │   │   ├── Auth/            # Xác thực
│   │   │   └── Shipper/         # Controllers cho shipper
│   │   └── Middleware/          # Middleware tùy chỉnh
│   ├── Models/                  # Eloquent models
│   └── Helpers/                 # Helper functions
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/
│   ├── views/                   # Blade templates
│   ├── lang/                    # Đa ngôn ngữ
│   ├── css/                     # Stylesheets
│   └── js/                      # JavaScript
└── routes/
    ├── web.php                  # Web routes
    └── shipper.php              # Shipper routes
```

## 🔄 Quy trình đơn hàng

1. **Pending** - Đơn hàng được tạo
2. **Confirmed** - Agent xác nhận
3. **Assigned** - Phân công shipper
4. **Pickup** - Shipper đến lấy hàng
5. **Picked Up** - Đã lấy hàng
6. **In Transit** - Đang vận chuyển
7. **Delivering** - Đang giao hàng
8. **Delivered** - Giao thành công
9. **Failed** - Giao thất bại
10. **Returned** - Trả hàng
11. **Cancelled** - Hủy đơn

## 🧪 Chạy tests

```bash
php artisan test
```

## 📝 API Documentation

### Authentication
- `POST /login` - Đăng nhập
- `POST /register` - Đăng ký
- `POST /logout` - Đăng xuất

### Orders
- `GET /api/orders` - Danh sách đơn hàng
- `POST /api/orders` - Tạo đơn hàng
- `GET /api/orders/{id}` - Chi tiết đơn hàng
- `PUT /api/orders/{id}/status` - Cập nhật trạng thái

### Tracking
- `GET /tracking?tracking_id={id}` - Theo dõi đơn hàng

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 License

Dự án này được phát hành dưới [MIT license](https://opensource.org/licenses/MIT).

## 📞 Liên hệ

- Email: info@courierxpress.com
- Website: https://courierxpress.com
- Support: support@courierxpress.com

---

<p align="center">
  Made with ❤️ by CourierXpress Team
</p>
