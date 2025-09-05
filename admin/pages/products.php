<?php
require_once __DIR__ . '/../config/function.php';
$categories = db_all('SELECT id, name FROM categories ORDER BY name ASC');
$subcategories = db_all('SELECT id, category_id, name FROM subcategories ORDER BY name ASC');
$brands = db_all('SELECT id, name FROM brands ORDER BY name ASC');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_product') {
      $title = trim($_POST['title'] ?? '');
      $brandId = (int)($_POST['brand_id'] ?? 0);
      $brand = '';
      $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
      $stockQty = isset($_POST['stock_qty']) ? (int)$_POST['stock_qty'] : 0;
      $discount = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? (float)$_POST['discount_percent'] : null;
      $gender = $_POST['gender'] ?? null;
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $status = $_POST['status'] ?? 'draft';
      $description = trim($_POST['description'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      $subcategoryId = (int)($_POST['subcategory_id'] ?? 0);

      if ($title === '') {
            header('Location: /admin/?p=products&error=Title%20is%20required');
            exit;
      }

      $slug = $slugInput !== '' ? make_slug($slugInput) : make_slug($title);
      if ($slug === '') {
            $slug = 'product-' . time();
      }

      // Ensure slug uniqueness
      $baseSlug = $slug;
      $i = 1;
      while (db_one('SELECT id FROM products WHERE slug = ?', [$slug])) {
            $slug = $baseSlug . '-' . (++$i);
      }

      $featuredImagePath = null;
      $uploadDir = __DIR__ . '/../assets/images/product_images';
      if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
      }

      if (!empty($_FILES['featured_image']['name'] ?? '')) {
            $tmp = $_FILES['featured_image']['tmp_name'] ?? '';
            $name = basename($_FILES['featured_image']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                  $safeName = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                  $dest = $uploadDir . '/' . $safeName;
                  if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                        $featuredImagePath = 'assets/images/product_images/' . $safeName;
                  }
            }
      }

      // Resolve brand name by selected brand_id
      if ($brandId > 0) {
            $b = db_one('SELECT name FROM brands WHERE id = ?', [$brandId]);
            if ($b && !empty($b['name'])) {
                  $brand = $b['name'];
            }
      }

      db_exec(
            'INSERT INTO products (title, slug, description, featured_image, price, stock_qty, discount_percent, brand, brand_id, gender, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                  $title,
                  $slug,
                  $description !== '' ? $description : null,
                  $featuredImagePath,
                  $price,
                  $stockQty,
                  $discount,
                  $brand !== '' ? $brand : null,
                  $brandId > 0 ? $brandId : null,
                  $gender !== '' ? $gender : null,
                  $status
            ]
      );
      $productId = db_last_insert_id();

      // Handle multiple gallery images
      if (!empty($_FILES['gallery_images']['name']) && is_array($_FILES['gallery_images']['name'])) {
            foreach ($_FILES['gallery_images']['name'] as $idx => $gName) {
                  if (($gName ?? '') === '') {
                        continue;
                  }
                  $tmp = $_FILES['gallery_images']['tmp_name'][$idx] ?? '';
                  $ext = strtolower(pathinfo($gName, PATHINFO_EXTENSION));
                  if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        continue;
                  }
                  $safeName = 'prod_' . $productId . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                  $dest = $uploadDir . '/' . $safeName;
                  if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                        $rel = 'assets/images/product_images/' . $safeName;
                        db_exec('INSERT INTO product_images (product_id, image_url, position) VALUES (?, ?, ?)', [$productId, $rel, $idx + 1]);
                  }
            }
      }

      // Map to category (single)
      if ($categoryId > 0) {
            // ensure single mapping
            db_exec('DELETE FROM product_categories WHERE product_id = ?', [$productId]);
            db_exec('INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)', [$productId, $categoryId]);
      }

      // Map to subcategory (single)
      db_exec('DELETE FROM product_subcategories WHERE product_id = ?', [$productId]);
      if ($subcategoryId > 0 && $categoryId > 0) {
            $valid = db_one('SELECT id FROM subcategories WHERE id = ? AND category_id = ?', [$subcategoryId, $categoryId]);
            if ($valid) {
                  db_exec('INSERT INTO product_subcategories (product_id, subcategory_id) VALUES (?, ?)', [$productId, $subcategoryId]);
            }
      }

      header('Location: /admin/?p=products&added=1');
      exit;
}

$deleted = false;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_product') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            // remove images from disk
            $p = db_one('SELECT featured_image FROM products WHERE id = ?', [$id]);
            if ($p && !empty($p['featured_image'])) {
                  $path = __DIR__ . '/../' . ltrim($p['featured_image'], '/');
                  if (is_file($path)) {
                        @unlink($path);
                  }
            }
            $imgs = db_all('SELECT image_url FROM product_images WHERE product_id = ?', [$id]);
            foreach ($imgs as $im) {
                  $path = __DIR__ . '/../' . ltrim($im['image_url'], '/');
                  if (is_file($path)) {
                        @unlink($path);
                  }
            }
            db_exec('DELETE FROM products WHERE id = ?', [$id]);
            header('Location: /admin/?p=products&deleted=1');
            exit;
      }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_product') {
      $id = (int)($_POST['id'] ?? 0);
      $title = trim($_POST['title'] ?? '');
      $brandId = (int)($_POST['brand_id'] ?? 0);
      $brand = '';
      $gender = $_POST['gender'] ?? null;
      $status = $_POST['status'] ?? 'draft';
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $subcategoryId = (int)($_POST['subcategory_id'] ?? 0);
      $description = trim($_POST['description'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
      $stockQty = isset($_POST['stock_qty']) ? (int)$_POST['stock_qty'] : 0;
      $discount = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? (float)$_POST['discount_percent'] : null;
      if ($id > 0 && $title !== '') {
            $old = db_one('SELECT slug, featured_image FROM products WHERE id = ?', [$id]);
            $slug = $slugInput !== '' ? make_slug($slugInput) : ($old['slug'] ?? make_slug($title));
            $base = $slug;
            $i = 1;
            while (db_one('SELECT id FROM products WHERE slug = ? AND id <> ?', [$slug, $id])) {
                  $slug = $base . '-' . (++$i);
            }

            $featuredImagePath = $old['featured_image'] ?? null;
            $uploadDir = __DIR__ . '/../assets/images/product_images';
            if (!is_dir($uploadDir)) {
                  @mkdir($uploadDir, 0775, true);
            }
            if (!empty($_FILES['featured_image']['name'] ?? '')) {
                  $tmp = $_FILES['featured_image']['tmp_name'] ?? '';
                  $name = basename($_FILES['featured_image']['name']);
                  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                  if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $safeName = 'prod_' . $id . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                        $dest = $uploadDir . '/' . $safeName;
                        if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                              // delete old
                              if (!empty($featuredImagePath)) {
                                    $oldPath = __DIR__ . '/../' . ltrim($featuredImagePath, '/');
                                    if (is_file($oldPath)) {
                                          @unlink($oldPath);
                                    }
                              }
                              $featuredImagePath = 'assets/images/product_images/' . $safeName;
                        }
                  }
            }

            // Resolve brand name by selected brand_id
            if ($brandId > 0) {
                  $b = db_one('SELECT name FROM brands WHERE id = ?', [$brandId]);
                  if ($b && !empty($b['name'])) {
                        $brand = $b['name'];
                  }
            }

            db_exec('UPDATE products SET title=?, slug=?, description=?, featured_image=?, price=?, stock_qty=?, discount_percent=?, brand=?, brand_id=?, gender=?, status=? WHERE id=?', [
                  $title,
                  $slug,
                  $description !== '' ? $description : null,
                  $featuredImagePath,
                  $price,
                  $stockQty,
                  $discount,
                  $brand !== '' ? $brand : null,
                  $brandId > 0 ? $brandId : null,
                  $gender !== '' ? $gender : null,
                  $status,
                  $id
            ]);

            // optional: add more gallery images
            if (!empty($_FILES['gallery_images']['name']) && is_array($_FILES['gallery_images']['name'])) {
                  $startPos = (int)(db_one('SELECT COALESCE(MAX(position),0) AS pos FROM product_images WHERE product_id=?', [$id])['pos'] ?? 0);
                  foreach ($_FILES['gallery_images']['name'] as $idx => $gName) {
                        if (($gName ?? '') === '') {
                              continue;
                        }
                        $tmp = $_FILES['gallery_images']['tmp_name'][$idx] ?? '';
                        $ext = strtolower(pathinfo($gName, PATHINFO_EXTENSION));
                        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                              continue;
                        }
                        $safeName = 'prod_' . $id . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                        $dest = $uploadDir . '/' . $safeName;
                        if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                              $rel = 'assets/images/product_images/' . $safeName;
                              db_exec('INSERT INTO product_images (product_id, image_url, position) VALUES (?, ?, ?)', [$id, $rel, $startPos + $idx + 1]);
                        }
                  }
            }

            // Update category mapping (single)
            db_exec('DELETE FROM product_categories WHERE product_id = ?', [$id]);
            if ($categoryId > 0) {
                  db_exec('INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)', [$id, $categoryId]);
            }

            // Update subcategory mapping (single)
            db_exec('DELETE FROM product_subcategories WHERE product_id = ?', [$id]);
            if ($subcategoryId > 0 && $categoryId > 0) {
                  $valid = db_one('SELECT id FROM subcategories WHERE id = ? AND category_id = ?', [$subcategoryId, $categoryId]);
                  if ($valid) {
                        db_exec('INSERT INTO product_subcategories (product_id, subcategory_id) VALUES (?, ?)', [$id, $subcategoryId]);
                  }
            }

            header('Location: /admin/?p=products&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=products&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM products');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$products = db_all('SELECT p.id, p.title, p.price, p.stock_qty, p.discount_percent, p.brand, p.brand_id, p.gender, p.status, p.created_at, p.slug, p.featured_image, pc.category_id, c.name AS category_name, ps.subcategory_id, sc.name AS subcategory_name FROM products p LEFT JOIN product_categories pc ON pc.product_id = p.id LEFT JOIN categories c ON c.id = pc.category_id LEFT JOIN product_subcategories ps ON ps.product_id = p.id LEFT JOIN subcategories sc ON sc.id = ps.subcategory_id ORDER BY p.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Products </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Product created successfully.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Product updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Product deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Products</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="products-table"> <!-- table-dt -->
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Image</th>
                                                <th>Brand</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Disc %</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($products as $p): ?>
                                                <tr>
                                                      <td><?php echo (int)$p['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?></td>
                                                      <td><?php if (!empty($p['featured_image'])): ?><img src="<?php echo htmlspecialchars($p['featured_image'], ENT_QUOTES); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:4px;" /><?php endif; ?></td>
                                                      <td><?php echo htmlspecialchars($p['brand'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($p['category_name'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)($p['price'] ?? 0), 2); ?></td>
                                                      <td><?php echo (int)($p['stock_qty'] ?? 0); ?></td>
                                                      <td><?php echo $p['discount_percent'] !== null ? number_format((float)$p['discount_percent'], 2) : '—'; ?></td>
                                                      <td><?php echo htmlspecialchars($p['gender'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><label class="badge badge-<?php echo $p['status'] === 'active' ? 'success' : ($p['status'] === 'archived' ? 'secondary' : 'warning'); ?>"><?php echo htmlspecialchars($p['status'], ENT_QUOTES); ?></label></td>
                                                      <td><?php echo htmlspecialchars($p['created_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal-<?php echo (int)$p['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this product?');">
                                                                  <input type="hidden" name="form" value="delete_product">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editProductModal-<?php echo (int)$p['id']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel-<?php echo (int)$p['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editProductModalLabel-<?php echo (int)$p['id']; ?>">Edit Product</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="form" value="update_product">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="row">
                                                                                    <div class="col-md-8 mb-3">
                                                                                          <label class="form-label">Title *</label>
                                                                                          <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Slug</label>
                                                                                          <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($p['slug'], ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Brand</label>
                                                                                          <select name="brand_id" class="form-select">
                                                                                                <option value="0">— None —</option>
                                                                                                <?php foreach ($brands as $b): ?>
                                                                                                      <option value="<?php echo (int)$b['id']; ?>" <?php echo (((int)($p['brand_id'] ?? 0) === (int)$b['id']) || (isset($p['brand']) && strcasecmp((string)$p['brand'], (string)$b['name']) === 0)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['name'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Category</label>
                                                                                          <select name="category_id" class="form-select" data-subcat-target="#edit-subcategory-<?php echo (int)$p['id']; ?>">
                                                                                                <option value="0">— None —</option>
                                                                                                <?php foreach ($categories as $cat): ?>
                                                                                                      <option value="<?php echo (int)$cat['id']; ?>" <?php echo ((int)($p['category_id'] ?? 0) === (int)$cat['id'] || (!isset($p['category_id']) && isset($p['category_name']) && strcasecmp((string)$p['category_name'], (string)$cat['name']) === 0)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Subcategory</label>
                                                                                          <select name="subcategory_id" id="edit-subcategory-<?php echo (int)$p['id']; ?>" class="form-select" data-initial-category="<?php echo (int)($p['category_id'] ?? 0); ?>" data-initial-subcategory="<?php echo (int)($p['subcategory_id'] ?? 0); ?>">
                                                                                                <option value="0">— None —</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Price</label>
                                                                                          <input type="number" step="0.01" min="0" name="price" class="form-control" value="<?php echo htmlspecialchars((string)($p['price'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Stock Qty</label>
                                                                                          <input type="number" step="1" min="0" name="stock_qty" class="form-control" value="<?php echo (int)($p['stock_qty'] ?? 0); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Discount %</label>
                                                                                          <input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control" value="<?php echo htmlspecialchars($p['discount_percent'] !== null ? (string)$p['discount_percent'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Gender</label>
                                                                                          <select name="gender" class="form-select">
                                                                                                <option value="" <?php echo empty($p['gender']) ? 'selected' : ''; ?>>—</option>
                                                                                                <option value="men" <?php echo ($p['gender'] === 'men') ? 'selected' : ''; ?>>Men</option>
                                                                                                <option value="women" <?php echo ($p['gender'] === 'women') ? 'selected' : ''; ?>>Women</option>
                                                                                                <option value="unisex" <?php echo ($p['gender'] === 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                                                                                                <option value="kids" <?php echo ($p['gender'] === 'kids') ? 'selected' : ''; ?>>Kids</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Status</label>
                                                                                          <select name="status" class="form-select">
                                                                                                <option value="draft" <?php echo ($p['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                                                                                <option value="active" <?php echo ($p['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                                                                                <option value="archived" <?php echo ($p['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-12 mb-3">
                                                                                          <label class="form-label">Description</label>
                                                                                          <textarea name="description" class="form-control" rows="4"></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Replace Featured Image</label>
                                                                                          <input type="file" name="featured_image" class="form-control" accept="image/*" data-preview-target="#edit-featured-preview-<?php echo (int)$p['id']; ?>">
                                                                                          <div id="edit-featured-preview-<?php echo (int)$p['id']; ?>" class="mt-2">
                                                                                                <?php if (!empty($p['featured_image'])): ?>
                                                                                                      <img src="<?php echo htmlspecialchars($p['featured_image'], ENT_QUOTES); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:4px;" />
                                                                                                <?php endif; ?>
                                                                                          </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Add Gallery Images</label>
                                                                                          <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple data-preview-target="#edit-gallery-preview-<?php echo (int)$p['id']; ?>">
                                                                                          <div id="edit-gallery-preview-<?php echo (int)$p['id']; ?>" class="d-flex flex-wrap gap-2 mt-2"></div>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                              <button type="submit" class="btn btn-gradient-primary">Save</button>
                                                                        </div>
                                                                  </form>
                                                            </div>
                                                      </div>
                                                </div>
                                          <?php endforeach; ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<?php if ($totalPages > 1): ?>
      <nav aria-label="Products pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=products&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=products&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=products&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form" value="add_product">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-8 mb-3">
                                          <label class="form-label">Title *</label>
                                          <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Slug (optional)</label>
                                          <input type="text" name="slug" class="form-control" placeholder="auto-from-title">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Brand</label>
                                          <select name="brand_id" class="form-select">
                                                <option value="0">— None —</option>
                                                <?php foreach ($brands as $b): ?>
                                                      <option value="<?php echo (int)$b['id']; ?>"><?php echo htmlspecialchars($b['name'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Category</label>
                                          <select name="category_id" class="form-select" data-subcat-target="#add-subcategory">
                                                <option value="0">— None —</option>
                                                <?php foreach ($categories as $cat): ?>
                                                      <option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['name'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Subcategory</label>
                                          <select name="subcategory_id" id="add-subcategory" class="form-select">
                                                <option value="0">— None —</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Price</label>
                                          <input type="number" step="0.01" min="0" name="price" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Stock Qty</label>
                                          <input type="number" step="1" min="0" name="stock_qty" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Discount %</label>
                                          <input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control" placeholder="e.g., 10 for 10%">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Gender</label>
                                          <select name="gender" class="form-select">
                                                <option value="">—</option>
                                                <option value="men">Men</option>
                                                <option value="women">Women</option>
                                                <option value="unisex">Unisex</option>
                                                <option value="kids">Kids</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Status</label>
                                          <select name="status" class="form-select">
                                                <option value="draft">Draft</option>
                                                <option value="active">Active</option>
                                                <option value="archived">Archived</option>
                                          </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                          <label class="form-label">Description</label>
                                          <textarea name="description" class="form-control" rows="4"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Featured Image</label>
                                          <input type="file" name="featured_image" class="form-control" accept="image/*" data-preview-target="#add-featured-preview">
                                          <div id="add-featured-preview" class="mt-2"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Gallery Images</label>
                                          <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple data-preview-target="#add-gallery-preview">
                                          <small class="text-muted">You can select multiple images.</small>
                                          <div id="add-gallery-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-gradient-primary">Create</button>
                        </div>
                  </form>
            </div>
      </div>
</div>

<script>
      (function() {
            const subcategories = <?php echo json_encode($subcategories, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;

            function populateSubcategories(selectEl, targetSelector, initialSubcategoryId) {
                  const target = document.querySelector(targetSelector);
                  if (!target) return;
                  const categoryId = parseInt(selectEl.value || '0', 10);
                  const options = [{
                        value: 0,
                        label: '— None —'
                  }];
                  if (categoryId > 0) {
                        for (const sc of subcategories) {
                              if (parseInt(sc.category_id, 10) === categoryId) {
                                    options.push({
                                          value: parseInt(sc.id, 10),
                                          label: sc.name
                                    });
                              }
                        }
                  }
                  target.innerHTML = '';
                  for (const opt of options) {
                        const o = document.createElement('option');
                        o.value = String(opt.value);
                        o.textContent = opt.label;
                        if (initialSubcategoryId && opt.value === initialSubcategoryId) {
                              o.selected = true;
                        }
                        target.appendChild(o);
                  }
            }

            // Wire up dynamic behavior for both Add and Edit forms
            document.addEventListener('change', function(e) {
                  const sel = e.target;
                  if (sel && sel.matches('select[name="category_id"][data-subcat-target]')) {
                        const targetSelector = sel.getAttribute('data-subcat-target');
                        populateSubcategories(sel, targetSelector, null);
                  }
            });

            // Initialize all existing pairs on load (handles Edit modals when opened too)
            function initAll() {
                  const selects = document.querySelectorAll('select[name="category_id"][data-subcat-target]');
                  for (const sel of selects) {
                        const targetSelector = sel.getAttribute('data-subcat-target');
                        const target = document.querySelector(targetSelector);
                        let initial = null;
                        if (target && target.hasAttribute('data-initial-subcategory')) {
                              const v = parseInt(target.getAttribute('data-initial-subcategory') || '0', 10);
                              initial = v > 0 ? v : null;
                        }
                        populateSubcategories(sel, targetSelector, initial);
                  }
            }

            // Initialize on DOM ready
            if (document.readyState === 'loading') {
                  document.addEventListener('DOMContentLoaded', initAll);
            } else {
                  initAll();
            }
      })();
</script>