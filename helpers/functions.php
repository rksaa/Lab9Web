<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

/* ---------- Tampilan & URL ---------- */
function e(?string $nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

function baseUrl(string $path = ''): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    return $dir . '/' . ltrim($path, '/');
}

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

function menuAktif(string $prefix): string
{
    global $page;
    $sekarang = is_string($page ?? null) ? $page : '';
    return ($sekarang === $prefix || str_starts_with($sekarang, rtrim($prefix, '/') . '/')) ? 'active' : '';
}

/* ---------- Format & utilitas ---------- */
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

/* ---------- Pesan sukses/gagal ---------- */
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

/* ---------- CSRF ---------- */
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