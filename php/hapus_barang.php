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

$user_id = $_SESSION['user_id'];
$check_query = $conn->prepare("SELECT id FROM barang WHERE id = ? AND pemilik_id = ?");
$check_query->bind_param("ii", $id, $user_id);
$check_query->execute();
$check_result = $check_query->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Barang tidak ditemukan atau bukan milik Anda']);
    exit;
}

$pinjam_check = $conn->query("SELECT id FROM peminjaman WHERE barang_id = $id AND status = 'dipinjam'");
if ($pinjam_check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Barang tidak dapat dihapus karena sedang dipinjam']);
    exit;
}

$delete_query = $conn->prepare("DELETE FROM barang WHERE id = ?");
$delete_query->bind_param("i", $id);

if ($delete_query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus barang']);
}

$delete_query->close();
$conn->close();
?>