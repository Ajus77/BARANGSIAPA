<?php
require_once '../includes/session.php';
require_once '../includes/db.php';
checkRole('user');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT transaksi.*, barang.nama_barang, barang.foto FROM transaksi 
    JOIN barang ON transaksi.barang_id = barang.id
    WHERE transaksi.peminjam_id = ?
    ORDER BY transaksi.tanggal_pinjam DESC");
$stmt->execute([$user_id]);
$transaksi = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User - Barang Siapa</title>
</head>
<body>
<h2>Selamat datang, <?= $_SESSION['username'] ?> (User)</h2>
<a href="../logout.php">Logout</a>
<h3>Riwayat Peminjaman</h3>
<table border="1">
<tr><th>Barang</th><th>Foto</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr>
<?php foreach ($transaksi as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
    <td><img src="../uploads/<?= $row['foto'] ?>" width="80"></td>
    <td><?= $row['tanggal_pinjam'] ?></td>
    <td><?= $row['tanggal_kembali'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>