<?php
require '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../php/login.php");
    exit;
}

$id = $_GET['id'];
$status = $_GET['status'];

$conn->query("UPDATE barang SET status = '$status' WHERE id = $id");
header("Location: konfirmasi_barang.php");
?>