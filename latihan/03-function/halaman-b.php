<?php
require_once __DIR__ . '/helpers.php';
echo "<h2>Halaman B</h2>";
echo "36 C = " . konversiCelciusKeFahrenheit(36) . " F<br>";
echo "Nilai 78 = " . tentukanStatusNilai(78) . "<br>";
echo "Diskon 15%: " . formatRupiah(hitungDiskon(200000, 15)) . "<br>";
