<?php
require 'config.php';
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$nama_barang = $data['nama_barang'] ?? null;
$kondisi = $data['kondisi'] ?? null;
$kategori_id = $data['kategori_id'] ?? null;

if (empty($id) || empty($nama_barang) || empty($kondisi) || empty($kategori_id)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$user_id = $_SESSION['user_id']; 
$check_query = $conn->prepare("SELECT id FROM barang WHERE id = ? AND pemilik_id = ?");
$check_query->bind_param("ii", $id, $user_id);
$check_query->execute();
$check_result = $check_query->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan atau bukan milik Anda']);
    exit;
}

$update_query = $conn->prepare("UPDATE barang SET nama_barang = ?, kondisi = ?, kategori_id = ? WHERE id = ?");
$update_query->bind_param("ssii", $nama_barang, $kondisi, $kategori_id, $id);

if ($update_query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan perubahan']);
}

$update_query->close();
$conn->close();
?>