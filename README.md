# FINAL-PROJECT-LAB-WEB25

<div align="center">
  <img src="Documentation/cutiin_banner.png" alt="CUTI-IN Banner" width="450"/>
</div>

**Sistem Manajemen Cuti Karyawan Berbasis Web (CUTI-IN)**

Sistem manajemen cuti karyawan berbasis web yang menampilkan alur persetujuan bertingkat (tiered approval workflows), pelacakan kuota otomatis, dan kontrol akses multi-role. Sistem ini dirancang untuk membantu perusahaan dalam mengelola pengajuan cuti karyawan secara digital dengan efisien dan terstruktur.

## 📋 Daftar Isi

- [🎯 Fitur Utama](#-fitur-utama)
- [🛠️ Teknologi yang Digunakan](#️-teknologi-yang-digunakan)
- [👥 Role dan Hak Akses](#-role-dan-hak-akses)
- [🔄 Alur Persetujuan Cuti](#-alur-persetujuan-cuti)
- [📋 Prasyarat](#-prasyarat)
- [🚀 Instalasi](#-instalasi)
- [⚙️ Konfigurasi](#️-konfigurasi)
- [📖 Penggunaan](#-penggunaan)
- [📁 Struktur Proyek](#-struktur-proyek)
- [🗄️ Struktur Database](#️-struktur-database)
- [🧪 Testing](#-testing)
- [🤝 Kontribusi](#-kontribusi)

---

## 🎯 Fitur Utama

### 1. **Manajemen Pengajuan Cuti**
- Pengajuan cuti tahunan (annual leave) dan cuti sakit (sick leave)
- Perhitungan hari cuti otomatis dengan mengecualikan hari libur nasional
- Upload dokumen pendukung (attachment)
- Pelacakan status pengajuan secara real-time
- Unduh surat cuti dalam format PDF

### 2. **Alur Persetujuan Bertingkat**
- **Tingkat 1**: Persetujuan oleh Division Leader (Ketua Divisi)
- **Tingkat 2**: Persetujuan final oleh HRD
- Penolakan dengan catatan (minimal 10 karakter)
- Catatan persetujuan dari leader

### 3. **Manajemen Kuota Cuti**
- Tracking kuota cuti tahunan per karyawan (default: 12 hari)
- Pengurangan kuota otomatis saat cuti tahunan disetujui
- Validasi kuota sebelum pengajuan cuti tahunan
- Cuti sakit tidak mengurangi kuota cuti tahunan

### 4. **Manajemen Hari Libur**
- Manajemen hari libur nasional dan internal perusahaan
- Sinkronisasi otomatis hari libur dari Google Calendar (opsional)
- Penghitungan hari kerja otomatis (exclude weekends dan hari libur)

### 5. **Manajemen Divisi**
- Manajemen divisi/departemen
- Penugasan division leader untuk setiap divisi
- Manajemen anggota divisi

### 6. **Dashboard Berdasarkan Role**
- Dashboard khusus untuk setiap role (Admin, HRD, Division Leader, User)
- Statistik dan ringkasan data relevan per role
- Notifikasi pengajuan yang menunggu persetujuan

### 7. **Manajemen Pengguna**
- Manajemen data karyawan (CRUD)
- Penetapan role dan divisi
- Manajemen status aktif/nonaktif karyawan
- Manajemen kuota cuti per karyawan

### 8. **Laporan dan Ringkasan**
- Ringkasan pengajuan cuti untuk Admin dan HRD
- Filter berdasarkan divisi, periode, dan status
- Statistik pengajuan cuti

### 9. **Keamanan**
- Autentikasi berbasis Laravel Breeze
- Middleware role-based access control
- Verifikasi email (opsional)
- Reset password

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP 8.2+**
- **Laravel Framework 12.0**
- **MySQL** 

### Frontend
- **Tailwind CSS 3.1.0** - Utility-first CSS framework
- **Alpine.js 3.4.2** - JavaScript framework untuk interaktivitas
- **Vite 7.0.7** - Build tool dan development server
- **Blade Templates** - Templating engine Laravel

### Libraries & Packages
- **Laravel Breeze 2.3** - Autentikasi scaffolding
- **Laravel DomPDF 3.1** - Generate PDF untuk surat cuti
- **Laravel Pint 1.24** - Code style fixer
- **PHPUnit 11.5.3** - Testing framework

### Development Tools
- **Laravel Sail 1.41** - Docker development environment
- **Laravel Pail 1.2.2** - Real-time log viewer
- **Concurrently 9.0.1** - Run multiple commands simultaneously

## 📁 Struktur Proyek

```
FINAL-PROJECT-LAB-WEB25/
├── manager_cuti/                 # Main application directory
│   ├── app/
│   │   ├── Console/              # Artisan commands
│   │   ├── Http/
│   │   │   ├── Controllers/      # Application controllers
│   │   │   │   ├── Admin/        # Admin controllers
│   │   │   │   ├── Hrd/          # HRD controllers
│   │   │   │   ├── Leader/       # Division Leader controllers
│   │   │   │   ├── User/         # User controllers
│   │   │   │   └── Auth/         # Authentication controllers
│   │   │   ├── Middleware/       # Custom middleware
│   │   │   ├── Requests/         # Form request validation
│   │   │   └── Rules/            # Custom validation rules
│   │   ├── Models/               # Eloquent models
│   │   ├── Providers/            # Service providers
│   │   ├── Services/             # Business logic services
│   │   └── View/                 # View components
│   ├── bootstrap/                # Bootstrap files
│   ├── config/                   # Configuration files
│   ├── database/
│   │   ├── migrations/           # Database migrations
│   │   ├── seeders/              # Database seeders
│   │   └── factories/            # Model factories
│   ├── public/                   # Public assets
│   ├── resources/
│   │   ├── css/                  # Stylesheets
│   │   ├── js/                   # JavaScript files
│   │   └── views/                # Blade templates
│   │       ├── admin/            # Admin views
│   │       ├── hrd/              # HRD views
│   │       ├── leader/           # Leader views
│   │       ├── user/             # User views
│   │       ├── auth/             # Authentication views
│   │       ├── leave-requests/   # Leave request views
│   │       └── layouts/          # Layout templates
│   ├── routes/
│   │   ├── web.php               # Web routes
│   │   └── auth.php              # Authentication routes
│   ├── storage/                  # Storage files
│   ├── tests/                    # Automated tests
│   ├── vendor/                   # Composer dependencies
│   ├── artisan                   # Artisan CLI
│   ├── composer.json             # PHP dependencies
│   └── package.json              # Node.js dependencies
└── README.md                     # Dokumentasi proyek
```

## 👥 Role dan Hak Akses

Sistem ini mendukung 4 role utama dengan hak akses yang berbeda:

### 1. **Admin**
Hak akses penuh untuk mengelola sistem:
- ✅ Manajemen divisi (CRUD)
- ✅ Manajemen pengguna/karyawan (CRUD)
- ✅ Manajemen hari libur (CRUD)
- ✅ Melihat semua pengajuan cuti
- ✅ Melihat ringkasan cuti (leave summary)
- ✅ Sinkronisasi hari libur dari Google Calendar

### 2. **HRD**
Mengelola pengajuan cuti dan persetujuan final:
- ✅ Persetujuan final pengajuan cuti (setelah disetujui leader)
- ✅ Penolakan pengajuan cuti
- ✅ Melihat pengajuan yang sudah disetujui leader
- ✅ Melihat pengajuan dari division leader (langsung ke HRD)
- ✅ Melihat ringkasan cuti
- ✅ Bulk update pengajuan cuti

### 3. **Division Leader**
Mengelola pengajuan cuti dari anggota divisinya:
- ✅ Melihat pengajuan cuti dari anggota divisi (status: pending)
- ✅ Menyetujui pengajuan cuti (tingkat 1)
- ✅ Menolak pengajuan cuti
- ✅ Menambahkan catatan persetujuan
- ✅ Bulk update pengajuan cuti
- ✅ Mengajukan cuti sendiri (langsung ke HRD)

### 4. **User (Karyawan)**
Fitur dasar untuk karyawan:
- ✅ Mengajukan cuti (tahunan/sakit)
- ✅ Melihat riwayat pengajuan cuti sendiri
- ✅ Membatalkan pengajuan (jika masih pending)
- ✅ Mengunduh surat cuti (PDF) jika disetujui
- ✅ Melihat statistik cuti sendiri
- ✅ Mengelola profil

## 🔄 Alur Persetujuan Cuti

### Untuk Karyawan Biasa (User)

```
1. Karyawan mengajukan cuti
   ↓
2. Status: "pending"
   ↓
3. Division Leader meninjau dan memutuskan:
   ├─ Menyetujui → Status: "approved_by_leader"
   │                ↓
   │  4. HRD meninjau dan memutuskan:
   │     ├─ Menyetujui → Status: "approved"
   │     │              ├─ Kuota dikurangi (jika annual leave)
   │     │              └─ Karyawan bisa download PDF
   │     │
   │     └─ Menolak → Status: "rejected"
   │                  └─ Catatan penolakan tersimpan
   │
   └─ Menolak → Status: "rejected"
                └─ Catatan penolakan tersimpan
```

### Untuk Division Leader

```
1. Division Leader mengajukan cuti
   ↓
2. Status: "pending" (langsung ke HRD, skip leader approval)
   ↓
3. HRD meninjau dan memutuskan:
   ├─ Menyetujui → Status: "approved"
   │              ├─ Kuota dikurangi (jika annual leave)
   │              └─ Bisa download PDF
   │
   └─ Menolak → Status: "rejected"
                └─ Catatan penolakan tersimpan
```

### Status Pengajuan Cuti

- **pending**: Menunggu persetujuan
- **approved_by_leader**: Disetujui oleh division leader, menunggu persetujuan HRD
- **approved**: Disetujui sepenuhnya dan siap digunakan
- **rejected**: Ditolak oleh division leader atau HRD

## 📋 Prasyarat

Sebelum memulai instalasi, pastikan sistem Anda memiliki:

- **PHP** >= 8.2 
- **Composer** >= 2.0
- **Node.js** >= 18.0 dan **NPM** >= 8.0
- **Git**

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd FINAL-PROJECT-LAB-WEB25
cd manager_cuti
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies Node.js

```bash
npm install
```

### 4. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```


### 6. Run Migration dan Seeder

```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

### 7. Build Assets

Untuk development:
```bash
npm run dev
```

Untuk production:
```bash
npm run build
```

### 8. Jalankan Server Development

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### Quick Setup (Menggunakan Composer Script)

```bash
composer run setup
```

Script ini akan menjalankan semua langkah instalasi secara otomatis.

## ⚙️ Konfigurasi

### Akun Default

Setelah menjalankan seeder, akun default berikut tersedia:

**Admin:**
- Email: `admin@example.com`
- Password: `Password`

**HRD:**
- Email: `hrd@example.com`
- Password: `Password`

**Division Leader (IT):**
- Email: `lead.it@example.com`
- Password: `Password`

**Staff (IT):**
- Email: `staff.it@example.com`
- Password: `Password`

**Staff (Finance):**
- Email: `staff.finance@example.com`
- Password: `Password`

⚠️ **PENTING**: Ubah password default setelah instalasi pertama!

## 📖 Penggunaan

### Untuk Karyawan (User)

1. **Login** dengan akun karyawan
2. **Dashboard** menampilkan statistik cuti Anda
3. **Ajukan Cuti**:
   - Pilih tipe cuti (Tahunan/Sakit)
   - Isi tanggal mulai dan akhir
   - Sistem akan menghitung hari cuti otomatis (exclude weekends dan hari libur)
   - Isi alasan cuti, alamat saat cuti, dan kontak darurat
   - Upload dokumen pendukung (opsional)
   - Submit pengajuan
4. **Lacak Status** pengajuan di halaman "Pengajuan Cuti"
5. **Unduh PDF** surat cuti setelah disetujui

### Untuk Division Leader

1. **Login** dengan akun division leader
2. **Dashboard** menampilkan pengajuan yang menunggu persetujuan
3. **Review Pengajuan**:
   - Buka halaman "Pengajuan Cuti Divisi"
   - Tinjau detail pengajuan
   - Approve atau Reject
   - Tambahkan catatan (opsional)
4. **Bulk Update** untuk menangani multiple pengajuan sekaligus

### Untuk HRD

1. **Login** dengan akun HRD
2. **Dashboard** menampilkan ringkasan pengajuan
3. **Review Pengajuan**:
   - Buka halaman "Pengajuan Cuti"
   - Tinjau pengajuan yang sudah disetujui leader
   - Approve final atau Reject
4. **Lihat Ringkasan** cuti semua karyawan
5. **Filter** berdasarkan divisi, periode, dan status

### Untuk Admin

1. **Login** dengan akun admin
2. **Manajemen Divisi**:
   - Tambah/edit/hapus divisi
   - Tetapkan division leader
   - Kelola anggota divisi
3. **Manajemen Karyawan**:
   - Tambah/edit/hapus karyawan
   - Set role dan divisi
   - Kelola kuota cuti
4. **Manajemen Hari Libur**:
   - Tambah hari libur manual
   - Sinkronisasi dari Google Calendar (opsional)
5. **Lihat Semua Pengajuan** dan ringkasan

## 🗄️ Struktur Database

Sistem menggunakan 4 tabel utama:

### 1. **users**
Menyimpan data pengguna/karyawan:
- Informasi personal (nama, email, phone, address)
- Role (admin, hrd, division_leader, user)
- Division ID (relasi ke divisions)
- Leave quota (kuota cuti tahunan)
- Active status

### 2. **divisions**
Menyimpan data divisi/departemen:
- Nama divisi
- Deskripsi
- Leader ID (relasi ke users)

### 3. **leave_requests**
Menyimpan pengajuan cuti:
- User ID (pemohon)
- Leave type (annual/sick)
- Start date, end date, total days
- Reason, address during leave, emergency contact
- Attachment path
- Status (pending/approved_by_leader/approved/rejected)
- Approved by (user ID yang menyetujui)
- Leader note, rejection note

### 4. **holidays**
Menyimpan hari libur:
- Title (nama hari libur)
- Holiday date
- Description
- Is national holiday (boolean)

### ERD Diagram

File ERD tersedia di `manager_cuti/ERD_CUTI_IN.dbml`. Anda dapat melihat diagram visual dengan:
1. Buka https://dbdiagram.io
2. Copy paste isi file ERD_CUTI_IN.dbml
3. Diagram akan otomatis ter-render

## 🧪 Testing

### Menjalankan Tests

```bash
# Jalankan semua tests
php artisan test

# Jalankan dengan coverage
php artisan test --coverage

# Jalankan specific test file
php artisan test tests/Feature/Auth/LoginTest.php
```

### Struktur Tests

```
tests/
├── Feature/              # Integration/Feature tests
│   ├── Auth/            # Authentication tests
│   └── Profile/         # Profile tests
└── Unit/                # Unit tests
```

### Test Coverage

Sistem ini dilengkapi dengan test cases untuk:
- Autentikasi (login, register, password reset)
- Profile management
- Leave request creation
- Role-based access control

## 🎨 Development

### Menjalankan Development Server

Gunakan composer script untuk menjalankan semua service sekaligus:

```bash
composer run dev
```

Ini akan menjalankan:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server

### Code Style

Gunakan Laravel Pint untuk format code:

```bash
./vendor/bin/pint
```

### Artisan Commands

Beberapa command yang tersedia:

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate
php artisan migrate:fresh --seed

# Generate model dengan migration
php artisan make:model ModelName -m

# Generate controller
php artisan make:controller ControllerName

# List semua routes
php artisan route:list
```

## 📝 Kontribusi

Kontribusi sangat diterima! Jika ingin berkontribusi:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## 📄 License

Proyek ini dibuat untuk tujuan pembelajaran dalam rangka Final Project Lab Web Semester 3.

## 👤 Author

**Dalvyn Suhada - Final Project Lab Web 25**

## 🙏 Acknowledgments

- Laravel Framework
- Laravel Breeze
- Tailwind CSS
- Alpine.js
- Semua kontributor open source yang digunakan dalam proyek ini

---

**Selamat menggunakan CUTI-IN! 🎉**

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.
