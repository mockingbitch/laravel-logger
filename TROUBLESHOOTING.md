# Troubleshooting Guide

## Vấn đề: "Invalid argument" khi truy cập /logger

### Nguyên nhân có thể:

1. **Routes không được đăng ký đúng cách**
2. **Middleware 'web' không được load**
3. **Views không tồn tại**
4. **Service Provider không được đăng ký**

### Các bước debug:

#### 1. Kiểm tra routes đã được đăng ký
```bash
php artisan route:list | grep logger
```

#### 2. Clear cache
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

#### 3. Kiểm tra service provider
```bash
php artisan config:show app.providers | grep logger
```

#### 4. Debug bằng helper functions
```php
// Trong tinker
php artisan tinker

// Kiểm tra routes
>>> logger_routes_debug()

// Kiểm tra tổng quan
>>> logger_debug_info()
```

#### 5. Kiểm tra views
```php
// Trong tinker
>>> view()->exists('logger::index')
>>> view()->exists('logger::detail')
```

### Giải pháp:

#### 1. Đảm bảo package được cài đúng
```bash
composer require phongtran/logger
```

#### 2. Publish config và views (nếu cần)
```bash
php artisan vendor:publish --tag=logger
```

#### 3. Chạy migrations
```bash
php artisan migrate
```

#### 4. Kiểm tra .env
```env
APP_KEY=base64:your-app-key-here
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Test routes:

```bash
# Test bằng curl
curl -I http://your-app.test/logger

# Test với verbose
curl -v http://your-app.test/logger
```

### Expected Output:

Khi hoạt động đúng, bạn sẽ thấy:
- Routes: `/logger` và `/logger/{id}` trong `php artisan route:list`
- Response: JSON hoặc HTML từ controller
- No "Invalid argument" errors

### Nếu vẫn có vấn đề:

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Check web server logs**: Apache/Nginx error logs
3. **Enable debug mode**: `APP_DEBUG=true` trong .env
4. **Check PHP error logs**: `/var/log/php_errors.log` 