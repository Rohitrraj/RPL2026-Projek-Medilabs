# MediLabs

MediLabs adalah aplikasi web untuk **reservasi dan pendaftaran layanan laboratorium klinik**. Sistem ini membantu pasien membuat akun, melengkapi data pasien, memilih layanan, membuat reservasi, memantau status pemeriksaan, serta melihat riwayat reservasi. Administrator dapat mengelola layanan, reservasi, status pemeriksaan, statistik, dan ekspor data.

## Status Project

- **Versi stabil:** `v1.0.0`
- **Branch rilis:** `main`
- **Branch pengembangan:** `develop`
- **Status:** frontend dan backend telah terintegrasi
- **Lingkungan pengembangan:** server lokal
- **Deployment produksi:** belum disertakan dalam repository

## Fitur

### Pasien

- Registrasi akun
- Login dan logout
- Lupa dan reset password
- Pengisian data pasien
- Melihat daftar serta detail layanan laboratorium
- Membuat reservasi pemeriksaan
- Mendapatkan kode reservasi dan nomor antrean
- Melihat hasil reservasi
- Mengecek status reservasi
- Melihat riwayat reservasi
- Membatalkan reservasi yang masih memenuhi aturan status
- Mencetak bukti reservasi

### Administrator

- Dashboard statistik reservasi
- Melihat dan mencari data reservasi
- Filter, sorting, dan pagination
- Melihat detail reservasi
- Memperbarui status sesuai alur pemeriksaan
- Menghapus reservasi melalui panel administrator
- Ekspor data reservasi ke CSV
- Mengelola layanan laboratorium
- Menambah dan mengubah layanan
- Mengaktifkan atau menonaktifkan layanan

## Konsep Sistem

MediLabs menggunakan konsep **satu akun untuk satu data pasien utama**.

Setiap akun dengan role `patient` hanya dapat memiliki satu data pasien. Data tersebut digunakan untuk seluruh reservasi yang dibuat oleh akun terkait.

Setiap reservasi memiliki:

- kode reservasi, misalnya `A001`;
- nomor antrean, misalnya `A-01`;
- pasien;
- layanan laboratorium;
- tanggal dan waktu reservasi;
- status;
- catatan opsional.

Status reservasi yang digunakan:

1. `Menunggu`
2. `Terjadwal`
3. `Diproses`
4. `Selesai`
5. `Dibatalkan`

Perubahan status mengikuti workflow yang telah ditentukan. Pasien tidak dapat melihat atau membatalkan reservasi milik akun lain.

## Role Pengguna

| Role | Hak akses |
|---|---|
| `patient` | Mengelola data pasien dan reservasi milik sendiri |
| `admin` | Mengelola layanan, reservasi, status, statistik, dan ekspor data |

## Teknologi

- PHP 8.3
- Laravel 13
- Blade
- MySQL
- HTML
- CSS
- JavaScript Vanilla
- Bootstrap dan Bootstrap Icons
- Vite
- Composer
- Node.js dan npm
- Git dan GitHub

## Struktur Database Utama

| Tabel | Fungsi |
|---|---|
| `users` | Menyimpan akun pasien dan administrator |
| `patients` | Menyimpan data utama pasien |
| `lab_tests` | Menyimpan layanan laboratorium |
| `reservations` | Menyimpan transaksi reservasi |

Laravel juga menggunakan tabel pendukung untuk migration, session, cache, reset password, dan queue.

Relasi utama:

```text
users
  └── patients
        └── reservations
              └── lab_tests
```

Secara relasional:

- `users` memiliki satu `patient`;
- `patients` memiliki banyak `reservations`;
- `lab_tests` memiliki banyak `reservations`;
- setiap `reservation` dimiliki satu pasien dan satu layanan.

## API

API publik yang tersedia:

| Method | Endpoint | Fungsi |
|---|---|---|
| `GET` | `/api/health` | Memeriksa status API |
| `GET` | `/api/lab-tests` | Mengambil seluruh layanan aktif |
| `GET` | `/api/lab-tests/{slug}` | Mengambil detail layanan aktif berdasarkan slug |

Endpoint reservasi tidak dibuka sebagai API publik untuk mencegah akses data dan perubahan status tanpa autentikasi.

## Persyaratan Sistem

Pastikan perangkat memiliki:

- PHP 8.3 atau versi kompatibel;
- Composer;
- MySQL;
- Node.js dan npm;
- Git.

## Instalasi Lokal

### 1. Clone repository

```bash
git clone https://github.com/Rohitrraj/RPL2026-Projek-Medilabs.git
cd RPL2026-Projek-Medilabs
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database pada `.env`:

```env
APP_NAME=MediLabs
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medilabs
DB_USERNAME=root
DB_PASSWORD=
```

Jangan memasukkan file `.env` ke repository.

### 4. Siapkan database

Buat database MySQL bernama `medilabs`, kemudian jalankan:

```bash
php artisan migrate --seed
```

Seeder digunakan untuk menyiapkan data awal seperti administrator dan layanan laboratorium sesuai konfigurasi project.

### 5. Build asset

Untuk development:

```bash
npm run dev
```

Untuk production build:

```bash
npm run build
```

### 6. Jalankan aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

Jika port `8000` sedang digunakan, Laravel dapat memilih port lain, misalnya `8001`.

## Alur Penggunaan

### Pasien

1. Membuat akun.
2. Login.
3. Mengisi data pasien.
4. Memilih layanan laboratorium.
5. Membuat reservasi.
6. Mendapatkan kode reservasi dan nomor antrean.
7. Memantau status reservasi.
8. Melihat riwayat atau membatalkan reservasi sesuai aturan.

### Administrator

1. Login menggunakan akun administrator.
2. Membuka dashboard.
3. Memantau statistik dan daftar reservasi.
4. Melihat detail reservasi.
5. Memperbarui status pemeriksaan.
6. Mengelola layanan aktif dan tidak aktif.
7. Mengekspor data reservasi sesuai periode.

## Pengujian

Jalankan automated testing:

```bash
php artisan optimize:clear
php artisan test
```

Validasi production build:

```bash
npm run build
```

Periksa route:

```bash
php artisan route:list
```

Periksa status migration:

```bash
php artisan migrate:status
```

Sebelum perubahan digabungkan ke branch rilis, pastikan:

- seluruh automated test lulus;
- tidak ada merge conflict;
- production build berhasil;
- working tree bersih;
- route dan middleware telah diperiksa;
- data pasien hanya dapat diakses oleh pemilik atau administrator.

## Deployment Production

Gunakan server dengan PHP, MySQL, Composer, Node.js, dan web server seperti Nginx atau Apache.

Konfigurasi utama:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-aplikasi.example
```

Langkah umum deployment:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Pastikan:

- document root web server mengarah ke folder `public/`;
- file `.env` tidak dapat diakses publik;
- `APP_KEY` telah dibuat;
- kredensial database menggunakan akun khusus aplikasi;
- HTTPS diaktifkan;
- folder `storage/` dan `bootstrap/cache/` dapat ditulis oleh web server;
- backup database dilakukan secara berkala;
- akun administrator menggunakan password yang kuat.

## Struktur Branch

```text
main
└── develop
    ├── feat/nama-fitur
    ├── fix/nama-perbaikan
    ├── refactor/nama-bagian
    └── docs/nama-dokumentasi
```

Aturan workflow:

1. Perubahan dibuat dari branch baru yang berasal dari `develop`.
2. Branch tugas di-push ke GitHub.
3. Perubahan digabungkan melalui Pull Request ke `develop`.
4. Setelah audit dan pengujian lulus, `develop` digabungkan ke `main`.
5. Pengembangan tidak dilakukan langsung pada `main`.

## Keamanan

Implementasi keamanan yang diterapkan mencakup:

- autentikasi berbasis session;
- middleware `guest`, `auth`, dan `admin`;
- pembatasan akses berdasarkan role;
- pemeriksaan ownership data pasien dan reservasi;
- validasi input;
- password hashing;
- reset password dengan token;
- pembatasan request reset password;
- workflow status reservasi;
- pembatalan tanpa menghapus histori reservasi pasien;
- API reservasi tidak tersedia secara publik.

Jangan menyimpan data berikut di repository:

- `.env`;
- password atau credential;
- dump database yang berisi data sensitif;
- folder `vendor/`;
- folder `node_modules/`;
- file log produksi.

## Pengembangan Lanjutan

Beberapa pengembangan yang masih dapat dilakukan:

- deployment ke server produksi;
- autentikasi API menggunakan Laravel Sanctum;
- pemisahan halaman login administrator;
- notifikasi email atau WhatsApp;
- ekspor bukti reservasi ke PDF;
- audit log aktivitas administrator;
- pengujian browser dan end-to-end;
- observability, monitoring, dan backup otomatis.

## Konteks Akademik

MediLabs dikembangkan sebagai project mata kuliah Rekayasa Perangkat Lunak dengan fokus pada:

- analisis kebutuhan;
- perancangan database;
- implementasi fitur berbasis role;
- pengelolaan data pasien dan reservasi;
- keamanan akses;
- integrasi frontend dan backend;
- version control;
- automated testing;
- dokumentasi project.

## Penutup

MediLabs merupakan sistem reservasi laboratorium klinik berbasis web yang mengintegrasikan proses pendaftaran pasien, pemilihan layanan, reservasi, pemantauan status, dan pengelolaan administrator dalam satu aplikasi.
