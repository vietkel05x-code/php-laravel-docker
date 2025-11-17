# ✅ Mixed Content Error - FIXED

## Vấn đề
Khi deploy lên Render.com, ứng dụng gặp lỗi Mixed Content:
- CSS, JS không load (blocked by browser)
- Forms target HTTP thay vì HTTPS
- Database và session không hoạt động

## Giải pháp đã áp dụng

### 1. ✅ Force HTTPS trong AppServiceProvider
**File:** `app/Providers/AppServiceProvider.php`

```php
public function boot(): void
{
    // Force HTTPS in production
    if (config('app.env') === 'production') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
```

### 2. ✅ Trust Proxies
**File:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Trust proxies for HTTPS detection on Render
    $middleware->trustProxies(at: '*');
})
```

### 3. ✅ Cấu hình Environment Variables
**File:** `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://elearning-gath.onrender.com

# Session với HTTPS
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Database PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=dpg-d4dc4uqdbo4c73doamc0-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=elearning_db_a5rt
DB_USERNAME=elearning_db_a5rt_user
DB_PASSWORD=YSzrFOeZSQfO7IrXJE9yuDlI4tRnAVhV
```

### 4. ✅ Migrations cho PostgreSQL
Tất cả migrations đã được cập nhật:
- `enum()` → `string()`
- `dateTime()` → `timestamp()` / `timestamps()`
- `mediumText()` / `longText()` → `text()`
- MySQL FULLTEXT → PostgreSQL GIN index

### 5. ✅ Deploy Script
**File:** `scripts/00-laravel-deploy.sh`

```bash
#!/usr/bin/env bash
composer install --no-dev --working-dir=/var/www/html
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan storage:link
php artisan migrate --force
```

## Cách Deploy lên Render

### Bước 1: Cấu hình Environment Variables trên Render Dashboard

```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:0nEn094hGODjECKGoeQyPI0RncvuvOFhKypxcGrlJIE=
APP_DEBUG=false
APP_URL=https://elearning-gath.onrender.com

DB_CONNECTION=pgsql
DB_HOST=dpg-d4dc4uqdbo4c73doamc0-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=elearning_db_a5rt
DB_USERNAME=elearning_db_a5rt_user
DB_PASSWORD=YSzrFOeZSQfO7IrXJE9yuDlI4tRnAVhV

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Bước 2: Build Command
```bash
chmod +x scripts/00-laravel-deploy.sh && bash scripts/00-laravel-deploy.sh
```

### Bước 3: Start Command
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Bước 4: Verify
1. Mở https://elearning-gath.onrender.com
2. Kiểm tra Console (F12) - không còn lỗi Mixed Content
3. CSS/JS load thành công
4. Thử đăng ký/đăng nhập

## Checklist sau khi deploy

- [ ] ✅ APP_URL bắt đầu bằng `https://`
- [ ] ✅ APP_ENV=production
- [ ] ✅ APP_DEBUG=false
- [ ] ✅ SESSION_SECURE_COOKIE=true
- [ ] ✅ Database PostgreSQL connected
- [ ] ✅ Migrations đã chạy
- [ ] ✅ Storage link created
- [ ] ✅ Cache cleared
- [ ] ✅ No Mixed Content errors trong Console
- [ ] ✅ CSS/JS load qua HTTPS
- [ ] ✅ Forms submit qua HTTPS
- [ ] ✅ Session hoạt động

## Troubleshooting

### Vẫn còn lỗi Mixed Content?

1. **Clear cache trên Render:**
```bash
php artisan config:clear && php artisan cache:clear
```

2. **Kiểm tra APP_URL:**
- Phải bắt đầu bằng `https://`
- Không có trailing slash

3. **Redeploy:**
- Commit changes
- Push to git
- Trigger manual deploy trên Render

### CSS/JS không load?

1. **Kiểm tra files tồn tại:**
```bash
ls -la public/css/
ls -la public/js/
```

2. **Set permissions:**
```bash
chmod -R 755 public/
```

3. **Check asset() helper đang dùng HTTPS:**
- View source → tìm `<link rel="stylesheet"`
- URL phải là `https://...`

### Database connection failed?

1. **Verify credentials trong Render Dashboard**
2. **Check PostgreSQL service đang chạy**
3. **Test connection:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

## Tài liệu tham khảo

- [Laravel Documentation - URL Generation](https://laravel.com/docs/urls)
- [Laravel Documentation - Proxies](https://laravel.com/docs/requests#configuring-trusted-proxies)
- [Render Documentation - Laravel](https://render.com/docs/deploy-laravel)
- [PostgreSQL Full-Text Search](https://www.postgresql.org/docs/current/textsearch.html)

## Các file đã thay đổi

1. `app/Providers/AppServiceProvider.php` - Force HTTPS
2. `app/Http/Middleware/TrustProxies.php` - Trust all proxies
3. `bootstrap/app.php` - Register TrustProxies middleware
4. `scripts/00-laravel-deploy.sh` - Updated deploy script
5. `.env` - Production configuration
6. `database/migrations/*` - PostgreSQL compatibility
7. `RENDER_DEPLOY.md` - Deployment guide

---

**Status:** ✅ RESOLVED

**Last Updated:** November 17, 2025
