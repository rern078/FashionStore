-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 04, 2025 at 02:10 AM
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
  PRIMARY KEY (`id`),
  KEY `idx_addresses_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `line1`, `line2`, `city`, `state`, `postal`, `country`, `is_default`) VALUES
(1, 1, '30, 81 street', 'chamrern', 'phnom penh', 'cambodia', '121208', 'KH', 1),
(2, 2, 'sangkat teuk thla, kan sensok, phnom penh', 'sangkat teuk thla, kan sensok, phnom penh', 'Phnom Penh, Cambodia', 'cambodia', '334234', 'CA', 0),
(3, 3, '123 Market St', NULL, 'San Francisco', 'CA', '94103', 'US', 1),
(4, 4, '456 Broadway Ave', 'Apt 8B', 'New York', 'NY', '10012', 'US', 1);

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

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`, `currency`) VALUES
(1, NULL, 'SEED-CART-1', '2025-09-03 09:05:14', '2025-09-03 09:05:14', 'USD'),
(2, NULL, 'SEED-CART-2', '2025-09-03 09:05:14', '2025-09-03 09:05:14', 'USD');

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

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `variant_id`, `qty`, `unit_price`, `applied_promo_code`) VALUES
(1, 1, 1, 2, '19.99', NULL),
(2, 1, 2, 1, '49.90', NULL),
(3, 1, 3, 1, '39.50', NULL),
(4, 1, 4, 3, '29.00', NULL),
(5, 1, 5, 1, '79.00', NULL),
(6, 2, 1, 1, '19.99', NULL),
(7, 2, 2, 2, '49.90', NULL),
(8, 2, 3, 2, '39.50', NULL),
(9, 2, 4, 1, '29.00', NULL),
(10, 2, 5, 2, '79.00', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`) VALUES
(1, 'T-Shirts', 't-shirts', NULL),
(2, 'Shirts', 'shirts', NULL),
(3, 'Jeans', 'jeans', NULL),
(4, 'Jackets', 'jackets', NULL),
(5, 'Shoes', 'shoes', NULL),
(6, 'Accessories', 'accessories', NULL),
(7, 'Hoodies', 'hoodies', NULL),
(8, 'Dresses', 'dresses', NULL),
(9, 'Shorts', 'shorts', NULL),
(10, 'Skirts', 'skirts', NULL);

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

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `variant_id`, `location_id`, `qty_available`, `qty_reserved`, `low_stock_threshold`) VALUES
(1, 1, NULL, 100, 5, 10),
(2, 2, NULL, 60, 2, 8),
(3, 3, NULL, 80, 0, 12),
(4, 4, NULL, 40, 1, 5),
(5, 5, NULL, 25, 0, 4);

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `discount_total`, `shipping_total`, `tax_total`, `grand_total`, `currency`, `placed_at`, `payment_status`) VALUES
(1, 3, 'ORD-1001', 'paid', '89.88', '10.00', '5.00', '8.12', '93.00', 'USD', '2025-09-03 16:32:47', 'captured'),
(2, 3, 'ORD-1002', 'pending', '59.99', '0.00', '5.00', '6.00', '70.99', 'USD', '2025-09-03 16:32:47', 'unpaid'),
(3, 4, 'ORD-1003', 'fulfilled', '79.00', '0.00', '5.00', '7.50', '91.50', 'USD', '2025-09-03 16:32:47', 'captured');

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
(1, 1, 1, 2, '19.99', '5.00', '3.20'),
(2, 1, 2, 1, '49.90', '5.00', '4.92'),
(3, 2, 3, 1, '39.50', '0.00', '3.16'),
(4, 2, 4, 1, '29.00', '0.00', '2.32'),
(5, 3, 5, 1, '79.00', '0.00', '7.50');

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `provider`, `provider_txn_id`, `amount`, `status`, `captured_at`) VALUES
(1, 1, 'Stripe', 'pi_test_1001', '93.00', 'captured', '2025-09-03 16:32:47'),
(2, 2, 'Stripe', NULL, '70.99', 'pending', NULL),
(3, 3, 'PayPal', 'PP-ABC-1003', '91.50', 'captured', '2025-09-03 16:32:47');

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
  `gender` enum('men','women','unisex','kids') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `care` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','active','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `description`, `featured_image`, `price`, `stock_qty`, `discount_percent`, `brand`, `gender`, `care`, `status`, `created_at`) VALUES
(14, 'Classic Cotton T-Shirt', 'sample-product-1', 'Soft cotton tee with a regular fit.', 'assets/images/samples/img_1.jpg', '19.99', 120, '0.00', 'Brand A', 'unisex', NULL, 'active', '2025-09-03 07:58:26'),
(15, 'Slim Fit Denim Jeans', 'sample-product-2', 'Stretch denim for everyday comfort.', 'assets/images/samples/img_2.jpg', '49.90', 80, '10.00', 'Brand B', 'men', NULL, 'active', '2025-09-03 07:58:26'),
(16, 'Lightweight Hoodie', 'sample-product-3', 'Perfect layering piece for cool days.', 'assets/images/samples/img_3.jpg', '39.50', 65, '5.00', 'Brand C', 'unisex', NULL, 'active', '2025-09-03 07:58:26'),
(17, 'Linen Button Shirt', 'sample-product-4', 'Breathable linen, great for summer.', 'assets/images/samples/img_4.jpg', '44.00', 50, '0.00', 'Brand D', 'men', NULL, 'active', '2025-09-03 07:58:26'),
(18, 'Leather Sneakers', 'sample-product-5', 'Minimal sneakers with cushioned sole.', 'assets/images/samples/img_2.jpg', '79.00', 40, '15.00', 'Brand E', 'women', NULL, 'active', '2025-09-03 07:58:26'),
(19, 'Floral Summer Dress', 'sample-product-6', 'Flowy silhouette with floral print.', 'assets/images/samples/img_3.jpg', '59.90', 35, '0.00', 'Brand F', 'women', NULL, 'active', '2025-09-03 07:58:26'),
(20, 'Chino Shorts', 'sample-product-7', 'Casual shorts with tailored look.', 'assets/images/samples/img_4.jpg', '29.99', 100, '0.00', 'Brand G', 'men', NULL, 'active', '2025-09-03 07:58:26'),
(21, 'Denim Jacket', 'sample-product-8', 'Classic trucker style denim jacket.', 'assets/images/samples/img_1.jpg', '69.00', 45, '5.00', 'Brand H', 'unisex', NULL, 'active', '2025-09-03 07:58:26'),
(22, 'Pencil Skirt', 'sample-product-9', 'High-waist pencil skirt for office.', 'assets/images/samples/img_2.jpg', '39.00', 70, '0.00', 'Brand I', 'women', NULL, 'active', '2025-09-03 07:58:26'),
(23, 'Silk Scarf', 'sample-product-10', 'Premium silk scarf with pattern.', 'assets/images/samples/img_3.jpg', '24.50', 150, '20.00', 'Brand J', 'women', NULL, 'active', '2025-09-03 07:58:26'),
(24, 'Testing', 'testing', 'Testing', 'assets/images/product_images/prod_1756886603_9b8317b3.webp', '3000.00', 200, '10.00', 'Testing', 'kids', NULL, 'archived', '2025-09-03 08:03:23');

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
(14, 1),
(17, 2),
(15, 3),
(21, 4),
(18, 5),
(23, 6),
(24, 6),
(16, 7),
(19, 8),
(20, 9),
(22, 10);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `position`) VALUES
(4, 24, 'assets/images/product_images/prod_24_f357100a.webp', 1),
(5, 24, 'assets/images/product_images/prod_24_db24b8a9.webp', 2);

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
(1, 'SAVE10', 'percentage', '10.00', '2025-09-03 16:12:12', '2025-12-02 16:12:12', '50.00', NULL, NULL),
(2, 'SAVE20', 'fixed', '20.00', '2025-09-03 16:12:12', '2025-11-02 16:12:12', '100.00', 1000, 1),
(3, 'FREESHIP', 'free_shipping', '0.00', '2025-09-03 16:12:12', '2026-01-01 16:12:12', NULL, NULL, NULL);

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

--
-- Dumping data for table `promotion_rules`
--

INSERT INTO `promotion_rules` (`id`, `promotion_id`, `rule_type`, `rule_value`) VALUES
(1, 1, 'include_category_slug', 'jeans'),
(2, 1, 'include_category_slug', 't-shirts'),
(3, 2, 'include_product_slug', 'sample-product-2'),
(4, 3, 'country', 'US'),
(5, 3, 'dff_343', '234');

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

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `order_id`, `rma_no`, `status`, `created_at`, `refund_amount`) VALUES
(1, 1, 'RMA-1001', 'received', '2025-09-03 09:32:47', '19.99');

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

--
-- Dumping data for table `return_items`
--

INSERT INTO `return_items` (`id`, `return_id`, `order_item_id`, `qty`, `reason`, `resolution`) VALUES
(1, 1, 1, 1, 'Too small', 'refund');

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

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `title`, `body`, `created_at`, `status`) VALUES
(1, 3, 14, 5, 'Great tee', 'Soft fabric, fits well.', '2025-09-03 09:32:47', 'approved'),
(2, 3, 15, 4, 'Solid jeans', 'Good stretch and fit.', '2025-09-03 09:32:47', 'approved'),
(3, 4, 18, 3, 'Decent shoes', 'Comfortable but runs narrow.', '2025-09-03 09:32:47', 'pending');

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

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `order_id`, `carrier`, `service`, `tracking_no`, `shipped_at`, `delivered_at`, `status`, `address_id`) VALUES
(1, 1, 'UPS', 'Ground', '1ZTEST1001', '2025-09-03 16:32:47', '2025-09-06 16:32:47', 'delivered', 3),
(2, 3, 'FedEx', 'Express', 'FDXTEST1003', '2025-09-03 16:32:47', NULL, 'shipped', 4);

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

--
-- Dumping data for table `size_charts`
--

INSERT INTO `size_charts` (`id`, `product_id`, `gender`, `region`, `size_label`, `chest_cm`, `waist_cm`, `hips_cm`) VALUES
(1, 14, 'men', 'US', 'S', '88.00', '76.00', '92.00'),
(2, 14, 'men', 'US', 'M', '96.00', '84.00', '100.00'),
(3, 14, 'men', 'US', 'L', '104.00', '92.00', '108.00'),
(4, 15, 'women', 'EU', '36', '84.00', '66.00', '90.00'),
(5, 15, 'women', 'EU', '38', '88.00', '70.00', '94.00'),
(6, 15, 'women', 'EU', '40', '92.00', '74.00', '98.00'),
(7, NULL, 'unisex', 'INT', 'XS', '82.00', '66.00', '86.00'),
(8, NULL, 'unisex', 'INT', 'S', '88.00', '72.00', '92.00'),
(9, NULL, 'unisex', 'INT', 'M', '96.00', '80.00', '100.00'),
(10, NULL, 'unisex', 'INT', 'L', '104.00', '88.00', '108.00');

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
(2, 'customers', 'customers@gmail.com', '0943434444', '$2y$10$vctiyfSgBLE3Fcu4/notS.KYSWadimtDs6SbDWKjjiXsCx6QZ6Xni', 'customer', '2025-09-03 08:32:47'),
(3, 'Alice Smith', 'alice@example.com', '1234567890', 'bcrypt-placeholder', 'customer', '2025-09-03 09:32:47'),
(4, 'Bob Johnson', 'bob@example.com', '9876543210', 'bcrypt-placeholder', 'customer', '2025-09-03 09:32:47'),
(5, 'user customer1', 'customer1@gmail.com', '08352555234', '$2y$10$pKpQwF9W6anrK7M7fxvLseSLECyLbQrN15a5tWo5tGq4qlrcApOk6', 'customer', '2025-09-03 10:08:17'),
(6, 'user2 customer2', 'customers2@gmail.com', '042423424', '$2y$10$xRu0QwUJlhDbkg7Dsx/jrOfWo2aqZaydtakKzTQ9SkeUJsLCTAQ2S', 'customer', '2025-09-04 01:50:07');

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
  `size` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `barcode` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_variants_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `product_id`, `sku`, `color`, `size`, `material`, `price`, `compare_at_price`, `weight`, `barcode`) VALUES
(1, 14, 'SKU-TSHIRT-001', 'Black', 'M', 'Cotton', '19.99', NULL, '0.250', '1111111111111'),
(2, 15, 'SKU-JEANS-002', 'Blue', '32', 'Denim', '49.90', '59.90', '0.650', '2222222222222'),
(3, 16, 'SKU-HOODIE-003', 'Gray', 'L', 'Fleece', '39.50', NULL, '0.800', '3333333333333'),
(4, 17, 'SKU-SHIRT-004', 'White', 'M', 'Linen', '29.00', '39.00', '0.300', '4444444444444'),
(5, 18, 'SKU-SHOES-005', 'White', '8', 'Leather', '79.00', '99.00', '0.900', '5555555555555');

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
-- Dumping data for table `variant_images`
--

INSERT INTO `variant_images` (`id`, `variant_id`, `image_url`, `position`) VALUES
(1, 1, 'assets/images/samples/img_1.jpg', 1),
(2, 2, 'assets/images/samples/img_2.jpg', 1),
(3, 3, 'assets/images/samples/img_3.jpg', 1),
(4, 4, 'assets/images/samples/img_4.jpg', 1),
(5, 5, 'assets/images/samples/img_1.jpg', 1);

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
-- Constraints for table `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD CONSTRAINT `fk_variant_images_variant` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
