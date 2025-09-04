<?php
require_once __DIR__ . '/../config/function.php';

$products = db_all('SELECT id, title FROM products ORDER BY id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_size_chart') {
      $productId = trim($_POST['product_id'] ?? '');
      $gender = trim($_POST['gender'] ?? '');
      $region = trim($_POST['region'] ?? '');
      $sizeLabel = trim($_POST['size_label'] ?? '');
      $chest = trim($_POST['chest_cm'] ?? '');
      $waist = trim($_POST['waist_cm'] ?? '');
      $hips = trim($_POST['hips_cm'] ?? '');

      if ($sizeLabel === '') {
            header('Location: /admin/?p=size_charts&error=Size%20label%20is%20required');
            exit;
      }

      db_exec('INSERT INTO size_charts (product_id, gender, region, size_label, chest_cm, waist_cm, hips_cm) VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $productId !== '' ? (int)$productId : null,
            $gender !== '' ? $gender : null,
            $region !== '' ? substr($region, 0, 20) : null,
            substr($sizeLabel, 0, 40),
            $chest !== '' ? (float)$chest : null,
            $waist !== '' ? (float)$waist : null,
            $hips !== '' ? (float)$hips : null
      ]);

      header('Location: /admin/?p=size_charts&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_size_chart') {
      $id = (int)($_POST['id'] ?? 0);
      $productId = trim($_POST['product_id'] ?? '');
      $gender = trim($_POST['gender'] ?? '');
      $region = trim($_POST['region'] ?? '');
      $sizeLabel = trim($_POST['size_label'] ?? '');
      $chest = trim($_POST['chest_cm'] ?? '');
      $waist = trim($_POST['waist_cm'] ?? '');
      $hips = trim($_POST['hips_cm'] ?? '');

      if ($id <= 0 || $sizeLabel === '') {
            header('Location: /admin/?p=size_charts&error=Invalid%20data');
            exit;
      }

      db_exec('UPDATE size_charts SET product_id=?, gender=?, region=?, size_label=?, chest_cm=?, waist_cm=?, hips_cm=? WHERE id=?', [
            $productId !== '' ? (int)$productId : null,
            $gender !== '' ? $gender : null,
            $region !== '' ? substr($region, 0, 20) : null,
            substr($sizeLabel, 0, 40),
            $chest !== '' ? (float)$chest : null,
            $waist !== '' ? (float)$waist : null,
            $hips !== '' ? (float)$hips : null,
            $id
      ]);

      header('Location: /admin/?p=size_charts&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_size_chart') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM size_charts WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=size_charts&deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM size_charts');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all('SELECT sc.id, sc.product_id, sc.gender, sc.region, sc.size_label, sc.chest_cm, sc.waist_cm, sc.hips_cm, p.title AS product_title FROM size_charts sc LEFT JOIN products p ON p.id=sc.product_id ORDER BY sc.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Size Charts </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Size Charts</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Size chart added.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Size chart updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Size chart deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Size Charts</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSizeChartModal">Add Size Chart</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="size-charts-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Gender</th>
                                                <th>Region</th>
                                                <th>Size</th>
                                                <th>Chest (cm)</th>
                                                <th>Waist (cm)</th>
                                                <th>Hips (cm)</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['product_title'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['gender'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['region'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['size_label'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo $r['chest_cm'] !== null ? number_format((float)$r['chest_cm'], 2) : '—'; ?></td>
                                                      <td><?php echo $r['waist_cm'] !== null ? number_format((float)$r['waist_cm'], 2) : '—'; ?></td>
                                                      <td><?php echo $r['hips_cm'] !== null ? number_format((float)$r['hips_cm'], 2) : '—'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSizeChartModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this size chart?');">
                                                                  <input type="hidden" name="form" value="delete_size_chart">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSizeChartModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editSizeChartModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSizeChartModalLabel-<?php echo (int)$r['id']; ?>">Edit Size Chart</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_size_chart">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Product</label>
                                                                                    <select name="product_id" class="form-select">
                                                                                          <option value="">— None —</option>
                                                                                          <?php foreach ($products as $p): ?>
                                                                                                <option value="<?php echo (int)$p['id']; ?>" <?php echo ((int)($r['product_id'] ?? 0) === (int)$p['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Gender</label>
                                                                                          <select name="gender" class="form-select">
                                                                                                <option value="">—</option>
                                                                                                <option value="men" <?php echo (($r['gender'] ?? '') === 'men') ? 'selected' : ''; ?>>men</option>
                                                                                                <option value="women" <?php echo (($r['gender'] ?? '') === 'women') ? 'selected' : ''; ?>>women</option>
                                                                                                <option value="unisex" <?php echo (($r['gender'] ?? '') === 'unisex') ? 'selected' : ''; ?>>unisex</option>
                                                                                                <option value="kids" <?php echo (($r['gender'] ?? '') === 'kids') ? 'selected' : ''; ?>>kids</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Region</label>
                                                                                          <input type="text" name="region" class="form-control" maxlength="20" value="<?php echo htmlspecialchars($r['region'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Size Label *</label>
                                                                                          <input type="text" name="size_label" class="form-control" maxlength="40" required value="<?php echo htmlspecialchars($r['size_label'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Chest (cm)</label>
                                                                                          <input type="number" step="0.01" min="0" name="chest_cm" class="form-control" value="<?php echo htmlspecialchars($r['chest_cm'] !== null ? (string)$r['chest_cm'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Waist (cm)</label>
                                                                                          <input type="number" step="0.01" min="0" name="waist_cm" class="form-control" value="<?php echo htmlspecialchars($r['waist_cm'] !== null ? (string)$r['waist_cm'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Hips (cm)</label>
                                                                                          <input type="number" step="0.01" min="0" name="hips_cm" class="form-control" value="<?php echo htmlspecialchars($r['hips_cm'] !== null ? (string)$r['hips_cm'] : '', ENT_QUOTES); ?>">
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
      <nav aria-label="Size charts pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=size_charts&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=size_charts&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=size_charts&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addSizeChartModal" tabindex="-1" aria-labelledby="addSizeChartModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSizeChartModalLabel">Add Size Chart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_size_chart">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Product</label>
                                    <select name="product_id" class="form-select">
                                          <option value="">— None —</option>
                                          <?php foreach ($products as $p): ?>
                                                <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Gender</label>
                                          <select name="gender" class="form-select">
                                                <option value="">—</option>
                                                <option value="men">men</option>
                                                <option value="women">women</option>
                                                <option value="unisex">unisex</option>
                                                <option value="kids">kids</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Region</label>
                                          <input type="text" name="region" class="form-control" maxlength="20">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Size Label *</label>
                                          <input type="text" name="size_label" class="form-control" maxlength="40" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Chest (cm)</label>
                                          <input type="number" step="0.01" min="0" name="chest_cm" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Waist (cm)</label>
                                          <input type="number" step="0.01" min="0" name="waist_cm" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Hips (cm)</label>
                                          <input type="number" step="0.01" min="0" name="hips_cm" class="form-control">
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