# Panduan Deploy ke Dokploy

## File yang Sudah Dibuat

1. **Dockerfile** - Multi-stage build untuk optimasi ukuran image
2. **.dockerignore** - Exclude file yang tidak perlu di image
3. **docker/nginx.conf** - Konfigurasi Nginx
4. **docker/supervisord.conf** - Menjalankan PHP-FPM, Nginx, dan Queue Worker
5. **docker/entrypoint.sh** - Script untuk migration dan optimization
6. **.env.production** - Template environment variables

## Langkah Deploy di Dokploy

### 1. Setup Environment Variables di Dokploy

Buka dashboard Dokploy → Service App Anda → Environment Variables, lalu tambahkan:

```env
APP_NAME=IPASS
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=auditpenjaminanmutu-auditmutu-2vuinb
DB_PORT=5432
DB_DATABASE=audit-mutu
DB_USERNAME=spmipolteksi
DB_PASSWORD=SpM1P0Lt3k5I2025

SESSION_DRIVER=database
SESSION_LIFETIME=120

QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local

MAIL_MAILER=resend
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME=IPASS

RESEND_API_KEY=your_resend_api_key_here

VITE_APP_NAME=IPASS
```

### 2. Generate APP_KEY

Sebelum deploy, generate APP_KEY dengan cara:

```bash
php artisan key:generate --show
```

Copy hasilnya (format: `base64:xxxxx`) dan masukkan ke environment variable `APP_KEY` di Dokploy.

### 3. Konfigurasi Build di Dokploy

- **Build Method**: Dockerfile
- **Dockerfile Path**: `./Dockerfile`
- **Port**: 80

### 4. Deploy

1. Push kode ke repository Git Anda
2. Connect repository ke Dokploy
3. Klik "Deploy"

### 5. Verifikasi

Setelah deploy berhasil, aplikasi akan:
- ✅ Otomatis menjalankan migration
- ✅ Cache config, routes, dan views
- ✅ Menjalankan queue worker
- ✅ Siap menerima traffic di port 80

## Catatan Penting

### Database
Database sudah dikonfigurasi dengan connection string internal Dokploy:
- Host: `auditpenjaminanmutu-auditmutu-2vuinb`
- Database: `audit-mutu`
- User: `spmipolteksi`
- Password: `SpM1P0Lt3k5I2025`

### Storage & Logs
Jika perlu persistent storage untuk uploads:
1. Di Dokploy, tambahkan Volume Mount
2. Mount path: `/var/www/html/storage/app/public`

### Queue Worker
Queue worker sudah berjalan otomatis via Supervisor. Jika tidak perlu queue, hapus section `[program:laravel-queue]` di `docker/supervisord.conf`.

### Custom Domain
Setelah deploy, update `APP_URL` di environment variables dengan domain Anda.

### Troubleshooting

Jika ada error, cek logs di Dokploy dashboard atau jalankan:
```bash
docker logs <container-id>
```

## Update Aplikasi

Untuk update aplikasi:
1. Push perubahan ke Git
2. Klik "Redeploy" di Dokploy
3. Migration akan otomatis dijalankan
