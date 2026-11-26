<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

// Penghapusan hanya lewat POST + token CSRF, bukan dengan membuka URL (GET).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('gagal', 'Penghapusan harus dilakukan melalui tombol Hapus.');
    redirect('user/list');
}
if (!csrfValid()) {
    setFlash('gagal', 'Token keamanan tidak valid.');
    redirect('user/list');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    setFlash('gagal', 'ID pengguna tidak valid.');
    redirect('user/list');
}
if ($id === (userSekarang()['id'] ?? 0)) {
    setFlash('gagal', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
    redirect('user/list');
}

// Ambil nama file foto dulu agar bisa ikut dihapus
$stmt = $pdo->prepare('SELECT foto FROM users WHERE id_user = :id');
$stmt->execute([':id' => $id]);
$foto = $stmt->fetchColumn();

$stmt = $pdo->prepare('DELETE FROM users WHERE id_user = :id');
$stmt->execute([':id' => $id]);

if ($stmt->rowCount() > 0) {
    hapusGambar($foto ?: null);
    setFlash('sukses', 'Pengguna berhasil dihapus.');
} else {
    setFlash('gagal', 'Pengguna tidak ditemukan.');
}
redirect('user/list');
