-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2016 at 12:54 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nillsushi`
--

-- --------------------------------------------------------

--
-- Table structure for table `gr_activitylog`
--

CREATE TABLE IF NOT EXISTS `gr_activitylog` (
`idactivitylog` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activity` varchar(45) NOT NULL,
  `url` varchar(255) NOT NULL,
  `fkidusersession` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_activitylog`
--

INSERT INTO `gr_activitylog` (`idactivitylog`, `date`, `activity`, `url`, `fkidusersession`) VALUES
(1, '2015-11-04 11:24:35', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 1),
(2, '2015-11-04 11:27:29', 'Added product module in the Webmaster user', 'http://localhost/web/griffo/rest/admin/website/module/insert', 1),
(3, '2015-11-04 11:27:29', 'Installation Module Product', 'http://localhost/web/griffo/rest/admin/website/module/insert', 1),
(4, '2015-11-04 11:32:04', 'Delete Module product', 'http://localhost/web/griffo/rest/admin/website/module/16', 1),
(5, '2015-11-04 11:46:26', 'Added product module in the Webmaster user', 'http://localhost/web/griffo/rest/admin/website/module/insert', 1),
(6, '2015-11-04 11:46:26', 'Installation Module Product', 'http://localhost/web/griffo/rest/admin/website/module/insert', 1),
(7, '2015-11-04 12:13:06', 'Register Optional', 'http://localhost/web/griffo/rest/admin/website/optional/insert', 1),
(8, '2015-11-04 13:40:18', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/optional/', 1),
(9, '2015-11-04 13:40:28', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 2),
(10, '2015-11-04 17:36:43', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/optional/', 2),
(11, '2015-11-04 18:26:01', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 3),
(12, '2015-11-05 09:50:06', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/optional/', 3),
(13, '2015-11-05 11:05:44', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 4),
(14, '2015-11-05 19:00:39', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/optional/', 4),
(15, '2015-11-06 13:18:20', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 5),
(16, '2015-11-06 18:48:49', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 5),
(17, '2015-11-06 18:48:53', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 6),
(18, '2015-11-06 19:05:02', 'Update Optional', 'http://localhost/web/griffo/rest/admin/website/optional/update_attributes/1', 6),
(19, '2015-11-06 19:05:26', 'Delete Optional', 'http://localhost/web/griffo/rest/admin/website/optional/1', 6),
(20, '2015-11-09 10:04:42', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/fraction/', 6),
(21, '2015-11-11 10:54:27', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 7),
(22, '2015-11-11 17:25:36', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/fraction/', 7),
(23, '2015-11-11 17:25:55', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 8),
(24, '2015-11-11 17:34:38', 'Register Fraction', 'http://localhost/web/griffo/rest/admin/website/fraction/insert', 8),
(25, '2015-11-12 13:01:48', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin', 8),
(26, '2015-11-12 13:01:52', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 9),
(27, '2015-11-12 13:27:49', 'Register Fraction', 'http://localhost/web/griffo/rest/admin/website/fraction/insert', 9),
(28, '2015-11-12 13:38:19', 'Register Group', 'http://localhost/web/griffo/rest/admin/website/group/insert', 9),
(29, '2015-11-12 18:42:45', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/fraction/', 9),
(30, '2015-11-12 18:53:25', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 10),
(31, '2015-11-16 18:46:25', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 11),
(32, '2015-11-17 00:11:32', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/new/', 11),
(33, '2015-11-18 13:10:17', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 12),
(34, '2015-11-18 13:10:24', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 12),
(35, '2015-11-18 18:13:05', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 12),
(36, '2015-11-18 18:29:31', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 13),
(37, '2015-11-19 10:25:51', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 13),
(38, '2015-11-19 19:12:38', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 14),
(39, '2015-11-20 09:46:59', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/menu/list/', 14),
(40, '2015-11-20 12:30:37', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 15),
(41, '2015-11-20 12:34:01', 'Register Page Página inicial', 'http://localhost/web/griffo/rest/admin/website/page/insert', 15),
(42, '2015-11-20 12:44:39', 'Delete Page Página inicial', 'http://localhost/web/griffo/rest/admin/website/page/1', 15),
(43, '2015-11-20 18:23:55', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin', 15),
(44, '2015-11-20 18:24:00', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 16),
(45, '2015-11-20 18:24:57', 'Register Optional', 'http://localhost/web/griffo/rest/admin/website/optional/insert', 16),
(46, '2015-11-20 18:25:11', 'Update Optional', 'http://localhost/web/griffo/rest/admin/website/optional/update_attributes/1', 16),
(47, '2015-11-20 18:38:12', 'Register Group', 'http://localhost/web/griffo/rest/admin/website/group/insert', 16),
(48, '2015-11-20 18:56:42', 'Register Group', 'http://localhost/web/griffo/rest/admin/website/group/insert', 16),
(49, '2015-11-23 18:24:48', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/fraction/', 16),
(50, '2015-11-25 13:49:50', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 17),
(51, '2015-11-25 18:08:25', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/category/', 17),
(52, '2015-11-25 18:08:28', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 18),
(53, '2015-11-25 18:09:00', 'Register Category asd', 'http://localhost/web/griffo/rest/admin/website/category/insert', 18),
(54, '2015-11-25 18:09:19', 'Register Category fsdf', 'http://localhost/web/griffo/rest/admin/website/category/insert', 18),
(55, '2015-11-25 18:09:36', 'Update Category fsdf', 'http://localhost/web/griffo/rest/admin/website/category/update_attributes/2', 18),
(56, '2015-11-25 18:45:00', 'Update Group', 'http://localhost/web/griffo/rest/admin/website/group/update_attributes/1', 18),
(57, '2015-11-25 18:47:21', 'Update Group', 'http://localhost/web/griffo/rest/admin/website/group/update_attributes/1', 18),
(58, '2015-11-25 19:17:01', 'Update Group', 'http://localhost/web/griffo/rest/admin/website/group/update_attributes/1', 18),
(59, '2015-11-25 19:17:10', 'Update Group', 'http://localhost/web/griffo/rest/admin/website/group/update_attributes/1', 18),
(60, '2015-11-26 12:41:45', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/product/fraction/', 18),
(61, '2015-12-02 13:05:23', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 19),
(62, '2015-12-02 17:34:43', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 19),
(63, '2015-12-02 18:24:13', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 20),
(64, '2015-12-02 18:35:07', 'Register Menu tttt', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 20),
(65, '2015-12-03 10:10:41', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/menu/new/', 20),
(66, '2016-01-14 15:37:35', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 21),
(67, '2016-01-14 15:54:32', 'Update Customer acoll', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 21),
(68, '2016-01-14 18:46:54', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 22),
(69, '2016-01-14 18:51:14', 'Update Customer acoll', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 22),
(70, '2016-01-14 18:52:41', 'Update Customer acoll', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 22),
(71, '2016-01-14 18:55:18', 'Update Customer acoll', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 22),
(72, '2016-01-14 18:56:03', 'Update Customer acoll', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 22),
(73, '2016-01-14 18:57:00', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 22),
(74, '2016-01-14 19:23:45', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 22),
(75, '2016-01-14 19:23:46', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 22),
(76, '2016-01-14 19:23:47', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 22),
(77, '2016-01-14 19:23:47', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 22),
(78, '2016-01-14 19:23:48', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 22),
(79, '2016-01-15 15:29:06', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/', 22),
(80, '2016-01-15 15:52:00', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 23),
(81, '2016-01-15 21:35:05', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 23),
(82, '2016-01-18 12:38:52', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 24),
(83, '2016-01-18 12:55:29', 'Update User Webmaster', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/1', 24),
(84, '2016-01-18 13:36:37', 'Register Profile teste', 'http://localhost/web/griffo/rest/admin/website/profile/insert', 24),
(85, '2016-01-18 13:37:08', 'Register User Teste', 'http://localhost/web/griffo/rest/admin/website/user/insert', 24),
(86, '2016-01-18 15:44:26', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/', 24),
(87, '2016-01-18 15:44:30', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 25),
(88, '2016-01-18 15:45:30', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 25),
(89, '2016-01-18 15:45:38', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 25),
(90, '2016-01-18 15:46:00', 'Update password User Teste', 'http://localhost/web/griffo/rest/admin/website/user/change_password/2', 25),
(91, '2016-01-18 15:46:03', 'Update User Teste', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/2', 25),
(92, '2016-01-18 15:46:08', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 25),
(93, '2016-01-18 15:46:14', 'Login user: teste password: e10adc3949ba59abb', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 26),
(94, '2016-01-18 15:49:26', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/admin/authentication/logout', 26),
(95, '2016-01-18 15:49:30', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 27),
(96, '2016-01-18 15:50:06', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 27),
(97, '2016-01-18 15:50:16', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 27),
(98, '2016-01-18 15:50:21', 'Login user: teste password: e10adc3949ba59abb', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 28),
(99, '2016-01-18 15:50:34', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/admin/authentication/logout', 28),
(100, '2016-01-18 15:50:38', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 29),
(101, '2016-01-18 15:51:03', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 29),
(102, '2016-01-18 15:51:09', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 29),
(103, '2016-01-18 15:51:18', 'Login user: teste password: e10adc3949ba59abb', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 30),
(104, '2016-01-18 15:51:56', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/admin/authentication/logout', 30),
(105, '2016-01-18 15:52:00', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 31),
(106, '2016-01-18 15:52:21', 'Update User Teste', 'http://localhost/web/griffo/rest/admin/website/user/update_attributes/2', 31),
(107, '2016-01-18 15:52:26', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 31),
(108, '2016-01-18 15:52:31', 'Login user: teste password: e10adc3949ba59abb', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 32),
(109, '2016-01-18 15:52:48', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/admin/authentication/logout', 32),
(110, '2016-01-18 15:52:56', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 33),
(111, '2016-01-18 15:53:07', 'Delete User Teste', 'http://localhost/web/griffo/rest/admin/website/user/2', 33),
(112, '2016-01-18 15:53:34', 'Register User Teste', 'http://localhost/web/griffo/rest/admin/website/user/insert', 33),
(113, '2016-01-18 15:53:42', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 33),
(114, '2016-01-18 15:53:46', 'Login user: teste password: 698dc19d489c4e4db', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 34),
(115, '2016-01-18 15:54:51', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 34),
(116, '2016-01-18 15:55:32', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 34),
(117, '2016-01-18 15:56:11', 'Update Access Control', 'http://localhost/web/griffo/rest/admin/website/authentication/update_access_control/2', 34),
(118, '2016-01-18 15:56:36', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 34),
(119, '2016-01-18 15:56:41', 'Login user: teste password: 698dc19d489c4e4db', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 35),
(120, '2016-01-18 15:57:11', 'Logout layer: admin userauth: teste', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 35),
(121, '2016-01-18 15:57:15', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 36),
(122, '2016-01-18 15:59:10', 'Delete Profile teste', 'http://localhost/web/griffo/rest/admin/website/profile/2', 36),
(123, '2016-01-18 16:00:05', 'Delete User Teste', 'http://localhost/web/griffo/rest/admin/website/user/3', 36),
(124, '2016-01-19 11:09:20', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/user/list/', 36),
(125, '2016-01-19 13:26:47', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 37),
(126, '2016-01-19 13:30:21', 'Register Template Modelo 01', 'http://localhost/web/griffo/rest/admin/website/template/insert', 37),
(127, '2016-01-19 13:30:29', 'Register Template Modelo 02', 'http://localhost/web/griffo/rest/admin/website/template/insert', 37),
(128, '2016-01-19 13:30:35', 'Register Template Modelo 03', 'http://localhost/web/griffo/rest/admin/website/template/insert', 37),
(129, '2016-01-19 13:30:46', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 37),
(130, '2016-01-19 13:32:17', 'Register Page Página Inicial', 'http://localhost/web/griffo/rest/admin/website/page/insert', 37),
(131, '2016-01-19 13:33:22', 'Update Config Page', 'http://localhost/web/griffo/rest/admin/website/page/update_config', 37),
(132, '2016-01-19 13:34:37', 'Delete Menu tttt', 'http://localhost/web/griffo/rest/admin/website/menu/56', 37),
(133, '2016-01-19 13:34:56', 'Register Menu Página inicial', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(134, '2016-01-19 13:35:06', 'Register Menu Produtos', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(135, '2016-01-19 13:35:30', 'Register Menu Produto 01', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(136, '2016-01-19 13:35:38', 'Register Menu Produto 02', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(137, '2016-01-19 13:35:49', 'Register Menu Produto 03', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(138, '2016-01-19 13:36:16', 'Register Menu Produto 02.1', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(139, '2016-01-19 13:36:22', 'Register Menu Produto 02.2', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(140, '2016-01-19 13:36:27', 'Register Menu Produto 02.3', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(141, '2016-01-19 13:40:29', 'Register Menu Empresa', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(142, '2016-01-19 13:40:35', 'Register Menu Contato', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 37),
(143, '2016-01-20 11:02:02', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/menu/new/', 37),
(144, '2016-01-20 11:09:35', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 38),
(145, '2016-01-25 11:39:33', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 39),
(146, '2016-01-25 15:17:15', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 39),
(147, '2016-01-25 17:52:30', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 40),
(148, '2016-01-25 17:53:11', 'Update Menu Produto 02.2', 'http://localhost/web/griffo/rest/admin/website/menu/update_attributes/63', 40),
(149, '2016-01-25 17:53:22', 'Update Menu Produto 02.3', 'http://localhost/web/griffo/rest/admin/website/menu/update_attributes/64', 40),
(150, '2016-01-25 20:57:54', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/menu/list/', 40),
(151, '2016-01-27 17:46:57', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 41),
(152, '2016-01-27 17:48:30', 'Register Menu Produto 02.2.1', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 41),
(153, '2016-01-27 17:48:47', 'Register Menu Produto 02.2.2', 'http://localhost/web/griffo/rest/admin/website/menu/insert', 41),
(154, '2016-01-27 22:00:21', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/menu/new/', 41),
(155, '2016-01-27 22:31:52', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 42),
(156, '2016-01-28 11:19:13', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 42),
(157, '2016-01-28 13:21:19', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 43),
(158, '2016-01-28 13:21:35', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 43),
(159, '2016-01-28 13:43:02', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 43),
(160, '2016-01-28 14:01:06', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 44),
(161, '2016-01-28 15:45:17', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 44),
(162, '2016-01-28 15:45:21', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 45),
(163, '2016-01-28 18:12:38', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 45),
(164, '2016-01-28 18:20:13', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 45),
(165, '2016-01-28 18:20:39', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website', 45),
(166, '2016-01-28 18:20:43', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 46),
(167, '2016-01-28 18:20:53', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(168, '2016-01-28 18:28:14', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(169, '2016-01-28 18:33:10', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(170, '2016-01-28 19:01:31', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(171, '2016-01-28 19:09:49', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(172, '2016-01-28 19:24:07', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(173, '2016-01-28 19:26:26', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(174, '2016-01-28 19:26:55', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(175, '2016-01-28 19:27:31', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(176, '2016-01-28 19:38:33', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(177, '2016-01-28 19:39:49', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(178, '2016-01-28 19:41:09', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(179, '2016-01-28 19:42:44', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(180, '2016-01-28 19:46:42', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(181, '2016-01-28 19:47:21', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 46),
(182, '2016-01-28 19:58:32', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 46),
(183, '2016-01-28 19:58:46', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 46),
(184, '2016-01-28 19:58:59', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 46),
(185, '2016-01-29 12:24:44', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 47),
(186, '2016-01-29 12:25:11', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(187, '2016-01-29 12:26:41', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 47),
(188, '2016-01-29 12:27:08', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 47),
(189, '2016-01-29 12:31:41', 'Register Page Fale conosco', 'http://localhost/web/griffo/rest/admin/website/page/insert', 47),
(190, '2016-01-29 12:33:09', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 47),
(191, '2016-01-29 12:33:29', 'Update Config layer website', 'http://localhost/web/griffo/rest/admin/website/layer/config', 47),
(192, '2016-01-29 12:34:29', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(193, '2016-01-29 12:42:40', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(194, '2016-01-29 12:43:45', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(195, '2016-01-29 12:44:39', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(196, '2016-01-29 12:44:52', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(197, '2016-01-29 12:48:26', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(198, '2016-01-29 12:48:43', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(199, '2016-01-29 13:02:09', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(200, '2016-01-29 13:10:30', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(201, '2016-01-29 13:23:21', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(202, '2016-01-29 13:26:10', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 47),
(203, '2016-01-29 13:59:57', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/admin/website/module/list/', 47),
(204, '2016-01-29 15:31:41', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 48),
(205, '2016-01-29 15:31:53', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 48),
(206, '2016-01-29 15:40:38', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 49),
(207, '2016-01-29 15:41:32', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 49),
(208, '2016-01-29 15:41:52', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 49),
(209, '2016-01-29 15:42:15', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 49),
(210, '2016-01-29 15:43:36', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/griffo/rest/admin/admin/authentication/login', 50),
(211, '2016-01-29 15:43:55', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(212, '2016-01-29 15:46:23', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(213, '2016-01-29 15:47:19', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(214, '2016-01-29 15:47:45', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(215, '2016-01-29 15:49:11', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(216, '2016-01-29 15:49:18', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(217, '2016-01-29 15:49:27', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(218, '2016-01-29 15:49:47', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(219, '2016-01-29 15:55:31', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(220, '2016-01-29 15:56:40', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(221, '2016-01-29 15:57:40', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(222, '2016-01-29 16:01:26', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(223, '2016-01-29 16:02:25', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(224, '2016-01-29 16:02:35', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(225, '2016-01-29 16:02:43', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(226, '2016-01-29 16:03:33', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(227, '2016-01-29 16:03:34', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(228, '2016-01-29 16:04:00', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(229, '2016-01-29 16:04:00', 'Update Customer Acoll Assessoria & Comunicaçã', 'http://localhost/web/griffo/rest/admin/website/customer/update_attributes', 50),
(230, '2016-01-29 16:08:16', 'Logout layer: admin userauth: admin', 'http://localhost/web/griffo/rest/admin/website/authentication/logout', 50),
(231, '2016-05-18 03:05:03', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 51),
(232, '2016-05-18 03:06:14', 'Register Theme Crab', 'http://localhost/web/nillsushi/website/rest/admin/website/theme/insert', 51),
(233, '2016-05-18 03:07:08', 'Register Template Crab', 'http://localhost/web/nillsushi/website/rest/admin/website/template/insert', 51),
(234, '2016-05-18 03:07:18', 'Update Config layer website', 'http://localhost/web/nillsushi/website/rest/admin/website/layer/config', 51),
(235, '2016-05-18 03:16:17', 'Added banner module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 51),
(236, '2016-05-18 03:16:17', 'Installation Module Banner', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 51),
(237, '2016-05-18 03:24:09', 'Update Config Page', 'http://localhost/web/nillsushi/website/rest/admin/website/page/update_config', 51),
(238, '2016-05-24 23:02:20', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website/page/config/', 51),
(239, '2016-05-25 02:18:59', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 52),
(240, '2016-05-25 03:09:48', 'Added shop module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(241, '2016-05-25 03:09:48', 'Installation Module Shop', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(242, '2016-05-25 03:11:24', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(243, '2016-05-25 03:11:24', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(244, '2016-05-25 03:26:02', 'Added client module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(245, '2016-05-25 03:26:02', 'Installation Module Client', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(246, '2016-05-25 03:29:01', 'Delete Module client', 'http://localhost/web/nillsushi/website/rest/admin/website/module/21', 52),
(247, '2016-05-25 03:29:21', 'Added client module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(248, '2016-05-25 03:29:21', 'Installation Module Client', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 52),
(249, '2016-05-25 15:58:53', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website/order/list/', 52),
(250, '2016-05-25 16:22:49', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 53),
(251, '2016-05-27 18:40:57', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website', 53),
(252, '2016-06-08 00:30:26', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 54),
(253, '2016-06-08 01:36:08', 'Delete Category Esfihas de Carne', 'http://localhost/web/nillsushi/website/rest/admin/website/category/4', 54),
(254, '2016-06-08 01:45:16', 'Delete Module product', 'http://localhost/web/nillsushi/website/rest/admin/website/module/17', 54),
(255, '2016-06-08 01:48:15', 'Added product module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 54),
(256, '2016-06-08 01:48:15', 'Installation Module Product', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 54),
(257, '2016-06-08 01:48:41', 'Register Category Categoria 1', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 54),
(258, '2016-06-08 01:48:45', 'Register Category Categoria 2', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 54),
(259, '2016-06-08 01:48:49', 'Register Category Categoria 3', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 54),
(260, '2016-06-08 01:59:27', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 55),
(261, '2016-06-08 01:59:47', 'Delete Module product', 'http://localhost/web/nillsushi/website/rest/admin/website/module/23', 55),
(262, '2016-06-08 02:00:11', 'Added product module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 55),
(263, '2016-06-08 02:00:11', 'Installation Module Product', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 55),
(264, '2016-06-08 02:00:43', 'Register Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/insert', 55),
(265, '2016-06-08 02:01:43', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 56),
(266, '2016-06-08 02:01:58', 'Register Category Categoria 1', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 56),
(267, '2016-06-08 02:02:06', 'Register Category Categoria 2', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 56),
(268, '2016-06-08 02:02:09', 'Register Category Categoria 3', 'http://localhost/web/nillsushi/website/rest/admin/website/category/insert', 56),
(269, '2016-06-08 02:03:10', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/1', 56),
(270, '2016-06-08 02:06:33', 'Delete Banner', 'http://localhost/web/nillsushi/website/rest/admin/website/banner/2', 56),
(271, '2016-06-08 02:06:43', 'Update Banner', 'http://localhost/web/nillsushi/website/rest/admin/website/banner/update_attributes/1', 56),
(272, '2016-06-08 02:06:49', 'Update Banner', 'http://localhost/web/nillsushi/website/rest/admin/website/banner/update_attributes/1', 56),
(273, '2016-06-08 02:13:14', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/1', 56),
(274, '2016-06-08 02:13:33', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/2', 56),
(275, '2016-06-08 02:13:42', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/3', 56),
(276, '2016-06-08 02:13:50', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/4', 56),
(277, '2016-06-08 02:14:00', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/5', 56),
(278, '2016-06-08 02:14:10', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/6', 56),
(279, '2016-06-08 02:14:18', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/7', 56),
(280, '2016-06-08 02:14:27', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/8', 56),
(281, '2016-06-08 02:14:36', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/9', 56),
(282, '2016-06-08 02:14:50', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/10', 56),
(283, '2016-06-08 02:15:01', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/11', 56),
(284, '2016-06-08 02:15:13', 'Update Product', 'http://localhost/web/nillsushi/website/rest/admin/website/product/update_attributes/12', 56),
(285, '2016-06-08 03:12:22', 'Delete Module order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/20', 56),
(286, '2016-06-08 03:12:46', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 56),
(287, '2016-06-08 03:12:46', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 56),
(288, '2016-06-08 10:38:18', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website/order/list/', 56),
(289, '2016-06-08 23:33:46', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 57),
(290, '2016-06-08 23:36:19', 'Delete Module shop', 'http://localhost/web/nillsushi/website/rest/admin/website/module/19', 57),
(291, '2016-06-08 23:36:32', 'Added shop module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 57),
(292, '2016-06-08 23:36:32', 'Installation Module Shop', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 57),
(293, '2016-06-09 02:14:40', 'Update Access Control', 'http://localhost/web/nillsushi/website/rest/admin/website/authentication/update_access_control/1', 57),
(294, '2016-07-30 21:45:02', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 58),
(295, '2016-07-30 23:21:38', 'Delete Module order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/25', 58),
(296, '2016-07-30 23:32:22', 'Added shop module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(297, '2016-07-30 23:32:22', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(298, '2016-07-31 00:00:34', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(299, '2016-07-31 00:00:34', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(300, '2016-07-31 00:19:35', 'Delete Module order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/27', 58),
(301, '2016-07-31 00:20:03', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(302, '2016-07-31 00:20:03', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 58),
(303, '2016-07-31 00:23:28', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 59),
(304, '2016-07-31 00:26:19', 'Delete Module order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/28', 59),
(305, '2016-07-31 00:26:46', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 59),
(306, '2016-07-31 00:26:46', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 59),
(307, '2016-07-31 00:28:48', 'Delete Module order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/29', 59),
(308, '2016-07-31 00:29:52', 'Added order module in the Webmaster user', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 59),
(309, '2016-07-31 00:29:52', 'Installation Module Order', 'http://localhost/web/nillsushi/website/rest/admin/website/module/insert', 59),
(310, '2016-07-31 00:55:38', 'Update Access Control', 'http://localhost/web/nillsushi/website/rest/admin/website/authentication/update_access_control/1', 59),
(311, '2016-07-31 00:55:58', 'Update Access Control', 'http://localhost/web/nillsushi/website/rest/admin/website/authentication/update_access_control/1', 59),
(312, '2016-07-31 01:10:31', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 60),
(313, '2016-07-31 01:20:25', 'Register Days Enabled', 'http://localhost/web/nillsushi/website/rest/admin/website/daysenabled/insert', 60),
(314, '2016-07-31 01:23:05', 'Register Days Enabled', 'http://localhost/web/nillsushi/website/rest/admin/website/daysenabled/insert', 60),
(315, '2016-07-31 02:53:21', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 61),
(316, '2016-07-31 04:00:27', 'Register Days Enabled', 'http://localhost/web/nillsushi/website/rest/admin/website/daysenabled/insert', 61),
(317, '2016-07-31 04:01:08', 'Register Days Enabled', 'http://localhost/web/nillsushi/website/rest/admin/website/daysenabled/insert', 61),
(318, '2016-07-31 04:26:50', 'Register Client', 'http://localhost/web/nillsushi/website/rest/admin/website/client/insert', 61),
(319, '2016-07-31 04:27:40', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 62),
(320, '2016-07-31 04:31:48', 'Delete Client', 'http://localhost/web/nillsushi/website/rest/admin/website/client/1', 62),
(321, '2016-07-31 18:47:35', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website/shop/new/', 62),
(322, '2016-07-31 18:47:44', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 63),
(323, '2016-07-31 18:48:25', 'Register Shop', 'http://localhost/web/nillsushi/website/rest/admin/website/shop/insert', 63),
(324, '2016-07-31 18:49:10', 'Login user: cliente@teste.com password: f5bb0', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 64),
(325, '2016-07-31 21:03:29', 'Logout layer: website userauth: cliente@teste', 'http://localhost/web/nillsushi/website/finish', 64),
(326, '2016-07-31 21:03:36', 'Login user: cliente@teste.com password: f5bb0', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 65),
(327, '2016-07-31 21:21:16', 'Logout layer: website userauth: cliente@teste', 'http://localhost/web/nillsushi/website/rest/website/authentication/logout', 65),
(328, '2016-07-31 21:22:24', 'Login user: cliente@teste.com password: f5bb0', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 66),
(329, '2016-07-31 21:22:56', 'Logout layer: website userauth: cliente@teste', 'http://localhost/web/nillsushi/website/rest/website/authentication/logout', 66),
(330, '2016-07-31 21:38:50', 'Login user: cliente2@teste.com password: f5bb', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 67),
(331, '2016-07-31 21:41:03', 'Update Client', 'http://localhost/web/nillsushi/website/rest/website/client/update_attributes/3', 67),
(332, '2016-07-31 21:41:27', 'Logout layer: website userauth: cliente2@test', 'http://localhost/web/nillsushi/website/rest/website/authentication/logout', 67),
(333, '2016-07-31 21:43:26', 'Login user: cliente3@teste.com password: f5bb', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 68),
(334, '2016-07-31 21:43:40', 'Logout layer: website userauth: cliente3@test', 'http://localhost/web/nillsushi/website/rest/website/authentication/logout', 68),
(335, '2016-07-31 21:46:44', 'Login user: cliente4@teste.com password: f5bb', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 69),
(336, '2016-07-31 21:52:38', 'Logout layer: website userauth: cliente4@test', 'http://localhost/web/nillsushi/website/rest/website/authentication/logout', 69),
(337, '2016-07-31 21:54:36', 'Login user: cliente4@teste.com password: f5bb', 'http://localhost/web/nillsushi/website/rest/website/authentication/login', 70),
(338, '2016-07-31 22:53:11', 'Logout layer: admin userauth: admin', 'http://localhost/web/nillsushi/website/admin/website/order/list/', 63),
(339, '2016-07-31 22:53:17', 'Login user: admin password: 21232f297a57a5a74', 'http://localhost/web/nillsushi/website/rest/admin/admin/authentication/login', 71);

-- --------------------------------------------------------

--
-- Table structure for table `gr_applicationauth`
--

CREATE TABLE IF NOT EXISTS `gr_applicationauth` (
`idapplicationauth` int(11) NOT NULL,
  `id` varchar(100) NOT NULL,
  `key` varchar(255) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_applicationauth`
--

INSERT INTO `gr_applicationauth` (`idapplicationauth`, `id`, `key`, `name`) VALUES
(1, '123123123', '11l2kj3hk12h3k', 'admin'),
(2, '1231233432', '123daslk12j3kl12j', 'website');

-- --------------------------------------------------------

--
-- Table structure for table `gr_banner`
--

CREATE TABLE IF NOT EXISTS `gr_banner` (
`idbanner` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `description` text,
  `keywords` varchar(255) DEFAULT NULL,
  `picture` text NOT NULL,
  `sort` int(11) DEFAULT NULL,
  `registrationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  `fkidcategory` int(11) DEFAULT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_banner`
--

INSERT INTO `gr_banner` (`idbanner`, `title`, `link`, `width`, `height`, `description`, `keywords`, `picture`, `sort`, `registrationdate`, `status`, `fkidcategory`, `fkidmodule`) VALUES
(1, 'Banner 1', NULL, NULL, NULL, NULL, NULL, 'Banner/banner.png', 1, '2015-06-25 20:20:36', 1, NULL, 15);

-- --------------------------------------------------------

--
-- Table structure for table `gr_block`
--

CREATE TABLE IF NOT EXISTS `gr_block` (
`idblock` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `content` text,
  `type` varchar(45) NOT NULL,
  `fkidpage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_category`
--

CREATE TABLE IF NOT EXISTS `gr_category` (
`idcategory` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `url` varchar(255) NOT NULL,
  `idcategoryparent` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidmodule` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_category`
--

INSERT INTO `gr_category` (`idcategory`, `name`, `url`, `idcategoryparent`, `sort`, `status`, `fkidmodule`) VALUES
(1, 'Categoria 1', 'categoria-1/', NULL, 4, 1, 24),
(2, 'Categoria 2', 'categoria-2/', NULL, 5, 1, 24),
(3, 'Categoria 3', 'categoria-3/', NULL, 6, 1, 24);

-- --------------------------------------------------------

--
-- Table structure for table `gr_client`
--

CREATE TABLE IF NOT EXISTS `gr_client` (
`idclient` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(200) NOT NULL,
  `number` varchar(10) NOT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `district` varchar(45) NOT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `mobilephone` varchar(30) DEFAULT '0',
  `preferredshop` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidmodule` int(11) NOT NULL,
  `fkidshop` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_client`
--

INSERT INTO `gr_client` (`idclient`, `name`, `email`, `address`, `number`, `complement`, `district`, `city`, `state`, `phone`, `mobilephone`, `preferredshop`, `created`, `updated`, `status`, `fkidmodule`, `fkidshop`) VALUES
(2, 'Teste', 'cliente@teste.com', 'Teste', '123', NULL, 'Teste', 'Itapeva', 'SP', '15999999999', '0', 'Itapeva', '2016-07-31 15:49:09', NULL, 1, 22, 1),
(3, 'Teste 2', 'cliente2@teste.com', 'Teste', '123', NULL, 'Teste', 'Itapeva', 'SP', '15999999999', NULL, NULL, '2016-07-31 18:38:49', '2016-07-31 18:41:03', 1, 22, 1),
(4, 'Cliente 3', 'cliente3@teste.com', 'Teste', '123', NULL, 'Teste', 'Itapeva', 'SP', '15999999999', '0', NULL, '2016-07-31 18:43:25', NULL, 1, 22, 1),
(5, 'Cliente 4', 'cliente4@teste.com', 'Teste', '123', NULL, 'Teste', 'Itapeva', 'SP', '99999999999', NULL, NULL, '2016-07-31 18:46:44', NULL, 1, 22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_customer`
--

CREATE TABLE IF NOT EXISTS `gr_customer` (
`idcustomer` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `map` varchar(255) NOT NULL,
  `social` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_customer`
--

INSERT INTO `gr_customer` (`idcustomer`, `logo`, `name`, `phone`, `email`, `address`, `map`, `social`) VALUES
(1, 'logo/logo-header.png', 'Acoll Assessoria & Comunicação', 'O:8:"stdClass":3:{s:1:"0";s:10:"1535223302";s:1:"1";s:10:"1535225498";s:1:"2";s:11:"15997006808";}', 'O:8:"stdClass":1:{s:1:"0";s:20:"suporte@acoll.com.br";}', 'Rua Epitácio Piedade, 204, Vila Ophélia, Itapeva/SP', '{"coords":"-23.9852375,-48.8805527","zoom":15}', 'O:8:"stdClass":7:{s:8:"facebook";s:24:"http://www.google.com.br";s:7:"twitter";s:24:"http://www.google.com.br";s:9:"instagram";s:24:"http://www.google.com.br";s:7:"youtube";s:24:"http://www.google.com.br";s:10:"googlePlus";s:24:"http://www.google.com.br";s:6:"github";s:24:"http://www.google.com.br";s:8:"whatsapp";s:11:"15997006808";}');

-- --------------------------------------------------------

--
-- Table structure for table `gr_daysenabled`
--

CREATE TABLE IF NOT EXISTS `gr_daysenabled` (
`iddaysenabled` int(11) NOT NULL,
  `date` date NOT NULL,
  `enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_daysenabled`
--

INSERT INTO `gr_daysenabled` (`iddaysenabled`, `date`, `enabled`) VALUES
(4, '2016-07-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_filtermodel`
--

CREATE TABLE IF NOT EXISTS `gr_filtermodel` (
`idfiltermodel` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `regex` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_filtermodel`
--

INSERT INTO `gr_filtermodel` (`idfiltermodel`, `filter`, `regex`) VALUES
(1, '%layer%', '[a-z0-9\\_\\-]{0,45}'),
(2, '%module%', '[a-z0-9\\_\\-]{0,45}'),
(3, '%view%', '[a-z0-9\\_\\-]{0,45}'),
(4, '%year%', '^([1-9][0-9]{3})$'),
(5, '%month-string%', '^(jan|fev|mar|abr|mai|jun|jul|ago|set|out|nov|dez)$|^(janeiro|fevereiro|março|marco|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro)$'),
(6, '%day%', '^((0[1-9])|(1[0-9])|2[0-9]|3[0-1])$'),
(7, '%number%', '^(0[1-9]|1[0-2])$'),
(8, '%month-numeric%', '^(0[1-9]|1[0-2])$'),
(9, '%hour%', '^(0[0-9]|1[0-9]|2[0-3])$'),
(10, '%minute%', '^([0-5][0-9])$'),
(11, '%second%', '^([0-5][0-9])$'),
(12, '%id%', '^(0|0[1-9]|[1-9][0-9]+)$'),
(13, '%title%', '^([a-z0-9\\-\\_]{0,255})$'),
(14, '%name%', '^([a-z0-9\\-\\_]{0,255})$'),
(15, '%category%', '^([a-z0-9\\-\\_]{0,255})$'),
(16, '%author%', '^([a-z0-9\\-\\_]{0,255})$'),
(17, '%url%', '^([a-z0-9\\-\\_]{0,255})$'),
(18, '%language%', '[a-z0-9\\_\\-]{0,5}'),
(19, '%urlcategory%', '^([a-z0-9\\-\\_]{0,255})$'),
(20, '%pageparent%', '^([a-z0-9\\-\\_]{0,255})$'),
(21, '%pagechild%', '^([a-z0-9\\-\\_]{0,255})$'),
(22, '%login%', '^([a-z0-9\\-\\_]{0,255})$');

-- --------------------------------------------------------

--
-- Table structure for table `gr_fraction`
--

CREATE TABLE IF NOT EXISTS `gr_fraction` (
`idfraction` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `formula` varchar(45) NOT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_fraction`
--

INSERT INTO `gr_fraction` (`idfraction`, `name`, `formula`, `fkidmodule`) VALUES
(1, 'teste', 'teste', 15),
(2, 'test', 'teste', 15);

-- --------------------------------------------------------

--
-- Table structure for table `gr_fractiongroup`
--

CREATE TABLE IF NOT EXISTS `gr_fractiongroup` (
`idfractiongroup` int(11) NOT NULL,
  `fkidfraction` int(11) NOT NULL,
  `fkidgroup` int(11) NOT NULL,
  `image` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_fractionproduct`
--

CREATE TABLE IF NOT EXISTS `gr_fractionproduct` (
`idproductfraction` int(11) NOT NULL,
  `fkidproduct` int(11) NOT NULL,
  `fkidfraction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_group`
--

CREATE TABLE IF NOT EXISTS `gr_group` (
`idgroup` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `default` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_group`
--

INSERT INTO `gr_group` (`idgroup`, `name`, `default`) VALUES
(1, 'miller', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_groupfraction`
--

CREATE TABLE IF NOT EXISTS `gr_groupfraction` (
`idgroupfraction` int(11) NOT NULL,
  `fkidgroup` int(11) NOT NULL,
  `fkidfraction` int(11) NOT NULL,
  `image` text
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_groupfraction`
--

INSERT INTO `gr_groupfraction` (`idgroupfraction`, `fkidgroup`, `fkidfraction`, `image`) VALUES
(16, 1, 1, NULL),
(17, 1, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gr_language`
--

CREATE TABLE IF NOT EXISTS `gr_language` (
`idlanguage` int(11) NOT NULL,
  `label` varchar(45) NOT NULL,
  `name` varchar(5) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_language`
--

INSERT INTO `gr_language` (`idlanguage`, `label`, `name`) VALUES
(1, 'English', 'en'),
(2, 'Portuguese Brazil', 'pt-br');

-- --------------------------------------------------------

--
-- Table structure for table `gr_layer`
--

CREATE TABLE IF NOT EXISTS `gr_layer` (
`idlayer` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `label` varchar(45) NOT NULL,
  `url` varchar(255) NOT NULL,
  `urllogin` varchar(255) DEFAULT NULL,
  `filtermodel` varchar(255) DEFAULT NULL,
  `thumbwidth` int(11) DEFAULT NULL,
  `thumbheight` int(11) DEFAULT NULL,
  `defaultsitemap` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidlanguage` int(11) NOT NULL,
  `fkidtemplate` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_layer`
--

INSERT INTO `gr_layer` (`idlayer`, `name`, `label`, `url`, `urllogin`, `filtermodel`, `thumbwidth`, `thumbheight`, `defaultsitemap`, `status`, `fkidlanguage`, `fkidtemplate`) VALUES
(1, 'admin', 'Admin', 'admin/', 'login', '/%login%/%layer%/%language%', NULL, NULL, 'http://www.acoll.com.br', 1, 2, 1),
(2, 'website', 'Website', '/', 'login', '/%login%/%language%', 0, 0, 'http://www.acoll.com.br', 1, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `gr_layermodule`
--

CREATE TABLE IF NOT EXISTS `gr_layermodule` (
`idlayermodule` int(11) NOT NULL,
  `idlayer` int(11) NOT NULL,
  `idmodule` int(11) NOT NULL,
  `filtermodel` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `custom` text,
  `urlcategory` varchar(255) DEFAULT NULL,
  `default` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_layermodule`
--

INSERT INTO `gr_layermodule` (`idlayermodule`, `idlayer`, `idmodule`, `filtermodel`, `url`, `custom`, `urlcategory`, `default`, `status`) VALUES
(1, 1, 1, '/%layer%/%module%/%view%', 'website/gallery/', NULL, NULL, 0, 1),
(2, 1, 2, '/%layer%/%module%/%view%', 'website/layer/', NULL, NULL, 0, 1),
(3, 1, 3, '/%layer%/%module%/%view%', 'website/menu/', NULL, NULL, 0, 1),
(4, 1, 4, '/%layer%/%module%/%view%', 'website/module/', NULL, NULL, 0, 1),
(5, 1, 5, '/%layer%/%module%/%view%', 'website/profile/', NULL, NULL, 0, 1),
(6, 1, 6, '/%layer%/%module%/%view%', 'website/search/', NULL, NULL, 0, 1),
(7, 1, 7, '/%layer%/%module%/%view%', 'website/user/', NULL, NULL, 0, 1),
(8, 1, 8, '/%layer%', '/', NULL, NULL, 1, 1),
(9, 1, 9, '/%layer%/%module%/%view%', 'website/page/', NULL, NULL, 0, 1),
(10, 2, 1, '/%module%/%view%', 'gallery/', NULL, NULL, 0, 1),
(11, 2, 2, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(12, 2, 3, '/%module%/%view%', 'menu/', NULL, NULL, 0, 1),
(13, 2, 4, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(14, 2, 5, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(15, 2, 6, '/%module%/%view%', 'search/', NULL, NULL, 0, 1),
(16, 2, 7, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(17, 2, 8, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(18, 2, 9, '/%pageparent%/%pagechild%', '/', 'a:1:{s:12:"default_page";i:1;}', NULL, 1, 1),
(19, 1, 10, '/%layer%/%module%/%view%', 'website/theme/', NULL, NULL, 0, 1),
(20, 1, 11, '/%layer%/%module%/%view%', 'website/template/', NULL, NULL, 0, 1),
(21, 2, 10, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(22, 2, 11, '/%module%/%view%', NULL, NULL, NULL, 0, 1),
(23, 1, 12, '/%layer%/%module%/%view%', 'website/category/', NULL, NULL, 0, 1),
(24, 2, 12, '/%view%/%urlcategory%', 'category/', NULL, 'categoria/', 0, 1),
(25, 1, 13, '/%module%/%view%', 'authentication/', NULL, NULL, 0, 1),
(26, 2, 13, '/%module%/%view%', 'authentication/', NULL, NULL, 0, 1),
(27, 1, 14, '/%layer%/%module%/%view%', 'website/optional/', NULL, NULL, 0, 1),
(28, 2, 14, '/%module%/%view%', 'optional/', NULL, NULL, 0, 1),
(29, 1, 15, '/%layer%/%module%/%view%', 'website/fraction/', NULL, NULL, 0, 1),
(30, 2, 15, '/%module%/%view%', 'fraction/', NULL, NULL, 0, 1),
(35, 1, 18, '/%layer%/%module%/%view%', 'website/banner/', NULL, NULL, 0, 1),
(36, 2, 18, '/%view%/%id%', 'banner/', NULL, NULL, 0, 1),
(43, 1, 22, '/%layer%/%module%/%view%', 'website/client/', NULL, NULL, 0, 1),
(44, 2, 22, '/%view%/%id%', 'client/', NULL, NULL, 0, 1),
(47, 1, 24, '/%layer%/%module%/%view%', 'website/product/', NULL, NULL, 0, 1),
(48, 2, 24, '/%view%/%urlcategory%/%category%', 'product/', NULL, 'categoria/', 0, 1),
(51, 1, 26, '/%layer%/%module%/%view%', 'website/shop/', NULL, NULL, 0, 1),
(52, 2, 26, '/%view%/%id%', 'shop/', NULL, NULL, 0, 1),
(59, 1, 30, '/%layer%/%module%/%view%', 'website/order/', NULL, NULL, 0, 1),
(60, 2, 30, '/%view%/%id%', 'order/', NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_menu`
--

CREATE TABLE IF NOT EXISTS `gr_menu` (
`idmenu` int(11) NOT NULL,
  `label` varchar(45) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `idmenuparent` int(11) DEFAULT NULL,
  `path` varchar(45) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidmodule` int(11) NOT NULL,
  `fkidlayer` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_menu`
--

INSERT INTO `gr_menu` (`idmenu`, `label`, `icon`, `idmenuparent`, `path`, `keywords`, `status`, `fkidmodule`, `fkidlayer`) VALUES
(1, 'Layer', 'fa fa-fw fa-tasks', NULL, NULL, 'layer,camada', 1, 2, 1),
(2, 'Menu', 'fa fa-fw fa-th-list', NULL, NULL, 'menu', 1, 3, 1),
(3, 'Module', 'fa fa-fw fa-puzzle-piece', NULL, NULL, 'module,módulo', 1, 4, 1),
(4, 'Profile', 'fa fa-fw fa-list-alt', NULL, NULL, 'profile,perfil', 1, 5, 1),
(5, 'Search', 'fa fa-fw fa-search', NULL, 'search/list/', NULL, 1, 6, 1),
(6, 'User', 'fa fa-fw fa-user', NULL, NULL, 'user,usuário', 1, 7, 1),
(7, 'Dashboard', 'fa fa-fw fa-tachometer', NULL, NULL, 'dashboard,painel,home,página inicial', 1, 8, 1),
(8, 'Page', 'fa fa-fw fa-file-o', NULL, NULL, 'page,página', 1, 9, 1),
(9, 'New', 'fa fa-fw fa-file', 1, 'layer/new/', NULL, 1, 2, 1),
(10, 'List', 'fa fa-fw fa-list', 1, 'layer/list/', NULL, 1, 2, 1),
(11, 'Install', 'fa fa-fw fa-file', 3, 'module/install/', NULL, 1, 4, 1),
(12, 'List', 'fa fa-fw fa-list', 3, 'module/list/', NULL, 1, 4, 1),
(13, 'New', 'fa fa-fw fa-file', 4, 'profile/new/', NULL, 1, 5, 1),
(14, 'List', 'fa fa-fw fa-list', 4, 'profile/list/', NULL, 1, 5, 1),
(15, 'New', 'fa fa-fw fa-file', 6, 'user/new/', NULL, 1, 7, 1),
(16, 'List', 'fa fa-fw fa-list', 6, 'user/list/', NULL, 1, 7, 1),
(17, 'New', 'fa fa-fw fa-file', 8, 'page/new/', NULL, 1, 9, 1),
(18, 'List', 'fa fa-fw fa-list', 8, 'page/list/', NULL, 1, 9, 1),
(19, 'Theme', 'fa fa-fw fa-desktop', NULL, NULL, 'theme,tema', 1, 10, 1),
(20, 'New', 'fa fa-fw fa-file', 19, 'theme/new/', NULL, 1, 10, 1),
(21, 'List', 'fa fa-fw fa-list', 19, 'theme/list/', NULL, 1, 10, 1),
(22, 'Template', 'fa fa-fw fa-columns', NULL, NULL, 'template,modelo', 1, 11, 1),
(23, 'New', 'fa fa-fw fa-file', 22, 'template/new/', NULL, 1, 11, 1),
(24, 'List', 'fa fa-fw fa-list', 22, 'template/list/', NULL, 1, 11, 1),
(25, 'Category', 'fa fa-fw fa-flag', NULL, NULL, 'category,categoria', 1, 12, 1),
(26, 'New', 'fa fa-fw fa-file', 25, 'category/new/', NULL, 1, 12, 1),
(27, 'List', 'fa fa-fw fa-list', 25, 'category/list/', NULL, 1, 12, 1),
(28, 'New', 'fa fa-fw fa-file', 2, 'menu/new/', NULL, 1, 3, 1),
(29, 'List', 'fa fa-fw fa-list', 2, 'menu/list/', NULL, 1, 3, 1),
(30, 'Config', 'fa fa-fw fa-cogs', 1, 'layer/config/', 'layer config,camada configuração,camada de configuração', 1, 2, 1),
(31, 'Config', 'fa fa-fw fa-cogs', 8, 'page/config/', 'page config,página configuração,configuração de página', 1, 9, 1),
(32, 'Access Control', 'fa fa-fw fa-lock', 4, 'profile/access-control', 'access control,controle de acesso', 1, 5, 1),
(33, 'Config', 'fa fa-fw fa-cogs', 5, 'search/config/', 'search config,pesquisa configuração, configuração de pesquisa', 1, 6, 1),
(34, 'Optional', 'fa fa-fw fa-plus', NULL, NULL, 'optional,opcional', 1, 14, 1),
(35, 'New', 'fa fa-fw fa-file', 34, 'optional/new/', NULL, 1, 14, 1),
(36, 'List', 'fa fa-fw fa-list', 34, 'optional/list/', NULL, 1, 14, 1),
(37, 'Config', 'fa fa-fw fa-cogs', 34, 'optional/config/', NULL, 1, 14, 1),
(38, 'Fraction', 'fa fa-fw fa-pie-chart', NULL, NULL, NULL, 1, 15, 1),
(39, 'New', 'fa fa-fw fa-file', 38, 'fraction/new/', NULL, 1, 15, 1),
(40, 'List', 'fa fa-fw fa-list', 38, 'fraction//list/', NULL, 1, 15, 1),
(41, 'Config', 'fa fa-fw fa-cogs', 38, 'fraction/config/', NULL, 1, 15, 1),
(57, 'Página inicial', 'fa fa-fw fa-home', NULL, 'home/', NULL, 1, 9, 2),
(58, 'Produtos', NULL, NULL, NULL, NULL, 1, 9, 2),
(59, 'Produto 01', NULL, 58, 'produto01/', NULL, 1, 9, 2),
(60, 'Produto 02', NULL, 58, 'produto02/', NULL, 1, 9, 2),
(61, 'Produto 03', NULL, 58, 'produto03/', NULL, 1, 9, 2),
(62, 'Produto 02.1', NULL, 60, 'produto021/', NULL, 1, 9, 2),
(63, 'Produto 02.2', '', 60, 'produto022/', '', 1, 9, 2),
(64, 'Produto 02.3', '', 60, 'produto023/', '', 1, 9, 2),
(65, 'Empresa', NULL, NULL, 'empresa/', NULL, 1, 9, 2),
(66, 'Contato', NULL, NULL, 'contato/', NULL, 1, 9, 2),
(67, 'Produto 02.2.1', NULL, 63, 'produto0221/', NULL, 1, 9, 2),
(68, 'Produto 02.2.2', NULL, 63, 'produto0222/', NULL, 1, 9, 2),
(69, 'Banner', 'fa fa-fw fa-picture-o', NULL, NULL, 'banner', 1, 18, 1),
(70, 'New', 'fa fa-fw fa-file', 69, 'banner/new/', 'new,novo,novo banner', 1, 18, 1),
(71, 'List', 'fa fa-fw fa-list', 69, 'banner/list/', 'list,listar,listar banner', 1, 18, 1),
(72, 'Category', 'fa fa-fw fa-flag', 69, 'banner/category/', 'category,categoria,categoria do banner', 1, 18, 1),
(73, 'Config', 'fa fa-fw fa-cogs', 69, 'banner/config/', 'config,configuração,configuração do banner', 1, 18, 1),
(86, 'Client', 'fa fa-fw fa-group', NULL, NULL, 'client,cliente', 1, 22, 1),
(87, 'New', 'fa fa-fw fa-file', 86, 'client/new/', 'new,novo,novo cliente', 1, 22, 1),
(88, 'List', 'fa fa-fw fa-list', 86, 'client/list/', 'list,listar,listar cliente', 1, 22, 1),
(89, 'Config', 'fa fa-fw fa-cogs', 86, 'client/config/', 'config,configuração,configuração do cliente', 1, 22, 1),
(97, 'Product', 'fa fa-fw fa-shopping-cart', NULL, NULL, 'product,produto', 1, 24, 1),
(98, 'New', 'fa fa-fw fa-file', 97, 'product/new/', 'new,novo,novo produto', 1, 24, 1),
(99, 'List', 'fa fa-fw fa-list', 97, 'product/list/', 'list,listar,listar produto', 1, 24, 1),
(100, 'Category', 'fa fa-fw fa-flag', 97, 'product/category/', 'category,categoria,categoria do produto', 1, 24, 1),
(101, 'Optional', 'fa fa-fw fa-plus', 97, 'product/optional/', 'optional,opcional,opcional do produto', 1, 24, 1),
(102, 'Fraction', 'fa fa-fw fa-pie-chart', 97, 'product/fraction/', 'fração,frações,fração do produto', 1, 24, 1),
(103, 'Config', 'fa fa-fw fa-cogs', 97, 'product/config/', 'config,configuração,configuração do produto', 1, 24, 1),
(108, 'shop', 'fa fa-fw fa-building-o', NULL, NULL, 'shop,loja,lojas', 1, 26, 1),
(109, 'New', 'fa fa-fw fa-file', 108, 'shop/new/', 'new,novo,nova loja', 1, 26, 1),
(110, 'List', 'fa fa-fw fa-list', 108, 'shop/list/', 'list,listar,listar loja,listar lojas', 1, 26, 1),
(111, 'Config', 'fa fa-fw fa-cogs', 108, 'shop/config/', 'config,configuração,configuração da loja,configuração das lojas', 1, 26, 1),
(124, 'Order', 'fa fa-fw fa-clipboard', NULL, NULL, 'order,pedido,pedidos', 1, 30, 1),
(125, 'New', 'fa fa-fw fa-file', 124, 'order/new/', 'new,novo,novo pedido', 1, 30, 1),
(126, 'List', 'fa fa-fw fa-list', 124, 'order/list/', 'list,listar,listar pedido,listar pedidos', 1, 30, 1),
(127, 'Config', 'fa fa-fw fa-cogs', 124, 'order/config/', 'config,configuração,configuração do pedido,configuração dos pedidos', 1, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_module`
--

CREATE TABLE IF NOT EXISTS `gr_module` (
`idmodule` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `struct` tinyint(1) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `searchable` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_module`
--

INSERT INTO `gr_module` (`idmodule`, `name`, `struct`, `path`, `searchable`, `status`) VALUES
(1, 'gallery', 1, 'griffo/Gallery/', 0, 1),
(2, 'layer', 1, 'griffo/Layer/', 0, 1),
(3, 'menu', 1, 'griffo/Menu/', 1, 1),
(4, 'module', 1, 'griffo/Module/', 0, 1),
(5, 'profile', 1, 'griffo/Profile/', 0, 1),
(6, 'search', 1, 'griffo/Search/', 0, 1),
(7, 'user', 1, 'griffo/User/', 1, 1),
(8, 'dashboard', 1, 'griffo/Dashboard/', 0, 1),
(9, 'page', 1, 'griffo/Page/', 1, 1),
(10, 'theme', 1, 'griffo/Theme/', 0, 1),
(11, 'template', 1, 'griffo/Template/', 0, 1),
(12, 'category', 1, 'griffo/Category/', 1, 1),
(13, 'authentication', 1, 'griffo/Authentication', 0, 1),
(14, 'optional', 1, 'griffo/Optional/', 0, 1),
(15, 'fraction', 1, 'griffo/Fraction/', 0, 1),
(18, 'banner', 0, 'complementary/Banner/', 1, 1),
(22, 'client', 0, 'complementary/Client/', 1, 1),
(24, 'product', 0, 'complementary/Product/', 1, 1),
(26, 'shop', 0, 'complementary/Shop/', 1, 1),
(30, 'order', 0, 'complementary/Order/', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_moduleprofile`
--

CREATE TABLE IF NOT EXISTS `gr_moduleprofile` (
`idmoduleprofile` int(11) NOT NULL,
  `idmodule` int(11) NOT NULL,
  `idprofile` int(11) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_moduleprofile`
--

INSERT INTO `gr_moduleprofile` (`idmoduleprofile`, `idmodule`, `idprofile`, `visible`, `status`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 1, 1, 1),
(3, 3, 1, 1, 1),
(4, 4, 1, 1, 1),
(5, 5, 1, 1, 1),
(6, 6, 1, NULL, 1),
(7, 7, 1, 1, 1),
(8, 8, 1, 1, 1),
(9, 9, 1, 1, 1),
(10, 10, 1, 1, 1),
(11, 11, 1, 1, 1),
(12, 12, 1, 1, 1),
(13, 13, 1, NULL, 1),
(14, 14, 1, 1, 1),
(15, 15, 1, 1, 1),
(18, 1, 2, 1, 1),
(19, 2, 2, 1, 1),
(20, 3, 2, 1, 1),
(21, 4, 2, 1, 1),
(22, 5, 2, 1, 1),
(23, 6, 2, 1, 1),
(24, 7, 2, 1, 1),
(25, 8, 2, 1, 1),
(26, 9, 2, 1, 1),
(27, 10, 2, 1, 1),
(28, 11, 2, 1, 1),
(29, 12, 2, 1, 1),
(30, 13, 2, 1, 1),
(31, 14, 2, 1, 1),
(32, 15, 2, 1, 1),
(34, 18, 1, 1, 1),
(38, 22, 1, 1, 1),
(40, 24, 1, 1, 1),
(42, 26, 1, 1, 1),
(46, 30, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_notification`
--

CREATE TABLE IF NOT EXISTS `gr_notification` (
`idnotification` int(11) NOT NULL,
  `text` text NOT NULL,
  `read` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `fkiduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_optional`
--

CREATE TABLE IF NOT EXISTS `gr_optional` (
`idoptional` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` decimal(7,2) NOT NULL,
  `info` text,
  `image` text,
  `divisible` tinyint(1) NOT NULL,
  `list` text,
  `type` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_optional`
--

INSERT INTO `gr_optional` (`idoptional`, `name`, `value`, `info`, `image`, `divisible`, `list`, `type`, `status`, `fkidmodule`) VALUES
(1, 'teste2', '3.00', NULL, NULL, 0, NULL, '', 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `gr_optionalproduct`
--

CREATE TABLE IF NOT EXISTS `gr_optionalproduct` (
`idoptionproduct` int(11) NOT NULL,
  `fkidoptional` int(11) NOT NULL,
  `fkidproduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_order`
--

CREATE TABLE IF NOT EXISTS `gr_order` (
`idorder` int(11) NOT NULL,
  `fetch` tinyint(1) NOT NULL,
  `formpayment` varchar(45) NOT NULL,
  `change` float DEFAULT NULL,
  `deliveryfee` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `district` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidclient` int(11) NOT NULL,
  `fkidmodule` int(11) NOT NULL,
  `fkidshop` int(11) NOT NULL,
  `fkiddaysenabled` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_orderproduct`
--

CREATE TABLE IF NOT EXISTS `gr_orderproduct` (
`idorderproduct` int(11) NOT NULL,
  `fkidorder` int(11) NOT NULL,
  `fkidproduct` int(11) NOT NULL,
  `quantity` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gr_page`
--

CREATE TABLE IF NOT EXISTS `gr_page` (
`idpage` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` text,
  `metatag` text,
  `url` varchar(255) NOT NULL,
  `idpageparent` int(11) DEFAULT NULL,
  `filecss` varchar(255) DEFAULT NULL,
  `filejs` varchar(255) DEFAULT NULL,
  `fileview` varchar(255) DEFAULT NULL,
  `picture` varchar(45) DEFAULT NULL,
  `keywords` text NOT NULL,
  `authenticate` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_page`
--

INSERT INTO `gr_page` (`idpage`, `title`, `description`, `metatag`, `url`, `idpageparent`, `filecss`, `filejs`, `fileview`, `picture`, `keywords`, `authenticate`, `status`, `fkidmodule`) VALUES
(1, 'Faça seu pedido | Esfiha Show', '', '', 'home/', 0, 'css/home.css', 'js/controllers/homeCtrl.js', 'home', '', '', 0, 1, 9),
(5, 'Finalização do pedido | Esfiha Show', NULL, '', 'finish/', NULL, NULL, 'js/controllers/finishCtrl.js', 'finish', NULL, '', 1, 1, 9),
(3, 'Painel do cliente | Esfiha Show', '', '', 'user/', 0, 'css/user.css', 'js/controllers/userCtrl.js', 'user', '', '', 1, 1, 9),
(4, 'Login | Esfiha Show', '', '', 'login/', 0, '', 'js/controllers/loginCtrl.js', 'login', '', '', 1, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `gr_product`
--

CREATE TABLE IF NOT EXISTS `gr_product` (
`idproduct` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `shortdescription` varchar(85) DEFAULT NULL,
  `description` text,
  `brand` varchar(45) DEFAULT NULL,
  `manufacturer` varchar(45) DEFAULT NULL,
  `supplier` varchar(45) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `packaging` int(11) DEFAULT NULL,
  `weight` decimal(7,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `discount` decimal(7,2) DEFAULT NULL,
  `unitvalue` decimal(7,2) DEFAULT NULL,
  `amount` decimal(7,2) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `picture` text,
  `pictures` text,
  `highlight` tinyint(1) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `registrationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  `fkidcategory` int(11) DEFAULT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_product`
--

INSERT INTO `gr_product` (`idproduct`, `name`, `url`, `code`, `shortdescription`, `description`, `brand`, `manufacturer`, `supplier`, `unit`, `packaging`, `weight`, `stock`, `discount`, `unitvalue`, `amount`, `commission`, `observation`, `keywords`, `picture`, `pictures`, `highlight`, `sort`, `registrationdate`, `status`, `fkidcategory`, `fkidmodule`) VALUES
(1, 'Produto 1', 'produto-1/', NULL, NULL, '<p>Descrição do produto</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 1, 24),
(2, 'Produto 2', 'produto-2/', NULL, NULL, '<p>Descrição do produto</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 1, 24),
(3, 'Produto 3', 'produto-3/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 1, 24),
(4, 'Produto 4', 'produto-4/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 1, 24),
(5, 'Produto 5', 'produto-5/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 2, 24),
(6, 'Produto 6', 'produto-6/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 2, 24),
(7, 'Produto 7', 'produto-7/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 2, 24),
(8, 'Produto 8', 'produto-8/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 2, 24),
(9, 'Produto 9', 'produto-9/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 3, 24),
(10, 'Produto 10', 'produto-10/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 3, 24),
(11, 'Produto 11', 'produto-11/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 3, 24),
(12, 'Produto 12', 'produto-12/', NULL, NULL, '<p>Descrição do produto<br/></p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.50', NULL, NULL, NULL, NULL, 'Produtos/imagem.png', NULL, 0, 1, '2016-06-08 02:00:42', 1, 3, 24);

-- --------------------------------------------------------

--
-- Table structure for table `gr_profile`
--

CREATE TABLE IF NOT EXISTS `gr_profile` (
`idprofile` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `label` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_profile`
--

INSERT INTO `gr_profile` (`idprofile`, `name`, `label`) VALUES
(1, 'admin', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `gr_profilelayer`
--

CREATE TABLE IF NOT EXISTS `gr_profilelayer` (
`idprofilelayer` int(11) NOT NULL,
  `idprofile` int(11) NOT NULL,
  `idlayer` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_profilelayer`
--

INSERT INTO `gr_profilelayer` (`idprofilelayer`, `idprofile`, `idlayer`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(4, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_profilemenu`
--

CREATE TABLE IF NOT EXISTS `gr_profilemenu` (
`idprofilemenu` int(11) NOT NULL,
  `idmenu` int(11) NOT NULL,
  `idprofile` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_profilemenu`
--

INSERT INTO `gr_profilemenu` (`idprofilemenu`, `idmenu`, `idprofile`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 1, 1),
(5, 5, 1, 1),
(6, 6, 1, 1),
(7, 7, 1, 1),
(8, 8, 1, 1),
(9, 9, 1, 1),
(10, 10, 1, 1),
(11, 11, 1, 1),
(12, 12, 1, 1),
(13, 13, 1, 1),
(14, 14, 1, 1),
(15, 15, 1, 1),
(16, 16, 1, 1),
(17, 17, 1, 1),
(18, 18, 1, 1),
(19, 19, 1, 1),
(20, 20, 1, 1),
(21, 21, 1, 1),
(22, 22, 1, 1),
(23, 23, 1, 1),
(24, 24, 1, 1),
(25, 25, 1, 1),
(26, 26, 1, 1),
(27, 27, 1, 1),
(28, 28, 1, 1),
(29, 29, 1, 1),
(30, 30, 1, 1),
(31, 31, 1, 1),
(32, 32, 1, 1),
(33, 33, 1, 1),
(34, 34, 1, 1),
(35, 35, 1, 1),
(36, 36, 1, 1),
(37, 37, 1, 1),
(38, 38, 1, 1),
(39, 39, 1, 1),
(40, 40, 1, 1),
(41, 41, 1, 1),
(56, 1, 2, 1),
(57, 9, 2, 1),
(58, 10, 2, 1),
(59, 30, 2, 1),
(60, 2, 2, 1),
(61, 28, 2, 1),
(62, 29, 2, 1),
(63, 3, 2, 1),
(64, 11, 2, 1),
(65, 12, 2, 1),
(66, 4, 2, 1),
(67, 13, 2, 1),
(68, 14, 2, 1),
(69, 32, 2, 1),
(70, 5, 2, 1),
(71, 33, 2, 1),
(72, 6, 2, 1),
(73, 15, 2, 1),
(74, 16, 2, 1),
(75, 7, 2, 1),
(76, 8, 2, 1),
(77, 17, 2, 1),
(78, 18, 2, 1),
(79, 31, 2, 1),
(80, 19, 2, 1),
(81, 20, 2, 1),
(82, 21, 2, 1),
(83, 22, 2, 1),
(84, 23, 2, 1),
(85, 24, 2, 1),
(86, 25, 2, 1),
(87, 26, 2, 1),
(88, 27, 2, 1),
(89, 34, 2, 1),
(90, 35, 2, 1),
(91, 36, 2, 1),
(92, 37, 2, 1),
(93, 38, 2, 1),
(94, 39, 2, 1),
(95, 40, 2, 1),
(96, 41, 2, 1),
(104, 69, 1, 1),
(105, 70, 1, 1),
(106, 71, 1, 1),
(107, 72, 1, 1),
(108, 73, 1, 1),
(121, 86, 1, 1),
(122, 87, 1, 1),
(123, 88, 1, 1),
(124, 89, 1, 1),
(132, 97, 1, 1),
(133, 98, 1, 1),
(134, 99, 1, 1),
(135, 100, 1, 1),
(136, 101, 1, 1),
(137, 102, 1, 1),
(138, 103, 1, 1),
(143, 108, 1, 1),
(144, 109, 1, 1),
(145, 110, 1, 1),
(146, 111, 1, 1),
(159, 124, 1, 1),
(160, 125, 1, 1),
(161, 126, 1, 1),
(162, 127, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_profileresource`
--

CREATE TABLE IF NOT EXISTS `gr_profileresource` (
`idprofileresource` int(11) NOT NULL,
  `idprofile` int(11) NOT NULL,
  `idresource` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_profileresource`
--

INSERT INTO `gr_profileresource` (`idprofileresource`, `idprofile`, `idresource`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 1, 11, 1),
(12, 1, 12, 1),
(13, 1, 13, 1),
(14, 1, 14, 1),
(15, 1, 15, 1),
(16, 1, 16, 1),
(17, 1, 17, 1),
(18, 1, 18, 1),
(19, 1, 19, 1),
(20, 1, 20, 1),
(21, 1, 21, 1),
(22, 1, 22, 1),
(23, 1, 23, 1),
(24, 1, 24, 1),
(25, 1, 25, 1),
(26, 1, 26, 1),
(27, 1, 27, 1),
(28, 1, 28, 1),
(29, 1, 29, 1),
(30, 1, 30, 1),
(31, 1, 31, 1),
(32, 1, 32, 1),
(33, 1, 33, 1),
(34, 1, 34, 1),
(35, 1, 35, 1),
(36, 1, 36, 1),
(37, 1, 38, 1),
(38, 1, 39, 1),
(39, 1, 40, 1),
(40, 1, 41, 1),
(41, 1, 42, 1),
(42, 1, 43, 1),
(43, 1, 44, 1),
(44, 1, 45, 1),
(45, 1, 46, 1),
(46, 1, 47, 1),
(47, 1, 48, 1),
(48, 1, 49, 1),
(49, 1, 54, 1),
(50, 1, 55, 1),
(51, 1, 56, 1),
(52, 1, 57, 1),
(53, 1, 58, 1),
(54, 1, 59, 1),
(55, 1, 60, 1),
(56, 1, 61, 1),
(57, 1, 62, 1),
(58, 1, 63, 1),
(59, 1, 64, 1),
(60, 1, 65, 1),
(61, 1, 66, 1),
(62, 1, 67, 1),
(73, 2, 1, 1),
(74, 2, 2, 1),
(75, 2, 3, 1),
(76, 2, 4, 1),
(77, 2, 5, 1),
(78, 2, 6, 1),
(79, 2, 7, 1),
(80, 2, 8, 1),
(81, 2, 54, 0),
(82, 2, 9, 1),
(83, 2, 10, 1),
(84, 2, 11, 1),
(85, 2, 12, 1),
(86, 2, 13, 1),
(87, 2, 14, 1),
(88, 2, 15, 1),
(89, 2, 16, 1),
(90, 2, 17, 1),
(91, 2, 18, 1),
(92, 2, 19, 1),
(93, 2, 20, 1),
(94, 2, 56, 0),
(95, 2, 21, 1),
(96, 2, 22, 1),
(97, 2, 23, 1),
(98, 2, 24, 1),
(99, 2, 57, 1),
(100, 2, 25, 1),
(101, 2, 26, 1),
(102, 2, 27, 1),
(103, 2, 28, 1),
(104, 2, 29, 1),
(105, 2, 30, 1),
(106, 2, 31, 1),
(107, 2, 32, 1),
(108, 2, 33, 1),
(109, 2, 34, 1),
(110, 2, 35, 1),
(111, 2, 36, 1),
(112, 2, 55, 1),
(113, 2, 38, 1),
(114, 2, 39, 1),
(115, 2, 40, 1),
(116, 2, 41, 1),
(117, 2, 42, 1),
(118, 2, 43, 1),
(119, 2, 44, 1),
(120, 2, 45, 1),
(121, 2, 46, 1),
(122, 2, 47, 1),
(123, 2, 48, 1),
(124, 2, 49, 1),
(125, 2, 58, 1),
(126, 2, 59, 1),
(127, 2, 60, 1),
(128, 2, 61, 1),
(129, 2, 62, 1),
(130, 2, 63, 1),
(131, 2, 64, 1),
(132, 2, 65, 1),
(133, 2, 66, 1),
(134, 2, 67, 1),
(140, 1, 78, 1),
(141, 1, 79, 1),
(142, 1, 80, 1),
(143, 1, 81, 1),
(144, 1, 82, 1),
(160, 1, 98, 1),
(161, 1, 99, 1),
(162, 1, 100, 1),
(163, 1, 101, 1),
(164, 1, 102, 1),
(170, 1, 108, 1),
(171, 1, 109, 1),
(172, 1, 110, 1),
(173, 1, 111, 1),
(174, 1, 112, 1),
(180, 1, 118, 1),
(181, 1, 119, 1),
(182, 1, 120, 1),
(183, 1, 121, 1),
(184, 1, 122, 1),
(200, 1, 138, 1),
(201, 1, 139, 1),
(202, 1, 140, 1),
(203, 1, 141, 1),
(204, 1, 142, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_resource`
--

CREATE TABLE IF NOT EXISTS `gr_resource` (
`idresource` int(11) NOT NULL,
  `resource` varchar(45) NOT NULL,
  `idresourceparent` int(11) DEFAULT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_resource`
--

INSERT INTO `gr_resource` (`idresource`, `resource`, `idresourceparent`, `fkidmodule`) VALUES
(1, 'insert', NULL, 1),
(2, 'update', NULL, 1),
(3, 'select', NULL, 1),
(4, 'delete', NULL, 1),
(5, 'insert', NULL, 2),
(6, 'update', NULL, 2),
(7, 'select', NULL, 2),
(8, 'delete', NULL, 2),
(9, 'insert', NULL, 3),
(10, 'update', NULL, 3),
(11, 'select', NULL, 3),
(12, 'delete', NULL, 3),
(13, 'install', NULL, 4),
(14, 'update', NULL, 4),
(15, 'select', NULL, 4),
(16, 'delete', NULL, 4),
(17, 'insert', NULL, 5),
(18, 'update', NULL, 5),
(19, 'select', NULL, 5),
(20, 'delete', NULL, 5),
(21, 'insert', NULL, 6),
(22, 'update', NULL, 6),
(23, 'select', NULL, 6),
(24, 'delete', NULL, 6),
(25, 'insert', NULL, 7),
(26, 'update', NULL, 7),
(27, 'select', NULL, 7),
(28, 'delete', NULL, 7),
(29, 'insert', NULL, 8),
(30, 'update', NULL, 8),
(31, 'select', NULL, 8),
(32, 'delete', NULL, 8),
(33, 'insert', NULL, 9),
(34, 'update', NULL, 9),
(35, 'select', NULL, 9),
(36, 'delete', NULL, 9),
(38, 'insert', NULL, 10),
(39, 'update', NULL, 10),
(40, 'select', NULL, 10),
(41, 'delete', NULL, 10),
(42, 'insert', NULL, 11),
(43, 'update', NULL, 11),
(44, 'select', NULL, 11),
(45, 'delete', NULL, 11),
(46, 'insert', NULL, 12),
(47, 'update', NULL, 12),
(48, 'select', NULL, 12),
(49, 'delete', NULL, 12),
(54, 'config', NULL, 2),
(55, 'config', NULL, 9),
(56, 'access-control', NULL, 5),
(57, 'config', NULL, 6),
(58, 'insert', NULL, 14),
(59, 'update', NULL, 14),
(60, 'select', NULL, 14),
(61, 'delete', NULL, 14),
(62, 'config', NULL, 14),
(63, 'insert', NULL, 15),
(64, 'update', NULL, 15),
(65, 'select', NULL, 15),
(66, 'delete', NULL, 15),
(67, 'config', NULL, 15),
(78, 'insert', NULL, 18),
(79, 'update', NULL, 18),
(80, 'select', NULL, 18),
(81, 'delete', NULL, 18),
(82, 'config', NULL, 18),
(98, 'insert', NULL, 22),
(99, 'update', NULL, 22),
(100, 'select', NULL, 22),
(101, 'delete', NULL, 22),
(102, 'config', NULL, 22),
(108, 'insert', NULL, 24),
(109, 'update', NULL, 24),
(110, 'select', NULL, 24),
(111, 'delete', NULL, 24),
(112, 'config', NULL, 24),
(118, 'insert', NULL, 26),
(119, 'update', NULL, 26),
(120, 'select', NULL, 26),
(121, 'delete', NULL, 26),
(122, 'config', NULL, 26),
(138, 'insert', NULL, 30),
(139, 'update', NULL, 30),
(140, 'select', NULL, 30),
(141, 'delete', NULL, 30),
(142, 'config', NULL, 30);

-- --------------------------------------------------------

--
-- Table structure for table `gr_shop`
--

CREATE TABLE IF NOT EXISTS `gr_shop` (
`idshop` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` varchar(200) NOT NULL,
  `number` int(11) NOT NULL,
  `complement` varchar(45) DEFAULT NULL,
  `district` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) DEFAULT NULL,
  `phone1` varchar(30) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `phone3` varchar(30) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `fkidmodule` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_shop`
--

INSERT INTO `gr_shop` (`idshop`, `name`, `address`, `number`, `complement`, `district`, `city`, `state`, `phone1`, `phone2`, `phone3`, `status`, `fkidmodule`) VALUES
(1, 'Loja principal', 'Endereço de exemplo', 123, NULL, 'Teste', 'Itapeva', 'SP', '15999999999', NULL, NULL, 1, 26);

-- --------------------------------------------------------

--
-- Table structure for table `gr_template`
--

CREATE TABLE IF NOT EXISTS `gr_template` (
`idtemplate` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_template`
--

INSERT INTO `gr_template` (`idtemplate`, `name`, `path`) VALUES
(1, 'default', 'default'),
(2, 'Modelo 01', 'modelo01'),
(3, 'Modelo 02', 'modelo02'),
(4, 'Modelo 03', 'modelo03'),
(5, 'Crab', 'crab');

-- --------------------------------------------------------

--
-- Table structure for table `gr_theme`
--

CREATE TABLE IF NOT EXISTS `gr_theme` (
`idtheme` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `fkidlayer` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_theme`
--

INSERT INTO `gr_theme` (`idtheme`, `name`, `path`, `fkidlayer`) VALUES
(1, 'dark', NULL, 1),
(2, 'green', NULL, 1),
(3, 'blue', NULL, 1),
(4, 'red', NULL, 1),
(5, 'Crab', 'crab', 2);

-- --------------------------------------------------------

--
-- Table structure for table `gr_user`
--

CREATE TABLE IF NOT EXISTS `gr_user` (
`iduser` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `nickname` varchar(15) DEFAULT NULL,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `datecad` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(100) DEFAULT NULL,
  `picture` varchar(45) DEFAULT NULL,
  `menuorder` text,
  `status` tinyint(1) NOT NULL,
  `fkidprofile` int(11) DEFAULT NULL,
  `fkidlanguage` int(11) DEFAULT NULL,
  `fkidlayer` int(11) DEFAULT NULL,
  `fkidtheme` int(11) DEFAULT NULL,
  `fkiduserauth` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_user`
--

INSERT INTO `gr_user` (`iduser`, `name`, `nickname`, `login`, `password`, `datecad`, `email`, `picture`, `menuorder`, `status`, `fkidprofile`, `fkidlanguage`, `fkidlayer`, `fkidtheme`, `fkiduserauth`) VALUES
(1, 'Webmaster', 'Webmaster', 'admin', '21232f297a57a5a743894a0e4a801fc3', '2016-07-31 00:55:58', 'felipe@acoll.com.br', '', 'a:18:{i:0;i:8;i:1;i:2;i:2;i:4;i:3;i:3;i:4;i:5;i:5;i:7;i:6;i:9;i:7;i:10;i:8;i:11;i:9;i:12;i:10;i:14;i:11;i:15;i:14;i:18;i:16;i:1;i:17;i:22;i:18;i:24;i:19;i:26;i:20;i:30;}', 1, 1, 2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_userauth`
--

CREATE TABLE IF NOT EXISTS `gr_userauth` (
`iduserauth` int(11) NOT NULL,
  `user` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_userauth`
--

INSERT INTO `gr_userauth` (`iduserauth`, `user`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'employee', 'fa5473530e4d1a5a1e1eb53d2fedb10c'),
(3, 'teste', 'e10adc3949ba59abbe56e057f20f883e'),
(4, 'teste', '698dc19d489c4e4db73e28a713eab07b'),
(5, 'cliente@teste.com', 'f5bb0c8de146c67b44babbf4e6584cc0'),
(6, 'cliente2@teste.com', 'f5bb0c8de146c67b44babbf4e6584cc0'),
(7, 'cliente3@teste.com', 'f5bb0c8de146c67b44babbf4e6584cc0'),
(8, 'cliente4@teste.com', 'f5bb0c8de146c67b44babbf4e6584cc0');

-- --------------------------------------------------------

--
-- Table structure for table `gr_userauthapplication`
--

CREATE TABLE IF NOT EXISTS `gr_userauthapplication` (
`iduserauthapplication` int(11) NOT NULL,
  `iduserauth` int(11) NOT NULL,
  `idapplicationauth` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_userauthapplication`
--

INSERT INTO `gr_userauthapplication` (`iduserauthapplication`, `iduserauth`, `idapplicationauth`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 2, 1, 1),
(4, 2, 2, 1),
(5, 3, 1, 1),
(6, 4, 1, 1),
(7, 5, 2, 1),
(8, 6, 2, 1),
(9, 7, 2, 1),
(10, 8, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gr_usersession`
--

CREATE TABLE IF NOT EXISTS `gr_usersession` (
`idusersession` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `idsession` varchar(45) NOT NULL,
  `accesstoken` varchar(100) DEFAULT NULL,
  `expiry` datetime DEFAULT NULL,
  `datetimein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetimeout` timestamp NULL DEFAULT NULL,
  `fkiduserauth` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gr_usersession`
--

INSERT INTO `gr_usersession` (`idusersession`, `ip`, `idsession`, `accesstoken`, `expiry`, `datetimein`, `datetimeout`, `fkiduserauth`) VALUES
(1, '127.0.0.1', '8lg8jmjb3q7fjuku7hq5bdm1s4', '89!@ad*&153016f434bcf4448737edd5b18fc4de3a7\\%th6>ad', '2015-11-04 11:27:00', '2015-11-04 11:24:00', '2015-11-04 13:40:00', 1),
(2, '127.0.0.1', 'b240g83laeveuh7odfrt8leq03', '89!@ad*&19ca482e0818e561cf5041befbf81dc1fa7\\%th6>ad', '2015-11-04 12:56:00', '2015-11-04 13:40:00', '2015-11-04 17:36:00', 1),
(3, '127.0.0.1', '8iof08ppqu8ad3pe98c0kep285', '89!@ad*&129c1878cd0ddeef45c16fc20f3d6aba8a7\\%th6>ad', '2015-11-04 17:51:00', '2015-11-04 18:26:00', '2015-11-05 09:50:00', 1),
(4, '127.0.0.1', '49taf2huet6pjn7o9bv85sivd7', '89!@ad*&10d8ae552db65d732b21347f555be421da7\\%th6>ad', '2015-11-05 10:06:00', '2015-11-05 11:05:00', '2015-11-05 19:00:00', 1),
(5, '127.0.0.1', 'plr93c286ndv6b5lr7ahslee87', '89!@ad*&173143eb68fdf1b43c61bf6bbae7c3186a7\\%th6>ad', '2015-11-06 12:18:00', '2015-11-06 13:18:00', '2015-11-06 18:48:00', 1),
(6, '127.0.0.1', '4pdtmnk20o9snoidhdheii9050', '89!@ad*&1fcef110ac913bec71c60ff2996179d9ca7\\%th6>ad', '2015-11-06 18:06:00', '2015-11-06 18:48:00', '2015-11-09 10:04:00', 1),
(7, '127.0.0.1', 'hkde7iec2jmd7vemu2jul5ato7', '89!@ad*&111f489ba3dde1dca9bef8757a81b5385a7\\%th6>ad', '2015-11-11 09:54:00', '2015-11-11 10:54:00', '2015-11-11 17:25:00', 1),
(8, '127.0.0.1', 'qfnqp4uffruqf493pfolqmt757', '89!@ad*&151b266048b9545aab90e3fe45e83f822a7\\%th6>ad', '2015-11-11 16:43:00', '2015-11-11 17:25:00', '2015-11-12 13:01:00', 1),
(9, '127.0.0.1', 'ka7esnq1qkuqc5u1v4mr46cn66', '89!@ad*&16f2c0ba7742613e2edd67a9dd035f4eca7\\%th6>ad', '2015-11-12 12:39:00', '2015-11-12 13:01:00', '2015-11-12 18:42:00', 1),
(10, '127.0.0.1', 'su8h5glhff4473mrk94e9f5fi0', '89!@ad*&13072eb8252e1c0735dd2b489dfc7b1eda7\\%th6>ad', '2015-11-12 17:53:00', '2015-11-12 18:53:00', NULL, 1),
(11, '127.0.0.1', 'rmt03jgv685ghqojp2p4vfgrm5', '89!@ad*&1955433133dab21f305b5141b04de77e2a7\\%th6>ad', '2015-11-16 17:46:00', '2015-11-16 18:46:00', '2015-11-17 00:11:00', 1),
(12, '127.0.0.1', 'i124ose5f6os3v89gilaof2ab5', '89!@ad*&128719fd0da65d1b1c093d779fc1902d7a7\\%th6>ad', '2015-11-18 13:07:00', '2015-11-18 13:10:00', '2015-11-18 18:13:00', 1),
(13, '127.0.0.1', 'nllaiaqn75uf50a2ocovhhi4b4', '89!@ad*&13986cd991e04909c461faaac5ded5718a7\\%th6>ad', '2015-11-18 18:08:00', '2015-11-18 18:29:00', '2015-11-19 10:25:00', 1),
(14, '127.0.0.1', 'tlvdji3iocmmlmf0rlhtbrohu1', '89!@ad*&1b0c131b473b222ef46b1222ef9a65ecca7\\%th6>ad', '2015-11-19 18:12:00', '2015-11-19 19:12:00', '2015-11-20 09:46:00', 1),
(15, '127.0.0.1', 'jdljllt32tqr1nhqdc286l38j6', '89!@ad*&14fe5acb4ae6705689a85251ea5e253b9a7\\%th6>ad', '2015-11-20 11:49:00', '2015-11-20 12:30:00', '2015-11-20 18:23:00', 1),
(16, '127.0.0.1', '95aghe459u43jnbruuo2sm34c2', '89!@ad*&17aa3af021f84e5875e1be3f31725f5c6a7\\%th6>ad', '2015-11-20 17:57:00', '2015-11-20 18:24:00', '2015-11-23 18:24:00', 1),
(17, '127.0.0.1', '7q824k98ekldnvncth37i5a0g1', '89!@ad*&1e0b9041ae3b1e08b4d49c8589a4468c2a7\\%th6>ad', '2015-11-25 13:15:00', '2015-11-25 13:49:00', '2015-11-25 18:08:00', 1),
(18, '127.0.0.1', 'n5olt01s2mq33l6jf6vl4s8tb2', '89!@ad*&10f756603b102c8505ee5e64630c872a2a7\\%th6>ad', '2015-11-25 18:16:00', '2015-11-25 18:08:00', '2015-11-26 12:41:00', 1),
(19, '127.0.0.1', 'c1f3k8crnuhrivji9nc339vp36', '89!@ad*&1a4453a547b052a1dfc727fadbb930eb8a7\\%th6>ad', '2015-12-02 12:05:00', '2015-12-02 13:05:00', '2015-12-02 17:34:00', 1),
(20, '127.0.0.1', 'derm43dumg1rr2k24lqtn3nhs1', '89!@ad*&18cd05a027e37214315bd3e3316d0d236a7\\%th6>ad', '2015-12-02 17:28:00', '2015-12-02 18:24:00', '2015-12-03 10:10:00', 1),
(21, '127.0.0.1', 'hgsnfe4m26uhgfbv33tnf778f3', '89!@ad*&1c33511635855c68832a1d9249ea08678a7\\%th6>ad', '2016-01-14 14:54:00', '2016-01-14 15:37:00', NULL, 1),
(22, '127.0.0.1', 's506vrfdp8tdii7qa44rs44c36', '89!@ad*&1cad9d39c70e829e2eaf307e5eec2f92aa7\\%th6>ad', '2016-01-14 18:44:00', '2016-01-15 15:29:06', '2016-01-15 15:29:00', 1),
(23, '127.0.0.1', '7rt2l6ik5v9e2r1cr0kq230lh1', '89!@ad*&1a5fb6283a201132d3465dff455551fe0a7\\%th6>ad', '2016-01-15 15:38:00', '2016-01-15 21:35:04', '2016-01-15 21:35:00', 1),
(24, '127.0.0.1', 'qefkgooa7gg5l864rdv863vgp2', '89!@ad*&1cabc845744994be9c67956e540f76006a7\\%th6>ad', '2016-01-18 13:09:00', '2016-01-18 15:44:25', '2016-01-18 15:44:00', 1),
(25, '127.0.0.1', '0f9kbv5ggfglfgm1i229ueuqt1', '89!@ad*&11c4317ad08cc86c172a1d1986e3e9defa7\\%th6>ad', '2016-01-18 14:45:00', '2016-01-18 15:46:08', '2016-01-18 15:46:00', 1),
(26, '127.0.0.1', 'cri4f5jnsujr16o9bu4ntddv85', '89!@ad*&143cd857d512103b59e9beb8ddfd60207a7\\%th6>ad', '2016-01-18 14:49:00', '2016-01-18 15:49:26', '2016-01-18 15:49:00', 3),
(27, '127.0.0.1', '882jahj6ojalfpqmled6vqhlo1', '89!@ad*&151e0067bc64c8100f713688d7a0278fba7\\%th6>ad', '2016-01-18 14:49:00', '2016-01-18 15:50:16', '2016-01-18 15:50:00', 1),
(28, '127.0.0.1', 'e54sqjrja17fbtnapqf4vh6lb4', '89!@ad*&16c9d30e9777cee9a86553acdb950148fa7\\%th6>ad', '2016-01-18 14:50:00', '2016-01-18 15:50:34', '2016-01-18 15:50:00', 3),
(29, '127.0.0.1', '71k51oaa6c90r495ht3pmamhf5', '89!@ad*&15de02ba02ddce10a613fa6d7d413b263a7\\%th6>ad', '2016-01-18 14:50:00', '2016-01-18 15:51:09', '2016-01-18 15:51:00', 1),
(30, '127.0.0.1', '43pv8a5o6v6cdf1d6hq0vf7uk5', '89!@ad*&1c3afbf5f7ae7b6ad2ce42f1be319305ca7\\%th6>ad', '2016-01-18 14:51:00', '2016-01-18 15:51:56', '2016-01-18 15:51:00', 3),
(31, '127.0.0.1', 'baiidg9k9vfa2q3clh7aafao17', '89!@ad*&14304591173b8fefded0c7935f4ff1efaa7\\%th6>ad', '2016-01-18 14:52:00', '2016-01-18 15:52:26', '2016-01-18 15:52:00', 1),
(32, '127.0.0.1', '2r0fr9r9pu9mctgk5uvph80gp5', '89!@ad*&1aefed22b652cd433c6de64f1b28906ffa7\\%th6>ad', '2016-01-18 14:52:00', '2016-01-18 15:52:48', '2016-01-18 15:52:00', 3),
(33, '127.0.0.1', 'jktati86tg4p08ajv2cj2l5us1', '89!@ad*&1bbaf3aaf67a85b140c8130317db5316ca7\\%th6>ad', '2016-01-18 14:53:00', '2016-01-18 15:53:42', '2016-01-18 15:53:00', 1),
(34, '127.0.0.1', 'q5l9pa5b4gk27tmv4bt051sp84', '89!@ad*&15434a0eb98bff685406486024c36a620a7\\%th6>ad', '2016-01-18 14:56:00', '2016-01-18 15:56:36', '2016-01-18 15:56:00', 4),
(35, '127.0.0.1', 'tblrpj531n7efj7s09a3c35237', '89!@ad*&107fc0c5532144f2478162aeab955fbeba7\\%th6>ad', '2016-01-18 14:56:00', '2016-01-18 15:57:11', '2016-01-18 15:57:00', 4),
(36, '127.0.0.1', 'h5mspkg3b8igueln7bmj6trrr4', '89!@ad*&134eedacadcfedab7c862997a556ee311a7\\%th6>ad', '2016-01-18 15:00:00', '2016-01-19 11:09:20', '2016-01-19 11:09:00', 1),
(37, '127.0.0.1', 'e41ib9dla94gi5487dflghslv7', '89!@ad*&16dd333a292e0b405309546fd80fee531a7\\%th6>ad', '2016-01-19 12:36:00', '2016-01-20 11:02:02', '2016-01-20 11:02:00', 1),
(38, '127.0.0.1', 'stu6od273r5eaji1uqmahrui25', '89!@ad*&10d41d2014d8d7f48b93acee83a678574a7\\%th6>ad', '2016-01-20 10:11:00', '2016-01-20 11:11:03', NULL, 1),
(39, '127.0.0.1', '54n3o1eanek4fammn7uolmpnl7', '89!@ad*&1a6d485dd60d052cd23baadb8c18c5e7da7\\%th6>ad', '2016-01-25 10:39:00', '2016-01-25 15:17:15', '2016-01-25 15:17:00', 1),
(40, '127.0.0.1', '167lsla6pvm2qg175b3e4qv6m3', '89!@ad*&1abeb3c4d3b47b94ce6fa76e4efbb1d5ba7\\%th6>ad', '2016-01-25 16:52:00', '2016-01-25 20:57:54', '2016-01-25 20:57:00', 1),
(41, '127.0.0.1', 'qeiqtbapes6g0ed8md4m79j680', '89!@ad*&1ec70887867ead67c356683389c681c86a7\\%th6>ad', '2016-01-27 16:47:00', '2016-01-27 22:00:21', '2016-01-27 22:00:00', 1),
(42, '127.0.0.1', 'd2ioht4sptlenodgvggqklq7j2', '89!@ad*&188b323336e03b10b067e478f42b647f1a7\\%th6>ad', '2016-01-27 21:38:00', '2016-01-28 11:19:13', '2016-01-28 11:19:00', 1),
(43, '127.0.0.1', 'e057ubtissg4h2k7demecpq373', '89!@ad*&181d9c4d0a4e39226d5adedaf33e4e5eaa7\\%th6>ad', '2016-01-28 12:21:00', '2016-01-28 13:21:00', NULL, 1),
(44, '127.0.0.1', '8hs39lt5mqvh9mmp0lp3bg8ah1', '89!@ad*&1de7279be43d877c41c0da26658780d43a7\\%th6>ad', '2016-01-28 13:09:00', '2016-01-28 15:45:17', '2016-01-28 15:45:00', 1),
(45, '127.0.0.1', 'ujhu7lupu435k75q9vudelhoc1', '89!@ad*&12b9083545ef01e43db9df90f586ac36fa7\\%th6>ad', '2016-01-28 15:34:00', '2016-01-28 18:20:38', '2016-01-28 18:20:00', 1),
(46, '127.0.0.1', 'nn0l6qmb8bo6cab9qq0u17ce72', '89!@ad*&1af064e8929c94443cb3e6baa00c1c70ea7\\%th6>ad', '2016-01-28 18:58:00', '2016-01-28 19:58:25', NULL, 1),
(47, '127.0.0.1', '5p7h4ehac0auk9baddh1hrgd91', '89!@ad*&1a7b38fd5c0350a8f4822327ea02ecb90a7\\%th6>ad', '2016-01-29 11:52:00', '2016-01-29 13:59:57', '2016-01-29 13:59:00', 1),
(48, '127.0.0.1', 'qv33e2kn1qt9fvl2qsefmff8o5', '89!@ad*&1a1f7b78a2de6d3f748f3ca57b70818bfa7\\%th6>ad', '2016-01-29 14:31:00', '2016-01-29 15:31:53', '2016-01-29 15:31:00', 1),
(49, '127.0.0.1', 'gfo1ool37uhp4ohe8800i09fc0', '89!@ad*&120e5d545086f8180a5cce3b2d62359d4a7\\%th6>ad', '2016-01-29 14:40:00', '2016-01-29 15:40:00', NULL, 1),
(50, '127.0.0.1', '1atcp4d6k8f0i36l51qajj5dt0', '89!@ad*&1700b1d646481b07824ffb65d8dbef934a7\\%th6>ad', '2016-01-29 15:02:00', '2016-01-29 16:08:16', '2016-01-29 16:08:00', 1),
(51, '127.0.0.1', 'shcknb3h1clqtdqd0nn7318vv2', '89!@ad*&113e92f379718912d04a0ac5b36e4c583a7\\%th6>ad', '2016-05-18 01:24:00', '2016-05-24 23:02:19', '2016-05-24 23:02:00', 1),
(52, '127.0.0.1', 'aogeqekgvplavcstp006708rj0', '89!@ad*&1fcc7824e5c31506e91aae0572f347751a7\\%th6>ad', '2016-05-25 02:02:00', '2016-05-25 15:58:53', '2016-05-25 15:58:00', 1),
(53, '127.0.0.1', 'mjab2gpod0qkhm939bnsf50bq0', '89!@ad*&173e7b363cb66b9c8b73e33dbec78e39ea7\\%th6>ad', '2016-05-25 14:22:00', '2016-05-27 18:40:57', '2016-05-27 18:40:00', 1),
(54, '127.0.0.1', 's6pfcvgoiok1ekskrj4qsrim96', '89!@ad*&182b89d5cda7dc040b4c098ee82053850a7\\%th6>ad', '2016-06-07 23:56:00', '2016-06-08 01:56:53', NULL, 1),
(55, '127.0.0.1', '369mhf4tnekhjrihvoe4p7qgr3', '89!@ad*&183e9ee07ed93796fa8693c36ebba94b0a7\\%th6>ad', NULL, '2016-06-08 02:00:14', NULL, 1),
(56, '127.0.0.1', 'ug3f1374n300b84jr3q75s1vc2', '89!@ad*&18e80dbca70179ccb045c941078000037a7\\%th6>ad', '2016-06-08 02:36:00', '2016-06-08 10:38:17', '2016-06-08 10:38:00', 1),
(57, '127.0.0.1', 'k86umi39ionj0did8s9aqn9ou2', '89!@ad*&17903c5b97d9dddcca832688421487781a7\\%th6>ad', '2016-06-13 09:41:00', '2016-06-13 11:41:44', NULL, 1),
(58, '127.0.0.1', 'j4jp1g0k0mr3gfp2e3avoilfr7', '89!@ad*&10b8182ed7228aea67bdc16f60cd7bf11a7\\%th6>ad', '2016-07-30 22:21:00', '2016-07-31 00:21:30', NULL, 1),
(59, '127.0.0.1', 'ducrcgqra4qcn587ml4lc37ml2', '89!@ad*&1c3d641c774de77be4cd28e80ab3eb23ca7\\%th6>ad', '2016-07-30 23:09:00', '2016-07-31 01:09:55', NULL, 1),
(60, '127.0.0.1', 'bjnukq92nsvv732u1pg67353r4', '89!@ad*&1c898558903849ee37913aecb8c8cc0c1a7\\%th6>ad', NULL, '2016-07-31 02:00:40', NULL, 1),
(61, '127.0.0.1', '9urfjbl70g20hl23pk6oshrl81', '89!@ad*&190e435b04295d12df03909a031b18361a7\\%th6>ad', '2016-07-31 02:27:00', '2016-07-31 04:27:26', NULL, 1),
(62, '127.0.0.1', 'hmg7jlo8hd646kifi3ds3vhf76', '89!@ad*&1609e4f5b79e092f9f744c0d3494524a5a7\\%th6>ad', '2016-07-31 02:31:00', '2016-07-31 18:47:35', '2016-07-31 18:47:00', 1),
(63, '127.0.0.1', 'll9e2cjjjfoe37v9dolfinjpb5', '89!@ad*&178313c3ed83dcdee37880fd1d5564a2aa7\\%th6>ad', '2016-07-31 16:47:00', '2016-07-31 22:53:11', '2016-07-31 22:53:00', 1),
(64, '127.0.0.1', '6sdehdhk4t89aib9g7cbhe07m3', '89!@ad*&11e4597cb5a2fd3f8036957858550b01fa7\\%th6>ad', '2016-07-31 16:55:00', '2016-07-31 21:03:28', '2016-07-31 21:03:00', 5),
(65, '127.0.0.1', 'u611f502p9ajn8g0s90k329u53', '89!@ad*&1454d35e2b4c9989b3f94c0e90a721ec2a7\\%th6>ad', '2016-07-31 19:19:00', '2016-07-31 21:21:16', '2016-07-31 21:21:00', 5),
(66, '127.0.0.1', 's4g9inoi90vl87g1i8gadm5kv4', '89!@ad*&1a1eb66c452ecac6fff57e7027daaeddba7\\%th6>ad', '2016-07-31 19:22:00', '2016-07-31 21:22:56', '2016-07-31 21:22:00', 5),
(67, '127.0.0.1', 'qqeqhmrpcipmmc0vsevht9p4t6', '89!@ad*&1fced6ab15f6b712387f7b2a9fd44bd78a7\\%th6>ad', '2016-07-31 19:41:00', '2016-07-31 21:41:27', '2016-07-31 21:41:00', 6),
(68, '127.0.0.1', 'tirv7s3oe81cnedgvqv166skb2', '89!@ad*&1f4c53f73051c0b2f172c005d74c2b41ca7\\%th6>ad', '2016-07-31 19:43:00', '2016-07-31 21:43:40', '2016-07-31 21:43:00', 7),
(69, '127.0.0.1', '6h73ttf7ml3o66g680hh1udku1', '89!@ad*&1b4f24cf160d023ec1056f0508f44d36ea7\\%th6>ad', '2016-07-31 19:50:00', '2016-07-31 21:52:38', '2016-07-31 21:52:00', 8),
(70, '127.0.0.1', 'v4m6bj6uh6sl7plvkptev1p1c1', '89!@ad*&147d78313df033271630fa6521f49b4bba7\\%th6>ad', '2016-07-31 20:51:00', '2016-07-31 22:51:28', NULL, 8),
(71, '127.0.0.1', 'l07u6dgfuu6f4fi3ba9m9vnq36', '89!@ad*&16e4ae2df644dd807aa5ca72bcb5bef81a7\\%th6>ad', '2016-07-31 20:53:00', '2016-07-31 22:53:00', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gr_activitylog`
--
ALTER TABLE `gr_activitylog`
 ADD PRIMARY KEY (`idactivitylog`), ADD KEY `fk_ActivityLog_userSession1_idx` (`fkidusersession`), ADD KEY `activity` (`activity`), ADD KEY `date` (`date`);

--
-- Indexes for table `gr_applicationauth`
--
ALTER TABLE `gr_applicationauth`
 ADD PRIMARY KEY (`idapplicationauth`);

--
-- Indexes for table `gr_banner`
--
ALTER TABLE `gr_banner`
 ADD PRIMARY KEY (`idbanner`), ADD KEY `name` (`title`), ADD KEY `fk_gr_banner_gr_category_idx` (`fkidcategory`), ADD KEY `fk_gr_banner_gr_module1_idx` (`fkidmodule`);

--
-- Indexes for table `gr_block`
--
ALTER TABLE `gr_block`
 ADD PRIMARY KEY (`idblock`), ADD KEY `fk_gr_block_gr_page1_idx` (`fkidpage`), ADD KEY `name` (`name`), ADD FULLTEXT KEY `content` (`content`);

--
-- Indexes for table `gr_category`
--
ALTER TABLE `gr_category`
 ADD PRIMARY KEY (`idcategory`), ADD KEY `idcategoryparent` (`idcategoryparent`), ADD KEY `category` (`name`), ADD KEY `fk_gr_category_gr_module1_idx` (`fkidmodule`), ADD KEY `url` (`url`), ADD FULLTEXT KEY `name` (`name`);

--
-- Indexes for table `gr_client`
--
ALTER TABLE `gr_client`
 ADD PRIMARY KEY (`idclient`), ADD KEY `fk_gr_client_gr_module` (`fkidmodule`), ADD KEY `fk_gr_client_gr_shop1_idx` (`fkidshop`), ADD FULLTEXT KEY `name` (`name`,`address`);

--
-- Indexes for table `gr_customer`
--
ALTER TABLE `gr_customer`
 ADD PRIMARY KEY (`idcustomer`), ADD KEY `name` (`name`);

--
-- Indexes for table `gr_daysenabled`
--
ALTER TABLE `gr_daysenabled`
 ADD PRIMARY KEY (`iddaysenabled`);

--
-- Indexes for table `gr_filtermodel`
--
ALTER TABLE `gr_filtermodel`
 ADD PRIMARY KEY (`idfiltermodel`), ADD KEY `filter` (`filter`);

--
-- Indexes for table `gr_fraction`
--
ALTER TABLE `gr_fraction`
 ADD PRIMARY KEY (`idfraction`), ADD KEY `fk_gr_fraction_gr_module1_idx` (`fkidmodule`);

--
-- Indexes for table `gr_fractiongroup`
--
ALTER TABLE `gr_fractiongroup`
 ADD PRIMARY KEY (`idfractiongroup`), ADD KEY `fk_gr_fraction_has_group_group1_idx` (`fkidgroup`), ADD KEY `fk_gr_fraction_has_group_gr_fraction1_idx` (`fkidfraction`);

--
-- Indexes for table `gr_fractionproduct`
--
ALTER TABLE `gr_fractionproduct`
 ADD PRIMARY KEY (`idproductfraction`), ADD KEY `fk_gr_product_has_gr_fraction_gr_fraction1_idx` (`fkidfraction`), ADD KEY `fk_gr_product_has_gr_fraction_gr_product1_idx` (`fkidproduct`);

--
-- Indexes for table `gr_group`
--
ALTER TABLE `gr_group`
 ADD PRIMARY KEY (`idgroup`);

--
-- Indexes for table `gr_groupfraction`
--
ALTER TABLE `gr_groupfraction`
 ADD PRIMARY KEY (`idgroupfraction`), ADD KEY `fk_gr_group_has_gr_fraction_gr_fraction1_idx` (`fkidfraction`), ADD KEY `fk_gr_group_has_gr_fraction_gr_group1_idx` (`fkidgroup`);

--
-- Indexes for table `gr_language`
--
ALTER TABLE `gr_language`
 ADD PRIMARY KEY (`idlanguage`), ADD KEY `name` (`name`);

--
-- Indexes for table `gr_layer`
--
ALTER TABLE `gr_layer`
 ADD PRIMARY KEY (`idlayer`), ADD KEY `fk_gr_layer_gr_language1_idx` (`fkidlanguage`), ADD KEY `fk_gr_layer_gr_template1_idx` (`fkidtemplate`);

--
-- Indexes for table `gr_layermodule`
--
ALTER TABLE `gr_layermodule`
 ADD PRIMARY KEY (`idlayermodule`), ADD KEY `fk_layer_has_module_module1_idx` (`idmodule`), ADD KEY `fk_layer_has_module_layer1_idx` (`idlayer`);

--
-- Indexes for table `gr_menu`
--
ALTER TABLE `gr_menu`
 ADD PRIMARY KEY (`idmenu`), ADD KEY `idmenuParent` (`idmenuparent`), ADD KEY `fk_menu_module_idx` (`fkidmodule`), ADD KEY `fk_gr_menu_gr_layer1_idx` (`fkidlayer`), ADD FULLTEXT KEY `keywords` (`label`,`keywords`);

--
-- Indexes for table `gr_module`
--
ALTER TABLE `gr_module`
 ADD PRIMARY KEY (`idmodule`);

--
-- Indexes for table `gr_moduleprofile`
--
ALTER TABLE `gr_moduleprofile`
 ADD PRIMARY KEY (`idmoduleprofile`), ADD KEY `fk_gr_module_has_gr_profile_gr_profile1_idx` (`idprofile`), ADD KEY `fk_gr_module_has_gr_profile_gr_module1_idx` (`idmodule`);

--
-- Indexes for table `gr_notification`
--
ALTER TABLE `gr_notification`
 ADD PRIMARY KEY (`idnotification`), ADD KEY `fk_gr_notification_gr_user1_idx` (`fkiduser`);

--
-- Indexes for table `gr_optional`
--
ALTER TABLE `gr_optional`
 ADD PRIMARY KEY (`idoptional`), ADD KEY `fk_gr_optional_gr_module1_idx` (`fkidmodule`);

--
-- Indexes for table `gr_optionalproduct`
--
ALTER TABLE `gr_optionalproduct`
 ADD PRIMARY KEY (`idoptionproduct`), ADD KEY `fk_gr_optional_has_gr_product_gr_product1_idx` (`fkidproduct`), ADD KEY `fk_gr_optional_has_gr_product_gr_optional1_idx` (`fkidoptional`);

--
-- Indexes for table `gr_order`
--
ALTER TABLE `gr_order`
 ADD PRIMARY KEY (`idorder`), ADD KEY `fk_order_gr_client1_idx` (`fkidclient`), ADD KEY `fk_gr_order_gr_module1_idx` (`fkidmodule`), ADD KEY `fk_gr_order_shop1_idx` (`fkidshop`), ADD KEY `fk_gr_order_gr_daysenabled1_idx` (`fkiddaysenabled`);

--
-- Indexes for table `gr_orderproduct`
--
ALTER TABLE `gr_orderproduct`
 ADD PRIMARY KEY (`idorderproduct`), ADD KEY `fk_gr_order_has_gr_product_gr_product1_idx` (`fkidproduct`), ADD KEY `fk_gr_order_has_gr_product_gr_order1_idx` (`fkidorder`);

--
-- Indexes for table `gr_page`
--
ALTER TABLE `gr_page`
 ADD PRIMARY KEY (`idpage`), ADD KEY `url` (`url`), ADD KEY `fk_gr_page_gr_module1_idx` (`fkidmodule`), ADD KEY `idpageparent` (`idpageparent`), ADD FULLTEXT KEY `keywords` (`title`,`description`,`keywords`);

--
-- Indexes for table `gr_product`
--
ALTER TABLE `gr_product`
 ADD PRIMARY KEY (`idproduct`), ADD KEY `name` (`name`), ADD KEY `code` (`code`), ADD KEY `fk_gr_produtct_gr_category` (`fkidcategory`), ADD KEY `url` (`url`), ADD KEY `fk_gr_product_gr_module1` (`fkidmodule`), ADD FULLTEXT KEY `title` (`name`,`shortdescription`,`description`,`keywords`);

--
-- Indexes for table `gr_profile`
--
ALTER TABLE `gr_profile`
 ADD PRIMARY KEY (`idprofile`), ADD KEY `name` (`name`);

--
-- Indexes for table `gr_profilelayer`
--
ALTER TABLE `gr_profilelayer`
 ADD PRIMARY KEY (`idprofilelayer`), ADD KEY `fk_gr_profile_has_gr_layer_gr_layer1_idx` (`idlayer`), ADD KEY `fk_gr_profile_has_gr_layer_gr_profile1_idx` (`idprofile`);

--
-- Indexes for table `gr_profilemenu`
--
ALTER TABLE `gr_profilemenu`
 ADD PRIMARY KEY (`idprofilemenu`), ADD KEY `fk_gr_menu_has_gr_profile_gr_profile1_idx` (`idprofile`), ADD KEY `fk_gr_menu_has_gr_profile_gr_menu1_idx` (`idmenu`);

--
-- Indexes for table `gr_profileresource`
--
ALTER TABLE `gr_profileresource`
 ADD PRIMARY KEY (`idprofileresource`), ADD KEY `fk_profile_has_features_features1_idx` (`idresource`), ADD KEY `fk_profile_has_features_profile1_idx` (`idprofile`);

--
-- Indexes for table `gr_resource`
--
ALTER TABLE `gr_resource`
 ADD PRIMARY KEY (`idresource`), ADD KEY `fk_gr_resources_gr_modules1_idx` (`fkidmodule`);

--
-- Indexes for table `gr_shop`
--
ALTER TABLE `gr_shop`
 ADD PRIMARY KEY (`idshop`), ADD KEY `fk_shop_gr_module_idx` (`fkidmodule`);

--
-- Indexes for table `gr_template`
--
ALTER TABLE `gr_template`
 ADD PRIMARY KEY (`idtemplate`), ADD KEY `name` (`name`);

--
-- Indexes for table `gr_theme`
--
ALTER TABLE `gr_theme`
 ADD PRIMARY KEY (`idtheme`), ADD KEY `name` (`name`), ADD KEY `fk_gr_theme_gr_layer1_idx` (`fkidlayer`);

--
-- Indexes for table `gr_user`
--
ALTER TABLE `gr_user`
 ADD PRIMARY KEY (`iduser`), ADD KEY `login` (`login`), ADD KEY `password` (`password`), ADD KEY `fk_user_profile1_idx` (`fkidprofile`), ADD KEY `fk_gr_user_gr_language1_idx` (`fkidlanguage`), ADD KEY `fk_gr_user_gr_layer1_idx` (`fkidlayer`), ADD KEY `fk_gr_user_gr_theme1_idx` (`fkidtheme`), ADD KEY `fk_gr_user_gr_userauth1_idx` (`fkiduserauth`);

--
-- Indexes for table `gr_userauth`
--
ALTER TABLE `gr_userauth`
 ADD PRIMARY KEY (`iduserauth`);

--
-- Indexes for table `gr_userauthapplication`
--
ALTER TABLE `gr_userauthapplication`
 ADD PRIMARY KEY (`iduserauthapplication`), ADD KEY `fk_gr_userauth_has_gr_application_gr_application1_idx` (`idapplicationauth`), ADD KEY `fk_gr_userauth_has_gr_application_gr_userauth1_idx` (`iduserauth`);

--
-- Indexes for table `gr_usersession`
--
ALTER TABLE `gr_usersession`
 ADD PRIMARY KEY (`idusersession`), ADD KEY `idsession` (`idsession`), ADD KEY `fk_gr_usersession_gr_userauth1_idx` (`fkiduserauth`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gr_activitylog`
--
ALTER TABLE `gr_activitylog`
MODIFY `idactivitylog` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=340;
--
-- AUTO_INCREMENT for table `gr_applicationauth`
--
ALTER TABLE `gr_applicationauth`
MODIFY `idapplicationauth` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gr_banner`
--
ALTER TABLE `gr_banner`
MODIFY `idbanner` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_block`
--
ALTER TABLE `gr_block`
MODIFY `idblock` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_category`
--
ALTER TABLE `gr_category`
MODIFY `idcategory` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gr_client`
--
ALTER TABLE `gr_client`
MODIFY `idclient` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gr_customer`
--
ALTER TABLE `gr_customer`
MODIFY `idcustomer` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_daysenabled`
--
ALTER TABLE `gr_daysenabled`
MODIFY `iddaysenabled` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `gr_filtermodel`
--
ALTER TABLE `gr_filtermodel`
MODIFY `idfiltermodel` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `gr_fraction`
--
ALTER TABLE `gr_fraction`
MODIFY `idfraction` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gr_fractiongroup`
--
ALTER TABLE `gr_fractiongroup`
MODIFY `idfractiongroup` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_fractionproduct`
--
ALTER TABLE `gr_fractionproduct`
MODIFY `idproductfraction` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_group`
--
ALTER TABLE `gr_group`
MODIFY `idgroup` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_groupfraction`
--
ALTER TABLE `gr_groupfraction`
MODIFY `idgroupfraction` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `gr_language`
--
ALTER TABLE `gr_language`
MODIFY `idlanguage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gr_layer`
--
ALTER TABLE `gr_layer`
MODIFY `idlayer` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `gr_layermodule`
--
ALTER TABLE `gr_layermodule`
MODIFY `idlayermodule` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `gr_menu`
--
ALTER TABLE `gr_menu`
MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=128;
--
-- AUTO_INCREMENT for table `gr_module`
--
ALTER TABLE `gr_module`
MODIFY `idmodule` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `gr_moduleprofile`
--
ALTER TABLE `gr_moduleprofile`
MODIFY `idmoduleprofile` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `gr_notification`
--
ALTER TABLE `gr_notification`
MODIFY `idnotification` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_optional`
--
ALTER TABLE `gr_optional`
MODIFY `idoptional` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_optionalproduct`
--
ALTER TABLE `gr_optionalproduct`
MODIFY `idoptionproduct` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_order`
--
ALTER TABLE `gr_order`
MODIFY `idorder` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_orderproduct`
--
ALTER TABLE `gr_orderproduct`
MODIFY `idorderproduct` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gr_page`
--
ALTER TABLE `gr_page`
MODIFY `idpage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gr_product`
--
ALTER TABLE `gr_product`
MODIFY `idproduct` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `gr_profile`
--
ALTER TABLE `gr_profile`
MODIFY `idprofile` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_profilelayer`
--
ALTER TABLE `gr_profilelayer`
MODIFY `idprofilelayer` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `gr_profilemenu`
--
ALTER TABLE `gr_profilemenu`
MODIFY `idprofilemenu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT for table `gr_profileresource`
--
ALTER TABLE `gr_profileresource`
MODIFY `idprofileresource` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=205;
--
-- AUTO_INCREMENT for table `gr_resource`
--
ALTER TABLE `gr_resource`
MODIFY `idresource` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT for table `gr_shop`
--
ALTER TABLE `gr_shop`
MODIFY `idshop` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_template`
--
ALTER TABLE `gr_template`
MODIFY `idtemplate` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gr_theme`
--
ALTER TABLE `gr_theme`
MODIFY `idtheme` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gr_user`
--
ALTER TABLE `gr_user`
MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gr_userauth`
--
ALTER TABLE `gr_userauth`
MODIFY `iduserauth` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `gr_userauthapplication`
--
ALTER TABLE `gr_userauthapplication`
MODIFY `iduserauthapplication` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `gr_usersession`
--
ALTER TABLE `gr_usersession`
MODIFY `idusersession` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `gr_banner`
--
ALTER TABLE `gr_banner`
ADD CONSTRAINT `fk_gr_banner_gr_category` FOREIGN KEY (`fkidcategory`) REFERENCES `gr_category` (`idcategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gr_banner_gr_module1` FOREIGN KEY (`fkidmodule`) REFERENCES `gr_module` (`idmodule`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gr_client`
--
ALTER TABLE `gr_client`
ADD CONSTRAINT `fk_gr_client_gr_module` FOREIGN KEY (`fkidmodule`) REFERENCES `gr_module` (`idmodule`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gr_client_gr_shop1` FOREIGN KEY (`fkidshop`) REFERENCES `gr_shop` (`idshop`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gr_order`
--
ALTER TABLE `gr_order`
ADD CONSTRAINT `fk_gr_order_gr_daysenabled1` FOREIGN KEY (`fkiddaysenabled`) REFERENCES `gr_daysenabled` (`iddaysenabled`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gr_order_gr_module1` FOREIGN KEY (`fkidmodule`) REFERENCES `gr_module` (`idmodule`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gr_order_shop1` FOREIGN KEY (`fkidshop`) REFERENCES `gr_shop` (`idshop`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_order_gr_client1` FOREIGN KEY (`fkidclient`) REFERENCES `gr_client` (`idclient`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gr_orderproduct`
--
ALTER TABLE `gr_orderproduct`
ADD CONSTRAINT `fk_gr_order_has_gr_product_gr_order1` FOREIGN KEY (`fkidorder`) REFERENCES `gr_order` (`idorder`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gr_order_has_gr_product_gr_product1` FOREIGN KEY (`fkidproduct`) REFERENCES `gr_product` (`idproduct`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gr_shop`
--
ALTER TABLE `gr_shop`
ADD CONSTRAINT `fk_shop_gr_module` FOREIGN KEY (`fkidmodule`) REFERENCES `gr_module` (`idmodule`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
