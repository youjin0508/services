-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 01:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_services_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `image`, `date_posted`) VALUES
(34, 'No Title', '', '1.png', '2025-04-04 14:24:44'),
(35, 'No Title', '', '2.jpg', '2025-04-04 14:24:52'),
(36, 'No Title', '', '3.png', '2025-04-04 14:25:00'),
(37, 'fjsdjfk', '', 'Screenshot 2025-02-03 174320.png', '2025-04-08 03:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('pending','approved','completed') DEFAULT 'pending',
  `reason` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `user_id`, `appointment_date`, `status`, `reason`, `created_at`, `updated_at`, `admin_message`) VALUES
(46, 'GAB2022-00258', 'Guidance01', '2025-04-09 17:25:00', 'completed', 'academic problem', '2025-04-04 09:24:31', '2025-08-17 04:40:43', ''),
(50, 'GAB2022-1111', 'Guidance01', '2025-08-27 00:45:00', 'approved', 'WALA LANG PO MAGAWA BAKA PWEDE KAUSAPIN KAYO!\\', '2025-08-17 04:38:11', '2025-08-17 04:40:28', 'RESCHED NALANG'),
(51, 'GAB2022-1111', 'Guidance01', '2025-08-01 15:00:00', 'approved', 'testing', '2025-08-17 04:57:08', '2025-08-17 04:57:08', NULL),
(52, 'GAB2022-1111', 'Guidance01', '2025-07-25 16:02:00', 'approved', 'asdfkjahsdjk fhasdjkfha skjhsadjkf hkjsa', '2025-08-17 04:59:29', '2025-08-17 04:59:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_blocks`
--

CREATE TABLE `calendar_blocks` (
  `id` int(11) NOT NULL,
  `counselor_id` varchar(64) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `type` enum('available','blocked') NOT NULL DEFAULT 'available',
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counseling_reports`
--

CREATE TABLE `counseling_reports` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `report_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dormitory_payments`
--

CREATE TABLE `dormitory_payments` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `period` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `receipt_number` varchar(100) NOT NULL,
  `date_paid` date NOT NULL,
  `receipt_file` varchar(255) NOT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `verified_by` varchar(50) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dorm_agreements`
--

CREATE TABLE `dorm_agreements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `effective_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dorm_agreements`
--

INSERT INTO `dorm_agreements` (`id`, `title`, `content`, `effective_date`, `created_at`, `is_active`) VALUES
(1, 'jskfjsdkfj', 'sdfsdajkfhasjkd fh\r\nsfsdfsjdfs\r\nfsdfjsfs\r\nfsdfsdjfsdf\r\nsdfsdjfkhsdf\r\nsdfsdjfhk', '2001-12-25', '2025-08-15 23:16:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dorm_agreement_acceptance`
--

CREATE TABLE `dorm_agreement_acceptance` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `agreement_id` int(11) NOT NULL,
  `accepted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dorm_agreement_acceptance`
--

INSERT INTO `dorm_agreement_acceptance` (`id`, `user_id`, `agreement_id`, `accepted_at`) VALUES
(3, 'GAB2022-1111', 1, '2025-08-16 09:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `grievances`
--

CREATE TABLE `grievances` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolution_date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grievances`
--

INSERT INTO `grievances` (`id`, `user_id`, `category`, `title`, `description`, `attachment`, `status`, `submission_date`, `resolution_date`, `updated_at`) VALUES
(1, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 06:35:08', '2025-04-03 07:26:13', '2025-08-17 01:52:49'),
(2, 'admin01', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 06:59:25', '2025-04-03 07:22:45', '2025-08-17 01:52:49'),
(3, 'admin01', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 07:05:25', '2025-04-03 07:22:42', '2025-08-17 01:52:49'),
(4, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 07:10:44', '2025-04-03 07:22:28', '2025-08-17 01:52:49'),
(5, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 07:18:29', '2025-04-03 07:22:43', '2025-08-17 01:52:49'),
(6, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'resolved', '2025-04-03 07:22:30', '2025-04-03 07:22:44', '2025-08-17 01:52:49'),
(7, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'pending', '2025-04-03 07:22:48', NULL, '2025-08-17 01:52:49'),
(8, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'pending', '2025-04-03 07:28:09', NULL, '2025-08-17 01:52:49'),
(9, 'GAB2022-00258', NULL, 'sdf', 'sdfsfsdf', NULL, 'pending', '2025-04-03 07:28:48', NULL, '2025-08-17 01:52:49'),
(10, 'Gab2022-00259', NULL, 'Binagsak ni Sir', 'Di daw nakapag comply pero may valid reason!', NULL, 'pending', '2025-04-03 08:00:04', NULL, '2025-08-17 01:52:49'),
(11, 'GAB2022-00000', NULL, 'Security', 'kdsfkljdsflkslfjsjflds lkdsjfklj dsflkdsjflk ', NULL, 'pending', '2025-04-08 03:16:08', NULL, '2025-08-17 01:52:49'),
(12, 'Guidance01', NULL, 'sdfsdfsdf', 'sdfdsfsdfsdfskdjfh sajkdfhsdajkfhsadjk hfjksadhfjksda', NULL, 'pending', '2025-08-17 01:42:41', NULL, '2025-08-17 01:52:49'),
(13, 'Guidance01', NULL, 'sdfsdfsdf', 'sdfdsfsdfsdfskdjfh sajkdfhsdajkfhsadjk hfjksadhfjksda', NULL, 'pending', '2025-08-17 02:04:19', NULL, '2025-08-17 02:04:19'),
(14, 'Guidance01', NULL, 'sdfsdfsdf', 'sdfdsfsdfsdfskdjfh sajkdfhsdajkfhsadjk hfjksadhfjksda', NULL, 'pending', '2025-08-17 02:04:21', NULL, '2025-08-17 02:04:21'),
(15, 'Guidance01', NULL, 'sdfsdfsdf', 'sdfdsfsdfsdfskdjfh sajkdfhsdajkfhsadjk hfjksadhfjksda', NULL, 'pending', '2025-08-17 02:04:35', NULL, '2025-08-17 02:04:35'),
(16, 'Guidance01', NULL, 'sdfsdfsdf', 'sdfdsfsdfsdfskdjfh sajkdfhsdajkfhsadjk hfjksadhfjksda', NULL, 'pending', '2025-08-17 02:04:37', NULL, '2025-08-17 02:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `title` varchar(120) NOT NULL,
  `body` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `receipt_path` varchar(255) NOT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_by` varchar(50) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `file_hash` varchar(64) DEFAULT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `date_paid` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `room_id`, `amount`, `receipt_path`, `status`, `submitted_at`, `verified_by`, `verified_at`, `remarks`, `file_hash`, `receipt_number`, `date_paid`) VALUES
(1, 'GAB2022-1111', 15, 550.00, 'GAB2022-1111_20250816_043013.jpg', 'Verified', '2025-08-16 02:30:13', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'GAB2022-1111', 15, 550.00, 'GAB2022-1111_20250816_044525.png', 'Rejected', '2025-08-16 02:45:25', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'GAB2022-1111', 15, 23123.00, 'GAB2022-1111_20250816_050502.png', 'Verified', '2025-08-16 03:05:02', 'Dormitory01', '2025-08-17 06:44:30', '', NULL, NULL, NULL),
(4, 'GAB2022-1111', 15, 345435.00, 'GAB2022-1111_20250817_005714.png', 'Rejected', '2025-08-16 22:57:14', NULL, NULL, 'SDF', NULL, NULL, NULL),
(5, 'GAB2022-1111', 15, 55.00, 'GAB2022-1111_20250817_025119.png', 'Verified', '2025-08-17 00:51:19', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'GAB2022-1111', 15, 550.00, 'GAB2022-1111_20250817_025424.png', 'Rejected', '2025-08-17 00:54:24', NULL, NULL, 'wrong receipt', NULL, NULL, NULL),
(7, 'GAB2022-1111', 15, 26256.00, 'GAB2022-1111_20250818_051242.png', 'Rejected', '2025-08-18 03:12:42', NULL, NULL, 'iba yan', NULL, NULL, NULL),
(8, 'GAB2022-1111', 15, 100.00, 'GAB2022-1111_20250818_124441.png', 'Pending', '2025-08-18 10:44:41', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_audit_logs`
--

CREATE TABLE `payment_audit_logs` (
  `id` int(11) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `old_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) DEFAULT NULL,
  `admin_id` varchar(50) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_audit_logs`
--

INSERT INTO `payment_audit_logs` (`id`, `payment_type`, `payment_id`, `action`, `old_status`, `new_status`, `admin_id`, `remarks`, `created_at`) VALUES
(1, 'Dorm', 4, 'reject', 'Pending', 'Rejected', 'Dormitory01', 'SDF', '2025-08-17 08:46:29'),
(2, 'Dorm', 5, 'verify', 'Pending', 'Verified', 'Dormitory01', NULL, '2025-08-17 08:52:14'),
(3, 'Dorm', 6, 'reject', 'Pending', 'Rejected', 'Dormitory01', 'wrong receipt', '2025-08-17 08:54:58'),
(4, 'Dorm', 7, 'reject', 'Pending', 'Rejected', 'Dormitory01', 'iba yan', '2025-08-18 11:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `request_type` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_beds` int(11) NOT NULL,
  `occupied_beds` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `amenities` text NOT NULL,
  `price_per_month` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `total_beds`, `occupied_beds`, `image`, `amenities`, `price_per_month`) VALUES
(15, 'Room 1', 7, 4, 'uploads/images.jpg', 'Closet, Private Bathroom, Shared Bathroom, Laundry Facilities', 500),
(16, 'jsafhkshf', 5, 0, 'uploads/Screenshot 2024-11-01 112113.png', 'Wi-Fi, Closet, Private Bathroom', 2500),
(17, 'dsfds', 5, 0, 'uploads/istockphoto-528888367-612x612.jpg', 'Wi-Fi, Air Conditioning', 5556);

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT 'Academic',
  `description` text NOT NULL,
  `eligibility` text NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `requirements` text DEFAULT NULL,
  `documents_required` text DEFAULT NULL,
  `max_applicants` int(11) DEFAULT 0,
  `current_applicants` int(11) DEFAULT 0,
  `created_by` varchar(50) DEFAULT NULL,
  `deadline` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`id`, `name`, `type`, `description`, `eligibility`, `amount`, `requirements`, `documents_required`, `max_applicants`, `current_applicants`, `created_by`, `deadline`, `created_at`, `updated_at`, `status`) VALUES
(11, 'Abot kamay na pangarap', 'Academic', 'sdfd', 'dfs', 0.00, '', '[]', 0, 0, NULL, '2025-09-17', '2025-04-06 06:19:27', '2025-08-18 10:52:51', 'inactive'),
(16, 'abot kamay na pangarap', 'Other', 'kahit sino', 'kahit sino', 2000.00, 'pogi', '[\"Birth Certificate\",\"Report Card\",\"Good Moral\"]', 12, 0, 'Scholarship123', '2025-09-17', '2025-08-18 10:42:10', '2025-08-18 10:52:51', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_applications`
--

CREATE TABLE `scholarship_applications` (
  `id` int(11) NOT NULL,
  `scholarship_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `approval_date` datetime DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `documents_submitted` longtext DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `reviewed_by` varchar(50) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarship_applications`
--

INSERT INTO `scholarship_applications` (`id`, `scholarship_id`, `user_id`, `application_date`, `status`, `approval_date`, `gpa`, `course`, `year_level`, `documents_submitted`, `review_notes`, `rejection_reason`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(10, 11, 'GAB2022-00271', '2025-04-06 08:02:17', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 08:45:26', '2025-08-18 09:18:34'),
(11, 11, 'GAB2022-00000', '2025-04-08 03:15:02', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 08:45:26', '2025-08-18 09:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_audit_log`
--

CREATE TABLE `scholarship_audit_log` (
  `id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `user_id` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_documents`
--

CREATE TABLE `scholarship_documents` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_notifications`
--

CREATE TABLE `scholarship_notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_reports`
--

CREATE TABLE `scholarship_reports` (
  `id` int(11) NOT NULL,
  `report_type` varchar(100) NOT NULL,
  `report_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`report_data`)),
  `generated_by` varchar(50) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_room_applications`
--

CREATE TABLE `student_room_applications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `applied_at` datetime DEFAULT current_timestamp(),
  `price_per_month` float DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_room_applications`
--

INSERT INTO `student_room_applications` (`id`, `user_id`, `room_id`, `status`, `applied_at`, `price_per_month`, `approved_at`) VALUES
(51, 'Dormitory01', 15, 'Rejected', '2025-04-06 13:48:34', 500, NULL),
(52, 'GAB2022-00258', 15, 'Approved', '2025-04-06 13:49:15', 500, NULL),
(53, 'GAB2022-00000', 15, 'Approved', '2025-04-08 11:14:12', 500, NULL),
(54, 'GAB2022-1111', 16, 'Rejected', '2025-08-16 05:20:33', 2500, NULL),
(55, 'GAB2022-1111', 15, 'Rejected', '2025-08-16 05:20:53', 500, NULL),
(56, 'GAB2022-1111', 16, 'Rejected', '2025-08-16 07:33:50', 2500, NULL),
(57, 'GAB2022-1111', 15, 'Rejected', '2025-08-16 07:55:37', 500, NULL),
(58, 'GAB2022-1111', 15, 'Rejected', '2025-08-16 08:21:17', 500, NULL),
(59, 'GAB2022-1111', 16, 'Rejected', '2025-08-16 08:34:33', 2500, NULL),
(60, 'GAB2022-1111', 16, 'Rejected', '2025-08-16 08:38:04', 2500, NULL),
(61, 'GAB2022-1111', 15, 'Rejected', '2025-08-16 08:41:21', 500, NULL),
(62, 'GAB2022-1111', 15, 'Approved', '2025-08-16 09:05:46', 500, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_room_assignments`
--

CREATE TABLE `student_room_assignments` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `status` enum('Active','Inactive','MovedOut') NOT NULL DEFAULT 'Active',
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ended_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `biological_sex` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `current_address` varchar(255) NOT NULL,
  `permanent_address` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `mother_work` varchar(50) NOT NULL,
  `mother_contact` varchar(20) NOT NULL,
  `father_name` varchar(50) NOT NULL,
  `father_work` varchar(50) NOT NULL,
  `father_contact` varchar(20) NOT NULL,
  `siblings_count` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `date_registered` datetime DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `year` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `family_income` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `birth_date`, `nationality`, `religion`, `biological_sex`, `email`, `phone`, `current_address`, `permanent_address`, `role`, `password_hash`, `mother_name`, `mother_work`, `mother_contact`, `father_name`, `father_work`, `father_contact`, `siblings_count`, `unit`, `last_login`, `date_registered`, `profile_picture`, `status`, `year`, `section`, `course`, `department`, `gpa`, `family_income`) VALUES
('admin01', 'John', NULL, 'Doe', '1990-01-01', 'Filipino', 'Christian', 'Male', 'admin@example.com', '1234567890', '123 Admin St', '456 Permanent Address St', 'Power Admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Doe', 'Teacher', '0987654321', 'John Doe', 'Engineer', '1230987654', 3, NULL, NULL, '2025-03-27 22:23:04', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('ADM_67e683402a324', 'sdfnsdknf', 'nsmd,fn', 'lnfsdkfnskdn', '2025-03-04', '', '', '', 'dkdsm2@gmail.com', '0978456314', 'dfskdjfskj', 'jbsdjfbskfnk', 'Student', '$2y$10$3Vea4r2KxXa2AARJHuRRCuY8xzyHyKSosbzFlhJiAhzOf/gED17i2', 'sdfnksn', '', '', 'nskjdfns', '', '', 0, 'Dormitory', NULL, '2025-03-28 19:08:48', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('ADM_67e68827e81d7', 'sdjfnk', 'njsdnkfj', 'nkjsdnfksdnfk', '2025-03-11', '', '', '', 'sdjfnjksdfnk2@gmail.com', '0798546321', 'sdfkjsdfkjsd', 'kljnjksdfieuhk', 'Student', '$2y$10$tfS2jakzj5y0HvRTHO0CH.dnt9YumArtP81ZUknSS8EA0naFQIS66', 'sdfnskdnfk', '', '', 'jnksdjfnksdn', '', '', 0, 'Dormitory', NULL, '2025-03-28 19:29:43', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('Dorm123', 'sdfnsdkfjn', 'nsdkfjsn', 'kjnfkdsjnf', '2025-03-18', '', '', '', 'dskfnskdnf2@gmail.com', '09756412358', 'sdjfklj', 'kjsdkjfnkjd', 'Student', '$2y$10$CH.jZQU.BNAvthuwPEVxQ.EXoasIiFFCzWl/kUwYFV8Jw62gUG9kq', 'sdfn', '', '', 'klsldkfn', '', '', 0, 'Dormitory', NULL, '2025-03-28 19:33:31', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('Dormitory01', 'Juan', 'Last', 'Song', '2025-03-19', 'filipino', 'wesleyan', 'Male', 'juan02@gmail.com', '09756315487', 'Homeless', 'Homeless', 'Dormitory Admin', '$2y$10$ZnsVQ29PizgLji8nqjICM.grJZlqXRdYAQMmzBfjXxnPnO5GPQ14u', 'Laika', '', '', 'Ralp', '', '', 0, 'Guidance', NULL, '2025-03-31 19:29:28', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('GAB2022-00000', 'jahn', 'sjdfjkkjshdf', 'jhdsfjk', '2002-06-05', 'filipino', 'Catholic', 'Male', 'sjfkjssjdfhk@gmail.com', '09785456325', 'sdjfsk', 'jkhsddkfj', 'Student', '$2y$10$MNVOOMGzx9pDObQUL3.LJeUpFtzf4yu/3O27893.yryghehtDQmha', 'sdfkjh', 'jksdhfjk', 'jkdhsdkfjh', 'jkhsdkjfhajk', 'hkjdshfjk', 'khsdkfhsk', 8, NULL, NULL, '2025-04-08 11:13:14', NULL, 'Inactive', 3, 'A', 'BSIT', NULL, NULL, NULL),
('GAB2022-00258', 'Jessie ', 'De Guzman', 'Javier', '2001-02-05', 'Filipino', 'Catholic', 'Male', 'javierjessie02@gmail.com', '09701114903', 'South Poblacion Gabaldon Nueva Ecija', 'Bacong, Umiray, Dingalan, Aurora', 'Student', '$2y$10$mnCIIltXnw3vsZ5OW7NPo.hhLve6VkjsGOIjiWu6wFVhpWo4mjPde', 'Malou Javier', 'Housewife', '09784563241', 'Arnie Javier', 'Driver', '09854763254', 4, NULL, NULL, '2025-03-29 17:37:23', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('Gab2022-00259', 'Lavizares ', 'Domingo', 'Frayres III', '2004-05-17', 'Filipino', 'Mistica', 'Male', 'lavizaresf@gmail.com', '09128325757', 'Cuyapa, Gabaldon. Nueva ecija', 'Cuyapa, Gabaldon Nueva Ecija', 'Student', '$2y$10$Vz8Ppzpv6xMk4Oq8bX7k2eH7YL11rNmSiSdV9gRRm.sHReqLUTGzC', 'Mercedes', 'OFW', '09123456789', 'Lavizares', 'N/A', '09234567890', 2, NULL, NULL, '2025-04-03 15:50:55', NULL, 'Inactive', 3, 'B', 'BIST', NULL, NULL, NULL),
('GAB2022-00271', 'Brando', 'Mendoza', 'Verganos', '2003-08-14', 'Filipino', 'Wesleyan', 'Male', 'verganosbrando555@gmail.com', '09303562427', 'Ligaya', 'Ligaya', 'Student', '$2y$10$UVfICg7VsmBCL/m0VXs00e81pqtTpz6Pr/Kd7vBvzm5shRcPMuUYq', 'Dolly Mendoza', 'Housewife', '09756482456', 'Benjamin Verganos', 'Contractor', '09785463254', 3, NULL, NULL, '2025-04-02 10:23:14', NULL, 'Inactive', 3, 'B', 'BIST', NULL, NULL, NULL),
('GAB2022-00291', 'SHANE', 'G', 'HGD', '2004-06-10', 'filipino', 'INC', 'Female', 'flororitashane@gmail.com', '09066832584', 'ibona', 'ibona', 'Student', '$2y$10$wCTquK/f4V.623lZhbyBc.ZC1kAHl31jFkKGq/0qnhApcroWCGE0q', 'sofia', 'Housewife', '09658746215', 'romeo', 'farmers', '09765432578', 5, NULL, NULL, '2025-04-03 15:35:15', NULL, 'Inactive', 3, 'B', 'BIST', NULL, NULL, NULL),
('GAB2022-00685', '', 'Noma', '', '0000-00-00', '', '', 'Female', 'arnykayeadobe@gmail.com', '09687969271', '', '', 'Student', '$2y$10$NCmNOSWTeCp.XLtazF7su.ZbDV9QVTZMHgK4io4gvzAHMP8yQL9n2', 'Cindy N. Adobe', 'Housewife', '09384528909', 'efren M. Adobe', 'Driver', 'NA', 3, NULL, NULL, '2025-03-27 15:31:52', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('GAB2022-1111', 'Gary ', 'Sabandal ', 'Ruiz', '1999-02-08', 'Filipino', 'Catholic', 'Male', 'Garyruiz0111@gmail.com', '09784562351', 'Bulacan City', 'Bulacan City', 'Student', '$2y$10$/nF21Os95YdrTIqUdvc6m.JLe4Gfr8KCgAcZQccV3W4NFVCW31lzW', 'Linda Sabandal', 'Housewife ', '0913545698', 'Robert Ruiz ', 'IT developer', '09786452134', 3, NULL, NULL, '2025-08-16 05:19:48', NULL, 'Inactive', 2, 'a', 'HM', NULL, NULL, NULL),
('GAB2022-12345', 'Jeremy', 'Noma', 'javier', '1999-01-03', 'filipino', 'Catholic', 'Male', 'jeremy@gmail.com', '09458463254', 'bacong', 'bacong', 'Student', '$2y$10$1gMxcu.i2kncVd.fIxNRi.aMdGhVA37rvZoPV1ZOq9Eq4solTctQi', 'lala', 'na', '09784563214', 'gats', '09456325447', 'Driver', 3, NULL, NULL, '2025-07-21 19:33:54', NULL, 'Inactive', 3, 'B', 'Educ', NULL, NULL, NULL),
('Guidance01', 'Mila', 'Laso', 'Lambo', '2025-05-08', '', '', '', 'Mila023@gmail.com', '09756482384', 'Umiray', 'Umiray', 'Guidance Admin', '$2y$10$dX.X47cJ9oz.UyzR62eBwOf7PFhoTzkWD3WnMqRocm20HFeWKL1M6', 'Kimmy', '', '', 'Lando', '', '', 0, 'Guidance Admin', NULL, '2025-04-01 11:37:50', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('Registrar01', 'Rarnar', 'Larn', 'Gard', '2025-04-17', '', '', '', 'ragnar033@gmail.com', '09756482364', 'Cuyapa', 'Cuyapa, Gabaldon Nueva Ecija', 'Registrar Admin', '$2y$10$y7Dqj2mq234NMYSOV.UvPe/bbRAbCbun8w3U/iVzDGbOwYBWiMibu', 'Linda', '', '', 'Sandy', '', '', 0, 'Registrar Admin', NULL, '2025-04-01 11:35:26', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL),
('Scholarship01', 'Scholarship', '', 'Administrator', '1990-01-01', 'Filipino', 'Christian', 'Male', 'scholarship@neust.edu.ph', '09123456789', 'NEUST Gabaldon Campus', 'NEUST Gabaldon Campus', 'Scholarship Admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Mother', 'N/A', 'N/A', 'Admin Father', 'N/A', 'N/A', 0, 'Scholarship Office', NULL, '2025-08-18 06:50:58', NULL, 'Active', NULL, NULL, 'Scholarship Management', 'Student Services', NULL, NULL),
('Scholarship123', 'LUNA', 'HEMENEZ', 'GARLANDO', '1998-02-03', '', '', '', 'lunagarlando04@gmail.com', '09756431524', 'Quezon City', 'Quezon City', 'Scholarship Admin', '$2y$10$vQQnFzqKMTeOw62ysm4uR.0h3HxjhXrcN8uGh71lLIjDLaKn1D/Im', 'Lany', '', '', 'Arman', '', '', 0, 'Scholarship Admin', NULL, '2025-08-18 10:29:09', NULL, 'Inactive', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_appt_counselor_time` (`user_id`,`appointment_date`),
  ADD KEY `idx_appt_student_time` (`student_id`,`appointment_date`);

--
-- Indexes for table `calendar_blocks`
--
ALTER TABLE `calendar_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_block_time` (`start_datetime`,`end_datetime`),
  ADD KEY `idx_block_counselor` (`counselor_id`);

--
-- Indexes for table `counseling_reports`
--
ALTER TABLE `counseling_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `dormitory_payments`
--
ALTER TABLE `dormitory_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `receipt_number` (`receipt_number`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `dormitory_payments_verified_fk` (`verified_by`);

--
-- Indexes for table `dorm_agreements`
--
ALTER TABLE `dorm_agreements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dorm_agreement_acceptance`
--
ALTER TABLE `dorm_agreement_acceptance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_agreement` (`user_id`,`agreement_id`),
  ADD KEY `agreement_id` (`agreement_id`);

--
-- Indexes for table `grievances`
--
ALTER TABLE `grievances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_user` (`user_id`,`is_read`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `status` (`status`),
  ADD KEY `submitted_at` (`submitted_at`),
  ADD KEY `idx_verified_by` (`verified_by`),
  ADD KEY `idx_payments_status_date` (`status`,`submitted_at`),
  ADD KEY `idx_payments_file_hash` (`file_hash`),
  ADD KEY `idx_payments_receipt_number` (`receipt_number`);

--
-- Indexes for table `payment_audit_logs`
--
ALTER TABLE `payment_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payment_id` (`payment_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_scholarship_status` (`status`),
  ADD KEY `idx_scholarship_deadline` (`deadline`),
  ADD KEY `idx_scholarship_type` (`type`),
  ADD KEY `fk_scholarships_created_by_users` (`created_by`);

--
-- Indexes for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_application_status` (`status`),
  ADD KEY `idx_application_date` (`application_date`),
  ADD KEY `idx_apps_scholarship` (`scholarship_id`);

--
-- Indexes for table `scholarship_audit_log`
--
ALTER TABLE `scholarship_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `scholarship_documents`
--
ALTER TABLE `scholarship_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_document_type` (`document_type`);

--
-- Indexes for table `scholarship_notifications`
--
ALTER TABLE `scholarship_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `scholarship_reports`
--
ALTER TABLE `scholarship_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_type` (`report_type`),
  ADD KEY `idx_generated_by` (`generated_by`);

--
-- Indexes for table `student_room_applications`
--
ALTER TABLE `student_room_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `student_room_assignments`
--
ALTER TABLE `student_room_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `calendar_blocks`
--
ALTER TABLE `calendar_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counseling_reports`
--
ALTER TABLE `counseling_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dormitory_payments`
--
ALTER TABLE `dormitory_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dorm_agreements`
--
ALTER TABLE `dorm_agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dorm_agreement_acceptance`
--
ALTER TABLE `dorm_agreement_acceptance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grievances`
--
ALTER TABLE `grievances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_audit_logs`
--
ALTER TABLE `payment_audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `scholarship_audit_log`
--
ALTER TABLE `scholarship_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholarship_documents`
--
ALTER TABLE `scholarship_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholarship_notifications`
--
ALTER TABLE `scholarship_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholarship_reports`
--
ALTER TABLE `scholarship_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_room_applications`
--
ALTER TABLE `student_room_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `student_room_assignments`
--
ALTER TABLE `student_room_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `counseling_reports`
--
ALTER TABLE `counseling_reports`
  ADD CONSTRAINT `counseling_reports_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dormitory_payments`
--
ALTER TABLE `dormitory_payments`
  ADD CONSTRAINT `dormitory_payments_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dormitory_payments_verified_fk` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `dorm_agreement_acceptance`
--
ALTER TABLE `dorm_agreement_acceptance`
  ADD CONSTRAINT `dorm_agreement_acceptance_agreement_fk` FOREIGN KEY (`agreement_id`) REFERENCES `dorm_agreements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dorm_agreement_acceptance_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grievances`
--
ALTER TABLE `grievances`
  ADD CONSTRAINT `grievances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_room_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_student_fk` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_verified_by_fk` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD CONSTRAINT `fk_scholarships_created_by_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `scholarship_applications`
--
ALTER TABLE `scholarship_applications`
  ADD CONSTRAINT `scholarship_applications_ibfk_1` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships` (`id`),
  ADD CONSTRAINT `scholarship_applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `scholarship_documents`
--
ALTER TABLE `scholarship_documents`
  ADD CONSTRAINT `scholarship_documents_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `scholarship_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_room_applications`
--
ALTER TABLE `student_room_applications`
  ADD CONSTRAINT `student_room_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_room_applications_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_room_assignments`
--
ALTER TABLE `student_room_assignments`
  ADD CONSTRAINT `student_room_assignments_room_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_room_assignments_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
