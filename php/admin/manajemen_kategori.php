<?php
require '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../php/login.php");
    exit;
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    if ($nama != "") {
        $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $deskripsi);
        $stmt->execute();
        $msg = "Kategori berhasil ditambahkan!";
    } else {
        $msg = "Nama kategori wajib diisi.";
    }
}

$result = $conn->query("SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="section">
        <h2><i class="fas fa-tags"></i> Manajemen Kategori</h2>
        <?php if ($msg): ?><p style="color:green"><?= $msg ?></p><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Nama Kategori:</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Deskripsi:</label>
                <input type="text" name="deskripsi">
            </div>
            <button type="submit"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </form>
        <table>
            <thead><tr><th>Nama</th><th>Deskripsi</th></tr></thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <br>
        <a class="logout-btn" href="admin_dashboard.php">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>