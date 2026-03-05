# Web Multi Tenancy

Project website multi-tenancy berbasis Laravel 12 + Filament 5.

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm
- Database (sesuaikan dengan `.env`)

## Setup

### Opsi cepat (disarankan)

```bash
composer run setup
```

Script ini akan menjalankan instalasi dependency, membuat `.env` jika belum ada, generate app key, migrate database, install package frontend, lalu build asset.

### Opsi manual

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan shield:generate --all --no-interaction
npm install
npm run build
```

Tambahkan juga konfigurasi berikut ke file `.env`:

```dotenv
SUPERADMIN_PANEL_DOMAIN=superadmin.local.test
ADMIN_PANEL_DOMAIN=admin.local.test
SUPERADMIN_PASSWORD=dor4emoN_++
```

## Menjalankan aplikasi (development)

```bash
composer run dev
```

Perintah di atas menjalankan server Laravel, queue listener, log tailing, dan Vite secara bersamaan.

Jika hanya butuh frontend:

```bash
npm run dev
```

## Testing

```bash
composer run test
```

## Seeding Flow Project

### 1) Seed awal saat install

```bash
php artisan migrate --seed
```

`DatabaseSeeder` hanya membuat:
- role `super_admin`
- user `superadmin@mail.com`

Password super admin mengikuti env `SUPERADMIN_PASSWORD` (default: `default123` jika env belum diisi).

Setelah seeding awal, pastikan permission Filament Shield sudah tergenerate:

```bash
php artisan shield:generate --all
```

### 2) Tambah Program Studi manual

Buat data Program Studi terlebih dahulu (misalnya dari panel admin).

### 3) Seed konten akademik saat dibutuhkan

```bash
php artisan db:seed --class=AcademicContentSeeder
```

Seeder ini akan:
- menampilkan daftar Program Studi
- meminta input ID Program Studi
- menjalankan `CategorySeeder`, `LecturerSeeder`, `FacilitySeeder`, dan `NewsSeeder` untuk prodi terpilih
- jika input dikosongkan, seeding dijalankan untuk semua prodi

`CategorySeeder` membuat kategori default bilingual:
- `Berita` / `News`
- `Pengumuman` / `Announcements`

### 4) Jalankan per seeder (opsional)

```bash
php artisan db:seed --class=LecturerSeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=NewsSeeder
php artisan db:seed --class=CategorySeeder
```

Masing-masing seeder di atas juga mendukung pemilihan ID Program Studi saat dijalankan.
