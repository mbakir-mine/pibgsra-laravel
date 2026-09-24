# Deployment PIBGSRA Laravel ke cPanel

## 1. Sediakan database

Dalam cPanel buka **MySQL Databases** dan cipta database, user serta password. Simpan nilai:

```text
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

## 2. Upload projek

Upload keseluruhan projek Laravel ke folder di luar `public_html`, contohnya:

```text
/home/USERNAME/pibgsra-laravel
```

Copy kandungan folder `public` ke:

```text
/home/USERNAME/public_html
```

Kemudian ubah `public_html/index.php` supaya path menunjuk kepada projek:

```php
require __DIR__.'/../pibgsra-laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../pibgsra-laravel/bootstrap/app.php';
```

## 3. Environment

Salin `.env.example` kepada `.env` dan tetapkan:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN-ANDA
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=DATABASE_CPANEL
DB_USERNAME=USER_CPANEL
DB_PASSWORD=PASSWORD_CPANEL
```

## 4. Terminal cPanel

```bash
cd ~/pibgsra-laravel
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

## 5. Permissions

Pastikan folder berikut boleh ditulis oleh PHP:

```text
storage/
bootstrap/cache/
```

## 6. Semakan

Buka domain dan uji login, dashboard, sekolah, keluarga, murid, caj, bayaran, resit, penyata, laporan dan audit.
