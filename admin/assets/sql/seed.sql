-- FashionStore sample data (10 categories, 10 products)
-- Usage (MySQL client):
--   SOURCE F:/Coder/FashionStore/admin/assets/sql/seed.sql;

SET NAMES utf8mb4;

-- Currencies (idempotent seeds)
INSERT INTO currencies (code, name, exchange_rate, symbol, decimal_places, position, is_default, is_active)
VALUES
  ('USD', 'US Dollar',       1.000000, '$',  2, 'prefix', 1, 1),
  ('EUR', 'Euro',            0.920000, '€',  2, 'suffix', 0, 1),
  ('GBP', 'British Pound',   0.800000, '£',  2, 'prefix', 0, 1),
  ('JPY', 'Japanese Yen',  150.000000, '¥',  0, 'prefix', 0, 1),
  ('INR', 'Indian Rupee',   83.000000, '₹',  2, 'prefix', 0, 1)
ON DUPLICATE KEY UPDATE
  name=VALUES(name), exchange_rate=VALUES(exchange_rate), symbol=VALUES(symbol),
  decimal_places=VALUES(decimal_places), position=VALUES(position),
  is_default=VALUES(is_default), is_active=VALUES(is_active);

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


-- About Us (optional seed - 5 sample entries, idempotent)
INSERT INTO about_us (title, content, image_url)
SELECT 'Our Story', 'Founded to bring quality fashion at fair prices. Update this content in Admin.', NULL
WHERE NOT EXISTS (SELECT 1 FROM about_us WHERE title = 'Our Story');

INSERT INTO about_us (title, content, image_url)
SELECT 'Our Mission', 'We aim to empower self-expression through accessible, high-quality fashion.', 'assets/img/product/product-f-1.webp'
WHERE NOT EXISTS (SELECT 1 FROM about_us WHERE title = 'Our Mission');

INSERT INTO about_us (title, content, image_url)
SELECT 'Craftsmanship', 'Every piece is thoughtfully designed and rigorously tested for durability.', 'assets/img/product/product-m-1.webp'
WHERE NOT EXISTS (SELECT 1 FROM about_us WHERE title = 'Craftsmanship');

INSERT INTO about_us (title, content, image_url)
SELECT 'Sustainability', 'We prioritize responsible sourcing and reduced waste in our operations.', 'assets/img/product/product-f-4.webp'
WHERE NOT EXISTS (SELECT 1 FROM about_us WHERE title = 'Sustainability');

INSERT INTO about_us (title, content, image_url)
SELECT 'Community', 'Giving back is core to who we are—from local partnerships to global causes.', 'assets/img/product/product-m-4.webp'
WHERE NOT EXISTS (SELECT 1 FROM about_us WHERE title = 'Community');

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


-- Shipping Methods (sample data)
INSERT INTO shipping_methods (name, code, base_cost, min_subtotal_free, is_active, sort_order)
VALUES
  ('Standard Delivery', 'standard', 2.99, 300.00, 1, 1),
  ('Express Delivery',  'express', 12.99, NULL,   1, 2)
ON DUPLICATE KEY UPDATE
  name=VALUES(name), base_cost=VALUES(base_cost), min_subtotal_free=VALUES(min_subtotal_free), is_active=VALUES(is_active), sort_order=VALUES(sort_order);

-- Tax Rates (sample data)
INSERT INTO tax_rates (name, country, state, city, postal, rate_percent, is_active, sort_order)
VALUES
  ('US Default Sales Tax', 'US', NULL, NULL, NULL, 8.250, 1, 1),
  ('CA Ontario HST', 'CA', 'ON', NULL, NULL, 13.000, 1, 2)
ON DUPLICATE KEY UPDATE
  country=VALUES(country), state=VALUES(state), city=VALUES(city), postal=VALUES(postal), rate_percent=VALUES(rate_percent), is_active=VALUES(is_active), sort_order=VALUES(sort_order);



-- New Top-level Categories per spec
INSERT INTO categories (name, slug, parent_id)
VALUES
  ('Clothing',                 'clothing',                 NULL),
  ('Electronics',              'electronics',              NULL),
  ('Home & Kitchen',           'home-kitchen',             NULL),
  ('Beauty & Personal Care',   'beauty-personal-care',     NULL),
  ('Sports & Outdoors',        'sports-outdoors',          NULL),
  ('Books',                    'books',                    NULL),
  ('Toys & Games',             'toys-games',               NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Resolve new category ids
SET @cat_clothing  = (SELECT id FROM categories WHERE slug='clothing');
SET @cat_elec      = (SELECT id FROM categories WHERE slug='electronics');
SET @cat_home      = (SELECT id FROM categories WHERE slug='home-kitchen');
SET @cat_beauty    = (SELECT id FROM categories WHERE slug='beauty-personal-care');
SET @cat_sports    = (SELECT id FROM categories WHERE slug='sports-outdoors');
SET @cat_books     = (SELECT id FROM categories WHERE slug='books');
SET @cat_toys      = (SELECT id FROM categories WHERE slug='toys-games');

-- Subcategories for the new categories
INSERT INTO subcategories (category_id, name, slug)
VALUES
  -- Clothing
  (@cat_clothing, 'Men''s Wear',        'mens-wear'),
  (@cat_clothing, 'Women''s Wear',      'womens-wear'),
  (@cat_clothing, 'Kid''s Clothing',    'kids-clothing'),
  (@cat_clothing, 'Accessories',        'accessories'),
  -- Electronics
  (@cat_elec, 'Smartphones',           'smartphones'),
  (@cat_elec, 'Laptops',               'laptops'),
  (@cat_elec, 'Tablets',               'tablets'),
  (@cat_elec, 'Accessories',           'accessories'),
  -- Home & Kitchen
  (@cat_home, 'Furniture',             'furniture'),
  (@cat_home, 'Kitchen Appliances',    'kitchen-appliances'),
  (@cat_home, 'Home Decor',            'home-decor'),
  (@cat_home, 'Bedding',               'bedding'),
  -- Beauty & Personal Care
  (@cat_beauty, 'Skincare',            'skincare'),
  (@cat_beauty, 'Makeup',              'makeup'),
  (@cat_beauty, 'Hair Care',           'hair-care'),
  (@cat_beauty, 'Fragrances',          'fragrances'),
  -- Sports & Outdoors
  (@cat_sports, 'Fitness Equipment',   'fitness-equipment'),
  (@cat_sports, 'Outdoor Gear',        'outdoor-gear'),
  (@cat_sports, 'Sports Apparel',      'sports-apparel'),
  (@cat_sports, 'Team Sports',         'team-sports'),
  -- Toys & Games
  (@cat_toys, 'Board Games',           'board-games'),
  (@cat_toys, 'Puzzles',               'puzzles'),
  (@cat_toys, 'Action Figures',        'action-figures'),
  (@cat_toys, 'Educational Toys',      'educational-toys')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Resolve subcategory ids we will use
SET @sub_mens_wear       = (SELECT id FROM subcategories WHERE category_id=@cat_clothing AND slug='mens-wear');
SET @sub_womens_wear     = (SELECT id FROM subcategories WHERE category_id=@cat_clothing AND slug='womens-wear');
SET @sub_kids_clothing   = (SELECT id FROM subcategories WHERE category_id=@cat_clothing AND slug='kids-clothing');
SET @sub_clothing_acc    = (SELECT id FROM subcategories WHERE category_id=@cat_clothing AND slug='accessories');

SET @sub_smartphones     = (SELECT id FROM subcategories WHERE category_id=@cat_elec AND slug='smartphones');
SET @sub_laptops         = (SELECT id FROM subcategories WHERE category_id=@cat_elec AND slug='laptops');
SET @sub_tablets         = (SELECT id FROM subcategories WHERE category_id=@cat_elec AND slug='tablets');
SET @sub_elec_acc        = (SELECT id FROM subcategories WHERE category_id=@cat_elec AND slug='accessories');

SET @sub_furniture       = (SELECT id FROM subcategories WHERE category_id=@cat_home AND slug='furniture');
SET @sub_kitchen_appl    = (SELECT id FROM subcategories WHERE category_id=@cat_home AND slug='kitchen-appliances');
SET @sub_home_decor      = (SELECT id FROM subcategories WHERE category_id=@cat_home AND slug='home-decor');
SET @sub_bedding         = (SELECT id FROM subcategories WHERE category_id=@cat_home AND slug='bedding');

SET @sub_skincare        = (SELECT id FROM subcategories WHERE category_id=@cat_beauty AND slug='skincare');
SET @sub_makeup          = (SELECT id FROM subcategories WHERE category_id=@cat_beauty AND slug='makeup');
SET @sub_hair_care       = (SELECT id FROM subcategories WHERE category_id=@cat_beauty AND slug='hair-care');
SET @sub_fragrances      = (SELECT id FROM subcategories WHERE category_id=@cat_beauty AND slug='fragrances');

SET @sub_fitness_eq      = (SELECT id FROM subcategories WHERE category_id=@cat_sports AND slug='fitness-equipment');
SET @sub_outdoor_gear    = (SELECT id FROM subcategories WHERE category_id=@cat_sports AND slug='outdoor-gear');
SET @sub_sports_apparel  = (SELECT id FROM subcategories WHERE category_id=@cat_sports AND slug='sports-apparel');
SET @sub_team_sports     = (SELECT id FROM subcategories WHERE category_id=@cat_sports AND slug='team-sports');

SET @sub_board_games     = (SELECT id FROM subcategories WHERE category_id=@cat_toys AND slug='board-games');
SET @sub_puzzles         = (SELECT id FROM subcategories WHERE category_id=@cat_toys AND slug='puzzles');
SET @sub_action_figures  = (SELECT id FROM subcategories WHERE category_id=@cat_toys AND slug='action-figures');
SET @sub_edu_toys        = (SELECT id FROM subcategories WHERE category_id=@cat_toys AND slug='educational-toys');

-- 30 Sample Products (idempotent)
INSERT INTO products (title, slug, description, featured_image, price, stock_qty, discount_percent, brand, gender, status)
VALUES
  ('Men''s Classic Tee',            'prod-sample-001', 'Comfort cotton tee for everyday wear.', 'assets/images/samples/img_1.jpg', 19.99, 100, NULL, 'Nike',       'men',    'active'),
  ('Women''s Running Leggings',     'prod-sample-002', 'Stretch leggings for workouts.',        'assets/images/samples/img_2.jpg', 29.99,  80, NULL, 'Adidas',     'women',  'active'),
  ('Kids Hoodie',                   'prod-sample-003', 'Soft hoodie for kids.',                 'assets/images/samples/img_3.jpg', 24.99,  60, NULL, 'Puma',       'kids',   'active'),
  ('Leather Belt',                  'prod-sample-004', 'Genuine leather belt.',                 'assets/images/samples/img_4.jpg', 15.99, 120, NULL, 'FILA',       NULL,     'active'),

  ('Android Smartphone X',          'prod-sample-005', '6.5" display, 128GB storage.',          'assets/images/samples/img_1.jpg', 399.00, 50, NULL, 'Samsung',    NULL,     'active'),
  ('Ultrabook 14"',                'prod-sample-006', 'Lightweight laptop for travel.',        'assets/images/samples/img_2.jpg', 899.00, 25, NULL, 'Lenovo',     NULL,     'active'),
  ('10" Tablet Pro',               'prod-sample-007', 'Entertainment and work on the go.',     'assets/images/samples/img_3.jpg', 299.00, 40, NULL, 'Apple',      NULL,     'active'),
  ('Wireless Earbuds',              'prod-sample-008', 'Noise-cancelling earbuds.',             'assets/images/samples/img_4.jpg',  79.00, 90, NULL, 'Sony',       NULL,     'active'),

  ('Modern Sofa 3-Seater',          'prod-sample-009', 'Comfortable contemporary sofa.',        'assets/images/samples/img_1.jpg', 599.00, 10, NULL, 'Brand A',    NULL,     'active'),
  ('Air Fryer XL',                  'prod-sample-010', 'Crispy results with less oil.',         'assets/images/samples/img_2.jpg', 129.00, 35, NULL, 'Brand B',    NULL,     'active'),
  ('Wall Art Canvas',               'prod-sample-011', 'Abstract canvas wall art.',             'assets/images/samples/img_3.jpg',  49.00, 70, NULL, 'Brand C',    NULL,     'active'),
  ('King Size Duvet',               'prod-sample-012', 'Soft microfiber duvet.',                'assets/images/samples/img_4.jpg',  89.00, 45, NULL, 'Brand D',    NULL,     'active'),

  ('Hydrating Face Serum',          'prod-sample-013', 'Hyaluronic acid serum.',                'assets/images/samples/img_1.jpg',  25.00, 80, NULL, 'Brand E',    NULL,     'active'),
  ('Matte Lipstick',                'prod-sample-014', 'Long-lasting matte finish.',            'assets/images/samples/img_2.jpg',  12.00,120, NULL, 'Brand F',    NULL,     'active'),
  ('Nourishing Shampoo',            'prod-sample-015', 'For all hair types.',                   'assets/images/samples/img_3.jpg',   9.00,150, NULL, 'Brand G',    NULL,     'active'),
  ('Eau de Parfum',                 'prod-sample-016', 'Floral fragrance.',                     'assets/images/samples/img_4.jpg',  59.00, 60, NULL, 'Brand H',    NULL,     'active'),

  ('Adjustable Dumbbells',          'prod-sample-017', 'Space-saving strength training.',       'assets/images/samples/img_1.jpg', 199.00, 20, NULL, 'Brand I',    NULL,     'active'),
  ('Hiking Backpack 30L',           'prod-sample-018', 'Durable outdoor pack.',                 'assets/images/samples/img_2.jpg',  79.00, 50, NULL, 'Brand J',    NULL,     'active'),
  ('Breathable Sports Tee',         'prod-sample-019', 'Quick-dry athletic shirt.',             'assets/images/samples/img_3.jpg',  19.00,100, NULL, 'Under Armour','men',    'active'),
  ('Team Soccer Ball',              'prod-sample-020', 'Official size 5.',                      'assets/images/samples/img_4.jpg',  25.00, 80, NULL, 'Adidas',     NULL,     'active'),

  ('Mystery Novel',                 'prod-sample-021', 'Bestselling mystery thriller.',         'assets/images/samples/img_1.jpg',  14.00,200, NULL, 'Brand A',    NULL,     'active'),
  ('Science Fiction Epic',          'prod-sample-022', 'Space opera adventure.',                'assets/images/samples/img_2.jpg',  18.00,150, NULL, 'Brand B',    NULL,     'active'),
  ('Self-Help Guide',               'prod-sample-023', 'Practical life strategies.',            'assets/images/samples/img_3.jpg',  16.00,170, NULL, 'Brand C',    NULL,     'active'),
  ('Cookbook Favorites',            'prod-sample-024', '100 easy recipes.',                     'assets/images/samples/img_4.jpg',  22.00,120, NULL, 'Brand D',    NULL,     'active'),

  ('Family Board Game',             'prod-sample-025', 'Fun for all ages.',                     'assets/images/samples/img_1.jpg',  29.00, 90, NULL, 'Brand E',    NULL,     'active'),
  ('1000-piece Puzzle',             'prod-sample-026', 'Challenging and relaxing.',             'assets/images/samples/img_2.jpg',  15.00,110, NULL, 'Brand F',    NULL,     'active'),
  ('Action Figure Hero',            'prod-sample-027', 'Poseable collector figure.',            'assets/images/samples/img_3.jpg',  19.00,130, NULL, 'Brand G',    NULL,     'active'),
  ('STEM Kit Robotics',             'prod-sample-028', 'Learn coding and robotics.',            'assets/images/samples/img_4.jpg',  49.00, 70, NULL, 'Brand H',    NULL,     'active'),

  ('Women''s Summer Dress',         'prod-sample-029', 'Lightweight and flowy.',                'assets/images/samples/img_1.jpg',  39.00, 60, NULL, 'Zara',       'women',  'active'),
  ('Men''s Chinos',                 'prod-sample-030', 'Slim fit cotton chinos.',               'assets/images/samples/img_2.jpg',  34.00, 80, NULL, 'H&M',        'men',    'active')
ON DUPLICATE KEY UPDATE
  title=VALUES(title), description=VALUES(description), featured_image=VALUES(featured_image), price=VALUES(price), stock_qty=VALUES(stock_qty), discount_percent=VALUES(discount_percent), brand=VALUES(brand), gender=VALUES(gender), status=VALUES(status);

-- Resolve product ids
SET @pp01 = (SELECT id FROM products WHERE slug='prod-sample-001');
SET @pp02 = (SELECT id FROM products WHERE slug='prod-sample-002');
SET @pp03 = (SELECT id FROM products WHERE slug='prod-sample-003');
SET @pp04 = (SELECT id FROM products WHERE slug='prod-sample-004');
SET @pp05 = (SELECT id FROM products WHERE slug='prod-sample-005');
SET @pp06 = (SELECT id FROM products WHERE slug='prod-sample-006');
SET @pp07 = (SELECT id FROM products WHERE slug='prod-sample-007');
SET @pp08 = (SELECT id FROM products WHERE slug='prod-sample-008');
SET @pp09 = (SELECT id FROM products WHERE slug='prod-sample-009');
SET @pp10 = (SELECT id FROM products WHERE slug='prod-sample-010');
SET @pp11 = (SELECT id FROM products WHERE slug='prod-sample-011');
SET @pp12 = (SELECT id FROM products WHERE slug='prod-sample-012');
SET @pp13 = (SELECT id FROM products WHERE slug='prod-sample-013');
SET @pp14 = (SELECT id FROM products WHERE slug='prod-sample-014');
SET @pp15 = (SELECT id FROM products WHERE slug='prod-sample-015');
SET @pp16 = (SELECT id FROM products WHERE slug='prod-sample-016');
SET @pp17 = (SELECT id FROM products WHERE slug='prod-sample-017');
SET @pp18 = (SELECT id FROM products WHERE slug='prod-sample-018');
SET @pp19 = (SELECT id FROM products WHERE slug='prod-sample-019');
SET @pp20 = (SELECT id FROM products WHERE slug='prod-sample-020');
SET @pp21 = (SELECT id FROM products WHERE slug='prod-sample-021');
SET @pp22 = (SELECT id FROM products WHERE slug='prod-sample-022');
SET @pp23 = (SELECT id FROM products WHERE slug='prod-sample-023');
SET @pp24 = (SELECT id FROM products WHERE slug='prod-sample-024');
SET @pp25 = (SELECT id FROM products WHERE slug='prod-sample-025');
SET @pp26 = (SELECT id FROM products WHERE slug='prod-sample-026');
SET @pp27 = (SELECT id FROM products WHERE slug='prod-sample-027');
SET @pp28 = (SELECT id FROM products WHERE slug='prod-sample-028');
SET @pp29 = (SELECT id FROM products WHERE slug='prod-sample-029');
SET @pp30 = (SELECT id FROM products WHERE slug='prod-sample-030');

-- Map products to categories
INSERT IGNORE INTO product_categories (product_id, category_id) VALUES
  (@pp01, @cat_clothing), (@pp02, @cat_clothing), (@pp03, @cat_clothing), (@pp04, @cat_clothing),
  (@pp05, @cat_elec),     (@pp06, @cat_elec),     (@pp07, @cat_elec),     (@pp08, @cat_elec),
  (@pp09, @cat_home),     (@pp10, @cat_home),     (@pp11, @cat_home),     (@pp12, @cat_home),
  (@pp13, @cat_beauty),   (@pp14, @cat_beauty),   (@pp15, @cat_beauty),   (@pp16, @cat_beauty),
  (@pp17, @cat_sports),   (@pp18, @cat_sports),   (@pp19, @cat_sports),   (@pp20, @cat_sports),
  (@pp21, @cat_books),    (@pp22, @cat_books),    (@pp23, @cat_books),    (@pp24, @cat_books),
  (@pp25, @cat_toys),     (@pp26, @cat_toys),     (@pp27, @cat_toys),     (@pp28, @cat_toys),
  (@pp29, @cat_clothing), (@pp30, @cat_clothing);

-- Map products to subcategories (where applicable)
INSERT IGNORE INTO product_subcategories (product_id, subcategory_id) VALUES
  (@pp01, @sub_mens_wear),      (@pp02, @sub_womens_wear),  (@pp03, @sub_kids_clothing), (@pp04, @sub_clothing_acc),
  (@pp05, @sub_smartphones),    (@pp06, @sub_laptops),      (@pp07, @sub_tablets),       (@pp08, @sub_elec_acc),
  (@pp09, @sub_furniture),      (@pp10, @sub_kitchen_appl), (@pp11, @sub_home_decor),    (@pp12, @sub_bedding),
  (@pp13, @sub_skincare),       (@pp14, @sub_makeup),       (@pp15, @sub_hair_care),     (@pp16, @sub_fragrances),
  (@pp17, @sub_fitness_eq),     (@pp18, @sub_outdoor_gear), (@pp19, @sub_sports_apparel),(@pp20, @sub_team_sports),
  -- Books have no subcategory mapping
  (@pp25, @sub_board_games),    (@pp26, @sub_puzzles),      (@pp27, @sub_action_figures),(@pp28, @sub_edu_toys),
  (@pp29, @sub_womens_wear),    (@pp30, @sub_mens_wear);
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

-- Brands (popular, 15 items)
INSERT INTO brands (name, slug, description, logo_url)
VALUES
  ('Nike',          'nike',           'Innovative sportswear and footwear.',                'assets/images/logo.svg'),
  ('Adidas',        'adidas',         'Performance apparel and iconic sneakers.',           'assets/images/logo.svg'),
  ('Puma',          'puma',           'Sport-inspired footwear and apparel.',               'assets/images/logo.svg'),
  ('Reebok',        'reebok',         'Fitness-focused shoes and apparel.',                 'assets/images/logo.svg'),
  ('New Balance',   'new-balance',    'Comfort-first running and lifestyle shoes.',         'assets/images/logo.svg'),
  ('Under Armour',  'under-armour',   'Training gear and performance sportswear.',          'assets/images/logo.svg'),
  ('ASICS',         'asics',          'Running shoes engineered for distance.',             'assets/images/logo.svg'),
  ('Converse',      'converse',       'Classic canvas sneakers and street style.',          'assets/images/logo.svg'),
  ('Vans',          'vans',           'Skate-inspired shoes and apparel.',                  'assets/images/logo.svg'),
  ('FILA',          'fila',           'Retro athletic style and footwear.',                 'assets/images/logo.svg'),
  ('Champion',      'champion',       'Iconic athletic apparel and basics.',                'assets/images/logo.svg'),
  ('Jordan',        'jordan',         'Premium basketball footwear and apparel.',           'assets/images/logo.svg'),
  ('Skechers',      'skechers',       'Everyday comfort shoes and sneakers.',               'assets/images/logo.svg'),
  ('HOKA',          'hoka',           'Max-cushion performance running shoes.',             'assets/images/logo.svg'),
  ('On',            'on-running',     'Swiss-engineered running shoes with CloudTec.',      'assets/images/logo.svg')
ON DUPLICATE KEY UPDATE
  name=VALUES(name), description=VALUES(description), logo_url=VALUES(logo_url);

-- Colors (15 items)
INSERT INTO colors (name, hex)
VALUES
  ('Black',       '#000000'),
  ('White',       '#FFFFFF'),
  ('Red',         '#FF0000'),
  ('Green',       '#008000'),
  ('Blue',        '#0000FF'),
  ('Yellow',      '#FFFF00'),
  ('Orange',      '#FFA500'),
  ('Purple',      '#800080'),
  ('Pink',        '#FFC0CB'),
  ('Brown',       '#A52A2A'),
  ('Gray',        '#808080'),
  ('Cyan',        '#00FFFF'),
  ('Maroon',      '#800000'),
  ('Teal',        '#008080'),
  ('Light Blue',  '#ADD8E6')
ON DUPLICATE KEY UPDATE
  hex = VALUES(hex);

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


INSERT INTO languages (code, name, native_name, position, is_default, is_active) VALUES
('kh','Khmer','ខ្មែរ',1,1,1),
('en','English','English',2,0,1),
('zh','Chinese','中文',3,0,1),
('es','Spanish','Español',4,0,1),
('hi','Hindi','हिन्दी',5,0,1),
('ar','Arabic','العربية',6,0,1),
('bn','Bengali','বাংলা',7,0,1),
('pt','Portuguese','Português',8,0,1),
('ru','Russian','Русский',9,0,1),
('ja','Japanese','日本語',10,0,1),
('pa','Punjabi','ਪੰਜਾਬੀ',11,0,1),
('de','German','Deutsch',12,0,1),
('jv','Javanese','Basa Jawa',13,0,1),
('ko','Korean','한국어',14,0,1),
('fr','French','Français',15,0,1),
('te','Telugu','తెలుగు',16,0,1),
('mr','Marathi','मराठी',17,0,1),
('ta','Tamil','தமிழ்',18,0,1),
('ur','Urdu','اردو',19,0,1),
('vi','Vietnamese','Tiếng Việt',20,0,1),
('it','Italian','Italiano',21,0,1),
('tr','Turkish','Türkçe',22,0,1),
('th','Thai','ไทย',23,0,1),
('gu','Gujarati','ગુજરાતી',24,0,1),
('pl','Polish','Polski',25,0,1),
('uk','Ukrainian','Українська',26,0,1),
('ml','Malayalam','മലയാളം',27,0,1),
('kn','Kannada','ಕನ್ನಡ',28,0,1),
('or','Odia','ଓଡ଼ିଆ',29,0,1),
('my','Burmese','မြန်မာဘာသာ',30,0,1),
('fa','Persian','فارسی',31,0,1),
('ps','Pashto','پښتو',32,0,1),
('am','Amharic','አማርኛ',33,0,1),
('ha','Hausa','Hausa',34,0,1),
('yo','Yoruba','Yorùbá',35,0,1),
('ig','Igbo','Asụsụ Igbo',36,0,1),
('sw','Swahili','Kiswahili',37,0,1),
('zu','Zulu','isiZulu',38,0,1),
('af','Afrikaans','Afrikaans',39,0,1),
('nl','Dutch','Nederlands',40,0,1),
('sv','Swedish','Svenska',41,0,1),
('no','Norwegian','Norsk',42,0,1),
('da','Danish','Dansk',43,0,1),
('fi','Finnish','Suomi',44,0,1),
('el','Greek','Ελληνικά',45,0,1),
('he','Hebrew','עברית',46,0,1),
('hu','Hungarian','Magyar',47,0,1),
('cs','Czech','Čeština',48,0,1),
('ro','Romanian','Română',49,0,1),
('bg','Bulgarian','Български',50,0,1),
('sr','Serbian','Српски',51,0,1),
('hr','Croatian','Hrvatski',52,0,1),
('sk','Slovak','Slovenčina',53,0,1),
('sl','Slovenian','Slovenščina',54,0,1),
('lt','Lithuanian','Lietuvių',55,0,1),
('lv','Latvian','Latviešu',56,0,1),
('et','Estonian','Eesti',57,0,1),
('is','Icelandic','Íslenska',58,0,1),
('ga','Irish','Gaeilge',59,0,1),
('mt','Maltese','Malti',60,0,1);
