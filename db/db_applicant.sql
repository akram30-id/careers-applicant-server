-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2023 at 03:29 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_applicant`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keahlian`
--

CREATE TABLE `tbl_keahlian` (
  `id_keahlian` bigint(20) NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `jenis_sertifikat` varchar(72) NOT NULL,
  `tgl_berlaku` date NOT NULL,
  `tgl_expired` date NOT NULL,
  `file_sertifikat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_keahlian`
--

INSERT INTO `tbl_keahlian` (`id_keahlian`, `id_personal`, `jenis_sertifikat`, `tgl_berlaku`, `tgl_expired`, `file_sertifikat`) VALUES
(24, 57, 'CCNA 1 v.7 - Introduction to Network', '2019-12-10', '2023-12-10', 'CCNA1.pdf'),
(25, 58, 'CCNA 1 v.7 - Introduction to Network', '2018-12-10', '2022-12-10', 'CCNA11.pdf'),
(26, 59, 'CCNA 1 v.7 - Introduction to Network', '2018-12-12', '2022-12-12', 'CCNA12.pdf'),
(27, 60, 'CCNA 1 v.7 - Introduction to Network', '2019-12-10', '2023-12-10', 'CCNA13.pdf'),
(31, 64, 'CCNA 1 v.7 - Introduction to Network', '2019-12-10', '2023-12-10', 'CCNA17.pdf'),
(32, 65, 'CCNA 1 v.7 - Introduction to Network', '2019-11-10', '2023-12-10', 'CCNA18.pdf'),
(33, 66, 'CCNA 1 v.7 - Introduction to Network', '2019-12-10', '2023-12-10', 'CCNA14.pdf'),
(34, 67, 'CCNA 1 v.7 - Introduction to Network', '2019-12-10', '2023-12-10', 'CCNA15.pdf'),
(35, 68, 'CCNA 1 v.7 - Introduction to Network', '2019-12-12', '2019-12-12', 'CCNA16.pdf'),
(36, 69, 'CCNA 1 v.7 - Introduction to Network', '2019-01-01', '2023-01-01', 'CCNA19.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pendidikan`
--

CREATE TABLE `tbl_pendidikan` (
  `id_pendidikan` bigint(20) NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `jenjang_pendidikan` int(11) NOT NULL,
  `prodi_pendidikan` varchar(32) NOT NULL,
  `institusi_pendidikan` varchar(64) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `achievement_pendidikan` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pendidikan`
--

INSERT INTO `tbl_pendidikan` (`id_pendidikan`, `id_personal`, `jenjang_pendidikan`, `prodi_pendidikan`, `institusi_pendidikan`, `tgl_mulai`, `tgl_selesai`, `achievement_pendidikan`) VALUES
(53, 57, 4, 'Teknologi Informasi', 'UBSI', '2019-07-20', '2023-08-20', 'adaaaa'),
(54, 58, 4, 'Sistem Informasi', 'UBSI', '2018-08-20', '2021-08-20', 'adaaa'),
(55, 59, 4, 'Sistem Informasi', 'UBSI', '2018-08-20', '2021-08-20', 'ada'),
(56, 60, 4, 'Teknik Industri', 'Universitas Darma Persada', '2019-07-20', '2023-07-20', 'ada'),
(60, 64, 4, 'Teknologi Informasi', 'UBSI', '2019-08-10', '2023-08-10', 'ada'),
(61, 65, 4, 'Teknologi Informasi', 'UBSI', '2019-08-20', '2023-08-20', 'ada'),
(62, 66, 4, 'Teknologi Informasi', 'UBSI', '2019-08-20', '2023-08-20', 'ada'),
(63, 67, 4, 'Teknologi Informasi', 'UBSI', '2019-08-25', '2023-08-25', 'ada'),
(64, 68, 4, 'Teknologi Informasi', 'UBSI', '2019-08-15', '2023-08-15', 'ada'),
(65, 69, 4, 'Sistem Informasi', 'UBSI', '2018-08-10', '2021-08-10', 'ada dah');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengalaman`
--

CREATE TABLE `tbl_pengalaman` (
  `id_pengalaman` bigint(20) NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `institusi_kerja` varchar(64) NOT NULL,
  `jabatan_kerja` varchar(32) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `achievement_kerja` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengalaman`
--

INSERT INTO `tbl_pengalaman` (`id_pengalaman`, `id_personal`, `institusi_kerja`, `jabatan_kerja`, `tgl_mulai`, `tgl_selesai`, `achievement_kerja`) VALUES
(48, 57, 'insititusi 1', 'jabatan 1', '2018-11-10', '2021-11-10', 'ada pokoknya'),
(49, 57, 'insititusi 2', 'jabatan 2', '2021-12-10', '2022-01-10', 'ada juga lah'),
(50, 58, 'insititusi 1', 'jabatan 1', '2020-01-10', '2022-01-10', 'ada pokoknya'),
(51, 58, 'insititusi 2', 'jabatan 2', '2022-02-20', '2023-02-20', 'ada juga'),
(52, 59, 'insititusi 1', 'jabatan 1', '2018-12-10', '2022-12-10', 'ada pokoknya'),
(53, 59, 'insititusi 2', 'jabatan 2', '2022-12-12', '2023-02-12', 'ada juga'),
(54, 60, 'insititusi 1', 'jabatan 1', '2018-12-10', '2021-12-10', 'ada pokoknya'),
(55, 60, 'insititusi 2', 'jabatan 2', '2022-01-10', '2023-01-10', 'ada juga'),
(62, 64, 'insititusi 1', 'jabatan 1', '2019-12-10', '2021-12-10', 'ada pokoknya'),
(63, 64, 'insititusi 2', 'jabatan 2', '2021-12-15', '2022-12-15', 'ada juga'),
(64, 65, 'insititusi 1', 'jabatan 1', '2019-12-10', '2022-12-10', 'ada lah'),
(65, 65, 'insititusi 2', 'jabatan 2', '2018-12-09', '2019-12-09', 'ada juga'),
(66, 66, 'insititusi 1', 'jabatan 1', '2021-01-20', '2023-02-20', 'ada pokoknya'),
(67, 66, 'insititusi 2', 'jabatan 2', '2021-12-06', '2022-08-08', 'ada juga'),
(68, 67, 'insititusi 1', 'programmer', '2019-08-05', '2022-08-05', 'ada dah'),
(69, 67, 'insititusi 2', 'software engineer', '2022-08-10', '2023-01-15', 'ada juga dah'),
(70, 68, 'insititusi 1', 'programmer', '2019-01-15', '2021-01-15', 'ada dah'),
(71, 68, 'insititusi 2', 'software engineer', '2021-02-10', '2023-02-10', 'ada juga'),
(72, 69, 'insititusi 1', 'programmer', '2019-01-10', '2021-01-10', 'ada pokoknya'),
(73, 69, 'insititusi 2', 'software engineer', '2021-02-20', '2023-02-20', 'ada juga');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personal`
--

CREATE TABLE `tbl_personal` (
  `id_personal` bigint(20) NOT NULL,
  `id_loker` bigint(20) NOT NULL,
  `nama_lengkap` varchar(32) NOT NULL,
  `tempat_lahir` varchar(64) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `kontak` varchar(16) NOT NULL,
  `email` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_personal`
--

INSERT INTO `tbl_personal` (`id_personal`, `id_loker`, `nama_lengkap`, `tempat_lahir`, `tgl_lahir`, `kontak`, `email`) VALUES
(57, 4300123080521, 'Khofifah Indar Parawansa', 'Jakarta', '1999-11-22', '081282818281', 'khofifahindarp@gmail.com'),
(58, 4300123080521, 'Maystira Anggraeni', 'Wonogiri', '2000-05-20', '082188873997', 'maystira@mail.com'),
(59, 4300123080521, 'Maystira Anggraeni', 'Wonogiri', '2000-05-20', '0822888777222', 'maystira@mail.com'),
(60, 4300123080521, 'Fany Kusumawardhani', 'Jakarta', '2021-05-25', '0821766350991', 'fanykusuma@gmail.com'),
(64, 4300123080521, 'Trisari Gandania Zalukhu', 'Nias', '2000-01-10', '082177776666', 'trasarizalukhu@gmail.com'),
(65, 4300123080521, 'Ananda Akram Syahrastani', 'Pringsewu', '2001-11-30', '082112624390', 'anandaakrams@gmail.com'),
(66, 3150223220459, 'Ananda Akram Syahrastani', 'Pringsewu', '2001-11-30', '082112624390', 'anandaakrams@gmail.com'),
(67, 3150223220459, 'Trisari Gandania Zalukhu', 'Nias', '2000-01-10', '0877666665555', 'trasarizalukhu@gmail.com'),
(68, 3150223220459, 'Khofifah Indar Parawansa', 'Jakarta', '1999-11-22', '08776665555', 'khofifahindarp@gmail.com'),
(69, 3150223220459, 'Maystira Anggraeni', 'Wonogiri', '2000-05-20', '087655554444', 'maystira@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_referensi`
--

CREATE TABLE `tbl_referensi` (
  `id_referensi` bigint(20) NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `referensi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_referensi`
--

INSERT INTO `tbl_referensi` (`id_referensi`, `id_personal`, `referensi`) VALUES
(25, 57, 'Iklan Lowongan Kerja'),
(26, 58, 'Iklan Lowongan Kerja'),
(27, 59, 'Iklan Lowongan Kerja'),
(28, 60, 'Iklan Lowongan Kerja'),
(32, 64, 'Iklan Lowongan Kerja'),
(33, 65, 'Iklan Lowongan Kerja'),
(34, 66, 'Iklan Lowongan Kerja'),
(35, 67, 'Iklan Lowongan Kerja'),
(36, 68, 'Iklan Lowongan Kerja'),
(37, 69, 'Iklan Lowongan Kerja');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status`
--

CREATE TABLE `tbl_status` (
  `id_status` bigint(20) NOT NULL,
  `id_personal` bigint(20) NOT NULL,
  `status` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`id_status`, `id_personal`, `status`) VALUES
(7, 57, 'disqualified'),
(8, 58, 'disqualified'),
(9, 59, 'disqualified'),
(10, 60, 'disqualified'),
(14, 64, 'disqualified'),
(15, 65, 'disqualified'),
(16, 66, 'disqualified'),
(17, 67, 'qualified'),
(18, 68, 'qualified'),
(19, 69, 'qualified');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_keahlian`
--
ALTER TABLE `tbl_keahlian`
  ADD PRIMARY KEY (`id_keahlian`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indexes for table `tbl_pendidikan`
--
ALTER TABLE `tbl_pendidikan`
  ADD PRIMARY KEY (`id_pendidikan`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indexes for table `tbl_pengalaman`
--
ALTER TABLE `tbl_pengalaman`
  ADD PRIMARY KEY (`id_pengalaman`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indexes for table `tbl_personal`
--
ALTER TABLE `tbl_personal`
  ADD PRIMARY KEY (`id_personal`);

--
-- Indexes for table `tbl_referensi`
--
ALTER TABLE `tbl_referensi`
  ADD PRIMARY KEY (`id_referensi`),
  ADD KEY `id_personal` (`id_personal`);

--
-- Indexes for table `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD PRIMARY KEY (`id_status`),
  ADD KEY `id_personal` (`id_personal`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_keahlian`
--
ALTER TABLE `tbl_keahlian`
  MODIFY `id_keahlian` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_pendidikan`
--
ALTER TABLE `tbl_pendidikan`
  MODIFY `id_pendidikan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_pengalaman`
--
ALTER TABLE `tbl_pengalaman`
  MODIFY `id_pengalaman` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tbl_personal`
--
ALTER TABLE `tbl_personal`
  MODIFY `id_personal` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tbl_referensi`
--
ALTER TABLE `tbl_referensi`
  MODIFY `id_referensi` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_status`
--
ALTER TABLE `tbl_status`
  MODIFY `id_status` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_keahlian`
--
ALTER TABLE `tbl_keahlian`
  ADD CONSTRAINT `one_to_one_keahlian_personal` FOREIGN KEY (`id_personal`) REFERENCES `tbl_personal` (`id_personal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pendidikan`
--
ALTER TABLE `tbl_pendidikan`
  ADD CONSTRAINT `one_to_one_pendidikan_personal` FOREIGN KEY (`id_personal`) REFERENCES `tbl_personal` (`id_personal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pengalaman`
--
ALTER TABLE `tbl_pengalaman`
  ADD CONSTRAINT `one_to_one_pengalaman_personal` FOREIGN KEY (`id_personal`) REFERENCES `tbl_personal` (`id_personal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_referensi`
--
ALTER TABLE `tbl_referensi`
  ADD CONSTRAINT `one_to_one_referensi_personal` FOREIGN KEY (`id_personal`) REFERENCES `tbl_personal` (`id_personal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD CONSTRAINT `one_to_one_status_personal` FOREIGN KEY (`id_personal`) REFERENCES `tbl_personal` (`id_personal`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
