# Debug Guide for Logger Package

## Vấn đề: Route /logger trả về 404 Not Found

### Bước 1: Kiểm tra Service Providers
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Bước 2: Kiểm tra routes đã đăng ký
```bash
php artisan route:list | grep logger
```

### Bước 3: Debug bằng helper functions
```php
// Trong tinker hoặc controller
php artisan tinker

// Kiểm tra routes
>>> logger_routes_debug()

// Kiểm tra tổng quan package
>>> logger_debug_info()
```

### Bước 4: Kiểm tra Service Provider
```php
// Kiểm tra xem service provider có được đăng ký không
>>> app()->bound('logger')
>>> config('logger')
```

### Bước 5: Kiểm tra views
```php
// Kiểm tra view có tồn tại không
>>> view()->exists('logger::index')
>>> view()->exists('logger::detail')
```

### Bước 6: Test trực tiếp
```bash
# Test route bằng curl
curl -I http://your-app.test/logger

# Test với verbose
curl -v http://your-app.test/logger
```

## Các vấn đề thường gặp:

### 1. Service Provider không được đăng ký
**Giải pháp**: Kiểm tra `config/app.php` hoặc `bootstrap/providers.php` (Laravel 11+)

### 2. Routes bị cache
**Giải pháp**: Clear route cache
```bash
php artisan route:clear
```

### 3. Views không được load
**Giải pháp**: Kiểm tra namespace views trong controller

### 4. Middleware issues
**Giải pháp**: Kiểm tra middleware 'web' có được áp dụng không

## Debug Commands:

```bash
# Clear all caches
php artisan optimize:clear

# Check route cache
php artisan route:cache

# List all routes
php artisan route:list

# Check config
php artisan config:show logger
```

## Expected Output:

Khi debug thành công, bạn sẽ thấy:
- Routes: `/logger` và `/logger/{id}`
- Views: `logger::index` và `logger::detail`
- Service: `logger` bound to container
- Config: `logger` config loaded 