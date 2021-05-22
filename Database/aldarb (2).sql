-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2021 at 09:36 AM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aldarb`
--

-- --------------------------------------------------------

--
-- Table structure for table `catch_receipt`
--

CREATE TABLE `catch_receipt` (
  `id` int(11) NOT NULL,
  `by_user` int(11) NOT NULL,
  `from_father` int(11) NOT NULL,
  `to_student` int(11) NOT NULL,
  `for_cource` int(11) NOT NULL,
  `mony` double NOT NULL,
  `date_insert` datetime NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catch_receipt`
--

INSERT INTO `catch_receipt` (`id`, `by_user`, `from_father`, `to_student`, `for_cource`, `mony`, `date_insert`, `notes`) VALUES
(1, 8, 1, 6, 2, 500, '2020-11-19 13:07:03', '-'),
(2, 8, 1, 6, 2, 150, '2020-11-19 13:19:16', 'kyikmiumi'),
(3, 8, 1, 6, 5, 123, '2020-11-19 13:19:29', 'jk'),
(4, 8, 1, 6, 2, 100, '2020-11-19 13:22:25', '-'),
(5, 8, 1, 6, 5, 50, '2020-11-19 13:24:19', NULL),
(6, 8, 1, 6, 6, 50, '2020-11-19 13:42:03', 'دفعة من الحساب '),
(7, 8, 1, 7, 4, 100, '2020-11-19 13:57:47', 'دفعة على الحساب '),
(8, 8, 1, 6, 5, 500, '2021-02-05 21:50:13', NULL),
(9, 8, 1, 6, 7, 250, '2021-02-18 20:32:08', NULL),
(10, 8, -1, 56, -1, 300, '2021-02-27 20:53:52', 'gyityuiyui'),
(11, 8, -1, 56, -1, 350, '2021-02-27 20:59:47', '2152156'),
(12, 8, -1, 56, -1, 100, '2021-02-27 21:18:54', 'jijk'),
(13, 8, -1, 56, -1, 200, '2021-02-27 21:22:47', '10'),
(14, 8, -1, 56, -1, 500, '2021-02-27 21:23:20', '10'),
(15, 8, -1, 56, -1, 200, '2021-02-27 21:24:37', '10\n'),
(16, 8, -1, 58, -1, 500, '2021-02-27 21:30:33', '52612'),
(17, 8, -1, 54, -1, 550, '2021-02-27 22:43:12', 'تسديد باقي الحساب'),
(18, 8, -1, 6, -1, 4077, '2021-02-27 22:43:44', 'قق'),
(19, 8, -1, 57, -1, 350, '2021-03-01 14:03:26', ']]]'),
(20, 8, -1, 61, -1, 350, '2021-04-04 13:28:44', '500'),
(21, 8, -1, 62, -1, 100, '2021-05-22 10:52:04', 'دفعة');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`) VALUES
(1, 'الصف الاول'),
(2, 'الصف الثاني'),
(3, 'الصف الثالث'),
(4, 'الصف الرابع'),
(5, 'الصف الخامس'),
(6, 'الصف السادس'),
(7, 'الصف السابع'),
(8, 'الصف الثامن'),
(9, 'الصف التاسع'),
(10, 'الصف العاشر'),
(11, 'الحادي عشر'),
(12, 'التوجيهي');

-- --------------------------------------------------------

--
-- Table structure for table `cource_program`
--

CREATE TABLE `cource_program` (
  `id` int(11) NOT NULL,
  `cource_id` int(11) NOT NULL,
  `p_day` text NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `room` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cource_program`
--

INSERT INTO `cource_program` (`id`, `cource_id`, `p_day`, `from_time`, `to_time`, `room`) VALUES
(1, 4, 'الاحد', '05:00:00', '06:00:00', 1),
(2, 4, 'الاثنين', '16:00:00', '17:00:00', 1),
(3, 4, 'الخميس', '14:00:00', '15:00:00', 1),
(6, 1, 'الاحد', '12:00:00', '13:00:00', 1),
(7, 1, 'السبت', '12:59:00', '13:59:00', 1),
(8, 3, 'السبت', '13:00:00', '14:00:00', 1),
(9, 6, 'الاحد', '12:30:00', '13:30:00', 1),
(10, 6, 'الثلاثاء', '12:30:00', '14:00:00', 1),
(11, 7, 'السبت', '10:00:00', '11:00:00', 1),
(12, 7, 'الاحد', '10:00:00', '11:00:00', 1),
(13, 7, 'الثلاثاء', '10:00:00', '11:00:00', 1),
(15, 4, 'الاربعاء', '17:00:00', '18:00:00', 2),
(16, 2, 'الخميس', '16:00:00', '17:00:00', 1),
(17, 8, 'الخميس', '10:00:00', '00:00:00', 2),
(18, 9, 'السبت', '20:56:00', '20:56:00', 1),
(19, 9, 'الاحد', '00:00:00', '01:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cource_students`
--

CREATE TABLE `cource_students` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `cost` double NOT NULL,
  `insert_date` date NOT NULL,
  `reg_end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cource_students`
--

INSERT INTO `cource_students` (`id`, `c_id`, `stu_id`, `cost`, `insert_date`, `reg_end_date`) VALUES
(4, 4, 7, 350, '2020-11-08', '0000-00-00'),
(5, 6, 6, 350, '2020-11-08', '0000-00-00'),
(6, 7, 6, 450, '2020-11-13', '0000-00-00'),
(7, 8, 6, 5000, '2020-11-19', '0000-00-00'),
(8, 6, 10, 200, '2021-02-09', '0000-00-00'),
(9, 2, 51, 250, '2021-02-18', '0000-00-00'),
(10, 9, 51, 250, '2021-02-18', '0000-00-00'),
(11, 2, 52, 250, '2021-02-19', '0000-00-00'),
(12, 9, 52, 250, '2021-02-19', '0000-00-00'),
(26, 2, 54, 300, '2021-02-19', '0000-00-00'),
(27, 9, 54, 250, '2021-02-19', '0000-00-00'),
(29, 9, 53, 250, '2021-02-19', '0000-00-00'),
(30, 2, 53, 250, '2021-02-19', '2021-03-19'),
(31, 2, 55, 250, '2021-02-19', '2021-03-19'),
(32, 2, 56, 300, '2021-02-27', '2021-03-27'),
(33, 9, 56, 250, '2021-02-27', '2021-03-27'),
(34, 2, 57, 300, '2021-02-27', '2021-03-27'),
(35, 9, 57, 250, '2021-02-27', '2021-03-27'),
(36, 2, 58, 300, '2021-02-27', '2021-03-27'),
(37, 9, 58, 250, '2021-02-27', '2021-03-27'),
(38, 2, 59, 300, '2021-02-27', '2021-03-27'),
(39, 9, 59, 250, '2021-02-27', '2021-03-27'),
(40, 8, 57, 500, '2021-03-01', '0000-00-00'),
(49, 8, 61, 150, '2021-04-04', '0000-00-00'),
(52, 2, 61, 300, '2021-04-06', '2021-05-06'),
(53, 9, 61, 250, '2021-04-06', '2021-05-06'),
(55, 9, 62, 500, '2021-05-22', '2021-06-22');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `c_id` int(11) NOT NULL,
  `c_name` text NOT NULL,
  `c_type` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `c_hours` int(11) NOT NULL,
  `c_teatcher` int(11) NOT NULL,
  `add_by` int(11) DEFAULT NULL,
  `c_status` int(11) NOT NULL,
  `insert_date` date NOT NULL,
  `c_cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`c_id`, `c_name`, `c_type`, `class_id`, `material_id`, `c_hours`, `c_teatcher`, `add_by`, `c_status`, `insert_date`, `c_cost`) VALUES
(1, 'الصف الثاني - اللغة الانجليزية', 1, 2, 1, 20, 5, NULL, 0, '2020-11-06', 350),
(2, 'الصف الثالث - اللغة الانجليزية', 1, 3, 1, 50, 4, NULL, 1, '2020-11-06', 300),
(3, 'الصف الاول - اللغة العربية', 1, 1, 2, 30, 4, NULL, 1, '2020-11-06', 22),
(4, 'الصف السادس - اللغة الانجليزية', 1, 6, 1, 50, 4, NULL, 1, '2020-11-06', 450),
(5, 'دورة تأسيس لغة انجليزية', 2, NULL, NULL, 40, 5, NULL, 0, '2020-11-08', 500),
(6, 'دورة الخط العربي', 2, NULL, NULL, 50, 4, NULL, 1, '2020-11-08', 500),
(7, 'صعوبة تعلم : زين الدين', 4, NULL, NULL, 30, 5, 1, 1, '2020-11-13', 450),
(8, 'تاسيس لغة انجليزي ', 3, NULL, NULL, 30, 4, NULL, 1, '2020-11-19', 500),
(9, 'الصف الثالث - اللغة العربية', 1, 3, 2, 30, 4, NULL, 1, '2021-02-13', 250);

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `name`) VALUES
(0, 'السبت'),
(0, 'الاحد'),
(0, 'الاثنين'),
(0, 'الثلاثاء'),
(0, 'الاربعاء'),
(0, 'الخميس'),
(0, 'الجمعة');

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `m_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `m_name`) VALUES
(1, 'اللغة الانجليزية'),
(2, 'اللغة العربية'),
(3, 'الرياضيات'),
(4, 'تربية اسلامية'),
(5, 'علوم عامة'),
(6, 'فيزياء'),
(7, 'احياء'),
(8, 'كيمياء');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`) VALUES
(1, 'قاعة رقم 1'),
(2, 'قاعة رقم 2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `father_id` int(11) NOT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `address` text,
  `password` varchar(40) DEFAULT NULL,
  `additional_info` text,
  `account_status` int(11) NOT NULL DEFAULT '1',
  `user_type` int(11) NOT NULL,
  `reg_date` date NOT NULL,
  `gender` varchar(5) NOT NULL,
  `emergency_data` text,
  `stu_class` text,
  `mobile` text NOT NULL,
  `father_work_location` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `father_id`, `user_email`, `address`, `password`, `additional_info`, `account_status`, `user_type`, `reg_date`, `gender`, `emergency_data`, `stu_class`, `mobile`, `father_work_location`) VALUES
(1, 'انس المحتسب', -1, NULL, 'الخليل - راس الجورة', NULL, '-', 1, 3, '2020-11-04', 'ذكر', NULL, 'اتحاد لجان العمل الزراعي', '0595941017', NULL),
(2, 'اسماء الجعبري', -1, NULL, 'الخليل', NULL, '-', 1, 3, '2020-11-04', 'انثى', NULL, 'مركز الدرب', '0599999', NULL),
(3, 'اسلام الجعبري', -1, NULL, 'الخليل - نمره', NULL, '-', 1, 3, '2020-11-04', 'انثى', NULL, 'جامعة الخليل', '059111222', NULL),
(4, 'رامي الدراويش', -1, NULL, 'دورا', NULL, '-', 1, 2, '2020-11-06', 'ذكر', NULL, 'البولتكنك', '05999999', NULL),
(5, 'الاستاذ محمد احمد', -1, NULL, 'الخليل', NULL, '-', 1, 2, '2020-11-06', 'ذكر', NULL, '-', '-', NULL),
(6, 'زين الدين', 1, NULL, 'الخليل - راس الجورة', NULL, '-', 1, 4, '2020-11-07', 'ذكر', NULL, 'طالب', '0595941017', NULL),
(7, 'زينة', 1, NULL, 'الخليل - راس الجورة', NULL, '-', 1, 4, '2020-11-07', 'انثى', NULL, 'طالب', '0595941017', NULL),
(8, 'admin', -2, 'admin@mail.com', '-', '123456', '-', 1, 1, '2020-11-19', '-', '-', '-', '-', NULL),
(9, '', 0, NULL, NULL, NULL, NULL, 1, 0, '0000-00-00', '', NULL, NULL, '', NULL),
(10, 'احمد', 1, NULL, 'الخليل - راس الجورة', NULL, '--', 1, 4, '2021-01-01', 'ذكر', NULL, '2', '0595941017', NULL),
(11, 'علي', 1, NULL, 'الخليل - راس الجورة', NULL, '--', 1, 4, '2021-01-01', 'ذكر', NULL, NULL, '0595941017', NULL),
(12, '111', 1, NULL, '1', NULL, 'f', 1, 4, '2021-01-01', 'ذكر', NULL, NULL, '0595941017', NULL),
(13, '444', 3, NULL, 'الخليل -44 نمره', NULL, '444', 1, 4, '2021-02-05', 'ذكر', NULL, '3', '059111222', NULL),
(14, '66', 1, NULL, 'الخليل - راس الجورة', NULL, '66', 1, 4, '2021-02-05', 'ذكر', NULL, '3', '0595941017', NULL),
(15, 'reEW', 3, NULL, 'الخليلRWE - نمره', NULL, 'WEREW', 1, 4, '2021-02-09', 'ذكر', NULL, '3', '059111222', NULL),
(16, '222', 3, NULL, 'الخليل - نمره', NULL, '11', 1, 4, '2021-02-09', 'ذكر', NULL, '3', '059111222', NULL),
(17, '11', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-13', 'ذكر', NULL, '1', '1', NULL),
(18, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(19, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(20, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(21, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(22, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(23, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(24, '22', -1, NULL, '2', NULL, '11', 1, 4, '2021-02-13', 'ذكر', NULL, '3', '2', NULL),
(25, 'AAA', -1, NULL, 'AA', NULL, 'JMKJH', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '52353', NULL),
(26, 'AAA', -1, NULL, 'AA', NULL, 'JMKJH', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '52353', NULL),
(27, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(28, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(29, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(30, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(31, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(32, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(33, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(34, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(35, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(36, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(37, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(38, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(39, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(40, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(41, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(42, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(43, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(44, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(45, '22', -1, NULL, '2', NULL, '0', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '0', NULL),
(46, '22', -1, NULL, '2', NULL, '0', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '0', NULL),
(47, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(48, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(49, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(50, '1', -1, NULL, '1', NULL, '1', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '11', NULL),
(51, 'baraa', -1, NULL, 'hebron', NULL, '51545', 1, 4, '2021-02-18', 'ذكر', NULL, '3', '0595452', NULL),
(52, 'انس', -1, NULL, 'لايبلا', NULL, '123', 1, 4, '2021-02-19', 'ذكر', NULL, '3', '0565952', NULL),
(53, '2', -1, NULL, '2', NULL, '1', 1, 4, '2021-02-19', 'ذكر', NULL, '3', '1', NULL),
(54, '2', -1, NULL, '22', NULL, '2', 1, 4, '2021-02-19', 'ذكر', NULL, '3', '2', NULL),
(55, 'علي', -1, NULL, 'الخليل', NULL, '5555', 1, 4, '2021-02-19', 'ذكر', NULL, '3', '059*', NULL),
(56, 'aaa', -1, NULL, 'aaa', NULL, 'aa', 1, 4, '2021-02-27', 'ذكر', NULL, '3', '11', NULL),
(57, 'ahmad', -1, NULL, 'uhhui', NULL, '1551', 1, 4, '2021-02-27', 'ذكر', NULL, '3', '0599952', NULL),
(58, 'ali', -1, NULL, '1', NULL, '25612', 1, 4, '2021-02-27', 'ذكر', NULL, '3', '2566952', NULL),
(59, 'محمد احمد علي', -1, NULL, 'ششش', NULL, 'قق', 1, 4, '2021-02-27', 'ذكر', NULL, '3', '2ق', NULL),
(60, 'شش', -1, NULL, 'شش', NULL, 'لثقل', 1, 4, '2021-02-27', 'ذكر', NULL, '2', 'بلرث', NULL),
(61, 'jyu', -1, NULL, 'ukyu', NULL, 'uykyu', 1, 4, '2021-03-01', 'ذكر', NULL, '3', 'yukuy', NULL),
(62, 'subhi', -1, NULL, 'hebron', NULL, '---', 1, 4, '2021-05-22', 'ذكر', NULL, '3', '0599999', NULL),
(63, 'jyufg', -1, NULL, 'yutg', NULL, '98529', 1, 4, '2021-05-22', 'ذكر', NULL, '3', '95', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catch_receipt`
--
ALTER TABLE `catch_receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `cource_program`
--
ALTER TABLE `cource_program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cource_students`
--
ALTER TABLE `cource_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catch_receipt`
--
ALTER TABLE `catch_receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `cource_program`
--
ALTER TABLE `cource_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `cource_students`
--
ALTER TABLE `cource_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
