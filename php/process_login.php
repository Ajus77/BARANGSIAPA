<?php
session_start();
require 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        if (!$user['is_active']) {
            $_SESSION['error'] = "Akun Anda nonaktif. Hubungi admin.";
            header("Location: login.php");
            exit;
        }
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: main.php");
        }
        exit;
    }
}
$_SESSION['error'] = "Username atau password salah!";
header("Location: login.php");
?>