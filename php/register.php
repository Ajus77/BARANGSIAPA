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
    <title>Register - BARANGSIAPA</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="login-container active" id="loginContainer">
        <div class="login-wrapper">
            <div class="login-card">
                <h1 class="login-title">Registrasi</h1>
                <p class="login-subtitle">Buat akun baru untuk UKM Anda</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
                <form action="process_register.php" method="POST">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-users"></i> Nama UKM:</label>
                        <input type="text" name="username" placeholder="Masukkan nama UKM" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password:</label>
                        <input type="password" name="password" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit"><i class="fas fa-user-plus"></i> Daftar</button>
                </form>
                <p style="text-align:center; margin-top:20px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>