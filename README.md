# 🕌 Santrix - Sistem Manajemen Pesantren Modern No. #1

**Platform Manajemen Pesantren Cloud-Based Terlengkap & Aman**

[![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue?logo=php)](https://www.php.net)
[![Tenancy](https://img.shields.io/badge/Tenancy-Multi--Tenant-green)](https://tenancyforlaravel.com)
[![License](https://img.shields.io/badge/License-MIT-purple)]()

---

![Santrix Landing Page](https://santrix.my.id/images/seo-og-image.jpg)

## 📖 Tentang Santrix
Santrix adalah solusi **SaaS (Software as a Service)** untuk digitalisasi administrasi pesantren. Didesain untuk menangani kompleksitas manajemen pesantren modern maupun salaf, mulai dari **Keuangan Syahriah, Akademik Kitab Kuning/Umum, hingga Laporan Keuangan Yayasan.**

### � Keunggulan Utama
- **Multi-Tenant Architecture**: Satu aplikasi untuk ribuan pesantren dengan database terpisah (Data isolation).
- **Subdomain Otomatis**: Setiap pesantren mendapatkan subdomain unik (contoh: `al-ikhlas.santrix.my.id`).
- **Owner Dashboard**: Panel kontrol pusat untuk memantau seluruh tenant pesantren.
- **Security First**: Otomatis HTTPS/SSL (Let's Encrypt), Proteksi CSRF/XSS, dan Backup Berkala.

---

## ✨ Fitur Unggulan

### � 1. Keuangan & SPP (Syahriah)
- Dashboard Arus Kas Real-time
- Tagihan Syahriah & Tabungan Santri
- **WhatsApp Gateway**: Kirim tagihan & bukti bayar otomatis ke Wali Santri.
- Laporan Keuangan Harian/Bulanan (PDF/Excel)

### 📚 2. Akademik & Pendidikan
- Manajemen Kelas & Asrama (Kobong)
- Input Nilai Hafalan (Talaran) & Kitab Kuning
- Absensi Harian/Mingguan
- Cetak Rapor Standar Pondok/Diknas (PDF)

### � 3. Keamanan & Akses
- **Multi-Role**: Admin, Sekretaris, Bendahara, Pendidikan, Owner.
- **Secure Login**: Dilengkapi fitur "Show Password" toggle & Rate Limiting.
- **HTTPS Forced**: Keamanan data terenkripsi standar perbankan.

---

## 🛠️ Teknologi yang Digunakan
- **Framework**: Laravel 10 (PHP 8.2)
- **Database**: MariaDB / MySQL 8.0
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Feather Icons
- **Server**: Nginx (Ubuntu VPS)
- **Tools**: Composer, NPM (Vite), Certbot (SSL), Git

---

## 🚀 Instalasi (Development)

Jika Anda ingin menjalankan project ini di lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/mahinutsmannawawi20-svg/santrix.git
   cd santrix
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Setup Environment**
   - Copy `.env.example` ke `.env`
   - Setup database connection
   - **PENTING**: Setup `CENTRAL_DOMAINS` untuk tenancy
     ```env
     CENTRAL_DOMAINS=santrix.test,owner.santrix.test
     ```

4. **Migrate & Seed**
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server**
   ```bash
   php artisan serve
   ```
   Akses di `http://santrix.test` (Gunakan Laravel Valet atau Laragon untuk support subdomain).

---

## ☁️ Deployment (Production VPS)

Panduan singkat deploy di VPS (Ubuntu 22.04):

1. **Setup DNS**: A Record `*` (Wildcard) dan `@` ke IP VPS.
2. **Nginx Config**: Setup block server untuk handle Wildcard Subdomains.
3. **SSL (Certbot)**:
   ```bash
   certbot --nginx -d santrix.my.id -d owner.santrix.my.id
   ```
4. **Env Variables**:
   ```env
   APP_URL=https://santrix.my.id
   CENTRAL_DOMAINS=santrix.my.id,owner.santrix.my.id
   SESSION_DOMAIN=.santrix.my.id
   ```
5. **Optimize**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## � Akun Demo (Owner)

| Role | Email | Password |
|------|-------|----------|
| **Owner** | `nawawimahinutsman@gmail.com` | `OwnerSantrix200601` |

---

## 👨‍� Developer & Credits

Dibuat dengan ❤️ oleh **Mahin Utsman Nawawi** & Team Velora.
Dedikasi untuk kemajuan Pesantren Indonesia.

**© 2026 Santrix Project.** All rights reserved.
