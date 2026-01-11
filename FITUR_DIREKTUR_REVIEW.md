# Fitur Review Direktur - Catatan Peningkatan

## Deskripsi
Fitur untuk role Direktur melakukan review terhadap hasil audit yang sudah selesai dan memberikan catatan peningkatan untuk standar yang sudah tercapai.

## File yang Dibuat/Diubah

### 1. Migration
- `database/migrations/2026_01_11_211141_add_catatan_peningkatan_to_audit_table.php`
  - Menambah kolom `catatan_peningkatan` (text)
  - Menambah kolom `tanggal_review_direktur` (timestamp)
  - Menambah kolom `direview_oleh` (integer)

### 2. Controller
- `app/Http/Controllers/DirekturReviewController.php`
  - `index()` - List audit yang perlu direview
  - `show()` - Detail audit dan form input catatan
  - `update()` - Simpan catatan peningkatan

### 3. Views
- `resources/views/dashboard/direktur-review/index.blade.php` - List audit
- `resources/views/dashboard/direktur-review/show.blade.php` - Form review

### 4. Routes
- `GET /dashboard/direktur-review` - List
- `GET /dashboard/direktur-review/{id}` - Detail
- `PUT /dashboard/direktur-review/{id}` - Update catatan

### 5. Permission
- Permission baru: `direktur-review`
- Ditambahkan ke `RoleController` available permissions
- Ditambahkan ke `FixPermissions` command untuk role Admin/Direktur

### 6. Menu
- Menu "Review Direktur" ditambahkan di sidebar

## Cara Menggunakan

### Setup Permission
Jalankan command untuk set default permissions:
```bash
php artisan fix:permissions
```

Atau set manual di Dashboard → Roles → Edit Role → Centang "Review Direktur"

### Workflow
1. Direktur login
2. Klik menu "Review Direktur"
3. Filter audit berdasarkan status review, unit, atau tahun
4. Klik "Review" pada audit yang ingin direview
5. Lihat detail hasil audit
6. Isi "Catatan Peningkatan" untuk memberikan arahan peningkatan
7. Klik "Simpan Catatan"
8. Catatan tersimpan dan bisa diupdate kapan saja

### Fitur Filter
- Status Review: Belum Direview / Sudah Direview
- Unit: Filter berdasarkan unit kerja
- Tahun: Filter berdasarkan tahun audit

## Database Schema

```sql
ALTER TABLE audit ADD COLUMN catatan_peningkatan TEXT NULL;
ALTER TABLE audit ADD COLUMN tanggal_review_direktur TIMESTAMP NULL;
ALTER TABLE audit ADD COLUMN direview_oleh INTEGER NULL;
```

## Deploy ke Production

1. Push ke repository
2. Deploy di Dokploy
3. Jalankan migration:
   ```bash
   php artisan migrate --force
   ```
4. Set permissions:
   ```bash
   php artisan fix:permissions
   ```
5. Atau set manual permission `direktur-review` untuk role Direktur di dashboard
