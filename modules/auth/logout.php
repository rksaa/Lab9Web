<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

// Logout hanya lewat POST + token CSRF (tombol Logout di navigasi).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrfValid()) {
    redirect('dashboard');
}

$_SESSION = [];
session_regenerate_id(true);
setFlash('sukses', 'Anda sudah logout.');
redirect('auth/login');
