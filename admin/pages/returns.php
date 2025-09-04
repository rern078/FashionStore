<?php
require_once __DIR__ . '/../config/function.php';

$orderId = (int)($_GET['order_id'] ?? 0);
$orders = db_all('SELECT id, order_number FROM orders ORDER BY id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_return') {
      $order = (int)($_POST['order_id'] ?? 0);
      $rma = trim($_POST['rma_no'] ?? '');
      $status = $_POST['status'] ?? 'requested';
      $refund = trim($_POST['refund_amount'] ?? '');
      if ($order <= 0 || $rma === '') {
            header('Location: /admin/?p=returns&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      $exists = db_one('SELECT id FROM returns WHERE rma_no=?', [$rma]);
      if ($exists) {
            header('Location: /admin/?p=returns&order_id=' . $order . '&error=RMA%20exists');
            exit;
      }
      db_exec('INSERT INTO returns (order_id, rma_no, status, refund_amount) VALUES (?, ?, ?, ?)', [$order, substr($rma, 0, 40), $status, $refund !== '' ? (float)$refund : null]);
      header('Location: /admin/?p=returns&order_id=' . $order . '&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_return') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      $rma = trim($_POST['rma_no'] ?? '');
      $status = $_POST['status'] ?? 'requested';
      $refund = trim($_POST['refund_amount'] ?? '');
      if ($id <= 0 || $order <= 0 || $rma === '') {
            header('Location: /admin/?p=returns&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      $exists = db_one('SELECT id FROM returns WHERE rma_no=? AND id <> ?', [$rma, $id]);
      if ($exists) {
            header('Location: /admin/?p=returns&order_id=' . $order . '&error=RMA%20exists');
            exit;
      }
      db_exec('UPDATE returns SET order_id=?, rma_no=?, status=?, refund_amount=? WHERE id=?', [$order, substr($rma, 0, 40), $status, $refund !== '' ? (float)$refund : null, $id]);
      header('Location: /admin/?p=returns&order_id=' . $order . '&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_return') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM returns WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=returns&order_id=' . $order . '&deleted=1');
      exit;
}

// List
if ($orderId > 0) {
      $rows = db_all('SELECT id, order_id, rma_no, status, refund_amount, created_at FROM returns WHERE order_id=? ORDER BY id DESC', [$orderId]);
} else {
      $rows = db_all('SELECT id, order_id, rma_no, status, refund_amount, created_at FROM returns ORDER BY id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Returns </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=orders">Orders</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Returns</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Return created.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Return updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Return deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Returns</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addReturnModal">Add Return</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="returns-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Order</th>
                                                <th>RMA</th>
                                                <th>Status</th>
                                                <th>Refund</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo (int)$r['order_id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['rma_no'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['status'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo $r['refund_amount'] !== null ? number_format((float)$r['refund_amount'], 2) : '—'; ?></td>
                                                      <td><?php echo htmlspecialchars($r['created_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editReturnModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this return?');">
                                                                  <input type="hidden" name="form" value="delete_return">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <input type="hidden" name="order_id" value="<?php echo (int)$orderId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editReturnModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editReturnModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editReturnModalLabel-<?php echo (int)$r['id']; ?>">Edit Return</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_return">
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
                                                                                    <label class="form-label">RMA *</label>
                                                                                    <input type="text" name="rma_no" class="form-control" maxlength="40" required value="<?php echo htmlspecialchars($r['rma_no'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Status</label>
                                                                                    <select name="status" class="form-select">
                                                                                          <option value="requested" <?php echo ($r['status'] === 'requested') ? 'selected' : ''; ?>>requested</option>
                                                                                          <option value="approved" <?php echo ($r['status'] === 'approved') ? 'selected' : ''; ?>>approved</option>
                                                                                          <option value="rejected" <?php echo ($r['status'] === 'rejected') ? 'selected' : ''; ?>>rejected</option>
                                                                                          <option value="received" <?php echo ($r['status'] === 'received') ? 'selected' : ''; ?>>received</option>
                                                                                          <option value="refunded" <?php echo ($r['status'] === 'refunded') ? 'selected' : ''; ?>>refunded</option>
                                                                                          <option value="closed" <?php echo ($r['status'] === 'closed') ? 'selected' : ''; ?>>closed</option>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Refund Amount</label>
                                                                                    <input type="number" step="0.01" min="0" name="refund_amount" class="form-control" value="<?php echo htmlspecialchars($r['refund_amount'] !== null ? (string)$r['refund_amount'] : '', ENT_QUOTES); ?>">
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

<div class="modal fade" id="addReturnModal" tabindex="-1" aria-labelledby="addReturnModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addReturnModalLabel">Add Return</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_return">
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
                                    <label class="form-label">RMA *</label>
                                    <input type="text" name="rma_no" class="form-control" maxlength="40" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                          <option value="requested">requested</option>
                                          <option value="approved">approved</option>
                                          <option value="rejected">rejected</option>
                                          <option value="received">received</option>
                                          <option value="refunded">refunded</option>
                                          <option value="closed">closed</option>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Refund Amount</label>
                                    <input type="number" step="0.01" min="0" name="refund_amount" class="form-control" placeholder="optional">
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