<?php
require_once __DIR__ . '/../config/function.php';

$returnId = (int)($_GET['return_id'] ?? 0);
$returns = db_all('SELECT id, rma_no FROM returns ORDER BY id DESC');
$orderItems = db_all('SELECT oi.id, oi.order_id, v.sku, p.title AS product_title FROM order_items oi JOIN variants v ON v.id=oi.variant_id JOIN products p ON p.id=v.product_id ORDER BY oi.id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_return_item') {
      $ret = (int)($_POST['return_id'] ?? 0);
      $orderItem = (int)($_POST['order_item_id'] ?? 0);
      $qty = max(1, (int)($_POST['qty'] ?? 1));
      $reason = trim($_POST['reason'] ?? '');
      $resolution = $_POST['resolution'] ?? 'refund';
      if ($ret <= 0 || $orderItem <= 0 || !in_array($resolution, ['refund', 'exchange'], true)) {
            header('Location: /admin/?p=return_items&return_id=' . $ret . '&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO return_items (return_id, order_item_id, qty, reason, resolution) VALUES (?, ?, ?, ?, ?)', [$ret, $orderItem, $qty, $reason !== '' ? substr($reason, 0, 200) : null, $resolution]);
      header('Location: /admin/?p=return_items&return_id=' . $ret . '&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_return_item') {
      $id = (int)($_POST['id'] ?? 0);
      $ret = (int)($_POST['return_id'] ?? 0);
      $orderItem = (int)($_POST['order_item_id'] ?? 0);
      $qty = max(1, (int)($_POST['qty'] ?? 1));
      $reason = trim($_POST['reason'] ?? '');
      $resolution = $_POST['resolution'] ?? 'refund';
      if ($id <= 0 || $ret <= 0 || $orderItem <= 0 || !in_array($resolution, ['refund', 'exchange'], true)) {
            header('Location: /admin/?p=return_items&return_id=' . $ret . '&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE return_items SET return_id=?, order_item_id=?, qty=?, reason=?, resolution=? WHERE id=?', [$ret, $orderItem, $qty, $reason !== '' ? substr($reason, 0, 200) : null, $resolution, $id]);
      header('Location: /admin/?p=return_items&return_id=' . $ret . '&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_return_item') {
      $id = (int)($_POST['id'] ?? 0);
      $ret = (int)($_POST['return_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM return_items WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=return_items&return_id=' . $ret . '&deleted=1');
      exit;
}

// List
if ($returnId > 0) {
      $rows = db_all('SELECT ri.id, ri.return_id, ri.qty, ri.reason, ri.resolution, oi.order_id, v.sku, p.title AS product_title FROM return_items ri JOIN order_items oi ON oi.id=ri.order_item_id JOIN variants v ON v.id=oi.variant_id JOIN products p ON p.id=v.product_id WHERE ri.return_id=? ORDER BY ri.id DESC', [$returnId]);
} else {
      $rows = db_all('SELECT ri.id, ri.return_id, ri.qty, ri.reason, ri.resolution, oi.order_id, v.sku, p.title AS product_title FROM return_items ri JOIN order_items oi ON oi.id=ri.order_item_id JOIN variants v ON v.id=oi.variant_id JOIN products p ON p.id=v.product_id ORDER BY ri.id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Return Items </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=returns">Returns</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Items</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Item added.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Item updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Item deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Items</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addReturnItemModal">Add Item</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="return-items-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <?php if (!$returnId): ?><th>Return</th><?php endif; ?>
                                                <th>Order</th>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Qty</th>
                                                <th>Resolution</th>
                                                <th>Reason</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <?php if (!$returnId): ?><td><?php echo (int)$r['return_id']; ?></td><?php endif; ?>
                                                      <td><?php echo (int)$r['order_id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['sku'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['qty']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['resolution'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['reason'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editReturnItemModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this item?');">
                                                                  <input type="hidden" name="form" value="delete_return_item">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <input type="hidden" name="return_id" value="<?php echo (int)$returnId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editReturnItemModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editReturnItemModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editReturnItemModalLabel-<?php echo (int)$r['id']; ?>">Edit Return Item</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_return_item">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Return *</label>
                                                                                    <select class="form-select" name="return_id" required>
                                                                                          <?php foreach ($returns as $ret): ?>
                                                                                                <option value="<?php echo (int)$ret['id']; ?>" <?php echo ((int)$r['return_id'] === (int)$ret['id']) ? 'selected' : ''; ?>><?php echo 'RMA #' . (int)$ret['id'] . ' — ' . htmlspecialchars($ret['rma_no'] ?? '', ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Order Item *</label>
                                                                                    <select class="form-select" name="order_item_id" required>
                                                                                          <?php foreach ($orderItems as $oi): ?>
                                                                                                <option value="<?php echo (int)$oi['id']; ?>"><?php echo 'OI #' . (int)$oi['id'] . ' — ' . htmlspecialchars(($oi['product_title'] ?? '') . ' — ' . ($oi['sku'] ?? ''), ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Qty</label>
                                                                                          <input type="number" step="1" min="1" name="qty" class="form-control" value="<?php echo (int)$r['qty']; ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Resolution</label>
                                                                                          <select name="resolution" class="form-select">
                                                                                                <option value="refund" <?php echo ($r['resolution'] === 'refund') ? 'selected' : ''; ?>>refund</option>
                                                                                                <option value="exchange" <?php echo ($r['resolution'] === 'exchange') ? 'selected' : ''; ?>>exchange</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-3">
                                                                                          <label class="form-label">Reason</label>
                                                                                          <input type="text" name="reason" class="form-control" value="<?php echo htmlspecialchars($r['reason'] ?? '', ENT_QUOTES); ?>">
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

<div class="modal fade" id="addReturnItemModal" tabindex="-1" aria-labelledby="addReturnItemModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addReturnItemModalLabel">Add Return Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_return_item">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Return *</label>
                                    <select class="form-select" name="return_id" required>
                                          <?php if ($returnId > 0): ?>
                                                <option value="<?php echo (int)$returnId; ?>" selected>Return #<?php echo (int)$returnId; ?></option>
                                          <?php else: ?>
                                                <option value="">— Select Return —</option>
                                                <?php foreach ($returns as $ret): ?>
                                                      <option value="<?php echo (int)$ret['id']; ?>"><?php echo 'RMA #' . (int)$ret['id'] . ' — ' . htmlspecialchars($ret['rma_no'] ?? '', ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          <?php endif; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Order Item *</label>
                                    <select class="form-select" name="order_item_id" required>
                                          <option value="">— Select Order Item —</option>
                                          <?php foreach ($orderItems as $oi): ?>
                                                <option value="<?php echo (int)$oi['id']; ?>"><?php echo 'OI #' . (int)$oi['id'] . ' — ' . htmlspecialchars(($oi['product_title'] ?? '') . ' — ' . ($oi['sku'] ?? ''), ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="row">
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Qty</label>
                                          <input type="number" step="1" min="1" name="qty" class="form-control" value="1">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Resolution</label>
                                          <select name="resolution" class="form-select">
                                                <option value="refund">refund</option>
                                                <option value="exchange">exchange</option>
                                          </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                          <label class="form-label">Reason</label>
                                          <input type="text" name="reason" class="form-control" placeholder="optional">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-gradient-primary">Add</button>
                        </div>
                  </form>
            </div>
      </div>
</div>