<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$id = $_GET['id'];
$error = '';
$success = '';

// Get student data
$stmt = $conn->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: admin_dashboard.php');
    exit;
}

$student = $result->fetch_assoc();
$stmt->close();

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
        // Cek apakah NIS sudah ada (kecuali untuk siswa ini)
        $stmt = $conn->prepare("SELECT id FROM siswa WHERE nis = ? AND id != ?");
        $stmt->bind_param("si", $nis, $id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = 'NIS sudah terdaftar untuk siswa lain';
        } else {
            // Update data
            $stmt = $conn->prepare("UPDATE siswa SET nis = ?, nama = ?, kelas = ?, status_kelulusan = ?, tanggal_pengumuman = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $nis, $nama, $kelas, $status, $tanggal, $id);
            
            if ($stmt->execute()) {
                $success = 'Data siswa berhasil diperbarui';
                // Refresh student data
                $student['nis'] = $nis;
                $student['nama'] = $nama;
                $student['kelas'] = $kelas;
                $student['status_kelulusan'] = $status;
                $student['tanggal_pengumuman'] = $tanggal;
            } else {
                $error = 'Gagal memperbarui data: ' . $stmt->error;
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
    <title>Edit Siswa</title>
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
                    <a href="admin_add.php"><i class="bi bi-plus-circle me-2"></i>Tambah Siswa</a>
                    <a href="admin_import.php"><i class="bi bi-upload me-2"></i>Import Excel</a>
                    <a href="admin_logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h3>Edit Data Siswa</h3>
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
                                           value="<?= htmlspecialchars($student['nis']) ?>" 
                                           maxlength="10" required>
                                    <div class="form-text">Contoh: 1234567890</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" 
                                           value="<?= htmlspecialchars($student['nama']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <input type="text" class="form-control" id="kelas" name="kelas" 
                                           value="<?= htmlspecialchars($student['kelas']) ?>" required>
                                    <div class="form-text">Contoh: XII IPA 1</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status Kelulusan</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Lulus" <?= $student['status_kelulusan'] === 'Lulus' ? 'selected' : '' ?>>Lulus</option>
                                        <option value="Tidak Lulus" <?= $student['status_kelulusan'] === 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal Pengumuman</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= htmlspecialchars($student['tanggal_pengumuman']) ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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