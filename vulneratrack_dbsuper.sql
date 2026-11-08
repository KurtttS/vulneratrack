-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 08, 2026 at 09:48 AM
-- Server version: 12.3.3-MariaDB-log
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
DROP DATABASE IF EXISTS vulneratrack_db;
CREATE DATABASE vulneratrack_db;
USE vulneratrack_db;
--

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `AreaID` int(11) NOT NULL,
  `Area_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`AreaID`, `Area_Name`) VALUES
(1, 'Celine Homes'),
(2, 'East Homes'),
(3, 'Purok Carvic');

-- --------------------------------------------------------

--
-- Table structure for table `household`
--

CREATE TABLE `household` (
  `HouseholdID` int(11) NOT NULL,
  `CoordinateX` decimal(11,8) NOT NULL,
  `CoordinateY` decimal(11,8) NOT NULL,
  `Area_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `household`
--

INSERT INTO `household` (`HouseholdID`, `CoordinateX`, `CoordinateY`, `Area_ID`) VALUES
(18, 122.97679100, 10.68411900, 2),
(19, 122.96048900, 10.69113100, 3),
(20, 122.95966000, 10.68989300, 3),
(22, 122.98888600, 10.67566400, 2),
(23, 122.97679100, 10.68411900, 2),
(24, 122.98456200, 10.67441100, 1),
(25, 122.95954200, 10.68978900, 3),
(26, 122.97679100, 10.68411900, 3),
(29, 122.95944100, 10.69076400, 3),
(30, 122.98537700, 10.67333000, 1),
(31, 122.98575282, 10.67264857, 1),
(32, 122.98836708, 10.67680258, 2),
(33, 122.98979800, 10.67398600, 2),
(34, 122.98881021, 10.67581153, 2),
(35, 122.99033400, 10.67386600, 2);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `MemberID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Household_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`MemberID`, `User_ID`, `Household_ID`) VALUES
(21, 23, 18),
(23, 25, 20),
(24, 26, 19),
(25, 27, 19),
(26, 28, 22),
(27, 29, 23),
(28, 30, 24),
(29, 31, 25),
(30, 32, 26),
(32, 34, 19),
(33, 35, 29),
(34, 36, 30),
(35, 37, 31),
(36, 38, 32),
(37, 39, 33),
(38, 40, 34),
(39, 41, 35);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL,
  `Date_Sent` date NOT NULL,
  `Date_Resolved` date DEFAULT NULL,
  `Report_Message` varchar(200) NOT NULL,
  `Type_of_Problem` varchar(100) NOT NULL,
  `Report_Image` varchar(150) NOT NULL,
  `Household_ID` int(11) NOT NULL,
  `report_address` varchar(255) DEFAULT NULL,
  `report_status` enum('unverified','ongoing','resolved') DEFAULT 'unverified',
  `located` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `Date_Sent`, `Date_Resolved`, `Report_Message`, `Type_of_Problem`, `Report_Image`, `Household_ID`, `report_address`, `report_status`, `located`) VALUES
(9, '2026-03-31', NULL, 'Flooding everywhere in our subdivision', 'Rising Water Level', '1774968569_flood.jpg', 19, 'Purok Carvic Sts', 'ongoing', 'EH3 B1 L1'),
(10, '2026-04-05', NULL, 'Somethung wrong here', 'Rising Water Level', '1775432192_17754321650089108192440338689393.jpg', 25, 'Purok carvic one', 'ongoing', 'Purok cargvic'),
(11, '2026-04-06', '2026-04-19', 'Presentaiton', 'Other', '', 23, 'EH3 B1 L1', 'resolved', 'Eh3 B1 L6'),
(13, '2026-04-19', NULL, 'watafreak', 'Rising Water Level', '', 30, 'One regis street', 'ongoing', 'BAHA'),
(14, '2026-04-20', NULL, 'There is flood near our house. We need help ASAP', 'Other', '1776645435_flood new.jpg', 29, 'Purok carvic blk 3 lt 2', 'ongoing', 'Purok Saint Anne B1 L1'),
(15, '2026-04-20', NULL, 'Part 2', 'Other', '1776645491_flood new.jpg', 29, 'Purok carvic blk 3 lt 2', 'unverified', 'Test'),
(16, '2026-05-23', NULL, 'road potholes found near my place', 'Road Potholes', '1779510203_scourch edits.png', 20, 'One regis street', 'unverified', 'Purok Carvic');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password_Hashed` varchar(255) NOT NULL,
  `User_Type` varchar(25) NOT NULL,
  `Date_Created` date NOT NULL,
  `Date_Updated` date NOT NULL,
  `Date_of_Birth` date NOT NULL,
  `Birth_Certificate` varchar(150) DEFAULT NULL,
  `User_Status` varchar(50) NOT NULL,
  `Contact_Number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `First_Name`, `Last_Name`, `Address`, `Email`, `Password_Hashed`, `User_Type`, `Date_Created`, `Date_Updated`, `Date_of_Birth`, `Birth_Certificate`, `User_Status`, `Contact_Number`) VALUES
(23, 'User', 'Berondo', 'East homes blk 3 lt 2', 'vulneradmin@gmail.com', '$2y$10$1oJn7FQGyO3hT/m0FH6q3OL/JAE.pqlY9Y.Zp3Nm.8M5F3.xFn2q6', 'admin', '2026-03-23', '2026-03-23', '0000-00-00', 'VLSM-Workbook-Instructors-Edition-v2_0.pdf', 'PWD', '09532623'),
(25, 'Gail', 'Flores', 'One regis street', 'gailflores1121@gmail.com', '$2y$12$dV60EyVFeD4CV3/KjX/5fO3RpJCqJ7aQ2nXmEeghC1ZOwmt7hBFeK', 'user', '2026-03-25', '2026-03-25', '2005-11-21', 'vulneratrack_dbsuperheliohostbeforesms.sql', 'Adult', '09298242625'),
(26, 'charm', 'martinez', 'purok blk 20', 'charm@gmail.com', '$2y$12$2eja8f14EwkmJnpkWdENf./9iuK1UC1uz56AyQmIoWL4E98v6s0he', 'user', '2026-03-25', '2026-04-20', '1987-12-25', 'IMG_1226.jpeg', 'Adult', '09669974380'),
(27, 'Wayne', 'Salonga', 'Purok Carvic Sts', 'anojhey@gmail.com', '$2y$12$mOVi3.9jtM3hdgKbzeRlo.yiFcsibxx9D.o2AujJJGnePfEtoGx7q', 'user', '2026-03-25', '2026-03-24', '2005-12-23', NULL, 'PWD', '09669572948'),
(28, 'Juliana', 'Duco', 'East homed blk 3 lt 2', 'juliana@gmail.com', '$2y$12$HAW2y5QtLL1EysG6q7LKZu63iB2T/ML9hLF47H0cVaENPf/PYWQZ2', 'user', '2026-04-06', '2026-04-06', '2006-04-18', '20260405_203330.jpg', 'PWD', '09707216118'),
(29, 'Luke', 'Berondo', 'EH3 B1 L1', 'berondoluke@gmail.com', '$2y$12$u0hmre2jqRNAvAsWS93wqOutgb.uTjK0aQagQsRS0BVW1qgA3Lgu.', 'user', '2026-04-06', '2026-04-06', '2005-04-06', 'CWrld.pdf', 'Adult', '09229905468'),
(30, 'Celine', 'Dion', 'Celine homes blk 3 lt 9', 'celine@gmail.com', '$2y$12$GWMFLe8TzXKgjisCy0ihOOKbme6ZnsZCh0ECv.NdOlsVurDHfYlGq', 'user', '2026-04-06', '2026-04-06', '1981-04-06', '17754319704392477343630924719256.jpg', 'Senior Citizen', '09502710768'),
(31, 'Francine', 'Teodoro', 'Purok carvic one', 'Somethingwong@gmail.com', '$2y$12$UwmQ3OhJXQgqJKpVS9Qqt.4al7XoZJJ3HjtyOCcQm9F76231Y52iW', 'user', '2026-04-06', '2026-04-06', '2026-04-28', '1775432119645704070146067005491.jpg', 'Child', '09669572948'),
(32, 'Wan', 'To', 'B1 L3', 'waku@gmail.com', '$2y$12$knBdBYUPxqSZ5b2LFR2N3eaeJAAQpEM0LGqf7JwarKBexRgSo.ov.', 'user', '2026-04-06', '2026-04-19', '2005-11-30', NULL, 'Senior Citizen', '09229905468'),
(34, 'Karen', 'Bieber', 'Saint Annee 1221', 'pb@gmail.com', '$2y$12$qdyAu0.IEM5g7tBeQ0OrdOC4r28Mm6y4nOGjVKx6vsOBJWeFMYw7u', 'user', '2026-04-19', '2026-04-19', '2004-11-30', NULL, 'Child', '09669572948'),
(35, 'Jonas', 'Abdul', 'Purok carvic blk 3 lt 2', 'vulnerauser@gmail.com', '$2y$12$qRtpijRIsKPjWHdNG/FvseTbDPqMRdxl31.7948PxTPpMaQGdnz7C', 'user', '2026-04-19', '2026-04-20', '2000-03-12', NULL, 'Adult', '09669572948'),
(36, 'hannah', 'gwen', 'One regis street', 'hgflores@gmail.com', '$2y$12$CKmPf2aWwVwFfPkvIPpjiu7Mqdd6qN8NzaFdZKqruCfxZ4etNZx0O', 'user', '2026-04-20', '2026-04-20', '2001-05-01', 'FLORES_DSA.jpg', 'Adult', '09669572948'),
(37, 'Jeremy', 'Hermando', 'Celine Homes', 'jeremy@gmail.com', '$2y$12$ACF4z2DfJvfVBt/SmnsSpukeoNHSv1hKJwLIJ0GVnVjHrUISR3K4u', 'user', '2026-04-19', '2026-04-19', '2019-01-09', NULL, 'Child', '09669572948'),
(38, 'Kurt', 'Flores', 'celine street', 'kurt1121@gmail.com', '$2y$12$Zn9.uIx/vpKCtEAEf1BSzOR2AIGA7Z9neAEP5HU6f/0d9Tue6ux.m', 'user', '2026-04-19', '2026-04-19', '1999-02-21', NULL, 'Adult', '09298242625'),
(39, 'Francois', 'Wagayway', 'East homes blk 3 lt 5', 'francois@gmail.com', '$2y$12$mtMVUZWPM/65X6A3TIaqp.7.OvExF9LmutsldXEomsKV9nJzWVsoK', 'user', '2026-04-20', '2026-04-20', '1992-04-08', 'Screenshot_20260420_072702_Chrome.jpg', 'Adult', '09669572948'),
(40, 'Sheena', 'Geronimo', 'EH3 B1 L1', 'sg@gmail.com', '$2y$12$mQyAiv8cUPslYVmuVsjHDuEQoBmAkMEtbDJtGEp5f7zFvzsxQeqkO', 'user', '2026-04-20', '2026-04-20', '2020-01-11', NULL, 'Child', '09229905468'),
(41, 'Francine', 'Teodoro', 'easthomea2', 'giovani@gmail.com', '$2y$12$YjNfvjPZCVUDiAWs/29y6.CFjRI/5GlWs/urejIRsyH5El3qbqFem', 'user', '2026-07-27', '2026-07-27', '1988-07-07', '17851204227507733932888571894643.jpg', 'Senior Citizen', '09669572948');

-- --------------------------------------------------------

--
-- Table structure for table `vulnerability`
--

CREATE TABLE `vulnerability` (
  `VulnerabilityID` int(11) NOT NULL,
  `Type_of_Vulnerablity` varchar(50) NOT NULL,
  `Vulnerability_Points` int(11) NOT NULL,
  `Household_ID` int(11) DEFAULT NULL,
  `Area_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vulnerability`
--

INSERT INTO `vulnerability` (`VulnerabilityID`, `Type_of_Vulnerablity`, `Vulnerability_Points`, `Household_ID`, `Area_ID`) VALUES
(95, 'Clogged Canals', 2, 22, NULL),
(96, 'Severe Structural Damage', 5, 22, NULL),
(97, 'Road potholes', 3, 22, NULL),
(98, 'Standing Water', 2, 22, NULL),
(99, 'Structural Damage', 3, 22, NULL),
(100, 'Other', 3, 22, NULL),
(103, 'Road potholes', 3, 24, NULL),
(104, 'Other', 3, 24, NULL),
(105, 'Severe Structural Damage', 5, 24, NULL),
(107, 'Road potholes', 3, 25, NULL),
(108, 'Clogged Canals', 2, 25, NULL),
(109, 'Standing Water', 2, 25, NULL),
(110, 'Structural Damage', 3, 25, NULL),
(111, 'Severe Structural Damage', 5, 25, NULL),
(120, 'Road potholes', 3, 29, NULL),
(121, 'Clogged Canals', 2, 29, NULL),
(122, 'Standing Water', 2, 29, NULL),
(123, 'Structural Damage', 3, 29, NULL),
(124, 'Other', 3, 29, NULL),
(126, 'Road potholes', 3, 19, NULL),
(127, 'Clogged Canals', 2, 19, NULL),
(132, 'Road potholes', 3, 30, NULL),
(133, 'Structural Damage', 3, 30, NULL),
(134, 'Clogged Canals', 2, 30, NULL),
(135, 'Other', 3, 30, NULL),
(139, 'Standing Water', 2, 19, NULL),
(140, 'Structural Damage', 3, 19, NULL),
(142, 'Road potholes', 3, 31, NULL),
(143, 'Clogged Canals', 2, 31, NULL),
(144, 'Standing Water', 2, 31, NULL),
(146, 'Other', 3, 31, NULL),
(148, 'Structural Damage', 3, 31, NULL),
(149, 'Severe Structural Damage', 5, 31, NULL),
(155, 'Other', 3, 25, NULL),
(156, 'Storm Surge', 10, NULL, 1),
(157, 'Other', 3, 19, NULL),
(158, 'Severe Structural Damage', 5, 19, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`AreaID`);

--
-- Indexes for table `household`
--
ALTER TABLE `household`
  ADD PRIMARY KEY (`HouseholdID`),
  ADD KEY `Area_ID_fk` (`Area_ID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`MemberID`),
  ADD KEY `User_ID_fk` (`User_ID`),
  ADD KEY `Household_ID_fk` (`Household_ID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `Househhold_ID_fk` (`Household_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `vulnerability`
--
ALTER TABLE `vulnerability`
  ADD PRIMARY KEY (`VulnerabilityID`),
  ADD KEY `Household_ID_fk` (`Household_ID`),
  ADD KEY `Area_ID_fk` (`Area_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `AreaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `household`
--
ALTER TABLE `household`
  MODIFY `HouseholdID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `MemberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `vulnerability`
--
ALTER TABLE `vulnerability`
  MODIFY `VulnerabilityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `household`
--
ALTER TABLE `household`
  ADD CONSTRAINT `household_ibfk_1` FOREIGN KEY (`Area_ID`) REFERENCES `area` (`AreaID`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`Household_ID`) REFERENCES `household` (`HouseholdID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`Household_ID`) REFERENCES `household` (`HouseholdID`);

--
-- Constraints for table `vulnerability`
--
ALTER TABLE `vulnerability`
  ADD CONSTRAINT `vulnerability_ibfk_1` FOREIGN KEY (`Area_ID`) REFERENCES `area` (`AreaID`),
  ADD CONSTRAINT `vulnerability_ibfk_2` FOREIGN KEY (`Household_ID`) REFERENCES `household` (`HouseholdID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
