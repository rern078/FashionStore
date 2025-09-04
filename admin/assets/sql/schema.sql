-- FashionStore MySQL schema
-- Ensure database exists
-- CREATE DATABASE `fashionstore` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `fashionstore`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Users
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  phone VARCHAR(30) NULL,
  password_hash VARCHAR(100) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Addresses
CREATE TABLE addresses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  line1 VARCHAR(255) NOT NULL,
  line2 VARCHAR(255) NULL,
  city VARCHAR(100) NOT NULL,
  state VARCHAR(100) NULL,
  postal VARCHAR(20) NOT NULL,
  country VARCHAR(2) NOT NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_addresses_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories (self-referencing tree)
CREATE TABLE categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  parent_id BIGINT UNSIGNED NULL,
  CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products
CREATE TABLE products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  description MEDIUMTEXT NULL,
  featured_image VARCHAR(500) NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock_qty INT NOT NULL DEFAULT 0,
  discount_percent DECIMAL(5,2) NULL,
  brand VARCHAR(120) NULL,
  gender ENUM('men','women','unisex','kids') NULL,
  care TEXT NULL,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ProductCategories (many-to-many)
CREATE TABLE product_categories (
  product_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (product_id, category_id),
  CONSTRAINT fk_pc_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT fk_pc_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Variants
CREATE TABLE variants (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  sku VARCHAR(120) NOT NULL UNIQUE,
  color VARCHAR(80) NULL,
  size VARCHAR(40) NULL,
  material VARCHAR(120) NULL,
  price DECIMAL(10,2) NOT NULL,
  compare_at_price DECIMAL(10,2) NULL,
  weight DECIMAL(10,3) NULL,
  barcode VARCHAR(128) NULL,
  CONSTRAINT fk_variants_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_variants_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VariantImages
CREATE TABLE variant_images (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  variant_id BIGINT UNSIGNED NOT NULL,
  image_url VARCHAR(500) NOT NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_variant_images_variant FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE CASCADE,
  INDEX idx_variant_images_variant (variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ProductImages (product-level gallery)
CREATE TABLE product_images (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  image_url VARCHAR(500) NOT NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_product_images_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inventory
CREATE TABLE inventory (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  variant_id BIGINT UNSIGNED NOT NULL,
  location_id BIGINT UNSIGNED NULL,
  qty_available INT NOT NULL DEFAULT 0,
  qty_reserved INT NOT NULL DEFAULT 0,
  low_stock_threshold INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_inventory_variant FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE CASCADE,
  INDEX idx_inventory_variant (variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Carts
CREATE TABLE carts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  session_id VARCHAR(64) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  UNIQUE KEY uniq_cart_session (session_id),
  INDEX idx_cart_user (user_id),
  CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CartItems
CREATE TABLE cart_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cart_id BIGINT UNSIGNED NOT NULL,
  variant_id BIGINT UNSIGNED NOT NULL,
  qty INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  applied_promo_code VARCHAR(50) NULL,
  CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_items_variant FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE RESTRICT,
  UNIQUE KEY uniq_cart_variant (cart_id, variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promotions
CREATE TABLE promotions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  type ENUM('percentage','fixed','free_shipping') NOT NULL,
  value DECIMAL(10,2) NOT NULL,
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  min_subtotal DECIMAL(10,2) NULL,
  max_uses INT NULL,
  per_user_limit INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PromotionRules
CREATE TABLE promotion_rules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  promotion_id BIGINT UNSIGNED NOT NULL,
  rule_type VARCHAR(50) NOT NULL,
  rule_value VARCHAR(200) NOT NULL,
  CONSTRAINT fk_promotion_rules_promotion FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE,
  INDEX idx_promotion_rules_promo (promotion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders
CREATE TABLE orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  order_number VARCHAR(30) NOT NULL UNIQUE,
  status ENUM('pending','paid','fulfilled','cancelled','refunded') NOT NULL DEFAULT 'pending',
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  discount_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  shipping_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  tax_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  grand_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) NOT NULL DEFAULT 'USD',
  placed_at DATETIME NULL,
  payment_status ENUM('unpaid','authorized','captured','refunded','failed') NOT NULL DEFAULT 'unpaid',
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
  INDEX idx_orders_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OrderItems
CREATE TABLE order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  variant_id BIGINT UNSIGNED NOT NULL,
  qty INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_variant FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE RESTRICT,
  INDEX idx_order_items_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments
CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  provider VARCHAR(50) NOT NULL,
  provider_txn_id VARCHAR(100) NULL,
  amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending','authorized','captured','refunded','failed') NOT NULL DEFAULT 'pending',
  captured_at DATETIME NULL,
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_payments_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Shipments
CREATE TABLE shipments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  carrier VARCHAR(80) NULL,
  service VARCHAR(80) NULL,
  tracking_no VARCHAR(120) NULL,
  shipped_at DATETIME NULL,
  delivered_at DATETIME NULL,
  status ENUM('pending','shipped','delivered','returned') NOT NULL DEFAULT 'pending',
  address_id BIGINT UNSIGNED NULL,
  CONSTRAINT fk_shipments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_shipments_address FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE SET NULL,
  INDEX idx_shipments_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Returns
CREATE TABLE returns (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  rma_no VARCHAR(40) NOT NULL UNIQUE,
  status ENUM('requested','approved','rejected','received','refunded','closed') NOT NULL DEFAULT 'requested',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  refund_amount DECIMAL(10,2) NULL,
  CONSTRAINT fk_returns_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_returns_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ReturnItems
CREATE TABLE return_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  return_id BIGINT UNSIGNED NOT NULL,
  order_item_id BIGINT UNSIGNED NOT NULL,
  qty INT NOT NULL,
  reason VARCHAR(200) NULL,
  resolution ENUM('refund','exchange') NOT NULL,
  CONSTRAINT fk_return_items_return FOREIGN KEY (return_id) REFERENCES returns(id) ON DELETE CASCADE,
  CONSTRAINT fk_return_items_order_item FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE RESTRICT,
  INDEX idx_return_items_return (return_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews
CREATE TABLE reviews (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  title VARCHAR(150) NULL,
  body TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_reviews_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SizeCharts
CREATE TABLE size_charts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NULL,
  gender ENUM('men','women','unisex','kids') NULL,
  region VARCHAR(20) NULL,
  size_label VARCHAR(40) NOT NULL,
  chest_cm DECIMAL(6,2) NULL,
  waist_cm DECIMAL(6,2) NULL,
  hips_cm DECIMAL(6,2) NULL,
  CONSTRAINT fk_size_charts_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
  INDEX idx_size_charts_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Brands
CREATE TABLE brands (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  description TEXT NULL,
  logo_url VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Colors
CREATE TABLE colors (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  hex VARCHAR(7) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sizes
CREATE TABLE sizes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(40) NOT NULL UNIQUE,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subcategories (child of Categories)
CREATE TABLE subcategories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL,
  UNIQUE KEY uniq_subcategories_category_slug (category_id, slug),
  CONSTRAINT fk_subcategories_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  INDEX idx_subcategories_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ProductSubcategories (many-to-many between products and subcategories)
CREATE TABLE product_subcategories (
  product_id BIGINT UNSIGNED NOT NULL,
  subcategory_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (product_id, subcategory_id),
  CONSTRAINT fk_ps_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT fk_ps_subcategory FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Schema adaptations for new relations
ALTER TABLE products
  ADD COLUMN brand_id BIGINT UNSIGNED NULL AFTER brand,
  ADD INDEX idx_products_brand (brand_id),
  ADD CONSTRAINT fk_products_brand FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL;

ALTER TABLE variants
  ADD COLUMN color_id BIGINT UNSIGNED NULL AFTER color,
  ADD COLUMN size_id BIGINT UNSIGNED NULL AFTER size,
  ADD INDEX idx_variants_color (color_id),
  ADD INDEX idx_variants_size (size_id),
  ADD CONSTRAINT fk_variants_color FOREIGN KEY (color_id) REFERENCES colors(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_variants_size FOREIGN KEY (size_id) REFERENCES sizes(id) ON DELETE SET NULL;

-- Settings
CREATE TABLE settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT NULL,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEOs
CREATE TABLE seos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page VARCHAR(150) NOT NULL UNIQUE,
  slug VARCHAR(200) NULL,
  meta_title VARCHAR(200) NULL,
  meta_description VARCHAR(300) NULL,
  meta_keywords VARCHAR(300) NULL,
  og_title VARCHAR(200) NULL,
  og_description VARCHAR(300) NULL,
  og_image_url VARCHAR(500) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banners
CREATE TABLE banners (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NULL,
  subtitle VARCHAR(200) NULL,
  image_url VARCHAR(500) NOT NULL,
  link_url VARCHAR(500) NULL,
  alt_text VARCHAR(150) NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_banners_active_position (is_active, position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;



-- Content: About Us
CREATE TABLE about_us (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NULL,
  content MEDIUMTEXT NULL,
  image_url VARCHAR(500) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Content: Contact Messages (from Contact Us page)
CREATE TABLE contact_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NULL,
  subject VARCHAR(200) NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','archived') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_contact_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Content: Social Links / Social Media
CREATE TABLE social_links (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  platform ENUM('facebook','instagram','twitter','tiktok','youtube','linkedin','pinterest','telegram','whatsapp','other') NOT NULL,
  label VARCHAR(100) NULL,
  url VARCHAR(500) NOT NULL,
  icon VARCHAR(100) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_platform_label (platform, label),
  INDEX idx_social_active_position (is_active, position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
