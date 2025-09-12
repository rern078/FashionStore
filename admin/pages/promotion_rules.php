<?php
require_once __DIR__ . '/../config/function.php';

// Create rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_rule') {
      $promotionId = (int)($_POST['promotion_id'] ?? 0);
      $ruleType = trim((string)($_POST['rule_type'] ?? ''));
      $ruleValue = trim((string)($_POST['rule_value'] ?? ''));
      if ($promotionId > 0 && $ruleType !== '' && $ruleValue !== '') {
            db_exec('INSERT INTO promotion_rules (promotion_id, rule_type, rule_value) VALUES (?, ?, ?)', [$promotionId, substr($ruleType, 0, 50), substr($ruleValue, 0, 200)]);
            header('Location: /admin/?p=promotion_rules&added=1');
            exit;
      }
      header('Location: /admin/?p=promotion_rules&error=Invalid%20data');
      exit;
}

// Update rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_rule') {
      $id = (int)($_POST['id'] ?? 0);
      $promotionId = (int)($_POST['promotion_id'] ?? 0);
      $ruleType = trim((string)($_POST['rule_type'] ?? ''));
      $ruleValue = trim((string)($_POST['rule_value'] ?? ''));
      if ($id > 0 && $promotionId > 0 && $ruleType !== '' && $ruleValue !== '') {
            db_exec('UPDATE promotion_rules SET promotion_id=?, rule_type=?, rule_value=? WHERE id=?', [$promotionId, substr($ruleType, 0, 50), substr($ruleValue, 0, 200), $id]);
            header('Location: /admin/?p=promotion_rules&updated=1');
            exit;
      }
      header('Location: /admin/?p=promotion_rules&error=Invalid%20data');
      exit;
}

// Delete rule
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_rule') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) db_exec('DELETE FROM promotion_rules WHERE id=?', [$id]);
      header('Location: /admin/?p=promotion_rules&deleted=1');
      exit;
}

// Data
$promos = db_all('SELECT id, code FROM promotions ORDER BY code ASC');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM promotion_rules');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT pr.id, pr.promotion_id, pr.rule_type, pr.rule_value, p.code AS promo_code FROM promotion_rules pr JOIN promotions p ON p.id=pr.promotion_id ORDER BY pr.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Promotion Rules </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=promotions">Promotions</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Rules</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success">Rule created.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success">Rule updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Rule deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Rules</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addRuleModal">Add Rule</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Promotion</th>
                                                <th>Type</th>
                                                <th>Value</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r) { ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td>
                                                            <form method="post" class="d-flex align-items-center gap-2">
                                                                  <input type="hidden" name="form" value="update_rule">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <select class="form-select" name="promotion_id" style="max-width:180px">
                                                                        <?php foreach ($promos as $p) { ?>
                                                                              <option value="<?php echo (int)$p['id']; ?>" <?php echo ((int)$p['id'] === (int)$r['promotion_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['code'], ENT_QUOTES); ?></option>
                                                                        <?php } ?>
                                                                  </select>
                                                      </td>
                                                      <td>
                                                            <input type="text" name="rule_type" class="form-control" style="max-width:220px" value="<?php echo htmlspecialchars($r['rule_type'], ENT_QUOTES); ?>">
                                                      </td>
                                                      <td>
                                                            <input type="text" name="rule_value" class="form-control" style="max-width:260px" value="<?php echo htmlspecialchars($r['rule_value'], ENT_QUOTES); ?>">
                                                      </td>
                                                      <td class="d-flex align-items-center gap-2">
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                                                            </form>
                                                            <form method="post" class="d-inline-block">
                                                                  <input type="hidden" name="form" value="delete_rule">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this rule?');">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                          <?php } ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<?php if ($totalPages > 1): ?>
      <nav aria-label="Promotion rules pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=promotion_rules&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=promotion_rules&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=promotion_rules&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addRuleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Add Rule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_rule">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Promotion *</label>
                                    <select class="form-select" name="promotion_id" required>
                                          <option value="">Select...</option>
                                          <?php foreach ($promos as $p) { ?>
                                                <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['code'], ENT_QUOTES); ?></option>
                                          <?php } ?>
                                    </select>
                              </div>
                              <div class="mb-3"><label class="form-label">Rule Type *</label><input type="text" class="form-control" name="rule_type" placeholder="e.g., country or include_product_slug" required></div>
                              <div class="mb-3"><label class="form-label">Rule Value *</label><input type="text" class="form-control" name="rule_value" required></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Create</button></div>
                  </form>
            </div>
      </div>
</div>