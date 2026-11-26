<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$user = false;
if ($id !== false) {
    $stmt = $pdo->prepare('SELECT id_user, username, nama, email, foto FROM users WHERE id_user = :id');
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();
}

if (!$user) {
    http_response_code(404);
    echo '<section><h2>Pengguna tidak ditemukan</h2><a class="btn" href="' . e(url('user/list')) . '">Kembali</a></section>';
    return;
}

$data   = $user + ['password' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        http_response_code(403);
        $errors['umum'] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        [$data, $errors] = validasiUser($_POST, false); // password boleh kosong = tidak diubah
        if (!isset($errors['username']) && usernameTerpakai($pdo, $data['username'], $id)) {
            $errors['username'] = 'Username sudah dipakai.';
        }
        if (!$errors) {
            [$foto, $errFoto] = simpanGambar($_FILES['foto'] ?? [], $user['foto']);
            if ($errFoto) {
                $errors['foto'] = $errFoto;
            } else {
                $sql    = 'UPDATE users SET username = :username, nama = :nama, email = :email, foto = :foto';
                $params = [
                    ':username' => $data['username'], ':nama' => $data['nama'],
                    ':email' => $data['email'], ':foto' => $foto, ':id' => $id,
                ];
                if ($data['password'] !== '') {
                    $sql .= ', password = :password';
                    $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                $pdo->prepare($sql . ' WHERE id_user = :id')->execute($params);

                if ($id === (userSekarang()['id'] ?? 0)) { // perbarui nama di navigasi
                    $_SESSION['user']['username'] = $data['username'];
                    $_SESSION['user']['nama']     = $data['nama'];
                }
                setFlash('sukses', 'Pengguna berhasil diubah.');
                redirect('user/list');
            }
        }
    }
}
?>
<section>
    <h2>Ubah Pengguna</h2>
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
            <label for="password">Password baru</label>
            <input type="password" id="password" name="password">
            <div class="bantuan">Kosongkan jika tidak ingin mengganti password.</div>
            <?php if (isset($errors['password'])): ?><div class="error"><?= e($errors['password']) ?></div><?php endif; ?>
        </div>
        <div class="baris">
            <label for="foto">Foto (JPG/PNG/WEBP, maks 2 MB)</label>
            <?php if (!empty($user['foto'])): ?>
                <img class="thumb" src="<?= e(baseUrl('assets/uploads/' . $user['foto'])) ?>" alt="Foto saat ini" style="margin-bottom:6px;">
            <?php endif; ?>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
            <?php if (isset($errors['foto'])): ?><div class="error"><?= e($errors['foto']) ?></div><?php endif; ?>
        </div>
        <button type="submit">Simpan Perubahan</button>
        <a class="btn btn-warn" href="<?= e(url('user/list')) ?>">Batal</a>
    </form>
</section>
