<?php
require_once __DIR__ . '/../config/function.php';

// Fetch active categories for selects
$incomeCategories = db_all('SELECT id, name FROM finance_categories WHERE type = ? AND is_active = 1 ORDER BY name ASC', ['income']);
$expenseCategories = db_all('SELECT id, name FROM finance_categories WHERE type = ? AND is_active = 1 ORDER BY name ASC', ['expense']);

// Helpers
function current_admin_user_id(): ?int
{
      if (isset($_SESSION['user']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            return (int)$_SESSION['user']['id'];
      }
      return null;
}

// Handle add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_finance_entry') {
      $entryDate = $_POST['entry_date'] ?? date('Y-m-d');
      $type = $_POST['type'] ?? 'expense';
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
      $currency = strtoupper(trim($_POST['currency'] ?? 'USD'));
      $description = trim($_POST['description'] ?? '');
      $refType = $_POST['reference_type'] ?? null;
      $refId = isset($_POST['reference_id']) && $_POST['reference_id'] !== '' ? (int)$_POST['reference_id'] : null;
      $createdBy = current_admin_user_id();

      if (!in_array($type, ['income', 'expense'], true) || $amount <= 0) {
            header('Location: /admin/?p=finance_entries&error=Invalid%20data');
            exit;
      }
      if ($categoryId > 0) {
            $ok = db_one('SELECT id FROM finance_categories WHERE id = ? AND type = ?', [$categoryId, $type]);
            if (!$ok) {
                  header('Location: /admin/?p=finance_entries&error=Invalid%20category');
                  exit;
            }
      } else {
            $categoryId = null;
      }

      db_exec(
            'INSERT INTO finance_entries (entry_date, type, category_id, amount, currency, description, reference_type, reference_id, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$entryDate, $type, $categoryId, $amount, $currency, $description !== '' ? $description : null, $refType ?: null, $refId, $createdBy]
      );
      header('Location: /admin/?p=finance_entries&added=1');
      exit;
}

// Handle delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_finance_entry') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM finance_entries WHERE id = ?', [$id]);
            header('Location: /admin/?p=finance_entries&deleted=1');
            exit;
      }
}

// Handle update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_finance_entry') {
      $id = (int)($_POST['id'] ?? 0);
      $entryDate = $_POST['entry_date'] ?? date('Y-m-d');
      $type = $_POST['type'] ?? 'expense';
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
      $currency = strtoupper(trim($_POST['currency'] ?? 'USD'));
      $description = trim($_POST['description'] ?? '');
      $refType = $_POST['reference_type'] ?? null;
      $refId = isset($_POST['reference_id']) && $_POST['reference_id'] !== '' ? (int)$_POST['reference_id'] : null;

      if ($id <= 0 || !in_array($type, ['income', 'expense'], true) || $amount <= 0) {
            header('Location: /admin/?p=finance_entries&error=Invalid%20data');
            exit;
      }
      if ($categoryId > 0) {
            $ok = db_one('SELECT id FROM finance_categories WHERE id = ? AND type = ?', [$categoryId, $type]);
            if (!$ok) {
                  header('Location: /admin/?p=finance_entries&error=Invalid%20category');
                  exit;
            }
      } else {
            $categoryId = null;
      }

      db_exec(
            'UPDATE finance_entries SET entry_date=?, type=?, category_id=?, amount=?, currency=?, description=?, reference_type=?, reference_id=? WHERE id=?',
            [$entryDate, $type, $categoryId, $amount, $currency, $description !== '' ? $description : null, $refType ?: null, $refId, $id]
      );
      header('Location: /admin/?p=finance_entries&updated=1');
      exit;
}

// Filters
$typeFilter = isset($_GET['type']) && in_array($_GET['type'], ['income', 'expense'], true) ? $_GET['type'] : null;
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = [];
$params = [];
if ($typeFilter) {
      $where[] = 'fe.type = ?';
      $params[] = $typeFilter;
}
if ($from !== '') {
      $where[] = 'fe.entry_date >= ?';
      $params[] = $from;
}
if ($to !== '') {
      $where[] = 'fe.entry_date <= ?';
      $params[] = $to;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;
$totalRow = db_one("SELECT COUNT(*) AS c FROM finance_entries fe $whereSql", $params);
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

// List
$entries = db_all(
      "SELECT fe.*, fc.name AS category_name FROM finance_entries fe LEFT JOIN finance_categories fc ON fc.id = fe.category_id $whereSql ORDER BY fe.entry_date DESC, fe.id DESC LIMIT ? OFFSET ?",
      array_merge($params, [$perPage, $offset])
);

// Totals (within filter)
$totals = db_one(
      "SELECT 
            SUM(CASE WHEN fe.type='income' THEN fe.amount ELSE 0 END) AS income_total,
            SUM(CASE WHEN fe.type='expense' THEN fe.amount ELSE 0 END) AS expense_total
       FROM finance_entries fe $whereSql",
      $params
);
$incomeTotal = (float)($totals['income_total'] ?? 0);
$expenseTotal = (float)($totals['expense_total'] ?? 0);
$net = $incomeTotal - $expenseTotal;
?>

<div class="page-header">
      <h3 class="page-title"> Income & Expenses </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Finance</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Entries</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Entry added.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Entry updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Entry deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row mb-3">
      <div class="col-12">
            <form class="row g-2">
                  <input type="hidden" name="p" value="finance_entries">
                  <div class="col-md-2">
                        <select name="type" class="form-select">
                              <option value="">All Types</option>
                              <option value="income" <?php echo $typeFilter === 'income' ? 'selected' : ''; ?>>Income</option>
                              <option value="expense" <?php echo $typeFilter === 'expense' ? 'selected' : ''; ?>>Expense</option>
                        </select>
                  </div>
                  <div class="col-md-2">
                        <input type="date" name="from" class="form-control" value="<?php echo htmlspecialchars($from, ENT_QUOTES); ?>" placeholder="From">
                  </div>
                  <div class="col-md-2">
                        <input type="date" name="to" class="form-control" value="<?php echo htmlspecialchars($to, ENT_QUOTES); ?>" placeholder="To">
                  </div>
                  <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
                  </div>
                  <div class="col-md-2">
                        <a class="btn btn-light w-100" href="/admin/?p=finance_entries">Reset</a>
                  </div>
                  <div class="col-md-2">
                        <button class="btn btn-gradient-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#addEntryModal">Add Entry</button>
                  </div>
            </form>
      </div>
</div>

<div class="row mb-3">
      <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Income</h4>
                        <h3 class="text-success mb-0"><?php echo number_format($incomeTotal, 2); ?> <?php echo htmlspecialchars((string)($_SESSION['currency'] ?? 'USD'), ENT_QUOTES); ?></h3>
                  </div>
            </div>
      </div>
      <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Expenses</h4>
                        <h3 class="text-danger mb-0"><?php echo number_format($expenseTotal, 2); ?> <?php echo htmlspecialchars((string)($_SESSION['currency'] ?? 'USD'), ENT_QUOTES); ?></h3>
                  </div>
            </div>
      </div>
      <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Net</h4>
                        <h3 class="mb-0"><?php echo number_format($net, 2); ?> <?php echo htmlspecialchars((string)($_SESSION['currency'] ?? 'USD'), ENT_QUOTES); ?></h3>
                  </div>
            </div>
      </div>
</div>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Entries</h4>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Currency</th>
                                                <th>Description</th>
                                                <th>Reference</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($entries as $e): ?>
                                                <tr>
                                                      <td><?php echo (int)$e['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($e['entry_date'], ENT_QUOTES); ?></td>
                                                      <td><span class="badge badge-<?php echo $e['type'] === 'income' ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($e['type'], ENT_QUOTES); ?></span></td>
                                                      <td><?php echo htmlspecialchars($e['category_name'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)$e['amount'], 2); ?></td>
                                                      <td><?php echo htmlspecialchars($e['currency'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($e['description'] ?? '—', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars(($e['reference_type'] ?? '') . (($e['reference_id'] ?? '') ? (' #' . $e['reference_id']) : ''), ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEntry-<?php echo (int)$e['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this entry?');">
                                                                  <input type="hidden" name="form" value="delete_finance_entry">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editEntry-<?php echo (int)$e['id']; ?>" tabindex="-1" aria-labelledby="editEntryLabel-<?php echo (int)$e['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editEntryLabel-<?php echo (int)$e['id']; ?>">Edit Entry</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_finance_entry">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Date *</label>
                                                                                    <input type="date" name="entry_date" class="form-control" required value="<?php echo htmlspecialchars($e['entry_date'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Type *</label>
                                                                                    <select name="type" class="form-select" required>
                                                                                          <option value="income" <?php echo $e['type'] === 'income' ? 'selected' : ''; ?>>Income</option>
                                                                                          <option value="expense" <?php echo $e['type'] === 'expense' ? 'selected' : ''; ?>>Expense</option>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Category</label>
                                                                                    <select name="category_id" class="form-select" data-income-options='<?php echo json_encode($incomeCategories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>' data-expense-options='<?php echo json_encode($expenseCategories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>'>
                                                                                          <option value="0">— None —</option>
                                                                                          <?php
                                                                                          $list = $e['type'] === 'income' ? $incomeCategories : $expenseCategories;
                                                                                          foreach ($list as $c) {
                                                                                                $sel = ((int)($e['category_id'] ?? 0) === (int)$c['id']) ? 'selected' : '';
                                                                                                echo '<option value="' . (int)$c['id'] . '" ' . $sel . '>' . htmlspecialchars($c['name'], ENT_QUOTES) . '</option>';
                                                                                          }
                                                                                          ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Amount *</label>
                                                                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required value="<?php echo htmlspecialchars((string)$e['amount'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Currency</label>
                                                                                    <input type="text" name="currency" maxlength="3" class="form-control" value="<?php echo htmlspecialchars($e['currency'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Description</label>
                                                                                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($e['description'] ?? '', ENT_QUOTES); ?></textarea>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Reference Type</label>
                                                                                          <select name="reference_type" class="form-select">
                                                                                                <option value="">—</option>
                                                                                                <?php foreach (['order', 'payment', 'return', 'shipment', 'other'] as $rt): ?>
                                                                                                      <option value="<?php echo $rt; ?>" <?php echo ($e['reference_type'] ?? '') === $rt ? 'selected' : ''; ?>><?php echo ucfirst($rt); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Reference ID</label>
                                                                                          <input type="number" step="1" min="1" name="reference_id" class="form-control" value="<?php echo htmlspecialchars((string)($e['reference_id'] ?? ''), ENT_QUOTES); ?>">
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
      <nav aria-label="Finance pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=finance_entries&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=finance_entries&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=finance_entries&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addEntryModalLabel">Add Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_finance_entry">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" name="entry_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Type *</label>
                                    <select name="type" class="form-select" required id="addTypeSelect">
                                          <option value="income">Income</option>
                                          <option value="expense" selected>Expense</option>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" id="addCategorySelect" data-income-options='<?php echo json_encode($incomeCategories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>' data-expense-options='<?php echo json_encode($expenseCategories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>'>
                                          <option value="0">— None —</option>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Amount *</label>
                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" name="currency" maxlength="3" class="form-control" value="<?php echo htmlspecialchars((string)($_SESSION['currency'] ?? 'USD'), ENT_QUOTES); ?>">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                              </div>
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Reference Type</label>
                                          <select name="reference_type" class="form-select">
                                                <option value="">—</option>
                                                <?php foreach (['order', 'payment', 'return', 'shipment', 'other'] as $rt): ?>
                                                      <option value="<?php echo $rt; ?>"><?php echo ucfirst($rt); ?></option>
                                                <?php endforeach; ?>
                                          </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Reference ID</label>
                                          <input type="number" step="1" min="1" name="reference_id" class="form-control">
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

<script>
      (function() {
            function populateCategory(selectTypeEl, categorySelectEl) {
                  var type = selectTypeEl.value === 'income' ? 'income' : 'expense';
                  var optionsData = categorySelectEl.getAttribute('data-' + type + '-options');
                  try {
                        var arr = JSON.parse(optionsData || '[]');
                  } catch (e) {
                        arr = [];
                  }
                  categorySelectEl.innerHTML = '<option value="0">— None —</option>';
                  for (var i = 0; i < arr.length; i++) {
                        var o = document.createElement('option');
                        o.value = String(arr[i].id);
                        o.textContent = arr[i].name;
                        categorySelectEl.appendChild(o);
                  }
            }

            // Add modal wiring
            var addType = document.getElementById('addTypeSelect');
            var addCat = document.getElementById('addCategorySelect');
            if (addType && addCat) {
                  populateCategory(addType, addCat);
                  addType.addEventListener('change', function() {
                        populateCategory(addType, addCat);
                  });
            }

            // Edit modals wiring (delegated on show)
            document.addEventListener('shown.bs.modal', function(ev) {
                  var modal = ev.target;
                  if (!modal || !modal.id || modal.id.indexOf('editEntry-') !== 0) return;
                  var typeSel = modal.querySelector('select[name="type"]');
                  var catSel = modal.querySelector('select[name="category_id"]');
                  if (typeSel && catSel) {
                        populateCategory(typeSel, catSel);
                        // try select current
                        var cur = String(<?php echo json_encode(array_column($entries, 'category_id', 'id')); ?>[modal.querySelector('input[name="id"]').value] || '0');
                        for (var i = 0; i < catSel.options.length; i++) {
                              if (catSel.options[i].value === cur) {
                                    catSel.options[i].selected = true;
                                    break;
                              }
                        }
                        typeSel.addEventListener('change', function() {
                              populateCategory(typeSel, catSel);
                        });
                  }
            });
      })();
</script>