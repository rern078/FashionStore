<?php
require_once __DIR__ . '/../config/function.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_size') {
      $label = trim($_POST['label'] ?? '');
      $sortOrder = (int)($_POST['sort_order'] ?? 0);
      if ($label === '') {
            header('Location: /admin/?p=sizes&error=Label%20is%20required');
            exit;
      }
      db_exec('INSERT INTO sizes (label, sort_order) VALUES (?, ?)', [$label, $sortOrder]);
      header('Location: /admin/?p=sizes&added=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_size') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM sizes WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=sizes&deleted=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_size') {
      $id = (int)($_POST['id'] ?? 0);
      $label = trim($_POST['label'] ?? '');
      $sortOrder = (int)($_POST['sort_order'] ?? 0);
      if ($id > 0 && $label !== '') {
            db_exec('UPDATE sizes SET label = ?, sort_order = ? WHERE id = ?', [$label, $sortOrder, $id]);
            header('Location: /admin/?p=sizes&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=sizes&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM sizes');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$sizes = db_all('SELECT id, label, sort_order FROM sizes ORDER BY sort_order ASC, id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Sizes </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Sizes</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Size created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Size updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Size deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Sizes</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSizeModal">Add Size</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="sizes-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Label</th>
                                                <th>Sort Order</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($sizes as $s): ?>
                                                <tr>
                                                      <td><?php echo (int)$s['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($s['label'], ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$s['sort_order']; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSizeModal-<?php echo (int)$s['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this size?');">
                                                                  <input type="hidden" name="form" value="delete_size">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSizeModal-<?php echo (int)$s['id']; ?>" tabindex="-1" aria-labelledby="editSizeModalLabel-<?php echo (int)$s['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSizeModalLabel-<?php echo (int)$s['id']; ?>">Edit Size</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_size">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Label *</label>
                                                                                    <input type="text" class="form-control" name="label" required value="<?php echo htmlspecialchars($s['label'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Sort Order</label>
                                                                                    <input type="number" class="form-control" name="sort_order" value="<?php echo (int)$s['sort_order']; ?>">
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

<div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSizeModalLabel">Add Size</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_size">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Label *</label>
                                    <input type="text" class="form-control" name="label" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" name="sort_order" value="0">
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
      <nav aria-label="Sizes pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=sizes&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=sizes&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=sizes&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>