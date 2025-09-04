<?php
require_once __DIR__ . '/../config/function.php';

// Create Promotion
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_promo') {
      $code = strtoupper(trim($_POST['code'] ?? ''));
      $type = $_POST['type'] ?? 'percentage';
      $value = isset($_POST['value']) ? (float)$_POST['value'] : 0.0;
      $startsAt = trim($_POST['starts_at'] ?? '');
      $endsAt = trim($_POST['ends_at'] ?? '');
      $minSubtotal = trim($_POST['min_subtotal'] ?? '');
      $maxUses = trim($_POST['max_uses'] ?? '');
      $perUser = trim($_POST['per_user_limit'] ?? '');

      if ($code === '' || !in_array($type, ['percentage', 'fixed', 'free_shipping'], true)) {
            header('Location: /admin/?p=promotions&error=Invalid%20data');
            exit;
      }

      db_exec('INSERT INTO promotions (code, type, value, starts_at, ends_at, min_subtotal, max_uses, per_user_limit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            substr($code, 0, 50),
            $type,
            $value,
            $startsAt !== '' ? $startsAt : null,
            $endsAt !== '' ? $endsAt : null,
            $minSubtotal !== '' ? (float)$minSubtotal : null,
            $maxUses !== '' ? (int)$maxUses : null,
            $perUser !== '' ? (int)$perUser : null
      ]);
      header('Location: /admin/?p=promotions&added=1');
      exit;
}

// Delete Promotion
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_promo') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM promotions WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=promotions&deleted=1');
      exit;
}

// Update Promotion
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_promo') {
      $id = (int)($_POST['id'] ?? 0);
      $code = strtoupper(trim($_POST['code'] ?? ''));
      $type = $_POST['type'] ?? 'percentage';
      $value = isset($_POST['value']) ? (float)$_POST['value'] : 0.0;
      $startsAt = trim($_POST['starts_at'] ?? '');
      $endsAt = trim($_POST['ends_at'] ?? '');
      $minSubtotal = trim($_POST['min_subtotal'] ?? '');
      $maxUses = trim($_POST['max_uses'] ?? '');
      $perUser = trim($_POST['per_user_limit'] ?? '');

      if ($id <= 0 || $code === '' || !in_array($type, ['percentage', 'fixed', 'free_shipping'], true)) {
            header('Location: /admin/?p=promotions&error=Invalid%20data');
            exit;
      }

      db_exec('UPDATE promotions SET code=?, type=?, value=?, starts_at=?, ends_at=?, min_subtotal=?, max_uses=?, per_user_limit=? WHERE id=?', [
            substr($code, 0, 50),
            $type,
            $value,
            $startsAt !== '' ? $startsAt : null,
            $endsAt !== '' ? $endsAt : null,
            $minSubtotal !== '' ? (float)$minSubtotal : null,
            $maxUses !== '' ? (int)$maxUses : null,
            $perUser !== '' ? (int)$perUser : null,
            $id
      ]);
      header('Location: /admin/?p=promotions&updated=1');
      exit;
}

// Add Rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_rule') {
      $promotionId = (int)($_POST['promotion_id'] ?? 0);
      $ruleType = trim($_POST['rule_type'] ?? '');
      $ruleValue = trim($_POST['rule_value'] ?? '');
      if ($promotionId > 0 && $ruleType !== '' && $ruleValue !== '') {
            db_exec('INSERT INTO promotion_rules (promotion_id, rule_type, rule_value) VALUES (?, ?, ?)', [$promotionId, substr($ruleType, 0, 50), substr($ruleValue, 0, 200)]);
            header('Location: /admin/?p=promotions&rule_added=1');
            exit;
      } else {
            header('Location: /admin/?p=promotions&error=Invalid%20rule');
            exit;
      }
}

// Delete Rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_rule') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM promotion_rules WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=promotions&rule_deleted=1');
      exit;
}

// Update Rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_rule') {
      $id = (int)($_POST['id'] ?? 0);
      $ruleType = trim($_POST['rule_type'] ?? '');
      $ruleValue = trim($_POST['rule_value'] ?? '');
      if ($id > 0 && $ruleType !== '' && $ruleValue !== '') {
            db_exec('UPDATE promotion_rules SET rule_type=?, rule_value=? WHERE id=?', [substr($ruleType, 0, 50), substr($ruleValue, 0, 200), $id]);
            header('Location: /admin/?p=promotions&rule_updated=1');
            exit;
      } else {
            header('Location: /admin/?p=promotions&error=Invalid%20rule');
            exit;
      }
}

// Pagination for promotions
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM promotions');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$promos = db_all('SELECT id, code, type, value, starts_at, ends_at, min_subtotal, max_uses, per_user_limit FROM promotions ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Promotions </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Promotions</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Promotion created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Promotion updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Promotion deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['rule_added'])): ?>
      <div class="alert alert-success" role="alert">Rule added.</div>
<?php endif; ?>
<?php if (!empty($_GET['rule_updated'])): ?>
      <div class="alert alert-success" role="alert">Rule updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['rule_deleted'])): ?>
      <div class="alert alert-success" role="alert">Rule deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Promotions</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addPromoModal">Add Promotion</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="promotions-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Value</th>
                                                <th>Starts</th>
                                                <th>Ends</th>
                                                <th>Min Subtotal</th>
                                                <th>Max Uses</th>
                                                <th>Per User</th>
                                                <th>Rules</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($promos as $pr): $rules = db_all('SELECT id, rule_type, rule_value FROM promotion_rules WHERE promotion_id=? ORDER BY id ASC', [$pr['id']]); ?>
                                                <tr>
                                                      <td><?php echo (int)$pr['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($pr['code'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($pr['type'], ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)($pr['value'] ?? 0), 2); ?></td>
                                                      <td><?php echo htmlspecialchars($pr['starts_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($pr['ends_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo $pr['min_subtotal'] !== null ? number_format((float)$pr['min_subtotal'], 2) : '—'; ?></td>
                                                      <td><?php echo $pr['max_uses'] !== null ? (int)$pr['max_uses'] : '—'; ?></td>
                                                      <td><?php echo $pr['per_user_limit'] !== null ? (int)$pr['per_user_limit'] : '—'; ?></td>
                                                      <td>
                                                            <div>
                                                                  <?php foreach ($rules as $ru): ?>
                                                                        <form method="post" class="d-flex align-items-center gap-2 mb-1">
                                                                              <input type="hidden" name="form" value="update_rule">
                                                                              <input type="hidden" name="id" value="<?php echo (int)$ru['id']; ?>">
                                                                              <input type="text" name="rule_type" class="form-control" style="max-width:160px" value="<?php echo htmlspecialchars($ru['rule_type'], ENT_QUOTES); ?>">
                                                                              <input type="text" name="rule_value" class="form-control" style="max-width:220px" value="<?php echo htmlspecialchars($ru['rule_value'], ENT_QUOTES); ?>">
                                                                              <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                                                                        </form>
                                                                        <form method="post" onsubmit="return confirm('Delete this rule?');" class="mb-2">
                                                                              <input type="hidden" name="form" value="delete_rule">
                                                                              <input type="hidden" name="id" value="<?php echo (int)$ru['id']; ?>">
                                                                              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                                        </form>
                                                                  <?php endforeach; ?>
                                                                  <form method="post" class="mt-2">
                                                                        <input type="hidden" name="form" value="add_rule">
                                                                        <input type="hidden" name="promotion_id" value="<?php echo (int)$pr['id']; ?>">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                              <input type="text" name="rule_type" class="form-control" style="max-width:160px" placeholder="rule_type e.g., include_category_slug">
                                                                              <input type="text" name="rule_value" class="form-control" style="max-width:220px" placeholder="value e.g., jeans">
                                                                              <button type="submit" class="btn btn-sm btn-outline-secondary">Add</button>
                                                                        </div>
                                                                  </form>
                                                            </div>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPromoModal-<?php echo (int)$pr['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this promotion?');">
                                                                  <input type="hidden" name="form" value="delete_promo">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$pr['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editPromoModal-<?php echo (int)$pr['id']; ?>" tabindex="-1" aria-labelledby="editPromoModalLabel-<?php echo (int)$pr['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editPromoModalLabel-<?php echo (int)$pr['id']; ?>">Edit Promotion</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_promo">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$pr['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="row">
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Code *</label>
                                                                                          <input type="text" name="code" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($pr['code'], ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Type</label>
                                                                                          <select name="type" class="form-select">
                                                                                                <option value="percentage" <?php echo ($pr['type'] === 'percentage') ? 'selected' : ''; ?>>percentage</option>
                                                                                                <option value="fixed" <?php echo ($pr['type'] === 'fixed') ? 'selected' : ''; ?>>fixed</option>
                                                                                                <option value="free_shipping" <?php echo ($pr['type'] === 'free_shipping') ? 'selected' : ''; ?>>free_shipping</option>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Value</label>
                                                                                          <input type="number" step="0.01" min="0" name="value" class="form-control" value="<?php echo htmlspecialchars((string)($pr['value'] ?? '0'), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Starts at</label>
                                                                                          <input type="datetime-local" name="starts_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($pr['starts_at'] ?? '')), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Ends at</label>
                                                                                          <input type="datetime-local" name="ends_at" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', (string)($pr['ends_at'] ?? '')), ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Min Subtotal</label>
                                                                                          <input type="number" step="0.01" min="0" name="min_subtotal" class="form-control" value="<?php echo htmlspecialchars($pr['min_subtotal'] !== null ? (string)$pr['min_subtotal'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Max Uses</label>
                                                                                          <input type="number" step="1" min="0" name="max_uses" class="form-control" value="<?php echo htmlspecialchars($pr['max_uses'] !== null ? (string)$pr['max_uses'] : '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Per User Limit</label>
                                                                                          <input type="number" step="1" min="0" name="per_user_limit" class="form-control" value="<?php echo htmlspecialchars($pr['per_user_limit'] !== null ? (string)$pr['per_user_limit'] : '', ENT_QUOTES); ?>">
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
      <nav aria-label="Promotions pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=promotions&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=promotions&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=promotions&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addPromoModal" tabindex="-1" aria-labelledby="addPromoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addPromoModalLabel">Add Promotion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_promo">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Code *</label>
                                          <input type="text" name="code" class="form-control" maxlength="50" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Type</label>
                                          <select name="type" class="form-select">
                                                <option value="percentage">percentage</option>
                                                <option value="fixed">fixed</option>
                                                <option value="free_shipping">free_shipping</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Value</label>
                                          <input type="number" step="0.01" min="0" name="value" class="form-control" value="0.00">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Starts at</label>
                                          <input type="datetime-local" name="starts_at" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Ends at</label>
                                          <input type="datetime-local" name="ends_at" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Min Subtotal</label>
                                          <input type="number" step="0.01" min="0" name="min_subtotal" class="form-control" placeholder="e.g., 50.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Max Uses</label>
                                          <input type="number" step="1" min="0" name="max_uses" class="form-control" placeholder="optional">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Per User Limit</label>
                                          <input type="number" step="1" min="0" name="per_user_limit" class="form-control" placeholder="optional">
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