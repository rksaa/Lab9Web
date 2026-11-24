# Lab9Web - Praktikum 9: PHP Modular

**Nama:** Riksa Siddiq Alrizki | **NIM:** 312510015 | **Kelas:** I253D | **Mata kuliah:** Pemrograman Web

## 1. Tujuan Project
Menerapkan modularisasi PHP: memisahkan template, function, konfigurasi, dan modul fitur, lalu mengendalikan halaman lewat router sederhana berbasis parameter `page` dengan whitelist. Aplikasi yang dimodularisasi adalah CRUD **Data Barang** (PHP + MySQL) dari praktikum 8.

## 2. Kebutuhan Perangkat
- XAMPP (Apache + MariaDB/MySQL) atau Laragon, PHP 8.1+ (ekstensi `pdo_mysql`)
- Browser dan Visual Studio Code

## 3. Cara Menjalankan
1. Salin folder `Lab9Web` ke `C:\xampp\htdocs\`.
2. Jalankan **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`, lalu impor `database/latihan1.sql`
   (membuat database `latihan1`, tabel `data_barang`, dan 4 data contoh).
   Jika tabel praktikum 8 sudah ada, cukup sesuaikan nama kolom pada modul `modules/barang/`.
4. Sesuaikan user/password pada `config/database.php` bila berbeda (default: `root`, tanpa password).
5. Buka `http://localhost/Lab9Web/`.

Latihan bagian C-E (include/require, template, function) ada di folder `latihan/`,
dibuka lewat `http://localhost/Lab9Web/latihan/01-include-require/uji-include.php` dan seterusnya.

## 4. Struktur Direktori
```
Lab9Web/
├── index.php              # titik masuk + router (whitelist)
├── .htaccess              # URL rewrite (opsional)
├── config/database.php    # satu-satunya koneksi database (PDO)
├── views/                 # header, footer, dashboard, 404
├── helpers/functions.php  # function umum (e, formatRupiah, hitungDiskon, validasi, CSRF, flash)
├── modules/
│   ├── barang/            # list, add, edit, delete (+ _form.php)
│   ├── halaman/           # profil, kontak
│   └── latihan/           # demo function
├── assets/css, assets/js
├── database/latihan1.sql
├── latihan/               # praktik bagian C, D, E modul
└── docs/screenshots/      # tempat screenshot laporan
```

## 5. Daftar Rute
| Rute (`index.php?page=`) | File | Keterangan |
|---|---|---|
| `dashboard` | views/dashboard.php | Ringkasan data barang (default) |
| `barang/list` | modules/barang/list.php | Daftar barang |
| `barang/add` | modules/barang/add.php | Tambah barang (POST) |
| `barang/edit&id=N` | modules/barang/edit.php | Ubah barang (POST) |
| `barang/delete` | modules/barang/delete.php | Hapus barang (hanya POST + CSRF) |
| `halaman/profil` | modules/halaman/profil.php | Profil |
| `halaman/kontak` | modules/halaman/kontak.php | Form kontak + `validasiEmail()` |
| `latihan/function` | modules/latihan/function.php | Demo seluruh function helper |

Rute yang tidak terdaftar (mis. `page=admin/rahasia`), kosong, atau bertipe array menghasilkan **HTTP 404** dan halaman `views/404.php`.

## 6. Langkah Praktikum
Ambil screenshot sendiri, simpan di `docs/screenshots/` dengan nama berikut, lalu biarkan tautan di bawah.

1. **Include vs require** - ubah nama `pesan.php` sementara, jalankan `uji-include.php` (muncul warning, baris sesudahnya tetap tampil) dan `uji-require.php` (fatal error, eksekusi berhenti).
   ![Screenshot 1](docs/screenshots/01-include-require.png)
2. **Template** - `latihan/02-template/home.php` dan `about.php` memakai `header.php` dan `footer.php` yang sama.
   ![Screenshot 2](docs/screenshots/02-template.png)
3. **Function** - `helpers.php` dipanggil dari `halaman-a.php` dan `halaman-b.php`.
   ![Screenshot 3](docs/screenshots/03-function.png)
4. **Struktur direktori** project Lab9Web di VS Code.
   ![Screenshot 4](docs/screenshots/04-struktur-direktori.png)
5. **Routing** - `index.php`, `?page=barang/list`, `?page=barang/add`.
   ![Screenshot 5](docs/screenshots/05-routing.png)
6. **Halaman 404** - `?page=admin/rahasia`.
   ![Screenshot 6](docs/screenshots/06-404.png)
7. **Pengujian CRUD** - tambah, ubah, hapus barang lengkap dengan pesan sukses.
   ![Screenshot 7](docs/screenshots/07-crud.png)

### Hasil pengujian (dijalankan pada PHP 8.3 + MariaDB)
| Pengujian | Hasil |
|---|---|
| Rute valid (dashboard, barang/list, barang/add, halaman/*, latihan/function) | 200 |
| `page=admin/rahasia`, `page=` (kosong), `page[]=x`, `page=../config/database` | 404 |
| `barang/edit` dengan id tidak ada | 404 |
| Tambah barang valid / input tidak valid | Tersimpan / pesan error per kolom |
| POST tanpa token CSRF | 403 |
| Hapus lewat URL (GET) | Ditolak, data tetap ada |
| Hapus lewat tombol (POST + CSRF) | Data terhapus |
| Menu aktif pada navigasi | Berfungsi |

## 7. Jawaban Pertanyaan Pemahaman
1. **Modularisasi** adalah membagi aplikasi menjadi bagian kecil berdasarkan tanggung jawabnya. Manfaat: kode dipakai ulang (header/footer tidak ditulis berulang), perubahan cukup di satu file, dan lebih mudah dirawat serta dikerjakan bersama.
2. Jika file tidak ditemukan, `include` hanya menghasilkan warning dan eksekusi lanjut, sedangkan `require` menghasilkan fatal error dan eksekusi berhenti.
3. Konfigurasi database wajib ada (tanpanya aplikasi tidak bisa jalan), sehingga dipakai `require`; akhiran `_once` mencegah file dimuat berulang agar tidak terjadi koneksi/deklarasi ganda.
4. **Parameter** adalah variabel pada deklarasi function, **argumen** adalah nilai yang dikirim saat function dipanggil, dan **nilai return** adalah nilai yang dikembalikan function ke pemanggilnya (bisa disimpan atau dipakai lagi).
5. Header dan footer sama di semua halaman, sedangkan konten berbeda. Dipisahkan agar tampilan konsisten dan perubahan layout cukup dilakukan sekali.
6. `index.php` adalah titik masuk aplikasi: membaca parameter `page`, memeriksanya pada daftar rute, lalu memuat header, konten, dan footer yang sesuai (router).
7. Whitelist membatasi halaman yang bisa dibuka hanya pada rute yang ditentukan aplikasi, sehingga input pengguna tidak bisa mengakses file lain.
8. Jika `page` langsung digabung menjadi path `require`, penyerang bisa memakai path traversal (`../`) untuk memuat file yang tidak dimaksudkan, misalnya file konfigurasi, sehingga data sensitif bocor atau kode berbahaya dieksekusi.
9. `RewriteCond` menentukan syarat aturan berlaku (di sini: bukan file dan bukan direktori yang ada), sedangkan `RewriteRule` mengarahkan URL yang memenuhi syarat ke `index.php?page=...`.
10. **config**: koneksi database; **views**: header, footer, dashboard, 404; **helpers**: function umum (validasi, format rupiah, escape, CSRF); **modules**: halaman list, add, edit, delete barang.

## 8. Kendala dan Cara Mengatasinya
Contoh di bawah adalah kendala umum pada praktikum ini; ganti atau tambahkan sesuai pengalaman sendiri.
- **CSS tidak tampil saat URL berubah** - path aset relatif ikut berubah. Diatasi dengan helper `baseUrl()` sehingga path aset selalu dibentuk dari folder aplikasi.
- **Function already declared** - file helper dimuat lebih dari sekali. Diatasi dengan `require_once`.
- **Redirect gagal ("headers already sent")** - header/footer sudah tercetak sebelum modul memproses form. Diatasi dengan `ob_start()` di `index.php` dan function `redirect()`.
- **URL rewrite 404 dari Apache** - `mod_rewrite` belum aktif atau `AllowOverride` bukan `All`. Diperiksa pada `httpd.conf` XAMPP.

## 9. Catatan Keamanan
- Router memakai whitelist, bukan menyusun path dari input URL.
- Semua keluaran HTML di-escape dengan `e()`; query memakai prepared statement (PDO).
- Tambah/ubah/hapus lewat POST dengan token CSRF dan validasi di server.
- File di `config/`, `helpers/`, `views/`, `modules/` menolak akses langsung (`APP_ROOT`).
