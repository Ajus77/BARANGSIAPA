<?php
require_once 'includes/db.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->rowCount() > 0) {
        $error = 'Username sudah digunakan!';
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, status) VALUES (?, ?, 'user', 'aktif')");
        if ($stmt->execute([$username, $password])) {
            $success = 'Registrasi berhasil. Silakan login.';
        } else {
            $error = 'Registrasi gagal.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Barang Siapa</title>
</head>
<body>
<h2>Daftar Akun</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Daftar</button>
</form>
<p style="color:red;"><?= $error ?></p>
<p style="color:green;"><?= $success ?></p>
<p><a href="login.php">Sudah punya akun? Login di sini</a></p>
</body>
</html>