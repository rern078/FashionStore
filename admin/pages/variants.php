<?php
require_once __DIR__ . '/../config/function.php';

$products = db_all('SELECT id, title FROM products ORDER BY id DESC');
// Load colors and sizes for selects
$colors = db_all('SELECT id, name, hex FROM colors ORDER BY name ASC');
$sizes = db_all('SELECT id, label, sort_order FROM sizes ORDER BY sort_order ASC, label ASC');

// Add variant
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_variant') {
      $productId = (int)($_POST['product_id'] ?? 0);
      $sku = trim($_POST['sku'] ?? '');
      $colorId = (int)($_POST['color_id'] ?? 0);
      $sizeId = (int)($_POST['size_id'] ?? 0);
      $color = '';
      $size = '';
      $material = trim($_POST['material'] ?? '');
      $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
      $compareAt = isset($_POST['compare_at_price']) && $_POST['compare_at_price'] !== '' ? (float)$_POST['compare_at_price'] : null;
      $weight = isset($_POST['weight']) && $_POST['weight'] !== '' ? (float)$_POST['weight'] : null;
      $barcode = trim($_POST['barcode'] ?? '');

      if ($productId <= 0 || $sku === '' || $price <= 0) {
            header('Location: /admin/?p=variants&error=Missing%20required%20fields');
            exit;
      }

      $exists = db_one('SELECT id FROM variants WHERE sku = ?', [$sku]);
      if ($exists) {
            header('Location: /admin/?p=variants&error=SKU%20already%20exists');
            exit;
      }

      // Resolve color/size labels from IDs
      if ($colorId > 0) {
            $c = db_one('SELECT name FROM colors WHERE id=?', [$colorId]);
            if ($c && !empty($c['name'])) {
                  $color = (string)$c['name'];
            }
      }
      if ($sizeId > 0) {
            $s = db_one('SELECT label FROM sizes WHERE id=?', [$sizeId]);
            if ($s && !empty($s['label'])) {
                  $size = (string)$s['label'];
            }
      }

      db_exec('INSERT INTO variants (product_id, sku, color, color_id, size, size_id, material, price, compare_at_price, weight, barcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $productId,
            $sku,
            $color !== '' ? $color : null,
            $colorId > 0 ? $colorId : null,
            $size !== '' ? $size : null,
            $sizeId > 0 ? $sizeId : null,
            $material !== '' ? $material : null,
            $price,
            $compareAt,
            $weight,
            $barcode !== '' ? $barcode : null
      ]);

      header('Location: /admin/?p=variants&added=1');
      exit;
}

// Delete variant
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_variant') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM variants WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=variants&deleted=1');
      exit;
}

// Update variant
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_variant') {
      $id = (int)($_POST['id'] ?? 0);
      $productId = (int)($_POST['product_id'] ?? 0);
      $sku = trim($_POST['sku'] ?? '');
      $colorId = (int)($_POST['color_id'] ?? 0);
      $sizeId = (int)($_POST['size_id'] ?? 0);
      $color = '';
      $size = '';
      $material = trim($_POST['material'] ?? '');
      $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
      $compareAt = isset($_POST['compare_at_price']) && $_POST['compare_at_price'] !== '' ? (float)$_POST['compare_at_price'] : null;
      $weight = isset($_POST['weight']) && $_POST['weight'] !== '' ? (float)$_POST['weight'] : null;
      $barcode = trim($_POST['barcode'] ?? '');

      if ($id <= 0 || $productId <= 0 || $sku === '' || $price <= 0) {
            header('Location: /admin/?p=variants&error=Invalid%20data');
            exit;
      }

      $exists = db_one('SELECT id FROM variants WHERE sku = ? AND id <> ?', [$sku, $id]);
      if ($exists) {
            header('Location: /admin/?p=variants&error=SKU%20already%20exists');
            exit;
      }

      // Resolve color/size labels from IDs
      if ($colorId > 0) {
            $c = db_one('SELECT name FROM colors WHERE id=?', [$colorId]);
            if ($c && !empty($c['name'])) {
                  $color = (string)$c['name'];
            }
      }
      if ($sizeId > 0) {
            $s = db_one('SELECT label FROM sizes WHERE id=?', [$sizeId]);
            if ($s && !empty($s['label'])) {
                  $size = (string)$s['label'];
            }
      }

      db_exec('UPDATE variants SET product_id=?, sku=?, color=?, color_id=?, size=?, size_id=?, material=?, price=?, compare_at_price=?, weight=?, barcode=? WHERE id=?', [
            $productId,
            $sku,
            $color !== '' ? $color : null,
            $colorId > 0 ? $colorId : null,
            $size !== '' ? $size : null,
            $sizeId > 0 ? $sizeId : null,
            $material !== '' ? $material : null,
            $price,
            $compareAt,
            $weight,
            $barcode !== '' ? $barcode : null,
            $id
      ]);

      header('Location: /admin/?p=variants&updated=1');
      exit;
}

// Add variant images
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_variant_images') {
      $variantId = (int)($_POST['variant_id'] ?? 0);
      if ($variantId > 0 && !empty($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {
            $uploadDir = __DIR__ . '/../assets/images/product_images';
            if (!is_dir($uploadDir)) {
                  @mkdir($uploadDir, 0775, true);
            }
            $startPos = (int)(db_one('SELECT COALESCE(MAX(position),0) AS pos FROM variant_images WHERE variant_id=?', [$variantId])['pos'] ?? 0);
            foreach ($_FILES['images']['name'] as $idx => $gName) {
                  if (($gName ?? '') === '') continue;
                  $tmp = $_FILES['images']['tmp_name'][$idx] ?? '';
                  $ext = strtolower(pathinfo($gName, PATHINFO_EXTENSION));
                  if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;
                  $safeName = 'var_' . $variantId . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                  $dest = $uploadDir . '/' . $safeName;
                  if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                        $rel = 'assets/images/product_images/' . $safeName;
                        db_exec('INSERT INTO variant_images (variant_id, image_url, position) VALUES (?, ?, ?)', [$variantId, $rel, $startPos + $idx + 1]);
                  }
            }
      }
      header('Location: /admin/?p=variants&images_added=1');
      exit;
}

// Delete a variant image
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_variant_image') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            $im = db_one('SELECT image_url FROM variant_images WHERE id=?', [$id]);
            if ($im && !empty($im['image_url'])) {
                  $path = __DIR__ . '/../' . ltrim($im['image_url'], '/');
                  if (is_file($path)) @unlink($path);
            }
            db_exec('DELETE FROM variant_images WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=variants&image_deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM variants');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$variants = db_all('SELECT v.id, v.product_id, v.sku, v.color, v.color_id, v.size, v.size_id, v.material, v.price, v.compare_at_price, v.weight, v.barcode, p.title AS product_title FROM variants v JOIN products p ON p.id=v.product_id ORDER BY v.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);

?>

<div class="page-header">
      <h3 class="page-title"> Variants </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Variants</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Variant created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Variant updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Variant deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['images_added'])): ?>
      <div class="alert alert-success" role="alert">Variant images added.</div>
<?php endif; ?>
<?php if (!empty($_GET['image_deleted'])): ?>
      <div class="alert alert-success" role="alert">Variant image deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Variants</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">Add Variant</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="variants-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Material</th>
                                                <th>Price</th>
                                                <th>Compare at</th>
                                                <th>Weight</th>
                                                <th>Barcode</th>
                                                <th>Images</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($variants as $v): $images = db_all('SELECT id, image_url, position FROM variant_images WHERE variant_id=? ORDER BY position ASC, id ASC', [$v['id']]); ?>
                                                <tr>
                                                      <td><?php echo (int)$v['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($v['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($v['sku'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($v['color'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($v['size'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($v['material'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)($v['price'] ?? 0), 2); ?></td>
                                                      <td><?php echo $v['compare_at_price'] !== null ? number_format((float)$v['compare_at_price'], 2) : '—'; ?></td>
                                                      <td><?php echo $v['weight'] !== null ? number_format((float)$v['weight'], 3) : '—'; ?></td>
                                                      <td><?php echo htmlspecialchars($v['barcode'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                  <?php foreach ($images as $im): ?>
                                                                        <div class="position-relative" style="width:48px;height:48px;">
                                                                              <img src="<?php echo htmlspecialchars($im['image_url'], ENT_QUOTES); ?>" style="width:48px;height:48px;object-fit:cover;border-radius:4px;" />
                                                                              <form method="post" class="position-absolute" style="top:-8px;right:-8px;">
                                                                                    <input type="hidden" name="form" value="delete_variant_image">
                                                                                    <input type="hidden" name="id" value="<?php echo (int)$im['id']; ?>">
                                                                                    <button type="submit" class="btn btn-sm btn-danger" style="padding:2px 6px;line-height:1;" onclick="return confirm('Delete this image?');">×</button>
                                                                              </form>
                                                                        </div>
                                                                  <?php endforeach; ?>
                                                            </div>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editVariantModal-<?php echo (int)$v['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this variant?');">
                                                                  <input type="hidden" name="form" value="delete_variant">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editVariantModal-<?php echo (int)$v['id']; ?>" tabindex="-1" aria-labelledby="editVariantModalLabel-<?php echo (int)$v['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editVariantModalLabel-<?php echo (int)$v['id']; ?>">Edit Variant</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_variant">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Product *</label>
                                                                                          <select name="product_id" class="form-select" required>
                                                                                                <option value="">— Select Product —</option>
                                                                                                <?php foreach ($products as $p): ?>
                                                                                                      <option value="<?php echo (int)$p['id']; ?>" <?php echo ((int)$v['product_id'] === (int)$p['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">SKU *</label>
                                                                                          <input type="text" name="sku" class="form-control" required value="<?php echo htmlspecialchars($v['sku'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Color</label>
                                                                                          <select name="color_id" class="form-select">
                                                                                                <option value="0">— None —</option>
                                                                                                <?php foreach ($colors as $c): ?>
                                                                                                      <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)($v['color_id'] ?? 0) === (int)$c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Size</label>
                                                                                          <select name="size_id" class="form-select">
                                                                                                <option value="0">— None —</option>
                                                                                                <?php foreach ($sizes as $s): ?>
                                                                                                      <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)($v['size_id'] ?? 0) === (int)$s['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['label'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Material</label>
                                                                                          <input type="text" name="material" class="form-control" value="<?php echo htmlspecialchars($v['material'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Price *</label>
                                                                                          <input type="number" step="0.01" min="0" name="price" class="form-control" required value="<?php echo htmlspecialchars((string)($v['price'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Compare at Price</label>
                                                                                          <input type="number" step="0.01" min="0" name="compare_at_price" class="form-control" value="<?php echo htmlspecialchars($v['compare_at_price'] !== null ? (string)$v['compare_at_price'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Weight (kg)</label>
                                                                                          <input type="number" step="0.001" min="0" name="weight" class="form-control" value="<?php echo htmlspecialchars($v['weight'] !== null ? (string)$v['weight'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Barcode</label>
                                                                                          <input type="text" name="barcode" class="form-control" value="<?php echo htmlspecialchars($v['barcode'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                              <button type="submit" class="btn btn-gradient-primary">Save</button>
                                                                        </div>
                                                                  </form>
                                                                  <div class="border-top p-3">
                                                                        <form method="post" enctype="multipart/form-data">
                                                                              <input type="hidden" name="form" value="add_variant_images">
                                                                              <input type="hidden" name="variant_id" value="<?php echo (int)$v['id']; ?>">
                                                                              <label class="form-label">Add Images</label>
                                                                              <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                                                              <button type="submit" class="btn btn-sm btn-outline-secondary mt-2">Upload</button>
                                                                        </form>
                                                                  </div>
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
      <nav aria-label="Variants pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=variants&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=variants&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=variants&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addVariantModalLabel">Add Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_variant">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Product *</label>
                                          <select name="product_id" class="form-select" required>
                                                <option value="">— Select Product —</option>
                                                <?php foreach ($products as $p): ?>
                                                      <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">SKU *</label>
                                          <input type="text" name="sku" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Color</label>
                                          <select name="color_id" class="form-select">
                                                <option value="0">— None —</option>
                                                <?php foreach ($colors as $c): ?>
                                                      <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Size</label>
                                          <select name="size_id" class="form-select">
                                                <option value="0">— None —</option>
                                                <?php foreach ($sizes as $s): ?>
                                                      <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['label'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Material</label>
                                          <input type="text" name="material" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Price *</label>
                                          <input type="number" step="0.01" min="0" name="price" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Compare at Price</label>
                                          <input type="number" step="0.01" min="0" name="compare_at_price" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Weight (kg)</label>
                                          <input type="number" step="0.001" min="0" name="weight" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Barcode</label>
                                          <input type="text" name="barcode" class="form-control">
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