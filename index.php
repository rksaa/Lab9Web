<?php
define('APP_ROOT', __DIR__);
session_start();
ob_start();

require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/config/database.php';

$routes = [
    'auth/login'       => ['file' => __DIR__ . '/modules/auth/login.php',        'title' => 'Login', 'public' => true],
    'auth/logout'      => ['file' => __DIR__ . '/modules/auth/logout.php',       'title' => 'Logout'],
    'user/list'        => ['file' => __DIR__ . '/modules/user/list.php',         'title' => 'Data Pengguna'],
    'user/add'         => ['file' => __DIR__ . '/modules/user/add.php',          'title' => 'Tambah Pengguna'],
    'user/edit'        => ['file' => __DIR__ . '/modules/user/edit.php',         'title' => 'Ubah Pengguna'],
    'user/delete'      => ['file' => __DIR__ . '/modules/user/delete.php',       'title' => 'Hapus Pengguna'],
    'dashboard'        => ['file' => __DIR__ . '/views/dashboard.php',          'title' => 'Dashboard'],
    'barang/list'      => ['file' => __DIR__ . '/modules/barang/list.php',      'title' => 'Data Barang'],
    'barang/add'       => ['file' => __DIR__ . '/modules/barang/add.php',       'title' => 'Tambah Barang'],
    'barang/edit'      => ['file' => __DIR__ . '/modules/barang/edit.php',      'title' => 'Ubah Barang'],
    'barang/delete'    => ['file' => __DIR__ . '/modules/barang/delete.php',    'title' => 'Hapus Barang'],
    'halaman/profil'   => ['file' => __DIR__ . '/modules/halaman/profil.php',   'title' => 'Profil'],
    'halaman/kontak'   => ['file' => __DIR__ . '/modules/halaman/kontak.php',   'title' => 'Kontak'],
    'latihan/function' => ['file' => __DIR__ . '/modules/latihan/function.php', 'title' => 'Latihan Function'],
];

$page = $_GET['page'] ?? 'dashboard';

if (!is_string($page) || !array_key_exists($page, $routes)) {
    http_response_code(404);
    $contentFile = __DIR__ . '/views/404.php';
    $pageTitle   = '404 - Tidak ditemukan';
} else {
    // Semua rute wajib login, kecuali yang ditandai 'public'
    if (empty($routes[$page]['public']) && !sudahLogin()) {
        setFlash('gagal', 'Silakan login terlebih dahulu.');
        redirect('auth/login');
    }
    $contentFile = $routes[$page]['file'];
    $pageTitle   = $routes[$page]['title'];
}

require_once __DIR__ . '/views/header.php';
require $contentFile;
require_once __DIR__ . '/views/footer.php';