<?php
require_once __DIR__ . '/helpers.php';
echo "<h2>Halaman A</h2>";
echo "Harga: " . formatRupiah(150000) . "<br>";
echo "Total 3 barang: " . formatRupiah(hitungTotal(150000, 3)) . "<br>";
echo "Diskon 10%: " . formatRupiah(hitungDiskon(450000)) . "<br>";
echo "Escape: " . e('<script>alert("uji")</script>') . "<br>";