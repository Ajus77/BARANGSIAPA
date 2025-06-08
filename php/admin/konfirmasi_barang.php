<?php
require '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../php/login.php");
    exit;
}
$result = $conn->query("SELECT b.*, u.username FROM barang b JOIN users u ON b.pemilik_id = u.id WHERE b.status = 'pending'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Barang</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="section">
        <h2><i class="fas fa-check-circle"></i> Konfirmasi Barang</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kondisi</th>
                    <th>Pemilik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($row['kondisi']); ?></td>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td>
                        <a class="approve-btn" href="set_barang.php?id=<?= $row['id']; ?>&status=tersedia">Terima</a>
                        <a class="reject-btn" href="set_barang.php?id=<?= $row['id']; ?>&status=nonaktif">Tolak</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <a class="logout-btn" href="admin_dashboard.php">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>