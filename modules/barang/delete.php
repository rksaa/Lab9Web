<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('gagal', 'Penghapusan harus dilakukan melalui tombol Hapus.');
    redirect('barang/list');
}
if (!csrfValid()) {
    setFlash('gagal', 'Token keamanan tidak valid.');
    redirect('barang/list');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    setFlash('gagal', 'ID barang tidak valid.');
    redirect('barang/list');
}

// Ambil nama file gambar dulu agar bisa ikut dihapus
$stmt = $pdo->prepare('SELECT gambar FROM data_barang WHERE id_barang = :id');
$stmt->execute([':id' => $id]);
$gambar = $stmt->fetchColumn();

$stmt = $pdo->prepare('DELETE FROM data_barang WHERE id_barang = :id');
$stmt->execute([':id' => $id]);

if ($stmt->rowCount() > 0) {
    hapusGambar($gambar ?: null);
    setFlash('sukses', 'Barang berhasil dihapus.');
} else {
    setFlash('gagal', 'Barang tidak ditemukan.');
}
redirect('barang/list');
