-- FashionStore sample data (10 categories, 10 products)
-- Usage (MySQL client):
--   SOURCE F:/Coder/FashionStore/admin/assets/sql/seed.sql;

SET NAMES utf8mb4;

-- Categories
INSERT INTO categories (name, slug, parent_id)
VALUES
  ('T-Shirts', 't-shirts', NULL),
  ('Shirts', 'shirts', NULL),
  ('Jeans', 'jeans', NULL),
  ('Jackets', 'jackets', NULL),
  ('Shoes', 'shoes', NULL),
  ('Accessories', 'accessories', NULL),
  ('Hoodies', 'hoodies', NULL),
  ('Dresses', 'dresses', NULL),
  ('Shorts', 'shorts', NULL),
  ('Skirts', 'skirts', NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Resolve category ids to variables
SET @cat_tshirts = (SELECT id FROM categories WHERE slug='t-shirts');
SET @cat_shirts  = (SELECT id FROM categories WHERE slug='shirts');
SET @cat_jeans   = (SELECT id FROM categories WHERE slug='jeans');
SET @cat_jackets = (SELECT id FROM categories WHERE slug='jackets');
SET @cat_shoes   = (SELECT id FROM categories WHERE slug='shoes');
SET @cat_acc     = (SELECT id FROM categories WHERE slug='accessories');
SET @cat_hoodies = (SELECT id FROM categories WHERE slug='hoodies');
SET @cat_dresses = (SELECT id FROM categories WHERE slug='dresses');
SET @cat_shorts  = (SELECT id FROM categories WHERE slug='shorts');
SET @cat_skirts  = (SELECT id FROM categories WHERE slug='skirts');

-- Products
INSERT INTO products (title, slug, description, featured_image, brand, gender, status)
VALUES
  ('Classic Cotton T-Shirt', 'sample-product-1', 'Soft cotton tee with a regular fit.', 'assets/images/samples/img_1.jpg', 'Brand A', 'unisex', 'active'),
  ('Slim Fit Denim Jeans', 'sample-product-2', 'Stretch denim for everyday comfort.', 'assets/images/samples/img_2.jpg', 'Brand B', 'men', 'active'),
  ('Lightweight Hoodie', 'sample-product-3', 'Perfect layering piece for cool days.', 'assets/images/samples/img_3.jpg', 'Brand C', 'unisex', 'active'),
  ('Linen Button Shirt', 'sample-product-4', 'Breathable linen, great for summer.', 'assets/images/samples/img_4.jpg', 'Brand D', 'men', 'active'),
  ('Leather Sneakers', 'sample-product-5', 'Minimal sneakers with cushioned sole.', 'assets/images/samples/img_2.jpg', 'Brand E', 'women', 'active'),
  ('Floral Summer Dress', 'sample-product-6', 'Flowy silhouette with floral print.', 'assets/images/samples/img_3.jpg', 'Brand F', 'women', 'active'),
  ('Chino Shorts', 'sample-product-7', 'Casual shorts with tailored look.', 'assets/images/samples/img_4.jpg', 'Brand G', 'men', 'active'),
  ('Denim Jacket', 'sample-product-8', 'Classic trucker style denim jacket.', 'assets/images/samples/img_1.jpg', 'Brand H', 'unisex', 'active'),
  ('Pencil Skirt', 'sample-product-9', 'High-waist pencil skirt for office.', 'assets/images/samples/img_2.jpg', 'Brand I', 'women', 'active'),
  ('Silk Scarf', 'sample-product-10', 'Premium silk scarf with pattern.', 'assets/images/samples/img_3.jpg', 'Brand J', 'women', 'active')
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), featured_image = VALUES(featured_image), brand = VALUES(brand), gender = VALUES(gender), status = VALUES(status);

-- Resolve product ids
SET @p1  = (SELECT id FROM products WHERE slug='sample-product-1');
SET @p2  = (SELECT id FROM products WHERE slug='sample-product-2');
SET @p3  = (SELECT id FROM products WHERE slug='sample-product-3');
SET @p4  = (SELECT id FROM products WHERE slug='sample-product-4');
SET @p5  = (SELECT id FROM products WHERE slug='sample-product-5');
SET @p6  = (SELECT id FROM products WHERE slug='sample-product-6');
SET @p7  = (SELECT id FROM products WHERE slug='sample-product-7');
SET @p8  = (SELECT id FROM products WHERE slug='sample-product-8');
SET @p9  = (SELECT id FROM products WHERE slug='sample-product-9');
SET @p10 = (SELECT id FROM products WHERE slug='sample-product-10');

-- Map products to categories
INSERT IGNORE INTO product_categories (product_id, category_id) VALUES
  (@p1, @cat_tshirts),
  (@p2, @cat_jeans),
  (@p3, @cat_hoodies),
  (@p4, @cat_shirts),
  (@p5, @cat_shoes),
  (@p6, @cat_dresses),
  (@p7, @cat_shorts),
  (@p8, @cat_jackets),
  (@p9, @cat_skirts),
  (@p10, @cat_acc);


-- Promotions (sample data)
INSERT INTO promotions (code, type, value, starts_at, ends_at, min_subtotal, max_uses, per_user_limit)
VALUES
  ('SAVE10',   'percentage',     10.00, NOW(), DATE_ADD(NOW(), INTERVAL 90 DAY),  50.00,  NULL, NULL),
  ('SAVE20',   'fixed',          20.00, NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY), 100.00, 1000,   1),
  ('FREESHIP', 'free_shipping',   0.00, NOW(), DATE_ADD(NOW(), INTERVAL 120 DAY),  NULL,  NULL, NULL)
ON DUPLICATE KEY UPDATE
  type=VALUES(type), value=VALUES(value), starts_at=VALUES(starts_at), ends_at=VALUES(ends_at),
  min_subtotal=VALUES(min_subtotal), max_uses=VALUES(max_uses), per_user_limit=VALUES(per_user_limit);

-- Resolve promotion ids
SET @promo_save10 = (SELECT id FROM promotions WHERE code='SAVE10');
SET @promo_save20 = (SELECT id FROM promotions WHERE code='SAVE20');
SET @promo_ship   = (SELECT id FROM promotions WHERE code='FREESHIP');

-- Promotion rules (sample data)
INSERT INTO promotion_rules (promotion_id, rule_type, rule_value) VALUES
  (@promo_save10, 'include_category_slug', 'jeans'),
  (@promo_save10, 'include_category_slug', 't-shirts'),
  (@promo_save20, 'include_product_slug',  'sample-product-2'),
  (@promo_ship,   'country',               'US');


