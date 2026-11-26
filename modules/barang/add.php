<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$data   = ['nama' => '', 'kategori' => '', 'harga_beli' => '', 'harga_jual' => '', 'stok' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        http_response_code(403);
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        [$data, $errors] = validasiBarang($_POST);
        if (!$errors) {
            $stmt = $pdo->prepare(
                'INSERT INTO data_barang (nama, kategori, harga_beli, harga_jual, stok)
                 VALUES (:nama, :kategori, :hb, :hj, :stok)'
            );
            $stmt->execute([
                ':nama' => $data['nama'], ':kategori' => $data['kategori'],
                ':hb' => (int) $data['harga_beli'], ':hj' => (int) $data['harga_jual'], ':stok' => (int) $data['stok'],
            ]);
            setFlash('sukses', 'Barang berhasil ditambahkan.');
            redirect('barang/list');
        }
    }
}
?>
<section>
    <h2>Tambah Barang</h2>
    <?php if (isset($errors['umum'])): ?><div class="alert alert-gagal"><?= e($errors['umum']) ?></div><?php endif; ?>
    <?php $tombol = 'Simpan'; require __DIR__ . '/_form.php'; ?>
</section>