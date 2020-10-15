-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 28, 2019 at 09:52 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`) VALUES
(10, 'Hand Made', 'This Category Contains Hand Made Items', 1),
(11, 'Computers', 'This Category Contains Computers,Laptops,Desktops Items', 2),
(12, 'Phones', 'This Category Contains iPhones,Samsung Items', 3),
(13, 'Clothes', 'This Category Contains Clothes Items', 4),
(15, 'Sports', 'This Category Contains Sports', 6),
(16, 'Tv\'s', 'This Category Contains Tv\'s', 7),
(18, 'Health care', 'This Category Contain Health Care Tools', 9);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `items_comment` (`item_id`),
  KEY `items_comment_userId` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `item_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Adding_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT 'layout/images/unknown.png',
  `Status` varchar(255) NOT NULL,
  `Cat_Id` int(11) DEFAULT NULL,
  `Member_Id` int(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `selled` int(11) NOT NULL DEFAULT '0' COMMENT 'For Check If Item Is Selled Or Not Selled = 1 And Not Selled = 0',
  PRIMARY KEY (`item_Id`),
  KEY `num_1` (`Member_Id`),
  KEY `cat_1` (`Cat_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_Id`, `Name`, `Description`, `Price`, `Adding_Date`, `Country_Made`, `Image`, `Status`, `Cat_Id`, `Member_Id`, `Approve`, `selled`) VALUES
(13, 'Nike Children Shoes', 'This Shoes For Children With 40 Size And With Nike Marka', '100$', '2019-12-25', 'Bahamas', 'layout/images/69067694373728_alexander-rotker-l8p1aWZqHvE-unsplash.jpg', '1', 13, 216, 1, 1),
(14, 'MacBook 2019', 'This Is MacBook Laptop Made In China With Powerful Material And Hard Worker Processor', '1000$', '2019-12-25', 'China', 'layout/images/42185278648661_derick-david-paSLTZpHCdo-unsplash.jpg', '1', 11, 216, 1, 1),
(15, 'Hand Made Products', 'This Is Material Hand Made Products With High Quality Of Cottons', '20$', '2019-12-25', 'Anguilla', 'layout/images/67687435724364_ella-jardim-jmGH6r8K5Ak-unsplash.jpg', '2', 10, 217, 1, 0),
(20, 'SwitherLand Clock', 'This Is High Quality Clock Made From High Quality Metal And Materials Maded In Switherland', '1000$', '2019-12-26', 'Swaziland', 'layout/images/12844017435343_rachit-tank-2cFZ_FB08UM-unsplash.jpg', '1', 11, 210, 1, 1),
(21, 'French Perfume', 'This Is High Quality French Perfume Maded From High Quality And famous Planet', '150$', '2019-12-26', 'France', 'layout/images/27740567376895_daria-nepriakhina-bUjiFIn3PLk-unsplash.jpg', '1', 18, 210, 1, 0),
(28, 'soap', 'This Is High Quality Soap That Made In Egypt With High Quality Material', '5$', '2019-12-26', 'Egypt', 'layout/images/76989624279648_brandless-Kn_SPQKgu20-unsplash.jpg', '1', 18, 217, 1, 1),
(30, 'Mouse', 'This Is hand Made High Quality Bags That Made In Egypt With High Quality Material', '1000$', '2019-12-28', 'Afghanistan', 'layout/images/50643411532852_emiliano-cicero-xo-MJALGPrI-unsplash.jpg', '1', 11, 210, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_Id` int(11) NOT NULL AUTO_INCREMENT,
  `itemName` varchar(255) NOT NULL,
  `itemPrice` varchar(255) NOT NULL,
  `itemCustomer` varchar(255) NOT NULL,
  `itemSeller` varchar(255) NOT NULL,
  `OrderDate` date NOT NULL,
  PRIMARY KEY (`order_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_Id`, `itemName`, `itemPrice`, `itemCustomer`, `itemSeller`, `OrderDate`) VALUES
(23, 'Hand Made Products', '20$', 'memory2', 'Said El-Sayed', '2019-12-28'),
(24, 'Hand Made Products', '20$', 'memory2', 'Said El-Sayed', '2019-12-28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_Name` varchar(255) NOT NULL,
  `user_Password` varchar(255) NOT NULL,
  `user_Email` varchar(255) NOT NULL,
  `user_fullName` varchar(255) NOT NULL,
  `group_Id` int(11) NOT NULL DEFAULT '0',
  `reg_status` int(11) NOT NULL DEFAULT '0',
  `reg_date` date NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`user_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_Id`, `user_Name`, `user_Password`, `user_Email`, `user_fullName`, `group_Id`, `reg_status`, `reg_date`, `image`) VALUES
(1, 'Magdy Mohammed', '669e9c979154d7372d989686b3c61419e2747654', 'magdymohammed37@yahoo.com', 'Magdy Mohammed', 1, 1, '2011-01-26', ''),
(210, 'memory2', '601f1889667efaebb33b8c12572835da3f027f78', 'memory20101989@yahoo.com', 'Memory Bas', 0, 1, '2019-12-18', '134903_5c6f20b629806.png'),
(212, 'Adham Hossam', '601f1889667efaebb33b8c12572835da3f027f78', 'adham@yahoo.com', 'Adham Hussien Mohammed', 0, 1, '2019-12-18', ''),
(213, 'Daloa', '601f1889667efaebb33b8c12572835da3f027f78', 'mego20101989@gmail.com', 'ewcewcewcewc', 0, 1, '2019-12-18', '656798_5c6f20fb6ffd2.png'),
(216, 'Hossam Mohammed ', '601f1889667efaebb33b8c12572835da3f027f78', 'HossamMohammed2010@yahoo.com', 'Hossam Mohammed Ahmed El-Deep', 0, 1, '2019-12-25', 'default.png'),
(217, 'Said El-Sayed', '601f1889667efaebb33b8c12572835da3f027f78', 'SaidElSayed2010@yahoo.com', 'Said El-Sayed Ahmed', 0, 1, '2019-12-25', 'default.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_Id`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `num_1` FOREIGN KEY (`Member_Id`) REFERENCES `users` (`user_Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
