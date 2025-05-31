-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 04:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelulusan_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sekolah`
--

CREATE TABLE `pengaturan_sekolah` (
  `id` int(11) NOT NULL,
  `nama_sekolah` varchar(100) NOT NULL,
  `alamat_sekolah` text NOT NULL,
  `kota_sekolah` varchar(50) NOT NULL,
  `nama_kepala_sekolah` varchar(100) NOT NULL,
  `nip_kepala_sekolah` varchar(20) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL DEFAULT '123/SKL/UND/VI/2023',
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `tanda_tangan` varchar(255) DEFAULT NULL,
  `stempel` varchar(255) DEFAULT NULL,
  `tanggal_pengumuman` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_sekolah`
--

INSERT INTO `pengaturan_sekolah` (`id`, `nama_sekolah`, `alamat_sekolah`, `kota_sekolah`, `nama_kepala_sekolah`, `nip_kepala_sekolah`, `nomor_surat`, `logo_sekolah`, `tanda_tangan`, `stempel`, `tanggal_pengumuman`, `updated_at`) VALUES
(1, 'SMP Negeri 6 Sudimoro', 'Jl. Raya Pacitan-Trenggalek, km.55, Desa Sukorejo, Kecamatan Sudimoro, Pacitan', 'Sudimoro', 'Drs. Marjoko, M.M.Pd.', '196809161999031010', '400.11.1/22/408.37.15.50/2025', 'uploads/logo_1748689528.png', 'uploads/ttd_1748689528.png', 'uploads/stempel_1748689528.png', '2025-06-02 10:00:00', '2025-05-31 13:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `status_kelulusan` enum('Lulus','Tidak Lulus') NOT NULL,
  `tanggal_pengumuman` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama`, `kelas`, `status_kelulusan`, `tanggal_pengumuman`) VALUES
(4, '0106081332', 'ADIKA MAHFUDZ WAKHID', 'Kelas IX', 'Lulus', '2025-06-02'),
(5, '0085143644', 'ADITYA PUTRA', 'Kelas IX', 'Lulus', '2025-06-02'),
(6, '0106506862', 'ALMIRA PUTRI', 'Kelas IX', 'Lulus', '2025-06-02'),
(7, '0108744267', 'ALVINO DIKY RIZKYAWAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(8, '0108299251', 'ANANDA GITAFREYA', 'Kelas IX', 'Lulus', '2025-06-02'),
(9, '0097838371', 'ANDIN SEFTIA RAMADHANI', 'Kelas IX', 'Lulus', '2025-06-02'),
(10, '3103815937', 'Arjuno Aji Pamungkas', 'Kelas IX', 'Lulus', '2025-06-02'),
(11, '0104849575', 'Assyfa Khoirunisa', 'Kelas IX', 'Lulus', '2025-06-02'),
(12, '0106563258', 'CHRISTIAN RIZKI PRATAMA', 'Kelas IX', 'Lulus', '2025-06-02'),
(13, '0091142708', 'DIMAS SEPTIAWAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(14, '0105970968', 'FADHIL REKA PRANATA', 'Kelas IX', 'Lulus', '2025-06-02'),
(15, '0091997799', 'FAHRISYAD GALIH PANORAGA', 'Kelas IX', 'Lulus', '2025-06-02'),
(16, '0097307826', 'FARHAN DHEKA PRATAMA', 'Kelas IX', 'Lulus', '2025-06-02'),
(17, '0105230704', 'FEBBY ELLY ZESTKIA', 'Kelas IX', 'Lulus', '2025-06-02'),
(18, '0098325417', 'I\'AM CANDRA SETIAWAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(19, '0105874784', 'IKA ANDRIANI', 'Kelas IX', 'Lulus', '2025-06-02'),
(20, '3102460054', 'Indah Permatasari', 'Kelas IX', 'Lulus', '2025-06-02'),
(21, '0096968133', 'KEVIN PUTRA BRILIAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(22, '0107595741', 'KRISNA DWI PERMANA', 'Kelas IX', 'Lulus', '2025-06-02'),
(23, '0093649159', 'LUCVI ALFIANDO', 'Kelas IX', 'Lulus', '2025-06-02'),
(24, '0109539715', 'NINDY AYU NINATA', 'Kelas IX', 'Lulus', '2025-06-02'),
(25, '0105834041', 'OKTAVIA ALFIN NURROHMAH', 'Kelas IX', 'Lulus', '2025-06-02'),
(26, '3103289756', 'RANGGA PUTRA PRATAMA', 'Kelas IX', 'Lulus', '2025-06-02'),
(27, '0098088070', 'REMBO ARBIAN SAPUTRA', 'Kelas IX', 'Lulus', '2025-06-02'),
(28, '0103549693', 'SECILLIA MILKA EVAGELISTA', 'Kelas IX', 'Lulus', '2025-06-02'),
(29, '0108835524', 'VEALEN VILDA ARZUTA', 'Kelas IX', 'Lulus', '2025-06-02'),
(30, '0096410963', 'ZADA AFRILIO', 'Kelas IX', 'Lulus', '2025-06-02'),
(31, '0102129982', 'ADITYA ADJI WIJAYA', 'Kelas IX', 'Lulus', '2025-06-02'),
(32, '0099784945', 'AFIFA AMAILIA PUTRI', 'Kelas IX', 'Lulus', '2025-06-02'),
(33, '0085224867', 'Ahmad Anam Susanto', 'Kelas IX', 'Lulus', '2025-06-02'),
(34, '0094347916', 'AHMAD RAFIQ KURNIAWAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(35, '0097378976', 'ALUNG BAGAS DANUARTA', 'Kelas IX', 'Lulus', '2025-06-02'),
(36, '0101889420', 'ALVIANO RAFEL JUNIOR', 'Kelas IX', 'Lulus', '2025-06-02'),
(37, '0091349400', 'Anik Widia Sari', 'Kelas IX', 'Lulus', '2025-06-02'),
(38, '0104064312', 'AZIZATUL FIRDHAUS', 'Kelas IX', 'Lulus', '2025-06-02'),
(39, '0085165918', 'DEBRI ALVITO', 'Kelas IX', 'Lulus', '2025-06-02'),
(40, '0097294556', 'DENI ANDARISTA', 'Kelas IX', 'Lulus', '2025-06-02'),
(41, '0108079919', 'FADHIL AHMAD ROBBANI', 'Kelas IX', 'Lulus', '2025-06-02'),
(42, '0099187067', 'INTAN PUTRI OKTAVIA', 'Kelas IX', 'Lulus', '2025-06-02'),
(43, '0107806202', 'JECKY ANGGA FEBRIAN', 'Kelas IX', 'Lulus', '2025-06-02'),
(44, '0094366162', 'JESICA FATIMATURROHMAH', 'Kelas IX', 'Lulus', '2025-06-02'),
(45, '0091939564', 'KALISTA AURELIA PUTRI', 'Kelas IX', 'Lulus', '2025-06-02'),
(46, '3107359594', 'Maita Nurhidayah', 'Kelas IX', 'Lulus', '2025-06-02'),
(47, '3093056680', 'Marcelino Oki Firnando', 'Kelas IX', 'Lulus', '2025-06-02'),
(48, '0106061196', 'NANDINI EKA MUSFITASARI', 'Kelas IX', 'Lulus', '2025-06-02'),
(49, '0104065898', 'NELY AYU FEBRIANI', 'Kelas IX', 'Lulus', '2025-06-02'),
(50, '0102234055', 'PANDU MARVINO', 'Kelas IX', 'Lulus', '2025-06-02'),
(51, '0105264523', 'PANJI NURCAHYONO', 'Kelas IX', 'Lulus', '2025-06-02'),
(52, '0108743287', 'RAHMA SAFIRA', 'Kelas IX', 'Lulus', '2025-06-02'),
(53, '0086403882', 'Reihan Adi Pratama', 'Kelas IX', 'Lulus', '2025-06-02'),
(54, '0093455912', 'RIZQI DAVA RINTO', 'Kelas IX', 'Lulus', '2025-06-02'),
(55, '3100549179', 'Sifa Usman Rizali', 'Kelas IX', 'Lulus', '2025-06-02'),
(56, '0097302480', 'VIKY OKTAVIANO', 'Kelas IX', 'Lulus', '2025-06-02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaturan_sekolah`
--
ALTER TABLE `pengaturan_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaturan_sekolah`
--
ALTER TABLE `pengaturan_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
