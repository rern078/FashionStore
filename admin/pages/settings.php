<?php
require_once __DIR__ . '/../config/function.php';

// Add setting (key-value)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_setting') {
      $key = trim($_POST['key'] ?? '');
      $value = trim($_POST['value'] ?? '');
      $description = trim($_POST['description'] ?? '');
      if ($key === '') {
            header('Location: /admin/?p=settings&error=Key%20is%20required');
            exit;
      }
      // Ensure unique key
      $exists = db_one('SELECT id FROM settings WHERE `key` = ?', [$key]);
      if ($exists) {
            header('Location: /admin/?p=settings&error=Key%20already%20exists');
            exit;
      }
      db_exec('INSERT INTO settings (`key`, `value`, description) VALUES (?, ?, ?)', [$key, $value !== '' ? $value : null, $description !== '' ? $description : null]);
      header('Location: /admin/?p=settings&added=1');
      exit;
}

// Delete setting
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_setting') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM settings WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=settings&deleted=1');
      exit;
}

// Update setting
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_setting') {
      $id = (int)($_POST['id'] ?? 0);
      $key = trim($_POST['key'] ?? '');
      $value = trim($_POST['value'] ?? '');
      $description = trim($_POST['description'] ?? '');
      if ($id > 0 && $key !== '') {
            // prevent duplicate keys on other rows
            $dup = db_one('SELECT id FROM settings WHERE `key` = ? AND id <> ?', [$key, $id]);
            if ($dup) {
                  header('Location: /admin/?p=settings&error=Key%20already%20exists');
                  exit;
            }
            db_exec('UPDATE settings SET `key` = ?, `value` = ?, description = ? WHERE id = ?', [$key, $value !== '' ? $value : null, $description !== '' ? $description : null, $id]);
            header('Location: /admin/?p=settings&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=settings&error=Invalid%20data');
            exit;
      }
}

// Pagination and fetching
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM settings');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT id, `key`, `value`, description, created_at, updated_at FROM settings ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Settings </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Configuration</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Setting created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Setting updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Setting deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Settings</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSettingModal">Add Setting</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="settings-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Key</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                                <th>Updated</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['key'], ENT_QUOTES); ?></td>
                                                      <td style="max-width:300px;">
                                                            <div class="text-truncate" style="max-width:300px;"><?php echo htmlspecialchars((string)$r['value'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td style="max-width:300px;">
                                                            <div class="text-truncate" style="max-width:300px;"><?php echo htmlspecialchars((string)$r['description'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td><?php echo htmlspecialchars((string)$r['updated_at'], ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSettingModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this setting?');">
                                                                  <input type="hidden" name="form" value="delete_setting">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSettingModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editSettingModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSettingModalLabel-<?php echo (int)$r['id']; ?>">Edit Setting</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_setting">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Key *</label>
                                                                                    <input type="text" class="form-control" name="key" required value="<?php echo htmlspecialchars($r['key'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Value</label>
                                                                                    <textarea class="form-control" name="value" rows="4"><?php echo htmlspecialchars((string)$r['value'], ENT_QUOTES); ?></textarea>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Description</label>
                                                                                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars((string)$r['description'], ENT_QUOTES); ?></textarea>
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

<div class="modal fade" id="addSettingModal" tabindex="-1" aria-labelledby="addSettingModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSettingModalLabel">Add Setting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_setting">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Key *</label>
                                    <input type="text" class="form-control" name="key" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Value</label>
                                    <textarea class="form-control" name="value" rows="4"></textarea>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
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
      <nav aria-label="Settings pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=settings&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=settings&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=settings&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>