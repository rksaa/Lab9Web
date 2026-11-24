<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');
?>
<form method="post" action="">
    <?= csrfField() ?>
    <div class="baris">
        <label for="nama">Nama Barang</label>
        <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" maxlength="100" required>
        <?php if (isset($errors['nama'])): ?><div class="error"><?= e($errors['nama']) ?></div><?php endif; ?>
    </div>
    <div class="baris">
        <label for="kategori">Kategori</label>
        <input type="text" id="kategori" name="kategori" value="<?= e($data['kategori']) ?>" maxlength="50" required>
        <?php if (isset($errors['kategori'])): ?><div class="error"><?= e($errors['kategori']) ?></div><?php endif; ?>
    </div>
    <div class="baris">
        <label for="harga_beli">Harga Beli (Rp)</label>
        <input type="number" id="harga_beli" name="harga_beli" min="0" value="<?= e($data['harga_beli']) ?>" required>
        <?php if (isset($errors['harga_beli'])): ?><div class="error"><?= e($errors['harga_beli']) ?></div><?php endif; ?>
    </div>
    <div class="baris">
        <label for="harga_jual">Harga Jual (Rp)</label>
        <input type="number" id="harga_jual" name="harga_jual" min="0" value="<?= e($data['harga_jual']) ?>" required>
        <?php if (isset($errors['harga_jual'])): ?><div class="error"><?= e($errors['harga_jual']) ?></div><?php endif; ?>
    </div>
    <div class="baris">
        <label for="stok">Stok</label>
        <input type="number" id="stok" name="stok" min="0" value="<?= e($data['stok']) ?>" required>
        <?php if (isset($errors['stok'])): ?><div class="error"><?= e($errors['stok']) ?></div><?php endif; ?>
    </div>
    <button type="submit"><?= e($tombol) ?></button>
    <a class="btn btn-warn" href="<?= e(url('barang/list')) ?>">Batal</a>
</form>