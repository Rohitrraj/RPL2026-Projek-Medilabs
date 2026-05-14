# MediLabs 25%

Project Laravel tahap 25% untuk UI MediLabs. Data masih dummy/static dan belum memakai database untuk proses reservasi.

## Menjalankan Project

1. Buka terminal di folder project:

   ```bash
   cd "/Users/rohitraj/Documents/New project"
   ```

2. Jalankan server Laravel:

   ```bash
   php artisan serve
   ```

3. Buka browser ke:

   ```text
   http://127.0.0.1:8000
   ```

## Halaman Yang Dibuat

- `/` - Landing page
- `/daftar` - Daftar akun
- `/login` - Login
- `/data-pasien` - Form data pasien
- `/reservasi` - Form reservasi
- `/layanan` - Detail layanan populer
- `/hasil-reservasi` - Hasil reservasi

## Cara Mengganti Gambar

Semua gambar placeholder ada di:

```text
public/assets/images/
```

Cara paling mudah:

1. Siapkan gambar pengganti dengan nama yang sama.
2. Letakkan ke folder `public/assets/images/`.
3. Timpa file lama.
4. Refresh browser.

Daftar file gambar:

- `logo.svg` - logo kecil di navbar
- `doctor-card.svg` - ilustrasi dokter di login/daftar
- `hospital.svg` - gambar hero landing page
- `reservation-team.svg` - gambar di form reservasi
- `patient-info.svg` - gambar kartu informasi pasien
- `lab.svg` - gambar detail layanan

Jika nama file berbeda, ubah path gambar di file Blade yang sesuai, misalnya:

```blade
<img src="{{ asset('assets/images/nama-gambar-baru.png') }}" alt="Deskripsi gambar">
```

File Blade berada di:

```text
resources/views/
```

## Catatan Pengembangan

- Styling memakai CSS custom di `public/assets/css/medilabs.css`.
- JavaScript kecil untuk tombol cetak ada di `public/assets/js/medilabs.js`.
- Routing ada di `routes/web.php`.
- Dummy data halaman ada di `app/Http/Controllers/MediLabsController.php`.
