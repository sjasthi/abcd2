-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2023 at 08:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abcd_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `user_id` int(11) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `country` varchar(50) NOT NULL,
  `facebook` varchar(100) NOT NULL,
  `instagram` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `whatsapp` varchar(100) NOT NULL,
  `art_site` varchar(100) NOT NULL,
  `other` varchar(100) NOT NULL,
  `approval_status` varchar(10) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`user_id`, `profile_picture`, `description`, `country`, `facebook`, `instagram`, `twitter`, `whatsapp`, `art_site`, `other`, `approval_status`) VALUES
(1, 'maddypfp.png', 'This is a test description for an artist. I can tell users what I make, more about who I am, etc.', 'United States', 'testfacebook', 'testinstagram', 'testtwitter', 'testwhatsapp', 'testartsite', 'other', 'pending');


-- --------------------------------------------------------

--
-- Table structure for table `dresses`
--

CREATE TABLE `dresses` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `did_you_know` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT NULL COMMENT 'regional, religious, people, dresses, festivals, other',
  `type` varchar(10) NOT NULL COMMENT 'boys, girls, men, women, other',
  `state_name` varchar(100) DEFAULT NULL,
  `key_words` varchar(500) DEFAULT NULL COMMENT 'any key words separate by comma',
  `image_url` varchar(100) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'proposed' COMMENT 'proposed, approved, writeup_done, art_work_done, designed, completed',
  `notes` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dresses`
--

INSERT INTO `dresses` (`id`, `name`, `description`, `did_you_know`, `category`, `type`, `state_name`, `key_words`, `image_url`, `status`, `notes`) VALUES
(1, 'Saree2', 'This is being updated in the JQuery table', 'did_you_know', 'category', 'hello', 'dd', 'hello', 'Slide1.PNG', 'testing', 'notes'),
(2, 'Saree', 'this is popular dress', 'there are 100 types of draping', 'sf', 'sdf', '', '', 'Slide2.PNG', 'art_work_done', ''),
(3, 'Suit', 'Suit is worn by both men and women', 'asdfa', 'asf', 'asf', 'asf', 'asdf', 'Slide3.PNG', 'art_work_done', 'sdf'),
(4, 'Slide4', 'Slide4', '', '', '', '', '', 'Slide4.PNG', 'art_work_done', ''),
(5, 'Testing', 'Slide5', 'yes', '', 'boys', '', '', 'Slide5.PNG', 'art_work_done', ''),
(6, 'Slide6', 'Slide6', '', '', '', '', '', 'Slide6.PNG', 'art_work_done', ''),
(7, 'Slide7', 'Slide7', '', '', '', '', '', 'Slide7.PNG', 'art_work_done', ''),
(8, 'Slide8', 'Slide8', '', '', '', '', '', 'Slide8.PNG', 'art_work_done', ''),
(9, 'Slide9', 'Slide9', '', '', '', '', '', 'Slide9.PNG', 'art_work_done', ''),
(10, 'Slide10', 'Slide10', '', '', '', '', '', 'Slide10.PNG', 'art_work_done', ''),
(11, 'Slide11', 'Slide11', '', '', '', '', '', 'Slide11.PNG', 'art_work_done', ''),
(12, 'Slide12', 'Slide12', '', '', '', '', '', 'Slide12.PNG', 'art_work_done', ''),
(13, 'Slide13', 'Slide13', '', '', '', '', '', 'Slide13.PNG', 'art_work_done', ''),
(14, 'Slide14', 'Slide14', '', '', '', '', '', 'Slide14.PNG', 'art_work_done', ''),
(15, 'Slide15', 'Slide15', '', '', '', '', '', 'Slide15.PNG', 'art_work_done', ''),
(16, 'Slide16', 'Slide16', '', '', '', '', '', 'Slide16.PNG', 'art_work_done', ''),
(17, 'Slide17', 'Slide17', '', '', '', '', '', 'Slide17.PNG', 'art_work_done', ''),
(18, 'Slide18', 'Slide18', '', '', '', '', '', 'Slide18.PNG', 'art_work_done', ''),
(19, 'Slide19', 'Slide19', '', '', '', '', '', 'Slide19.PNG', 'art_work_done', ''),
(20, 'Slide20', 'Slide20', '', '', '', '', '', 'Slide20.PNG', 'art_work_done', ''),
(21, 'Slide21', 'Slide21', '', '', '', '', '', 'Slide21.PNG', 'art_work_done', ''),
(22, 'Slide22', 'Slide22', '', '', '', '', '', 'Slide22.PNG', 'art_work_done', ''),
(23, 'Slide23', 'Slide23', '', '', '', '', '', 'Slide23.PNG', 'art_work_done', ''),
(24, 'Slide24', 'Slide24', '', '', '', '', '', 'Slide24.PNG', 'art_work_done', ''),
(25, 'Slide25', 'Slide25', '', '', '', '', '', 'Slide25.PNG', 'art_work_done', ''),
(26, 'Slide26', 'Slide26', '', '', '', '', '', 'Slide26.PNG', 'art_work_done', ''),
(27, 'Slide27', 'Slide27', '', '', '', '', '', 'Slide27.PNG', 'art_work_done', ''),
(28, 'Slide28', 'Slide28', '', '', '', '', '', 'Slide28.PNG', 'art_work_done', ''),
(29, 'Slide29', 'Slide29', '', '', '', '', '', 'Slide29.PNG', 'art_work_done', ''),
(30, 'Slide30', 'Slide30', '', '', '', '', '', 'Slide30.PNG', 'art_work_done', ''),
(31, 'Slide31', 'Slide31', '', '', '', '', '', 'Slide31.PNG', 'art_work_done', ''),
(32, 'Slide32', 'Slide32', '', '', '', '', '', 'Slide32.PNG', 'art_work_done', ''),
(33, 'Slide33', 'Slide33', '', '', '', '', '', 'Slide33.PNG', 'art_work_done', ''),
(34, 'Slide34', 'Slide34', '', '', '', '', '', 'Slide34.PNG', 'art_work_done', ''),
(35, 'Slide35', 'Slide35', '', '', '', '', '', 'Slide35.PNG', 'art_work_done', ''),
(36, 'Slide36', 'Slide36', '', '', '', '', '', 'Slide36.PNG', 'art_work_done', ''),
(37, 'Slide37', 'Slide37', '', '', '', '', '', 'Slide37.PNG', 'art_work_done', ''),
(38, 'Slide38', 'Slide38', '', '', '', '', '', 'Slide38.PNG', 'art_work_done', ''),
(39, 'Slide39', 'Slide39', '', '', '', '', '', 'Slide39.PNG', 'art_work_done', ''),
(40, 'Slide40', 'Slide40', '', '', '', '', '', 'Slide40.PNG', 'art_work_done', ''),
(41, 'Slide41', 'Slide41', '', '', '', '', '', 'Slide41.PNG', 'art_work_done', ''),
(42, 'Slide42', 'Slide42', '', '', '', '', '', 'Slide42.PNG', 'art_work_done', ''),
(43, 'Slide43', 'Slide43', '', '', '', '', '', 'Slide43.PNG', 'art_work_done', ''),
(44, 'Slide44', 'Slide44', '', '', '', '', '', 'Slide44.PNG', 'art_work_done', ''),
(45, 'Slide45', 'Slide45', '', '', '', '', '', 'Slide45.PNG', 'art_work_done', ''),
(46, 'Slide46', 'Slide46', '', '', '', '', '', 'Slide46.PNG', 'art_work_done', ''),
(47, 'Slide47', 'Slide47', '', '', '', '', '', 'Slide47.PNG', 'art_work_done', ''),
(48, 'Slide48', 'Slide48', '', '', '', '', '', 'Slide48.PNG', 'art_work_done', ''),
(49, 'Slide49', 'Slide49', '', '', '', '', '', 'Slide49.PNG', 'art_work_done', ''),
(50, 'Slide50', 'Slide50', '', '', '', '', '', 'Slide50.PNG', 'art_work_done', ''),
(51, 'Slide51', 'Slide51', '', '', '', '', '', 'Slide51.PNG', 'art_work_done', ''),
(52, 'Slide52', 'Slide52', '', '', '', '', '', 'Slide52.PNG', 'art_work_done', ''),
(53, 'Slide53', 'Slide53', '', '', '', '', '', 'Slide53.PNG', 'art_work_done', ''),
(54, 'Slide54', 'Slide54', '', '', '', '', '', 'Slide54.PNG', 'art_work_done', ''),
(55, 'Slide55', 'Slide55', '', '', '', '', '', 'Slide55.PNG', 'art_work_done', ''),
(56, 'Slide56', 'Slide56', '', '', '', '', '', 'Slide56.PNG', 'art_work_done', ''),
(57, 'Slide57', 'Slide57', '', '', '', '', '', 'Slide57.PNG', 'art_work_done', ''),
(58, 'Slide58', 'Slide58', '', '', '', '', '', 'Slide58.PNG', 'art_work_done', ''),
(59, 'Slide59', 'Slide59', '', '', '', '', '', 'Slide59.PNG', 'art_work_done', ''),
(60, 'Slide60', 'Slide60', '', '', '', '', '', 'Slide60.PNG', 'art_work_done', ''),
(61, 'Slide61', 'Slide61', '', '', '', '', '', 'Slide61.PNG', 'art_work_done', ''),
(62, 'Slide62', 'Slide62', '', '', '', '', '', 'Slide62.PNG', 'art_work_done', ''),
(63, 'Slide63', 'Slide63', '', '', '', '', '', 'Slide63.PNG', 'art_work_done', ''),
(64, 'Slide64', 'Slide64', '', '', '', '', '', 'Slide64.PNG', 'art_work_done', ''),
(65, 'Slide65', 'Slide65', '', '', '', '', '', 'Slide65.PNG', 'art_work_done', ''),
(66, 'Slide66', 'Slide66', '', '', '', '', '', 'Slide66.PNG', 'art_work_done', ''),
(67, 'Slide67', 'Slide67', '', '', '', '', '', 'Slide67.PNG', 'art_work_done', ''),
(68, 'Slide68', 'Slide68', '', '', '', '', '', 'Slide68.PNG', 'art_work_done', ''),
(69, 'Slide69', 'Slide69', '', '', '', '', '', 'Slide69.PNG', 'art_work_done', ''),
(70, 'Slide70', 'Slide70', '', '', '', '', '', 'Slide70.PNG', 'art_work_done', ''),
(71, 'Slide71', 'Slide71', '', '', '', '', '', 'Slide71.PNG', 'art_work_done', ''),
(72, 'Slide72', 'Slide72', '', '', '', '', '', 'Slide72.PNG', 'art_work_done', ''),
(73, 'Slide73', 'Slide73', '', '', '', '', '', 'Slide73.PNG', 'art_work_done', ''),
(74, 'Slide74', 'Slide74', '', '', '', '', '', 'Slide74.PNG', 'art_work_done', ''),
(75, 'Slide75', 'Slide75', '', '', '', '', '', 'Slide75.PNG', 'art_work_done', ''),
(76, 'Slide76', 'Slide76', '', '', '', '', '', 'Slide76.PNG', 'art_work_done', ''),
(77, 'Slide77', 'Slide77', '', '', '', '', '', 'Slide77.PNG', 'art_work_done', ''),
(78, 'Slide78', 'Slide78', '', '', '', '', '', 'Slide78.PNG', 'art_work_done', ''),
(79, 'Slide79', 'Slide79', '', '', '', '', '', 'Slide79.PNG', 'art_work_done', ''),
(80, 'Slide80', 'Slide80', '', '', '', '', '', 'Slide80.PNG', 'art_work_done', ''),
(81, 'Slide81', 'Slide81', '', '', '', '', '', 'Slide81.PNG', 'art_work_done', ''),
(82, 'Slide82', 'Slide82', '', '', '', '', '', 'Slide82.PNG', 'art_work_done', ''),
(83, 'Slide83', 'Slide83', '', '', '', '', '', 'Slide83.PNG', 'art_work_done', ''),
(84, 'Slide84', 'Slide84', '', '', '', '', '', 'Slide84.PNG', 'art_work_done', ''),
(85, 'Slide85', 'Slide85', '', '', '', '', '', 'Slide85.PNG', 'art_work_done', ''),
(86, 'Slide86', 'Slide86', '', '', '', '', '', 'Slide86.PNG', 'art_work_done', ''),
(87, 'Slide87', 'Slide87', '', '', '', '', '', 'Slide87.PNG', 'art_work_done', ''),
(88, 'Slide88', 'Slide88', '', '', '', '', '', 'Slide88.PNG', 'art_work_done', ''),
(89, 'Slide89', 'Slide89', '', '', '', '', '', 'Slide89.PNG', 'art_work_done', ''),
(90, 'Slide90', 'Slide90', '', '', '', '', '', 'Slide90.PNG', 'art_work_done', ''),
(91, 'Slide91', 'Slide91', '', '', '', '', '', 'Slide91.PNG', 'art_work_done', ''),
(92, 'Slide92', 'Slide92', '', '', '', '', '', 'Slide92.PNG', 'art_work_done', ''),
(93, 'Slide93', 'Slide93', '', '', '', '', '', 'Slide93.PNG', 'art_work_done', ''),
(94, 'Slide94', 'Slide94', '', '', '', '', '', 'Slide94.PNG', 'art_work_done', ''),
(95, 'Slide95', 'Slide95', '', '', '', '', '', 'Slide95.PNG', 'art_work_done', ''),
(96, 'Slide96', 'Slide96', '', '', '', '', '', 'Slide96.PNG', 'art_work_done', ''),
(97, 'Slide97', 'Slide97', '', '', '', '', '', 'Slide97.PNG', 'art_work_done', ''),
(98, 'Slide98', 'Slide98', '', '', '', '', '', 'Slide98.PNG', 'art_work_done', ''),
(99, 'Slide99', 'Slide99', '', '', '', '', '', 'Slide99.PNG', 'art_work_done', ''),
(100, 'Slide100', 'Slide100', '', '', '', '', '', 'Slide100.PNG', 'art_work_done', ''),
(101, 'Slide101', 'Slide101', '', '', '', '', '', 'Slide101.PNG', 'art_work_done', ''),
(102, 'Slide102', 'Slide102', '', '', '', '', '', 'Slide102.PNG', 'art_work_done', ''),
(103, 'Slide103', 'Slide103', '', '', '', '', '', 'Slide103.PNG', 'art_work_done', ''),
(104, 'Slide104', 'Slide104', '', '', '', '', '', 'Slide104.PNG', 'art_work_done', ''),
(105, 'Slide105', 'Slide105', '', '', '', '', '', 'Slide105.PNG', 'art_work_done', ''),
(106, 'Slide106', 'Slide106', '', '', '', '', '', 'Slide106.PNG', 'art_work_done', ''),
(107, 'Slide107', 'Slide107', '', '', '', '', '', 'Slide107.PNG', 'art_work_done', ''),
(108, 'Slide108', 'Slide108', '', '', '', '', '', 'Slide108.PNG', 'art_work_done', ''),
(109, 'Slide109', 'Slide109', '', '', '', '', '', 'Slide109.PNG', 'art_work_done', ''),
(110, 'Slide110', 'Slide110', '', '', '', '', '', 'Slide110.PNG', 'art_work_done', ''),
(111, 'Slide111', 'Slide111', '', '', '', '', '', 'Slide111.PNG', 'art_work_done', ''),
(112, 'Slide112', 'Slide112', '', '', '', '', '', 'Slide112.PNG', 'art_work_done', ''),
(113, 'Slide113', 'Slide113', '', '', '', '', '', 'Slide113.PNG', 'art_work_done', ''),
(114, 'Slide114', 'Slide114', '', '', '', '', '', 'Slide114.PNG', 'art_work_done', ''),
(115, 'Slide115', 'Slide115', '', '', '', '', '', 'Slide115.PNG', 'art_work_done', ''),
(116, 'Slide116', 'Slide116', '', '', '', '', '', 'Slide116.PNG', 'art_work_done', ''),
(117, 'Slide117', 'Slide117', '', '', '', '', '', 'Slide117.PNG', 'art_work_done', ''),
(118, 'Slide118', 'Slide118', '', '', '', '', '', 'Slide118.PNG', 'art_work_done', ''),
(119, 'Slide119', 'Slide119', '', '', '', '', '', 'Slide119.PNG', 'art_work_done', ''),
(120, 'Slide120', 'Slide120', '', '', '', '', '', 'Slide120.PNG', 'art_work_done', ''),
(121, 'Slide121', 'Slide121', '', '', '', '', '', 'Slide121.PNG', 'art_work_done', ''),
(122, 'Slide122', 'Slide122', '', '', '', '', '', 'Slide122.PNG', 'art_work_done', ''),
(123, 'Slide123', 'Slide123', '', '', '', '', '', 'Slide123.PNG', 'art_work_done', ''),
(124, 'Slide124', 'Slide124', '', '', '', '', '', 'Slide124.PNG', 'art_work_done', ''),
(125, 'Slide125', 'Slide125', '', '', '', '', '', 'Slide125.PNG', 'art_work_done', ''),
(126, 'Slide126', 'Slide126', '', '', '', '', '', 'Slide126.PNG', 'art_work_done', ''),
(127, 'Slide127', 'Slide127', '', '', '', '', '', 'Slide127.PNG', 'art_work_done', ''),
(128, 'Slide128', 'Slide128', '', '', '', '', '', 'Slide128.PNG', 'art_work_done', ''),
(129, 'Slide129', 'Slide129', '', '', '', '', '', 'Slide129.PNG', 'art_work_done', ''),
(130, 'Slide130', 'Slide130', '', '', '', '', '', 'Slide130.PNG', 'art_work_done', ''),
(131, 'Slide131', 'Slide131', '', '', '', '', '', 'Slide131.PNG', 'art_work_done', ''),
(132, 'Slide132', 'Slide132', '', '', '', '', '', 'Slide132.PNG', 'art_work_done', ''),
(133, 'Slide133', 'Slide133', '', '', '', '', '', 'Slide133.PNG', 'art_work_done', ''),
(134, 'Slide134', 'Slide134', '', '', '', '', '', 'Slide134.PNG', 'art_work_done', ''),
(135, 'Slide135', 'Slide135', '', '', '', '', '', 'Slide135.PNG', 'art_work_done', ''),
(136, 'Slide136', 'Slide136', '', '', '', '', '', 'Slide136.PNG', 'art_work_done', ''),
(137, 'Slide137', 'Slide137', '', '', '', '', '', 'Slide137.PNG', 'art_work_done', ''),
(138, 'Slide138', 'Slide138', '', '', '', '', '', 'Slide138.PNG', 'art_work_done', ''),
(139, 'Slide139', 'Slide139', '', '', '', '', '', 'Slide139.PNG', 'art_work_done', ''),
(140, 'Slide140', 'Slide140', '', '', '', '', '', 'Slide140.PNG', 'art_work_done', ''),
(141, 'Slide141', 'Slide141', '', '', '', '', '', 'Slide141.PNG', 'art_work_done', ''),
(142, 'Slide142', 'Slide142', '', '', '', '', '', 'Slide142.PNG', 'art_work_done', ''),
(143, 'Slide143', 'Slide143', '', '', '', '', '', 'Slide143.PNG', 'art_work_done', ''),
(144, 'Slide144', 'Slide144', '', '', '', '', '', 'Slide144.PNG', 'art_work_done', ''),
(145, 'Slide145', 'Slide145', '', '', '', '', '', 'Slide145.PNG', 'art_work_done', ''),
(146, 'Slide146', 'Slide146', '', '', '', '', '', 'Slide146.PNG', 'art_work_done', ''),
(147, 'Slide147', 'Slide147', '', '', '', '', '', 'Slide147.PNG', 'art_work_done', ''),
(148, 'Slide148', 'Slide148', '', '', '', '', '', 'Slide148.PNG', 'art_work_done', ''),
(149, 'Slide149', 'Slide149', '', '', '', '', '', 'Slide149.PNG', 'art_work_done', ''),
(150, 'Slide150', 'Slide150', '', '', '', '', '', 'Slide150.PNG', 'art_work_done', ''),
(151, 'Slide151', 'Slide151', '', '', '', '', '', 'Slide151.PNG', 'art_work_done', ''),
(152, 'Slide152', 'Slide152', '', '', '', '', '', 'Slide152.PNG', 'art_work_done', ''),
(153, 'Slide153', 'Slide153', '', '', '', '', '', 'Slide153.PNG', 'art_work_done', ''),
(154, 'Slide154', 'Slide154', '', '', '', '', '', 'Slide154.PNG', 'art_work_done', ''),
(155, 'Slide155', 'Slide155', '', '', '', '', '', 'Slide155.PNG', 'art_work_done', ''),
(156, 'Slide156', 'Slide156', '', '', '', '', '', 'Slide156.PNG', 'art_work_done', ''),
(157, 'Slide157', 'Slide157', '', '', '', '', '', 'Slide157.PNG', 'art_work_done', ''),
(158, 'Slide158', 'Slide158', '', '', '', '', '', 'Slide158.PNG', 'art_work_done', ''),
(159, 'Slide159', 'Slide159', '', '', '', '', '', 'Slide159.PNG', 'art_work_done', ''),
(160, 'Slide160', 'Slide160', '', '', '', '', '', 'Slide160.PNG', 'art_work_done', ''),
(161, 'Slide161', 'Slide161', '', '', '', '', '', 'Slide161.PNG', 'art_work_done', ''),
(162, 'Slide162', 'Slide162', '', '', '', '', '', 'Slide162.PNG', 'art_work_done', ''),
(163, 'Slide163', 'Slide163', '', '', '', '', '', 'Slide163.PNG', 'art_work_done', ''),
(164, 'Slide164', 'Slide164', '', '', '', '', '', 'Slide164.PNG', 'art_work_done', ''),
(165, 'Slide165', 'Slide165', '', '', '', '', '', 'Slide165.PNG', 'art_work_done', ''),
(166, 'Slide166', 'Slide166', '', '', '', '', '', 'Slide166.PNG', 'art_work_done', ''),
(167, 'Slide167', 'Slide167', '', '', '', '', '', 'Slide167.PNG', 'art_work_done', ''),
(168, 'Slide168', 'Slide168', '', '', '', '', '', 'Slide168.PNG', 'art_work_done', ''),
(169, 'Slide169', 'Slide169', '', '', '', '', '', 'Slide169.PNG', 'art_work_done', ''),
(170, 'Slide170', 'Slide170', '', '', '', '', '', 'Slide170.PNG', 'art_work_done', ''),
(171, 'Slide171', 'Slide171', '', '', '', '', '', 'Slide171.PNG', 'art_work_done', ''),
(172, 'Slide172', 'Slide172', '', '', '', '', '', 'Slide172.PNG', 'art_work_done', ''),
(173, 'Slide173', 'Slide173', '', '', '', '', '', 'Slide173.PNG', 'art_work_done', ''),
(174, 'Slide174', 'Slide174', '', '', '', '', '', 'Slide174.PNG', 'art_work_done', ''),
(175, 'Slide175', 'Slide175', '', '', '', '', '', 'Slide175.PNG', 'art_work_done', ''),
(176, 'Slide176', 'Slide176', '', '', '', '', '', 'Slide176.PNG', 'art_work_done', ''),
(177, 'Slide177', 'Slide177', '', '', '', '', '', 'Slide177.PNG', 'art_work_done', ''),
(178, 'Slide178', 'Slide178', '', '', '', '', '', 'Slide178.PNG', 'art_work_done', ''),
(179, 'Slide179', 'Slide179', '', '', '', '', '', 'Slide179.PNG', 'art_work_done', ''),
(180, 'Slide180', 'Slide180', '', '', '', '', '', 'Slide180.PNG', 'art_work_done', ''),
(181, 'Slide181', 'Slide181', '', '', '', '', '', 'Slide181.PNG', 'art_work_done', ''),
(182, 'Slide182', 'Slide182', '', '', '', '', '', 'Slide182.PNG', 'art_work_done', ''),
(183, 'Slide183', 'Slide183', '', '', '', '', '', 'Slide183.PNG', 'art_work_done', ''),
(184, 'Slide184', 'Slide184', '', '', '', '', '', 'Slide184.PNG', 'art_work_done', ''),
(185, 'Slide185', 'Slide185', '', '', '', '', '', 'Slide185.PNG', 'art_work_done', ''),
(186, 'Slide186', 'Slide186', '', '', '', '', '', 'Slide186.PNG', 'art_work_done', ''),
(187, 'Slide187', 'Slide187', '', '', '', '', '', 'Slide187.PNG', 'art_work_done', ''),
(188, 'Slide188', 'Slide188', '', '', '', '', '', 'Slide188.PNG', 'art_work_done', ''),
(189, 'Slide189', 'Slide189', '', '', '', '', '', 'Slide189.PNG', 'art_work_done', ''),
(190, 'Slide190', 'Slide190', '', '', '', '', '', 'Slide190.PNG', 'art_work_done', ''),
(191, 'Slide191', 'Slide191', '', '', '', '', '', 'Slide191.PNG', 'art_work_done', ''),
(192, 'Slide192', 'Slide192', '', '', '', '', '', 'Slide192.PNG', 'art_work_done', ''),
(193, 'Slide193', 'Slide193', '', '', '', '', '', 'Slide193.PNG', 'art_work_done', ''),
(194, 'Slide194', 'Slide194', '', '', '', '', '', 'Slide194.PNG', 'art_work_done', ''),
(195, 'Slide195', 'Slide195', '', '', '', '', '', 'Slide195.PNG', 'art_work_done', ''),
(196, 'Slide196', 'Slide196', '', '', '', '', '', 'Slide196.PNG', 'art_work_done', ''),
(197, 'Slide197', 'Slide197', '', '', '', '', '', 'Slide197.PNG', 'art_work_done', ''),
(198, 'Slide198', 'Slide198', '', '', '', '', '', 'Slide198.PNG', 'proposed', ''),
(199, 'Slide199', 'Slide199', '', '', '', '', '', 'Slide199.PNG', 'proposed', ''),
(200, 'Slide200', 'Slide200', '', '', '', '', '', 'Slide200.PNG', 'proposed', ''),
(201, 'Adul Kalam Dress', 'This suit is worn by missile scientist and former president of India Abdul Kalam', 'He is known as \"missile man of India\"', 'people', 'men', 'NA', 'people, suit', 'adul_kalam_dress.jpg', 'art_work_done', ''),
(202, 'Amul Girl', 'amul girl', 'amul girl', 'other', 'girl', 'NA', '', 'amul_girl.jpg', 'proposed', ''),
(203, 'Arunachal Pradesh', 'Arunachal Pradesh', 'Arunachal Pradesh', 'regiona', 'women', '', '', 'arunachal_pradesh.jpg', 'art_work_done', ''),
(204, 'Assam', 'Assam', 'Assam', 'regiona', 'women', '', '', 'assam.jpg', 'art_work_done', ''),
(205, 'Bharath Mata', 'Bharath Mata', 'Bharath Mata', 'regiona', 'women', '', '', 'bharath_mata.jpg', 'art_work_done', ''),
(206, 'Boy Shorts', 'Boy Shorts', 'Boy Shorts', 'regiona', 'boy', '', '', 'boy_shorts.jpg', 'art_work_done', ''),
(207, 'Chudidar', 'Chudidar', 'Chudidar', 'regiona', 'women', '', '', 'chudidar.jpg', 'art_work_done', ''),
(208, 'Crop Top', 'Crop Top', 'Crop Top', 'regiona', 'women', '', '', 'crop_top.jpg', 'art_work_done', ''),
(209, 'Crop Top Girl', 'Crop Top Girl', 'Crop Top Girl', 'regiona', 'women', '', '', 'crop_top_girl.jpg', 'art_work_done', ''),
(210, 'Dabba Wala', 'Dabba Wala', 'Dabba Wala', 'regiona', 'men', '', '', 'dabba_wala.jpg', 'art_work_done', ''),
(211, 'Dance Bhangra Boy', 'Dance Bhangra Boy', 'Dance Bhangra Boy', 'dance', 'men', '', 'dance', 'dance_bhangra_boy.jpg', 'art_work_done', ''),
(212, 'Dance Bhangra Girl', 'Dance Bhangra Girl', 'Dance Bhangra Girl', 'dance', 'women', '', 'dance', 'dance_bhangra_girl.jpg', 'art_work_done', ''),
(213, 'Dance Bharata Natyam', 'Dance Bharata Natyam', 'Dance Bharata Natyam', 'dance', 'women', '', 'dance', 'dance_bharata_natyam.jpg', 'art_work_done', ''),
(214, 'Dance Bihu', 'Dance Bihu', 'Dance Bihu', 'dance', 'women', '', 'dance', 'dance_bihu.jpg', 'art_work_done', ''),
(215, 'Dance Kathak', 'Dance Kathak', 'Dance Kathak', 'dance', 'women', '', 'dance', 'dance_kathak.jpg', 'art_work_done', ''),
(216, 'Dance Kathakali', 'Dance Kathakali', 'Dance Kathakali', 'dance', 'women', '', 'dance', 'dance_kathakali.jpg', 'art_work_done', ''),
(217, 'Dance Kuchipudi', 'Dance Kuchipudi', 'Dance Kuchipudi', 'dance', 'women', '', 'dance', 'dance_kuchipudi.jpg', 'art_work_done', ''),
(218, 'Dance Mohiniyattam', 'Dance Mohiniyattam', 'Dance Mohiniyattam', 'dance', 'women', '', 'dance', 'dance_mohiniyattam.jpg', 'art_work_done', ''),
(219, 'Dance Odissi', 'Dance Odissi', 'Dance Odissi', 'dance', 'women', '', 'dance', 'dance_odissi.jpg', 'art_work_done', ''),
(220, 'Dhoti Lalchi', 'Dhoti Lalchi', 'Dhoti Lalchi', 'regiona', 'men', '', '', 'dhoti_lalchi.jpg', 'art_work_done', ''),
(221, 'Gaghra Choli', 'Gaghra Choli', 'Gaghra Choli', 'regiona', 'women', '', '', 'gaghra_choli.jpg', 'art_work_done', ''),
(222, 'Gandhi Dress', 'Gandhi Dress', 'Gandhi Dress', 'regiona', 'men', '', '', 'gandhi_dress.jpg', 'art_work_done', ''),
(223, 'Garbha Ras Gujarath', 'Garbha Ras Gujarath', 'Garbha Ras Gujarath', 'dance', 'women', '', '', 'garbha_ras_gujarath.jpg', 'art_work_done', ''),
(224, 'Goa Koli', 'Goa Koli', 'Goa Koli', 'regiona', 'women', '', '', 'goa_koli.jpg', 'art_work_done', ''),
(225, 'Gujarathi', 'Gujarathi', 'Gujarathi', 'regiona', 'women', '', '', 'gujarathi.jpg', 'art_work_done', ''),
(226, 'Himachal Pradesh Girl', 'Himachal Pradesh Girl', 'Himachal Pradesh Girl', 'regiona', 'women', '', '', 'himachal_pradesh_girl.jpg', 'art_work_done', ''),
(227, 'I Love Silc Boy', 'I Love Silc Boy', 'I Love Silc Boy', 'regiona', 'boy', '', '', 'i_love_silc_boy.jpg', 'art_work_done', ''),
(228, 'I Love Silc Girl', 'I Love Silc Girl', 'I Love Silc Girl', 'regiona', 'girl', '', '', 'i_love_silc_girl.jpg', 'art_work_done', ''),
(229, 'Jaisalmar Boy', 'Jaisalmar Boy', 'Jaisalmar Boy', 'regiona', 'men', '', '', 'jaisalmar_boy.jpg', 'art_work_done', ''),
(230, 'Jhansi Lakshmi Bai', 'Jhansi Lakshmi Bai', 'Jhansi Lakshmi Bai', 'regiona', 'women', '', '', 'jhansi_lakshmi_bai.jpg', 'art_work_done', ''),
(231, 'Kannada Saree', 'Kannada Saree', 'Kannada Saree', 'regiona', 'women', '', '', 'kannada_saree.jpg', 'art_work_done', ''),
(232, 'King Dress', 'King Dress', 'King Dress', 'regiona', 'men', '', '', 'king_dress.jpg', 'art_work_done', ''),
(233, 'Kreala', 'Kreala', 'Kreala', 'regiona', 'women', '', '', 'kreala.jpg', 'art_work_done', ''),
(234, 'Kurta Boy', 'Kurta Boy', 'Kurta Boy', 'regiona', 'men', '', '', 'kurta_boy.jpg', 'art_work_done', ''),
(235, 'Langa Jacket', 'Langa Jacket', 'Langa Jacket', 'regiona', 'girl', '', '', 'langa_jacket.jpg', 'art_work_done', ''),
(236, 'Langa Voni', 'Langa Voni', 'Langa Voni', 'regiona', 'girl', '', '', 'langa_voni.jpg', 'art_work_done', ''),
(237, 'Lungi', 'Lungi', 'Lungi', 'regiona', 'men', '', '', 'lungi.jpg', 'art_work_done', ''),
(238, 'Manipur', 'Manipur', 'Manipur', 'regiona', 'women', '', '', 'manipur.jpg', 'art_work_done', ''),
(239, 'Marathi', 'Marathi', 'Marathi', 'regiona', 'women', '', '', 'marathi.jpg', 'art_work_done', ''),
(240, 'Megalaya', 'Megalaya', 'Megalaya', 'regiona', 'women', '', '', 'megalaya.jpg', 'art_work_done', ''),
(241, 'Mizoram', 'Mizoram', 'Mizoram', 'regiona', 'women', '', '', 'mizoram.jpg', 'art_work_done', ''),
(242, 'Nagalad', 'Nagalad', 'Nagalad', 'regiona', 'women', '', '', 'nagalad.jpg', 'art_work_done', ''),
(243, 'Panjabi', 'Panjabi', 'Panjabi', 'regiona', 'women', '', '', 'panjabi.jpg', 'art_work_done', ''),
(244, 'Pathan Dress', 'Pathan Dress', 'Pathan Dress', 'regiona', 'men', '', '', 'pathan_dress.jpg', 'art_work_done', ''),
(245, 'Rajastan', 'Rajastan', 'Rajastan', 'regiona', 'women', '', '', 'rajastan.jpg', 'art_work_done', ''),
(246, 'Saree Dress', 'Saree Dress', 'Saree Dress', 'regiona', 'women', '', '', 'saree_dress.jpg', 'art_work_done', ''),
(247, 'Tamilnadu', 'Tamilnadu', 'Tamilnadu', 'regiona', 'women', '', '', 'tamilnadu.jpg', 'art_work_done', ''),
(248, 'Tribal Dress', 'Tribal Dress', 'Tribal Dress', 'regiona', 'women', '', '', 'tribal_dress.jpg', 'art_work_done', ''),
(249, 'Tripura', 'Tripura', 'Tripura', 'regiona', 'women', '', '', 'tripura.jpg', 'art_work_done', ''),
(250, 'West Bengal', 'West Bengal', 'West Bengal', 'regiona', 'women', '', '', 'west_bengal.jpg', 'art_work_done', ''),
(255, 'one', 'two', 'three', 'hello', 'boys', 'mn', 'key, words', 'gardening.png', 'proposed', 'notes');

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `value` int(11) NOT NULL,
  `comments` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`id`, `name`, `value`, `comments`) VALUES
(1, 'NO_OF_DRESSES_PER_ROW', 5, 'This is the number of topics, per row, on home page'),
(2, 'NO_OF_DRESSES_TO_DISPLAY', 50, 'The number of dresses to display on main index page'),
(3, 'NAME_OF_FAVORITE_DRESS', 0, 'Saree');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(500) NOT NULL,
  `type` varchar(20) NOT NULL,
  `notes` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `url`, `type`, `notes`) VALUES
(1, 'Saree Wiki', 'https://i.pinimg.com/564x/36/82/e7/3682e7caa082b9e86d9085180339e5f5.jpg', 'image', 'Saree wiki'),
(2, 'Gagracholi', 'https://www.youtube.com/embed/6EeLwYPUVHM', 'video', 'Gagracholi wiki'),
(3, 'Crop Top', 'https://en.wikipedia.org/wiki/Gagra_choli', 'wiki.', '12345678910'),
(4, 'Saree Wiki', 'https://en.wikipedia.org/wiki/Gagra_choli', 'url', 'Saree wiki'),
(7, 'Test', 'https://i.pinimg.com/564x/36/82/e7/3682e7caa082b9e86d9085180339e5f5.jpg', 'IMAGE', 'test notes'),
(25, 'Namasthe', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'video', 'testing'),
(26, 'Salwar Kameez', 'https://www.youtube.com/embed/9NE33fpQuw8', 'video', 'testing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMEMNT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(75) NOT NULL,
  `hash` varchar(200) NOT NULL,
  `active` varchar(10) NOT NULL,
  `role` varchar(20) NOT NULL,
  `modified_time` date NOT NULL,
  `created_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `hash`, `active`, `role`, `modified_time`, `created_time`) VALUES
(0, 'siva', 'jasthi', 'siva.jasthi@gmail.com', '$2y$10$o6C.omh3F9XLUjex7qhvm.MbGNYn94HU64tvqiFpS5K6tOLLoE9C2', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(1, 'user1', 'cs', 'siva.jasthi@gmail.com', '$2y$10$o6C.omh3F9XLUjex7qhvm.MbGNYn94HU64tvqiFpS5K6tOLLoE9C2', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(2, 'user3', 'cs', 'user3@cs.com', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00'),
(4, 'SILC', 'CS320', 'cs320@silcmn.com', '$2y$10$zFAG5GBNtf.5BpowMqZSputSLeG8OzfKACpjAMsePjZhu.TnvU/Bu', 'yes', 'admin', '0000-00-00', '0000-00-00');

--
-- Indexes for dumped tables
--
