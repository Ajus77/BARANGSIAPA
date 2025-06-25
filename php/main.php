<?php
require 'config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['username'];
$user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_query->bind_param("s", $user);
$user_query->execute();
$user_result = $user_query->get_result();
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];
$_SESSION['user_id'] = $user_id;

$barang_saya = $conn->query("SELECT 
    b.*, 
    k.nama_kategori,
    k.id as kategori_id,
    CASE 
        WHEN EXISTS (SELECT 1 FROM peminjaman p WHERE p.barang_id = b.id AND p.status = 'dipinjam') THEN 'dipinjam'
        ELSE b.status
    END AS status_aktual
    FROM barang b 
    LEFT JOIN kategori k ON b.kategori_id = k.id 
    WHERE pemilik_id = $user_id");

$barang_tersedia = $conn->query("SELECT b.*, u.username, k.nama_kategori FROM barang b JOIN users u ON b.pemilik_id = u.id LEFT JOIN kategori k ON b.kategori_id = k.id WHERE b.status = 'tersedia' AND b.pemilik_id != $user_id");

$kategori_result = $conn->query("SELECT id, nama_kategori FROM kategori");
$kategori_options = [];
while($kat = $kategori_result->fetch_assoc()) {
    $kategori_options[$kat['id']] = $kat['nama_kategori'];
}

$peminjaman = $conn->query("SELECT 
    p.*, 
    b.nama_barang, 
    b.kondisi, 
    k.nama_kategori, 
    u.username AS pemilik,
    p.status AS status_peminjaman
    FROM peminjaman p
    JOIN barang b ON p.barang_id = b.id
    JOIN kategori k ON b.kategori_id = k.id
    JOIN users u ON b.pemilik_id = u.id
    WHERE p.peminjam_id = $user_id
    ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BARANGSIAPA - User</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         .hidden {
        display: none;
    }
    
    .action-buttons { 
        display: flex; 
        gap: 5px; 
    }
    
    .btn-edit, .btn-delete {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }
    
    .btn-edit { 
        background-color: #2196F3; 
        color: white; 
    }
    
    .btn-delete { 
        background-color: #f44336; 
        color: white; 
    }
    

    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 25px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        position: relative;
    }
    
    .close-modal {
        color: #aaa;
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close-modal:hover {
        color: black;
    }

    #editForm .form-group {
        margin-bottom: 15px;
    }
    
    #editForm label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    #editForm input,
    #editForm select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .btn-save {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }
    
    .btn-save:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container active">
            <div class="main-header">
                <h1 class="main-title">BARANGSIAPA</h1>
                <p class="welcome-text">Selamat datang, <span><?= htmlspecialchars($user); ?></span></p>
                <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>

            <div class="nav-menu">
                <div class="nav-items">
                    <a href="#tambah-barang" class="nav-link" data-target="tambah-barang"><i class="fas fa-plus-circle"></i> Tambah Barang</a>
                    <a href="#pinjam-barang" class="nav-link" data-target="pinjam-barang"><i class="fas fa-hand-holding"></i> Pinjam Barang</a>
                    <a href="#daftar-barang" class="nav-link" data-target="daftar-barang"><i class="fas fa-list"></i> Daftar Barang Saya</a>
                    <a href="#data-peminjaman" class="nav-link" data-target="data-peminjaman"><i class="fas fa-clipboard-list"></i> Data Peminjaman</a>
                    <a href="#req-pinjam" class="nav-link" data-target="req-pinjam"><i class="fas fa-clipboard-list"></i> Request Pinjam</a>
                </div>
            </div>

            <div class="section" id="tambah-barang">
                <h2><i class="fas fa-plus-circle"></i> Tambah Barang Baru</h2>
                <form method="POST" action="tambah_barang.php">
                    <div class="form-group">
                        <label>Nama Barang:</label>
                        <input type="text" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Kondisi Barang:</label>
                        <input type="text" name="kondisi" required>
                    </div>
                    <?php
                    $kategori_result = $conn->query("SELECT id, nama_kategori FROM kategori");
                    ?>
                    <div class="form-group">
                        <label>Kategori:</label>
                        <select name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while($kat = $kategori_result->fetch_assoc()): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit">Tambah Barang</button>
                </form>
            </div>

            <div class="section hidden" id="pinjam-barang">
                <h2><i class="fas fa-hand-holding"></i> Ajukan Peminjaman</h2>
                <form method="POST" action="ajukan_peminjaman.php">
                    <div class="form-group">
                        <label>Pilih Barang:</label>
                        <select name="barang_id" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php while($row = $barang_tersedia->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nama_barang']); ?> - <?= htmlspecialchars($row['nama_kategori'] ?? '-') ?> (<?= htmlspecialchars($row['username']); ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pinjam:</label>
                        <input type="date" name="tanggal_pinjam" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Kembali:</label>
                        <input type="date" name="tanggal_kembali" required>
                    </div>
                    <button type="submit">Ajukan</button>
                </form>
            </div>

          <div class="section hidden" id="daftar-barang">
        <h2><i class="fas fa-list"></i> Daftar Barang Saya</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kondisi</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($b = $barang_saya->fetch_assoc()): ?>
                <tr data-id="<?= $b['id'] ?>">
                    <td><?= htmlspecialchars($b['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($b['kondisi']); ?></td>
                    <td><?= $b['nama_kategori'] ?></td>
                    <td><?= htmlspecialchars($b['status_aktual']); ?></td>
                    <td class="action-buttons">
                        <button class="btn-edit" data-id="<?= $b['id'] ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" data-id="<?= $b['id'] ?>">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="editModal" class="modal hidden">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h3>Edit Barang</h3>
                <form id="editForm">
                    <input type="hidden" id="editId">
                    <div class="form-group">
                        <label>Nama Barang:</label>
                        <input type="text" id="editNama" required>
                    </div>
                    <div class="form-group">
                        <label>Kondisi:</label>
                        <input type="text" id="editKondisi" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori:</label>
                        <select id="editKategori" required>
                            <?php foreach($kategori_options as $id => $nama): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($nama) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

            <div class="section hidden" id="data-peminjaman">
                <h2><i class="fas fa-clipboard-list"></i> Data Peminjaman</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Pemilik</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Kategori</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $peminjaman->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($p['pemilik']); ?></td>
                            <td><?= $p['tanggal_pinjam']; ?></td>
                            <td><?= $p['tanggal_kembali']; ?></td>
                            <td><?= htmlspecialchars($p['nama_kategori']); ?></td>
                            <td><?= htmlspecialchars($p['status_peminjaman']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

    <div class="section hidden" id="req-pinjam">
    <h2><i class="fas fa-clipboard-list"></i> Request Pinjaman</h2>
    <p>Request yang diajukan ke barang saya:</p>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $req_ke_saya = $conn->query("SELECT 
                p.*, 
                b.nama_barang, 
                u.username AS peminjam,
                p.status AS status_peminjaman
                FROM peminjaman p
                JOIN barang b ON p.barang_id = b.id
                JOIN users u ON p.peminjam_id = u.id
                WHERE b.pemilik_id = $user_id
                ORDER BY p.created_at DESC");
            
            while($req = $req_ke_saya->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($req['nama_barang']); ?></td>
                <td><?= htmlspecialchars($req['peminjam']); ?></td>
                <td><?= $req['tanggal_pinjam']; ?></td>
                <td><?= $req['tanggal_kembali']; ?></td>
                <td><?= htmlspecialchars($req['status_peminjaman']); ?></td>
                <td class="action-buttons">
                    <?php if ($req['status_peminjaman'] == 'diajukan'): ?>
    <form method="POST" action="proses_request.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $req['id']; ?>">
        <input type="hidden" name="action" value="dipinjam">
        <button type="submit" class="btn-approve">Setujui</button>
    </form>
    <form method="POST" action="proses_request.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $req['id']; ?>">
        <input type="hidden" name="action" value="ditolak">
        <button type="submit" class="btn-reject">Tolak</button>
    </form>
<?php elseif ($req['status_peminjaman'] == 'dipinjam'): ?>
    <form method="POST" action="proses_request.php" style="display:inline;" onclick="alert('Pastikan barang sudah dikembalikan sebelum mengubah status!');">
        <input type="hidden" name="id" value="<?= $req['id']; ?>">
        <input type="hidden" name="action" value="tersedia">
        <button type="submit" class="btn-reject">kembalikan</button>
    </form>
<?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="../js/script.js"></script>
<script src="../js/user.js"></script>
</body>
</html>