<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$nama = $email = $pesan = '';
$errors = [];
$terkirim = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim((string) ($_POST['nama'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $pesan = trim((string) ($_POST['pesan'] ?? ''));

    if ($nama === '')           { $errors['nama']  = 'Nama wajib diisi.'; }
    if (!validasiEmail($email)) { $errors['email'] = 'Format email tidak valid.'; }
    if ($pesan === '')          { $errors['pesan'] = 'Pesan wajib diisi.'; }
    $terkirim = !$errors;
}
?>
<section>
    <h2>Kontak</h2>
    <?php if ($terkirim): ?>
        <div class="alert alert-sukses">Terima kasih, <?= e($nama) ?>. Pesan Anda: "<?= e(ringkasTeks($pesan, 80)) ?>"</div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="baris">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= e($nama) ?>">
            <?php if (isset($errors['nama'])): ?><div class="error"><?= e($errors['nama']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?= e($email) ?>">
            <?php if (isset($errors['email'])): ?><div class="error"><?= e($errors['email']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" rows="4"><?= e($pesan) ?></textarea>
            <?php if (isset($errors['pesan'])): ?><div class="error"><?= e($errors['pesan']) ?></div><?php endif; ?>
        </div>
        <button type="submit">Kirim</button>
    </form>
</section>