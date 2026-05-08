-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 05:27 PM
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
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `doctor_slug` varchar(100) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `patient_phone` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `availability` varchar(255) NOT NULL,
  `fee` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `consultation` varchar(255) NOT NULL,
  `avatar` varchar(10) NOT NULL,
  `online_available` tinyint(1) NOT NULL DEFAULT 1,
  `inperson_available` tinyint(1) NOT NULL DEFAULT 0,
  `experience` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `slug`, `name`, `title`, `location`, `availability`, `fee`, `description`, `consultation`, `avatar`, `online_available`, `inperson_available`, `experience`, `photo`) VALUES
(1, 'karina-velilla', 'Dr. Karina Velilla', 'MD, DPPS - Pediatrics', 'Esther Hospital', 'Today, 08:00 AM - 07:00 PM', '₱400.00', 'Specializes in pediatric wellness checks, acute child care, and vaccination guidance.', 'Online Clinic', 'KS', 1, 1, '', 'doctor_69fb5e66c081c7.93701111.jpg'),
(2, 'melissa-tisado', 'Dr. Melissa Tisado', 'MD - Family Medicine, Adult Diseases, Family and Community Health', 'Seventhday Adventist Hospital', 'Today, 12:00 AM - 10:00 PM', '₱500.00', 'Experienced in family care and pediatric-adult health coordination for every stage of childhood.', 'Online Clinic', 'MT', 1, 0, '', 'doctor_69fb5e8facf831.89322518.jpg'),
(3, 'eliza-peralta', 'Dr. Eliza Peralta', 'MD - Pediatrics', 'Blanco Hospital', 'Today, 05:00 PM - 11:30 PM', '₱500.00', 'Focuses on pediatric respiratory and developmental care with compassionate, child-friendly support.', 'Online Clinic', 'RP', 1, 1, '', 'doctor_69fb5e53b691b8.75100459.jpg'),
(4, 'rickyniell-ruiz', 'Dr. Ricky Niell Ruiz', 'MD, DPPS - Pediatrics', 'Medidas Hospital', 'Today, 08:00 AM - 04:00 PM', '₱500.00', 'Care for infants and children with a background in pediatric acute care and family guidance.', 'Online Clinic', 'AC', 1, 0, '7 yrs experience', 'doctor_69e9f090ea0ca8.07849537.jpg'),
(5, 'tricia-ojon', 'Dr. Tricia Ojon', 'MD, DPPS - Pediatrics', 'Esther Hospital', 'Today, 09:30 AM - 03:00 PM', '₱450.00', 'Provides pediatric evaluation and in-person care with deep experience in childhood health.', 'Balbido\'s Clinical Laboratory', 'TO', 1, 1, '', 'doctor_69fb5f1d76f1f8.11608496.jpg'),
(6, 'leonardo-fernandez', 'Dr. Leonardo Fernandez', 'MD - Pediatrics, Allergies and Asthma', 'Seventhday Adventist Hospital', 'Today, 10:00 AM - 06:00 PM', '₱450.00', 'Expert in pediatric allergies, asthma management, and long-term child wellness.', 'Online Clinic', 'LF', 1, 1, '', 'doctor_69fb5c83a2e1c3.84794539.jpg'),
(7, 'jaime-ramos', 'Dr. Jaime Ramos', 'MD - Pediatrics, Infectious Diseases', 'Blanco Hospital', 'Today, 02:00 PM - 09:00 PM', '₱520.00', 'Experienced in infectious disease treatment and pediatric recovery plans.', 'Online Clinic', 'JR', 1, 0, '', 'doctor_69fb5c639a4481.35679829.jpg'),
(8, 'mae-cabrera', 'Dr. Mae Cabrera', 'MD - Pediatric Nutrition and Growth', 'Medidas Hospital', 'Today, 11:00 AM - 05:00 PM', '₱470.00', 'Specializes in growth monitoring, nutrition counseling, and child development plans.', 'Online Clinic', 'MC', 1, 1, '', 'doctor_69fb5e723b3760.20210313.jpg'),
(9, 'alonzo-kim', 'Dr. Alonzo Kim', 'MD - Pediatrics, Child Development', 'Esther Hospital', 'Today, 03:00 PM - 08:00 PM', '₱480.00', 'Focused on child development assessments and family-centered pediatric advice.', 'Online Clinic', 'EK', 1, 1, '', 'doctor_69fb5e42409942.45172059.jpg'),
(11, 'marcalvin-tañeca', 'Dr. Marc Alvin Tañeca', 'MD - Pediatrics', 'Sinanglanan Hospital', 'Today, 10:00 AM - 05:00 PM', '₱500.00', 'yeah i doctor me doctor', 'Online Clinic', 'MA', 1, 0, '1 yr experience', 'doctor_69ea0722f07f66.56990321.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `salary` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
