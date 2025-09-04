<?php
require_once __DIR__ . '/../config/function.php';

$users = db_all('SELECT id, name, email FROM users ORDER BY id DESC');

// Add cart
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_cart') {
      $userId = trim($_POST['user_id'] ?? '');
      $sessionId = trim($_POST['session_id'] ?? '');
      $currency = strtoupper(trim($_POST['currency'] ?? 'USD'));

      if ($sessionId === '') {
            header('Location: /admin/?p=carts&error=Session%20ID%20is%20required');
            exit;
      }

      db_exec('INSERT INTO carts (user_id, session_id, currency) VALUES (?, ?, ?)', [
            $userId !== '' ? (int)$userId : null,
            substr($sessionId, 0, 64),
            substr($currency, 0, 3)
      ]);

      header('Location: /admin/?p=carts&added=1');
      exit;
}

// Delete cart
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_cart') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM carts WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=carts&deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM carts');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all('SELECT c.id, c.user_id, c.session_id, c.created_at, c.updated_at, c.currency, u.name, u.email FROM carts c LEFT JOIN users u ON u.id = c.user_id ORDER BY c.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Carts </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Carts</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Cart created.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Cart deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Carts</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addCartModal">Add Cart</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="carts-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Session</th>
                                                <th>Currency</th>
                                                <th>Created</th>
                                                <th>Updated</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo $r['user_id'] ? htmlspecialchars(($r['name'] ?? '') . ' (' . ($r['email'] ?? '') . ')', ENT_QUOTES) : '—'; ?></td>
                                                      <td><?php echo htmlspecialchars($r['session_id'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['currency'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['created_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['updated_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <a class="btn btn-sm btn-outline-secondary" href="/admin/?p=cart_items&cart_id=<?php echo (int)$r['id']; ?>">Items</a>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this cart?');">
                                                                  <input type="hidden" name="form" value="delete_cart">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                          <?php endforeach; ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<?php if ($totalPages > 1): ?>
      <nav aria-label="Carts pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=carts&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=carts&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=carts&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addCartModal" tabindex="-1" aria-labelledby="addCartModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addCartModalLabel">Add Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_cart">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">User (optional)</label>
                                    <select class="form-select" name="user_id">
                                          <option value="">— None —</option>
                                          <?php foreach ($users as $u): ?>
                                                <option value="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Session ID *</label>
                                    <input type="text" class="form-control" name="session_id" maxlength="64" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" class="form-control" name="currency" maxlength="3" value="USD">
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