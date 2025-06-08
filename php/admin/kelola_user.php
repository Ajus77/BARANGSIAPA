<?php
require '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../php/login.php");
    exit;
}
$result = $conn->query("SELECT * FROM users WHERE role = 'user'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="section">
        <h2><i class="fas fa-users-cog"></i> Kelola Akun User</h2>
        <?php if (isset($_SESSION['user_message'])): ?>
            <p style="color:green"><?= $_SESSION['user_message']; unset($_SESSION['user_message']); ?></p>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= $row['is_active'] ? 'Aktif' : 'Nonaktif'; ?></td>
                    <td>
                        <a class="approve-btn" href="toggle_user.php?id=<?= $row['id']; ?>">
                            <?= $row['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>
                        </a>
                    </td>
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