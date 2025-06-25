<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$user_id = $stmt->get_result()->fetch_assoc()['id'];
$barang_id = $_POST['barang_id'];
$tgl_pinjam = $_POST['tanggal_pinjam'];
$tgl_kembali = $_POST['tanggal_kembali'];

$insert = $conn->prepare("INSERT INTO peminjaman (barang_id, peminjam_id, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, 'diajukan')");
$insert->bind_param("iiss", $barang_id, $user_id, $tgl_pinjam, $tgl_kembali);
$insert->execute();

$conn->query("UPDATE barang SET status = 'pending' WHERE id = $barang_id");

header("Location: main.php");

?>