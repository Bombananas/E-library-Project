-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 10:50 AM
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
-- Database: `e_library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbook`
--

CREATE TABLE `tblbook` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `book_author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `book_source` varchar(255) NOT NULL,
  `book_cover` varchar(255) NOT NULL,
  `book_video` varchar(255) NOT NULL,
  `major_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbllevel`
--

CREATE TABLE `tbllevel` (
  `level_id` int(10) UNSIGNED NOT NULL,
  `level_name` varchar(255) NOT NULL,
  `timetostudy` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbllevel`
--

INSERT INTO `tbllevel` (`level_id`, `level_name`, `timetostudy`, `description`) VALUES
(14, 'C1', 1, 'Level 1 Training'),
(15, 'C2', 1, 'Level 2 Training'),
(16, 'C3', 1, 'Level 3 Training'),
(17, 'បរិញ្ញាបត្រជាន់ខ្ពស់​', 4, 'បរិញ្ញាបត្រជាន់ខ្ពស់​');

-- --------------------------------------------------------

--
-- Table structure for table `tblmajor`
--

CREATE TABLE `tblmajor` (
  `major_id` int(10) UNSIGNED NOT NULL,
  `major_name_kh` varchar(255) NOT NULL,
  `major_name_en` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `level_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmajor`
--

INSERT INTO `tblmajor` (`major_id`, `major_name_kh`, `major_name_en`, `description`, `level_id`) VALUES
(18, 'វិទ្យាសាស្ត្យកុំព្យូទ័', 'Computer Science', 'Class of 2026 second semester', 17),
(19, 'អគ្គីសនី', 'Electrician', 'Class of 2026 second semester', 17);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `role` set('Admin','Teacher') NOT NULL DEFAULT 'Teacher'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `user_name`, `user_password`, `role`) VALUES
(1, 'Admin', '$2y$10$lXXU2cAxOI1fSFNWL5AkIOKqiFXc.k2QBuNv4iTjwX7miUQ0rUCJC', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` set('User','Admin') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`ID`, `UserName`, `Password`, `Role`) VALUES
(19, 'admin', '$2y$10$8khsbxCdikaxkH8Zu/M.OuMbI3LyKzyvB6SJvLZuQg5aM7yr3ESii', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbook`
--
ALTER TABLE `tblbook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `majorID` (`major_id`);

--
-- Indexes for table `tbllevel`
--
ALTER TABLE `tbllevel`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `tblmajor`
--
ALTER TABLE `tblmajor`
  ADD PRIMARY KEY (`major_id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbook`
--
ALTER TABLE `tblbook`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbllevel`
--
ALTER TABLE `tbllevel`
  MODIFY `level_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblmajor`
--
ALTER TABLE `tblmajor`
  MODIFY `major_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbook`
--
ALTER TABLE `tblbook`
  ADD CONSTRAINT `tblbook_ibfk_1` FOREIGN KEY (`major_id`) REFERENCES `tblmajor` (`major_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblmajor`
--
ALTER TABLE `tblmajor`
  ADD CONSTRAINT `tblmajor_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `tbllevel` (`level_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
