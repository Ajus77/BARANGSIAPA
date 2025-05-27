
-- Database: barangsiapa

CREATE DATABASE IF NOT EXISTS barangsiapa;
USE barangsiapa;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    status ENUM('aktif','nonaktif') DEFAULT 'aktif'
);

-- Table: kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

-- Table: barang
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    kategori_id INT,
    deskripsi TEXT,
    foto VARCHAR(255),
    pemilik_id INT,
    status ENUM('tersedia','dipinjam') DEFAULT 'tersedia',
    FOREIGN KEY (kategori_id) REFERENCES kategori(id),
    FOREIGN KEY (pemilik_id) REFERENCES users(id)
);

-- Table: transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barang_id INT NOT NULL,
    peminjam_id INT NOT NULL,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status_pengembalian ENUM('belum','sudah') DEFAULT 'belum',
    FOREIGN KEY (barang_id) REFERENCES barang(id),
    FOREIGN KEY (peminjam_id) REFERENCES users(id)
);

-- Admin default
INSERT INTO users (username, password, role, status)
VALUES ('admin', '$2y$10$lA8vxh6nBKBOhTXTXyg7JuBRmSYfuS2qPuOnsNKRxPB6q2I85N9mC', 'admin', 'aktif');
-- Password default admin: admin123
