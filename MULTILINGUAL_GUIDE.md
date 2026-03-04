# Panduan Implementasi Multilingual (English & Indonesian)

## Ringkasan Perubahan

Situs web Anda sekarang mendukung dua bahasa: **Inggris (en)** dan **Indonesia (id)** dengan URL yang dilokalisasi.

### URL Examples:
- **English Home**: `/en/`
- **Indonesian Home**: `/id/`
- **English News**: `/en/news`
- **Indonesian News**: `/id/berita`
- **English Lecturers**: `/en/lecturers`
- **Indonesian Lecturers**: `/id/dosen`

---

## File-File yang Ditambahkan/Diubah

### 1. **Middleware**
- `app/Http/Middleware/SetLocaleFromRoute.php` - Mengatur locale dari URL route

### 2. **Helper Functions**
- `app/Support/LocalizationHelper.php` - Helper class untuk localization
- `app/Support/helpers.php` - Global helper functions

**Helper Functions yang Tersedia:**
```php
// Generate localized route
localized_route('public.home', [], 'en');

// Get available locales
get_available_locales(); // Returns: ['en', 'id']

// Get locale name
get_locale_name('en'); // Returns: 'English'
get_locale_name('id'); // Returns: 'Bahasa Indonesia'

// Get URL in different locale
locale_url('en'); // Returns URL of current page in English
locale_url('id'); // Returns URL of current page in Indonesian
```

### 3. **Routes**
- Updated `routes/web.php` - Menambahkan locale prefix pada semua routes

### 4. **Translation Files**
- `lang/en/labels.php` - Label dan pesan umum dalam Bahasa Inggris
- `lang/id/labels.php` - Label dan pesan umum dalam Bahasa Indonesia
- `lang/en/fields.php` - Nama field form dalam Bahasa Inggris
- `lang/id/fields.php` - Nama field form dalam Bahasa Indonesia
- `lang/en/menu.php` - Menu items (sudah ada, diupdate)
- `lang/id/menu.php` - Menu items (sudah ada, diupdate)

### 5. **Components**
- `resources/views/components/public/language-switcher.blade.php` - Language switcher component
- Updated `resources/views/components/public/header.blade.php` - Updated untuk localized routes

### 6. **Configuration**
- Updated `composer.json` - Menambahkan autoload untuk helpers.php

---

## Cara Menggunakan

### 1. Menggunakan Translation Keys di Blade

Gunakan helper `__()` untuk mendapatkan terjemahan:

```blade
<!-- Menu Items -->
<a href="{{ localized_route('public.home') }}">{{ __('menu.beranda') }}</a>

<!-- Labels -->
<label>{{ __('labels.labels.name') }}</label>

<!-- Fields -->
<input type="text" placeholder="{{ __('fields.email') }}">
```

### 2. Menghasilkan Localized Routes di Blade

```blade
<!-- Current locale -->
<a href="{{ localized_route('public.news.index') }}">Berita</a>

<!-- Specific locale -->
<a href="{{ localized_route('public.news.index', [], 'en') }}">News</a>

<!-- With parameters -->
<a href="{{ localized_route('public.news.show', ['slug' => $news->slug]) }}">
    {{ $news->title }}
</a>
```

### 3. Using Language Switcher Component

Sudah diimplementasikan di header:
```blade
<x-public.language-switcher />
```

Component ini secara otomatis:
- Menampilkan bahasa yang tersedia
- Menunjukkan bahasa aktif saat ini
- Generate links untuk switch ke bahasa lain

### 4. Dalam Controller/Backend

```php
use App\Support\LocalizationHelper;

// Set locale
\Illuminate\Support\Facades\App::setLocale('id');

// Get translation
$greeting = __('labels.messages.welcome');

// Generate localized route
$url = LocalizationHelper::localizedRoute('public.home', [], 'en');
```

---

## Penambahan Translation Files

Untuk menambahkan translation baru, ikuti struktur berikut:

### Contoh: `lang/en/home.php`
```php
<?php

return [
    'welcome' => 'Welcome to our website',
    'hero_title' => 'Our Study Program',
    'description' => 'This is our study program description',
];
```

### Contoh: `lang/id/home.php`
```php
<?php

return [
    'welcome' => 'Selamat datang di website kami',
    'hero_title' => 'Program Studi Kami',
    'description' => 'Ini adalah deskripsi program studi kami',
];
```

Gunakan di Blade:
```blade
<h1>{{ __('home.hero_title') }}</h1>
<p>{{ __('home.description') }}</p>
```

---

## Best Practices

1. **Selalu gunakan `localized_route()`** untuk generate URL internal, bukan `route()`
   ```blade
   <!-- ✅ Correct -->
   <a href="{{ localized_route('public.home') }}">{{ __('menu.beranda') }}</a>
   
   <!-- ❌ Wrong -->
   <a href="{{ route('public.home') }}">{{ __('menu.beranda') }}</a>
   ```

2. **Pisahkan text ke translation files**
   ```blade
   <!-- ✅ Correct -->
   <p>{{ __('home.welcome_message') }}</p>
   
   <!-- ❌ Wrong -->
   <p>Selamat Datang</p>
   ```

3. **Gunakan nama yang konsisten** untuk translation keys:
   - `labels.labels.*` - untuk label umum
   - `labels.messages.*` - untuk pesan
   - `fields.*` - untuk nama field
   - `menu.*` - untuk menu items

4. **Fallback locale** sudah set ke 'en', jadi jika text tidak ditemukan di 'id', akan fallback ke 'en'

---

## Struktur Routes

Routes sekarang terstruktur seperti ini:

```
/en/
  / (home)
  /news (list berita)
  /news/{slug} (detail berita)
  /lecturers (daftar dosen)
  /facilities (daftar fasilitas)
  /facilities/{slug} (detail fasilitas)
  /about (tentang kami)
  /contact (hubungi kami)

/id/
  / (home)
  /berita (list berita)
  /berita/{slug} (detail berita)
  /dosen (daftar dosen)
  /fasilitas (daftar fasilitas)
  /fasilitas/{slug} (detail fasilitas)
  /tentang-kami (tentang kami)
  /kontak-kami (hubungi kami)
```

---

## SEO Considerations

1. **Hreflang Tags** - Pertimbangkan untuk menambahkan di layout untuk SEO:
   ```blade
   <link rel="alternate" hreflang="en" href="{{ localized_route($routeName, [], 'en') }}">
   <link rel="alternate" hreflang="id" href="{{ localized_route($routeName, [], 'id') }}">
   ```

2. **Sitemap** - Generate sitemap terpisah untuk setiap locale atau include semua dalam satu sitemap

3. **Metadata** - Pastikan meta description dan title juga diterjemahkan

---

## Troubleshooting

### Routes tidak ditemukan?
- Jalankan `composer dump-autoload` untuk re-index helpers
- Pastikan middleware `SetLocaleFromRoute` terdaftar di route group
- Clear route cache: `php artisan route:clear`

### Translations tidak tampil?
- Pastikan file translation exists di `lang/{locale}/`
- Cek folder structure: `lang/en/menu.php` bukan `lang/en/menu/en.php`
- Gunakan `__('filename.key')` format yang correct

### Locale tidak ter-set di middleware?
- Pastikan URL route mengandung parameter `{locale}` yang valid (en atau id)
- Cek `config/app.php` fallback_locale setting

---

## Next Steps

1. Update semua hardcoded text di views ke translation files
2. Pastikan semua internal links menggunakan `localized_route()`
3. Test semua routes dengan kedua locale
4. Tambah hreflang tags untuk SEO
5. Test language switcher di semua pages
