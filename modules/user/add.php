<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$data   = ['username' => '', 'nama' => '', 'email' => '', 'password' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        http_response_code(403);
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        [$data, $errors] = validasiUser($_POST, true);
        if (!isset($errors['username']) && usernameTerpakai($pdo, $data['username'])) {
            $errors['username'] = 'Username sudah dipakai.';
        }
        if (!$errors) {
            [$foto, $errFoto] = simpanGambar($_FILES['foto'] ?? []);
            if ($errFoto) {
                $errors['foto'] = $errFoto;
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO users (username, nama, email, password, foto)
                     VALUES (:username, :nama, :email, :password, :foto)'
                );
                $stmt->execute([
                    ':username' => $data['username'], ':nama' => $data['nama'], ':email' => $data['email'],
                    ':password' => password_hash($data['password'], PASSWORD_DEFAULT), ':foto' => $foto,
                ]);
                setFlash('sukses', 'Pengguna berhasil ditambahkan.');
                redirect('user/list');
            }
        }
    }
}
?>
<section>
    <h2>Tambah Pengguna</h2>
    <?php if (isset($errors['umum'])): ?><div class="alert alert-gagal"><?= e($errors['umum']) ?></div><?php endif; ?>
    <form method="post" action="" enctype="multipart/form-data">
        <?= csrfField() ?>
        <div class="baris">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= e($data['username']) ?>" maxlength="30" required>
            <?php if (isset($errors['username'])): ?><div class="error"><?= e($errors['username']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" value="<?= e($data['nama']) ?>" maxlength="100" required>
            <?php if (isset($errors['nama'])): ?><div class="error"><?= e($errors['nama']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?= e($data['email']) ?>" maxlength="100" required>
            <?php if (isset($errors['email'])): ?><div class="error"><?= e($errors['email']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <?php if (isset($errors['password'])): ?><div class="error"><?= e($errors['password']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="foto">Foto (JPG/PNG/WEBP, maks 2 MB)</label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
            <?php if (isset($errors['foto'])): ?><div class="error"><?= e($errors['foto']) ?></div><?php endif; ?>
        </div>
        <button type="submit">Simpan</button>
        <a class="btn btn-warn" href="<?= e(url('user/list')) ?>">Batal</a>
    </form>
</section>
