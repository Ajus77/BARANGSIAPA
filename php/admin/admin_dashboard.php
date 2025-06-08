<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="section">
        <h2><i class="fas fa-tools"></i> Dashboard Admin</h2>
        <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
        <div class="nav-items">
            <a href="kelola_user.php" class="approve-btn"><i class="fas fa-users-cog"></i> Kelola User</a>
            <a href="konfirmasi_barang.php" class="approve-btn"><i class="fas fa-check-circle"></i> Konfirmasi Barang</a>
            <a href="manajemen_kategori.php" class="approve-btn"><i class="fas fa-tags"></i> Manajemen Kategori</a>
            <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</div>
</body>
</html>