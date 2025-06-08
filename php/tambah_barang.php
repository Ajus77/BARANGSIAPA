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

$nama = $_POST['nama'];
$kondisi = $_POST['kondisi'];
$kategori_id = $_POST['kategori_id'];

$insert = $conn->prepare("INSERT INTO barang (nama_barang, kondisi, kategori_id, pemilik_id, status) VALUES (?, ?, ?, ?, 'pending')");
$insert->bind_param("ssii", $nama, $kondisi, $kategori_id, $user_id);
$insert->execute();

header("Location: main.php");
?>