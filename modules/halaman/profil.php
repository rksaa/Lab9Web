<?php defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.'); ?>
<?php
$deskripsi = 'Aplikasi ini dibuat untuk praktikum Pemrograman Web sebagai latihan modularisasi PHP: '
           . 'template bersama, function helper, struktur direktori, dan router dengan whitelist.';
?>
<section>
    <h2>Profil</h2>
    <table>
        <tr><th>Nama</th><td>Riksa Siddiq Alrizki</td></tr>
        <tr><th>NIM</th><td>312510015</td></tr>
        <tr><th>Kelas</th><td>I253A/td></tr>
        <tr><th>Program Studi</th><td>Teknik Informatika</td></tr>
    </table>
    <p><?= e(ringkasTeks($deskripsi, 120)) ?></p>
</section>