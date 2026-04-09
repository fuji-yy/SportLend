# 🎯 Sipinjam - Aplikasi Peminjaman Alat

Sipinjam adalah aplikasi modern untuk manajemen peminjaman alat dengan tiga role berbeda: Admin, Petugas, dan Peminjam.

## 📋 Fitur Utama

### Admin
- ✅ Dashboard dengan statistik lengkap
- 👥 Manage User (CRUD)
- 🏷️ Manage Kategori Alat (CRUD)
- 🛠️ Manage Data Alat (CRUD)
- 📋 Manage Data Peminjaman (lihat, update status)
- 📤 Manage Pengembalian Alat
- 📊 Log Aktivitas Detail

### Petugas
- 📊 Dashboard dengan statistik
- ✅ Setujui/Tolak Peminjaman
- 👀 Pantau Pengembalian Alat
- 📄 Generate Laporan Peminjaman (HTML & PDF)
- 📄 Generate Laporan Pengembalian (HTML & PDF)

### Peminjam
- 🔍 Cari dan Lihat Daftar Alat Tersedia
- 📝 Ajukan Peminjaman Alat
- 📋 Kelola Peminjaman Saya
- 🔄 Kembalikan Alat yang Dipinjam
- 📊 Dashboard dengan Status Peminjaman

## 🚀 Instalasi & Setup

### Prasyarat
- PHP 8.1+
- Composer
- Node.js & npm
- Laragon atau Web Server lainnya
- Database SQLite (sudah disertakan)

### Langkah Instalasi

1. **Clone atau unduh project**
   ```bash
   cd c:\laragon\www\sipinjam
   ```

2. **Install dependencies (jika belum)**
   ```bash
   composer install
   npm install
   ```

3. **Build assets**
   ```bash
   npm run build
   ```

4. **Migrate database (jika belum)**
   ```bash
   php artisan migrate --force
   ```

5. **Seed database dengan data demo**
   ```bash
   php artisan db:seed --force
   ```

6. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

7. **Akses aplikasi**
   ```
   http://localhost:8000
   ```

## 🔑 Demo Akun

Gunakan akun berikut untuk login:

### Admin
- **Email**: admin@gmail.com
- **Password**: password
- **Roles**: Admin

### Petugas
- **Email**: petugas@gmail.com
- **Password**: password
- **Roles**: Petugas

### Peminjam
- **Email**: budi@gmail.com atau siti@gmail.com
- **Password**: password
- **Roles**: Peminjam

## 📁 Struktur Database

### Tables
- `users` - Data pengguna dengan role berbeda
- `categories` - Kategori alat
- `tools` - Data alat/peralatan
- `borrowings` - Data peminjaman alat
- `returns` - Data pengembalian alat
- `activity_logs` - Log aktivitas sistem

## 🎨 UI/UX

- **Framework**: Tailwind CSS 4.0
- **Design**: Modern dan Responsive
- **Mobile-Friendly**: Ya
- **Color Scheme**: Blue & Gray

## 🔐 Keamanan

- ✅ Password Hashing (Bcrypt)
- ✅ Role-based Access Control (RBAC)
- ✅ Activity Logging
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Prepared Statements)

## 📊 Status Peminjaman

- **pending** - Menunggu persetujuan petugas
- **approved** - Disetujui dan siap dipinjam
- **rejected** - Ditolak oleh petugas
- **returned** - Sudah dikembalikan

## 🔧 Teknologi Stack

- **Backend**: Laravel 12
- **Database**: SQLite
- **Frontend**: Blade Templates + Tailwind CSS
- **Build Tool**: Vite
- **Package Manager**: Composer, npm

## 📝 Catatan Penting

1. Alat akan berkurang ketersediaannya ketika peminjaman disetujui
2. Alat akan bertambah ketersediaannya ketika pengembalian diproses
3. Hanya peminjaman dengan status "approved" yang bisa dikembalikan
4. Admin dapat melihat semua aktivitas dalam menu "Log Aktifitas"
5. Password default untuk semua demo akun adalah "password"

## 🛠️ Maintenance

### Reset Database
```bash
php artisan migrate:reset --force
php artisan migrate --force
php artisan db:seed --force
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

## 📧 Support

Untuk pertanyaan atau masalah, hubungi administrator sistem.

---

**Dibuat dengan ❤️ menggunakan Laravel 12**
