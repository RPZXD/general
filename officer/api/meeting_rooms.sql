-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 11:14 AM
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
-- Database: `phichaia_general`
--

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms`
--

CREATE TABLE `meeting_rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `equipment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `meeting_rooms`
--

INSERT INTO `meeting_rooms` (`id`, `room_name`, `capacity`, `equipment`, `created_at`, `updated_at`) VALUES
(2, 'หอประชุมพิชัยดาบหัก', 600, 'ไมค์, เครื่องเสียง, โปรเจ็คเตอร์, โน๊ตบุ๊ค', '2025-03-29 07:28:13', '2025-03-29 08:14:33'),
(4, 'หอประชุมภักดิ์กมล', 600, 'ไมค์, เครื่องเสียง, โปรเจ็คเตอร์, โน๊ตบุ๊ค', '2025-03-29 07:51:46', '2025-03-29 07:51:46'),
(5, 'ห้องโสตทัศนศึกษา', 150, 'ไมค์, เครื่องเสียง, โปรเจ็คเตอร์, โน๊ตบุ๊ค', '2025-03-29 07:52:14', '2025-03-29 08:14:05'),
(6, 'ห้องพิชยนุสรณ์', 40, 'ไมค์, เครื่องเสียง, โปรเจ็คเตอร์, โน๊ตบุ๊ค', '2025-03-29 07:53:29', '2025-03-29 07:53:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
