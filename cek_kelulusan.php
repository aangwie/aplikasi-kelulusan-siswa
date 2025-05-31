<?php
include 'config.php';

header('Content-Type: application/json');

// Validasi tanggal pengumuman
$today = new DateTime();
$pengumuman = new DateTime($tanggal_pengumuman);

if ($today < $pengumuman) {
    echo json_encode(['error' => 'Pengumuman kelulusan akan dibuka pada tanggal ' . date('d F Y', strtotime($tanggal_pengumuman))]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = isset($_POST['nis']) ? trim($_POST['nis']) : '';
    
    // Validasi server-side tambahan
    if (strlen($nis) !== 10 || !ctype_digit($nis)) {
        echo json_encode(['error' => 'NIS harus terdiri dari tepat 10 digit angka.']);
        exit;
    }
    
    // Cari data siswa
    $stmt = $conn->prepare("SELECT nis, nama, kelas, status_kelulusan, tanggal_pengumuman FROM siswa WHERE nis = ?");
    $stmt->bind_param("s", $nis);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Format tanggal
        $tanggal = date('d F Y', strtotime($row['tanggal_pengumuman']));
        
        echo json_encode([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'kelas' => $row['kelas'],
            'status_kelulusan' => $row['status_kelulusan'],
            'tanggal_pengumuman' => $tanggal
        ]);
    } else {
        echo json_encode(['error' => 'Data siswa tidak ditemukan.']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Metode request tidak valid.']);
}
?>