<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

require 'vendor/autoload.php'; // Pastikan Anda telah menginstall phpoffice/phpspreadsheet via composer

use PhpOffice\PhpSpreadsheet\IOFactory;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Validasi file
    if (empty($file)) {
        $error = 'Silakan pilih file terlebih dahulu';
    } elseif (!in_array($file_ext, ['xlsx', 'xls', 'csv'])) {
        $error = 'Format file tidak didukung. Harap upload file Excel (xlsx, xls) atau CSV';
    } else {
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            $conn->begin_transaction();
            $stmt = $conn->prepare("INSERT INTO siswa (nis, nama, kelas, status_kelulusan, tanggal_pengumuman) VALUES (?, ?, ?, ?, ?)");
            $imported = 0;
            $skipped = 0;

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty($row[0])) continue;

                $nis = $row[0] ?? '';
                $nama = $row[1] ?? '';
                $kelas = $row[2] ?? '';
                $status = $row[3] ?? '';
                $tanggal = $row[4] ?? '';

                // Validasi data
                if (strlen($nis) !== 10 || !ctype_digit($nis) || empty($nama) || empty($kelas) || empty($status) || empty($tanggal)) {
                    $skipped++;
                    continue;
                }

                // Cek duplikat NIS
                $check = $conn->prepare("SELECT id FROM siswa WHERE nis = ?");
                $check->bind_param("s", $nis);
                $check->execute();
                $check->store_result();

                if ($check->num_rows === 0) {
                    $stmt->bind_param("sssss", $nis, $nama, $kelas, $status, $tanggal);
                    if ($stmt->execute()) {
                        $imported++;
                    } else {
                        $skipped++;
                    }
                } else {
                    $skipped++;
                }
                $check->close();
            }

            $conn->commit();
            $success = "Import selesai. Data berhasil diimport: $imported, Data dilewati: $skipped";
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Error saat memproses file: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Siswa</title>
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
        .template-link {
            cursor: pointer;
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
                    <a href="admin_import.php" class="active"><i class="bi bi-upload me-2"></i>Import Excel</a>
                    <a href="admin_logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h3>Import Data dari Excel</h3>
                <hr>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Excel</label>
                                <input class="form-control" type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                <div class="form-text">
                                    Format file harus Excel (xlsx, xls) atau CSV. 
                                    <a href="#" class="template-link" onclick="downloadTemplate()">Download template</a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Import Data</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Petunjuk Import</h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Pastikan file Excel/CSV memiliki format kolom berikut (urutan harus sama):
                                <ul>
                                    <li>Kolom 1: NIS (10 digit angka)</li>
                                    <li>Kolom 2: Nama Lengkap</li>
                                    <li>Kolom 3: Kelas</li>
                                    <li>Kolom 4: Status Kelulusan (Lulus/Tidak Lulus)</li>
                                    <li>Kolom 5: Tanggal Pengumuman (format YYYY-MM-DD)</li>
                                </ul>
                            </li>
                            <li>Baris pertama akan dianggap sebagai header dan akan dilewati</li>
                            <li>Data dengan NIS yang sudah ada akan dilewati</li>
                            <li>Data yang tidak valid (format salah atau kolom kosong) akan dilewati</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function downloadTemplate() {
            // Create a template Excel file dynamically
            const csvContent = "NIS,Nama,Kelas,Status Kelulusan,Tanggal Pengumuman\n" +
                              "1234567890,Andi Wijaya,XII IPA 1,Lulus,2023-05-15\n" +
                              "0987654321,Budi Santoso,XII IPA 2,Tidak Lulus,2023-05-15\n" +
                              "1122334455,Citra Dewi,XII IPS 1,Lulus,2023-05-15";
            
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.setAttribute('download', 'template_import_siswa.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>