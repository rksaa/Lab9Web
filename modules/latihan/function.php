<?php
defined('APP_ROOT') or exit('Akses langsung tidak diizinkan.');

$celcius = 36.6;
$nilai   = 78;
$harga   = 200000;
$jumlah  = 3;
$total   = hitungTotal($harga, $jumlah);
?>
<section>
    <h2>Latihan Function (helpers/functions.php)</h2>
    <table>
        <thead><tr><th>Function</th><th>Pemanggilan</th><th>Hasil</th></tr></thead>
        <tbody>
            <tr><td>konversiCelciusKeFahrenheit()</td><td><?= $celcius ?> &deg;C</td><td><?= e((string) konversiCelciusKeFahrenheit($celcius)) ?> &deg;F</td></tr>
            <tr><td>tentukanStatusNilai()</td><td>nilai <?= $nilai ?></td><td><?= e(tentukanStatusNilai($nilai)) ?></td></tr>
            <tr><td>hitungTotal()</td><td><?= e(formatRupiah($harga)) ?> x <?= $jumlah ?></td><td><?= e(formatRupiah($total)) ?></td></tr>
            <tr><td>hitungDiskon()</td><td>total, diskon default 10%</td><td><?= e(formatRupiah(hitungDiskon($total))) ?></td></tr>
            <tr><td>hitungDiskon()</td><td>total, diskon 15%</td><td><?= e(formatRupiah(hitungDiskon($total, 15))) ?></td></tr>
            <tr><td>validasiEmail()</td><td>"mhs@kampus.ac.id"</td><td><?= validasiEmail('mhs@kampus.ac.id') ? 'valid' : 'tidak valid' ?></td></tr>
            <tr><td>validasiEmail()</td><td>"bukan-email"</td><td><?= validasiEmail('bukan-email') ? 'valid' : 'tidak valid' ?></td></tr>
            <tr><td>ringkasTeks()</td><td>maks 30 karakter</td><td><?= e(ringkasTeks('Modularisasi membuat kode lebih mudah dirawat dan dipakai ulang.', 30)) ?></td></tr>
            <tr><td>e()</td><td>&lt;script&gt;alert("uji")&lt;/script&gt;</td><td><?= e('<script>alert("uji")</script>') ?></td></tr>
        </tbody>
    </table>
</section>
