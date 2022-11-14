-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2022 at 10:09 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shassic`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `password_code` mediumint(50) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessor_id` int(11) NOT NULL,
  `assessor_name` varchar(200) NOT NULL,
  `assessee_id` int(11) NOT NULL,
  `assessee_name` varchar(200) NOT NULL,
  `project_name` varchar (200) NOT NULL, 
  `project_date` date NOT NULL,
  `project_location` varchar(200) DEFAULT NULL,
  `project_picture` varchar(100) DEFAULT NULL,
  `assessment_progress` int(11) NOT NULL, 
  `calculation_id` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT current_timestamp(),
  `updation_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `workplace_inspection_section`
--

CREATE TABLE `workplace_inspection_section` (
  `id` int(11) NOT NULL,
  `item_no` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `workplace_inspection_checklist`
--

CREATE TABLE `workplace_inspection_checklist` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `checklist` varchar(200) NOT NULL,
  `c_status` int(1) NOT NULL,
  `nc_status` int(1) NOT NULL,
  `na_status` int(1) NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `document_check_section`
--

CREATE TABLE `document_check_section` (
  `id` int(11) NOT NULL,
  `item_no` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table structure for table `document_check_checklist`
--

CREATE TABLE `document_check_checklist` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `checklist` varchar(200) NOT NULL,
  `c_status` int(1) NOT NULL,
  `nc_status` int(1) NOT NULL,
  `na_status` int(1) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `assessment` (`assessee_id`, `assessee_name`,`project_name`, `project_date`, `project_location`, `project_picture`) VALUES
('1', 'Nurul Aqilah', 'shassic', '2022-10-17', 'Sungai Buloh', 'image1.jpg');
--
-- Indexes for dumped tables

--
ALTER TABLE `workplace_inspection_section`
  ADD PRIMARY KEY (`id`);
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessee_id`);

--
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workplace_inspection_section`
--
ALTER TABLE `workplace_inspection_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--

--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessee_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
