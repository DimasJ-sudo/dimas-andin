-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 09:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suarakitav2`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_username` varchar(50) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `detail` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `admin_username`, `action`, `detail`, `created_at`) VALUES
(1, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:02:56'),
(2, 1, 'admin', 'Update Laporan', 'ID SK-0610-01 → Selesai', '2026-06-10 07:03:20'),
(3, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:03:25'),
(4, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:04:07'),
(5, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:04:19'),
(6, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:05:03'),
(7, 1, 'admin', 'Update Laporan', 'ID SK-0610-02 → Diterima', '2026-06-10 07:05:15'),
(8, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:05:17'),
(9, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:15:36'),
(10, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:15:48'),
(11, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:41:06'),
(12, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:41:49'),
(13, 1, 'admin', 'Login', 'Admin login berhasil', '2026-06-10 07:43:32'),
(14, 1, 'admin', 'Update Laporan', 'ID SK-0610-03 -> Selesai', '2026-06-10 07:43:54'),
(15, 1, 'admin', 'Logout', 'Admin keluar', '2026-06-10 07:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `nama`, `created_at`) VALUES
(1, 'admin', '$2y$10$Jwr8MD8p5lThQNRet4EXnenfqvPOm3L4S9UHNa7Haqql2LkXayfHC', 'Administrator', '2026-06-10 06:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report_uid` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `urgency` varchar(20) NOT NULL DEFAULT 'Sedang',
  `is_anon` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'Diterima',
  `admin_response` text DEFAULT NULL,
  `upvotes` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_uid`, `category`, `description`, `urgency`, `is_anon`, `status`, `admin_response`, `upvotes`, `ip_address`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'SK-0610-01', 'Perundungan (Bullying)', 'aku di buli sampe gila', 'Sedang', 1, 'Selesai', 'baiklah silahkan hubungi guru ya', 0, '::1', 0, '2026-06-10 07:01:33', '2026-06-10 07:03:20'),
(2, 'SK-0610-02', 'Fasilitas Rusak', 'kursi rapuk', 'Rendah', 0, 'Diterima', 'iya akan segera di perbaiki', 1, '::1', 1, '2026-06-10 07:04:49', '2026-06-10 07:16:44'),
(3, 'SK-0610-03', 'Fasilitas Rusak', 'rryttryrdrdr ryfrdrdedted', 'Tinggi', 1, 'Selesai', 'ftr', 0, '::1', 1, '2026-06-10 07:43:07', '2026-06-10 07:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `report_logs`
--

CREATE TABLE `report_logs` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `status_change` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_logs`
--

INSERT INTO `report_logs` (`id`, `report_id`, `status_change`, `note`, `created_at`) VALUES
(1, 1, 'Diterima', 'Laporan masuk ke sistem.', '2026-06-10 07:01:33'),
(2, 1, 'Selesai', 'Status diubah ke Selesai', '2026-06-10 07:03:20'),
(3, 2, 'Diterima', 'Laporan masuk ke sistem.', '2026-06-10 07:04:49'),
(4, 3, 'Diterima', 'Laporan masuk ke sistem.', '2026-06-10 07:43:07'),
(5, 3, 'Selesai', 'Status diubah ke Selesai', '2026-06-10 07:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `report_votes`
--

CREATE TABLE `report_votes` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_votes`
--

INSERT INTO `report_votes` (`id`, `report_id`, `ip_address`, `created_at`) VALUES
(1, 2, '::1', '2026-06-10 07:16:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_uid` (`report_uid`),
  ADD KEY `idx_uid` (`report_uid`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `report_logs`
--
ALTER TABLE `report_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `report_votes`
--
ALTER TABLE `report_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_vote` (`report_id`,`ip_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_logs`
--
ALTER TABLE `report_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_votes`
--
ALTER TABLE `report_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `report_logs`
--
ALTER TABLE `report_logs`
  ADD CONSTRAINT `report_logs_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_votes`
--
ALTER TABLE `report_votes`
  ADD CONSTRAINT `report_votes_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
