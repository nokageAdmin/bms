-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2024 at 09:38 AM
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
-- Database: `brgydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `message`, `date`) VALUES
(129, 'si jv ay hinabol ng asoa\r\n\r\ntulungan nyo1211\n\n(edited)', '2024-11-02 05:26:32'),
(130, 'aaaaaaaaaaaaaaaaaassd\r\n12\r\nSADasdasdasaaasdaasd\n\n(edited)', '2024-10-29 00:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `name`, `position`) VALUES
(1, 'JV Quisto', 'Barangay Captain'),
(2, 'Roldan A. Rosita', 'Konsehal'),
(3, 'Richard C. Canos', 'Konsehal'),
(4, 'Lexter D. Maquinto', 'Konsehal'),
(5, 'Rosendo T. Babadilla', 'Konsehal'),
(6, 'Rodolfo U. Manalo Jr.', 'Konsehal'),
(7, 'Jaime C. Laqui', 'Konsehal'),
(8, 'Rechel R. Cireulas', 'Konsehal'),
(9, 'Apriljane J. Siscar', 'Secretary'),
(10, 'Josephine Quisto', 'Treasurer');

-- --------------------------------------------------------

--
-- Table structure for table `blotter_report`
--

CREATE TABLE `blotter_report` (
  `blotter_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `report_content` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','assigned','finished','canceled') DEFAULT 'pending',
  `meeting_date` date DEFAULT NULL,
  `meeting_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blotter_report`
--

INSERT INTO `blotter_report` (`blotter_id`, `user_id`, `report_content`, `reason`, `status`, `meeting_date`, `meeting_time`, `created_at`) VALUES
(95, 56, 'asdsadsadsad', '', 'canceled', NULL, NULL, '2024-10-27 09:20:00'),
(96, 57, 'sadsad', 'asdsa', 'finished', NULL, NULL, '2024-10-27 09:20:12'),
(97, 56, 'sinapak ako ni quisto', 'kasi trip ko lang', 'assigned', '2024-10-15', '18:34:00', '2024-10-27 09:51:29'),
(98, 57, 'sinapak ako ni nils', 'trip ko rin', 'canceled', NULL, NULL, '2024-10-27 09:51:57'),
(99, 61, 'Baog sa gold si lem', 'supot', 'assigned', '2024-10-16', '19:02:00', '2024-10-27 10:18:58'),
(100, 57, 'asd', 'asd', 'finished', NULL, NULL, '2024-10-29 00:40:10'),
(101, 56, 'aa', 'aaaaa', 'pending', NULL, NULL, '2024-10-29 00:52:43'),
(102, 69, 'Boring ng mga lalaki', 'Bored lang', 'canceled', NULL, NULL, '2024-10-31 06:35:07'),
(103, 56, 'asdasd', 'asdsad', 'assigned', '2024-11-11', '07:48:00', '2024-11-01 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `household_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `age` int(11) NOT NULL,
  `is_registered_voter` tinyint(1) DEFAULT 0,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `sex` enum('male','female','other') NOT NULL,
  `account_type` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `last_name`, `first_name`, `middle_name`, `contact_number`, `email`, `password`, `position`, `sex`, `account_type`) VALUES
(31, 'ngaa', 'asdsad', '', '21323a', 'staff@gmail.com', '$2y$10$Ksf2fwjK/SJmVLfoxTvWHejy82E2UV/6sEQG/9gDFcSAtPpysC5qy', '23', 'male', 2),
(50, 'padalaa', 'staff', 'ewan', '23232323', 'staff2@gmail.com', '$2y$10$gRreoei7N71Y2391GrPt5.N6hV8DLqI5VESWaIu93tWzkN76qzfH.', 'staff', 'male', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `purok` int(10) NOT NULL,
  `contact` int(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `suffix`, `purok`, `contact`, `email`, `password`, `account_type`, `created_at`, `reset_token`, `token_expires`, `login_attempts`, `last_attempt`) VALUES
(38, 'sd', '', '', '', 0, 0, 'admin@gmail.com', '$2y$10$VbTqKRbivcPx5KnaYhUa2eMjYB4yxzJZBeO.oBN18KF1BB0NqNTsS', 1, '2024-10-26 18:15:14', NULL, NULL, 5, '2024-10-31 06:25:21'),
(56, 'Nilsasadaaa', 'asdsa', 'Martija', '', 2, 654216441, 'nils@gmail.com', '$2y$10$Isf5iedVS/E2CRcTVnaS.eTBWlm7Z.SkJ15eYx4tw3UNueEWzG7du', 3, '2024-10-27 02:19:26', NULL, NULL, 0, NULL),
(57, 'JV', 'sdsd', 'Quisto', 'hr', 3, 123214, 'jv@gmail.com', '$2y$10$ZmwRKTCYzuk8NooCdxTwUeH9L.hJGcdhk4blSWG2eK9YUcguJZtfW', 3, '2024-10-27 02:19:50', NULL, NULL, 0, NULL),
(61, 'Ejayed', '', 'Dimayuga', 'Jr.', 1, 1244012131, 'ej@gmail.com', '$2y$10$8jLCBnTrsJ8uiyBIRJ2RpeAZ0zTWAZJfSQ733Z5c42d3stbhF8tBC', 3, '2024-10-27 03:18:25', NULL, NULL, 0, NULL),
(69, 'Kirss', '', 'Roldan', '', 4, 9494646, 'kirsroldan@gmai.com', '$2y$10$KFxZb.VsPoDGVNm9C7NFPeK3RI8eSDDUuUwBtxuFbmbkCb7dH1CwS', 3, '2024-10-30 23:32:53', NULL, NULL, 0, NULL),
(73, 'DJ', 'Morfi', 'Aquino', '', 1, 654216441, 'aquinodeej9@gmail.com', '$2y$10$0z96/d.bOx4iTt2l96DXEOQFkj75XZ5P1AOtcR6OpMX8/tWb5o4NG', 3, '2024-10-31 04:43:15', 'd02f1bde5074d10e9e9291e78f3177803420d6ec7ce721fdd7f70f7083a11b289930e8c481e89352886b3b623fa987ee5e17', NULL, 0, NULL),
(74, 'asdsadas', 'dsadsad', 'asdsad', 'asdsad', 2, 955264132, 'ss@gmail.com', '$2y$10$9rsmzHEX8DnC9suRDngGRuPQP2W5uDzxJoumqdN0c2redQA2v44Sy', 3, '2024-11-01 12:37:52', NULL, NULL, 0, NULL),
(75, 'Lebron', '', 'James', 'Jr.', 3, 654216441, 'lj@gmail.com', '$2y$10$Txbe0dHcj2RF/66Ej1ixAeIA50JCSvmbnKxjz3emDPRVbc8.sovqC', 3, '2024-11-01 17:24:59', NULL, NULL, 0, NULL),
(76, 'Drexel', '', 'Cueto', '', 1, 654216441, 'dk@gmail.com', '$2y$10$LWuLhTcSkZpb64d3jCWODem..OZOS/DqRGsjIfFY10lpFA4PujjRu', 3, '2024-11-02 03:05:59', NULL, NULL, 0, NULL),
(77, 'Carlo', 'Angelo', 'Arellano', 'Jr.', 5, 2147483647, 'carlo@gmail.com', '$2y$10$rQEMqret6pkvfxmQnQgO0.CR65cUhxJkVlmInKsIAsIW.IbUoRghm', 3, '2024-11-02 16:39:02', NULL, NULL, 0, NULL),
(78, 'Ninongg', '', 'Ry', '', 24, 232, 'nr@gmail.com', '$2y$10$/nyi1WMYl66NdykPlh2MWeWwzPlLGqv6BTBoilfbuTKBv1c2hsWEW', 3, '2024-11-02 21:23:26', NULL, NULL, 0, NULL),
(79, 'kevin', 'raul', 'cambao', '', 0, 2147483647, 'krc@gmail.com', '$2y$10$AXMrLW.e9AKW5KMKkqAGUe1irRk3B03INZJ/n/m2eIMZFk9BPoc0m', 3, '2024-11-03 04:32:04', NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blotter_report`
--
ALTER TABLE `blotter_report`
  ADD PRIMARY KEY (`blotter_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blotter_report`
--
ALTER TABLE `blotter_report`
  MODIFY `blotter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blotter_report`
--
ALTER TABLE `blotter_report`
  ADD CONSTRAINT `blotter_report_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
