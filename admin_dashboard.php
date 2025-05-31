<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_dashboard.php?success=Data berhasil dihapus');
    exit;
}

// Get all students
$students = $conn->query("SELECT * FROM siswa ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
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

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
                    <a href="admin_dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a href="admin_add.php"><i class="bi bi-plus-circle me-2"></i>Tambah Siswa</a>
                    <a href="admin_import.php"><i class="bi bi-upload me-2"></i>Import Excel</a>
                    <a href="admin_pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                    <a href="admin_logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h3>Daftar Siswa</h3>
                <hr>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengumuman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $students->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['nis']) ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $row['status_kelulusan'] === 'Lulus' ? 'success' : 'danger' ?>">
                                                    <?= htmlspecialchars($row['status_kelulusan']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($row['tanggal_pengumuman'])) ?></td>
                                            <td>
                                                <a href="admin_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="admin_dashboard.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>