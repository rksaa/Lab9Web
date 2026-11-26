<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

if (sudahLogin()) {
    redirect('dashboard');
}

$username = '';
$error    = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        http_response_code(403);
        $error = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $stmt = $pdo->prepare('SELECT id_user, username, nama, password FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // cegah session fixation
            $_SESSION['user'] = [
                'id'       => (int) $user['id_user'],
                'username' => $user['username'],
                'nama'     => $user['nama'],
            ];
            setFlash('sukses', 'Selamat datang, ' . $user['nama'] . '.');
            redirect('dashboard');
        }
        // Pesan sengaja sama untuk username salah maupun password salah
        $error = 'Username atau password salah.';
    }
}
?>
<section class="login-box">
    <h2>Login</h2>
    <?php if ($error): ?><div class="alert alert-gagal"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="">
        <?= csrfField() ?>
        <div class="baris">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= e($username) ?>" required autofocus>
        </div>
        <div class="baris">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Masuk</button>
    </form>
</section>
