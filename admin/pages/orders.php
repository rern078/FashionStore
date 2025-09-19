<?php
require_once __DIR__ . '/../config/function.php';

$users = db_all('SELECT id, name, email FROM users ORDER BY id DESC');

// Add order
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_order') {
      $userId = (int)($_POST['user_id'] ?? 0);
      $orderNo = trim($_POST['order_number'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      $currency = strtoupper(trim($_POST['currency'] ?? 'USD'));
      $placedAt = trim($_POST['placed_at'] ?? '');
      $subtotal = isset($_POST['subtotal']) ? (float)$_POST['subtotal'] : 0.0;
      $discount = isset($_POST['discount_total']) ? (float)$_POST['discount_total'] : 0.0;
      $shipping = isset($_POST['shipping_total']) ? (float)$_POST['shipping_total'] : 0.0;
      $tax = isset($_POST['tax_total']) ? (float)$_POST['tax_total'] : 0.0;
      $grand = isset($_POST['grand_total']) ? (float)$_POST['grand_total'] : 0.0;
      $paymentStatus = $_POST['payment_status'] ?? 'unpaid';

      if ($userId <= 0 || $orderNo === '') {
            header('Location: /admin/?p=orders&error=Missing%20required%20fields');
            exit;
      }

      $exists = db_one('SELECT id FROM orders WHERE order_number = ?', [$orderNo]);
      if ($exists) {
            header('Location: /admin/?p=orders&error=Order%20number%20exists');
            exit;
      }

      db_exec('INSERT INTO orders (user_id, order_number, status, subtotal, discount_total, shipping_total, tax_total, grand_total, currency, placed_at, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            substr($orderNo, 0, 30),
            $status,
            $subtotal,
            $discount,
            $shipping,
            $tax,
            $grand,
            substr($currency, 0, 3),
            $placedAt !== '' ? $placedAt : null,
            $paymentStatus
      ]);

      header('Location: /admin/?p=orders&added=1');
      exit;
}

// Delete order
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_order') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM orders WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=orders&deleted=1');
      exit;
}

// Update order
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_order') {
      $id = (int)$_POST['id'];
      $userId = (int)($_POST['user_id'] ?? 0);
      $orderNo = trim($_POST['order_number'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      $currency = strtoupper(trim($_POST['currency'] ?? 'USD'));
      $placedAt = trim($_POST['placed_at'] ?? '');
      $subtotal = isset($_POST['subtotal']) ? (float)$_POST['subtotal'] : 0.0;
      $discount = isset($_POST['discount_total']) ? (float)$_POST['discount_total'] : 0.0;
      $shipping = isset($_POST['shipping_total']) ? (float)$_POST['shipping_total'] : 0.0;
      $tax = isset($_POST['tax_total']) ? (float)$_POST['tax_total'] : 0.0;
      $grand = isset($_POST['grand_total']) ? (float)$_POST['grand_total'] : 0.0;
      $paymentStatus = $_POST['payment_status'] ?? 'unpaid';

      if ($id <= 0 || $userId <= 0 || $orderNo === '') {
            header('Location: /admin/?p=orders&error=Invalid%20data');
            exit;
      }

      $exists = db_one('SELECT id FROM orders WHERE order_number=? AND id <> ?', [$orderNo, $id]);
      if ($exists) {
            header('Location: /admin/?p=orders&error=Order%20number%20exists');
            exit;
      }

      db_exec('UPDATE orders SET user_id=?, order_number=?, status=?, subtotal=?, discount_total=?, shipping_total=?, tax_total=?, grand_total=?, currency=?, placed_at=?, payment_status=? WHERE id=?', [
            $userId,
            substr($orderNo, 0, 30),
            $status,
            $subtotal,
            $discount,
            $shipping,
            $tax,
            $grand,
            substr($currency, 0, 3),
            $placedAt !== '' ? $placedAt : null,
            $paymentStatus,
            $id
      ]);
      header('Location: /admin/?p=orders&updated=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM orders');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all(
      'SELECT 
            o.id, o.order_number, o.status, o.subtotal, o.discount_total, o.shipping_total, o.tax_total, o.grand_total, o.currency, o.placed_at, o.payment_status,
            u.name, u.email, u.id AS user_id,
            (SELECT SUM(p.amount) FROM payments p WHERE p.order_id = o.id AND p.status = "captured") AS captured_total
       FROM orders o 
       JOIN users u ON u.id=o.user_id 
       ORDER BY o.id DESC LIMIT ? OFFSET ?',
      [$perPage, $offset]
);
?>

<div class="page-header">
      <h3 class="page-title"> Orders </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Order created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Order updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Order deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Orders</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="orders-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Order #</th>
                                                <th>User</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Captured</th>
                                                <th>Balance</th>
                                                <th>Currency</th>
                                                <th>Placed</th>
                                                <th>Payment</th>
                                                <th>Links</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['order_number'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars(($r['name'] ?? '') . ' (' . ($r['email'] ?? '') . ')', ENT_QUOTES); ?></td>
                                                      <td><label class="badge badge-<?php echo $r['status'] === 'paid' || $r['status'] === 'fulfilled' ? 'success' : ($r['status'] === 'cancelled' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($r['status'], ENT_QUOTES); ?></label></td>
                                                      <td><?php echo number_format((float)($r['grand_total'] ?? 0), 2); ?></td>
                                                      <td><?php echo number_format((float)($r['captured_total'] ?? 0), 2); ?></td>
                                                      <td><?php $bal = (float)($r['grand_total'] ?? 0) - (float)($r['captured_total'] ?? 0);
                                                            echo number_format($bal, 2); ?></td>
                                                      <td><?php echo htmlspecialchars($r['currency'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['placed_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['payment_status'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <a class="btn btn-sm btn-outline-secondary" href="/admin/?p=order_items&order_id=<?php echo (int)$r['id']; ?>">Items</a>
                                                            <a class="btn btn-sm btn-outline-secondary" href="/admin/?p=payments&order_id=<?php echo (int)$r['id']; ?>">Payments</a>
                                                            <a class="btn btn-sm btn-outline-secondary" href="/admin/?p=shipments&order_id=<?php echo (int)$r['id']; ?>">Shipments</a>
                                                            <a class="btn btn-sm btn-outline-secondary" href="/admin/?p=returns&order_id=<?php echo (int)$r['id']; ?>">Returns</a>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this order?');">
                                                                  <input type="hidden" name="form" value="delete_order">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editOrderModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editOrderModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editOrderModalLabel-<?php echo (int)$r['id']; ?>">Edit Order</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_order">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="row">
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">User *</label>
                                                                                          <select name="user_id" class="form-select" required>
                                                                                                <?php foreach ($users as $u): ?>
                                                                                                      <option value="<?php echo (int)$u['id']; ?>" <?php echo ((int)$r['user_id'] === (int)$u['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Order # *</label>
                                                                                          <input type="text" name="order_number" class="form-control" maxlength="30" required value="<?php echo htmlspecialchars($r['order_number'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Status</label>
                                                                                          <select name="status" class="form-select">
                                                                                                <option value="pending" <?php echo ($r['status'] === 'pending') ? 'selected' : ''; ?>>pending</option>
                                                                                                <option value="paid" <?php echo ($r['status'] === 'paid') ? 'selected' : ''; ?>>paid</option>
                                                                                                <option value="fulfilled" <?php echo ($r['status'] === 'fulfilled') ? 'selected' : ''; ?>>fulfilled</option>
                                                                                                <option value="cancelled" <?php echo ($r['status'] === 'cancelled') ? 'selected' : ''; ?>>cancelled</option>
                                                                                                <option value="refunded" <?php echo ($r['status'] === 'refunded') ? 'selected' : ''; ?>>refunded</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Subtotal</label>
                                                                                          <input type="number" step="0.01" min="0" name="subtotal" class="form-control" value="<?php echo htmlspecialchars((string)($r['subtotal'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Discount</label>
                                                                                          <input type="number" step="0.01" min="0" name="discount_total" class="form-control" value="<?php echo htmlspecialchars((string)($r['discount_total'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Shipping</label>
                                                                                          <input type="number" step="0.01" min="0" name="shipping_total" class="form-control" value="<?php echo htmlspecialchars((string)($r['shipping_total'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-3 mb-3">
                                                                                          <label class="form-label">Tax</label>
                                                                                          <input type="number" step="0.01" min="0" name="tax_total" class="form-control" value="<?php echo htmlspecialchars((string)($r['tax_total'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Grand Total</label>
                                                                                          <input type="number" step="0.01" min="0" name="grand_total" class="form-control" value="<?php echo htmlspecialchars((string)($r['grand_total'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Currency</label>
                                                                                          <input type="text" name="currency" class="form-control" maxlength="3" value="<?php echo htmlspecialchars($r['currency'] ?? 'USD', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Placed at</label>
                                                                                          <input type="datetime-local" name="placed_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($r['placed_at'] ?? '')), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Payment Status</label>
                                                                                          <select name="payment_status" class="form-select">
                                                                                                <option value="unpaid" <?php echo ($r['payment_status'] === 'unpaid') ? 'selected' : ''; ?>>unpaid</option>
                                                                                                <option value="authorized" <?php echo ($r['payment_status'] === 'authorized') ? 'selected' : ''; ?>>authorized</option>
                                                                                                <option value="captured" <?php echo ($r['payment_status'] === 'captured') ? 'selected' : ''; ?>>captured</option>
                                                                                                <option value="refunded" <?php echo ($r['payment_status'] === 'refunded') ? 'selected' : ''; ?>>refunded</option>
                                                                                                <option value="failed" <?php echo ($r['payment_status'] === 'failed') ? 'selected' : ''; ?>>failed</option>
                                                                                          </select>
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
      <nav aria-label="Orders pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=orders&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=orders&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=orders&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">Add Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_order">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">User *</label>
                                          <select name="user_id" class="form-select" required>
                                                <option value="">— Select User —</option>
                                                <?php foreach ($users as $u): ?>
                                                      <option value="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Order # *</label>
                                          <input type="text" name="order_number" class="form-control" maxlength="30" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Status</label>
                                          <select name="status" class="form-select">
                                                <option value="pending" selected>pending</option>
                                                <option value="paid">paid</option>
                                                <option value="fulfilled">fulfilled</option>
                                                <option value="cancelled">cancelled</option>
                                                <option value="refunded">refunded</option>
                                          </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Subtotal</label>
                                          <input type="number" step="0.01" min="0" name="subtotal" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Discount</label>
                                          <input type="number" step="0.01" min="0" name="discount_total" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Shipping</label>
                                          <input type="number" step="0.01" min="0" name="shipping_total" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                          <label class="form-label">Tax</label>
                                          <input type="number" step="0.01" min="0" name="tax_total" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Grand Total</label>
                                          <input type="number" step="0.01" min="0" name="grand_total" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Currency</label>
                                          <input type="text" name="currency" class="form-control" maxlength="3" value="USD">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Placed at</label>
                                          <input type="datetime-local" name="placed_at" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Payment Status</label>
                                          <select name="payment_status" class="form-select">
                                                <option value="unpaid" selected>unpaid</option>
                                                <option value="authorized">authorized</option>
                                                <option value="captured">captured</option>
                                                <option value="refunded">refunded</option>
                                                <option value="failed">failed</option>
                                          </select>
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