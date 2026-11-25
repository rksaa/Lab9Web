<?php defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Aplikasi') ?> | Lab9Web</title>
    <link rel="stylesheet" href="<?= e(baseUrl('assets/css/style.css')) ?>">
</head>
<body>
<div class="container">
    <header>
        <h1>Aplikasi PHP Modular</h1>
        <p>Praktikum 9 Pemrograman Web - CRUD Data Barang</p>
    </header>
    <nav>
        <a class="<?= menuAktif('dashboard') ?>" href="<?= e(url('dashboard')) ?>">Dashboard</a>
        <a class="<?= menuAktif('barang') ?>" href="<?= e(url('barang/list')) ?>">Data Barang</a>
        <a class="<?= menuAktif('latihan') ?>" href="<?= e(url('latihan/function')) ?>">Latihan Function</a>
        <a class="<?= menuAktif('halaman/profil') ?>" href="<?= e(url('halaman/profil')) ?>">Profil</a>
        <a class="<?= menuAktif('halaman/kontak') ?>" href="<?= e(url('halaman/kontak')) ?>">Kontak</a>
    </nav>
    <main>
        <?php tampilkanFlash(); ?>
