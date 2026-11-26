<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$daftar = $pdo->query('SELECT id_user, username, nama, email, foto FROM users ORDER BY id_user ASC')->fetchAll();
$idSaya = userSekarang()['id'] ?? 0;
?>
<section>
    <h2>Data Pengguna</h2>
    <a class="btn" href="<?= e(url('user/add')) ?>">Tambah Pengguna</a>
    <div class="tabel-wrap">
    <table>
        <thead>
            <tr><th>No</th><th>Foto</th><th>Username</th><th>Nama</th><th>Email</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if (!$daftar): ?>
            <tr><td colspan="6">Belum ada data.</td></tr>
        <?php endif; ?>
        <?php foreach ($daftar as $i => $u): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td>
                    <?php if (!empty($u['foto'])): ?>
                        <img class="thumb" src="<?= e(baseUrl('assets/uploads/' . $u['foto'])) ?>" alt="Foto <?= e($u['nama']) ?>">
                    <?php else: ?>-<?php endif; ?>
                </td>
                <td><?= e($u['username']) ?></td>
                <td><?= e(ringkasTeks($u['nama'], 40)) ?></td>
                <td><?= e(ringkasTeks($u['email'], 40)) ?></td>
                <td class="aksi">
                    <a class="btn btn-warn" href="<?= e(url('user/edit', ['id' => $u['id_user']])) ?>">Ubah</a>
                    <?php if ((int) $u['id_user'] === $idSaya): ?>
                        <em>(akun Anda)</em>
                    <?php else: ?>
                        <form method="post" action="<?= e(url('user/delete')) ?>" data-konfirmasi="Hapus pengguna ini?">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int) $u['id_user'] ?>">
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</section>
