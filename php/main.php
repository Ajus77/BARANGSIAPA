<?php
require 'config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['username'];
$user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_query->bind_param("s", $user);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];

// Ambil barang milik user
$barang_saya = $conn->query("SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.kategori_id = k.id WHERE pemilik_id = $user_id");

// Ambil semua barang tersedia untuk dipinjam
$barang_tersedia = $conn->query("SELECT b.*, u.username, k.nama_kategori FROM barang b JOIN users u ON b.pemilik_id = u.id LEFT JOIN kategori k ON b.kategori_id = k.id WHERE b.status = 'tersedia' AND b.pemilik_id != $user_id");

// Ambil data peminjaman yang melibatkan user
$peminjaman = $conn->query("SELECT p.*, b.nama_barang, b.kondisi, u.username AS pemilik
    FROM peminjaman p
    JOIN barang b ON p.barang_id = b.id
    JOIN users u ON b.pemilik_id = u.id
    WHERE p.peminjam_id = $user_id
    ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BARANGSIAPA - User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="main-container active">
            <div class="main-header">
                <h1 class="main-title">BARANGSIAPA</h1>
                <p class="welcome-text">Selamat datang, <span><?= htmlspecialchars($user); ?></span></p>
                <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>

            <div class="nav-menu">
                <div class="nav-items">
                    <a href="#tambah-barang"><i class="fas fa-plus-circle"></i> Tambah Barang</a>
                    <a href="#pinjam-barang"><i class="fas fa-hand-holding"></i> Pinjam Barang</a>
                    <a href="#daftar-barang"><i class="fas fa-list"></i> Daftar Barang Saya</a>
                    <a href="#data-peminjaman"><i class="fas fa-clipboard-list"></i> Data Peminjaman</a>
                </div>
            </div>

            <div class="section" id="tambah-barang">
                <h2><i class="fas fa-plus-circle"></i> Tambah Barang Baru</h2>
                <form method="POST" action="tambah_barang.php">
                    <div class="form-group">
                        <label>Nama Barang:</label>
                        <input type="text" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Kondisi Barang:</label>
                        <input type="text" name="kondisi" required>
                    </div>
                    <?php
$kategori_result = $conn->query("SELECT id, nama_kategori FROM kategori");
?>
<div class="form-group">
    <label>Kategori:</label>
    <select name="kategori_id" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($kat = $kategori_result->fetch_assoc()): ?>
        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
        <?php endwhile; ?>
    </select>
</div>
<button type="submit">Tambah Barang</button>
                </form>
            </div>

            <div class="section" id="pinjam-barang">
                <h2><i class="fas fa-hand-holding"></i> Ajukan Peminjaman</h2>
                <form method="POST" action="ajukan_peminjaman.php">
                    <div class="form-group">
                        <label>Pilih Barang:</label>
                        <select name="barang_id" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php while($row = $barang_tersedia->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nama_barang']); ?> - <?= htmlspecialchars($row['nama_kategori'] ?? '-') ?> (<?= htmlspecialchars($row['username']); ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pinjam:</label>
                        <input type="date" name="tanggal_pinjam" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Kembali:</label>
                        <input type="date" name="tanggal_kembali" required>
                    </div>
                    <button type="submit">Ajukan</button>
                </form>
            </div>

            <div class="section" id="daftar-barang">
                <h2><i class="fas fa-list"></i> Daftar Barang Saya</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kondisi</th>
                            <th>Kategori</th><th>Kategori</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($b = $barang_saya->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($b['kondisi']); ?></td>
                            <td><?= htmlspecialchars($b['nama_kategori'] ?? '-') ?></td><td><?= htmlspecialchars($b['nama_kategori'] ?? "-"); ?></td><td><?= htmlspecialchars($b['status']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="section" id="data-peminjaman">
                <h2><i class="fas fa-clipboard-list"></i> Data Peminjaman</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Pemilik</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Kategori</th><th>Kategori</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $peminjaman->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($p['pemilik']); ?></td>
                            <td><?= $p['tanggal_pinjam']; ?></td>
                            <td><?= $p['tanggal_kembali']; ?></td>
                            <td><?= $p['status']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>