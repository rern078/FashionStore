<?php
require_once __DIR__ . '/../config/function.php';

// Create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_currency') {
      $code = strtoupper(trim($_POST['code'] ?? ''));
      $name = trim($_POST['name'] ?? '');
      $exchangeRate = trim($_POST['exchange_rate'] ?? '1');
      $symbol = trim($_POST['symbol'] ?? '');
      $decimalPlaces = (int)($_POST['decimal_places'] ?? 2);
      $position = $_POST['position'] === 'suffix' ? 'suffix' : 'prefix';
      $isDefault = isset($_POST['is_default']) ? 1 : 0;
      $isActive = isset($_POST['is_active']) ? 1 : 0;

      if ($code === '' || strlen($code) !== 3 || $name === '') {
            header('Location: /admin/?p=currencies&error=Invalid%20code%20or%20name');
            exit;
      }

      if (!is_numeric($exchangeRate) || (float)$exchangeRate <= 0) {
            header('Location: /admin/?p=currencies&error=Invalid%20exchange%20rate');
            exit;
      }

      // Ensure only one default
      if ($isDefault) {
            db_exec('UPDATE currencies SET is_default = 0 WHERE is_default = 1');
      }

      try {
            db_exec('INSERT INTO currencies (code, name, exchange_rate, symbol, decimal_places, position, is_default, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                  substr($code, 0, 3),
                  substr($name, 0, 100),
                  (float)$exchangeRate,
                  $symbol !== '' ? substr($symbol, 0, 10) : null,
                  max(0, min(6, $decimalPlaces)),
                  $position,
                  $isDefault,
                  $isActive,
            ]);
            header('Location: /admin/?p=currencies&added=1');
            exit;
      } catch (Throwable $e) {
            header('Location: /admin/?p=currencies&error=' . urlencode('Error: ' . $e->getMessage()));
            exit;
      }
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_currency') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM currencies WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=currencies&deleted=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_currency') {
      $id = (int)($_POST['id'] ?? 0);
      $code = strtoupper(trim($_POST['code'] ?? ''));
      $name = trim($_POST['name'] ?? '');
      $exchangeRate = trim($_POST['exchange_rate'] ?? '1');
      $symbol = trim($_POST['symbol'] ?? '');
      $decimalPlaces = (int)($_POST['decimal_places'] ?? 2);
      $position = $_POST['position'] === 'suffix' ? 'suffix' : 'prefix';
      $isDefault = isset($_POST['is_default']) ? 1 : 0;
      $isActive = isset($_POST['is_active']) ? 1 : 0;

      if ($id > 0 && $code !== '' && strlen($code) === 3 && $name !== '' && is_numeric($exchangeRate) && (float)$exchangeRate > 0) {
            if ($isDefault) {
                  db_exec('UPDATE currencies SET is_default = 0 WHERE is_default = 1 AND id <> ?', [$id]);
            }
            db_exec('UPDATE currencies SET code = ?, name = ?, exchange_rate = ?, symbol = ?, decimal_places = ?, position = ?, is_default = ?, is_active = ? WHERE id = ?', [
                  substr($code, 0, 3),
                  substr($name, 0, 100),
                  (float)$exchangeRate,
                  $symbol !== '' ? substr($symbol, 0, 10) : null,
                  max(0, min(6, $decimalPlaces)),
                  $position,
                  $isDefault,
                  $isActive,
                  $id,
            ]);
            header('Location: /admin/?p=currencies&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=currencies&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM currencies');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT id, code, name, exchange_rate, symbol, decimal_places, position, is_default, is_active FROM currencies ORDER BY is_default DESC, code ASC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Currencies </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Currencies</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Currency created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Currency updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Currency deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Currencies</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addCurrencyModal">Add Currency</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="currencies-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Rate</th>
                                                <th>Symbol</th>
                                                <th>Decimals</th>
                                                <th>Position</th>
                                                <th>Default</th>
                                                <th>Active</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['code'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)$r['exchange_rate'], 6); ?></td>
                                                      <td><?php echo htmlspecialchars($r['symbol'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['decimal_places']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['position'], ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['is_default'] === 1 ? '<span class="badge badge-success">Yes</span>' : 'No'; ?></td>
                                                      <td><?php echo (int)$r['is_active'] === 1 ? 'Yes' : 'No'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCurrencyModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this currency?');">
                                                                  <input type="hidden" name="form" value="delete_currency">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editCurrencyModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editCurrencyModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editCurrencyModalLabel-<?php echo (int)$r['id']; ?>">Edit Currency</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_currency">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Code *</label>
                                                                                    <input type="text" class="form-control" name="code" maxlength="3" required value="<?php echo htmlspecialchars($r['code'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Exchange Rate *</label>
                                                                                    <input type="number" step="0.000001" min="0.000001" class="form-control" name="exchange_rate" required value="<?php echo htmlspecialchars((string)$r['exchange_rate'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Symbol</label>
                                                                                    <input type="text" class="form-control" name="symbol" maxlength="10" value="<?php echo htmlspecialchars((string)$r['symbol'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Decimal Places</label>
                                                                                    <input type="number" class="form-control" name="decimal_places" min="0" max="6" value="<?php echo (int)$r['decimal_places']; ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Position</label>
                                                                                    <select class="form-control" name="position">
                                                                                          <option value="prefix" <?php echo ($r['position'] === 'prefix') ? 'selected' : ''; ?>>Prefix</option>
                                                                                          <option value="suffix" <?php echo ($r['position'] === 'suffix') ? 'selected' : ''; ?>>Suffix</option>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="form-check mb-2">
                                                                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default_<?php echo (int)$r['id']; ?>" <?php echo ((int)$r['is_default'] === 1) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="is_default_<?php echo (int)$r['id']; ?>">Default</label>
                                                                              </div>
                                                                              <div class="form-check mb-2">
                                                                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_<?php echo (int)$r['id']; ?>" <?php echo ((int)$r['is_active'] === 1) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="is_active_<?php echo (int)$r['id']; ?>">Active</label>
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

<div class="modal fade" id="addCurrencyModal" tabindex="-1" aria-labelledby="addCurrencyModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addCurrencyModalLabel">Add Currency</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_currency">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Code *</label>
                                    <input type="text" class="form-control" name="code" maxlength="3" required placeholder="e.g., USD">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required placeholder="US Dollar">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Exchange Rate *</label>
                                    <input type="number" step="0.000001" min="0.000001" class="form-control" name="exchange_rate" required value="1.000000">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Symbol</label>
                                    <input type="text" class="form-control" name="symbol" maxlength="10" placeholder="$">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Decimal Places</label>
                                    <input type="number" class="form-control" name="decimal_places" min="0" max="6" value="2">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <select class="form-control" name="position">
                                          <option value="prefix" selected>Prefix</option>
                                          <option value="suffix">Suffix</option>
                                    </select>
                              </div>
                              <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default_new" checked>
                                    <label class="form-check-label" for="is_default_new">Default</label>
                              </div>
                              <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new" checked>
                                    <label class="form-check-label" for="is_active_new">Active</label>
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

<?php if ($totalPages > 1): ?>
      <nav aria-label="Currencies pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=currencies&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=currencies&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=currencies&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>