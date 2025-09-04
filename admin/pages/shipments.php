<?php
require_once __DIR__ . '/../config/function.php';

$orderId = (int)($_GET['order_id'] ?? 0);
$orders = db_all('SELECT id, order_number FROM orders ORDER BY id DESC');
$addresses = db_all('SELECT id, user_id, line1, city, country FROM addresses ORDER BY id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_shipment') {
      $order = (int)($_POST['order_id'] ?? 0);
      $carrier = trim($_POST['carrier'] ?? '');
      $service = trim($_POST['service'] ?? '');
      $tracking = trim($_POST['tracking_no'] ?? '');
      $shippedAt = trim($_POST['shipped_at'] ?? '');
      $deliveredAt = trim($_POST['delivered_at'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      $addressId = trim($_POST['address_id'] ?? '');
      if ($order <= 0) {
            header('Location: /admin/?p=shipments&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO shipments (order_id, carrier, service, tracking_no, shipped_at, delivered_at, status, address_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            $order,
            $carrier !== '' ? substr($carrier, 0, 80) : null,
            $service !== '' ? substr($service, 0, 80) : null,
            $tracking !== '' ? substr($tracking, 0, 120) : null,
            $shippedAt !== '' ? $shippedAt : null,
            $deliveredAt !== '' ? $deliveredAt : null,
            $status,
            $addressId !== '' ? (int)$addressId : null
      ]);
      header('Location: /admin/?p=shipments&order_id=' . $order . '&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_shipment') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      $carrier = trim($_POST['carrier'] ?? '');
      $service = trim($_POST['service'] ?? '');
      $tracking = trim($_POST['tracking_no'] ?? '');
      $shippedAt = trim($_POST['shipped_at'] ?? '');
      $deliveredAt = trim($_POST['delivered_at'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      $addressId = trim($_POST['address_id'] ?? '');
      if ($id <= 0 || $order <= 0) {
            header('Location: /admin/?p=shipments&order_id=' . $order . '&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE shipments SET order_id=?, carrier=?, service=?, tracking_no=?, shipped_at=?, delivered_at=?, status=?, address_id=? WHERE id=?', [
            $order,
            $carrier !== '' ? substr($carrier, 0, 80) : null,
            $service !== '' ? substr($service, 0, 80) : null,
            $tracking !== '' ? substr($tracking, 0, 120) : null,
            $shippedAt !== '' ? $shippedAt : null,
            $deliveredAt !== '' ? $deliveredAt : null,
            $status,
            $addressId !== '' ? (int)$addressId : null,
            $id
      ]);
      header('Location: /admin/?p=shipments&order_id=' . $order . '&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_shipment') {
      $id = (int)($_POST['id'] ?? 0);
      $order = (int)($_POST['order_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM shipments WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=shipments&order_id=' . $order . '&deleted=1');
      exit;
}

// List
if ($orderId > 0) {
      $rows = db_all('SELECT s.id, s.order_id, s.carrier, s.service, s.tracking_no, s.shipped_at, s.delivered_at, s.status, s.address_id FROM shipments s WHERE s.order_id=? ORDER BY s.id DESC', [$orderId]);
} else {
      $rows = db_all('SELECT s.id, s.order_id, s.carrier, s.service, s.tracking_no, s.shipped_at, s.delivered_at, s.status, s.address_id FROM shipments s ORDER BY s.id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Shipments </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=orders">Orders</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Shipments</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Shipment added.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Shipment updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Shipment deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Shipments</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addShipmentModal">Add Shipment</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="shipments-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Order</th>
                                                <th>Carrier</th>
                                                <th>Service</th>
                                                <th>Tracking</th>
                                                <th>Status</th>
                                                <th>Shipped</th>
                                                <th>Delivered</th>
                                                <th>Address</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo (int)$r['order_id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['carrier'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['service'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['tracking_no'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['status'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['shipped_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['delivered_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo $r['address_id'] ? (int)$r['address_id'] : '—'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editShipmentModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this shipment?');">
                                                                  <input type="hidden" name="form" value="delete_shipment">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <input type="hidden" name="order_id" value="<?php echo (int)$orderId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editShipmentModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editShipmentModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editShipmentModalLabel-<?php echo (int)$r['id']; ?>">Edit Shipment</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_shipment">
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
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Carrier</label>
                                                                                          <input type="text" name="carrier" class="form-control" value="<?php echo htmlspecialchars($r['carrier'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Service</label>
                                                                                          <input type="text" name="service" class="form-control" value="<?php echo htmlspecialchars($r['service'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Tracking #</label>
                                                                                          <input type="text" name="tracking_no" class="form-control" value="<?php echo htmlspecialchars($r['tracking_no'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Status</label>
                                                                                          <select name="status" class="form-select">
                                                                                                <option value="pending" <?php echo ($r['status'] === 'pending') ? 'selected' : ''; ?>>pending</option>
                                                                                                <option value="shipped" <?php echo ($r['status'] === 'shipped') ? 'selected' : ''; ?>>shipped</option>
                                                                                                <option value="delivered" <?php echo ($r['status'] === 'delivered') ? 'selected' : ''; ?>>delivered</option>
                                                                                                <option value="returned" <?php echo ($r['status'] === 'returned') ? 'selected' : ''; ?>>returned</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Shipped at</label>
                                                                                          <input type="datetime-local" name="shipped_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($r['shipped_at'] ?? '')), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Delivered at</label>
                                                                                          <input type="datetime-local" name="delivered_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($r['delivered_at'] ?? '')), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-12 mb-3">
                                                                                          <label class="form-label">Address</label>
                                                                                          <select name="address_id" class="form-select">
                                                                                                <option value="">— None —</option>
                                                                                                <?php foreach ($addresses as $a): ?>
                                                                                                      <option value="<?php echo (int)$a['id']; ?>" <?php echo ((int)($r['address_id'] ?? 0) === (int)$a['id']) ? 'selected' : ''; ?>><?php echo 'Addr #' . (int)$a['id'] . ' — ' . htmlspecialchars($a['line1'] . ', ' . $a['city'] . ', ' . $a['country'], ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
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

<div class="modal fade" id="addShipmentModal" tabindex="-1" aria-labelledby="addShipmentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addShipmentModalLabel">Add Shipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_shipment">
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
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Carrier</label>
                                          <input type="text" name="carrier" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Service</label>
                                          <input type="text" name="service" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Tracking #</label>
                                          <input type="text" name="tracking_no" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Status</label>
                                          <select name="status" class="form-select">
                                                <option value="pending">pending</option>
                                                <option value="shipped">shipped</option>
                                                <option value="delivered">delivered</option>
                                                <option value="returned">returned</option>
                                          </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Shipped at</label>
                                          <input type="datetime-local" name="shipped_at" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Delivered at</label>
                                          <input type="datetime-local" name="delivered_at" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3">
                                          <label class="form-label">Address</label>
                                          <select name="address_id" class="form-select">
                                                <option value="">— None —</option>
                                                <?php foreach ($addresses as $a): ?>
                                                      <option value="<?php echo (int)$a['id']; ?>"><?php echo 'Addr #' . (int)$a['id'] . ' — ' . htmlspecialchars($a['line1'] . ', ' . $a['city'] . ', ' . $a['country'], ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          </select>
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