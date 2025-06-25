<?php
session_start();
require 'config.php';
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/admin_dashboard.php");
    } else {
        header("Location: main.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - BARANGSIAPA</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-container active" id="loginContainer">
        <div class="login-wrapper">
            <div class="login-card">
                <h1 class="login-title">BARANGSIAPA</h1>
                <p class="login-subtitle">Sistem Peminjaman Barang UKM</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php elseif (isset($_SESSION['success'])): ?>
                    <p style="color:green"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
                <?php else: ?>
                <?php endif; ?>
                <form action="process_login.php" method="POST">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-users"></i> Nama UKM:</label>
                        <input type="text" name="username" id="username" placeholder="Masukkan nama UKM" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password:</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit"><i class="fas fa-sign-in-alt"></i> Masuk ke Sistem</button>
                </form>
                <p style="text-align:center; margin-top:20px;">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>