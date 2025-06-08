<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

if ($aksi === 'setujui') {
    $conn->query("UPDATE peminjaman SET status = 'dipinjam' WHERE id = $id");
    $conn->query("UPDATE barang SET status = 'dipinjam' WHERE id = (SELECT barang_id FROM peminjaman WHERE id = $id)");
} elseif ($aksi === 'tolak') {
    $conn->query("UPDATE peminjaman SET status = 'ditolak' WHERE id = $id");
}
header("Location: konfirmasi_pinjaman.php");
?>