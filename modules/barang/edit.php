<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$barang = false;
if ($id !== false) {
    $stmt = $pdo->prepare('SELECT * FROM data_barang WHERE id_barang = :id');
    $stmt->execute([':id' => $id]);
    $barang = $stmt->fetch();
}

if (!$barang) {
    http_response_code(404);
    echo '<section><h2>Barang tidak ditemukan</h2><a class="btn" href="' . e(url('barang/list')) . '">Kembali</a></section>';
    return;
}

$data   = $barang;
$errors = [];
$gambarSekarang = $barang['gambar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        http_response_code(403);
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        [$data, $errors] = validasiBarang($_POST);
        if (!$errors) {
            [$gambar, $errGambar] = simpanGambar($_FILES['gambar'] ?? [], $barang['gambar']);
            if ($errGambar) {
                $errors['gambar'] = $errGambar;
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE data_barang SET nama = :nama, kategori = :kategori, harga_beli = :hb,
                            harga_jual = :hj, stok = :stok, gambar = :gambar WHERE id_barang = :id'
                );
                $stmt->execute([
                    ':nama' => $data['nama'], ':kategori' => $data['kategori'],
                    ':hb' => (int) $data['harga_beli'], ':hj' => (int) $data['harga_jual'],
                    ':stok' => (int) $data['stok'], ':gambar' => $gambar, ':id' => $id,
                ]);
                setFlash('sukses', 'Barang berhasil diubah.');
                redirect('barang/list');
            }
        }
    }
}
?>
<section>
    <h2>Ubah Barang</h2>
    <?php if (isset($errors['umum'])): ?><div class="alert alert-gagal"><?= e($errors['umum']) ?></div><?php endif; ?>
    <?php $tombol = 'Simpan Perubahan'; require __DIR__ . '/_form.php'; ?>
</section>
