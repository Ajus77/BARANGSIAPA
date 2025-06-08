<?php
require 'config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_query->bind_param("s", $username);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];

$query = "
SELECT p.*, u.username as peminjam, b.nama_barang 
FROM peminjaman p
JOIN users u ON p.peminjam_id = u.id
JOIN barang b ON p.barang_id = b.id
WHERE b.pemilik_id = ? AND p.status = 'diajukan'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Peminjaman</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <div class="section">
        <h2>Konfirmasi Permintaan Peminjaman</h2>
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_kembali'] ?></td>
                    <td>
                        <a href="proses_konfirmasi_pinjaman.php?id=<?= $row['id'] ?>&aksi=setujui">Setujui</a> |
                        <a href="proses_konfirmasi_pinjaman.php?id=<?= $row['id'] ?>&aksi=tolak">Tolak</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a class="logout-btn" href="main.php">Kembali</a>
    </div>
</div>
</body>
</html>