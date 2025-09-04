<?php
require_once __DIR__ . '/../config/function.php';

$variants = db_all('SELECT v.id, v.sku, p.title AS product_title FROM variants v JOIN products p ON p.id = v.product_id ORDER BY v.id DESC');

// Add inventory row
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_inventory') {
      $variantId = (int)($_POST['variant_id'] ?? 0);
      $qtyAvailable = (int)($_POST['qty_available'] ?? 0);
      $qtyReserved = (int)($_POST['qty_reserved'] ?? 0);
      $lowStock = (int)($_POST['low_stock_threshold'] ?? 0);

      if ($variantId <= 0) {
            header('Location: /admin/?p=inventory&error=Invalid%20variant');
            exit;
      }

      db_exec('INSERT INTO inventory (variant_id, location_id, qty_available, qty_reserved, low_stock_threshold) VALUES (?, NULL, ?, ?, ?)', [
            $variantId,
            max(0, $qtyAvailable),
            max(0, $qtyReserved),
            max(0, $lowStock)
      ]);

      header('Location: /admin/?p=inventory&added=1');
      exit;
}

// Delete inventory row
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_inventory') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM inventory WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=inventory&deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM inventory');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all('SELECT i.id, i.variant_id, i.qty_available, i.qty_reserved, i.low_stock_threshold, v.sku, p.title AS product_title FROM inventory i JOIN variants v ON v.id=i.variant_id JOIN products p ON p.id=v.product_id ORDER BY i.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Inventory </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Inventory</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Inventory row added.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Inventory row deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">Inventory List</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">Add Inventory</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="inventory-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Variant SKU</th>
                                                <th>Available</th>
                                                <th>Reserved</th>
                                                <th>Low Stock</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['sku'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['qty_available']; ?></td>
                                                      <td><?php echo (int)$r['qty_reserved']; ?></td>
                                                      <td><?php echo (int)$r['low_stock_threshold']; ?></td>
                                                      <td>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this inventory row?');">
                                                                  <input type="hidden" name="form" value="delete_inventory">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                          <?php endforeach; ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<?php if ($totalPages > 1): ?>
      <nav aria-label="Inventory pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=inventory&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=inventory&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=inventory&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addInventoryModalLabel">Add Inventory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_inventory">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Variant *</label>
                                    <select class="form-select" name="variant_id" required>
                                          <option value="">— Select Variant —</option>
                                          <?php foreach ($variants as $v): ?>
                                                <option value="<?php echo (int)$v['id']; ?>"><?php echo htmlspecialchars(($v['product_title'] ?? '') . ' — ' . ($v['sku'] ?? ''), ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Available</label>
                                          <input type="number" step="1" min="0" name="qty_available" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Reserved</label>
                                          <input type="number" step="1" min="0" name="qty_reserved" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Low Stock Threshold</label>
                                          <input type="number" step="1" min="0" name="low_stock_threshold" class="form-control" value="0">
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