-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 19, 2025 at 02:29 AM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fashionstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

DROP TABLE IF EXISTS `about_us`;
CREATE TABLE IF NOT EXISTS `about_us` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `content_2` mediumtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `title`, `content`, `content_2`, `image_url`, `updated_at`, `created_at`) VALUES
(1, 'Our Story', 'Founded to bring quality fashion at fair prices. Update this content in Admin.', 'Content 2 Giving back is core to who we are—from local partnerships to global causes.', '/admin/assets/images/content/about_1_fe2a8f69.webp', '2025-09-05 02:39:13', '2025-09-04 09:00:52'),
(2, 'Our Mission', 'We aim to empower self-expression through accessible, high-quality fashion.', 'Content 2 Giving back is core to who we are—from local partnerships to global causes.', '/admin/assets/images/content/about_1_fe2a8f69.webp', '2025-09-05 02:39:19', '2025-09-04 12:36:57'),
(3, 'Craftsmanship', 'Every piece is thoughtfully designed and rigorously tested for durability.', 'Content 2 Giving back is core to who we are—from local partnerships to global causes.', '/admin/assets/images/content/about_1_fe2a8f69.webp', '2025-09-05 02:39:24', '2025-09-04 12:36:57'),
(4, 'Sustainability', 'We prioritize responsible sourcing and reduced waste in our operations.', 'Content 2 Giving back is core to who we are—from local partnerships to global causes.', '/admin/assets/images/content/about_1_fe2a8f69.webp', '2025-09-05 02:39:30', '2025-09-04 12:36:57'),
(5, 'Community', 'Giving back is core to who we are—from local partnerships to global causes.', 'Content 2 Giving back is core to who we are—from local partnerships to global causes.', '/admin/assets/images/content/about_1_fe2a8f69.webp', '2025-09-05 02:27:47', '2025-09-04 12:36:57'),
(7, 'Tesdf523525234', 'Tesdf523525235235 32', 'hh4523626vs content23  324', '/admin/assets/images/content/about_2415cd11.webp', '2025-09-05 02:27:42', '2025-09-05 02:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `business_hours` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_addresses_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `line1`, `line2`, `city`, `state`, `postal`, `country`, `is_default`, `business_hours`) VALUES
(1, 1, '30, 81 street', 'chamrern', 'phnom penh', 'cambodia', '121208', 'KH', 1, NULL),
(2, 2, '11sangkat teuk thla, kan sensok, phnom penh', '111sangkat teuk thla, kan sensok, phnom penh', 'Phnom Penh, Cambodia', 'cambodia', '334234', 'CA', 1, 'Mondays-Fridays: 9am-6pm\r\nSaturdays: 10am-4pm\r\nSundays: Closed');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_text` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_banners_active_position` (`is_active`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image_url`, `link_url`, `alt_text`, `position`, `is_active`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(1, 'Testing', 'Testing', '/admin/assets/images/banners/slider1-1758332133-e48f65fa.png', NULL, 'Testing', 1, 1, '2025-09-20 08:35:00', '2025-09-26 08:35:00', '2025-09-20 01:35:33', '2025-09-20 01:35:33'),
(2, 'Summer Sale', 'Up to 50% Off', '/admin/assets/images/carousel/banner_1.jpg', '/?p=products', 'Summer Sale Banner', 1, 1, NULL, NULL, '2025-09-20 01:41:11', '2025-09-20 01:41:11'),
(3, 'New Arrivals', 'Latest Styles In', '/admin/assets/images/carousel/banner_2.jpg', '/?p=products', 'New Arrivals Banner', 2, 1, NULL, NULL, '2025-09-20 01:41:11', '2025-09-20 01:41:11'),
(4, 'Men\'s Collection', 'Fresh Looks for Men', '/admin/assets/images/carousel/banner_3.jpg', '/?p=products', 'Men Collection Banner', 3, 1, NULL, NULL, '2025-09-20 01:41:11', '2025-09-20 01:41:11'),
(5, 'Women\'s Collection', 'Trendy Picks for Her', '/admin/assets/images/carousel/banner_4.jpg', '/?p=products', 'Women Collection Banner', 4, 1, NULL, NULL, '2025-09-20 01:41:11', '2025-09-20 01:41:11'),
(6, 'Accessories Picks', 'Complete Your Style', '/admin/assets/images/carousel/banner_5.jpg', '/?p=products', 'Accessories Banner', 5, 1, NULL, NULL, '2025-09-20 01:41:11', '2025-09-20 01:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo_url`, `created_at`) VALUES
(1, 'Nike', 'nike', 'nike', '/admin/assets/images/brands/34480-logo-nike-1757058659-2ed97e42.png', '2025-09-05 07:50:59'),
(2, 'Adidas', 'adidas', 'Adidas', '/admin/assets/images/brands/company-logo-32459-1757058715-2bdf4aa6.png', '2025-09-05 07:51:55'),
(3, 'Puma', 'puma', 'Puma', '/admin/assets/images/brands/163994-logo-puma-1757058743-f4ec68ef.png', '2025-09-05 07:52:23'),
(4, 'Reebok', 'reebok', 'Fitness-focused shoes and apparel.', '/admin/assets/images/brands/reebok-1757059371-56c7fa25.png', '2025-09-05 07:55:57'),
(5, 'New Balance', 'new-balance', 'Comfort-first running and lifestyle shoes.', '/admin/assets/images/brands/new-balance-1757059391-98a40933.png', '2025-09-05 07:55:57'),
(6, 'Under Armour', 'under-armour', 'Training gear and performance sportswear.', '/admin/assets/images/brands/72949-logo-under-armour-1757059508-4cbfe71f.png', '2025-09-05 07:55:57'),
(7, 'ASICS', 'asics', 'Running shoes engineered for distance.', '/admin/assets/images/brands/asics-1757059498-21728b6b.png', '2025-09-05 07:55:57'),
(8, 'Converse', 'converse', 'Classic canvas sneakers and street style.', '/admin/assets/images/brands/converse-1757059489-b72a2af9.png', '2025-09-05 07:55:57'),
(9, 'Vans', 'vans', 'Skate-inspired shoes and apparel.', '/admin/assets/images/brands/vans-1757059481-c963f76c.png', '2025-09-05 07:55:57'),
(10, 'FILA', 'fila', 'Retro athletic style and footwear.', '/admin/assets/images/brands/fila-1757059469-a8239cb8.png', '2025-09-05 07:55:57'),
(11, 'Champion', 'champion', 'Iconic athletic apparel and basics.', '/admin/assets/images/brands/champion-1757059456-b50a752e.png', '2025-09-05 07:55:57'),
(12, 'Jordan', 'jordan', 'Premium basketball footwear and apparel.', '/admin/assets/images/brands/jordan-1757059440-ce19c537.png', '2025-09-05 07:55:57'),
(13, 'Skechers', 'skechers', 'Everyday comfort shoes and sneakers.', '/admin/assets/images/brands/skechers-1757059431-920add86.png', '2025-09-05 07:55:57'),
(14, 'Home Goods', 'home-goods', 'Max-cushion performance running shoes.', '/admin/assets/images/brands/home-goods-1757059607-937553d3.png', '2025-09-05 07:55:57'),
(15, 'Brooks Running', 'brooks-running', 'Swiss-engineered running shoes with CloudTec.', '/admin/assets/images/brands/on-running-1757059563-aa9db633.png', '2025-09-05 07:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_cart_session` (`session_id`),
  KEY `idx_cart_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`, `currency`) VALUES
(7, NULL, 't7i277mm4kvn75k0f79lvplv76', '2025-09-16 08:34:06', '2025-09-16 08:34:06', 'USD'),
(8, NULL, 'tf9ru98asorq71qv3d0iu5q455', '2025-09-19 08:42:19', '2025-09-19 08:42:19', 'USD'),
(9, NULL, 't8gr6ujt99ce1emvb458ef9kmv', '2025-09-19 09:05:37', '2025-09-19 09:05:37', 'USD'),
(10, NULL, 'hb3sceq9tgh5cpmdaopvba3qos', '2025-09-19 10:05:31', '2025-09-19 10:05:31', 'USD'),
(11, NULL, 'eejsoas6o9u03c4mng8mvdlrvh', '2025-09-19 10:22:33', '2025-09-19 10:22:33', 'USD'),
(12, NULL, 'uvlfrfkpnid7qui63p4j2b1qet', '2025-09-19 10:51:32', '2025-09-19 10:51:32', 'USD'),
(13, NULL, 'j0nvpguob23r16euta65gco85a', '2025-09-19 10:58:22', '2025-09-19 10:58:22', 'USD'),
(14, NULL, 'p2fsh6q3qcc19g09827lbobvn0', '2025-09-20 01:59:38', '2025-09-20 01:59:38', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `applied_promo_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_cart_variant` (`cart_id`,`variant_id`),
  KEY `fk_cart_items_variant` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `variant_id`, `qty`, `unit_price`, `applied_promo_code`) VALUES
(24, 10, 9, 6, '34.00', NULL),
(30, 11, 9, 2, '34.00', NULL),
(32, 12, 9, 2, '34.00', NULL),
(33, 13, 9, 2, '34.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_categories_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`) VALUES
(1, 'Clothing', 'clothing', NULL),
(2, 'Electronics', 'electronics', NULL),
(3, 'Home & Kitchen', 'home-kitchen', NULL),
(4, 'Beauty & Personal Care', 'beauty-personal-care', NULL),
(5, 'Sports & Outdoors', 'sports-outdoors', NULL),
(6, 'Books', 'books', NULL),
(7, 'Toys & Games', 'toys-games', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `checkout_orders`
--

DROP TABLE IF EXISTS `checkout_orders`;
CREATE TABLE IF NOT EXISTS `checkout_orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('card','paypal','apple_pay') COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_last4` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `card_exp_year` smallint(5) UNSIGNED DEFAULT NULL,
  `card_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkout_orders`
--

INSERT INTO `checkout_orders` (`id`, `first_name`, `last_name`, `email`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country_code`, `payment_method`, `card_last4`, `card_exp_month`, `card_exp_year`, `card_name`, `terms_accepted`, `created_at`) VALUES
(1, 'Tieng', 'Chamrern', 'admin@gmail.com', '(096) 779-7762', 'Khan Sensok, Phnom Penh', 'Sales', 'Phnom Penh', 'cambodia', '12080', 'US', 'card', '4234', 42, 2034, 'Tieng chmarern', 1, '2025-09-16 07:56:57'),
(2, 'Mao', 'Orio', 'chamrern@gmail.com', '(042) 342-2342', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'CA', 'card', '2352', 52, 2035, 'Moa oere', 1, '2025-09-16 08:01:39'),
(3, 'customer1', 'customer1', 'customer1@gmail.com', '(042) 423-5235', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'DE', 'card', '6266', 23, 2042, 'customer1', 1, '2025-09-19 08:43:47'),
(4, 'customer1', 'customer1', 'customer1@gmail.com', '(052) 523-5252', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'CA', 'card', '6261', 34, 2015, 'customer1', 1, '2025-09-19 09:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE IF NOT EXISTS `colors` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `hex`, `created_at`) VALUES
(1, 'Black', '#000000', '2025-09-05 09:54:25'),
(2, 'White', '#FFFFFF', '2025-09-05 09:54:25'),
(3, 'Red', '#FF0000', '2025-09-05 09:54:25'),
(4, 'Green', '#008000', '2025-09-05 09:54:25'),
(5, 'Blue', '#0000FF', '2025-09-05 09:54:25'),
(6, 'Yellow', '#FFFF00', '2025-09-05 09:54:25'),
(7, 'Orange', '#FFA500', '2025-09-05 09:54:25'),
(8, 'Purple', '#800080', '2025-09-05 09:54:25'),
(9, 'Pink', '#FFC0CB', '2025-09-05 09:54:25'),
(10, 'Brown', '#A52A2A', '2025-09-05 09:54:25'),
(11, 'Gray', '#808080', '2025-09-05 09:54:25'),
(12, 'Cyan', '#00FFFF', '2025-09-05 09:54:25'),
(13, 'Maroon', '#800000', '2025-09-05 09:54:25'),
(14, 'Teal', '#008080', '2025-09-05 09:54:25'),
(15, 'Light Blue', '#ADD8E6', '2025-09-05 09:54:25');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','read','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contact_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `attachment_url`, `status`, `created_at`, `updated_at`) VALUES
(4, '23423423', 'tiengchamrern2@gmail.com', NULL, 'hamrern', 'Tieng Chamrern', 'admin/assets/images/contact_attachments/contact_1756987955_130cff7a.webp', 'new', '2025-09-04 12:12:35', '2025-09-04 12:14:57'),
(5, '42352', 'chamrern1@gmail.com', NULL, '34242', '423424', 'admin/assets/images/contact_attachments/contact_1756988086_82a875f9.webp', 'new', '2025-09-04 12:14:46', '2025-09-04 12:14:46'),
(6, 'Mr mao', 'chamrern@gmail.com', NULL, 'Customer', 'template responsive bootstrap and css free ecommerce website templates', 'admin/assets/images/contact_attachments/contact_1756988464_0acd2e68.webp', 'new', '2025-09-04 12:21:05', '2025-09-04 12:21:05'),
(7, 'chamrern3525', 'tiengchamrern2@gmail.com', NULL, 'chamrern3525', 'chamrern3525', 'admin/assets/images/contact_attachments/contact_1757045349_0f933c99.webp', 'new', '2025-09-05 04:09:09', '2025-09-05 04:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` decimal(18,6) NOT NULL DEFAULT '1.000000',
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decimal_places` tinyint(3) UNSIGNED NOT NULL DEFAULT '2',
  `position` enum('prefix','suffix') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prefix',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_currencies_code` (`code`),
  KEY `idx_currencies_active_position` (`is_active`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `name`, `exchange_rate`, `symbol`, `decimal_places`, `position`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'USD', 'US Dollar', '1.000000', '$', 2, 'prefix', 1, 1, '2025-09-05 02:49:41', '2025-09-12 07:26:58'),
(2, 'EUR', 'Euro', '0.920000', '€', 2, 'suffix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 03:24:44'),
(3, 'GBP', 'British Pound', '0.800000', '£', 2, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(4, 'JPY', 'Japanese Yen', '150.000000', '¥', 0, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(5, 'INR', 'Indian Rupee', '83.000000', '₹', 2, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(6, 'KHR', 'KHR Riel', '4100.000000', '៛', 2, 'prefix', 0, 1, '2025-09-05 03:24:30', '2025-09-12 07:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `finance_categories`
--

DROP TABLE IF EXISTS `finance_categories`;
CREATE TABLE IF NOT EXISTS `finance_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_finance_categories` (`name`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_categories`
--

INSERT INTO `finance_categories` (`id`, `name`, `type`, `is_active`, `created_at`) VALUES
(1, 'Product Sales', 'income', 1, '2025-09-19 08:27:41'),
(2, 'Shipping Fees', 'income', 1, '2025-09-19 08:27:41'),
(3, 'Misc Income', 'income', 1, '2025-09-19 08:27:41'),
(4, 'Payment Gateway Fees', 'expense', 1, '2025-09-19 08:27:41'),
(5, 'Returns/Refunds', 'expense', 1, '2025-09-19 08:27:41'),
(6, 'Marketing', 'expense', 1, '2025-09-19 08:27:41'),
(7, 'Salaries', 'expense', 1, '2025-09-19 08:27:41'),
(8, 'Rent', 'expense', 1, '2025-09-19 08:27:41'),
(9, 'Utilities', 'expense', 1, '2025-09-19 08:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `finance_entries`
--

DROP TABLE IF EXISTS `finance_entries`;
CREATE TABLE IF NOT EXISTS `finance_entries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_type` enum('order','payment','return','shipment','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_finance_entries_user` (`created_by`),
  KEY `idx_finance_entries_date` (`entry_date`),
  KEY `idx_finance_entries_type` (`type`),
  KEY `idx_finance_entries_category` (`category_id`),
  KEY `idx_finance_entries_ref` (`reference_type`,`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_entries`
--

INSERT INTO `finance_entries` (`id`, `entry_date`, `type`, `category_id`, `amount`, `currency`, `description`, `reference_type`, `reference_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2025-09-09', 'income', 1, '249.99', 'USD', 'Order FS-10021', 'order', 10021, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(2, '2025-09-10', 'income', 1, '189.50', 'USD', 'Order FS-10022', 'order', 10022, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(3, '2025-09-10', 'income', 2, '12.00', 'USD', 'Shipping collected FS-10022', 'order', 10022, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(4, '2025-09-11', 'income', 1, '315.00', 'USD', 'Order FS-10023', 'order', 10023, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(5, '2025-09-12', 'income', 3, '50.00', 'USD', 'Supplier rebate', 'other', NULL, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(6, '2025-09-17', 'income', 1, '420.00', 'USD', 'Order FS-10030', 'order', 10030, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(7, '2025-09-09', 'expense', 4, '5.00', 'USD', 'Stripe fee FS-10021', 'payment', 210021, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(8, '2025-09-10', 'expense', 4, '4.20', 'USD', 'Stripe fee FS-10022', 'payment', 210022, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(9, '2025-09-11', 'expense', 5, '39.99', 'USD', 'Partial refund FS-10020', 'return', 30012, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(10, '2025-09-13', 'expense', 6, '120.00', 'USD', 'Meta Ads - Prospecting', 'other', NULL, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(11, '2025-09-14', 'expense', 7, '800.00', 'USD', 'Weekly staff payout', 'other', NULL, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(12, '2025-09-16', 'expense', 8, '600.00', 'USD', 'Warehouse rent (prorated)', 'other', NULL, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(13, '2025-09-18', 'expense', 9, '85.75', 'USD', 'Electricity + Internet', 'other', NULL, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(14, '2025-09-19', 'expense', 4, '7.20', 'USD', 'Stripe fee FS-10030', 'payment', 210030, 1, '2025-09-19 08:27:41', '2025-09-19 08:27:41'),
(15, '2025-09-19', 'income', 1, '3000.00', 'USD', NULL, 'other', 42334, NULL, '2025-09-19 08:31:25', '2025-09-19 08:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty_available` int(11) NOT NULL DEFAULT '0',
  `qty_reserved` int(11) NOT NULL DEFAULT '0',
  `low_stock_threshold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_inventory_variant` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_languages_code` (`code`),
  KEY `idx_languages_active_position` (`is_active`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `native_name`, `position`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'kh', 'Khmer', 'ខ្មែរ', 1, 1, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(7, 'en', 'English', 'English', 2, 0, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(8, 'zh', 'Chinese', '中文', 3, 0, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(9, 'es', 'Spanish', 'Español', 4, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(10, 'hi', 'Hindi', 'हिन्दी', 5, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(11, 'ar', 'Arabic', 'العربية', 6, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(12, 'bn', 'Bengali', 'বাংলা', 7, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(13, 'pt', 'Portuguese', 'Português', 8, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(14, 'ru', 'Russian', 'Русский', 9, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(15, 'ja', 'Japanese', '日本語', 10, 0, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(16, 'pa', 'Punjabi', 'ਪੰਜਾਬੀ', 11, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(17, 'de', 'German', 'Deutsch', 12, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(18, 'jv', 'Javanese', 'Basa Jawa', 13, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(19, 'ko', 'Korean', '한국어', 14, 0, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(20, 'fr', 'French', 'Français', 15, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(21, 'te', 'Telugu', 'తెలుగు', 16, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(22, 'mr', 'Marathi', 'मराठी', 17, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(23, 'ta', 'Tamil', 'தமிழ்', 18, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(24, 'ur', 'Urdu', 'اردو', 19, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(25, 'vi', 'Vietnamese', 'Tiếng Việt', 20, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(26, 'it', 'Italian', 'Italiano', 21, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:05:19'),
(27, 'tr', 'Turkish', 'Türkçe', 22, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(28, 'th', 'Thai', 'ไทย', 23, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(29, 'gu', 'Gujarati', 'ગુજરાતી', 24, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(30, 'pl', 'Polish', 'Polski', 25, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(31, 'uk', 'Ukrainian', 'Українська', 26, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(32, 'ml', 'Malayalam', 'മലയാളം', 27, 0, 1, '2025-12-19 01:41:15', '2025-12-19 02:06:27'),
(33, 'kn', 'Kannada', 'ಕನ್ನಡ', 28, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(34, 'or', 'Odia', 'ଓଡ଼ିଆ', 29, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(35, 'my', 'Burmese', 'မြန်မာဘာသာ', 30, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(36, 'fa', 'Persian', 'فارسی', 31, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(37, 'ps', 'Pashto', 'پښتو', 32, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(38, 'am', 'Amharic', 'አማርኛ', 33, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(39, 'ha', 'Hausa', 'Hausa', 34, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(40, 'yo', 'Yoruba', 'Yorùbá', 35, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(41, 'ig', 'Igbo', 'Asụsụ Igbo', 36, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(42, 'sw', 'Swahili', 'Kiswahili', 37, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(43, 'zu', 'Zulu', 'isiZulu', 38, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(44, 'af', 'Afrikaans', 'Afrikaans', 39, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(45, 'nl', 'Dutch', 'Nederlands', 40, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(46, 'sv', 'Swedish', 'Svenska', 41, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(47, 'no', 'Norwegian', 'Norsk', 42, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(48, 'da', 'Danish', 'Dansk', 43, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(49, 'fi', 'Finnish', 'Suomi', 44, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(50, 'el', 'Greek', 'Ελληνικά', 45, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(51, 'he', 'Hebrew', 'עברית', 46, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(52, 'hu', 'Hungarian', 'Magyar', 47, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(53, 'cs', 'Czech', 'Čeština', 48, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(54, 'ro', 'Romanian', 'Română', 49, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(55, 'bg', 'Bulgarian', 'Български', 50, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(56, 'sr', 'Serbian', 'Српски', 51, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(57, 'hr', 'Croatian', 'Hrvatski', 52, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(58, 'sk', 'Slovak', 'Slovenčina', 53, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(59, 'sl', 'Slovenian', 'Slovenščina', 54, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(60, 'lt', 'Lithuanian', 'Lietuvių', 55, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(61, 'lv', 'Latvian', 'Latviešu', 56, 0, 0, '2025-12-19 01:41:15', '2025-12-19 02:02:08'),
(62, 'et', 'Estonian', 'Eesti', 57, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(63, 'is', 'Icelandic', 'Íslenska', 58, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(64, 'ga', 'Irish', 'Gaeilge', 59, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38'),
(65, 'mt', 'Maltese', 'Malti', 60, 0, 0, '2025-12-19 01:41:15', '2025-12-19 01:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','fulfilled','cancelled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `placed_at` datetime DEFAULT NULL,
  `payment_status` enum('unpaid','authorized','captured','refunded','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_orders_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `discount_total`, `shipping_total`, `tax_total`, `grand_total`, `currency`, `placed_at`, `payment_status`) VALUES
(2, 1, 'FS20250916075657-425', 'pending', '156.00', '0.00', '2.99', '12.87', '171.86', 'USD', '2025-09-16 07:56:57', 'unpaid'),
(3, 1, 'FS20250916080139-110', 'paid', '34.00', '0.00', '2.99', '0.00', '36.99', 'USD', '2025-09-16 08:01:39', 'authorized'),
(4, 2, 'FS20250919084347-603', 'paid', '78.00', '0.00', '2.99', '0.00', '80.99', 'USD', '2025-09-19 08:43:47', 'captured'),
(5, 2, 'FS20250919090855-955', 'paid', '136.00', '0.00', '2.99', '0.00', '138.99', 'USD', '2025-09-19 09:08:55', 'authorized');

-- --------------------------------------------------------

--
-- Table structure for table `order_addresses`
--

DROP TABLE IF EXISTS `order_addresses`;
CREATE TABLE IF NOT EXISTS `order_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `address_type` enum('shipping','billing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'shipping',
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_addresses_order` (`order_id`),
  KEY `idx_order_addresses_type` (`order_id`,`address_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_addresses`
--

INSERT INTO `order_addresses` (`id`, `order_id`, `address_type`, `full_name`, `email`, `phone`, `line1`, `line2`, `city`, `state`, `postal`, `country`) VALUES
(2, 2, 'shipping', 'Tieng Chamrern', 'admin@gmail.com', '(096) 779-7762', 'Khan Sensok, Phnom Penh', 'Sales', 'Phnom Penh', 'cambodia', '12080', 'US'),
(3, 3, 'shipping', 'Mao Orio', 'chamrern@gmail.com', '(042) 342-2342', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'CA'),
(4, 4, 'shipping', 'customer1 customer1', 'customer1@gmail.com', '(042) 423-5235', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'DE'),
(5, 5, 'shipping', 'customer1 customer1', 'customer1@gmail.com', '(052) 523-5252', '1Khan Sensok, Phnom Penh', 'Designer', 'Phnom Penh', 'cambodia', '32423', 'CA');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_order_items_variant` (`variant_id`),
  KEY `idx_order_items_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `qty`, `unit_price`, `discount_amount`, `tax_amount`) VALUES
(2, 2, 6, 4, '39.00', '0.00', '0.00'),
(3, 3, 9, 1, '34.00', '0.00', '0.00'),
(4, 4, 6, 2, '39.00', '0.00', '0.00'),
(5, 5, 9, 4, '34.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_txn_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','authorized','captured','refunded','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `captured_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_payments_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `provider`, `provider_txn_id`, `amount`, `status`, `captured_at`) VALUES
(2, 2, 'card', NULL, '171.86', 'captured', '2025-09-19 15:54:00'),
(3, 3, 'card', NULL, '36.99', 'captured', '2025-09-19 15:53:00'),
(4, 4, 'card', NULL, '80.99', 'captured', '2025-09-19 15:52:00'),
(5, 5, 'card', NULL, '138.99', 'captured', '2025-09-20 16:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `stock_qty` int(11) NOT NULL DEFAULT '0',
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gender` enum('men','women','unisex','kids') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `care` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','active','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_products_brand` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `description`, `featured_image`, `price`, `stock_qty`, `discount_percent`, `brand`, `brand_id`, `gender`, `care`, `status`, `created_at`) VALUES
(25, 'Testing', 'testing', 'Testing', 'assets/images/product_images/prod_1757060302_f5994e2f.jpeg', '20.00', 20, '10.00', NULL, NULL, 'men', NULL, 'active', '2025-09-05 08:18:22'),
(26, 'Men\'s Classic Tee', 'prod-sample-001', NULL, 'assets/images/product_images/prod_26_bd03ce43.webp', '19.99', 100, '40.00', 'Nike', 1, 'men', NULL, 'active', '2025-09-05 08:18:49'),
(27, 'Women\'s Running Leggings', 'prod-sample-002', NULL, 'assets/images/product_images/prod_27_44c4ede8.webp', '29.99', 80, NULL, 'Adidas', 2, 'women', NULL, 'active', '2025-09-05 08:18:49'),
(28, 'Kids Hoodie', 'prod-sample-003', NULL, 'assets/images/product_images/prod_28_69504530.webp', '24.99', 60, NULL, 'Puma', 3, 'kids', NULL, 'active', '2025-09-05 08:18:49'),
(29, 'Leather Belt', 'prod-sample-004', NULL, 'assets/images/product_images/prod_29_6e8e991f.jpeg', '15.99', 120, NULL, 'FILA', 10, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(30, 'Android Smartphone X', 'prod-sample-005', NULL, 'assets/images/product_images/prod_30_9e1f3951.png', '399.00', 50, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(31, 'Ultrabook 14\"', 'prod-sample-006', NULL, 'assets/images/product_images/prod_31_c5386a8c.webp', '899.00', 25, NULL, 'Brooks Running', 15, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(32, '10\" Tablet Pro', 'prod-sample-007', NULL, 'assets/images/product_images/prod_32_0cb1696f.webp', '299.00', 40, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(33, 'Wireless Earbuds', 'prod-sample-008', NULL, 'assets/images/product_images/prod_33_817e50ee.webp', '79.00', 90, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(34, 'Modern Sofa 3-Seater', 'prod-sample-009', NULL, 'assets/images/product_images/prod_34_879730de.webp', '599.00', 10, NULL, 'New Balance', 5, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(35, 'Air Fryer XL', 'prod-sample-010', NULL, 'assets/images/product_images/prod_35_3b1edbcc.jpg', '129.00', 35, NULL, 'Adidas', 2, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(36, 'Wall Art Canvas', 'prod-sample-011', NULL, 'assets/images/product_images/prod_36_c1d2336a.jpeg', '49.00', 70, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(37, 'King Size Duvet', 'prod-sample-012', NULL, 'assets/images/product_images/prod_37_b8618285.jpeg', '89.00', 45, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(38, 'Hydrating Face Serum', 'prod-sample-013', NULL, 'assets/images/product_images/prod_38_c6ba5999.png', '25.00', 80, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(39, 'Matte Lipstick', 'prod-sample-014', NULL, 'assets/images/product_images/prod_39_3f63e6e4.jpeg', '12.00', 120, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(40, 'Nourishing Shampoo', 'prod-sample-015', NULL, 'assets/images/product_images/prod_40_808a467d.png', '9.00', 150, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(41, 'Eau de Parfum', 'prod-sample-016', NULL, 'assets/images/product_images/prod_41_fcf32f4b.png', '59.00', 60, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(42, 'Adjustable Dumbbells', 'prod-sample-017', NULL, 'assets/images/product_images/prod_42_e4b95652.webp', '199.00', 20, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(43, 'Hiking Backpack 30L', 'prod-sample-018', NULL, 'assets/images/product_images/prod_43_26fd9dd0.jpeg', '79.00', 50, NULL, NULL, NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(44, 'Breathable Sports Tee', 'prod-sample-019', NULL, 'assets/images/product_images/prod_44_8adc8681.jpeg', '19.00', 100, NULL, 'Under Armour', 6, 'men', NULL, 'active', '2025-09-05 08:18:49'),
(45, 'Team Soccer Ball', 'prod-sample-020', NULL, 'assets/images/product_images/prod_45_55755012.jpeg', '25.00', 80, NULL, 'Adidas', 2, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(46, 'Mystery Novel', 'prod-sample-021', NULL, 'assets/images/product_images/prod_46_8d8f678d.webp', '14.00', 200, NULL, 'New Balance', 5, 'women', NULL, 'active', '2025-09-05 08:18:49'),
(47, 'Science Fiction Epic', 'prod-sample-022', NULL, 'assets/images/product_images/prod_47_e52fbcae.webp', '18.00', 150, NULL, 'Brooks Running', 15, 'women', NULL, 'active', '2025-09-05 08:18:49'),
(48, 'Self-Help Guide', 'prod-sample-023', NULL, 'assets/images/product_images/prod_48_828c6b1e.webp', '16.00', 170, NULL, 'Adidas', 2, 'men', NULL, 'active', '2025-09-05 08:18:49'),
(49, 'Cookbook Favorites', 'prod-sample-024', NULL, 'assets/images/product_images/prod_49_a7b39eeb.webp', '22.00', 120, NULL, 'Skechers', 13, 'men', NULL, 'active', '2025-09-05 08:18:49'),
(50, 'Family Board Game', 'prod-sample-025', NULL, 'assets/images/product_images/prod_50_e4bded2a.webp', '29.00', 90, NULL, 'Jordan', 12, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(51, '1000-piece Puzzle', 'prod-sample-026', NULL, 'assets/images/product_images/prod_51_bad2ac58.webp', '15.00', 110, NULL, 'Reebok', 4, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(52, 'Action Figure Hero', 'prod-sample-027', NULL, 'assets/images/product_images/prod_52_edf6d2f2.webp', '19.00', 130, NULL, 'FILA', 10, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(53, 'STEM Kit Robotics', 'prod-sample-028', NULL, 'assets/images/product_images/prod_53_d5093510.webp', '49.00', 70, NULL, 'Adidas', 2, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(54, 'Women\'s Summer Dress', 'prod-sample-029', 'Fun for all ages.', 'assets/images/product_images/prod_54_547fc72a.webp', '39.00', 60, '30.00', 'Nike', 1, 'women', NULL, 'active', '2025-09-05 08:18:49'),
(55, 'Men\'s Chinos', 'prod-sample-030', NULL, 'assets/images/product_images/prod_55_ce555309.jpg', '34.00', 80, '20.00', 'Adidas', 2, 'men', NULL, 'active', '2025-09-05 08:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `fk_pc_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(25, 1),
(26, 1),
(46, 1),
(48, 1),
(49, 1),
(50, 1),
(54, 1),
(30, 3),
(32, 3),
(34, 3),
(35, 4),
(55, 4),
(31, 6),
(47, 7),
(51, 7),
(52, 7),
(53, 7);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_product_images_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `position`) VALUES
(6, 55, 'assets/images/product_images/prod_55_e68d58f6.jpg', 1),
(7, 55, 'assets/images/product_images/prod_55_db197342.jpeg', 2),
(8, 26, 'assets/images/product_images/prod_26_f78eb8b9.webp', 1),
(9, 26, 'assets/images/product_images/prod_26_87e474ef.webp', 2),
(10, 53, 'assets/images/product_images/prod_53_efd128b2.webp', 1),
(11, 53, 'assets/images/product_images/prod_53_41a38d3f.webp', 2),
(12, 52, 'assets/images/product_images/prod_52_6a6dc227.webp', 1),
(13, 52, 'assets/images/product_images/prod_52_b7b648ae.webp', 2),
(14, 51, 'assets/images/product_images/prod_51_a06896cf.webp', 1),
(15, 50, 'assets/images/product_images/prod_50_a05f5dc2.webp', 1),
(16, 49, 'assets/images/product_images/prod_49_c3d81f0f.webp', 1),
(17, 48, 'assets/images/product_images/prod_48_c9583981.webp', 1),
(18, 47, 'assets/images/product_images/prod_47_b8349205.webp', 1),
(19, 46, 'assets/images/product_images/prod_46_122622da.webp', 1),
(20, 45, 'assets/images/product_images/prod_45_238df949.jpeg', 1),
(21, 44, 'assets/images/product_images/prod_44_ac3c50a6.jpeg', 1),
(22, 43, 'assets/images/product_images/prod_43_18f58800.jpeg', 1),
(23, 42, 'assets/images/product_images/prod_42_1a5ee165.webp', 1),
(24, 41, 'assets/images/product_images/prod_41_06ea6495.png', 1),
(25, 36, 'assets/images/product_images/prod_36_6df3319a.jpeg', 1),
(26, 37, 'assets/images/product_images/prod_37_0bbe07e4.jpeg', 1),
(27, 38, 'assets/images/product_images/prod_38_c73a80bc.png', 1),
(28, 38, 'assets/images/product_images/prod_38_81e4dd70.png', 2),
(29, 39, 'assets/images/product_images/prod_39_b80c9bfd.jpeg', 1),
(30, 39, 'assets/images/product_images/prod_39_59f384e7.jpeg', 2),
(31, 39, 'assets/images/product_images/prod_39_450e1a47.jpeg', 3),
(32, 40, 'assets/images/product_images/prod_40_d981ef00.png', 1),
(33, 40, 'assets/images/product_images/prod_40_f711521b.png', 2),
(34, 35, 'assets/images/product_images/prod_35_af896070.jpg', 1),
(35, 35, 'assets/images/product_images/prod_35_52498761.jpg', 2),
(36, 27, 'assets/images/product_images/prod_27_378ca5bc.webp', 1),
(37, 28, 'assets/images/product_images/prod_28_d47d65e5.webp', 1),
(38, 33, 'assets/images/product_images/prod_33_2d0ca532.webp', 1),
(39, 33, 'assets/images/product_images/prod_33_3094d9b2.webp', 2),
(40, 32, 'assets/images/product_images/prod_32_e5937a73.webp', 1),
(41, 32, 'assets/images/product_images/prod_32_1e7d3208.webp', 2),
(42, 31, 'assets/images/product_images/prod_31_2bd581df.webp', 1),
(43, 30, 'assets/images/product_images/prod_30_da1d514b.png', 1),
(44, 30, 'assets/images/product_images/prod_30_1815a88c.png', 2),
(45, 30, 'assets/images/product_images/prod_30_28c1d921.jpg', 3),
(46, 30, 'assets/images/product_images/prod_30_0c72f6d8.png', 4),
(47, 29, 'assets/images/product_images/prod_29_0bbb0d07.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategories`
--

DROP TABLE IF EXISTS `product_subcategories`;
CREATE TABLE IF NOT EXISTS `product_subcategories` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`,`subcategory_id`),
  KEY `fk_ps_subcategory` (`subcategory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_subcategories`
--

INSERT INTO `product_subcategories` (`product_id`, `subcategory_id`) VALUES
(26, 1),
(48, 1),
(49, 1),
(54, 1),
(46, 2),
(25, 8),
(30, 9),
(32, 9),
(34, 9),
(35, 16),
(55, 16),
(53, 21),
(51, 22),
(52, 24);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed','free_shipping') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `min_subtotal` decimal(10,2) DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `per_user_limit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `code`, `type`, `value`, `starts_at`, `ends_at`, `min_subtotal`, `max_uses`, `per_user_limit`) VALUES
(1, 'SAVE10', 'percentage', '10.00', '2025-09-12 17:14:09', '2025-12-11 17:14:09', '50.00', NULL, NULL),
(2, 'SAVE20', 'fixed', '20.00', '2025-09-12 17:14:09', '2025-11-11 17:14:09', '100.00', 1000, 1),
(3, 'FREESHIP', 'free_shipping', '0.00', '2025-09-12 17:14:09', '2026-01-10 17:14:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotion_rules`
--

DROP TABLE IF EXISTS `promotion_rules`;
CREATE TABLE IF NOT EXISTS `promotion_rules` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint(20) UNSIGNED NOT NULL,
  `rule_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule_value` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_promotion_rules_promo` (`promotion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotion_rules`
--

INSERT INTO `promotion_rules` (`id`, `promotion_id`, `rule_type`, `rule_value`) VALUES
(1, 1, 'include_category_slug', 'jeans'),
(2, 1, 'include_category_slug', 't-shirts'),
(3, 2, 'include_product_slug', 'sample-product-2'),
(4, 3, 'country', 'US');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
CREATE TABLE IF NOT EXISTS `returns` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `rma_no` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('requested','approved','rejected','received','refunded','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'requested',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rma_no` (`rma_no`),
  KEY `idx_returns_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_items`
--

DROP TABLE IF EXISTS `return_items`;
CREATE TABLE IF NOT EXISTS `return_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `reason` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolution` enum('refund','exchange') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_return_items_order_item` (`order_item_id`),
  KEY `idx_return_items_return` (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `fk_reviews_user` (`user_id`),
  KEY `idx_reviews_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

DROP TABLE IF EXISTS `seos`;
CREATE TABLE IF NOT EXISTS `seos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'enabled_languages', '[\"kh\",\"en\"]', 'Enabled language codes as JSON array', '2025-12-19 00:59:13', '2025-12-19 00:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `carrier` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_no` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `status` enum('pending','shipped','delivered','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shipments_address` (`address_id`),
  KEY `idx_shipments_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `min_subtotal_free` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `code`, `base_cost`, `min_subtotal_free`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Standard Delivery', 'standard', '2.99', '300.00', 1, 1, '2025-09-12 09:55:54', '2025-09-12 09:55:54'),
(2, 'Express Delivery', 'express', '12.99', NULL, 1, 2, '2025-09-12 09:55:54', '2025-09-12 09:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `label`, `sort_order`, `created_at`) VALUES
(1, '2XS', 1, '2025-09-05 09:02:05'),
(2, 'XS', 2, '2025-09-05 09:02:05'),
(3, 'S', 3, '2025-09-05 09:02:05'),
(4, 'M', 4, '2025-09-05 09:02:05'),
(5, 'L', 5, '2025-09-05 09:02:05'),
(6, 'XL', 6, '2025-09-05 09:02:05'),
(7, 'XXL', 7, '2025-09-05 09:02:05'),
(8, '3XL', 8, '2025-09-05 09:02:05'),
(9, '4XL', 9, '2025-09-05 09:02:05'),
(10, '24', 10, '2025-09-05 09:02:05'),
(11, '26', 11, '2025-09-05 09:02:05'),
(12, '28', 12, '2025-09-05 09:02:05'),
(13, '30', 13, '2025-09-05 09:02:05'),
(14, '32', 14, '2025-09-05 09:02:05'),
(15, '34', 15, '2025-09-05 09:02:05'),
(16, '36', 16, '2025-09-05 09:02:05'),
(17, '38', 17, '2025-09-05 09:02:05'),
(18, '40', 18, '2025-09-05 09:02:05'),
(19, '42', 19, '2025-09-05 09:02:05'),
(20, '44', 20, '2025-09-05 09:02:05');

-- --------------------------------------------------------

--
-- Table structure for table `size_charts`
--

DROP TABLE IF EXISTS `size_charts`;
CREATE TABLE IF NOT EXISTS `size_charts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gender` enum('men','women','unisex','kids') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_label` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chest_cm` decimal(6,2) DEFAULT NULL,
  `waist_cm` decimal(6,2) DEFAULT NULL,
  `hips_cm` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_size_charts_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
CREATE TABLE IF NOT EXISTS `social_links` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` enum('facebook','instagram','twitter','tiktok','youtube','linkedin','pinterest','telegram','whatsapp','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_platform_label` (`platform`,`label`),
  KEY `idx_social_active_position` (`is_active`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `platform`, `label`, `url`, `icon`, `is_active`, `position`, `created_at`, `updated_at`) VALUES
(1, 'facebook', 'Facebook', 'https://facebook.com/yourbrand', 'bi bi-facebook', 1, 1, '2025-09-04 09:00:52', '2025-09-04 10:26:10'),
(2, 'instagram', 'Instagram', 'https://instagram.com/yourbrand', 'bi bi-instagram', 1, 5, '2025-09-04 09:00:52', '2025-09-04 11:29:59'),
(3, 'twitter', 'Twitter', 'https://twitter.com/yourbrand', 'bi bi-twitter-x', 1, 6, '2025-09-04 09:00:52', '2025-09-04 11:30:14'),
(4, 'telegram', 'Telegram', 'https://instagram.com/chamrerns', 'bi bi-telegram', 1, 2, '2025-09-04 10:25:08', '2025-09-04 11:29:22'),
(6, 'whatsapp', 'Whatsapp', 'https://whatsapp.com/yourbrand', 'bi bi-whatsapp', 1, 3, '2025-09-04 10:30:58', '2025-09-04 11:29:32'),
(7, 'pinterest', 'Pinterest', 'https://twitter.com/yourbrand', 'bi bi-pinterest', 1, 7, '2025-09-04 10:52:25', '2025-09-04 11:30:20'),
(8, 'youtube', 'Youbube', 'https://youtu.be/7JCB-SsfIIk?si=YUmYiqF5uhnTKq2C', 'bi bi-youtube', 1, 4, '2025-09-04 10:58:43', '2025-09-04 11:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_subcategories_category_slug` (`category_id`,`slug`),
  KEY `idx_subcategories_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`) VALUES
(1, 1, 'Men\'s Wear', 'men-s-wear'),
(2, 1, 'Women\'s Wear', 'women-s-wear'),
(3, 2, 'Smartphones', 'smartphones'),
(4, 2, 'Laptops', 'laptops'),
(5, 2, 'Tablets', 'tablets'),
(6, 2, 'Acceessories', 'acceessories'),
(7, 1, 'Kid\'s Clothing', 'kid-s-clothing'),
(8, 1, 'Accessories', 'accessories'),
(9, 3, 'Furniture', 'furniture'),
(10, 3, 'Kitchen Appliances', 'kitchen-appliances'),
(11, 3, 'Home Decor', 'home-decor'),
(12, 3, 'Bedding', 'bedding'),
(13, 4, 'Skincare', 'skincare'),
(14, 4, 'Makeup', 'makeup'),
(15, 4, 'Hair Care', 'hair-care'),
(16, 4, 'Fragrances', 'fragrances'),
(17, 5, 'Fitness Equipment', 'fitness-equipment'),
(18, 5, 'Outdoor Gear', 'outdoor-gear'),
(19, 5, 'Sports Apparel', 'sports-apparel'),
(20, 5, 'Team Sports', 'team-sports'),
(21, 7, 'Board Games', 'board-games'),
(22, 7, 'Puzzles', 'puzzles'),
(23, 7, 'Action Figures', 'action-figures'),
(24, 7, 'Educational Toys', 'educational-toys');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_percent` decimal(6,3) NOT NULL DEFAULT '0.000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tax_geo` (`country`,`state`,`city`,`postal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `name`, `country`, `state`, `city`, `postal`, `rate_percent`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'US Default Sales Tax', 'US', NULL, NULL, NULL, '8.250', 1, 1, '2025-09-12 09:55:54', '2025-09-12 09:55:54'),
(2, 'CA Ontario HST', 'CA', 'ON', NULL, NULL, '13.000', 1, 2, '2025-09-12 09:55:54', '2025-09-12 09:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `created_at`) VALUES
(1, 'chamrern', 'chamrern@gmail.com', '0967797762', '$2y$10$ofwMCMCDkTfh2E.VW9uWxec8Q3GydQFOJ81Dthchx89RHdaObKaDy', 'admin', '2025-09-03 08:32:16'),
(2, 'customers', 'customers@gmail.com', '0943434444', '$2y$10$vctiyfSgBLE3Fcu4/notS.KYSWadimtDs6SbDWKjjiXsCx6QZ6Xni', 'customer', '2025-09-03 08:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

DROP TABLE IF EXISTS `variants`;
CREATE TABLE IF NOT EXISTS `variants` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_id` bigint(20) UNSIGNED DEFAULT NULL,
  `size` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_id` bigint(20) UNSIGNED DEFAULT NULL,
  `material` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `barcode` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_variants_product` (`product_id`),
  KEY `idx_variants_color` (`color_id`),
  KEY `idx_variants_size` (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `product_id`, `sku`, `color`, `color_id`, `size`, `size_id`, `material`, `price`, `compare_at_price`, `weight`, `barcode`) VALUES
(1, 26, 'TEE-26-BLK-S', 'Black', 1, 'S', 3, 'Cotton', '19.99', '24.99', '0.300', '012345678901'),
(2, 26, 'TEE-26-BLK-M', 'Black', 1, 'M', 4, 'Cotton', '19.99', '24.99', '0.320', '012345678902'),
(3, 26, 'TEE-26-BLK-L', 'Maroon', 13, 'L', 5, 'Cotton', '19.99', '24.99', '0.340', '012345678903'),
(4, 26, 'TEE-26-WHT-M', 'Green', 4, 'M', 4, 'Cotton', '19.99', '24.99', '0.320', '012345678904'),
(5, 26, 'TEE-26-WHT-L', 'White', 2, 'L', 5, 'Cotton', '19.99', '24.99', '0.340', '012345678905'),
(6, 54, 'DRS-54-RED-S', 'Light Blue', 15, 'S', 3, 'Polyester', '39.00', '49.00', '0.400', '012345678906'),
(7, 54, 'DRS-54-RED-M', 'Pink', 9, 'M', 4, 'Polyester', '39.00', '49.00', '0.420', '012345678907'),
(8, 54, 'DRS-54-RED-L', 'Red', 3, 'L', 5, 'Polyester', '39.00', '49.00', '0.440', '012345678908'),
(9, 55, 'CHN-55-BLU-30', 'Blue', 5, '30', 13, 'Twill', '34.00', '44.00', '0.500', '012345678909'),
(10, 55, 'CHN-55-BLU-32', 'Blue', 5, '32', 14, 'Twill', '34.00', '44.00', '0.520', '012345678910'),
(11, 26, 'TEE-26-BLK-XXL', 'Red', 3, '2XS', 1, NULL, '3000.00', NULL, NULL, '1234567890166');

-- --------------------------------------------------------

--
-- Table structure for table `variant_images`
--

DROP TABLE IF EXISTS `variant_images`;
CREATE TABLE IF NOT EXISTS `variant_images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_variant_images_variant` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `finance_entries`
--
ALTER TABLE `finance_entries`
  ADD CONSTRAINT `fk_finance_entries_category` FOREIGN KEY (`category_id`) REFERENCES `finance_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_finance_entries_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_variant` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_addresses`
--
ALTER TABLE `order_addresses`
  ADD CONSTRAINT `fk_order_addresses_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `fk_pc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pc_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD CONSTRAINT `fk_ps_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ps_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promotion_rules`
--
ALTER TABLE `promotion_rules`
  ADD CONSTRAINT `fk_promotion_rules_promotion` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `fk_returns_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_items`
--
ALTER TABLE `return_items`
  ADD CONSTRAINT `fk_return_items_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`),
  ADD CONSTRAINT `fk_return_items_return` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `fk_shipments_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_shipments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `size_charts`
--
ALTER TABLE `size_charts`
  ADD CONSTRAINT `fk_size_charts_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `fk_variants_color` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_variants_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD CONSTRAINT `fk_variant_images_variant` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
