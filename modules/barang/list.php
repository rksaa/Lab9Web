<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$daftar = $pdo->query('SELECT * FROM data_barang ORDER BY id_barang DESC')->fetchAll();
?>
<section>
    <h2>Data Barang</h2>
    <a class="btn" href="<?= e(url('barang/add')) ?>">Tambah Barang</a>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama</th><th>Kategori</th>
                <th class="angka">Harga Beli</th><th class="angka">Harga Jual</th>
                <th class="angka">Stok</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$daftar): ?>
            <tr><td colspan="7">Belum ada data.</td></tr>
        <?php endif; ?>
        <?php foreach ($daftar as $i => $b): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e(ringkasTeks($b['nama'], 40)) ?></td>
                <td><?= e($b['kategori']) ?></td>
                <td class="angka"><?= e(formatRupiah((float) $b['harga_beli'])) ?></td>
                <td class="angka"><?= e(formatRupiah((float) $b['harga_jual'])) ?></td>
                <td class="angka"><?= (int) $b['stok'] ?></td>
                <td class="aksi">
                    <a class="btn btn-warn" href="<?= e(url('barang/edit', ['id' => $b['id_barang']])) ?>">Ubah</a>
                    <form method="post" action="<?= e(url('barang/delete')) ?>"
                          onsubmit="return confirm('Hapus barang ini?');">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= (int) $b['id_barang'] ?>">
                        <button type="submit" class="btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>