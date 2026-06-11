# MediLabs

MediLabs adalah aplikasi web **Sistem Reservasi dan Pendaftaran Laboratorium Klinik**.  
Project ini dibuat untuk membantu proses pendaftaran pasien, pemesanan layanan laboratorium, pengecekan status reservasi, dan pengelolaan data reservasi oleh admin.

## Tentang Project

MediLabs dirancang untuk memisahkan alur pengguna biasa dan admin.  
Pengguna dapat membuat akun, mengisi data pasien, memilih layanan laboratorium, lalu membuat reservasi pemeriksaan. Setelah reservasi berhasil dibuat, sistem menampilkan kode reservasi dan nomor antrian yang dapat digunakan untuk pengecekan status.

Di sisi admin, sistem menyediakan dashboard untuk memantau reservasi, memperbarui status pemeriksaan, dan mengelola layanan laboratorium yang tersedia di frontend.

## Fitur Utama

Pada sisi pengguna, fitur utama yang tersedia meliputi:
- registrasi akun
- login dan logout
- input data pasien
- melihat daftar layanan laboratorium
- melihat detail layanan
- membuat reservasi
- melihat hasil reservasi
- mengecek status reservasi
- melihat riwayat reservasi
- mencetak bukti reservasi

Pada sisi admin, fitur utama yang tersedia meliputi:
- dashboard admin
- kelola reservasi
- ubah status reservasi
- cek detail reservasi
- kelola layanan laboratorium
- tambah layanan
- edit layanan
- aktifkan dan nonaktifkan layanan

## Konsep Sistem

Project ini menggunakan konsep **satu akun untuk satu pasien**.  
Artinya, setiap user hanya memiliki satu data pasien utama, dan data pasien tersebut digunakan untuk semua reservasi yang dibuat oleh akun tersebut.

Setiap reservasi memiliki:
- **kode reservasi**, misalnya `A001`
- **nomor antrian**, misalnya `A-01`

Layanan laboratorium disimpan di tabel layanan, lalu hanya layanan dengan status aktif yang ditampilkan kepada user di halaman frontend dan form reservasi.

## Role Pengguna

Sistem memiliki dua role utama:
- **patient**
- **admin**

Role patient digunakan untuk pengguna biasa yang melakukan reservasi.  
Role admin digunakan untuk pengelolaan reservasi dan layanan.

## Teknologi yang Digunakan

Project ini dibangun menggunakan:
- Laravel
- Blade
- PHP
- MySQL
- HTML
- CSS
- JavaScript Vanilla
- Vite

## Alur Penggunaan

Alur dasar pengguna dalam aplikasi ini adalah:
1. membuat akun
2. login
3. mengisi data pasien
4. memilih layanan
5. membuat reservasi
6. mendapatkan kode reservasi dan nomor antrian
7. mengecek status reservasi
8. melihat riwayat reservasi

Sementara itu, admin dapat login ke sistem lalu memantau seluruh reservasi, memperbarui status, dan mengelola data layanan laboratorium.

## API

MediLabs juga menyediakan API dasar untuk:
- health check
- mengambil data layanan
- mengambil data reservasi
- mencari reservasi berdasarkan kode
- memperbarui status reservasi

API ini digunakan sebagai bagian dari pengembangan backend dan integrasi data.

## Status Project

Saat ini project sudah mencakup:
- frontend user
- panel admin
- alur reservasi
- cek status
- riwayat reservasi
- kelola layanan
- endpoint API dasar
- refactor struktur CSS dan Blade agar lebih rapi

## Tujuan Pengembangan

Project ini dikembangkan sebagai bagian dari pembelajaran dan implementasi **Rekayasa Perangkat Lunak**, dengan fokus pada:
- analisis kebutuhan sistem
- implementasi fitur berbasis role
- pengelolaan data pasien dan reservasi
- pemisahan frontend user dan admin
- struktur project yang lebih rapi dan mudah dikembangkan

## Catatan

Project ini masih dapat dikembangkan lebih lanjut, misalnya:
- login admin terpisah
- export PDF yang lebih formal
- filter data yang lebih lengkap
- validasi dan keamanan yang lebih kuat
- pengujian otomatis
- deployment production

## Penutup

MediLabs merupakan project sistem informasi klinik sederhana yang menitikberatkan pada alur reservasi laboratorium, pengelolaan data pasien, dan manajemen reservasi oleh admin dalam satu aplikasi web yang terintegrasi.
