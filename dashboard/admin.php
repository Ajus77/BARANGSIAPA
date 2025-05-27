<?php
require_once '../includes/session.php';
require_once '../includes/db.php';
checkRole('admin');

$stmt = $pdo->query("SELECT transaksi.*, barang.nama_barang, barang.foto, u.username AS peminjam FROM transaksi 
    JOIN barang ON transaksi.barang_id = barang.id
    JOIN users u ON transaksi.peminjam_id = u.id
    ORDER BY transaksi.tanggal_pinjam DESC");
$transaksi = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Barang Siapa</title>
</head>
<body>
<h2>Selamat datang, <?= $_SESSION['username'] ?> (Admin)</h2>
<a href="../logout.php">Logout</a>
<h3>Monitoring Peminjaman</h3>
<table border="1">
<tr><th>Nama Barang</th><th>Foto</th><th>Peminjam</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr>
<?php foreach ($transaksi as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
    <td><img src="../uploads/<?= $row['foto'] ?>" width="80"></td>
    <td><?= $row['peminjam'] ?></td>
    <td><?= $row['tanggal_pinjam'] ?></td>
    <td><?= $row['tanggal_kembali'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>