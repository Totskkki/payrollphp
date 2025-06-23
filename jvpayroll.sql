-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 11:36 AM
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
-- Database: `jvpayroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `13th_month`
--

CREATE TABLE `13th_month` (
  `yearid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `thirteenth_month_pay` decimal(10,2) NOT NULL,
  `status` enum('Approve','Pending') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `13th_month`
--

INSERT INTO `13th_month` (`yearid`, `employee_id`, `year`, `thirteenth_month_pay`, `status`, `created_at`) VALUES
(1, 1, 2025, 2250.00, 'Pending', '2025-01-08 08:52:19'),
(2, 5, 2025, 5625.00, 'Pending', '2025-01-08 08:52:19'),
(3, 6, 2025, 1125.00, 'Pending', '2025-01-08 08:52:19'),
(4, 7, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(5, 8, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(6, 9, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(7, 10, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(8, 11, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(9, 12, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(10, 13, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(11, 14, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(12, 15, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(13, 2, 2025, 1500.00, 'Pending', '2025-01-08 08:52:19'),
(14, 4, 2025, 750.00, 'Pending', '2025-01-08 08:52:19'),
(15, 16, 2025, 0.00, 'Pending', '2025-01-08 08:52:19'),
(16, 17, 2025, 0.00, 'Pending', '2025-01-08 08:52:19');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressid` int(11) NOT NULL,
  `brgy` varchar(100) NOT NULL,
  `purok` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `empid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressid`, `brgy`, `purok`, `street`, `city`, `province`, `postal_code`, `country`, `empid`, `userid`) VALUES
(1, '', '', 'Blk. 4 Andres Bonifacio St, Poblacion, Koronadal City, South Cotabato', 'fdas', 'SOUTH COTABATO', '9506', 'Philippines', 1, 0),
(2, '', '', 'Blk. 4 Andres Bonifacio St, Poblacion, Koronadal City, South Cotabato', 'fsda', 'fsda', '9506', 'Philippines', 2, 0),
(3, '', '', 'Blk. 4 Andres Bonifacio St, Poblacion, Koronadal City, South Cotabato', 'fsda', 'fsda', '9506', 'Philippines', 3, 0),
(4, '', '', 'ivy', 'ivy', 'ivy', '123213', 'Philippines', 4, 0),
(5, '', '', 'koronadal city , south cotabato', 'koronadal city', 'Davao', '9506', 'Philippines', 5, 0),
(6, '', '', '123', 'koronadal city', 'SOUTH COTABATO', '9506', 'Philippines', 6, 0),
(7, 'Barangay 1', 'Purok 1', '123 Main St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 7, 1),
(8, 'Barangay 2', 'Purok 2', '456 Second St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 8, 1),
(9, 'Barangay 3', 'Purok 3', '789 Third St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 9, 1),
(10, 'Barangay 4', 'Purok 4', '1010 Fourth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 10, 1),
(11, 'Barangay 5', 'Purok 5', '1212 Fifth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 11, 1),
(12, 'Barangay 6', 'Purok 6', '1313 Sixth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 12, 1),
(13, 'Barangay 7', 'Purok 7', '1414 Seventh St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 13, 1),
(14, 'Barangay 8', 'Purok 8', '1515 Eighth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 14, 1),
(15, 'Barangay 9', 'Purok 9', '1616 Ninth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 15, 1),
(16, 'Barangay 10', 'Purok 10', '1717 Tenth St', 'Koronadal', 'South Cotabato', '9506', 'Philippines', 16, 1),
(17, '', '', 'koronadal city , south cotabato', 'koronadal city', 'Davao', '9506', 'Philippines', 17, 0);

-- --------------------------------------------------------

--
-- Table structure for table `allowance`
--

CREATE TABLE `allowance` (
  `allowid` int(11) NOT NULL,
  `allowance` varchar(100) NOT NULL,
  `allowance_type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allowance`
--

INSERT INTO `allowance` (`allowid`, `allowance`, `allowance_type`, `amount`, `description`, `created_on`) VALUES
(1, 'meal', 'weekly', 100.00, '', '2025-01-08 09:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `allowances_employee`
--

CREATE TABLE `allowances_employee` (
  `allempid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `allowid` varchar(50) NOT NULL,
  `allowance_amount` decimal(10,2) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allowances_employee`
--

INSERT INTO `allowances_employee` (`allempid`, `employee_id`, `allowid`, `allowance_amount`, `created_at`, `updated_at`) VALUES
(1, 17, '1', 100.00, '2025-01-08', NULL),
(2, 1, '1', 100.00, '2025-01-08', NULL),
(3, 3, '1', 100.00, '2025-01-08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceid` int(11) NOT NULL,
  `employee_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `num_hr` decimal(10,2) NOT NULL,
  `status` enum('absent','present') NOT NULL DEFAULT 'absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceid`, `employee_no`, `date`, `time_in`, `time_out`, `num_hr`, `status`) VALUES
(1, 'JV-102', '2025-01-02', '07:10:00', '17:00:00', 10.00, 'present'),
(2, 'JV-102', '2025-01-03', '07:10:00', '17:00:00', 10.00, 'present'),
(3, 'JV-102', '2025-01-04', '07:00:00', '17:00:00', 10.00, 'present'),
(4, 'JV-102', '2025-01-05', '07:00:00', '17:00:00', 10.00, 'present'),
(5, 'JV-102', '2025-01-06', '07:00:00', '17:00:00', 10.00, 'present'),
(6, 'JV-102', '2025-01-07', '07:00:00', '17:00:00', 10.00, 'present'),
(7, 'JV-102', '2025-01-08', '07:00:00', '17:00:00', 10.00, 'present'),
(8, 'JV-102', '2025-01-09', '07:00:00', '17:00:00', 10.00, 'present'),
(9, 'JV-102', '2025-01-10', '07:00:00', '17:00:00', 10.00, 'present'),
(10, 'JV-103', '2025-01-02', '07:00:00', '17:00:00', 0.00, 'present'),
(11, 'JV-103', '2025-01-03', '07:00:00', '17:00:00', 0.00, 'present'),
(12, 'JV-103', '2025-01-04', '07:00:00', '17:00:00', 0.00, 'present'),
(13, 'JV-103', '2025-01-05', '07:00:00', '17:00:00', 0.00, 'present'),
(14, 'JV-103', '2025-01-06', '07:00:00', '17:00:00', 0.00, 'present'),
(15, 'JV-103', '2025-01-07', '07:00:00', '17:00:00', 0.00, 'present'),
(16, 'JV-103', '2025-01-08', '07:00:00', '17:00:00', 0.00, 'present'),
(17, 'JV-103', '2025-01-09', '07:00:00', '17:00:00', 0.00, 'present'),
(18, 'JV-103', '2025-01-10', '07:00:00', '17:00:00', 0.00, 'present'),
(19, 'JV-104', '2025-01-02', '07:00:00', '17:00:00', 0.00, 'present'),
(20, 'JV-104', '2025-01-03', '07:00:00', '17:00:00', 0.00, 'present'),
(21, 'JV-104', '2025-01-04', '07:00:00', '17:00:00', 0.00, 'present'),
(22, 'JV-104', '2025-01-05', '07:00:00', '17:00:00', 0.00, 'present'),
(23, 'JV-104', '2025-01-06', '07:00:00', '17:00:00', 0.00, 'present'),
(24, 'JV-104', '2025-01-07', '07:00:00', '17:00:00', 0.00, 'present'),
(25, 'JV-104', '2025-01-08', '07:00:00', '16:00:00', 8.00, 'present'),
(26, 'JV-104', '2025-01-09', '07:00:00', '17:00:00', 0.00, 'present'),
(27, 'JV-104', '2025-01-10', '07:00:00', '17:00:00', 0.00, 'present'),
(28, 'JV-105', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(29, 'JV-105', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(30, 'JV-105', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(31, 'JV-105', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(32, 'JV-105', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(33, 'JV-105', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(34, 'JV-105', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(35, 'JV-105', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(36, 'JV-105', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(37, 'JV-105', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(38, 'JV-106', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(39, 'JV-106', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(40, 'JV-106', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(41, 'JV-106', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(42, 'JV-106', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(43, 'JV-106', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(44, 'JV-106', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(45, 'JV-106', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(46, 'JV-101', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(47, 'JV-101', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(48, 'JV-101', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(49, 'JV-101', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(50, 'JV-101', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(51, 'JV-101', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(52, 'JV-101', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(53, 'JV-101', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(54, 'JV-101', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(55, 'JV-107', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(56, 'JV-107', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(57, 'JV-107', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(58, 'JV-107', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(59, 'JV-107', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(60, 'JV-107', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(61, 'JV-107', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(62, 'JV-107', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(63, 'JV-107', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(64, 'JV-107', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(65, 'JV-109', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(66, 'JV-109', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(67, 'JV-109', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(68, 'JV-109', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(69, 'JV-109', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(70, 'JV-109', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(71, 'JV-109', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(72, 'JV-109', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(73, 'JV-109', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(74, 'JV-109', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(75, 'JV-110', '2025-01-02', '07:00:00', '17:00:00', 0.00, 'present'),
(76, 'JV-110', '2025-01-03', '07:00:00', '17:00:00', 0.00, 'present'),
(77, 'JV-110', '2025-01-04', '07:00:00', '17:00:00', 0.00, 'present'),
(78, 'JV-110', '2025-01-05', '07:00:00', '17:00:00', 0.00, 'present'),
(79, 'JV-110', '2025-01-06', '07:00:00', '17:00:00', 0.00, 'present'),
(80, 'JV-110', '2025-01-07', '07:00:00', '17:00:00', 0.00, 'present'),
(81, 'JV-110', '2025-01-08', '07:00:00', '17:00:00', 0.00, 'present'),
(82, 'JV-110', '2025-01-09', '07:00:00', '17:00:00', 0.00, 'present'),
(83, 'JV-110', '2025-01-10', '07:00:00', '17:00:00', 0.00, 'present'),
(84, 'JV-110', '2025-01-11', '07:00:00', '17:00:00', 0.00, 'present'),
(85, 'JV-111', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(86, 'JV-111', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(87, 'JV-111', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(88, 'JV-111', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(89, 'JV-111', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(90, 'JV-111', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(91, 'JV-111', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(92, 'JV-111', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(93, 'JV-111', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(94, 'JV-111', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(95, 'JV-112', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(96, 'JV-112', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(97, 'JV-112', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(98, 'JV-112', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(99, 'JV-112', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(100, 'JV-112', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(101, 'JV-112', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(102, 'JV-112', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(103, 'JV-112', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(104, 'JV-112', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(105, 'JV-113', '2025-01-02', '07:10:00', '17:00:00', 0.00, 'present'),
(106, 'JV-113', '2025-01-03', '07:00:00', '17:00:00', 0.00, 'present'),
(107, 'JV-113', '2025-01-04', '07:00:00', '17:00:00', 0.00, 'present'),
(108, 'JV-113', '2025-01-05', '07:10:00', '17:00:00', 0.00, 'present'),
(109, 'JV-113', '2025-01-06', '07:15:00', '17:00:00', 0.00, 'present'),
(110, 'JV-113', '2025-01-07', '07:03:00', '17:00:00', 0.00, 'present'),
(111, 'JV-113', '2025-01-08', '07:02:00', '17:00:00', 0.00, 'present'),
(112, 'JV-113', '2025-01-09', '07:01:00', '17:00:00', 0.00, 'present'),
(113, 'JV-113', '2025-01-10', '07:05:00', '17:00:00', 0.00, 'present'),
(114, 'JV-113', '2025-01-11', '07:06:00', '17:00:00', 0.00, 'present'),
(115, 'JV-114', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(116, 'JV-114', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(117, 'JV-114', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(118, 'JV-114', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(119, 'JV-114', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(120, 'JV-114', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(121, 'JV-114', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(122, 'JV-114', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(123, 'JV-114', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(124, 'JV-114', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(125, 'JV-115', '2025-01-02', '03:30:00', '12:00:00', 0.00, 'present'),
(126, 'JV-115', '2025-01-03', '03:30:00', '12:00:00', 0.00, 'present'),
(127, 'JV-115', '2025-01-04', '03:30:00', '12:00:00', 0.00, 'present'),
(128, 'JV-115', '2025-01-05', '03:30:00', '12:00:00', 0.00, 'present'),
(129, 'JV-115', '2025-01-06', '03:30:00', '12:00:00', 0.00, 'present'),
(130, 'JV-115', '2025-01-07', '03:30:00', '12:00:00', 0.00, 'present'),
(131, 'JV-115', '2025-01-08', '03:30:00', '12:00:00', 0.00, 'present'),
(132, 'JV-115', '2025-01-09', '03:30:00', '12:00:00', 0.00, 'present'),
(133, 'JV-115', '2025-01-10', '03:30:00', '12:00:00', 0.00, 'present'),
(134, 'JV-115', '2025-01-11', '03:30:00', '12:00:00', 0.00, 'present'),
(135, 'JV-116', '2025-01-02', '07:10:00', '17:00:00', 0.00, 'present'),
(136, 'JV-116', '2025-01-03', '07:00:00', '17:00:00', 0.00, 'present'),
(137, 'JV-116', '2025-01-04', '07:00:00', '17:00:00', 0.00, 'present'),
(138, 'JV-116', '2025-01-05', '07:10:00', '17:00:00', 0.00, 'present'),
(139, 'JV-116', '2025-01-06', '07:15:00', '17:00:00', 0.00, 'present'),
(140, 'JV-116', '2025-01-07', '07:03:00', '17:00:00', 0.00, 'present'),
(141, 'JV-116', '2025-01-08', '07:02:00', '17:00:00', 0.00, 'present'),
(142, 'JV-116', '2025-01-09', '07:01:00', '17:00:00', 0.00, 'present'),
(143, 'JV-116', '2025-01-10', '07:05:00', '17:00:00', 0.00, 'present'),
(144, 'JV-116', '2025-01-11', '07:06:00', '17:00:00', 0.00, 'present'),
(145, 'JV-102', '2025-01-12', '07:00:00', '17:00:00', 0.00, 'present'),
(146, 'JV-102', '2025-01-13', '07:00:00', '17:00:00', 0.00, 'present'),
(147, 'JV-102', '2025-01-14', '07:00:00', '17:00:00', 0.00, 'present'),
(148, 'JV-102', '2025-01-15', '07:00:00', '17:00:00', 0.00, 'present'),
(149, 'JV-102', '2025-01-16', '07:00:00', '17:00:00', 0.00, 'present'),
(150, 'JV-102', '2025-01-17', '07:00:00', '17:00:00', 0.00, 'present'),
(151, 'JV-102', '2025-01-18', '07:00:00', '17:00:00', 0.00, 'present'),
(152, 'JV-102', '2025-01-19', '07:00:00', '17:00:00', 0.00, 'present'),
(153, 'JV-102', '2025-01-20', '07:00:00', '17:00:00', 0.00, 'present'),
(154, 'JV-102', '2025-01-21', '07:00:00', '17:00:00', 0.00, 'present'),
(155, 'JV-102', '2025-01-22', '07:00:00', '17:00:00', 0.00, 'present'),
(156, 'JV-102', '2025-01-23', '07:00:00', '17:00:00', 0.00, 'present'),
(157, 'JV-102', '2025-01-24', '07:00:00', '17:00:00', 0.00, 'present'),
(158, 'JV-102', '2025-01-25', '07:00:00', '17:00:00', 0.00, 'present'),
(159, 'JV-102', '2025-01-26', '07:00:00', '17:00:00', 0.00, 'present'),
(160, 'JV-102', '2025-01-27', '07:00:00', '17:00:00', 10.00, 'present'),
(161, 'JV-117', '2025-01-08', '07:00:00', '17:00:00', 9.00, 'present');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `description`, `timestamp`) VALUES
(1, 1, 'approve', 'Approved payroll ID: 2', '2024-12-22 11:34:44'),
(2, 1, 'approve', 'Approved payroll ID: 3', '2024-12-23 01:12:56'),
(3, 1, 'approve', 'Approved payroll ID: 3', '2024-12-23 01:15:24'),
(4, 1, 'approve', 'Approved payroll ID: 1', '2024-12-26 07:30:28'),
(6, 1, 'approve', 'Approved payroll ID: 4', '2024-12-27 01:21:28'),
(7, 1, 'approve', 'Approved payroll ID: 3', '2024-12-27 02:08:16'),
(8, 1, 'approve', 'Approved payroll ID: 2', '2025-01-06 10:53:49'),
(10, 1, 'approve', 'Approved payroll ID: 13', '2025-01-08 02:12:19'),
(11, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 02:13:56'),
(12, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:19:31'),
(13, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:21:09'),
(14, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:24:40'),
(15, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:27:44'),
(16, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:39:32'),
(17, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 04:49:49'),
(18, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 05:18:56'),
(19, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 05:31:07'),
(20, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 05:32:33'),
(21, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 05:45:34'),
(22, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 05:56:31'),
(23, 1, 'approve', 'Approved payroll ID: 4', '2025-01-08 06:12:18'),
(24, 1, 'approve', 'Approved payroll ID: 7', '2025-01-08 06:16:05'),
(25, 1, 'approve', 'Approved payroll ID: 2', '2025-01-08 06:17:30'),
(26, 1, 'approve', 'Approved payroll ID: 4', '2025-01-08 06:29:27'),
(27, 1, 'approve', 'Approved payroll ID: 2', '2025-01-08 06:31:59'),
(28, 1, 'approve', 'Approved payroll ID: 2', '2025-01-08 06:59:42'),
(29, 1, 'approve', 'Approved payroll ID: 2', '2025-01-08 07:04:24'),
(30, 1, 'approve', 'Approved payroll ID: 11', '2025-01-08 07:13:56'),
(31, 1, 'approve', 'Approved payroll ID: 7', '2025-01-08 07:15:43'),
(32, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 07:16:21'),
(33, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 09:08:08'),
(34, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 09:14:56'),
(35, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 09:52:13'),
(36, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 09:59:06'),
(37, 1, 'approve', 'Approved payroll ID: 3', '2025-01-08 10:03:44'),
(38, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 10:28:39'),
(39, 1, 'approve', 'Approved payroll ID: 1', '2025-01-08 10:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `biometrics`
--

CREATE TABLE `biometrics` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `am_in` time DEFAULT NULL,
  `am_out` time DEFAULT NULL,
  `pm_in` time DEFAULT NULL,
  `pm_out` time DEFAULT NULL,
  `bio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bonus_incentives`
--

CREATE TABLE `bonus_incentives` (
  `bonusid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `bonus_amount` decimal(10,2) NOT NULL,
  `bonus_type` varchar(50) NOT NULL,
  `bonus_period` varchar(50) NOT NULL,
  `bonus_description` text NOT NULL,
  `status` enum('pending','Paid') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bonus_incentives`
--

INSERT INTO `bonus_incentives` (`bonusid`, `employee_id`, `bonus_amount`, `bonus_type`, `bonus_period`, `bonus_description`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 120.00, 'Performance', 'January 2025', '', 'Paid', '2025-01-08 07:38:11', '2025-01-08 16:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `cashadvance`
--

CREATE TABLE `cashadvance` (
  `cashid` int(11) NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `advance_amount` decimal(10,2) DEFAULT NULL,
  `advance_date` date DEFAULT NULL,
  `repayment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `repayment_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_units`
--

CREATE TABLE `daily_units` (
  `unitid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `unit_type` varchar(100) NOT NULL,
  `units_completed` int(11) NOT NULL,
  `date_completed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_units`
--

INSERT INTO `daily_units` (`unitid`, `employee_id`, `unit_type`, `units_completed`, `date_completed`) VALUES
(1, 3, 'pack', 600, '2025-01-02'),
(2, 3, 'pack', 550, '2025-01-03'),
(3, 3, 'pack', 700, '2025-01-04');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `dedID` int(11) NOT NULL,
  `deduction` varchar(100) NOT NULL,
  `deduction_type` varchar(100) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deductions`
--

INSERT INTO `deductions` (`dedID`, `deduction`, `deduction_type`, `amount`, `description`, `date_added`) VALUES
(1, ' Health Insurance', 'Weekly', 200.00, '', '2025-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `deductions_employees`
--

CREATE TABLE `deductions_employees` (
  `deductionid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `deducid` varchar(50) NOT NULL,
  `deduc_amount` decimal(10,2) NOT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deductions_employees`
--

INSERT INTO `deductions_employees` (`deductionid`, `employee_id`, `deducid`, `deduc_amount`, `created_on`, `updated_at`) VALUES
(1, 1, '1', 200.00, '2025-01-08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `depid` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`depid`, `department`, `description`, `created_on`) VALUES
(15, 'PAKYAWAN', 'PAKYAWANPAKYAWAN', '2024-12-18 04:23:33'),
(16, 'FISH PORT', 'FISH PORT', '2024-12-18 04:23:45'),
(17, 'SHOP', 'SHOP', '2024-12-18 04:23:53');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` bigint(11) NOT NULL,
  `employee_no` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_extension` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `face_path` varchar(255) NOT NULL,
  `is_archived` int(11) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `employee_no`, `first_name`, `middle_name`, `last_name`, `name_extension`, `birthdate`, `gender`, `contact_number`, `email`, `password`, `photo`, `userid`, `face_path`, `is_archived`, `created_on`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'JV-101', 'Marjun', 'V', 'Malintad', '', '1989-02-07', 'Male', '12321321', 'Marjun@gmail.com', '$2y$10$1YnDPCp5BO7hqY5ChiVIKuorrZlt1IjlkCd4Ywpyo32Gkb14Wd88O', NULL, 1, '[]', 0, '2024-12-22 08:55:48', NULL, NULL),
(2, 'JV-102', 'Soraida', '', 'Ambalgan', '', '2019-02-05', 'Female', '1234', 'soraida@gmail.com', '$2y$10$xdrCvDTze0qhEqjWOGaqxuiPTdEEMfy65FfUTZjnX5fMkH/rdNdzG', 'logo.webp', 1, '[]', 0, '2024-12-22 08:56:28', NULL, NULL),
(3, 'JV-103', 'Ronald', 'V', 'Roldan', '', '1988-12-27', 'Male', '123212', 'ronald@gmail.com', '$2y$10$dGQt6pHKNATjdLymc9KP3edPJqQaey1pKRtp6JbjXHTJWzMezUyne', NULL, 1, '[]', 0, '2024-12-22 11:58:44', NULL, NULL),
(4, 'JV-104', 'Ivy', '', 'Ivy', '', '2020-05-12', 'Female', '123213', 'mivy9779@gmail.com', '$2y$10$UzCqK5t1.henaNG4XcldbO5H5rwqonxfP53bx/2wrDLNUIXJCL9Hm', 'mangosteen_header.png', 1, '[]', 0, '2024-12-23 07:05:14', NULL, NULL),
(5, 'JV-105', 'Irlan', '', 'Magsangyaw', '', '2024-12-02', 'Male', '09677819501', 'irlan@gmail.com', '$2y$10$MdYRk9XnITNYm1DXXNTH4OyDCA5r6hi0G2kZLE/d7ROk2KeMU3YIu', NULL, 1, '[]', 0, '2024-12-27 02:16:10', NULL, NULL),
(6, 'JV-106', 'roland', '', 'magsangyaw', '', '1999-06-01', 'Male', '2131232222', 'roland@gmail', '$2y$10$EMEgCwwGFGPEC54zXa/6.eBTiXy0xCFAer4ZGx7V7lqI6HdPqmoOq', NULL, 1, '[]', 0, '2025-01-06 07:22:52', NULL, NULL),
(7, 'JV-107', 'Harry', 'S', 'Susamin', '', '1990-05-15', 'Male', '09171234567', 'johndoe@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(8, 'JV-108', 'Janes', 'M', 'Canes', '', '1992-08-20', 'Female', '09171234568', 'Canes@example.com', '$2y$10$oswcZsoroyyaoFf8ZQNVOuzS9iyKvVrylhWueIlfFkNR51u5xc096', '', 1, '[]', 0, '2025-01-05 16:00:00', NULL, NULL),
(9, 'JV-109', 'James', 'M', 'Taylor', '', '1985-02-10', 'Male', '09171234569', 'jamestaylor@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(10, 'JV-110', 'Mary', 'A', 'Wilson', '', '1993-11-25', 'Female', '09171234570', 'marywilson@example.com', '$2y$10$P0pCMD0kDYYuU9CwesMH.OiqK9KTMoDuvqLneYXktdjaJ2JahprRW', '', 1, '[]', 0, '2025-01-05 16:00:00', NULL, NULL),
(11, 'JV-111', 'Robert', 'L', 'Brown', '', '1987-03-05', 'Male', '09171234571', 'robertbrown@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(12, 'JV-112', 'Emily', 'G', 'Martinez', '', '1990-09-12', 'Female', '09171234572', 'emilymartinez@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(13, 'JV-113', 'Michael', 'D', 'Harris', '', '1989-12-03', 'Male', '09171234573', 'michaelharris@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(14, 'JV-114', 'Sophia', 'E', 'Clark', '', '1991-04-18', 'Female', '09171234574', 'sophiaclark@example.com', 'password123', '', 1, '', 0, '2025-01-05 16:00:00', NULL, NULL),
(15, 'JV-115', 'William', 'S', 'Lewis', '', '1986-07-30', 'Male', '09171234575', 'williamlewis@example.com', '$2y$10$4u86JY.la8l8jeMxvsgG/ekOWeWEK7TA..FD5e1nt6EhmWmL/d9Xe', '', 1, '[]', 0, '2025-01-05 16:00:00', NULL, NULL),
(16, 'JV-116', 'Olivia', 'H', 'Walker', '', '1995-01-14', 'Female', '09171234576', 'oliviawalker@example.com', '$2y$10$brJZDBFFc02KWUmUl9R7IeDk/oHd7CIF5XYXBO2YQBF.qGxmzwFzm', '', 1, '[]', 0, '2025-01-05 16:00:00', NULL, NULL),
(17, 'JV-117', 'Yr', '', 'Yr', '', '2024-10-06', 'Female', '09677819501', 'trr26@gmail.com', '$2y$10$6BkPX6Q9uM2uS04UQVOlFO6v8Fz4isPhaBROdCOp.qgVGQrpIwUQS', NULL, 1, '[]', 0, '2025-01-08 03:28:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE `employee_details` (
  `employment_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `positionid` int(11) NOT NULL,
  `departmentid` int(11) DEFAULT NULL,
  `scheduleid` int(11) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `employment_type` enum('Full-time','Part-time','Contract','Temporary') NOT NULL,
  `status` enum('Active','Inactive','Terminated','Resign') DEFAULT 'Active',
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_details`
--

INSERT INTO `employee_details` (`employment_id`, `employee_id`, `positionid`, `departmentid`, `scheduleid`, `hire_date`, `employment_type`, `status`, `created_on`) VALUES
(1, 1, 23, 16, 1, '2024-11-04', 'Part-time', 'Active', '2024-12-22 08:55:48'),
(2, 2, 24, 17, 2, '2024-11-04', 'Contract', 'Active', '2024-12-22 08:56:28'),
(3, 3, 22, 15, 2, '2024-11-04', 'Contract', 'Active', '2024-12-22 11:58:44'),
(4, 4, 24, 17, 2, '2024-11-11', 'Contract', 'Active', '2024-12-23 07:05:14'),
(5, 5, 23, 16, 1, '2024-08-04', 'Part-time', 'Active', '2024-12-27 02:16:10'),
(6, 6, 23, 16, 1, '2024-12-05', 'Part-time', 'Active', '2025-01-06 07:22:52'),
(7, 7, 23, 16, 1, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(8, 8, 23, 16, 2, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(9, 9, 23, 16, 1, '2025-01-06', 'Part-time', 'Active', '2025-01-05 16:00:00'),
(10, 10, 23, 16, 2, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(11, 11, 23, 16, 1, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(12, 12, 23, 16, 1, '2025-01-06', 'Contract', 'Active', '2025-01-05 16:00:00'),
(13, 13, 23, 16, 2, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(14, 14, 23, 16, 1, '2025-01-06', 'Part-time', 'Active', '2025-01-05 16:00:00'),
(15, 15, 23, 16, 1, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(16, 16, 24, 17, 2, '2025-01-06', 'Full-time', 'Active', '2025-01-05 16:00:00'),
(17, 17, 24, 17, 2, '2024-12-30', 'Full-time', 'Active', '2025-01-08 03:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `mandatory_benefits`
--

CREATE TABLE `mandatory_benefits` (
  `mandateid` int(11) NOT NULL,
  `benefit_type` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'active',
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mandatory_benefits`
--

INSERT INTO `mandatory_benefits` (`mandateid`, `benefit_type`, `amount`, `status`, `created_at`) VALUES
(1, 'SSS', 15000, 'active', '2025-01-01'),
(2, 'PAG-IBIG', 300, 'active', '2025-01-01'),
(3, 'PHILHEALTH', 500, 'active', '2025-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `overtimeid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `hours` double NOT NULL,
  `rate` double NOT NULL,
  `date_overtime` date NOT NULL,
  `status` tinyint(100) NOT NULL COMMENT '0=pending,1=rejected,2=approved',
  `total_compensation` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`overtimeid`, `employee_id`, `hours`, `rate`, `date_overtime`, `status`, `total_compensation`) VALUES
(1, 1, 2, 56.25, '2025-01-07', 2, 112.50);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payrollid` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `gross_salary` decimal(10,2) NOT NULL,
  `tot_deductions` decimal(10,2) NOT NULL,
  `deductions` decimal(5,2) NOT NULL,
  `mandatory_deductions` text NOT NULL,
  `late` decimal(5,2) NOT NULL,
  `undertime` float NOT NULL,
  `present` int(30) NOT NULL,
  `overtime` double(5,2) DEFAULT NULL,
  `allowances` double NOT NULL,
  `cash_advance` double NOT NULL,
  `bonus` double NOT NULL,
  `net_salary` decimal(10,2) NOT NULL,
  `status` enum('pending','approve','paid') DEFAULT 'pending',
  `pay_period_id` int(11) DEFAULT NULL,
  `payslip_no` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`payrollid`, `employee_id`, `gross_salary`, `tot_deductions`, `deductions`, `mandatory_deductions`, `late`, `undertime`, `present`, `overtime`, `allowances`, `cash_advance`, `bonus`, `net_salary`, `status`, `pay_period_id`, `payslip_no`, `created_at`) VALUES
(1, 1, 3482.50, 300.00, 200.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":342}', 0.00, 0, 7, 112.50, 100, 0, 120, 3140.50, 'paid', 1, 'PS-94628', '2025-01-08'),
(2, 2, 2100.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 12.50, 0, 7, NULL, 0, 0, 0, 1945.50, 'pending', 1, '', '2025-01-08'),
(3, 3, 3800.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 100, 0, 0, 3658.00, 'pending', 1, '', '2025-01-08'),
(4, 4, 2100.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 37.5, 7, NULL, 0, 0, 0, 1920.50, 'paid', 1, '', '2025-01-08'),
(5, 5, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(6, 6, 2700.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 6, NULL, 0, 0, 0, 2558.00, 'pending', 1, '', '2025-01-08'),
(7, 7, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(8, 9, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(9, 10, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(10, 11, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(11, 12, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'paid', 1, '', '2025-01-08'),
(12, 13, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 37.50, 0, 7, NULL, 0, 0, 0, 2970.50, 'paid', 1, '', '2025-01-08'),
(13, 14, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'pending', 1, '', '2025-01-08'),
(14, 15, 3150.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 7, NULL, 0, 0, 0, 3008.00, 'pending', 1, '', '2025-01-08'),
(15, 16, 2100.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 25.00, 0, 7, NULL, 0, 0, 0, 1933.00, 'paid', 1, '', '2025-01-08'),
(16, 17, 400.00, 100.00, 0.00, '{\"0\":{\"benefit_type\":\"PAG-IBIG\",\"amount\":\"300\",\"employee_share\":100,\"employer_share\":100},\"1\":{\"benefit_type\":\"PHILHEALTH\",\"amount\":\"500\",\"employee_share\":100,\"employer_share\":100},\"SSS_Employee\":42,\"SSS_Employer\":88.67,\"SSS_Total\":130.67000000000002,\"Total_Mandatory\":142}', 0.00, 0, 1, NULL, 100, 0, 0, 258.00, 'pending', 1, '', '2025-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_notifications`
--

CREATE TABLE `payroll_notifications` (
  `notification_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_notifications`
--

INSERT INTO `payroll_notifications` (`notification_id`, `employee_id`, `payroll_id`, `message`, `status`, `created_at`) VALUES
(1, 1, 1, 'Your payroll for the period 2025-01-02 to 2025-01-08 has been approved. Net Salary: ₱3,140.50.', 'unread', '2025-01-08 09:52:16'),
(2, 1, 1, 'Your payroll for the period 2025-01-02 to 2025-01-08 has been approved. Net Salary: ₱3,140.50.', 'unread', '2025-01-08 09:59:09'),
(3, 3, 3, 'Your payroll for the period 2025-01-02 to 2025-01-08 has been approved. Net Salary: ₱3,658.00.', 'unread', '2025-01-08 10:03:48'),
(4, 1, 1, 'Your payroll for the period 2025-01-02 to 2025-01-08 has been approved. Net Salary: ₱3,140.50.', 'unread', '2025-01-08 10:28:42'),
(5, 1, 1, 'Your payroll for the period 2025-01-02 to 2025-01-08 has been approved. Net Salary: ₱3,140.50.', 'unread', '2025-01-08 10:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `pay_periods`
--

CREATE TABLE `pay_periods` (
  `payid` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` enum('open','closed','locked') DEFAULT 'open',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`payid`, `ref_no`, `year`, `from_date`, `to_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PP-2025-01', 2025, '2025-01-02', '2025-01-08', 'open', '2025-01-08 17:22:49', '2025-01-08 17:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `positionid` int(11) NOT NULL,
  `departmentid` int(11) NOT NULL,
  `position` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rate_per_hour` double(10,2) DEFAULT NULL,
  `pakyawan_rate` decimal(10,2) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`positionid`, `departmentid`, `position`, `rate_per_hour`, `pakyawan_rate`, `created_on`) VALUES
(22, 15, 'PAKYAWAN', 0.00, 2.00, '2024-12-18 04:24:19'),
(23, 16, 'LABOR', 450.00, 0.00, '2024-12-18 04:24:35'),
(24, 17, 'CASHIER', 300.00, 0.00, '2024-12-18 04:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `scheduleid` int(11) NOT NULL,
  `scheduled_start` time NOT NULL,
  `scheduled_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`scheduleid`, `scheduled_start`, `scheduled_end`) VALUES
(1, '03:30:00', '12:00:00'),
(2, '07:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `date_login` datetime NOT NULL DEFAULT current_timestamp(),
  `date_logout` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `QR_code` varchar(255) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `user_role` int(11) NOT NULL DEFAULT 0 COMMENT '1=admin,0=employee,2=staff',
  `face_path` longblob NOT NULL,
  `biometric_data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `fname`, `mname`, `lname`, `contact`, `QR_code`, `photo`, `created_on`, `status`, `username`, `email`, `password`, `user_role`, `face_path`, `biometric_data`) VALUES
(1, 'carlos', '', 'yolo', '', 'QR12345', '1656551981avatar.png', '2024-12-10', 'active', 'admin', 'admin@example.com', '$2a$12$Pa7SBiQIFhpvI1qO5Tu8fehgiLfLBKEyzWTsAznRYa.MR.YfUlFoi', 1, 0x706174682f746f2f666163652e6a7067, 0x62696f6d65747269635f646174615f76616c7565);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `13th_month`
--
ALTER TABLE `13th_month`
  ADD PRIMARY KEY (`yearid`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressid`);

--
-- Indexes for table `allowance`
--
ALTER TABLE `allowance`
  ADD PRIMARY KEY (`allowid`);

--
-- Indexes for table `allowances_employee`
--
ALTER TABLE `allowances_employee`
  ADD PRIMARY KEY (`allempid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceid`),
  ADD KEY `employee_id` (`employee_no`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `bonus_incentives`
--
ALTER TABLE `bonus_incentives`
  ADD PRIMARY KEY (`bonusid`);

--
-- Indexes for table `cashadvance`
--
ALTER TABLE `cashadvance`
  ADD PRIMARY KEY (`cashid`);

--
-- Indexes for table `daily_units`
--
ALTER TABLE `daily_units`
  ADD PRIMARY KEY (`unitid`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`dedID`);

--
-- Indexes for table `deductions_employees`
--
ALTER TABLE `deductions_employees`
  ADD PRIMARY KEY (`deductionid`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`depid`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD PRIMARY KEY (`employment_id`);

--
-- Indexes for table `mandatory_benefits`
--
ALTER TABLE `mandatory_benefits`
  ADD PRIMARY KEY (`mandateid`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`overtimeid`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payrollid`),
  ADD KEY `pay_period_id` (`pay_period_id`);

--
-- Indexes for table `payroll_notifications`
--
ALTER TABLE `payroll_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `pay_periods`
--
ALTER TABLE `pay_periods`
  ADD PRIMARY KEY (`payid`),
  ADD KEY `idx_year` (`year`),
  ADD KEY `idx_from_date` (`from_date`),
  ADD KEY `idx_to_date` (`to_date`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`positionid`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`scheduleid`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `13th_month`
--
ALTER TABLE `13th_month`
  MODIFY `yearid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `allowance`
--
ALTER TABLE `allowance`
  MODIFY `allowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allowances_employee`
--
ALTER TABLE `allowances_employee`
  MODIFY `allempid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `bonus_incentives`
--
ALTER TABLE `bonus_incentives`
  MODIFY `bonusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cashadvance`
--
ALTER TABLE `cashadvance`
  MODIFY `cashid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_units`
--
ALTER TABLE `daily_units`
  MODIFY `unitid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `dedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deductions_employees`
--
ALTER TABLE `deductions_employees`
  MODIFY `deductionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `depid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `employment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `mandatory_benefits`
--
ALTER TABLE `mandatory_benefits`
  MODIFY `mandateid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `overtimeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payrollid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payroll_notifications`
--
ALTER TABLE `payroll_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pay_periods`
--
ALTER TABLE `pay_periods`
  MODIFY `payid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `positionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
