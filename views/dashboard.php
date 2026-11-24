<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

try {
    $ringkas = $pdo->query(
        'SELECT COUNT(*) AS jenis, COALESCE(SUM(stok),0) AS total_stok,
                COALESCE(SUM(harga_jual * stok),0) AS nilai_stok
         FROM data_barang'
    )->fetch();
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    $ringkas = null;
}
?>
<section>
    <h2>Dashboard</h2>
    <?php if ($ringkas === null): ?>
        <div class="alert alert-gagal">Tabel data_barang belum tersedia. Impor file database/latihan1.sql terlebih dahulu.</div>
    <?php else: ?>
        <div class="cards">
            <div class="card"><span>Jenis barang</span><strong><?= (int) $ringkas['jenis'] ?></strong></div>
            <div class="card"><span>Total stok</span><strong><?= (int) $ringkas['total_stok'] ?></strong></div>
            <div class="card"><span>Nilai stok (harga jual)</span><strong><?= e(formatRupiah((float) $ringkas['nilai_stok'])) ?></strong></div>
        </div>
    <?php endif; ?>
    <p>Ini adalah halaman dashboard. Anda dapat melihat ringkasan informasi di sini.</p>
    <a class="btn" href="<?= e(url('barang/list')) ?>">Kelola Data Barang</a>
</section>