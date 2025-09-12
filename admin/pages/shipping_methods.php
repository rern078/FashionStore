<?php
require_once __DIR__ . '/../config/function.php';

// Create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_shipping') {
      $name = trim((string)($_POST['name'] ?? ''));
      $code = strtolower(trim((string)($_POST['code'] ?? '')));
      $baseCost = (float)($_POST['base_cost'] ?? 0);
      $minFree = isset($_POST['min_subtotal_free']) && $_POST['min_subtotal_free'] !== '' ? (float)$_POST['min_subtotal_free'] : null;
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
      if ($name === '' || $code === '') {
            header('Location: /admin/?p=shipping_methods&error=Invalid%20data');
            exit;
      }
      try {
            db_exec('INSERT INTO shipping_methods (name, code, base_cost, min_subtotal_free, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?)', [
                  substr($name, 0, 150),
                  substr($code, 0, 80),
                  $baseCost,
                  $minFree,
                  $isActive,
                  $sortOrder,
            ]);
            header('Location: /admin/?p=shipping_methods&added=1');
            exit;
      } catch (Throwable $e) {
            header('Location: /admin/?p=shipping_methods&error=' . urlencode($e->getMessage()));
            exit;
      }
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_shipping') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim((string)($_POST['name'] ?? ''));
      $code = strtolower(trim((string)($_POST['code'] ?? '')));
      $baseCost = (float)($_POST['base_cost'] ?? 0);
      $minFree = isset($_POST['min_subtotal_free']) && $_POST['min_subtotal_free'] !== '' ? (float)$_POST['min_subtotal_free'] : null;
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
      if ($id <= 0 || $name === '' || $code === '') {
            header('Location: /admin/?p=shipping_methods&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE shipping_methods SET name=?, code=?, base_cost=?, min_subtotal_free=?, is_active=?, sort_order=? WHERE id=?', [
            substr($name, 0, 150),
            substr($code, 0, 80),
            $baseCost,
            $minFree,
            $isActive,
            $sortOrder,
            $id,
      ]);
      header('Location: /admin/?p=shipping_methods&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_shipping') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) db_exec('DELETE FROM shipping_methods WHERE id=?', [$id]);
      header('Location: /admin/?p=shipping_methods&deleted=1');
      exit;
}

$rows = db_all('SELECT id, name, code, base_cost, min_subtotal_free, is_active, sort_order FROM shipping_methods ORDER BY sort_order ASC, id DESC');
?>

<div class="page-header">
      <h3 class="page-title"> Shipping Methods </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Shipping Methods</li>
            </ol>
      </nav>
      }
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success">Shipping method created.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success">Shipping method updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Shipping method deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Shipping Methods</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addShippingModal">Add Shipping</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Base Cost</th>
                                                <th>Free over</th>
                                                <th>Active</th>
                                                <th>Sort</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r) { ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['code'], ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)$r['base_cost'], 2); ?></td>
                                                      <td><?php echo $r['min_subtotal_free'] !== null ? number_format((float)$r['min_subtotal_free'], 2) : '-'; ?></td>
                                                      <td><?php echo (int)$r['is_active'] === 1 ? 'Yes' : 'No'; ?></td>
                                                      <td><?php echo (int)$r['sort_order']; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editShippingModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this shipping method?');">
                                                                  <input type="hidden" name="form" value="delete_shipping">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editShippingModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Shipping</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_shipping">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Code *</label><input type="text" class="form-control" name="code" required value="<?php echo htmlspecialchars($r['code'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Base Cost *</label><input type="number" step="0.01" min="0" class="form-control" name="base_cost" required value="<?php echo htmlspecialchars((string)$r['base_cost'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Min Subtotal for Free</label><input type="number" step="0.01" min="0" class="form-control" name="min_subtotal_free" value="<?php echo htmlspecialchars((string)$r['min_subtotal_free'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" min="0" class="form-control" name="sort_order" value="<?php echo (int)$r['sort_order']; ?>"></div>
                                                                              <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" id="is_active_<?php echo (int)$r['id']; ?>" <?php echo ((int)$r['is_active'] === 1) ? 'checked' : ''; ?>><label class="form-check-label" for="is_active_<?php echo (int)$r['id']; ?>">Active</label></div>
                                                                        </div>
                                                                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Save</button></div>
                                                                  </form>
                                                            </div>
                                                      </div>
                                                </div>
                                          <?php } ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="addShippingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Add Shipping</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_shipping">
                        <div class="modal-body">
                              <div class="mb-3"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" required></div>
                              <div class="mb-3"><label class="form-label">Code *</label><input type="text" class="form-control" name="code" required placeholder="standard, express, ..."></div>
                              <div class="mb-3"><label class="form-label">Base Cost *</label><input type="number" step="0.01" min="0" class="form-control" name="base_cost" required value="0.00"></div>
                              <div class="mb-3"><label class="form-label">Min Subtotal for Free</label><input type="number" step="0.01" min="0" class="form-control" name="min_subtotal_free" placeholder="e.g. 300.00"></div>
                              <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" min="0" class="form-control" name="sort_order" value="0"></div>
                              <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" id="is_active_new" checked><label class="form-check-label" for="is_active_new">Active</label></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Create</button></div>
                  </form>
            </div>
      </div>
</div>