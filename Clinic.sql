-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 28, 2024 at 02:26 PM
-- Server version: 8.0.35
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `Appointment_ID` int NOT NULL,
  `Patient_ID` int DEFAULT NULL,
  `Doctor_ID` int DEFAULT NULL,
  `Appointment_Date` datetime DEFAULT NULL,
  `is_Confirmed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`Appointment_ID`, `Patient_ID`, `Doctor_ID`, `Appointment_Date`, `is_Confirmed`) VALUES
(1, 1, 1, '2024-11-27 09:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE `Doctor` (
  `Doctor_ID` int NOT NULL,
  `FName` varchar(100) DEFAULT NULL,
  `LName` varchar(100) DEFAULT NULL,
  `Specialty_ID` int DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Doctor`
--

INSERT INTO `Doctor` (`Doctor_ID`, `FName`, `LName`, `Specialty_ID`, `Password`) VALUES
(1, 'Jack', 'Black', 1, '$2y$10$.LlMWKM.pIRqolwd3ZkZaOr/c7F/gxHIRq5Nnc4MPVEoTImoh7FqO'),
(2, 'JJ', 'JJ', 2, '$2y$10$6vaUb58pzB5msS.LWAuNlePcFR5S9Au8ELtKiO/kGmsQlSn3lwSFu'),
(3, 'John', 'Oldman', 3, '$2y$10$.KYP28h3S2M/kFrWs3OxDucMAHEJcSTrGK7IYsiP8.cASUyjUWfKu'),
(4, 'Noel', 'Nemesis', 4, '$2y$10$jo5d37/vU1hjUjYiPuZ/HenmJRJBaJVq3MTvw8DQyAk.vCB9sFHbC'),
(5, 'Jack', 'and Jill', 5, '$2y$10$O6mlmEHvPWSBuXz0oyRK6OL/lJVHnVyjGOP0o7iDE3.RxOOcOLwNK'),
(6, 'Ham', 'za', 6, '$2y$10$b5UhtPSeRXETjS0Pfw/AY.Xi7JZC1siIot46aOWQP5jB8R8BDhxye'),
(7, 'First', 'min', 7, '$2y$10$8PRZSvQoGDgrdQTYf8Z7WuFsaS4tkH40RvgJkCQuTsGXdzpuTpWTm'),
(8, 'Jared', 'Leto', 8, '$2y$10$DFp4.qlSy.xeC5lmDcWbWuJk9ySW2Hp.R/3GtJWsq.8xNXP8ZD6H6'),
(9, 'Chill', 'Guy', 9, '$2y$10$ZpYxMHIlN9QS9vLb4kxZ5.EmyDpDpTWRWlEWgHHqoAu1.vSoEQ2jq'),
(10, 'Bhoon', 'God', 10, '$2y$10$RiS5sNJvKMVq6tpftATySO.o2HZnbGy/ZVSBcxqA9WH/5jeurNvhS\n');

-- --------------------------------------------------------

--
-- Table structure for table `Medical_Record`
--

CREATE TABLE `Medical_Record` (
  `Record_ID` int NOT NULL,
  `Appointment_ID` int NOT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Medical_Record`
--

INSERT INTO `Medical_Record` (`Record_ID`, `Appointment_ID`, `Notes`) VALUES
(3, 1, 'Initial notes for Appointment 1\r\ntest2');

-- --------------------------------------------------------

--
-- Table structure for table `Patient`
--

CREATE TABLE `Patient` (
  `Patient_ID` int NOT NULL,
  `FName` varchar(50) DEFAULT NULL,
  `LName` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Patient`
--

INSERT INTO `Patient` (`Patient_ID`, `FName`, `LName`, `Email`, `Phone`, `DOB`, `Username`, `Password`) VALUES
(1, 'Doki', 'Machine', 'doki@boom.com', '69', '2013-09-09', NULL, '$2y$10$5Mms4TC5Rd0Sllmcy4xNkeIccXy5e9YZx1z8meYAIlbQxqAUIOwtO');

-- --------------------------------------------------------

--
-- Table structure for table `Payment`
--

CREATE TABLE `Payment` (
  `Payment_ID` int NOT NULL,
  `Appointment_ID` int DEFAULT NULL,
  `Payment_Date` datetime DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Payment_Status` enum('Pending','Completed','Failed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Payment`
--

INSERT INTO `Payment` (`Payment_ID`, `Appointment_ID`, `Payment_Date`, `Amount`, `Payment_Status`) VALUES
(1, 1, NULL, 100.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `Specialty`
--

CREATE TABLE `Specialty` (
  `Specialty_ID` int NOT NULL,
  `Specialty_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Specialty`
--

INSERT INTO `Specialty` (`Specialty_ID`, `Specialty_Name`) VALUES
(1, 'General Medicine'),
(2, 'Dentistry'),
(3, 'Cardiology'),
(4, 'Neurology'),
(5, 'Orthopedics'),
(6, 'Pediatrics'),
(7, 'Psychiatry'),
(8, 'Radiology'),
(9, 'Surgery'),
(10, 'Urology');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `fk_PatientApp` (`Patient_ID`),
  ADD KEY `fk_DoctorApp` (`Doctor_ID`);

--
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`Doctor_ID`),
  ADD KEY `fk_specialty` (`Specialty_ID`);

--
-- Indexes for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD PRIMARY KEY (`Record_ID`),
  ADD UNIQUE KEY `Appointment_ID` (`Appointment_ID`);

--
-- Indexes for table `Patient`
--
ALTER TABLE `Patient`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `fk_AppointmentPay` (`Appointment_ID`);

--
-- Indexes for table `Specialty`
--
ALTER TABLE `Specialty`
  ADD PRIMARY KEY (`Specialty_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `Appointment_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Doctor`
--
ALTER TABLE `Doctor`
  MODIFY `Doctor_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  MODIFY `Record_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Patient`
--
ALTER TABLE `Patient`
  MODIFY `Patient_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Payment`
--
ALTER TABLE `Payment`
  MODIFY `Payment_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `fk_DoctorApp` FOREIGN KEY (`Doctor_ID`) REFERENCES `Doctor` (`Doctor_ID`),
  ADD CONSTRAINT `fk_PatientApp` FOREIGN KEY (`Patient_ID`) REFERENCES `Patient` (`Patient_ID`);

--
-- Constraints for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD CONSTRAINT `fk_specialty` FOREIGN KEY (`Specialty_ID`) REFERENCES `Specialty` (`Specialty_ID`);

--
-- Constraints for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`Appointment_ID`) REFERENCES `Appointment` (`Appointment_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `fk_AppointmentPay` FOREIGN KEY (`Appointment_ID`) REFERENCES `Appointment` (`Appointment_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
