-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 06, 2025 at 04:42 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'USD', 'US Dollar', '1.000000', '$', 2, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 03:07:46'),
(2, 'EUR', 'Euro', '0.920000', '€', 2, 'suffix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 03:24:44'),
(3, 'GBP', 'British Pound', '0.800000', '£', 2, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(4, 'JPY', 'Japanese Yen', '150.000000', '¥', 0, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(5, 'INR', 'Indian Rupee', '83.000000', '₹', 2, 'prefix', 0, 1, '2025-09-05 02:49:41', '2025-09-05 02:49:41'),
(6, 'KHR', 'KHR Riel', '4100.000000', '៛', 2, 'prefix', 1, 1, '2025-09-05 03:24:30', '2025-09-05 03:24:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(27, 'Women\'s Running Leggings', 'prod-sample-002', 'Stretch leggings for workouts.', 'assets/images/samples/img_2.jpg', '29.99', 80, NULL, 'Adidas', NULL, 'women', NULL, 'active', '2025-09-05 08:18:49'),
(28, 'Kids Hoodie', 'prod-sample-003', 'Soft hoodie for kids.', 'assets/images/samples/img_3.jpg', '24.99', 60, NULL, 'Puma', NULL, 'kids', NULL, 'active', '2025-09-05 08:18:49'),
(29, 'Leather Belt', 'prod-sample-004', 'Genuine leather belt.', 'assets/images/samples/img_4.jpg', '15.99', 120, NULL, 'FILA', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(30, 'Android Smartphone X', 'prod-sample-005', '6.5\" display, 128GB storage.', 'assets/images/samples/img_1.jpg', '399.00', 50, NULL, 'Samsung', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(31, 'Ultrabook 14\"', 'prod-sample-006', 'Lightweight laptop for travel.', 'assets/images/samples/img_2.jpg', '899.00', 25, NULL, 'Lenovo', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(32, '10\" Tablet Pro', 'prod-sample-007', 'Entertainment and work on the go.', 'assets/images/samples/img_3.jpg', '299.00', 40, NULL, 'Apple', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(33, 'Wireless Earbuds', 'prod-sample-008', 'Noise-cancelling earbuds.', 'assets/images/samples/img_4.jpg', '79.00', 90, NULL, 'Sony', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(34, 'Modern Sofa 3-Seater', 'prod-sample-009', NULL, 'assets/images/product_images/prod_34_879730de.webp', '599.00', 10, NULL, 'New Balance', 5, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(35, 'Air Fryer XL', 'prod-sample-010', NULL, 'assets/images/samples/img_2.jpg', '129.00', 35, NULL, 'Adidas', 2, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(36, 'Wall Art Canvas', 'prod-sample-011', 'Abstract canvas wall art.', 'assets/images/samples/img_3.jpg', '49.00', 70, NULL, 'Brand C', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(37, 'King Size Duvet', 'prod-sample-012', 'Soft microfiber duvet.', 'assets/images/samples/img_4.jpg', '89.00', 45, NULL, 'Brand D', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(38, 'Hydrating Face Serum', 'prod-sample-013', 'Hyaluronic acid serum.', 'assets/images/samples/img_1.jpg', '25.00', 80, NULL, 'Brand E', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(39, 'Matte Lipstick', 'prod-sample-014', 'Long-lasting matte finish.', 'assets/images/samples/img_2.jpg', '12.00', 120, NULL, 'Brand F', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(40, 'Nourishing Shampoo', 'prod-sample-015', 'For all hair types.', 'assets/images/samples/img_3.jpg', '9.00', 150, NULL, 'Brand G', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(41, 'Eau de Parfum', 'prod-sample-016', 'Floral fragrance.', 'assets/images/samples/img_4.jpg', '59.00', 60, NULL, 'Brand H', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(42, 'Adjustable Dumbbells', 'prod-sample-017', 'Space-saving strength training.', 'assets/images/samples/img_1.jpg', '199.00', 20, NULL, 'Brand I', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(43, 'Hiking Backpack 30L', 'prod-sample-018', 'Durable outdoor pack.', 'assets/images/samples/img_2.jpg', '79.00', 50, NULL, 'Brand J', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(44, 'Breathable Sports Tee', 'prod-sample-019', 'Quick-dry athletic shirt.', 'assets/images/samples/img_3.jpg', '19.00', 100, NULL, 'Under Armour', NULL, 'men', NULL, 'active', '2025-09-05 08:18:49'),
(45, 'Team Soccer Ball', 'prod-sample-020', 'Official size 5.', 'assets/images/samples/img_4.jpg', '25.00', 80, NULL, 'Adidas', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(46, 'Mystery Novel', 'prod-sample-021', 'Bestselling mystery thriller.', 'assets/images/samples/img_1.jpg', '14.00', 200, NULL, 'Brand A', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(47, 'Science Fiction Epic', 'prod-sample-022', 'Space opera adventure.', 'assets/images/samples/img_2.jpg', '18.00', 150, NULL, 'Brand B', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(48, 'Self-Help Guide', 'prod-sample-023', 'Practical life strategies.', 'assets/images/samples/img_3.jpg', '16.00', 170, NULL, 'Brand C', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(49, 'Cookbook Favorites', 'prod-sample-024', '100 easy recipes.', 'assets/images/samples/img_4.jpg', '22.00', 120, NULL, 'Brand D', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(50, 'Family Board Game', 'prod-sample-025', 'Fun for all ages.', 'assets/images/samples/img_1.jpg', '29.00', 90, NULL, 'Brand E', NULL, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(51, '1000-piece Puzzle', 'prod-sample-026', NULL, 'assets/images/samples/img_2.jpg', '15.00', 110, NULL, 'Reebok', 4, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(52, 'Action Figure Hero', 'prod-sample-027', NULL, 'assets/images/samples/img_3.jpg', '19.00', 130, NULL, 'FILA', 10, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(53, 'STEM Kit Robotics', 'prod-sample-028', NULL, 'assets/images/samples/img_4.jpg', '49.00', 70, NULL, 'Adidas', 2, NULL, NULL, 'active', '2025-09-05 08:18:49'),
(54, 'Women\'s Summer Dress', 'prod-sample-029', NULL, 'assets/images/product_images/prod_54_547fc72a.webp', '39.00', 60, '30.00', 'Nike', 1, 'women', NULL, 'active', '2025-09-05 08:18:49'),
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
(54, 1),
(34, 3),
(35, 4),
(55, 4),
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `position`) VALUES
(6, 55, 'assets/images/product_images/prod_55_e68d58f6.jpg', 1),
(7, 55, 'assets/images/product_images/prod_55_db197342.jpeg', 2),
(8, 26, 'assets/images/product_images/prod_26_f78eb8b9.webp', 1),
(9, 26, 'assets/images/product_images/prod_26_87e474ef.webp', 2);

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
(54, 1),
(25, 8),
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
