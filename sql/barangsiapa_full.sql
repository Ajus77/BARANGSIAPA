CREATE DATABASE IF NOT EXISTS barangsiapa;
USE barangsiapa;

-- Tabel user
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel kategori barang
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel barang
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    kondisi VARCHAR(255),
    status ENUM('pending', 'tersedia', 'dipinjam', 'nonaktif') DEFAULT 'pending',
    kategori_id INT,
    pemilik_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id),
    FOREIGN KEY (pemilik_id) REFERENCES users(id)
);

-- Tabel peminjaman
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barang_id INT,
    peminjam_id INT,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status ENUM('diajukan', 'dipinjam', 'dikembalikan', 'ditolak') DEFAULT 'diajukan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barang(id),
    FOREIGN KEY (peminjam_id) REFERENCES users(id)
);

-- Data admin default
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$sH5Nr7MgPgk4pk8OFwFTheAAJ7ZyB6xPDbkWzyvszXs.B4bx5msgC', 'admin');
-- Password admin = admin123