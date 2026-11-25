<?php
// Satu-satunya tempat koneksi database dikelola.
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$host = 'localhost';
$db   = 'latihan1';   // sesuaikan dengan database praktikum 8
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // Detail error tidak ditampilkan ke pengguna
    error_log($e->getMessage());
    http_response_code(500);
    exit('Terjadi kesalahan pada koneksi database.');
}
