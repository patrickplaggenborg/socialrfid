-- Social RFID Database Import
-- Modernized schema with InnoDB and utf8mb4
-- Full import with historical data

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

--
-- Dumping data for table `srfid_epc`
--

INSERT INTO `srfid_epc` (`uc`, `name`, `image`, `manufacturer`, `year`, `country`, `reseller`, `selldate`, `description`) VALUES
('B7C790A185F7A3', '#185 Roller skates', 'skate_med', 'Fisher-Price Toys', 1984, 'Taiwan', 'Bart Smit', '2006-08-16 22:11:05', 'Fisher-Price answered children\'s needs with it\'s first pair of skates, the #185 roller skates. These skates are perfect for teaching children how to skate, and are adjustable to grow with your child. The skates have a switch that allow forward rolling only for beginners. The switch turns \"off\" for forward and backward movement for more advanced skaters. These skates have stoppers both on the heels and toe, hard plastic toe and heel protectors, and a Velcro fastener on the ankle straps. Each skate fits both the left and right foot, and a simple switch adjusts the skates to shoe sizes 6-12. Designed for children ages 3 to 6 years old.'),
('37892DDA23978E', 'Military tank', 'tank_med', 'CH Toys', 1981, 'China', 'Intertoys', '2006-08-18 17:46:46', 'Plastic military toy tank'),
('98764BE89A213E', 'Stuffed dog', 'animal_med', 'Plushy Inc.', 1984, 'China', 'Intertoys', '2006-08-18 17:46:52', 'Small stuffed dog.');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=6;

--
-- Dumping data for table `srfid_objects`
--

INSERT INTO `srfid_objects` (`id`, `uc`, `db`) VALUES
(2, 'thing:522LLA', 2),
(3, 'B7C790A185F7A3', 1),
(4, '37892DDA23978E', 1),
(5, '98764BE89A213E', 1);

-- --------------------------------------------------------

--
-- Table structure for table `srfid_stories`
--

CREATE TABLE IF NOT EXISTS `srfid_stories` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uc` varchar(24) NOT NULL DEFAULT '',
  `filename` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=105;

--
-- Dumping data for table `srfid_stories`
--

INSERT INTO `srfid_stories` (`id`, `uc`, `filename`) VALUES
(1, 'thing:522LLA', 'puppet01'),
(2, 'thing:522LLA', 'puppet02'),
(3, 'thing:522LLA', 'puppet03'),
(4, 'thing:522LLA', 'puppet04'),
(5, 'thing:522LLA', 'puppet05'),
(6, 'thing:522LLA', 'puppet06'),
(7, 'thing:522LLA', 'puppet07'),
(8, 'thing:522LLA', 'puppet08'),
(9, 'B7C790A185F7A3', 'skate01'),
(10, 'B7C790A185F7A3', 'skate02'),
(11, 'B7C790A185F7A3', 'skate03'),
(12, 'B7C790A185F7A3', 'skate04'),
(13, 'B7C790A185F7A3', 'skate05'),
(14, 'B7C790A185F7A3', 'skate06'),
(15, 'B7C790A185F7A3', 'skate07'),
(16, 'B7C790A185F7A3', 'skate08'),
(17, 'B7C790A185F7A3', 'skate09'),
(18, 'B7C790A185F7A3', 'skate10'),
(19, 'B7C790A185F7A3', 'skate11'),
(20, 'B7C790A185F7A3', 'skate12'),
(21, 'B7C790A185F7A3', 'skate13'),
(22, '37892DDA23978E', 'tank01'),
(23, '37892DDA23978E', 'tank02'),
(24, '37892DDA23978E', 'tank03'),
(25, '37892DDA23978E', 'tank04'),
(26, '37892DDA23978E', 'tank05'),
(27, '37892DDA23978E', 'tank06'),
(28, '37892DDA23978E', 'tank07'),
(29, '37892DDA23978E', 'tank08'),
(30, '37892DDA23978E', 'tank09'),
(31, '98764BE89A213E', 'animal01'),
(32, '98764BE89A213E', 'animal02'),
(33, '98764BE89A213E', 'animal03'),
(34, '98764BE89A213E', 'animal04'),
(35, '98764BE89A213E', 'animal05'),
(36, '98764BE89A213E', 'animal06'),
(37, '98764BE89A213E', 'animal07'),
(38, '98764BE89A213E', 'animal08'),
(39, '98764BE89A213E', 'animal09'),
(40, '98764BE89A213E', 'animal10'),
(85, '', '_1158555576'),
(86, '', '_1158555607'),
(87, '', '_1158555609'),
(88, '', '_1158555611'),
(89, '', '_1158555613'),
(90, '', '_1158555614'),
(91, '', '_1158556565'),
(83, '', '_1158555574'),
(84, '', '_1158555576'),
(82, '37892DDA23978E', '37892dda23978e_1158083581'),
(92, '', '_1313785473'),
(93, '', '_1347478488'),
(94, '', '_1395916738'),
(95, '', '_1395916739'),
(96, '', '_1395937611'),
(97, '', '_1395937612'),
(98, '', '_1398012955'),
(99, '', '_1398012955'),
(100, '', '_1398622085'),
(101, '', '_1398622086'),
(102, '', '_1399231361'),
(103, '', '_1399231362'),
(104, '', '_1500838634');

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

--
-- Dumping data for table `srfid_thing`
--

INSERT INTO `srfid_thing` (`uc`, `name`, `image`, `maker`, `year`, `country`, `description`) VALUES
('thing:522LLA', 'Hand puppet', 'puppet_med', 'Patrick Plaggenborg', 1988, 'The Netherlands', 'Hand puppet made at primary school.');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

