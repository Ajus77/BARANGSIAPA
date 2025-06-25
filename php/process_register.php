<?php
session_start();
require 'config.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'user';

$cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
$cek->bind_param("s", $username);
$cek->execute();
$cek->store_result();
if ($cek->num_rows > 0) {
    $_SESSION['error'] = "Username sudah terdaftar!";
    header("Location: register.php");
    exit;
}

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);
if ($stmt->execute()) {
    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
    header("Location: login.php");
} else {
    $_SESSION['error'] = "Registrasi gagal.";
    header("Location: register.php");
}
?>