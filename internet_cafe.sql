-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 06:46 PM
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
-- Database: `internet_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_session` datetime NOT NULL,
  `end_session` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `amount_billed` decimal(10,2) NOT NULL,
  `computer_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `start_session`, `end_session`, `duration`, `amount_billed`, `computer_name`) VALUES
(1, 10, '2025-05-03 10:30:00', '2025-05-03 11:15:00', 45, 90.00, NULL),
(2, 10, '2025-05-05 11:30:00', '2025-05-05 13:30:00', 120, 240.00, NULL),
(3, 10, '2025-05-05 11:30:00', '2025-05-05 13:30:00', 120, 240.00, NULL),
(4, 23, '2025-05-06 02:25:00', '2025-05-06 03:32:00', 67, 134.00, NULL),
(5, 24, '2025-05-06 02:25:00', '2025-05-06 03:32:00', 67, 134.00, NULL),
(6, 27, '2025-05-06 19:21:00', '2025-05-06 20:23:00', 62, 124.00, NULL),
(7, 1, '2025-05-06 21:05:00', '2025-05-06 22:08:00', 63, 126.00, NULL),
(8, 2, '2025-05-06 21:06:00', '2025-05-06 22:34:00', 88, 176.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL,
  `computer_name` varchar(100) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('available','in use','offline') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`id`, `computer_name`, `ip_address`, `status`, `created_at`) VALUES
(6, 'computer 1', '192.158.1.38', 'in use', '2025-04-24 18:04:30'),
(12, 'computer 2', '192.168.1.10', 'available', '2025-05-01 10:50:44'),
(15, 'computer 3', '192.168.1.1', 'in use', '2025-05-01 14:11:15'),
(17, 'computer 3', '192.158.1.34', 'in use', '2025-05-02 14:25:35'),
(18, 'computer4', '198.168.1.1', 'available', '2025-05-02 14:35:28'),
(20, 'lenovo', '192.168.1.1', 'available', '2025-05-03 04:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `computer_name` varchar(100) NOT NULL,
  `start_session` datetime DEFAULT NULL,
  `end_session` datetime DEFAULT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `login_time`, `logout_time`, `duration`) VALUES
(1, 1, '2025-04-18 07:21:37', NULL, NULL),
(2, 1, '2025-04-18 07:22:48', NULL, NULL),
(3, 1, '2025-04-18 08:36:14', NULL, NULL),
(4, 1, '2025-04-18 08:49:14', NULL, NULL),
(5, 1, '2025-04-18 09:16:38', NULL, NULL),
(6, 1, '2025-04-18 09:22:09', NULL, NULL),
(7, 1, '2025-04-18 09:46:15', NULL, NULL),
(8, 1, '2025-04-18 10:07:48', NULL, NULL),
(9, 1, '2025-04-18 10:23:36', NULL, NULL),
(10, 1, '2025-04-18 11:50:32', NULL, NULL),
(11, 1, '2025-04-18 16:29:23', NULL, NULL),
(12, 1, '2025-04-19 07:03:09', NULL, NULL),
(13, 1, '2025-04-19 12:05:31', NULL, NULL),
(14, 1, '2025-04-19 12:16:56', NULL, NULL),
(15, 1, '2025-04-19 12:20:16', NULL, NULL),
(16, 1, '2025-04-20 08:00:16', NULL, NULL),
(17, 1, '2025-04-21 11:50:59', NULL, NULL),
(18, 1, '2025-04-21 16:24:19', NULL, NULL),
(19, 1, '2025-04-21 16:44:29', NULL, NULL),
(20, 1, '2025-04-21 19:04:55', NULL, NULL),
(21, 1, '2025-04-22 07:01:28', NULL, NULL),
(22, 1, '2025-04-22 07:05:07', NULL, NULL),
(23, 1, '2025-04-22 10:23:17', NULL, NULL),
(24, 1, '2025-04-22 11:55:03', NULL, NULL),
(25, 1, '2025-04-22 16:42:43', NULL, NULL),
(26, 1, '2025-04-22 16:47:50', NULL, NULL),
(27, 36, '2025-04-23 09:21:45', NULL, NULL),
(28, 37, '2025-04-23 10:33:56', NULL, NULL),
(29, 1, '2025-04-23 16:23:05', NULL, NULL),
(30, 1, '2025-04-23 16:27:42', NULL, NULL),
(31, 1, '2025-04-24 14:28:12', NULL, NULL),
(32, 1, '2025-04-24 15:45:09', NULL, NULL),
(33, 1, '2025-04-24 17:28:31', NULL, NULL),
(34, 1, '2025-04-24 18:08:17', NULL, NULL),
(35, 1, '2025-04-24 19:17:17', NULL, NULL),
(36, 1, '2025-04-24 19:19:32', NULL, NULL),
(37, 1, '2025-04-24 19:28:28', NULL, NULL),
(38, 1, '2025-04-24 19:58:44', NULL, NULL),
(39, 1, '2025-04-24 20:04:04', NULL, NULL),
(40, 1, '2025-04-24 20:21:37', NULL, NULL),
(41, 1, '2025-04-25 15:17:02', NULL, NULL),
(42, 1, '2025-04-25 17:33:01', NULL, NULL),
(43, 37, '2025-04-26 05:56:54', NULL, NULL),
(44, 37, '2025-04-26 06:07:04', NULL, NULL),
(45, 39, '2025-04-26 06:22:49', NULL, NULL),
(46, 39, '2025-04-26 06:24:45', NULL, NULL),
(47, 1, '2025-04-26 07:32:38', NULL, NULL),
(48, 1, '2025-04-26 08:18:56', NULL, NULL),
(49, 37, '2025-04-26 14:34:19', NULL, NULL),
(50, 1, '2025-04-27 05:53:27', NULL, NULL),
(51, 1, '2025-04-27 07:08:37', NULL, NULL),
(52, 1, '2025-04-27 08:06:01', NULL, NULL),
(53, 1, '2025-04-27 10:03:44', NULL, NULL),
(54, 1, '2025-04-27 11:46:28', NULL, NULL),
(55, 1, '2025-04-28 12:52:00', NULL, NULL),
(56, 1, '2025-04-28 12:54:01', NULL, NULL),
(57, 37, '2025-04-28 16:22:24', NULL, NULL),
(58, 37, '2025-04-28 18:05:15', NULL, NULL),
(59, 1, '2025-04-28 18:43:31', NULL, NULL),
(60, 37, '2025-04-28 19:02:59', NULL, NULL),
(61, 37, '2025-04-29 03:13:12', NULL, NULL),
(62, 37, '2025-04-29 10:34:30', NULL, NULL),
(63, 37, '2025-04-29 10:44:23', NULL, NULL),
(64, 36, '2025-04-29 11:02:32', NULL, NULL),
(65, 1, '2025-04-29 11:11:41', NULL, NULL),
(66, 37, '2025-04-29 16:50:34', NULL, NULL),
(67, 40, '2025-04-29 16:51:04', NULL, NULL),
(68, 44, '2025-04-29 17:09:58', NULL, NULL),
(69, 37, '2025-04-30 12:04:54', NULL, NULL),
(70, 37, '2025-05-01 12:48:17', NULL, NULL),
(71, 37, '2025-05-01 16:06:23', NULL, NULL),
(72, 44, '2025-05-02 16:18:23', NULL, NULL),
(73, 37, '2025-05-02 18:18:03', NULL, NULL),
(74, 37, '2025-05-03 06:40:16', NULL, NULL),
(75, 37, '2025-05-03 06:50:18', NULL, NULL),
(76, 37, '2025-05-03 06:58:11', NULL, NULL),
(77, 37, '2025-05-03 07:20:58', NULL, NULL),
(78, 37, '2025-05-03 07:36:54', NULL, NULL),
(79, 37, '2025-05-03 07:47:17', NULL, NULL),
(80, 1, '2025-05-05 16:51:36', NULL, NULL),
(81, 1, '2025-05-06 10:34:41', NULL, NULL),
(82, 1, '2025-05-06 10:47:14', NULL, NULL),
(83, 1, '2025-05-06 11:07:14', NULL, NULL),
(84, 1, '2025-05-06 11:46:15', NULL, NULL),
(85, 1, '2025-05-06 14:41:30', NULL, NULL),
(86, 1, '2025-05-06 17:32:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `address`, `mobile_number`, `email`) VALUES
(1, 'sahana', 'tumkur', '9731461050', 'sahananchinni16@gmail.com'),
(2, 'harshitha', 'tumkur', '9686584962', 'harshitha123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'sahana', '', '$2y$10$BQ3.GRp3ce8r0fMNv3FoKOSNzDoQdgCX1O8F19CbBa.OfYO9OUKWi'),
(36, 'harshitha', 'harshitha123@gmail.com', '$2y$10$6ZI3h7QUju2TM6RNJLujGuxc18mon.aIUIPyFPRNAJfLWp213goa6'),
(37, 'suma ', 'suma123@gmail.com', '$2y$10$e8aO0klGnBw3iLAHa9FIeO9rhopstfAme923/NHqTcxZOeRX/ms2S'),
(39, 'mounika', 'mounikayadav016@gmail.com', '$2y$10$xkyVj2d5qEIOw0SeQRyuRujiJQwWuO5JgLdQsZfaAGidDFkzgqXNy'),
(40, 'mizba', 'mizba@gmail.com', '$2y$10$eBlDnbR0O3kKPZ8xjB3Sv.TfgaBaDyol2UK16S0bIgrxVTy5dXg.G'),
(41, 'sindhu', 'sindhu@gmail.com', '$2y$10$qrT1OsCpPu0cgYT6dem4b.DpvjL04Ct9w8GFWboaC7OmpMXWlMW5y'),
(43, 'ramya ms ', 'ramya98ms@gmail.com', '$2y$10$27Z.fNWiUKAtuRg.aYNxZOk60AcRyZZFXvuxM.hNKzh0TVK4l3O6y'),
(44, 'admin', 'admin123@gmail.com', '$2y$10$oYSRiO/3DJlddaepJM4vsuKUEG/gs.6ZwPHOUO.Dy01EllS7udLua'),
(45, 'sahana', 'sahana@gmail.com', 'sahana@123'),
(46, 'Admin', 'admin2025@gmail.com', 'test@25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
