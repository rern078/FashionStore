<?php
require_once __DIR__ . '/../config/function.php';

$orderId = (int)($_GET['order_id'] ?? 0);
$orders = db_all('SELECT id, order_number FROM orders ORDER BY id DESC');
$variants = db_all('SELECT v.id, v.sku, p.title AS product_title FROM variants v JOIN products p ON p.id=v.product_id ORDER BY v.id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_order_item') {
      $order = (int)($_POST['order_id'] ?? 0);
      $variant = (int)($_POST['variant_id'] ?? 0);
      $qty = max(1, (int)($_POST['qty'] ?? 1));
      $unitPrice = isset($_POST['unit_price']) ? (float)$_POST['unit_price'] : 0.0;
      $discountAmount = isset($_POST['discount_amount']) ? (float)$_POST['discount_amount'] : 0.0;
      $taxAmount = isset($_POST['tax_amount']) ? (float)$_POST['tax_amount'] : 0.0;
      if ($order <= 0 || $variant <= 0 || $unitPrice < 0) {
            header('Location: /admin/?p=order_items&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO order_items (order_id, variant_id, qty, unit_price, discount_amount, tax_amount) VALUES (?, ?, ?, ?, ?, ?)', [$order, $variant, $qty, $unitPrice, $discountAmount, $taxAmount]);
      header('Location: /admin/?p=order_items&order_id=' . $order . '&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_order_item') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      $variant = (int)($_POST['variant_id'] ?? 0);
      $qty = max(1, (int)($_POST['qty'] ?? 1));
      $unitPrice = isset($_POST['unit_price']) ? (float)$_POST['unit_price'] : 0.0;
      $discountAmount = isset($_POST['discount_amount']) ? (float)$_POST['discount_amount'] : 0.0;
      $taxAmount = isset($_POST['tax_amount']) ? (float)$_POST['tax_amount'] : 0.0;
      if ($id <= 0 || $order <= 0 || $variant <= 0) {
            header('Location: /admin/?p=order_items&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE order_items SET order_id=?, variant_id=?, qty=?, unit_price=?, discount_amount=?, tax_amount=? WHERE id=?', [$order, $variant, $qty, $unitPrice, $discountAmount, $taxAmount, $id]);
      header('Location: /admin/?p=order_items&order_id=' . $order . '&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_order_item') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM order_items WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=order_items&order_id=' . $order . '&deleted=1');
      exit;
}

// List
if ($orderId > 0) {
      $items = db_all('SELECT oi.id, oi.order_id, oi.qty, oi.unit_price, oi.discount_amount, oi.tax_amount, v.sku, p.title AS product_title FROM order_items oi JOIN variants v ON v.id=oi.variant_id JOIN products p ON p.id=v.product_id WHERE oi.order_id=? ORDER BY oi.id DESC', [$orderId]);
} else {
      $items = db_all('SELECT oi.id, oi.order_id, oi.qty, oi.unit_price, oi.discount_amount, oi.tax_amount, v.sku, p.title AS product_title FROM order_items oi JOIN variants v ON v.id=oi.variant_id JOIN products p ON p.id=v.product_id ORDER BY oi.id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Order Items </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=orders">Orders</a></li>
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
                              <h4 class="card-title mb-0">Items<?php echo $orderId ? (' for Order #' . (int)$orderId) : ''; ?></h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addOrderItemModal">Add Item</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="order-items-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <?php if (!$orderId): ?><th>Order</th><?php endif; ?>
                                                <th>Product</th>
                                                <th>Variant SKU</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Discount</th>
                                                <th>Tax</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($items as $it): ?>
                                                <tr>
                                                      <td><?php echo (int)$it['id']; ?></td>
                                                      <?php if (!$orderId): ?><td><?php echo (int)$it['order_id']; ?></td><?php endif; ?>
                                                      <td><?php echo htmlspecialchars($it['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($it['sku'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$it['qty']; ?></td>
                                                      <td><?php echo number_format((float)$it['unit_price'], 2); ?></td>
                                                      <td><?php echo number_format((float)($it['discount_amount'] ?? 0), 2); ?></td>
                                                      <td><?php echo number_format((float)($it['tax_amount'] ?? 0), 2); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOrderItemModal-<?php echo (int)$it['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this item?');">
                                                                  <input type="hidden" name="form" value="delete_order_item">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$it['id']; ?>">
                                                                  <input type="hidden" name="order_id" value="<?php echo (int)$orderId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editOrderItemModal-<?php echo (int)$it['id']; ?>" tabindex="-1" aria-labelledby="editOrderItemModalLabel-<?php echo (int)$it['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editOrderItemModalLabel-<?php echo (int)$it['id']; ?>">Edit Item</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_order_item">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$it['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Order *</label>
                                                                                    <select class="form-select" name="order_id" required>
                                                                                          <?php foreach ($orders as $o): ?>
                                                                                                <option value="<?php echo (int)$o['id']; ?>" <?php echo ((int)($it['order_id'] ?? 0) === (int)$o['id']) ? 'selected' : ''; ?>><?php echo 'Order #' . (int)$o['id'] . ' — ' . htmlspecialchars($o['order_number'] ?? '', ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Variant *</label>
                                                                                    <select class="form-select" name="variant_id" required>
                                                                                          <?php foreach ($variants as $v): ?>
                                                                                                <option value="<?php echo (int)$v['id']; ?>"><?php echo htmlspecialchars(($v['product_title'] ?? '') . ' — ' . ($v['sku'] ?? ''), ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Qty</label>
                                                                                          <input type="number" step="1" min="1" name="qty" class="form-control" value="<?php echo (int)$it['qty']; ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Unit Price</label>
                                                                                          <input type="number" step="0.01" min="0" name="unit_price" class="form-control" value="<?php echo htmlspecialchars((string)$it['unit_price'], ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Discount</label>
                                                                                          <input type="number" step="0.01" min="0" name="discount_amount" class="form-control" value="<?php echo htmlspecialchars((string)($it['discount_amount'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Tax</label>
                                                                                          <input type="number" step="0.01" min="0" name="tax_amount" class="form-control" value="<?php echo htmlspecialchars((string)($it['tax_amount'] ?? '0'), ENT_QUOTES); ?>">
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

<div class="modal fade" id="addOrderItemModal" tabindex="-1" aria-labelledby="addOrderItemModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addOrderItemModalLabel">Add Order Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_order_item">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Order *</label>
                                    <select class="form-select" name="order_id" required>
                                          <?php if ($orderId > 0): ?>
                                                <option value="<?php echo (int)$orderId; ?>" selected>Order #<?php echo (int)$orderId; ?></option>
                                          <?php else: ?>
                                                <option value="">— Select Order —</option>
                                                <?php foreach ($orders as $o): ?>
                                                      <option value="<?php echo (int)$o['id']; ?>"><?php echo 'Order #' . (int)$o['id'] . ' — ' . htmlspecialchars($o['order_number'] ?? '', ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          <?php endif; ?>
                                    </select>
                              </div>
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
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Qty</label>
                                          <input type="number" step="1" min="1" name="qty" class="form-control" value="1">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Unit Price</label>
                                          <input type="number" step="0.01" min="0" name="unit_price" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Discount</label>
                                          <input type="number" step="0.01" min="0" name="discount_amount" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Tax</label>
                                          <input type="number" step="0.01" min="0" name="tax_amount" class="form-control" value="0.00">
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