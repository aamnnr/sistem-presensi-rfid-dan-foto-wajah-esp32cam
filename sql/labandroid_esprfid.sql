-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 17, 2022 at 10:17 AM
-- Server version: 10.5.15-MariaDB-cll-lve
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labandroid_esprfid`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(12) NOT NULL,
  `admin_username` varchar(30) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_password`, `admin_nama`) VALUES
(1, 'admin', 'admin', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `data_users`
--

CREATE TABLE `data_users` (
  `id` double NOT NULL,
  `rfid` varchar(50) NOT NULL,
  `nama` varchar(64) NOT NULL,
  `alamat` text NOT NULL,
  `umur` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_users`
--

INSERT INTO `data_users` (`id`, `rfid`, `nama`, `alamat`, `umur`, `status`, `updated_at`) VALUES
(1, 'A19E551B', 'Wardana Adiaksa', 'Jakarta', 12, 0, '2022-04-27 04:08:08'),
(2, 'B23D221B', 'Rudi', 'Bandung', 12, 0, '2022-04-27 04:10:49'),
(15, 'F3C1D89A', 'Badrun Alam', 'Bandung', 13, 0, '2022-07-09 12:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE `izin` (
  `izin_id` int(12) NOT NULL,
  `siswa_id` int(12) NOT NULL,
  `izin_nama` varchar(50) NOT NULL,
  `izin_dari` date NOT NULL,
  `izin_sampai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(12) NOT NULL,
  `kelas_nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `kelas_nama`) VALUES
(1, 'X TKRO 1'),
(2, 'X TKRO 2'),
(3, 'X TKRO 3');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `jadwal_id` int(12) NOT NULL,
  `kelas_id` int(12) NOT NULL,
  `jadwal_hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jadwal_masuk` time NOT NULL,
  `jadwal_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`jadwal_id`, `kelas_id`, `jadwal_hari`, `jadwal_masuk`, `jadwal_pulang`) VALUES
(1, 1, 'Senin', '12:00:00', '12:00:00'),
(2, 1, 'Selasa', '00:00:00', '00:00:00'),
(3, 1, 'Rabu', '00:00:00', '00:00:00'),
(4, 1, 'Kamis', '00:01:00', '00:36:00'),
(5, 1, 'Jumat', '00:00:00', '00:00:00'),
(6, 1, 'Sabtu', '00:00:00', '00:00:00'),
(7, 1, 'Minggu', '00:00:00', '00:00:00'),
(8, 2, 'Senin', '00:00:00', '00:00:00'),
(9, 2, 'Selasa', '00:00:00', '00:00:00'),
(10, 2, 'Rabu', '00:00:00', '00:00:00'),
(11, 2, 'Kamis', '00:00:00', '00:00:00'),
(12, 2, 'Jumat', '00:00:00', '00:00:00'),
(13, 2, 'Sabtu', '00:00:00', '00:00:00'),
(14, 2, 'Minggu', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(12) NOT NULL,
  `kelas_id` int(12) NOT NULL,
  `siswa_rfid` varchar(10) NOT NULL,
  `siswa_nama` varchar(50) NOT NULL,
  `siswa_absen` varchar(16) NOT NULL,
  `siswa_jeniskelamin` enum('M','F') NOT NULL,
  `siswa_lahir` date NOT NULL,
  `siswa_nomorhp` varchar(20) NOT NULL,
  `siswa_alamat` varchar(500) NOT NULL,
  `siswa_foto` varchar(255) NOT NULL,
  `siswa_status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`siswa_id`, `kelas_id`, `siswa_rfid`, `siswa_nama`, `siswa_absen`, `siswa_jeniskelamin`, `siswa_lahir`, `siswa_nomorhp`, `siswa_alamat`, `siswa_foto`, `siswa_status`) VALUES
(9, 4, 'E3E64D15', 'nama1', '1234567890123456', 'M', '2022-07-17', '1234567890', 'jakarta', '82799958662d3e0b6971bd.png', '1');

-- --------------------------------------------------------

--
-- Table structure for table `libur`
--

CREATE TABLE `libur` (
  `libur_id` int(12) NOT NULL,
  `libur_keterangan` varchar(50) NOT NULL,
  `libur_dari` date NOT NULL,
  `libur_sampai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rekap`
--

CREATE TABLE `rekap` (
  `rekap_id` bigint(20) NOT NULL,
  `jadwal_id` int(12) NOT NULL,
  `siswa_id` int(12) NOT NULL,
  `rekap_tanggal` date NOT NULL,
  `rekap_masuk` time DEFAULT NULL,
  `rekap_keluar` time DEFAULT NULL,
  `rekap_photomasuk` varchar(255) DEFAULT NULL,
  `status1` tinyint(2) NOT NULL DEFAULT 0,
  `rekap_photokeluar` varchar(255) DEFAULT NULL,
  `status2` tinyint(2) NOT NULL DEFAULT 0,
  `rekap_keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rekap`
--

INSERT INTO `rekap` (`rekap_id`, `jadwal_id`, `siswa_id`, `rekap_tanggal`, `rekap_masuk`, `rekap_keluar`, `rekap_photomasuk`, `status1`, `rekap_photokeluar`, `status2`, `rekap_keterangan`) VALUES
(5, 8, 9, '2022-07-17', '17:13:18', '17:13:37', '2022.07.17_10:13:21.jpg', 0, '2022.07.17_10:13:38.jpg', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rfid_code`
--

CREATE TABLE `rfid_code` (
  `id` double NOT NULL,
  `rfid_code` varchar(64) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `time_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rfid_code`
--

INSERT INTO `rfid_code` (`id`, `rfid_code`, `used`, `time_update`) VALUES
(1, 'E3E64D15', 1, '2022-07-17 10:13:10'),
(2, '43050017', 0, '2022-07-17 10:11:28'),
(3, 'A35F6817', 0, '2022-07-17 10:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `users_logs`
--

CREATE TABLE `users_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `rfid` varchar(20) NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `checkindate` date NOT NULL,
  `checkintime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_logs`
--

INSERT INTO `users_logs` (`id`, `username`, `rfid`, `image_url`, `checkindate`, `checkintime`) VALUES
(1, 'Rudi', 'B23D221B', 'mages/02012021011428.jpg 	', '2022-07-09', '19:27:15'),
(2, 'Rudi', 'B23D221B', '', '2022-07-09', '19:27:28'),
(3, 'Wardana Adiaksa', 'A19E551B', '', '2022-07-09', '19:27:37'),
(4, 'Badrun Alam', 'F3C1D89A', '', '2022-07-09', '19:28:58'),
(5, 'Rudi', 'B23D221B', '', '2022-07-09', '19:50:45'),
(6, 'Wardana Adiaksa', 'A19E551B', '', '2022-07-09', '19:50:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_username` (`admin_username`);

--
-- Indexes for table `data_users`
--
ALTER TABLE `data_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`izin_id`),
  ADD KEY `izin siswaid to siswaid` (`siswa_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`),
  ADD UNIQUE KEY `kelas_nama` (`kelas_nama`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`jadwal_id`),
  ADD KEY `jadwal kelasid to kelasid` (`kelas_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `siswa_rfid` (`siswa_rfid`),
  ADD UNIQUE KEY `siswa_absen` (`siswa_absen`),
  ADD KEY `siswa kelasid to kelasid` (`kelas_id`);

--
-- Indexes for table `libur`
--
ALTER TABLE `libur`
  ADD PRIMARY KEY (`libur_id`);

--
-- Indexes for table `rekap`
--
ALTER TABLE `rekap`
  ADD PRIMARY KEY (`rekap_id`),
  ADD KEY `rekap siswaid to siswaid` (`siswa_id`),
  ADD KEY `rekapjadwalfk` (`jadwal_id`);

--
-- Indexes for table `rfid_code`
--
ALTER TABLE `rfid_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_users`
--
ALTER TABLE `data_users`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `izin`
--
ALTER TABLE `izin`
  MODIFY `izin_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `jadwal_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `libur`
--
ALTER TABLE `libur`
  MODIFY `libur_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rekap`
--
ALTER TABLE `rekap`
  MODIFY `rekap_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rfid_code`
--
ALTER TABLE `rfid_code`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_logs`
--
ALTER TABLE `users_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin siswaid to siswaid` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal kelasid to kelasid` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa kelasid to kelasid` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekap`
--
ALTER TABLE `rekap`
  ADD CONSTRAINT `rekap siswaid to siswaid` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekapjadwalfk` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
