-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 18, 2026 at 12:54 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `queue_simulator`
--

-- --------------------------------------------------------

--
-- Table structure for table `dead_jobs`
--

CREATE TABLE `dead_jobs` (
  `id` int NOT NULL,
  `job_id` int NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `payload` text,
  `attempts` int DEFAULT NULL,
  `failed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dead_jobs`
--

INSERT INTO `dead_jobs` (`id`, `job_id`, `type`, `payload`, `attempts`, `failed_at`) VALUES
(1, 1, 'light_task', '{\"rows\":15000,\"sleep\":10}', 3, '2026-01-18 19:45:54'),
(2, 18, 'light_task', '{\"sleep\":1}', 3, '2026-01-18 19:46:46'),
(3, 30, 'light_task', '{\"sleep\":0}', 3, '2026-01-18 19:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int NOT NULL,
  `type` varchar(50) NOT NULL,
  `payload` text,
  `expected_weight` enum('light','heavy') NOT NULL,
  `status` enum('pending','processing','success','failed','dead') DEFAULT 'pending',
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `execution_ms` int DEFAULT NULL,
  `attempts` int DEFAULT '0',
  `max_attempts` int DEFAULT '3',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `type`, `payload`, `expected_weight`, `status`, `started_at`, `finished_at`, `execution_ms`, `attempts`, `max_attempts`, `created_at`, `updated_at`) VALUES
(1, 'light_task', '{\"rows\":15000,\"sleep\":10}', 'heavy', 'dead', '2026-01-18 19:45:44', '2026-01-18 19:45:54', 10004, 3, 3, '2026-01-18 19:44:38', '2026-01-18 19:45:54'),
(2, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:45:57', '2026-01-18 19:45:57', 0, 2, 3, '2026-01-18 19:44:38', '2026-01-18 19:45:57'),
(3, 'heavy_task', '{\"rows\":7303,\"sleep\":4}', 'heavy', 'success', '2026-01-18 19:45:58', '2026-01-18 19:46:02', 4005, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:02'),
(4, 'heavy_task', '{\"rows\":7615,\"sleep\":3}', 'heavy', 'success', '2026-01-18 19:46:03', '2026-01-18 19:46:06', 3012, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:06'),
(5, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:08', '2026-01-18 19:46:08', 0, 1, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:08'),
(6, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:09', '2026-01-18 19:46:09', 0, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:09'),
(7, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:10', '2026-01-18 19:46:10', 0, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:10'),
(8, 'heavy_task', '{\"rows\":3466,\"sleep\":5}', 'heavy', 'success', '2026-01-18 19:46:11', '2026-01-18 19:46:16', 5012, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:16'),
(9, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:19', '2026-01-18 19:46:19', 0, 1, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:19'),
(10, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:21', '2026-01-18 19:46:21', 0, 1, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:21'),
(11, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:46:22', '2026-01-18 19:46:23', 1009, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:23'),
(12, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:46:24', '2026-01-18 19:46:25', 1007, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:25'),
(13, 'heavy_task', '{\"rows\":3715,\"sleep\":6}', 'heavy', 'success', '2026-01-18 19:46:26', '2026-01-18 19:46:32', 6013, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:32'),
(14, 'heavy_task', '{\"rows\":8943,\"sleep\":3}', 'heavy', 'success', '2026-01-18 19:46:33', '2026-01-18 19:46:36', 3001, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:36'),
(15, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:46:37', '2026-01-18 19:46:38', 1001, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:38'),
(16, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:39', '2026-01-18 19:46:39', 0, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:39'),
(17, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:46:40', '2026-01-18 19:46:40', 0, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:40'),
(18, 'light_task', '{\"sleep\":1}', 'light', 'dead', '2026-01-18 19:46:45', '2026-01-18 19:46:46', 1010, 3, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:46'),
(19, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:46:47', '2026-01-18 19:46:48', 1010, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:48'),
(20, 'heavy_task', '{\"rows\":3314,\"sleep\":6}', 'heavy', 'success', '2026-01-18 19:46:49', '2026-01-18 19:46:55', 6006, 0, 3, '2026-01-18 19:44:38', '2026-01-18 19:46:55'),
(21, 'heavy_task', '{\"rows\":30000,\"sleep\":15}', 'heavy', 'success', '2026-01-18 19:48:59', '2026-01-18 19:49:14', 15013, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:14'),
(22, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:49:15', '2026-01-18 19:49:15', 0, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:15'),
(23, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:49:16', '2026-01-18 19:49:16', 0, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:16'),
(24, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:17', '2026-01-18 19:49:18', 1010, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:18'),
(25, 'heavy_task', '{\"rows\":7703,\"sleep\":4}', 'heavy', 'success', '2026-01-18 19:49:19', '2026-01-18 19:49:23', 4002, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:23'),
(26, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:49:25', '2026-01-18 19:49:25', 0, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:25'),
(27, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:28', '2026-01-18 19:49:29', 1000, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:29'),
(28, 'heavy_task', '{\"rows\":9241,\"sleep\":6}', 'heavy', 'success', '2026-01-18 19:49:30', '2026-01-18 19:49:36', 6003, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:36'),
(29, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:37', '2026-01-18 19:49:38', 1000, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:38'),
(30, 'light_task', '{\"sleep\":0}', 'light', 'dead', '2026-01-18 19:49:41', '2026-01-18 19:49:41', 0, 3, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:41'),
(31, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:44', '2026-01-18 19:49:45', 1002, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:45'),
(32, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:46', '2026-01-18 19:49:47', 1011, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:47'),
(33, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:49:48', '2026-01-18 19:49:49', 1000, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:49'),
(34, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:49:52', '2026-01-18 19:49:52', 0, 2, 3, '2026-01-18 19:47:37', '2026-01-18 19:49:52'),
(35, 'heavy_task', '{\"rows\":3118,\"sleep\":6}', 'heavy', 'success', '2026-01-18 19:50:00', '2026-01-18 19:50:06', 6028, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:06'),
(36, 'heavy_task', '{\"rows\":8582,\"sleep\":6}', 'heavy', 'success', '2026-01-18 19:50:14', '2026-01-18 19:50:20', 6008, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:20'),
(37, 'heavy_task', '{\"rows\":5827,\"sleep\":4}', 'heavy', 'success', '2026-01-18 19:50:26', '2026-01-18 19:50:30', 4004, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:30'),
(38, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:50:31', '2026-01-18 19:50:32', 1003, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:32'),
(39, 'light_task', '{\"sleep\":0}', 'light', 'success', '2026-01-18 19:50:33', '2026-01-18 19:50:33', 0, 0, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:33'),
(40, 'light_task', '{\"sleep\":1}', 'light', 'success', '2026-01-18 19:50:36', '2026-01-18 19:50:37', 1003, 1, 3, '2026-01-18 19:47:37', '2026-01-18 19:50:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dead_jobs`
--
ALTER TABLE `dead_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jobs_weight` (`expected_weight`),
  ADD KEY `idx_jobs_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dead_jobs`
--
ALTER TABLE `dead_jobs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
