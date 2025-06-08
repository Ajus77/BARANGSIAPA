<?php
require '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../php/login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("UPDATE users SET is_active = NOT is_active WHERE id = $id");
    $_SESSION['user_message'] = "Status user berhasil diperbarui.";
}
header("Location: kelola_user.php");
?>