# IPASS - Sistem Audit Mutu Internal

## Tentang Aplikasi

IPASS (Internal Process Audit System) adalah sistem informasi audit mutu internal yang dikembangkan untuk Politeknik Semen Indonesia. Aplikasi ini dirancang untuk mengelola seluruh proses audit mutu internal mulai dari perencanaan, pelaksanaan, hingga pelaporan.

## Fitur Utama

### Master Data
- **Standar Mutu** - Pengelolaan standar mutu institusi
- **Kriteria** - Definisi kriteria audit
- **Indikator Kinerja** - KPI dan indikator kinerja utama

### Proses Audit
- **Penetapan** - Penjadwalan dan penetapan audit
- **Pelaksanaan** - Eksekusi proses audit
- **Evaluasi** - Penilaian dan evaluasi hasil audit
- **Approval** - Sistem persetujuan bertingkat

### Manajemen
- **Users** - Pengelolaan pengguna sistem
- **Roles** - Manajemen peran dan hak akses
- **Unit Kerja** - Organisasi unit kerja
- **Unit Auditors** - Penugasan auditor per unit

### Dokumentasi
- **Buku Kebijakan** - Repositori kebijakan audit
- **Manual** - Panduan prosedur audit
- **Formulir** - Template formulir audit
- **Laporan** - Generate laporan audit dalam format PDF

## Arsitektur Sistem

### Technology Stack
- **Framework**: Laravel 12.x
- **Database**: PostgreSQL/SQLite
- **Frontend**: Tailwind CSS + Alpine.js
- **PDF Generation**: DomPDF
- **Authentication**: Laravel Auth
- **Authorization**: Role-based Access Control (RBAC)

### Database Schema
```
├── users (Pengguna sistem)
├── role (Peran pengguna)
├── unit (Unit kerja)
├── standar_mutu (Standar mutu)
├── kriteria (Kriteria audit)
├── indikator_kinerja (KPI)
├── penetapan (Penjadwalan audit)
├── pelaksanaan (Eksekusi audit)
├── evaluasi (Evaluasi hasil)
└── approval (Persetujuan)
```

### Struktur Direktori
```
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/              # Eloquent Models
│   └── Middleware/          # Custom Middleware
├── resources/
│   ├── views/
│   │   ├── layouts/         # Layout templates
│   │   ├── auth/           # Authentication views
│   │   └── dashboard/      # Dashboard views
│   └── css/                # Stylesheets
├── routes/
│   └── web.php             # Web routes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
└── public/
    └── polteksilogo.png   # Logo aplikasi
```

## Instalasi

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL/SQLite
- Docker (opsional)

### Setup

1. **Clone Repository**
```bash
git clone <repository-url>
cd Audit-mutu-polteksi
```

2. **Install Dependencies**
```bash
composer install --ignore-platform-reqs
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
# Untuk PostgreSQL (production)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=audit_polteksi
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Untuk SQLite (development)
DB_CONNECTION=sqlite
touch database/database.sqlite
```

5. **Database Migration**
```bash
php artisan migrate
php artisan db:seed
```

6. **Run Application**
```bash
php artisan serve
```

### Docker Setup (Opsional)

```bash
# Jalankan database dengan Docker
docker-compose up -d

# Update .env untuk Docker
DB_HOST=127.0.0.1
DB_PORT=5433
DB_USERNAME=admin_audit
DB_PASSWORD=admin@audit
```

## Penggunaan

### Login
- URL: `/login`
- Default Admin: admin@polteksi.ac.id
- Dashboard: `/dashboard`

### Role & Permissions
- **Admin**: Akses penuh ke semua fitur
- **Auditor**: Akses ke proses audit dan evaluasi
- **Unit Manager**: Akses ke data unit kerja
- **Viewer**: Akses read-only ke laporan

### Workflow Audit
1. **Penetapan** - Admin menjadwalkan audit
2. **Pelaksanaan** - Auditor melakukan audit
3. **Evaluasi** - Penilaian hasil audit
4. **Approval** - Persetujuan hasil audit
5. **Laporan** - Generate laporan final

## API Endpoints

### Authentication
- `POST /login` - Login pengguna
- `POST /logout` - Logout pengguna

### Dashboard
- `GET /dashboard` - Dashboard utama
- `GET /dashboard/{module}` - Modul spesifik

### Reports
- `GET /dashboard/laporan` - Daftar laporan
- `GET /dashboard/laporan/pdf` - Generate PDF

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## Support

- **Developer**: Tim Teknologi Informasi Politeknik Semen Indonesia
- **Email**: ti@polteksi.ac.id
- **Documentation**: [Wiki](wiki)

---

**© 2024 Politeknik Semen Indonesia - Sistem Audit Mutu Internal**
