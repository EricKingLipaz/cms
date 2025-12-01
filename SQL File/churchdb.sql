-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2021 at 03:32 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `churchdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `property` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `transaction_amount` int(12) NOT NULL,
  `transaction_ref` int(12) NOT NULL,
  `transaction_method` varchar(255) DEFAULT NULL,
  `deposited_by` varchar(255) NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_posting_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `currency`, `property`, `description`, `transaction_amount`, `transaction_ref`, `transaction_method`, `deposited_by`, `transaction_date`, `transaction_posting_date`) VALUES
(45, 'USD', '', 'offertories', 3000000, 66767676, 'Cash', 'Arinaitwe', '2021-05-02', '2021-08-01 03:06:41'),
(46, 'USD', '', 'Monday evening mass offertories', 500000, 8988887, 'Cash', 'Arinaitwe', '2021-05-17', '2021-08-01 03:06:54'),
(47, 'USD', '', 'Wednesday evening mass offertories ', 5000, 56789856, 'Cash', 'John', '2021-08-01', '2021-08-01 06:47:01'),
(48, 'USD', '', 'Sunday mass offertory', 500000, 2147483647, 'Cash', 'John', '2021-08-25', '2021-08-25 14:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `id` int(11) NOT NULL,
  `bankname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `account_no` int(15) NOT NULL,
  `currency` varchar(255) CHARACTER SET latin1 NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`id`, `bankname`, `account_no`, `currency`, `amount`) VALUES
(8, 'Stanbic Bank', 54344434, 'UGX', 3000000),
(9, 'Equity Bank', 66668787, 'USD', 505320);

-- --------------------------------------------------------

--
-- Table structure for table `expence`
--

CREATE TABLE `expence` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `resoan` varchar(500) NOT NULL,
  `expense` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expence`
--

INSERT INTO `expence` (`id`, `date`, `amount`, `resoan`, `expense`, `category`, `CreationDate`) VALUES
(67, '2021-05-02', 700000, 'Water: buying water', 'Water', '2', '2021-05-02 01:18:27'),
(68, '2021-05-02', 300000, ' Power: electricity bill', ' Power', '3', '2021-05-02 01:22:01'),
(69, '2021-05-02', 500000, ' Papers: papers for printing letters', ' Papers', '2', '2021-05-02 01:26:43'),
(70, '2021-05-21', 200000, ' Fuel: fueling church cars', ' Fuel', '3', '2021-05-21 19:54:01'),
(71, '2021-08-25', 100000, 'Water: water payment', 'Water', '2', '2021-08-25 14:33:15');

-- --------------------------------------------------------

--
-- Table structure for table `expensecategory`
--

CREATE TABLE `expensecategory` (
  `id` int(11) NOT NULL,
  `categoryname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `details` varchar(255) CHARACTER SET latin1 NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expensecategory`
--

INSERT INTO `expensecategory` (`id`, `categoryname`, `details`, `creation_date`) VALUES
(2, ' Utilities', 'Expense to support office', '2020-12-01 12:54:19'),
(3, ' Operations', 'Expenses during activities', '2020-12-01 12:54:19'),
(4, ' Others', 'other expenses', '2021-04-25 17:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `expensename`
--

CREATE TABLE `expensename` (
  `id` int(11) NOT NULL,
  `categoryname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `expensename` varchar(255) CHARACTER SET latin1 NOT NULL,
  `registeredby` varchar(255) CHARACTER SET latin1 NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expensename`
--

INSERT INTO `expensename` (`id`, `categoryname`, `expensename`, `registeredby`, `creation_date`) VALUES
(1, '2', 'Water', 'CEO', '2020-12-01 12:38:14'),
(2, '3', ' Power', 'CEO', '2020-12-01 12:38:14'),
(3, '2', ' Papers', 'CEO', '2020-12-01 12:38:14'),
(4, '2', ' Pens', 'CEO', '2020-12-01 12:38:14'),
(5, '3', ' Fuel', 'CEO', '2020-12-01 12:38:14'),
(6, '3', ' Airtime', 'CEO', '2020-12-01 12:38:14'),
(7, '3', ' Transport', 'CEO', '2020-12-01 12:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `general_jaunal`
--

CREATE TABLE `general_jaunal` (
  `id` int(12) NOT NULL,
  `property` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `credit` varchar(15) NOT NULL,
  `debit` varchar(15) NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `general_jaunal`
--

INSERT INTO `general_jaunal` (`id`, `property`, `description`, `credit`, `debit`, `ref_no`, `date`) VALUES
(112, '', 'store item out: brooms', '', '200000', '924999235', '2021-08-25 14:35:46'),
(113, '', 'used item: brooms', '200000', '', '924999235', '2021-08-25 14:35:46');

-- --------------------------------------------------------

--
-- Table structure for table `newfiles`
--

CREATE TABLE `newfiles` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'application/octet-stream',
  `description` varchar(255) NOT NULL DEFAULT 'File Transfer',
  `disposition` varchar(255) NOT NULL DEFAULT 'attachment',
  `expires` int(11) NOT NULL DEFAULT 0,
  `cache` varchar(255) NOT NULL DEFAULT 'must-revalidate',
  `pragma` varchar(255) NOT NULL DEFAULT 'public',
  `downloads` int(11) NOT NULL,
  `size` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `newfiles`
--

INSERT INTO `newfiles` (`id`, `filename`, `type`, `description`, `disposition`, `expires`, `cache`, `pragma`, `downloads`, `size`) VALUES
(1, 'cat.zip', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 4, ''),
(2, 'bid.pdf', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 5, ''),
(3, 'flower.png', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 8, ''),
(4, 'ndinda.mp4', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 4, ''),
(6, 'doc.pdf', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 5, '2078'),
(9, 'modern.jpg', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 3, '112198'),
(10, 'gloria.docx', 'application/octet-stream', 'File Transfer', 'attachment', 0, 'must-revalidate', 'public', 2, '423400');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(255) CHARACTER SET latin1 NOT NULL,
  `createuser` varchar(255) DEFAULT NULL,
  `deleteuser` varchar(255) DEFAULT NULL,
  `createbid` varchar(255) DEFAULT NULL,
  `updatebid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `createuser`, `deleteuser`, `createbid`, `updatebid`) VALUES
(1, 'Superuser', '1', '1', '1', '1'),
(2, 'Admin', NULL, NULL, '1', '1'),
(3, 'User', NULL, NULL, '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `petty_balance`
--

CREATE TABLE `petty_balance` (
  `id` int(11) NOT NULL,
  `property` varchar(255) CHARACTER SET latin1 NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `petty_balance`
--

INSERT INTO `petty_balance` (`id`, `property`, `balance`) VALUES
(5, 'church', 900000);

-- --------------------------------------------------------

--
-- Table structure for table `petty_cash`
--

CREATE TABLE `petty_cash` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `ref_no` int(15) NOT NULL,
  `details` varchar(500) NOT NULL,
  `amount` varchar(500) NOT NULL,
  `method` varchar(255) NOT NULL,
  `initiatedby` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `petty_cash`
--

INSERT INTO `petty_cash` (`id`, `date`, `ref_no`, `details`, `amount`, `method`, `initiatedby`) VALUES
(46, '2021-05-21', 66767676, 'petty cash credit', '500000', 'Cash', 'Arinaitwe');

-- --------------------------------------------------------

--
-- Table structure for table `store_out`
--

CREATE TABLE `store_out` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `item` varchar(500) NOT NULL,
  `quantity` varchar(500) NOT NULL,
  `itemsoutvalue` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_out`
--

INSERT INTO `store_out` (`id`, `date`, `item`, `quantity`, `itemsoutvalue`) VALUES
(60, '2021-04-30', 'AC', '1', 100000),
(61, '2021-04-30', 'brooms', '2', 20000),
(62, '2021-05-11', 'brooms', '2', 20000),
(63, '2021-05-11', 'brooms', '1', 10000),
(64, '2021-08-01', 'liquid soap jellycans', '1', 20000),
(65, '2021-08-25', 'brooms', '5', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `store_stock`
--

CREATE TABLE `store_stock` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `item` varchar(500) NOT NULL,
  `quantity` varchar(500) NOT NULL,
  `rate` varchar(500) NOT NULL,
  `total` varchar(500) NOT NULL,
  `quantity_remaining` varchar(500) NOT NULL,
  `itemvalue` int(15) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_stock`
--

INSERT INTO `store_stock` (`id`, `date`, `item`, `quantity`, `rate`, `total`, `quantity_remaining`, `itemvalue`, `status`) VALUES
(12, '2021-08-25', 'brooms', '', '40000', '580000', '10', 400000, '1'),
(15, '2020-11-05', 'paint jellycans', '', '500000', '2500000', '3', 1500000, '1'),
(16, '2020-12-05', 'liquid soap jellycans', '', '20000', '200000', '6', 120000, '1'),
(17, '2020-12-07', 'AC', '', '100000', '1000000', '8', 800000, '1'),
(18, '2021-08-25', 'soap', '', '40000', '600000', '15', 600000, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `Staffid` varchar(255) DEFAULT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT 1,
  `Photo` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'avatar15.jpg',
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `Staffid`, `AdminName`, `UserName`, `FirstName`, `LastName`, `MobileNumber`, `Email`, `Status`, `Photo`, `Password`, `AdminRegdate`) VALUES
(2, 'U001', 'Admin', 'admin', 'John', 'Simith', 770546590, 'john@gmail.com', 1, 'face19.jpg', '81dc9bdb52d04dc20036dbd8313ed055', '2021-07-21 10:18:39'),
(9, 'U002', 'Admin', 'tom', 'Agaba', 'tom', 757537271, 'tom@gmail.com', 1, 'pic_3.jpg', '25d55ad283aa400af464c76d713c07ad', '2021-08-21 07:08:48'),
(29, 'U003', 'User', 'gloria', 'Gloria', 'Tracy', 770546590, 'gloria@gmail.com', 1, 'avatar15.jpg', '81dc9bdb52d04dc20036dbd8313ed055', '2021-07-26 03:28:25');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendancy`
--

CREATE TABLE `tblattendancy` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Sex` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Age` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `District` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Village` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Cdate` date DEFAULT NULL,
  `Temperature` int(10) DEFAULT NULL,
  `Registeredby` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblattendancy`
--

INSERT INTO `tblattendancy` (`ID`, `Name`, `Sex`, `Age`, `District`, `Village`, `Phone`, `Cdate`, `Temperature`, `Registeredby`, `lastname`) VALUES
(6, ' Lucky Gloria ', 'Female', '26', 'London', 'Hillside', 770546590, '2021-08-01', 35, 'John', 'Simith'),
(7, 'Christian Morgan', 'Male', '25', 'Kampala', 'Snowsfall', 770546590, '2021-08-01', 36, 'John', 'Simith'),
(8, 'Amanya Owen', 'Male', '26', 'Kampala', 'Muyenga', 770546590, '2021-08-01', 37, 'John', 'Simith'),
(9, 'Chris Brown', 'Male', '26', 'Marakei', 'Vangaide', 770546590, '2021-08-25', 36, 'John', 'Simith'),
(10, 'Chris Brown', 'Male', '26', 'Marakei', 'Vangaide', 770546590, '2021-08-25', 37, 'John', 'Simith');

-- --------------------------------------------------------

--
-- Table structure for table `tblbaptism`
--

CREATE TABLE `tblbaptism` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `Secondname` varchar(255) DEFAULT NULL,
  `Photo` varchar(255) NOT NULL,
  `Sex` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Occupation` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Parish` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Village` varchar(255) DEFAULT NULL,
  `District` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Registeredby` varchar(200) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `Birthdate` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbaptism`
--

INSERT INTO `tblbaptism` (`ID`, `Name`, `Secondname`, `Photo`, `Sex`, `Occupation`, `Country`, `Parish`, `Village`, `District`, `Email`, `Phone`, `Registeredby`, `lastname`, `Birthdate`, `CreationDate`) VALUES
(73, 'Arinaitwe ', 'Gerald', 'face25.jpg', 'Male', 'Doctor', 'Uganda', 'Bukasa', 'Muyenga', 'Kampala', 'gerald@gmail.com', 770546590, 'Arinaitwe', 'Gerald', '2021-05-17', '2021-05-17 13:21:25'),
(75, 'Perry', 'Tracy', 'face2.jpg', 'Female', NULL, 'Uganda', 'Bukasa', 'Muyenga', 'Kampala', 'petty@gmail.com', 770546590, 'Arinaitwe', 'Gerald', '1998-05-20', '2021-05-19 18:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `tblchristian`
--

CREATE TABLE `tblchristian` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `Code` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `number` int(11) NOT NULL DEFAULT 1,
  `Age` int(11) NOT NULL DEFAULT 1,
  `Sex` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Occupation` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Status` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Parish` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Village` varchar(255) DEFAULT NULL,
  `District` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `Marital` varchar(255) DEFAULT NULL,
  `Registeredby` varchar(200) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `Birthdate` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblchristian`
--

INSERT INTO `tblchristian` (`ID`, `Name`, `Code`, `number`, `Age`, `Sex`, `Occupation`, `Status`, `Country`, `Parish`, `Village`, `District`, `Email`, `Phone`, `Photo`, `Marital`, `Registeredby`, `lastname`, `Birthdate`, `CreationDate`) VALUES
(71, 'Christian Morgan', '0001', 1, 25, 'Male', 'Engineer', 'Baptised', 'Canada', 'Bukasa', 'Snowsfall', 'Kampala', 'morgan@gmail.com', 770546590, 'face22.jpg', 'Married', 'Arinaitwe', 'Gerald', '2021-04-25', '2021-04-25 09:03:16'),
(72, ' Lucky Gloria ', '0002', 1, 26, 'Female', 'Doctor', 'Not-Baptised', 'England', 'Domaria', 'Hillside', 'London', 'gloria@gmail.com', 770546590, 'face26.jpg', 'Single', 'Arinaitwe', 'Gerald', '2021-04-25', '2021-04-25 12:51:03'),
(73, 'Amanya Owen', '0003', 1, 26, 'Male', 'Lawyer', 'Baptised', 'Uganda', 'Bukasa', 'Muyenga', 'Kampala', 'owen@gmail.com', 770546590, 'face21.jpg', 'Single', 'Arinaitwe', 'Gerald', '1995-01-15', '2021-05-19 18:14:14'),
(74, 'Judith Mackline', 'C001', 1, 25, 'Female', 'Doctor', 'Baptised', 'Spain', 'Domaria', 'Kopkamp', 'Kenberg', 'judith@gmail.com', 770546590, 'face11.jpg', 'Single', 'John', 'Simith', '1996-01-01', '2021-08-01 06:51:38'),
(75, 'Chris Brown', 'C005', 1, 26, 'Male', 'Lawyer', 'Baptised', 'India', 'Hopedon', 'Vangaide', 'Marakei', 'chris@gmail.com', 770546590, 'face22.jpg', 'Single', 'John', 'Simith', '1995-01-01', '2021-08-01 07:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

CREATE TABLE `tblcompany` (
  `id` int(11) NOT NULL,
  `regno` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `companyname` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `companyemail` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `country` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `companyphone` int(10) NOT NULL,
  `companyaddress` varchar(255) CHARACTER SET latin1 NOT NULL,
  `companylogo` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'avatar15.jpg',
  `status` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`id`, `regno`, `companyname`, `companyemail`, `country`, `companyphone`, `companyaddress`, `companylogo`, `status`, `creationdate`) VALUES
(4, '1002', 'St. Paul Church', 'stpaul@gmail.com', 'Canada', 770546590, 'Park rise', 'churchlogo.ico', '1', '2021-02-02 12:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `tblitems`
--

CREATE TABLE `tblitems` (
  `id` int(11) NOT NULL,
  `item` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblitems`
--

INSERT INTO `tblitems` (`id`, `item`, `description`, `Creationdate`) VALUES
(1, 'brooms', 'sweeping brooms', '2021-04-30 01:15:55'),
(2, 'soap', 'washing soap', '2021-04-30 01:23:21'),
(3, 'Papers', 'Used in printing documents', '2021-07-26 03:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `tblloans`
--

CREATE TABLE `tblloans` (
  `id` int(11) NOT NULL,
  `bankname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `phone` int(10) NOT NULL,
  `promisedamount` int(11) NOT NULL,
  `loanamount` int(11) NOT NULL,
  `currency` varchar(255) CHARACTER SET latin1 NOT NULL,
  `loandescription` varchar(255) CHARACTER SET latin1 NOT NULL,
  `date` date NOT NULL,
  `depositedby` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblloans`
--

INSERT INTO `tblloans` (`id`, `bankname`, `phone`, `promisedamount`, `loanamount`, `currency`, `loandescription`, `date`, `depositedby`) VALUES
(7, 'Christian Morgan', 770546590, 8000, 6000, 'USD', 'church construction', '2021-08-01', 'John'),
(8, 'Lucky Gloria', 770546590, 10000, 9000, 'USD', 'Church contruction', '2021-08-01', 'John');

-- --------------------------------------------------------

--
-- Table structure for table `tblmarriage`
--

CREATE TABLE `tblmarriage` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `Age` int(11) NOT NULL DEFAULT 1,
  `Sex` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Occupation` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Country` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Parish` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Village` varchar(255) DEFAULT NULL,
  `District` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci DEFAULT NULL,
  `Phone` int(10) DEFAULT NULL,
  `Registeredby` varchar(200) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `Birthdate` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblmarriage`
--

INSERT INTO `tblmarriage` (`ID`, `Name`, `Age`, `Sex`, `Occupation`, `Country`, `Parish`, `Village`, `District`, `Email`, `Phone`, `Registeredby`, `lastname`, `Birthdate`, `CreationDate`) VALUES
(73, 'Arinaitwe Gerald', 30, 'Male', 'Doctor', 'Uganda', 'Bukasa', 'Muyenga', 'Kampala', 'gerald@gmail.com', 770546590, 'Arinaitwe', 'Gerald', '2021-05-17', '2021-05-17 13:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `tblnotification`
--

CREATE TABLE `tblnotification` (
  `id` int(11) NOT NULL,
  `nature` varchar(255) CHARACTER SET latin1 NOT NULL,
  `amount` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(255) CHARACTER SET latin1 NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblnotification`
--

INSERT INTO `tblnotification` (`id`, `nature`, `amount`, `total`, `status`, `creationdate`) VALUES
(1, 'debited', 500000, 1500000, 'off', '2021-05-14 08:14:53'),
(2, 'credited', 1000000, 2000000, 'on', '2021-05-14 08:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbloffertory`
--

CREATE TABLE `tbloffertory` (
  `id` int(11) NOT NULL,
  `currency` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `offertoryamount` int(10) NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `depositedby` varchar(255) CHARACTER SET latin1 NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'on',
  `date` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbloffertory`
--

INSERT INTO `tbloffertory` (`id`, `currency`, `offertoryamount`, `description`, `depositedby`, `status`, `date`) VALUES
(1, 'USD', 500000, 'Monday evening mass offertories', 'Morgan', 'off', '2021-05-17'),
(2, 'USD', 200000, 'Sunday mass offertories', 'Morgan', 'on', '2021-05-16'),
(3, 'USD', 5000, 'Sunday mass offertories', 'John', 'on', '2021-08-01'),
(4, 'USD', 500000, 'Sunday mass offertory', 'John', 'off', '2021-08-25');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayments`
--

CREATE TABLE `tblpayments` (
  `id` int(11) NOT NULL,
  `companyname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `paidamount` int(11) NOT NULL,
  `transaction` int(11) NOT NULL,
  `paiddate` date NOT NULL,
  `attachment` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpayments`
--

INSERT INTO `tblpayments` (`id`, `companyname`, `paidamount`, `transaction`, `paiddate`, `attachment`) VALUES
(3, 'compconsult', 500000, 4555, '2021-03-22', 'gaerden.jpg'),
(4, 'Mukwano', 0, 990, '2021-03-04', 'compconsult.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblservice`
--

CREATE TABLE `tblservice` (
  `ID` int(10) NOT NULL,
  `ServiceName` varchar(200) DEFAULT NULL,
  `population` int(11) DEFAULT NULL,
  `SerDes` varchar(250) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblservice`
--

INSERT INTO `tblservice` (`ID`, `ServiceName`, `population`, `SerDes`, `CreationDate`) VALUES
(7, 'Public', 30, 'Owned by government ', '2020-07-25 13:19:29'),
(8, 'Private ', 50, 'Owned by  people', '2020-07-25 13:20:38'),
(25, 'NGO', 75, 'Non government organisation', '2020-11-23 11:41:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expence`
--
ALTER TABLE `expence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensecategory`
--
ALTER TABLE `expensecategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensename`
--
ALTER TABLE `expensename`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_jaunal`
--
ALTER TABLE `general_jaunal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newfiles`
--
ALTER TABLE `newfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petty_balance`
--
ALTER TABLE `petty_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petty_cash`
--
ALTER TABLE `petty_cash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_out`
--
ALTER TABLE `store_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_stock`
--
ALTER TABLE `store_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblattendancy`
--
ALTER TABLE `tblattendancy`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblbaptism`
--
ALTER TABLE `tblbaptism`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblchristian`
--
ALTER TABLE `tblchristian`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcompany`
--
ALTER TABLE `tblcompany`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblitems`
--
ALTER TABLE `tblitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblloans`
--
ALTER TABLE `tblloans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblmarriage`
--
ALTER TABLE `tblmarriage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblnotification`
--
ALTER TABLE `tblnotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbloffertory`
--
ALTER TABLE `tbloffertory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblservice`
--
ALTER TABLE `tblservice`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expence`
--
ALTER TABLE `expence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `expensecategory`
--
ALTER TABLE `expensecategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expensename`
--
ALTER TABLE `expensename`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `general_jaunal`
--
ALTER TABLE `general_jaunal`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `newfiles`
--
ALTER TABLE `newfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `petty_balance`
--
ALTER TABLE `petty_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `petty_cash`
--
ALTER TABLE `petty_cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `store_out`
--
ALTER TABLE `store_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `store_stock`
--
ALTER TABLE `store_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblattendancy`
--
ALTER TABLE `tblattendancy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblbaptism`
--
ALTER TABLE `tblbaptism`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tblchristian`
--
ALTER TABLE `tblchristian`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tblcompany`
--
ALTER TABLE `tblcompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblitems`
--
ALTER TABLE `tblitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblloans`
--
ALTER TABLE `tblloans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblmarriage`
--
ALTER TABLE `tblmarriage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tblnotification`
--
ALTER TABLE `tblnotification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbloffertory`
--
ALTER TABLE `tbloffertory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblpayments`
--
ALTER TABLE `tblpayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblservice`
--
ALTER TABLE `tblservice`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
