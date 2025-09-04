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


-- About Us (optional seed)
INSERT INTO about_us (title, content, image_url)
VALUES
  ('Our Story', 'Founded to bring quality fashion at fair prices. Update this content in Admin.', NULL)
ON DUPLICATE KEY UPDATE
  title=VALUES(title), content=VALUES(content), image_url=VALUES(image_url);

-- Social Links (optional seed)
INSERT INTO social_links (platform, label, url, icon, is_active, position)
VALUES
  ('facebook',  'Facebook',  'https://facebook.com/yourbrand',  'mdi:facebook',  1, 1),
  ('instagram', 'Instagram', 'https://instagram.com/yourbrand', 'mdi:instagram', 1, 2),
  ('twitter',   'Twitter',   'https://twitter.com/yourbrand',   'mdi:twitter',   0, 3)
ON DUPLICATE KEY UPDATE
  url=VALUES(url), icon=VALUES(icon), is_active=VALUES(is_active), position=VALUES(position);

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



-- Brands (sample data)
INSERT INTO brands (name, slug, description, logo_url)
VALUES
  ('Brand A', 'brand-a', 'Sample brand A', 'assets/images/logo.svg'),
  ('Brand B', 'brand-b', 'Sample brand B', 'assets/images/logo.svg'),
  ('Brand C', 'brand-c', 'Sample brand C', 'assets/images/logo.svg'),
  ('Brand D', 'brand-d', 'Sample brand D', 'assets/images/logo.svg'),
  ('Brand E', 'brand-e', 'Sample brand E', 'assets/images/logo.svg'),
  ('Brand F', 'brand-f', 'Sample brand F', 'assets/images/logo.svg'),
  ('Brand G', 'brand-g', 'Sample brand G', 'assets/images/logo.svg'),
  ('Brand H', 'brand-h', 'Sample brand H', 'assets/images/logo.svg'),
  ('Brand I', 'brand-i', 'Sample brand I', 'assets/images/logo.svg'),
  ('Brand J', 'brand-j', 'Sample brand J', 'assets/images/logo.svg')
ON DUPLICATE KEY UPDATE
  name=VALUES(name), description=VALUES(description), logo_url=VALUES(logo_url);

-- Resolve brand ids
SET @brand_a = (SELECT id FROM brands WHERE slug='brand-a');
SET @brand_b = (SELECT id FROM brands WHERE slug='brand-b');
SET @brand_c = (SELECT id FROM brands WHERE slug='brand-c');
SET @brand_d = (SELECT id FROM brands WHERE slug='brand-d');
SET @brand_e = (SELECT id FROM brands WHERE slug='brand-e');
SET @brand_f = (SELECT id FROM brands WHERE slug='brand-f');
SET @brand_g = (SELECT id FROM brands WHERE slug='brand-g');
SET @brand_h = (SELECT id FROM brands WHERE slug='brand-h');
SET @brand_i = (SELECT id FROM brands WHERE slug='brand-i');
SET @brand_j = (SELECT id FROM brands WHERE slug='brand-j');

-- Map products to brands via brand_id (based on existing brand name column)
UPDATE products SET brand_id = @brand_a WHERE brand = 'Brand A';
UPDATE products SET brand_id = @brand_b WHERE brand = 'Brand B';
UPDATE products SET brand_id = @brand_c WHERE brand = 'Brand C';
UPDATE products SET brand_id = @brand_d WHERE brand = 'Brand D';
UPDATE products SET brand_id = @brand_e WHERE brand = 'Brand E';
UPDATE products SET brand_id = @brand_f WHERE brand = 'Brand F';
UPDATE products SET brand_id = @brand_g WHERE brand = 'Brand G';
UPDATE products SET brand_id = @brand_h WHERE brand = 'Brand H';
UPDATE products SET brand_id = @brand_i WHERE brand = 'Brand I';
UPDATE products SET brand_id = @brand_j WHERE brand = 'Brand J';

-- Subcategories (sample data)
INSERT INTO subcategories (category_id, name, slug)
VALUES
  (@cat_tshirts, 'Plain Tees',        'plain-tees'),
  (@cat_tshirts, 'Graphic Tees',      'graphic-tees'),
  (@cat_shirts,  'Formal Shirts',     'formal-shirts'),
  (@cat_shirts,  'Casual Shirts',     'casual-shirts'),
  (@cat_jeans,   'Slim Fit',          'slim-fit'),
  (@cat_jeans,   'Regular Fit',       'regular-fit'),
  (@cat_jackets, 'Denim Jackets',     'denim-jackets'),
  (@cat_jackets, 'Leather Jackets',   'leather-jackets'),
  (@cat_shoes,   'Sneakers',          'sneakers'),
  (@cat_shoes,   'Boots',             'boots'),
  (@cat_acc,     'Scarves',           'scarves'),
  (@cat_acc,     'Belts',             'belts'),
  (@cat_hoodies, 'Pullover',          'pullover'),
  (@cat_hoodies, 'Zip Up',            'zip-up'),
  (@cat_dresses, 'Casual Dresses',    'casual-dresses'),
  (@cat_dresses, 'Evening Dresses',   'evening-dresses'),
  (@cat_shorts,  'Chino Shorts',      'chino-shorts'),
  (@cat_shorts,  'Athletic Shorts',   'athletic-shorts'),
  (@cat_skirts,  'Pencil Skirts',     'pencil-skirts'),
  (@cat_skirts,  'Mini Skirts',       'mini-skirts')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Resolve subcategory ids used for sample product mapping
SET @sub_plain_tees      = (SELECT id FROM subcategories WHERE category_id=@cat_tshirts AND slug='plain-tees');
SET @sub_slim_fit        = (SELECT id FROM subcategories WHERE category_id=@cat_jeans   AND slug='slim-fit');
SET @sub_pullover        = (SELECT id FROM subcategories WHERE category_id=@cat_hoodies AND slug='pullover');
SET @sub_formal_shirts   = (SELECT id FROM subcategories WHERE category_id=@cat_shirts  AND slug='formal-shirts');
SET @sub_sneakers        = (SELECT id FROM subcategories WHERE category_id=@cat_shoes   AND slug='sneakers');
SET @sub_casual_dresses  = (SELECT id FROM subcategories WHERE category_id=@cat_dresses AND slug='casual-dresses');
SET @sub_chino_shorts    = (SELECT id FROM subcategories WHERE category_id=@cat_shorts  AND slug='chino-shorts');
SET @sub_denim_jackets   = (SELECT id FROM subcategories WHERE category_id=@cat_jackets AND slug='denim-jackets');
SET @sub_pencil_skirts   = (SELECT id FROM subcategories WHERE category_id=@cat_skirts  AND slug='pencil-skirts');
SET @sub_scarves         = (SELECT id FROM subcategories WHERE category_id=@cat_acc     AND slug='scarves');

-- Map products to subcategories (sample mapping)
INSERT IGNORE INTO product_subcategories (product_id, subcategory_id) VALUES
  (@p1,  @sub_plain_tees),
  (@p2,  @sub_slim_fit),
  (@p3,  @sub_pullover),
  (@p4,  @sub_formal_shirts),
  (@p5,  @sub_sneakers),
  (@p6,  @sub_casual_dresses),
  (@p7,  @sub_chino_shorts),
  (@p8,  @sub_denim_jackets),
  (@p9,  @sub_pencil_skirts),
  (@p10, @sub_scarves);
