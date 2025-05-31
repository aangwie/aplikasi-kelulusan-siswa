<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = $_POST['nis'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $status = $_POST['status'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';

    // Validasi
    if (strlen($nis) !== 10 || !ctype_digit($nis)) {
        $error = 'NIS harus 10 digit angka';
    } elseif (empty($nama) || empty($kelas) || empty($status) || empty($tanggal)) {
        $error = 'Semua field harus diisi';
    } else {
        // Cek apakah NIS sudah ada
        $stmt = $conn->prepare("SELECT id FROM siswa WHERE nis = ?");
        $stmt->bind_param("s", $nis);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = 'NIS sudah terdaftar';
        } else {
            // Insert data
            $stmt = $conn->prepare("INSERT INTO siswa (nis, nama, kelas, status_kelulusan, tanggal_pengumuman) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nis, $nama, $kelas, $status, $tanggal);
            
            if ($stmt->execute()) {
                $success = 'Data siswa berhasil ditambahkan';
                // Reset form
                $_POST = array();
            } else {
                $error = 'Gagal menambahkan data: ' . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            min-height: 100vh;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            color: white;
            background-color: #495057;
        }
        .sidebar a.active {
            color: white;
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4>Admin Panel</h4>
                    <hr>
                    <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a href="admin_add.php" class="active"><i class="bi bi-plus-circle me-2"></i>Tambah Siswa</a>
                    <a href="admin_import.php"><i class="bi bi-upload me-2"></i>Import Excel</a>
                    <a href="admin_logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h3>Tambah Data Siswa</h3>
                <hr>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NIS (10 Digit)</label>
                                    <input type="text" class="form-control" id="nis" name="nis" 
                                           value="<?= htmlspecialchars($_POST['nis'] ?? '') ?>" 
                                           maxlength="10" required>
                                    <div class="form-text">Contoh: 1234567890</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" 
                                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <input type="text" class="form-control" id="kelas" name="kelas" 
                                           value="<?= htmlspecialchars($_POST['kelas'] ?? '') ?>" required>
                                    <div class="form-text">Contoh: XII IPA 1</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status Kelulusan</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Lulus" <?= isset($_POST['status']) && $_POST['status'] === 'Lulus' ? 'selected' : '' ?>>Lulus</option>
                                        <option value="Tidak Lulus" <?= isset($_POST['status']) && $_POST['status'] === 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal Pengumuman</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>