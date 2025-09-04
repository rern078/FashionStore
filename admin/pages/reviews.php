<?php
require_once __DIR__ . '/../config/function.php';

$users = db_all('SELECT id, name, email FROM users ORDER BY id DESC');
$products = db_all('SELECT id, title FROM products ORDER BY id DESC');

// Add
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_review') {
      $userId = (int)($_POST['user_id'] ?? 0);
      $productId = (int)($_POST['product_id'] ?? 0);
      $rating = max(1, min(5, (int)($_POST['rating'] ?? 0)));
      $title = trim($_POST['title'] ?? '');
      $body = trim($_POST['body'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      if ($userId <= 0 || $productId <= 0 || $rating <= 0) {
            header('Location: /admin/?p=reviews&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO reviews (user_id, product_id, rating, title, body, status) VALUES (?, ?, ?, ?, ?, ?)', [$userId, $productId, $rating, $title !== '' ? substr($title, 0, 150) : null, $body !== '' ? $body : null, $status]);
      header('Location: /admin/?p=reviews&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_review') {
      $id = (int)($_POST['id'] ?? 0);
      $userId = (int)($_POST['user_id'] ?? 0);
      $productId = (int)($_POST['product_id'] ?? 0);
      $rating = max(1, min(5, (int)($_POST['rating'] ?? 0)));
      $title = trim($_POST['title'] ?? '');
      $body = trim($_POST['body'] ?? '');
      $status = $_POST['status'] ?? 'pending';
      if ($id <= 0 || $userId <= 0 || $productId <= 0 || $rating <= 0) {
            header('Location: /admin/?p=reviews&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE reviews SET user_id=?, product_id=?, rating=?, title=?, body=?, status=? WHERE id=?', [$userId, $productId, $rating, $title !== '' ? substr($title, 0, 150) : null, $body !== '' ? $body : null, $status, $id]);
      header('Location: /admin/?p=reviews&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_review') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM reviews WHERE id=?', [$id]);
      }
      header('Location: /admin/?p=reviews&deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM reviews');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));

$rows = db_all('SELECT r.id, r.user_id, r.product_id, r.rating, r.title, r.body, r.created_at, r.status, u.name AS user_name, u.email AS user_email, p.title AS product_title FROM reviews r JOIN users u ON u.id=r.user_id JOIN products p ON p.id=r.product_id ORDER BY r.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Reviews </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Reviews</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success" role="alert">Review added.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success" role="alert">Review updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success" role="alert">Review deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Reviews</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addReviewModal">Add Review</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="reviews-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Product</th>
                                                <th>Rating</th>
                                                <th>Title</th>
                                                <th>Created</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars(($r['user_name'] ?? '') . ' (' . ($r['user_email'] ?? '') . ')', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['rating']; ?>/5</td>
                                                      <td><?php echo htmlspecialchars($r['title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['created_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($r['status'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editReviewModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this review?');">
                                                                  <input type="hidden" name="form" value="delete_review">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editReviewModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editReviewModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editReviewModalLabel-<?php echo (int)$r['id']; ?>">Edit Review</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_review">
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
                                                                                          <label class="form-label">Product *</label>
                                                                                          <select name="product_id" class="form-select" required>
                                                                                                <?php foreach ($products as $p): ?>
                                                                                                      <option value="<?php echo (int)$p['id']; ?>" <?php echo ((int)$r['product_id'] === (int)$p['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['title'] ?? '', ENT_QUOTES); ?></option>
                                                                                                <?php endforeach; ?>
                                                                                          </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Rating *</label>
                                                                                          <input type="number" step="1" min="1" max="5" name="rating" class="form-control" value="<?php echo (int)$r['rating']; ?>">
                                                                                    </div>
                                                                                    <div class="col-12 mb-3">
                                                                                          <label class="form-label">Title</label>
                                                                                          <input type="text" name="title" class="form-control" maxlength="150" value="<?php echo htmlspecialchars($r['title'] ?? '', ENT_QUOTES); ?>">
                                                                                    </div>
                                                                                    <div class="col-12 mb-3">
                                                                                          <label class="form-label">Body</label>
                                                                                          <textarea name="body" class="form-control" rows="3"><?php echo htmlspecialchars($r['body'] ?? '', ENT_QUOTES); ?></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-4 mb-3">
                                                                                          <label class="form-label">Status</label>
                                                                                          <select name="status" class="form-select">
                                                                                                <option value="pending" <?php echo ($r['status'] === 'pending') ? 'selected' : ''; ?>>pending</option>
                                                                                                <option value="approved" <?php echo ($r['status'] === 'approved') ? 'selected' : ''; ?>>approved</option>
                                                                                                <option value="rejected" <?php echo ($r['status'] === 'rejected') ? 'selected' : ''; ?>>rejected</option>
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
      <nav aria-label="Reviews pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=reviews&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=reviews&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=reviews&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_review">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">User *</label>
                                    <select name="user_id" class="form-select" required>
                                          <option value="">— Select User —</option>
                                          <?php foreach ($users as $u): ?>
                                                <option value="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars(($u['name'] ?? 'User #' . $u['id']) . ' (' . ($u['email'] ?? '') . ')', ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Product *</label>
                                    <select name="product_id" class="form-select" required>
                                          <option value="">— Select Product —</option>
                                          <?php foreach ($products as $p): ?>
                                                <option value="<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['title'] ?? '', ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Rating *</label>
                                          <input type="number" step="1" min="1" max="5" name="rating" class="form-control" value="5">
                                    </div>
                                    <div class="col-12 mb-3">
                                          <label class="form-label">Title</label>
                                          <input type="text" name="title" class="form-control" maxlength="150">
                                    </div>
                                    <div class="col-12 mb-3">
                                          <label class="form-label">Body</label>
                                          <textarea name="body" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Status</label>
                                          <select name="status" class="form-select">
                                                <option value="pending">pending</option>
                                                <option value="approved">approved</option>
                                                <option value="rejected">rejected</option>
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