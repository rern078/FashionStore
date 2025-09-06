<?php
function getCurrencies()
{

      $currencies = [];
      $defaultCurrencyCode = 'USD';
      try {
            $stmtCurrencies = db()->query("SELECT code, name, symbol, decimal_places, position, is_default FROM currencies WHERE is_active=1 ORDER BY is_default DESC, code ASC");
            $currencies = $stmtCurrencies ? $stmtCurrencies->fetchAll(PDO::FETCH_ASSOC) : [];
      } catch (Throwable $e) {
            // ignore if DB not available
      }

      // Determine default currency code from DB rows if available
      foreach ($currencies as $row) {
            if ((int)($row['is_default'] ?? 0) === 1 && !empty($row['code'])) {
                  $defaultCurrencyCode = (string)$row['code'];
                  break;
            }
      }

      // Handle explicit currency change via query parameter
      $requestedCode = isset($_GET['set_currency']) ? strtoupper((string)$_GET['set_currency']) : null;
      if ($requestedCode !== null) {
            foreach ($currencies as $row) {
                  if (strcasecmp((string)$row['code'], (string)$requestedCode) === 0) {
                        $_SESSION['currency'] = $requestedCode;
                        break;
                  }
            }
      }

      $currentCurrencyCode = strtoupper((string)($_SESSION['currency'] ?? $defaultCurrencyCode));

      $currentCurrencyLabel = $currentCurrencyCode;
      $currentCurrencySymbol = $currentCurrencyCode;
      foreach ($currencies as $row) {
            if (strcasecmp((string)$row['code'], (string)$currentCurrencyCode) === 0) {
                  $currentCurrencyLabel = (string)($row['name'] ?: $row['code']);
                  $currentCurrencySymbol = (string)($row['symbol'] ?: $row['code']);
                  break;
            }
      }

      return [
            'currencies' => $currencies,
            'currentCurrencyCode' => $currentCurrencyCode,
            'currentCurrencyLabel' => $currentCurrencyLabel,
            'currentCurrencySymbol' => $currentCurrencySymbol,
      ];
}

function getAbout()
{
      $row = db_one('SELECT title, content, content_2, image_url FROM about_us ORDER BY id ASC LIMIT 1');
      $aboutTitle = (string)($row['title'] ?? 'About Our Company');
      $aboutContent = (string)($row['content'] ?? '');
      $aboutContent2 = (string)($row['content_2'] ?? '');
      $aboutImage = (string)($row['image_url'] ?? '');
      return [
            'title' => $aboutTitle,
            'content' => $aboutContent,
            'content_2' => $aboutContent2,
            'image_url' => $aboutImage,
      ];
}

// end about logic

function getDefaultAddress()
{
      $defaultAddress = db_one('SELECT line1, line2, city, state, postal, country, business_hours FROM addresses WHERE is_default = 1 ORDER BY id DESC LIMIT 1');
      $addressText = 'Sangkat Teuk Thla, Kan Sensok, Phnom Penh, Cambodia';
      $businessHours = '';
      $parts = [];
      if (is_array($defaultAddress) && !empty($defaultAddress)) {
            if (!empty($defaultAddress['line1'])) {
                  $parts[] = (string)$defaultAddress['line1'];
            }
            if (!empty($defaultAddress['line2'])) {
                  $parts[] = (string)$defaultAddress['line2'];
            }
            $cityStatePostal = [];
            if (!empty($defaultAddress['city'])) {
                  $cityStatePostal[] = (string)$defaultAddress['city'];
            }
            if (!empty($defaultAddress['state'])) {
                  $cityStatePostal[] = (string)$defaultAddress['state'];
            }
            if (!empty($defaultAddress['postal'])) {
                  $cityStatePostal[] = (string)$defaultAddress['postal'];
            }
            if (!empty($cityStatePostal)) {
                  $parts[] = implode(', ', $cityStatePostal);
            }
            if (!empty($defaultAddress['country'])) {
                  $parts[] = strtoupper((string)$defaultAddress['country']);
            }
            if (!empty($defaultAddress['business_hours'])) {
                  $businessHours = (string)$defaultAddress['business_hours'];
            }
      }
      if (!empty($parts)) {
            $addressText = implode(', ', $parts);
      }
      return [$addressText, $businessHours];
}


function getContact()
{
      $socialLinks = db_all('SELECT platform, label, url, icon FROM social_links WHERE is_active = 1 ORDER BY position ASC, id DESC');
      $socialLinks = is_array($socialLinks) ? $socialLinks : [];

      $adminContact = db_one('SELECT name, email, phone FROM users WHERE role = ? ORDER BY id ASC LIMIT 1', ['admin']);
      $adminEmail = is_array($adminContact) && !empty($adminContact['email']) ? (string)$adminContact['email'] : '';
      $adminPhone = is_array($adminContact) && !empty($adminContact['phone']) ? (string)$adminContact['phone'] : '';

      return [
            'socialLinks' => $socialLinks,
            'adminEmail' => $adminEmail,
            'adminPhone' => $adminPhone,
      ];
}


function getCategories()
{
      $categories = db_all('SELECT id, name, slug FROM categories WHERE parent_id IS NULL ORDER BY name ASC');
      $subcategories = db_all('SELECT id, name, slug, category_id FROM subcategories ORDER BY name ASC');

      $subcatsByCategory = [];
      foreach ($subcategories as $subcat) {
            $categoryId = (int)$subcat['category_id'];
            $subcatsByCategory[$categoryId][] = $subcat;
      }

      return [
            'categories' => $categories,
            'subcatsByCategory' => $subcatsByCategory,
      ];
}

function getBrand()
{
      // Return brands with optional product counts for display in filters
      try {
            $rows = db_all(
                  "SELECT b.id, b.name, b.slug, b.logo_url, COUNT(p.id) AS product_count\n" .
                        "FROM brands b\n" .
                        "LEFT JOIN products p ON p.brand_id = b.id AND (p.status IS NULL OR p.status = 'active')\n" .
                        "GROUP BY b.id, b.name, b.slug, b.logo_url\n" .
                        "ORDER BY b.name ASC"
            );
      } catch (Throwable $e) {
            $rows = [];
      }

      return is_array($rows) ? $rows : [];
}

function getColors()
{
      // Return colors with optional usage counts from variants
      try {
            $rows = db_all(
                  "SELECT c.id, c.name, c.hex, COUNT(v.id) AS usage_count\n" .
                        "FROM colors c\n" .
                        "LEFT JOIN variants v ON v.color_id = c.id\n" .
                        "GROUP BY c.id, c.name, c.hex\n" .
                        "ORDER BY c.name ASC"
            );
      } catch (Throwable $e) {
            $rows = [];
      }

      return is_array($rows) ? $rows : [];
}

function getSizes()
{
      // Return sizes with optional usage counts from variants
      try {
            $rows = db_all(
                  "SELECT s.id, s.label, COUNT(v.id) AS usage_count\n" .
                        "FROM sizes s\n" .
                        "LEFT JOIN variants v ON v.size_id = s.id\n" .
                        "GROUP BY s.id, s.label\n" .
                        "ORDER BY s.label ASC"
            );
      } catch (Throwable $e) {
            $rows = [];
      }

      return is_array($rows) ? $rows : [];
}

function getAllProduct()
{
      // Return all active products for frontend listings
      try {
            $rows = db_all(
                  "SELECT id, title, slug, description, price, discount_percent, featured_image, stock_qty, brand, gender, care, status\n" .
                        "FROM products\n" .
                        "WHERE (status IS NULL OR status = 'active')\n" .
                        "ORDER BY id DESC"
            );
      } catch (Throwable $e) {
            $rows = [];
      }

      return is_array($rows) ? $rows : [];
}

function countActiveProducts(?string $categorySlug = null): int
{
      try {
            if ($categorySlug !== null && $categorySlug !== '') {
                  $row = db_one(
                        "SELECT COUNT(DISTINCT p.id) AS c\n" .
                              "FROM products p\n" .
                              "LEFT JOIN product_categories pc ON pc.product_id = p.id\n" .
                              "LEFT JOIN categories c ON c.id = pc.category_id\n" .
                              "LEFT JOIN product_subcategories ps ON ps.product_id = p.id\n" .
                              "LEFT JOIN subcategories s ON s.id = ps.subcategory_id\n" .
                              "LEFT JOIN categories cp ON cp.id = s.category_id\n" .
                              "WHERE (p.status IS NULL OR p.status = 'active') AND (c.slug = ? OR cp.slug = ?)",
                        [$categorySlug, $categorySlug]
                  );
            } else {
                  $row = db_one(
                        "SELECT COUNT(*) AS c\n" .
                              "FROM products\n" .
                              "WHERE (status IS NULL OR status = 'active')"
                  );
            }
            return (int)($row['c'] ?? 0);
      } catch (Throwable $e) {
            return 0;
      }
}

function getActiveProductsPaginated(int $limit = 8, int $offset = 0, ?string $categorySlug = null): array
{
      $limit = max(1, min(100, (int)$limit));
      $offset = max(0, (int)$offset);
      try {
            $slugsJoin =
                  "LEFT JOIN (\n" .
                  "  SELECT pc.product_id, c.slug FROM product_categories pc JOIN categories c ON c.id = pc.category_id\n" .
                  "  UNION\n" .
                  "  SELECT ps.product_id, c2.slug FROM product_subcategories ps JOIN subcategories s ON s.id = ps.subcategory_id JOIN categories c2 ON c2.id = s.category_id\n" .
                  ") slugs ON slugs.product_id = p.id";

            if ($categorySlug !== null && $categorySlug !== '') {
                  $sql =
                        "SELECT p.id, p.title, p.slug, p.description, p.price, p.discount_percent, p.featured_image, p.stock_qty, p.brand, p.gender, p.care, p.status,\n" .
                        "       GROUP_CONCAT(DISTINCT slugs.slug) AS category_slugs\n" .
                        "FROM products p\n" .
                        $slugsJoin . "\n" .
                        "WHERE (p.status IS NULL OR p.status = 'active') AND slugs.slug = ?\n" .
                        "GROUP BY p.id, p.title, p.slug, p.description, p.price, p.discount_percent, p.featured_image, p.stock_qty, p.brand, p.gender, p.care, p.status\n" .
                        "ORDER BY p.id DESC\n" .
                        "LIMIT $limit OFFSET $offset";
                  $rows = db_all($sql, [$categorySlug]);
            } else {
                  $sql =
                        "SELECT p.id, p.title, p.slug, p.description, p.price, p.discount_percent, p.featured_image, p.stock_qty, p.brand, p.gender, p.care, p.status,\n" .
                        "       GROUP_CONCAT(DISTINCT slugs.slug) AS category_slugs\n" .
                        "FROM products p\n" .
                        $slugsJoin . "\n" .
                        "WHERE (p.status IS NULL OR p.status = 'active')\n" .
                        "GROUP BY p.id, p.title, p.slug, p.description, p.price, p.discount_percent, p.featured_image, p.stock_qty, p.brand, p.gender, p.care, p.status\n" .
                        "ORDER BY p.id DESC\n" .
                        "LIMIT $limit OFFSET $offset";
                  $rows = db_all($sql);
            }
      } catch (Throwable $e) {
            $rows = [];
      }
      return is_array($rows) ? $rows : [];
}
