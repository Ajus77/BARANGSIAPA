<?php
require 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'dipinjam') {
        $conn->query("UPDATE peminjaman SET status = 'dipinjam' WHERE id = $id");
        $conn->query("UPDATE barang SET status = 'dipinjam' WHERE id = (SELECT barang_id FROM peminjaman WHERE id = $id)");
    } else if ($action == 'ditolak') {
        $conn->query("UPDATE peminjaman SET status = 'ditolak' WHERE id = $id");
        $conn->query("UPDATE barang SET status = 'tersedia' WHERE id = (SELECT barang_id FROM peminjaman WHERE id = $id)");
    } else if ($action == 'tersedia') {
    $get_barang = $conn->query("SELECT barang_id FROM peminjaman WHERE id = $id");
    $row = $get_barang->fetch_assoc();
    $barang_id = $row['barang_id'];

    $conn->query("DELETE FROM peminjaman WHERE id = $id");
    $conn->query("UPDATE barang SET status = 'tersedia' WHERE id = $barang_id");
}

    
    header("Location: main.php");
    exit;
}
?>