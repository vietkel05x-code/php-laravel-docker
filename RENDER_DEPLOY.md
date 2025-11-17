# Hướng dẫn Deploy Laravel lên Render.com

## Vấn đề Mixed Content đã được khắc phục

Ứng dụng đã được cấu hình để hoạt động với HTTPS trên Render.

### Các thay đổi đã thực hiện:

1. **Force HTTPS trong AppServiceProvider** (`app/Providers/AppServiceProvider.php`)
   - Tự động force tất cả URL thành HTTPS khi `APP_ENV=production`

2. **Trust Proxies** (`bootstrap/app.php`)
   - Tin tưởng tất cả proxy headers từ Render
   - Laravel sẽ nhận biết đúng HTTPS từ reverse proxy

3. **Cấu hình môi trường** (`.env`)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://elearning-gath.onrender.com`

4. **Script Deploy** (`scripts/00-laravel-deploy.sh`)
   - Clear cache trước khi build
   - Tạo symbolic link cho storage
   - Chạy migrations

## Cấu hình trên Render

### 1. Environment Variables cần thiết:

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
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 2. Build Command:
```bash
chmod +x scripts/00-laravel-deploy.sh && bash scripts/00-laravel-deploy.sh
```

### 3. Start Command:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

Hoặc nếu dùng Apache/Nginx, đảm bảo document root trỏ đến `public/`

### 4. Kiểm tra sau khi deploy:

1. **CSS/JS/Images load qua HTTPS**
   - Mở DevTools > Console
   - Không còn lỗi "Mixed Content"

2. **Database kết nối thành công**
   - Truy cập `/login` hoặc `/register`
   - Thử đăng ký tài khoản mới

3. **Session hoạt động**
   - Đăng nhập và kiểm tra có bị logout không

## Troubleshooting

### Lỗi "Mixed Content" vẫn còn

1. Clear cache trên Render:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

2. Kiểm tra biến môi trường `APP_URL` phải bắt đầu bằng `https://`

3. Kiểm tra `APP_ENV=production`

### CSS/JS không load

1. Kiểm tra file có tồn tại trong thư mục `public/`:
   - `public/css/main.css`
   - `public/js/main.js`

2. Tạo symbolic link cho storage:
```bash
php artisan storage:link
```

3. Kiểm tra permissions:
```bash
chmod -R 755 public/
chmod -R 755 storage/
```

### Database connection failed

1. Kiểm tra PostgreSQL đang chạy trên Render
2. Verify connection string trong Environment Variables
3. Kiểm tra migrations đã chạy:
```bash
php artisan migrate:status
```

## Lưu ý quan trọng

1. **Không commit file `.env` lên Git**
   - Chỉ commit `.env.example`
   - Cấu hình biến môi trường trực tiếp trên Render Dashboard

2. **Tắt Debug mode trong Production**
   - `APP_DEBUG=false` để tránh lộ thông tin nhạy cảm

3. **Sử dụng Database session driver**
   - `SESSION_DRIVER=database` thay vì `file` cho môi trường distributed

4. **SSL Certificate**
   - Render tự động cấp SSL certificate miễn phí
   - Không cần cấu hình thêm

## Contact

Nếu gặp vấn đề, kiểm tra logs:
```bash
php artisan log:tail
```
