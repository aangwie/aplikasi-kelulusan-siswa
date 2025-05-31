<?php
error_reporting(0);
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kelulusan_siswa';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil pengaturan dari database
$pengaturan = $conn->query("SELECT * FROM pengaturan_sekolah LIMIT 1")->fetch_assoc();

// Set variabel global
$nama_sekolah = $pengaturan['nama_sekolah'];
$alamat_sekolah = $pengaturan['alamat_sekolah'];
$kota_sekolah = $pengaturan['kota_sekolah'];
$tanggal_pengumuman = $pengaturan['tanggal_pengumuman'];

// Set timezone
date_default_timezone_set('Asia/Jakarta');

function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function jam_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode(':', $tanggal);
    //$pecahkanjam = explode(':', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $pecahkan[1] . ' ' . $pecahkan[0] ;
}
?>