<?php
require_once __DIR__ . '/../config/function.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_color') {
      $name = trim($_POST['name'] ?? '');
      $hex = trim($_POST['hex'] ?? '');
      if ($name === '') {
            header('Location: /admin/?p=colors&error=Name%20is%20required');
            exit;
      }
      if ($hex !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            header('Location: /admin/?p=colors&error=Invalid%20hex%20format');
            exit;
      }
      db_exec('INSERT INTO colors (name, hex) VALUES (?, ?)', [$name, $hex !== '' ? strtolower($hex) : null]);
      header('Location: /admin/?p=colors&added=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_color') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM colors WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=colors&deleted=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_color') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $hex = trim($_POST['hex'] ?? '');
      if ($id > 0 && $name !== '') {
            if ($hex !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
                  header('Location: /admin/?p=colors&error=Invalid%20hex%20format');
                  exit;
            }
            db_exec('UPDATE colors SET name = ?, hex = ? WHERE id = ?', [$name, $hex !== '' ? strtolower($hex) : null, $id]);
            header('Location: /admin/?p=colors&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=colors&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM colors');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$colors = db_all('SELECT id, name, hex FROM colors ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Colors </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Colors</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Color created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Color updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Color deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Colors</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addColorModal">Add Color</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="colors-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Hex</th>
                                                <th>Preview</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($colors as $c): ?>
                                                <tr>
                                                      <td><?php echo (int)$c['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($c['hex'], ENT_QUOTES); ?></td>
                                                      <td>
                                                            <?php if (!empty($c['hex'])): ?>
                                                                  <span style="display:inline-block;width:24px;height:24px;border:1px solid #ccc;background: <?php echo htmlspecialchars($c['hex'], ENT_QUOTES); ?>"></span>
                                                            <?php else: ?>
                                                                  —
                                                            <?php endif; ?>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editColorModal-<?php echo (int)$c['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this color?');">
                                                                  <input type="hidden" name="form" value="delete_color">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editColorModal-<?php echo (int)$c['id']; ?>" tabindex="-1" aria-labelledby="editColorModalLabel-<?php echo (int)$c['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editColorModalLabel-<?php echo (int)$c['id']; ?>">Edit Color</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_color">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Hex</label>
                                                                                    <input type="text" class="form-control" name="hex" placeholder="#RRGGBB" value="<?php echo htmlspecialchars($c['hex'], ENT_QUOTES); ?>">
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

<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_color">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Hex</label>
                                    <input type="text" class="form-control" name="hex" placeholder="#RRGGBB">
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
      <nav aria-label="Colors pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=colors&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=colors&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=colors&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>