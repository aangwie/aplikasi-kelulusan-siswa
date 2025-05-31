<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Ambil data pengaturan saat ini
$pengaturan = $conn->query("SELECT * FROM pengaturan_sekolah LIMIT 1")->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data teks
    $nama_sekolah = $_POST['nama_sekolah'] ?? '';
    $alamat_sekolah = $_POST['alamat_sekolah'] ?? '';
    $kota_sekolah = $_POST['kota_sekolah'] ?? '';
    $nama_kepala_sekolah = $_POST['nama_kepala_sekolah'] ?? '';
    $nip_kepala_sekolah = $_POST['nip_kepala_sekolah'] ?? '';
    $nomor_surat = $_POST['nomor_surat'] ?? '';
    $tanggal_pengumuman = $_POST['tanggal_pengumuman'] ?? '';


    // Validasi
    if (
        empty($nama_sekolah) || empty($alamat_sekolah) || empty($kota_sekolah) ||
        empty($nama_kepala_sekolah) || empty($nip_kepala_sekolah) || empty($nomor_surat)
    ) {
        $error = 'Semua field teks harus diisi';
    } else {
        // Handle file uploads
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $logo_sekolah = $pengaturan['logo_sekolah'];
        $tanda_tangan = $pengaturan['tanda_tangan'];
        $stempel = $pengaturan['stempel'];

        // Fungsi untuk handle upload file
        function handleUpload($file, $upload_dir, $old_file, $prefix)
        {
            if (!empty($file['name'])) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $prefix . '_' . time() . '.' . $ext;
                $target = $upload_dir . $filename;

                // Hapus file lama jika ada
                if (!empty($old_file) && file_exists($old_file)) {
                    unlink($old_file);
                }

                // Pindahkan file baru
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    return $target;
                }
            }
            return $old_file;
        }

        // Upload logo sekolah
        if (!empty($_FILES['logo_sekolah']['name'])) {
            $logo_sekolah = handleUpload($_FILES['logo_sekolah'], $upload_dir, $logo_sekolah, 'logo');
        }

        // Upload tanda tangan
        if (!empty($_FILES['tanda_tangan']['name'])) {
            $tanda_tangan = handleUpload($_FILES['tanda_tangan'], $upload_dir, $tanda_tangan, 'ttd');
        }

        // Upload stempel
        if (!empty($_FILES['stempel']['name'])) {
            $stempel = handleUpload($_FILES['stempel'], $upload_dir, $stempel, 'stempel');
        }

        // Update pengaturan
        $stmt = $conn->prepare("UPDATE pengaturan_sekolah SET 
                              nama_sekolah = ?, 
                              alamat_sekolah = ?, 
                              kota_sekolah = ?, 
                              nama_kepala_sekolah = ?,
                              nip_kepala_sekolah = ?,
                              nomor_surat = ?,
                              logo_sekolah = ?,
                              tanda_tangan = ?,
                              stempel = ?,
                              tanggal_pengumuman = ? 
                              WHERE id = 1");
        $stmt->bind_param(
            "ssssssssss",
            $nama_sekolah,
            $alamat_sekolah,
            $kota_sekolah,
            $nama_kepala_sekolah,
            $nip_kepala_sekolah,
            $nomor_surat,
            $logo_sekolah,
            $tanda_tangan,
            $stempel,
            $tanggal_pengumuman
        );

        if ($stmt->execute()) {
            $success = 'Pengaturan berhasil diperbarui';
            // Refresh data pengaturan
            $pengaturan = $conn->query("SELECT * FROM pengaturan_sekolah LIMIT 1")->fetch_assoc();
        } else {
            $error = 'Gagal memperbarui pengaturan: ' . $stmt->error;
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
    <title>Pengaturan Sekolah</title>
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

        .logo-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 4px;
        }

        .upload-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        .file-input {
            display: none;
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
                    <a href="admin_pengaturan.php" class="active"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                    <a href="admin_logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h3><i class="bi bi-gear me-2"></i>Pengaturan Sekolah</h3>
                <hr>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Informasi Sekolah</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                                                value="<?= htmlspecialchars($pengaturan['nama_sekolah']) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kota_sekolah" class="form-label">Kota</label>
                                            <input type="text" class="form-control" id="kota_sekolah" name="kota_sekolah"
                                                value="<?= htmlspecialchars($pengaturan['kota_sekolah']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                                        <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah"
                                            rows="3" required><?= htmlspecialchars($pengaturan['alamat_sekolah']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nomor_surat" class="form-label">Format Nomor Surat</label>
                                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                                            value="<?= htmlspecialchars($pengaturan['nomor_surat']) ?>" required>
                                        <div class="form-text">Contoh: 123/SKL/UND/VI/2023</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Kepala Sekolah</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="nama_kepala_sekolah" class="form-label">Nama Kepala Sekolah</label>
                                            <input type="text" class="form-control" id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                                                value="<?= htmlspecialchars($pengaturan['nama_kepala_sekolah']) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nip_kepala_sekolah" class="form-label">NIP Kepala Sekolah</label>
                                            <input type="text" class="form-control" id="nip_kepala_sekolah" name="nip_kepala_sekolah"
                                                value="<?= htmlspecialchars($pengaturan['nip_kepala_sekolah']) ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Upload Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Logo Sekolah</label>
                                            <div class="upload-area" onclick="document.getElementById('logoInput').click()">
                                                <i class="bi bi-upload fs-1"></i>
                                                <p>Klik untuk upload logo</p>
                                                <input type="file" id="logoInput" name="logo_sekolah" class="file-input" accept="image/*">
                                            </div>
                                            <?php if (!empty($pengaturan['logo_sekolah'])): ?>
                                                <img src="<?= $pengaturan['logo_sekolah'] ?>" class="preview-image" id="logoPreview">
                                            <?php else: ?>
                                                <img src="" class="preview-image d-none" id="logoPreview">
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tanda Tangan</label>
                                            <div class="upload-area" onclick="document.getElementById('ttdInput').click()">
                                                <i class="bi bi-upload fs-1"></i>
                                                <p>Klik untuk upload tanda tangan</p>
                                                <input type="file" id="ttdInput" name="tanda_tangan" class="file-input" accept="image/*">
                                            </div>
                                            <?php if (!empty($pengaturan['tanda_tangan'])): ?>
                                                <img src="<?= $pengaturan['tanda_tangan'] ?>" class="preview-image" id="ttdPreview">
                                            <?php else: ?>
                                                <img src="" class="preview-image d-none" id="ttdPreview">
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Stempel Sekolah</label>
                                            <div class="upload-area" onclick="document.getElementById('stempelInput').click()">
                                                <i class="bi bi-upload fs-1"></i>
                                                <p>Klik untuk upload stempel</p>
                                                <input type="file" id="stempelInput" name="stempel" class="file-input" accept="image/*">
                                            </div>
                                            <?php if (!empty($pengaturan['stempel'])): ?>
                                                <img src="<?= $pengaturan['stempel'] ?>" class="preview-image" id="stempelPreview">
                                            <?php else: ?>
                                                <img src="" class="preview-image d-none" id="stempelPreview">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Pengaturan Pengumuman</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="tanggal_pengumuman" class="form-label">Tanggal & Waktu Pengumuman Kelulusan</label>
                                        <input type="datetime-local" class="form-control" id="tanggal_pengumuman" name="tanggal_pengumuman"
                                            value="<?= date('Y-m-d\TH:i', strtotime($pengaturan['tanggal_pengumuman'])) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan Semua Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image sebelum upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }
        
        document.getElementById('logoInput').addEventListener('change', function() {
            previewImage(this, 'logoPreview');
        });
        
        document.getElementById('ttdInput').addEventListener('change', function() {
            previewImage(this, 'ttdPreview');
        });
        
        document.getElementById('stempelInput').addEventListener('change', function() {
            previewImage(this, 'stempelPreview');
        });
    </script>
</body>

</html>