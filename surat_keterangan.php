<?php
session_start();
require_once('vendor/autoload.php');
require_once('config.php');

// Periksa apakah ada parameter NIS
if (!isset($_GET['nis']) || strlen($_GET['nis']) !== 10) {
    header('Location: index.php');
    exit;
}

$nis = $_GET['nis'];

// Ambil data siswa dari database
$stmt = $conn->prepare("SELECT * FROM siswa WHERE nis = ?");
$stmt->bind_param("s", $nis);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$siswa = $result->fetch_assoc();
$stmt->close();

// Ambil data pengaturan sekolah
$pengaturan = $conn->query("SELECT * FROM pengaturan_sekolah LIMIT 1")->fetch_assoc(); 

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Aplikasi Kelulusan');
$pdf->SetAuthor($pengaturan['nama_sekolah']);
$pdf->SetTitle('Surat Keterangan Kelulusan');
$pdf->SetSubject('Surat Keterangan Kelulusan');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(20, 15, 15);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('times', 'B', 12);

// Logo Sekolah (optional)
//$pdf->Image('assets/logo.png', 15, 22, 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

if (!empty($pengaturan['logo_sekolah']) && file_exists($pengaturan['logo_sekolah'])) {
    $pdf->Image($pengaturan['logo_sekolah'], 15, 22, 15, 15, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
    //$pdf->SetY(40); // Adjust position after logo
} else {
    $pdf->SetY(15);
}

// Header Surat
$pdf->Cell(0, 0, 'PEMERINTAH KABUPATEN PACITAN', 0, 1, 'C');
$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 0, strtoupper($pengaturan['nama_sekolah']), 0, 1, 'C');
//$pdf->Cell(0, 0, 'SMP NEGERI 6 SUDIMORO', 0, 1, 'C');
$pdf->SetFont('times', '', 10);
$pdf->Cell(0, 0, $pengaturan['alamat_sekolah'], 0, 1, 'C');
//$pdf->Cell(0, 0, 'Jl. Raya Pacitan-Trenggalek, km.55, Desa Sukorejo, Kecamatan Sudimoro, Pacitan', 0, 1, 'C');

// Garis pembatas
$pdf->SetLineWidth(0.8);
$pdf->Line(15, 40, 195, 40);
$pdf->SetLineWidth(0.3);
$pdf->Line(15, 41, 195, 41);
$pdf->Ln(10);

// Judul Surat
$pdf->SetFont('times', 'BU', 14);
$pdf->Cell(0, 0, 'SURAT KETERANGAN KELULUSAN', 0, 1, 'C');
$pdf->Ln(0);

// Nomor Surat (contoh)
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 0, 'Nomor: '.$pengaturan['nomor_surat'], 0, 1, 'C');
$pdf->Ln(10);

// Isi Surat
$pdf->MultiCell(0, 0, 'Yang bertanda tangan di bawah ini, Kepala '.$pengaturan['nama_sekolah'].', menerangkan bahwa:', 0, 'L');
$pdf->Ln(5);

// Data Siswa
$pdf->Cell(30, 0, 'Nama Siswa', 0, 0, 'L');
$pdf->Cell(5, 0, ':', 0, 0, 'C');
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 0, $siswa['nama'], 0, 1, 'L');

$pdf->SetFont('times', '', 12);
$pdf->Cell(30, 0, 'NIS/NISN', 0, 0, 'L');
$pdf->Cell(5, 0, ':', 0, 0, 'C');
$pdf->Cell(0, 0, $siswa['nis'], 0, 1, 'L');

$pdf->Cell(30, 0, 'Kelas', 0, 0, 'L');
$pdf->Cell(5, 0, ':', 0, 0, 'C');
$pdf->Cell(0, 0, $siswa['kelas'], 0, 1, 'L');
$pdf->Ln(5);

// Status Kelulusan
$pdf->MultiCell(0, 0, 'Berdasarkan hasil rapat dewan guru pada tanggal ' .tgl_indo($siswa['tanggal_pengumuman']) . ', siswa tersebut dinyatakan:', 0, 'L');
$pdf->Ln(5);

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 0, strtoupper($siswa['status_kelulusan']), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('times', '', 12);
$pdf->MultiCell(0, 0, 'Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'L');
$pdf->Ln(15);

// Tanda Tangan
$pdf->SetX(120);
$pdf->Cell(0, 0, $pengaturan['kota_sekolah'] .', ' .tgl_indo($siswa['tanggal_pengumuman']), 0, 1, 'L');
$pdf->Ln(2);
$pdf->SetX(120);
$pdf->Cell(0, 0, 'Kepala Sekolah,', 0, 1, 'L');
$pdf->SetY($pdf->GetY() - 5);
// Tanda tangan kepala sekolah
if (!empty($pengaturan['tanda_tangan']) && file_exists($pengaturan['tanda_tangan'])) {
    $pdf->Image($pengaturan['tanda_tangan'], 120, $pdf->GetY(), 35, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
}

// Stempel sekolah jika ada
if (!empty($pengaturan['stempel']) && file_exists($pengaturan['stempel'])) {
    $pdf->Image($pengaturan['stempel'], 110, $pdf->GetY() - 5, 40, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
}
$pdf->Ln(30);

$pdf->SetFont('times', 'BU', 12);
$pdf->SetX(120);
$pdf->Cell(0, 0, $pengaturan['nama_kepala_sekolah'], 0, 1, 'L');
$pdf->SetFont('times', '', 12);
$pdf->SetX(120);
$pdf->Cell(0, 0, "NIP.".$pengaturan['nip_kepala_sekolah'], 0, 1, 'L');

// Output PDF
$pdf->Output('Surat_Keterangan_Kelulusan_' . $siswa['nis'] . '.pdf', 'D');
exit;