-- Social RFID Database Schema
-- Modernized schema with InnoDB and utf8mb4
-- Empty schema for reference/documentation

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `srfid_epc`
--

CREATE TABLE IF NOT EXISTS `srfid_epc` (
  `uc` varchar(24) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `image` varchar(32) NOT NULL DEFAULT '',
  `manufacturer` varchar(48) NOT NULL DEFAULT '',
  `year` mediumint(4) NOT NULL DEFAULT '0',
  `country` varchar(48) NOT NULL DEFAULT '',
  `reseller` varchar(48) NOT NULL DEFAULT '',
  `selldate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` mediumtext NOT NULL,
  UNIQUE KEY `epc` (`uc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `srfid_objects`
--

CREATE TABLE IF NOT EXISTS `srfid_objects` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `uc` varchar(24) NOT NULL DEFAULT '',
  `db` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ucode` (`uc`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `srfid_stories`
--

CREATE TABLE IF NOT EXISTS `srfid_stories` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uc` varchar(24) NOT NULL DEFAULT '',
  `filename` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `srfid_thing`
--

CREATE TABLE IF NOT EXISTS `srfid_thing` (
  `uc` varchar(24) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `image` varchar(32) NOT NULL DEFAULT '',
  `maker` varchar(48) NOT NULL DEFAULT '',
  `year` mediumint(4) NOT NULL DEFAULT '0',
  `country` varchar(32) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  UNIQUE KEY `thinglink` (`uc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

