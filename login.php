<?php
session_start();
require_once 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 'aktif'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: dashboard/admin.php");
        } else {
            header("Location: dashboard/user.php");
        }
        exit();
    } else {
        $error = 'Login gagal. Periksa kembali username dan password Anda.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Barang Siapa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-box">
<h2>Login</h2>
<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
<p style="color:red;"> <?= $error ?> </p>
<p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>