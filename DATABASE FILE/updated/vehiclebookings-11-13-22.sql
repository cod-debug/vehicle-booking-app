-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2022 at 07:18 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehiclebookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `tms_admin`
--

CREATE TABLE `tms_admin` (
  `a_id` int(20) NOT NULL,
  `a_name` varchar(200) NOT NULL,
  `a_email` varchar(200) NOT NULL,
  `user_type` int(11) NOT NULL,
  `a_pwd` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `has_sent_notif` varchar(5) NOT NULL DEFAULT 'no',
  `status` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_admin`
--

INSERT INTO `tms_admin` (`a_id`, `a_name`, `a_email`, `user_type`, `a_pwd`, `address`, `contact_num`, `payment_status`, `has_sent_notif`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'roy.duenas.sdtpnoli@gmail.com', 2, 'password', '', '', '', 'yes', 'active', '2022-11-03 14:35:48', '2022-11-03 14:35:48'),
(4, 'ROY DUEÃ‘AS', 'quensed@gmail.com', 1, 'password', 'Manalad', '09123456789', 'approved', 'no', 'active', '2022-11-13 06:01:34', '2022-11-13 06:01:34'),
(26, 'asd asd', 'roy.duenas@mobilemoney.ph', 1, 'password', 'asd', '', 'approved', 'no', 'active', '2022-11-04 03:52:33', '2022-11-04 03:52:33'),
(27, 'ROY DUEÃ‘AS', 'roy.duenas061399@gmail.com', 1, 'password', 'Manalad', '09123456789', 'approved', 'yes', 'active', '2022-11-03 14:38:15', '2022-11-03 14:38:15'),
(28, 'ROY DUEÃ‘AS', 'vehicle.booking.info@gmail.com', 1, 'password', 'Manalad', '09123123123', 'approved', 'yes', 'active', '2022-11-03 14:38:19', '2022-11-03 14:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `tms_branch`
--

CREATE TABLE `tms_branch` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_branch`
--

INSERT INTO `tms_branch` (`branch_id`, `branch_name`, `branch_address`, `status`) VALUES
(1, 'Kabankalan', 'Kabankalan city, Negros Occidental', 1),
(2, 'Ilog', 'Ilog, Negros Occidental', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tms_feedback`
--

CREATE TABLE `tms_feedback` (
  `f_id` int(20) NOT NULL,
  `f_uname` varchar(200) NOT NULL,
  `f_content` longtext NOT NULL,
  `f_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_feedback`
--

INSERT INTO `tms_feedback` (`f_id`, `f_uname`, `f_content`, `f_status`) VALUES
(1, 'Elliot Gape', 'This is a demo feedback text. This is a demo feedback text. This is a demo feedback text.', 'Published'),
(2, 'Mark L. Anderson', 'Sample Feedback Text for testing! Sample Feedback Text for testing! Sample Feedback Text for testing!', 'Published'),
(3, 'Liam Moore ', 'test number 3', '');

-- --------------------------------------------------------

--
-- Table structure for table `tms_pwd_resets`
--

CREATE TABLE `tms_pwd_resets` (
  `r_id` int(20) NOT NULL,
  `r_email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_pwd_resets`
--

INSERT INTO `tms_pwd_resets` (`r_id`, `r_email`) VALUES
(2, 'admin@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tms_syslogs`
--

CREATE TABLE `tms_syslogs` (
  `l_id` int(20) NOT NULL,
  `u_id` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_ip` varbinary(200) NOT NULL,
  `u_city` varchar(200) NOT NULL,
  `u_country` varchar(200) NOT NULL,
  `u_logintime` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tms_transactions`
--

CREATE TABLE `tms_transactions` (
  `trans_id` int(11) NOT NULL,
  `trans_type` varchar(255) NOT NULL,
  `trans_amount` double(11,2) NOT NULL,
  `trans_payment_status` varchar(255) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lessor_id` int(11) NOT NULL,
  `trans_itenerary` text NOT NULL,
  `booking_pickup_date` datetime NOT NULL,
  `booking_due_date` datetime NOT NULL,
  `trans_proof_of_payment` text NOT NULL,
  `trans_disapprove_remarks` text NOT NULL,
  `parent_trans_id` int(11) NOT NULL,
  `trans_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_transactions`
--

INSERT INTO `tms_transactions` (`trans_id`, `trans_type`, `trans_amount`, `trans_payment_status`, `vehicle_id`, `user_id`, `lessor_id`, `trans_itenerary`, `booking_pickup_date`, `booking_due_date`, `trans_proof_of_payment`, `trans_disapprove_remarks`, `parent_trans_id`, `trans_created_at`, `updated_at`, `created_by`) VALUES
(1, 'registration', 4000.00, 'approved', 0, 0, 26, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '3865569.jpg', '', 0, '2022-10-10 15:51:32', '2022-11-04 03:52:33', 0),
(2, 'registration', 4000.00, 'approved', 0, 0, 27, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '3865569.jpg', '', 0, '2022-10-11 17:04:01', '2022-11-04 03:55:24', 0),
(12, 'booking', 2200.00, 'returned', 6, 8, 0, 'Sample additional comment', '2022-10-19 10:25:29', '2022-10-19 22:25:29', '312-3124884_report-abuse-seven-deadly-sins-profile.png', '', 0, '2022-10-19 02:26:17', '2022-11-13 05:20:46', 8),
(13, 'booking', 8000.00, 'returned', 3, 5, 0, 'sample comment only!', '2022-10-31 23:18:25', '2022-11-01 11:18:25', 'back.jpg', '', 0, '2022-11-01 15:18:58', '2022-11-13 05:17:35', 5),
(17, 'registration', 4000.00, 'approved', 0, 0, 28, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '312-3124884_report-abuse-seven-deadly-sins-profile.png', '', 0, '2022-10-20 15:33:03', '2022-10-20 15:42:27', 28),
(18, 'registration', 4000.00, 'approved', 0, 0, 4, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '312-3124884_report-abuse-seven-deadly-sins-profile.png', '', 0, '2022-10-29 14:00:07', '2022-10-29 14:00:18', 4),
(20, 'registration', 4000.00, 'approved', 0, 0, 26, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'stockmarket-1631258083.jpg', '', 0, '2022-11-04 03:51:55', '2022-11-04 03:52:33', 26),
(21, 'booking', 2000.00, 'approved', 4, 8, 0, 'Sample additional comment.', '2022-11-21 09:00:46', '2022-11-21 21:00:46', 'bg.png', '', 0, '2022-11-04 03:54:42', '2022-11-04 03:55:24', 8),
(24, 'booking', 74700.00, 'returned', 6, 8, 0, 'ADDITIONAL FEE', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 12, '2022-11-13 05:48:52', '0000-00-00 00:00:00', 0),
(26, 'registration', 4000.00, 'approved', 0, 0, 4, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '312-3124884_report-abuse-seven-deadly-sins-profile.png', '', 0, '2022-11-13 06:09:34', '2022-11-13 06:09:55', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tms_user`
--

CREATE TABLE `tms_user` (
  `u_id` int(20) NOT NULL,
  `u_fname` varchar(200) NOT NULL,
  `u_lname` varchar(200) NOT NULL,
  `u_phone` varchar(200) NOT NULL,
  `u_addr` varchar(200) NOT NULL,
  `u_category` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_pwd` varchar(20) NOT NULL,
  `has_sent_notifs` varchar(5) NOT NULL DEFAULT 'no',
  `u_car_type` varchar(200) NOT NULL,
  `u_car_regno` varchar(200) NOT NULL,
  `u_car_bookdate` varchar(200) NOT NULL,
  `u_car_book_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_user`
--

INSERT INTO `tms_user` (`u_id`, `u_fname`, `u_lname`, `u_phone`, `u_addr`, `u_category`, `u_email`, `u_pwd`, `has_sent_notifs`, `u_car_type`, `u_car_regno`, `u_car_bookdate`, `u_car_book_status`) VALUES
(3, 'Demo', 'User', '070678909', '90100 Machakos ', 'Driver', 'demouser@tms.com', 'demo123', 'no', 'SUV', 'CA1001', '2022-09-01', 'Pending'),
(4, 'John', 'Settles', '7145698540', '45 Clearview Drive', 'Driver', 'johns@mail.com', 'password', 'no', '', '', '', ''),
(5, 'Joseph', 'Yung', '7896587777', '72 Doe Meadow Drive', 'Driver', 'joseph@mail.com', 'password', 'no', '', '', '', ''),
(6, 'Vincent', 'Pelletier', '4580001456', '58 Farland Avenue', 'Driver', 'vincentp@mail.com', 'password', 'no', '', '', '', ''),
(7, 'Jesse', 'Robinson', '1458887855', '73 Fleming Way', 'Driver', 'jesser@mail.com', 'password', 'no', '', '', '', ''),
(8, 'Jona', 'Lin', '09123456789', 'Himamaylan', 'User', 'jo@mail.com', 'password', 'no', 'Sedan', 'CA1690', '2022-09-13', 'Approved'),
(9, 'Paul', 'Mills', '7412563258', '12 Red Maple Drive', 'User', 'paul@mail.com', 'password', 'no', 'Sedan', 'CA2077', '2022-09-14', 'Pending'),
(10, 'Liam', 'Moore', '7410001212', '114 Bleck Street', 'User', 'liamoore@mail.com', 'password', 'no', 'Sedan', 'CA1690', '2022-09-14', 'Approved'),
(11, 'Jeff', 'Lewis', '7854545454', '114 Test Adr', 'User', 'jeff@mail.com', 'password', 'no', 'Sedan', 'CA7700', '2022-09-14', 'Pending'),
(12, 'Kenya', 'Norman', '7896547855', '114 Test Addr', 'User', 'normank@mail.com', 'password', 'no', 'Sedan', 'CA2077', '2022-10-08', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tms_vehicle`
--

CREATE TABLE `tms_vehicle` (
  `v_id` int(20) NOT NULL,
  `v_name` varchar(200) NOT NULL,
  `v_reg_no` varchar(200) NOT NULL,
  `v_pass_no` varchar(200) NOT NULL,
  `v_driver` varchar(200) NOT NULL,
  `v_category` varchar(200) NOT NULL,
  `v_dpic` varchar(200) NOT NULL,
  `v_status` varchar(200) NOT NULL,
  `v_per_12hrs` double(11,2) NOT NULL,
  `v_per_24hrs` double(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tms_vehicle`
--

INSERT INTO `tms_vehicle` (`v_id`, `v_name`, `v_reg_no`, `v_pass_no`, `v_driver`, `v_category`, `v_dpic`, `v_status`, `v_per_12hrs`, `v_per_24hrs`) VALUES
(3, 'Euro Bond', 'CA7766', '50', 'Vincent Pelletier', 'Bus', 'buscch.jpg', 'Available', 8000.00, 12000.00),
(4, 'Honda Accord', 'CA2077', '5', 'Joseph Yung', 'Sedan', '2019_honda_accord_angularfront.jpg', 'Available', 2000.00, 2800.00),
(5, 'Volkswagen Passat', 'CA1690', '5', 'Jesse Robinson', 'Sedan', 'volkswagen-passat-500.jpg', 'Available', 1900.00, 2600.00),
(6, 'Nissan Rogue', 'CA1001', '7', 'Demo User', 'SUV', 'Nissan_Rogue_SV_2021.jpg', 'Available', 2200.00, 2900.00),
(7, 'Subaru Legacy', 'CA7700', '5', 'John Settles', 'Sedan', 'Subaru_Legacy_Premium_2022_2.jpg', 'Available', 2250.00, 2600.00),
(8, 'Toyota Innova', '123323', '6', 'Demo User', 'SUV', '3865569.jpg', 'Available', 1800.00, 2500.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tms_admin`
--
ALTER TABLE `tms_admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `tms_branch`
--
ALTER TABLE `tms_branch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `tms_feedback`
--
ALTER TABLE `tms_feedback`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `tms_pwd_resets`
--
ALTER TABLE `tms_pwd_resets`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `tms_syslogs`
--
ALTER TABLE `tms_syslogs`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `tms_transactions`
--
ALTER TABLE `tms_transactions`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `tms_user`
--
ALTER TABLE `tms_user`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `tms_vehicle`
--
ALTER TABLE `tms_vehicle`
  ADD PRIMARY KEY (`v_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tms_admin`
--
ALTER TABLE `tms_admin`
  MODIFY `a_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tms_branch`
--
ALTER TABLE `tms_branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tms_feedback`
--
ALTER TABLE `tms_feedback`
  MODIFY `f_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tms_pwd_resets`
--
ALTER TABLE `tms_pwd_resets`
  MODIFY `r_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tms_syslogs`
--
ALTER TABLE `tms_syslogs`
  MODIFY `l_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tms_transactions`
--
ALTER TABLE `tms_transactions`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tms_user`
--
ALTER TABLE `tms_user`
  MODIFY `u_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tms_vehicle`
--
ALTER TABLE `tms_vehicle`
  MODIFY `v_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
