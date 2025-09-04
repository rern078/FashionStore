<?php
require_once __DIR__ . '/../config/function.php';

$orderId = (int)($_GET['order_id'] ?? 0);
$orders = db_all('SELECT id, order_number FROM orders ORDER BY id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_payment') {
      $order = (int)($_POST['order_id'] ?? 0);
      $provider = trim($_POST['provider'] ?? '');
      $providerTxn = trim($_POST['provider_txn_id'] ?? '');
      $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
      $status = $_POST['status'] ?? 'pending';
      $capturedAt = trim($_POST['captured_at'] ?? '');
      if ($order <= 0 || $provider === '' || $amount <= 0) {
            header('Location: /admin/?p=payments&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO payments (order_id, provider, provider_txn_id, amount, status, captured_at) VALUES (?, ?, ?, ?, ?, ?)', [$order, substr($provider, 0, 50), $providerTxn !== '' ? substr($providerTxn, 0, 100) : null, $amount, $status, $capturedAt !== '' ? $capturedAt : null]);
      header('Location: /admin/?p=payments&order_id=' . $order . '&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_payment') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      $provider = trim($_POST['provider'] ?? '');
      $providerTxn = trim($_POST['provider_txn_id'] ?? '');
      $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
      $status = $_POST['status'] ?? 'pending';
      $capturedAt = trim($_POST['captured_at'] ?? '');
      if ($id <= 0 || $order <= 0 || $provider === '' || $amount <= 0) {
            header('Location: /admin/?p=payments&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE payments SET order_id=?, provider=?, provider_txn_id=?, amount=?, status=?, captured_at=? WHERE id=?', [$order, substr($provider, 0, 50), $providerTxn !== '' ? substr($providerTxn, 0, 100) : null, $amount, $status, $capturedAt !== '' ? $capturedAt : null, $id]);
      header('Location: /admin/?p=payments&order_id=' . $order . '&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_payment') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM payments WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=payments&order_id=' . $order . '&deleted=1');
      exit;
}

// List
if ($orderId > 0) {
      $rows = db_all('SELECT id, order_id, provider, provider_txn_id, amount, status, captured_at FROM payments WHERE order_id=? ORDER BY id DESC', [$orderId]);
} else {
      $rows = db_all('SELECT id, order_id, provider, provider_txn_id, amount, status, captured_at FROM payments ORDER BY id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Payments </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=orders">Orders</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Payments</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Payment added.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Payment updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Payment deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Payments</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="payments-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Order</th>
                                                <th>Provider</th>
                                                <th>Txn ID</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Captured</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo (int)$r['order_id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['provider'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['provider_txn_id'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)$r['amount'], 2); ?></td>
                                                      <td><?php echo htmlspecialchars($r['status'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['captured_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this payment?');">
                                                                  <input type="hidden" name="form" value="delete_payment">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <input type="hidden" name="order_id" value="<?php echo (int)$orderId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editPaymentModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editPaymentModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editPaymentModalLabel-<?php echo (int)$r['id']; ?>">Edit Payment</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_payment">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Order *</label>
                                                                                    <select class="form-select" name="order_id" required>
                                                                                          <?php foreach ($orders as $o): ?>
                                                                                                <option value="<?php echo (int)$o['id']; ?>" <?php echo ((int)$r['order_id'] === (int)$o['id']) ? 'selected' : ''; ?>><?php echo 'Order #' . (int)$o['id'] . ' — ' . htmlspecialchars($o['order_number'] ?? '', ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Provider *</label>
                                                                                    <input type="text" name="provider" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($r['provider'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Txn ID</label>
                                                                                    <input type="text" name="provider_txn_id" class="form-control" maxlength="100" value="<?php echo htmlspecialchars($r['provider_txn_id'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Amount *</label>
                                                                                          <input type="number" step="0.01" min="0" name="amount" class="form-control" value="<?php echo htmlspecialchars((string)$r['amount'], ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Status</label>
                                                                                          <select name="status" class="form-select">
                                                                                                <option value="pending" <?php echo ($r['status'] === 'pending') ? 'selected' : ''; ?>>pending</option>
                                                                                                <option value="authorized" <?php echo ($r['status'] === 'authorized') ? 'selected' : ''; ?>>authorized</option>
                                                                                                <option value="captured" <?php echo ($r['status'] === 'captured') ? 'selected' : ''; ?>>captured</option>
                                                                                                <option value="refunded" <?php echo ($r['status'] === 'refunded') ? 'selected' : ''; ?>>refunded</option>
                                                                                                <option value="failed" <?php echo ($r['status'] === 'failed') ? 'selected' : ''; ?>>failed</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Captured at</label>
                                                                                          <input type="datetime-local" name="captured_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($r['captured_at'] ?? '')), ENT_QUOTES); ?>">
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

<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_payment">
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
                                    <label class="form-label">Provider *</label>
                                    <input type="text" name="provider" class="form-control" maxlength="50" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Txn ID</label>
                                    <input type="text" name="provider_txn_id" class="form-control" maxlength="100">
                              </div>
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Amount *</label>
                                          <input type="number" step="0.01" min="0" name="amount" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Status</label>
                                          <select name="status" class="form-select">
                                                <option value="pending">pending</option>
                                                <option value="authorized">authorized</option>
                                                <option value="captured">captured</option>
                                                <option value="refunded">refunded</option>
                                                <option value="failed">failed</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Captured at</label>
                                          <input type="datetime-local" name="captured_at" class="form-control">
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