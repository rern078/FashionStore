<?php
require_once __DIR__ . '/../config/function.php';

// Load users for selector
$users = db_all('SELECT id, name, email FROM users ORDER BY id DESC');

// Create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_address') {
      $userId = (int)($_POST['user_id'] ?? 0);
      $line1 = trim($_POST['line1'] ?? '');
      $line2 = trim($_POST['line2'] ?? '');
      $city = trim($_POST['city'] ?? '');
      $state = trim($_POST['state'] ?? '');
      $postal = trim($_POST['postal'] ?? '');
      $country = strtoupper(trim($_POST['country'] ?? ''));
      $isDefault = isset($_POST['is_default']) ? 1 : 0;
      $businessHours = trim($_POST['business_hours'] ?? '');

      if ($userId <= 0 || $line1 === '' || $city === '' || $postal === '' || $country === '') {
            header('Location: /admin/?p=addresses&error=Missing%20required%20fields');
            exit;
      }

      if ($isDefault === 1) {
            db_exec('UPDATE addresses SET is_default = 0 WHERE user_id = ?', [$userId]);
      }

      db_exec(
            'INSERT INTO addresses (user_id, line1, line2, city, state, postal, country, is_default, business_hours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$userId, $line1, $line2 !== '' ? $line2 : null, $city, $state !== '' ? $state : null, $postal, substr($country, 0, 2), $isDefault, $businessHours !== '' ? $businessHours : null]
      );

      header('Location: /admin/?p=addresses&added=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_address') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM addresses WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=addresses&deleted=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_address') {
      $id = (int)($_POST['id'] ?? 0);
      $userId = (int)($_POST['user_id'] ?? 0);
      $line1 = trim($_POST['line1'] ?? '');
      $line2 = trim($_POST['line2'] ?? '');
      $city = trim($_POST['city'] ?? '');
      $state = trim($_POST['state'] ?? '');
      $postal = trim($_POST['postal'] ?? '');
      $country = strtoupper(trim($_POST['country'] ?? ''));
      $isDefault = isset($_POST['is_default']) ? 1 : 0;
      $businessHours = trim($_POST['business_hours'] ?? '');

      if ($id <= 0 || $userId <= 0 || $line1 === '' || $city === '' || $postal === '' || $country === '') {
            header('Location: /admin/?p=addresses&error=Invalid%20data');
            exit;
      }

      if ($isDefault === 1) {
            db_exec('UPDATE addresses SET is_default = 0 WHERE user_id = ? AND id <> ?', [$userId, $id]);
      }

      db_exec(
            'UPDATE addresses SET user_id=?, line1=?, line2=?, city=?, state=?, postal=?, country=?, is_default=?, business_hours=? WHERE id=?',
            [$userId, $line1, $line2 !== '' ? $line2 : null, $city, $state !== '' ? $state : null, $postal, substr($country, 0, 2), $isDefault, $businessHours !== '' ? $businessHours : null, $id]
      );

      header('Location: /admin/?p=addresses&updated=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM addresses');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all(
      'SELECT a.id, a.user_id, a.line1, a.line2, a.city, a.state, a.postal, a.country, a.is_default, a.business_hours, u.name AS user_name, u.email AS user_email
   FROM addresses a
   JOIN users u ON u.id = a.user_id
   ORDER BY a.id DESC
   LIMIT ? OFFSET ?',
      [$perPage, $offset]
);
?>

<div class="page-header">
      <h3 class="page-title"> Addresses </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Customers</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Addresses</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Address created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Address updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Address deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Addresses</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">Add Address</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="addresses-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Line 1</th>
                                                <th>Line 2</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Postal</th>
                                                <th>Country</th>
                                                <th>Default</th>
                                                <th>Business Hours</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars(($r['user_name'] ?? '') . ' (' . ($r['user_email'] ?? '') . ')', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['line1'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['line2'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['city'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['state'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['postal'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['country'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><label class="badge badge-<?php echo ((int)$r['is_default'] === 1) ? 'success' : 'secondary'; ?>"><?php echo ((int)$r['is_default'] === 1) ? 'Yes' : 'No'; ?></label></td>
                                                      <td style="white-space:pre-line; max-width:260px;"><?php echo htmlspecialchars($r['business_hours'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this address?');">
                                                                  <input type="hidden" name="form" value="delete_address">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editAddressModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editAddressModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editAddressModalLabel-<?php echo (int)$r['id']; ?>">Edit Address</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_address">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">User *</label>
                                                                                    <select class="form-select" name="user_id" required>
                                                                                          <option value="">— Select User —</option>
                                                                                          <?php foreach ($users as $u): ?>
                                                                                                <option value="<?php echo (int)$u['id']; ?>" <?php echo ((int)$r['user_id'] === (int)$u['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Line 1 *</label>
                                                                                    <input type="text" class="form-control" name="line1" required value="<?php echo htmlspecialchars($r['line1'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Line 2</label>
                                                                                    <input type="text" class="form-control" name="line2" value="<?php echo htmlspecialchars($r['line2'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">City *</label>
                                                                                          <input type="text" class="form-control" name="city" required value="<?php echo htmlspecialchars($r['city'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">State/Region</label>
                                                                                          <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($r['state'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Postal Code *</label>
                                                                                          <input type="text" class="form-control" name="postal" required value="<?php echo htmlspecialchars($r['postal'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Country (2-letter) *</label>
                                                                                          <input type="text" class="form-control" name="country" maxlength="2" required value="<?php echo htmlspecialchars($r['country'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Business Hours</label>
                                                                                    <textarea class="form-control" name="business_hours" rows="3" placeholder="e.g. Monday-Friday: 9am-6pm&#10;Saturday: 10am-4pm&#10;Sunday: Closed"><?php echo htmlspecialchars($r['business_hours'] ?? '', ENT_QUOTES); ?></textarea>
                                                                              </div>
                                                                              <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="is_default" id="editIsDefault-<?php echo (int)$r['id']; ?>" <?php echo ((int)$r['is_default'] === 1) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="editIsDefault-<?php echo (int)$r['id']; ?>">Set as default for user</label>
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
      <nav aria-label="Addresses pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=addresses&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=addresses&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=addresses&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_address">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">User *</label>
                                    <select class="form-select" name="user_id" required>
                                          <option value="">— Select User —</option>
                                          <?php foreach ($users as $u): ?>
                                                <option value="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Line 1 *</label>
                                    <input type="text" class="form-control" name="line1" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Line 2</label>
                                    <input type="text" class="form-control" name="line2">
                              </div>
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">City *</label>
                                          <input type="text" class="form-control" name="city" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">State/Region</label>
                                          <input type="text" class="form-control" name="state">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Postal Code *</label>
                                          <input type="text" class="form-control" name="postal" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Country (2-letter) *</label>
                                          <input type="text" class="form-control" name="country" maxlength="2" required>
                                    </div>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Business Hours</label>
                                    <textarea class="form-control" name="business_hours" rows="3" placeholder="e.g. Monday-Friday: 9am-6pm&#10;Saturday: 10am-4pm&#10;Sunday: Closed"></textarea>
                              </div>
                              <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="addIsDefault">
                                    <label class="form-check-label" for="addIsDefault">Set as default for user</label>
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