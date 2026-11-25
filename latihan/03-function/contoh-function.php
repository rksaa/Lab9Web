<?php
function tampilkanSalam()
{
    echo "Selamat belajar PHP modular!<br>";
}
function sapaPengguna($nama)
{
    echo "Halo, " . htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') . "<br>";
}
function hitungLuasPersegiPanjang($panjang, $lebar)
{
    return $panjang * $lebar;
}

tampilkanSalam();
sapaPengguna("Rina");
$luas = hitungLuasPersegiPanjang(8, 5);
echo "Luas: " . $luas . "<br>";
