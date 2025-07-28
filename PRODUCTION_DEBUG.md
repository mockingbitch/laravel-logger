# Production Debug Guide

## Vấn đề: Routes không hoạt động trong production

### Kiểm tra nhanh:

1. **Kiểm tra package đã được cài đúng chưa**:
```bash
composer show phongtran/logger
```

2. **Kiểm tra service provider đã được đăng ký**:
```bash
php artisan config:show app.providers | grep logger
```

3. **Kiểm tra routes đã được đăng ký**:
```bash
php artisan route:list | grep logger
```

### Nếu routes không xuất hiện:

#### Bước 1: Clear tất cả cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

#### Bước 2: Kiểm tra composer autoload
```bash
composer dump-autoload
```

#### Bước 3: Kiểm tra service provider trong composer.json
```json
{
    "extra": {
        "laravel": {
            "providers": [
                "phongtran\\Logger\\LoggerServiceProvider"
            ]
        }
    }
}
```

#### Bước 4: Kiểm tra thủ công trong config/app.php (Laravel 10)
```php
'providers' => [
    // ...
    phongtran\Logger\LoggerServiceProvider::class,
],
```

#### Bước 5: Kiểm tra bootstrap/providers.php (Laravel 11+)
```php
<?php

return [
    // ...
    phongtran\Logger\LoggerServiceProvider::class,
];
```

### Debug chi tiết:

#### 1. Kiểm tra logs
```bash
tail -f storage/logs/laravel.log
```

#### 2. Enable debug mode
```env
APP_DEBUG=true
```

#### 3. Kiểm tra web server logs
```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

#### 4. Test routes bằng curl
```bash
curl -v http://your-domain.com/logger
```

### Các vấn đề thường gặp:

#### 1. Service Provider không được đăng ký
**Triệu chứng**: Routes không xuất hiện trong `php artisan route:list`
**Giải pháp**: Thêm service provider vào config/app.php hoặc bootstrap/providers.php

#### 2. Cache vấn đề
**Triệu chứng**: Thay đổi không có hiệu lực
**Giải pháp**: Clear tất cả cache

#### 3. Autoload vấn đề
**Triệu chứng**: Class not found errors
**Giải pháp**: `composer dump-autoload`

#### 4. Web server configuration
**Triệu chứng**: 404 errors từ web server
**Giải pháp**: Kiểm tra .htaccess (Apache) hoặc nginx config

### Test manual:

#### 1. Tạo route test trong routes/web.php
```php
Route::get('/test-logger', function() {
    return 'Logger package is working!';
});
```

#### 2. Test bằng tinker
```php
php artisan tinker

// Kiểm tra routes
>>> app('router')->getRoutes()->get('GET')->filter(fn($r) => str_contains($r->uri(), 'logger'))->count()

// Kiểm tra service provider
>>> app()->bound('logger')
```

### Expected Output:

Khi hoạt động đúng:
- `php artisan route:list | grep logger` sẽ hiển thị 2 routes
- `curl http://your-domain.com/logger` sẽ trả về response (không phải 404)
- Không có lỗi trong logs

### Nếu vẫn không hoạt động:

1. **Kiểm tra Laravel version compatibility**
2. **Kiểm tra PHP version**
3. **Kiểm tra web server configuration**
4. **Kiểm tra file permissions**
5. **Contact support với thông tin debug** 