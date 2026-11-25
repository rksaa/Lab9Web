<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

/* ---------- Tampilan & URL ---------- */

// Escape teks sebelum dicetak ke HTML (cegah XSS)
function e(?string $nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

// Alamat folder aplikasi, mis. /Lab9Web/
function baseUrl(string $path = ''): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    return $dir . '/' . ltrim($path, '/');
}

// Bentuk tautan router: url('barang/edit', ['id' => 3]) => index.php?page=barang/edit&id=3
function url(string $page = 'dashboard', array $params = []): string
{
    $query = 'page=' . str_replace('%2F', '/', rawurlencode($page));
    if ($params) {
        $query .= '&' . http_build_query($params);
    }
    return baseUrl('index.php') . '?' . $query;
}

function redirect(string $page, array $params = []): never
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Location: ' . url($page, $params));
    exit;
}

// Menandai menu aktif pada navigasi
function menuAktif(string $prefix): string
{
    global $page;
    $sekarang = is_string($page ?? null) ? $page : '';
    return ($sekarang === $prefix || str_starts_with($sekarang, rtrim($prefix, '/') . '/')) ? 'active' : '';
}

/* ---------- Format & utilitas (Latihan 2) ---------- */

// Pembungkus mb_* dengan cadangan jika ekstensi mbstring tidak aktif
function panjangTeks(string $teks): int
{
    return function_exists('mb_strlen') ? mb_strlen($teks) : strlen($teks);
}

function potongTeks(string $teks, int $mulai, int $panjang): string
{
    return function_exists('mb_substr') ? mb_substr($teks, $mulai, $panjang) : substr($teks, $mulai, $panjang);
}

function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDiskon(float $harga, float $persen = 10): float
{
    return $harga * ($persen / 100);
}

function validasiEmail(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function ringkasTeks(string $teks, int $maks = 100): string
{
    $teks = trim($teks);
    if (panjangTeks($teks) <= $maks) {
        return $teks;
    }
    return rtrim(potongTeks($teks, 0, $maks)) . '...';
}

/* ---------- Latihan function (bagian E) ---------- */

function konversiCelciusKeFahrenheit(float $c): float
{
    return ($c * 9 / 5) + 32;
}

function tentukanStatusNilai(float $nilai): string
{
    if ($nilai >= 85) return 'A';
    if ($nilai >= 70) return 'B';
    if ($nilai >= 55) return 'C';
    if ($nilai >= 40) return 'D';
    return 'E';
}

function hitungTotal(float $harga, int $jumlah): float
{
    return $harga * $jumlah;
}

/* ---------- Pesan sukses/gagal (tantangan mandiri) ---------- */

function setFlash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function tampilkanFlash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $kelas = $f['tipe'] === 'sukses' ? 'alert-sukses' : 'alert-gagal';
    echo '<div class="alert ' . $kelas . '">' . e($f['pesan']) . '</div>';
}

/* ---------- Perlindungan CSRF ---------- */

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

function csrfValid(): bool
{
    $kirim = $_POST['csrf'] ?? '';
    return is_string($kirim) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $kirim);
}

/* ---------- Validasi form barang ---------- */

// Mengembalikan [data_bersih, daftar_error]
function validasiBarang(array $input): array
{
    $data = [
        'nama'       => trim((string) ($input['nama'] ?? '')),
        'kategori'   => trim((string) ($input['kategori'] ?? '')),
        'harga_beli' => trim((string) ($input['harga_beli'] ?? '')),
        'harga_jual' => trim((string) ($input['harga_jual'] ?? '')),
        'stok'       => trim((string) ($input['stok'] ?? '')),
    ];
    $errors = [];

    if ($data['nama'] === '' || panjangTeks($data['nama']) > 100) {
        $errors['nama'] = 'Nama wajib diisi (maksimal 100 karakter).';
    }
    if ($data['kategori'] === '' || panjangTeks($data['kategori']) > 50) {
        $errors['kategori'] = 'Kategori wajib diisi (maksimal 50 karakter).';
    }
    foreach (['harga_beli' => 'Harga beli', 'harga_jual' => 'Harga jual', 'stok' => 'Stok'] as $k => $label) {
        if (filter_var($data[$k], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $errors[$k] = $label . ' harus berupa bilangan bulat 0 atau lebih.';
        }
    }
    return [$data, $errors];
}

/* ---------- Upload gambar ---------- */

// Mengecilkan gambar (sisi terpanjang maks $maks piksel). Butuh ekstensi GD.
// Mengembalikan false jika GD tidak tersedia, agar pemanggil memakai file asli.
function perkecilGambar(string $sumber, string $tujuan, int $tipe, int $maks = 600): bool
{
    $pembuka = [
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG  => 'imagecreatefrompng',
        IMAGETYPE_WEBP => 'imagecreatefromwebp',
    ];
    $fungsi = $pembuka[$tipe] ?? null;
    if (!$fungsi || !function_exists($fungsi) || !function_exists('imagecreatetruecolor')) {
        return false;
    }
    $asli = @$fungsi($sumber);
    if (!$asli) {
        return false;
    }

    $lebar  = imagesx($asli);
    $tinggi = imagesy($asli);
    $skala  = min(1, $maks / max($lebar, $tinggi));
    $baruL  = max(1, (int) round($lebar * $skala));
    $baruT  = max(1, (int) round($tinggi * $skala));

    $kecil = imagecreatetruecolor($baruL, $baruT);
    if ($tipe !== IMAGETYPE_JPEG) { // pertahankan transparansi PNG/WEBP
        imagealphablending($kecil, false);
        imagesavealpha($kecil, true);
        imagefill($kecil, 0, 0, imagecolorallocatealpha($kecil, 0, 0, 0, 127));
    }
    imagecopyresampled($kecil, $asli, 0, 0, 0, 0, $baruL, $baruT, $lebar, $tinggi);

    return match ($tipe) {
        IMAGETYPE_JPEG => imagejpeg($kecil, $tujuan, 80),
        IMAGETYPE_PNG  => imagepng($kecil, $tujuan, 6),
        IMAGETYPE_WEBP => imagewebp($kecil, $tujuan, 80),
    };
}

// Mengembalikan [namaFile, pesanError]. Jika tidak ada file baru, namaFile = $lama.
function simpanGambar(array $file, ?string $lama = null): array
{
    $kode = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($kode === UPLOAD_ERR_NO_FILE) {
        return [$lama, null];
    }
    if ($kode !== UPLOAD_ERR_OK) {
        return [$lama, 'Upload gambar gagal (kode ' . $kode . '). Pastikan ukuran maksimal 2 MB.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return [$lama, 'Ukuran gambar maksimal 2 MB.'];
    }

    $info = @getimagesize($file['tmp_name']);
    $ekstensi = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if ($info === false || !isset($ekstensi[$info[2]])) {
        return [$lama, 'Gambar harus berformat JPG, PNG, atau WEBP.'];
    }

    $folder = APP_ROOT . '/uploads';
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
    $nama   = bin2hex(random_bytes(8)) . '.' . $ekstensi[$info[2]];
    $tujuan = $folder . '/' . $nama;

    // Coba kecilkan dulu; jika GD tidak aktif, simpan file asli.
    if (!perkecilGambar($file['tmp_name'], $tujuan, $info[2]) && !move_uploaded_file($file['tmp_name'], $tujuan)) {
        return [$lama, 'Gambar tidak bisa disimpan di folder uploads.'];
    }
    hapusGambar($lama);
    return [$nama, null];
}

function hapusGambar(?string $nama): void
{
    if (!$nama || basename($nama) !== $nama) {
        return;
    }
    $path = APP_ROOT . '/uploads/' . $nama;
    if (is_file($path)) {
        unlink($path);
    }
}
