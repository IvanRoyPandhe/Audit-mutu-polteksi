# Fitur SPI Monitoring & Notifikasi

## Deskripsi
Fitur untuk role SPI (Sistem Penjaminan Internal) melakukan monitoring semua pelaksanaan audit dan mengirim notifikasi peringatan ke auditor dan unit kerja.

## File yang Dibuat/Diubah

### 1. Migration
- `database/migrations/2026_01_12_041435_create_notifications_table.php`
  - Tabel notifications untuk menyimpan notifikasi in-app

### 2. Models
- `app/Models/Notification.php` - Model untuk notifikasi

### 3. Controllers
- `app/Http/Controllers/SPIController.php`
  - `index()` - Dashboard monitoring semua pelaksanaan
  - `sendNotification()` - Kirim notifikasi ke user
  - `getUsers()` - Get list user untuk notifikasi
- `app/Http/Controllers/NotificationController.php`
  - `index()` - List notifikasi user
  - `getUnread()` - API get notifikasi belum dibaca
  - `markAsRead()` - Tandai notifikasi dibaca
  - `markAllAsRead()` - Tandai semua dibaca

### 4. Views
- `resources/views/dashboard/spi/index.blade.php` - Dashboard SPI
- `resources/views/dashboard/notifications/index.blade.php` - List notifikasi

### 5. Routes
- `GET /dashboard/spi` - Dashboard SPI
- `POST /dashboard/spi/send-notification` - Kirim notifikasi
- `GET /dashboard/spi/users` - Get users untuk notifikasi
- `GET /dashboard/notifications` - List notifikasi
- `GET /dashboard/notifications/unread` - API unread notifications
- `POST /dashboard/notifications/{id}/read` - Mark as read
- `POST /dashboard/notifications/mark-all-read` - Mark all as read

### 6. Permission & Role
- Permission baru: `spi-monitoring`
- Role SPI (role_id = 4) dengan permission dashboard dan spi-monitoring

### 7. UI Updates
- Menu "SPI Monitoring" di sidebar
- Notification bell icon di header dengan counter
- Dropdown notifikasi real-time

## Database Schema

### Tabel notifications
```sql
CREATE TABLE notifications (
  id BIGINT PRIMARY KEY,
  user_id INTEGER,
  title VARCHAR(255),
  message TEXT,
  type VARCHAR(50) DEFAULT 'reminder',
  pelaksanaan_id INTEGER NULL,
  is_read BOOLEAN DEFAULT FALSE,
  sent_by INTEGER,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

## Fitur SPI Monitoring

### Dashboard Statistics
- Total Pelaksanaan
- Selesai
- Sedang Berjalan  
- Terlambat

### Status Monitoring
- **Selesai**: Status = "Selesai"
- **Terlambat**: Tanggal selesai < sekarang dan status != "Selesai"
- **Sedang Berjalan**: Tanggal mulai <= sekarang dan belum terlambat
- **Belum Mulai**: Tanggal mulai > sekarang

### Filter
- Status (Terlambat, Sedang Berjalan, Selesai)
- Unit Kerja
- Tahun

### Kirim Notifikasi
- Pilih penerima (PIC dan/atau Auditor)
- Jenis: Pengingat, Peringatan, Informasi
- Judul dan pesan custom
- Terkait pelaksanaan tertentu

## Fitur Notifikasi

### In-App Notifications
- Bell icon di header dengan counter unread
- Dropdown preview 10 notifikasi terbaru
- Halaman lengkap semua notifikasi
- Auto-refresh setiap 30 detik

### Jenis Notifikasi
- **Reminder** (Pengingat): Kuning
- **Warning** (Peringatan): Merah  
- **Info** (Informasi): Biru

## Cara Menggunakan

### Setup Role SPI
1. Buat user dengan role_id = 4 (SPI)
2. Jalankan `php artisan fix:permissions`
3. Atau set manual permission `spi-monitoring` di dashboard

### Workflow SPI
1. Login sebagai SPI
2. Klik menu "SPI Monitoring"
3. Lihat dashboard dengan statistik dan filter
4. Klik "Kirim Peringatan" pada pelaksanaan yang terlambat
5. Pilih penerima (PIC/Auditor)
6. Tulis judul dan pesan
7. Kirim notifikasi

### Workflow User (Penerima)
1. Lihat counter notifikasi di bell icon
2. Klik bell untuk preview notifikasi
3. Klik "Lihat Semua" untuk halaman lengkap
4. Tandai dibaca individual atau semua sekaligus

## Deploy ke Production

1. Push semua perubahan ke repository
2. Deploy di Dokploy
3. Jalankan migration:
   ```bash
   php artisan migrate --force
   ```
4. Set permissions:
   ```bash
   php artisan fix:permissions
   ```
5. Buat user dengan role SPI (role_id = 4) di database atau dashboard

## Teknologi
- **Backend**: Laravel dengan Eloquent ORM
- **Frontend**: Blade templates + Alpine.js untuk interaktivitas
- **Database**: PostgreSQL
- **Real-time**: Polling setiap 30 detik (bisa upgrade ke WebSocket)
- **Styling**: Tailwind CSS

## Future Enhancements
- Email notifications
- Push notifications
- WebSocket real-time updates
- Notification templates
- Bulk actions
- Advanced filtering