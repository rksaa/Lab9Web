-- Database untuk Lab9Web (sesuaikan kolom dengan tabel praktikum 8 jika berbeda)
CREATE DATABASE IF NOT EXISTS latihan1 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE latihan1;

CREATE TABLE IF NOT EXISTS data_barang (
    id_barang  INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    kategori   VARCHAR(50)  NOT NULL,
    harga_beli DECIMAL(12,0) NOT NULL DEFAULT 0,
    harga_jual DECIMAL(12,0) NOT NULL DEFAULT 0,
    stok       INT NOT NULL DEFAULT 0,
    gambar     VARCHAR(255) NULL
);

INSERT INTO data_barang (nama, kategori, harga_beli, harga_jual, stok) VALUES
('Mouse Wireless', 'Aksesoris', 85000, 120000, 25),
('Keyboard Mekanik', 'Aksesoris', 350000, 475000, 12),
('Flashdisk 64GB', 'Penyimpanan', 60000, 85000, 40),
('Monitor 24 inch', 'Monitor', 1300000, 1650000, 6);
