-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2022 at 02:27 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_sigt`
--

-- --------------------------------------------------------

--
-- Table structure for table `debt`
--

CREATE TABLE `debt` (
  `iddebt` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `debt` int(50) NOT NULL,
  `debtstatus` int(2) NOT NULL,
  `payqty` int(50) NOT NULL,
  `paydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `debt`
--

INSERT INTO `debt` (`iddebt`, `iduser`, `debt`, `debtstatus`, `payqty`, `paydate`) VALUES
(8, 10, 0, 0, 0, '0000-00-00 00:00:00'),
(11, 1, 0, 0, 0, '0000-00-00 00:00:00'),
(12, 9, 200000, 2, 200000, '2022-11-23 20:01:10'),
(13, 11, 0, 0, 0, '0000-00-00 00:00:00'),
(15, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(16, 14, 0, 0, 0, '0000-00-00 00:00:00'),
(17, 15, 0, 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemid` int(11) NOT NULL,
  `itemname` varchar(50) NOT NULL,
  `itemdesc` varchar(50) NOT NULL,
  `itemava` int(11) NOT NULL,
  `itemtotal` int(11) NOT NULL,
  `itemprice` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemid`, `itemname`, `itemdesc`, `itemava`, `itemtotal`, `itemprice`) VALUES
(1, 'Pena Standard AE7', 'Alat Tulis', 44, 49, 3000),
(3, 'Rp 100.000,00', 'Uang Tunai', 32, 34, 100000),
(4, 'Earphone Samsung Galaxy S8 AKG', 'Alat Elektronik', 19, 20, 20000),
(5, 'Xiaomi Redmi Note 10', 'Smartphone', 8, 8, 2200000),
(6, 'Apple Iphone 11', 'Smartphone', 0, 1, 6500000),
(7, 'Laptop ASUS Vivobook 14 A416FA', 'Komputer', 1, 1, 6000000),
(8, 'Keyboard', 'Alat Elektronik', 5, 5, 200000),
(9, 'Mouse', 'Alat Elektronik', 8, 8, 80000),
(11, 'Roti Kukus', 'Makanan', 17, 17, 12000),
(64, 'Piano Yamaha', 'Alat Musik', 5, 5, 6500000),
(65, 'Google Maps API', 'API Service', 96, 99, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `itemin`
--

CREATE TABLE `itemin` (
  `inid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `receivername` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `itemin`
--

INSERT INTO `itemin` (`inid`, `itemid`, `date`, `receivername`, `qty`) VALUES
(15, 9, '2022-11-15 15:00:21', 'Roy', 3),
(16, 5, '2022-11-15 15:00:51', 'Jeff', 5);

-- --------------------------------------------------------

--
-- Table structure for table `itemout`
--

CREATE TABLE `itemout` (
  `outid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `receivername` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `itemout`
--

INSERT INTO `itemout` (`outid`, `itemid`, `date`, `receivername`, `qty`) VALUES
(33, 1, '2022-11-15 15:00:38', 'Tori', 10),
(34, 7, '2022-11-15 15:01:00', 'Rudi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `idtr` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `borrowqty` int(11) NOT NULL,
  `req` int(11) NOT NULL DEFAULT 0,
  `returned` int(11) NOT NULL DEFAULT 0,
  `borrowdate` datetime NOT NULL,
  `borrowdeadline` datetime NOT NULL,
  `status` int(2) NOT NULL,
  `debtdate` datetime NOT NULL,
  `warehouselat` varchar(200) NOT NULL,
  `courierlat` varchar(200) NOT NULL,
  `courierlon` varchar(200) NOT NULL,
  `warehouselon` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `courierid` int(11) NOT NULL,
  `receivername` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`idtr`, `iduser`, `itemid`, `borrowqty`, `req`, `returned`, `borrowdate`, `borrowdeadline`, `status`, `debtdate`, `warehouselat`, `courierlat`, `courierlon`, `warehouselon`, `address`, `courierid`, `receivername`) VALUES
(111, 9, 1, 2, 0, 0, '2022-11-22 17:45:25', '2022-12-22 17:45:25', 9, '2022-12-22 17:45:25', '0.525268163083154', '0.5267136', '101.4287469', '101.44802027724216', 'Jalan Durian, Pulau Karam, Pekanbaru City, Riau, Indonesia', 10, 'User 1'),
(112, 9, 6, 1, 0, 0, '2022-11-22 20:09:10', '2022-12-22 20:09:10', 2, '2022-12-22 20:09:10', '0.525268163083154', '0.5267136', '101.4287469', '101.44802027724216', 'Jalan Jati, Kampung Baru, Pekanbaru City, Riau, Indonesia', 10, 'User 1'),
(113, 9, 8, 1, 0, 0, '2022-11-23 19:59:57', '2022-12-23 19:59:57', 11, '2022-12-23 19:59:57', '', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(1) NOT NULL DEFAULT 0,
  `image` varchar(99) DEFAULT NULL,
  `loan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `username`, `email`, `password`, `role`, `image`, `loan`) VALUES
(1, 'Hendy Saputra', 'hendy.saputra3132@student.unri.ac.id', '12345', 1, 'user.jpg', 0),
(9, 'User 1', 'user1@gmail.com', '123', 2, 'user.jpg', 1),
(10, 'Courier A', 'couriera@gmail.com', '123', 3, 'user.jpg', 0),
(11, 'Courier B', 'courierb@gmail.com', '123', 3, 'user.jpg', 0),
(14, 'Elvina Carolina', 'elvina.carolina1123@student.unri.ac.id', '12345', 1, 'user.jpg', 0),
(15, 'Seteven', 'seteven4203@student.unri.ac.id', '12345', 1, 'user.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertransaction`
--

CREATE TABLE `usertransaction` (
  `idutr` int(11) NOT NULL,
  `idtr` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `borrowqty` int(11) NOT NULL,
  `req` int(11) NOT NULL DEFAULT 0,
  `returned` int(11) NOT NULL DEFAULT 0,
  `borrowdate` datetime NOT NULL,
  `borrowdeadline` datetime NOT NULL,
  `status` int(2) NOT NULL,
  `debtdate` datetime NOT NULL,
  `warehouselat` varchar(200) NOT NULL,
  `warehouselon` varchar(200) NOT NULL,
  `courierlat` varchar(200) NOT NULL,
  `courierlon` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `courierid` int(11) NOT NULL,
  `receivername` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertransaction`
--

INSERT INTO `usertransaction` (`idutr`, `idtr`, `iduser`, `itemid`, `borrowqty`, `req`, `returned`, `borrowdate`, `borrowdeadline`, `status`, `debtdate`, `warehouselat`, `warehouselon`, `courierlat`, `courierlon`, `address`, `courierid`, `receivername`) VALUES
(112, 111, 9, 1, 2, 0, 0, '2022-11-22 17:45:25', '2022-12-22 17:45:25', 9, '2022-12-22 17:45:25', '0.525268163083154', '101.44802027724216', '0.5267136', '101.4287469', 'Jalan Durian, Pulau Karam, Pekanbaru City, Riau, Indonesia', 10, 'User 1'),
(113, 112, 9, 6, 1, 0, 0, '2022-11-22 20:09:10', '2022-12-22 20:09:10', 2, '2022-12-22 20:09:10', '0.525268163083154', '101.44802027724216', '0.5267136', '101.4287469', 'Jalan Jati, Kampung Baru, Pekanbaru City, Riau, Indonesia', 10, 'User 1'),
(114, 113, 9, 8, 1, 0, 0, '2022-11-23 19:59:57', '2022-12-23 19:59:57', 11, '2022-12-23 19:59:57', '', '', '', '', '', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `debt`
--
ALTER TABLE `debt`
  ADD PRIMARY KEY (`iddebt`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemid`);

--
-- Indexes for table `itemin`
--
ALTER TABLE `itemin`
  ADD PRIMARY KEY (`inid`);

--
-- Indexes for table `itemout`
--
ALTER TABLE `itemout`
  ADD PRIMARY KEY (`outid`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`idtr`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- Indexes for table `usertransaction`
--
ALTER TABLE `usertransaction`
  ADD PRIMARY KEY (`idutr`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `debt`
--
ALTER TABLE `debt`
  MODIFY `iddebt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `itemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `itemin`
--
ALTER TABLE `itemin`
  MODIFY `inid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `itemout`
--
ALTER TABLE `itemout`
  MODIFY `outid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idtr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `usertransaction`
--
ALTER TABLE `usertransaction`
  MODIFY `idutr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
