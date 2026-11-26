CREATE DATABASE IF NOT EXISTS latihan1 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE latihan1;

CREATE TABLE IF NOT EXISTS data_barang (
    id_barang  INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    kategori   VARCHAR(50)  NOT NULL,
    harga_beli DECIMAL(12,0) NOT NULL DEFAULT 0,
    harga_jual DECIMAL(12,0) NOT NULL DEFAULT 0,
    stok       INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS users (
    id_user  INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    foto     VARCHAR(255) NULL
);

-- password semua akun contoh: admin123
INSERT INTO users (username, nama, email, password) VALUES
('admin', 'Administrator', 'admin@kampus.ac.id', '$2y$10$FEIBQO8Yicz2O8lo.HTb/uVJaERGG1rw.r9v1ucboCSB/dILAbNEu'),
('budi',  'Budi Santoso',  'budi@kampus.ac.id',  '$2y$10$FEIBQO8Yicz2O8lo.HTb/uVJaERGG1rw.r9v1ucboCSB/dILAbNEu');
