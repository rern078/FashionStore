<?php
require_once __DIR__ . '/../config/function.php';

// Create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_social') {
      $platform = trim($_POST['platform'] ?? 'other');
      $label = trim($_POST['label'] ?? '');
      $url = trim($_POST['url'] ?? '');
      $icon = trim($_POST['icon'] ?? '');
      if ($icon === '') {
            $icon = social_default_icon($platform !== '' ? $platform : 'other');
      }
      $position = (int)($_POST['position'] ?? 1);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      if ($url !== '') {
            db_exec('INSERT INTO social_links (platform, label, url, icon, is_active, position) VALUES (?, ?, ?, ?, ?, ?)', [
                  $platform !== '' ? $platform : 'other',
                  $label !== '' ? $label : null,
                  $url,
                  $icon !== '' ? $icon : null,
                  $isActive,
                  $position > 0 ? $position : 1,
            ]);
            header('Location: /admin/?p=social_links&added=1');
            exit;
      } else {
            header('Location: /admin/?p=social_links&error=URL%20is%20required');
            exit;
      }
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_social') {
      $id = (int)($_POST['id'] ?? 0);
      $platform = trim($_POST['platform'] ?? 'other');
      $label = trim($_POST['label'] ?? '');
      $url = trim($_POST['url'] ?? '');
      $icon = trim($_POST['icon'] ?? '');
      if ($icon === '') {
            $icon = social_default_icon($platform !== '' ? $platform : 'other');
      }
      $position = (int)($_POST['position'] ?? 1);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      if ($id > 0 && $url !== '') {
            db_exec('UPDATE social_links SET platform = ?, label = ?, url = ?, icon = ?, is_active = ?, position = ? WHERE id = ?', [
                  $platform !== '' ? $platform : 'other',
                  $label !== '' ? $label : null,
                  $url,
                  $icon !== '' ? $icon : null,
                  $isActive,
                  $position > 0 ? $position : 1,
                  $id,
            ]);
            header('Location: /admin/?p=social_links&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=social_links&error=Invalid%20data');
            exit;
      }
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_social') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM social_links WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=social_links&deleted=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM social_links');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT * FROM social_links ORDER BY position ASC, id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Social Media </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Social Media</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Social link created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Social link updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Social link deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Social Links</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSocialModal">Add Social Link</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Platform</th>
                                                <th>Label</th>
                                                <th>URL</th>
                                                <th>Icon</th>
                                                <th>Position</th>
                                                <th>Active</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['platform'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['label'], ENT_QUOTES); ?></td>
                                                      <td style="max-width:260px;">
                                                            <div class="text-truncate" style="max-width:260px;">
                                                                  <?php echo htmlspecialchars((string)$r['url'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td><?php echo htmlspecialchars((string)$r['icon'], ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$r['position']; ?></td>
                                                      <td><?php echo ((int)$r['is_active']) ? 'Yes' : 'No'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSocialModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this social link?');">
                                                                  <input type="hidden" name="form" value="delete_social">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSocialModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editSocialModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSocialModalLabel-<?php echo (int)$r['id']; ?>">Edit Social Link</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_social">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Platform</label>
                                                                                    <select class="form-select" name="platform">
                                                                                          <?php
                                                                                          $platforms = ['facebook', 'instagram', 'twitter', 'tiktok', 'youtube', 'linkedin', 'pinterest', 'telegram', 'whatsapp', 'other'];
                                                                                          foreach ($platforms as $p): ?>
                                                                                                <option value="<?php echo $p; ?>" <?php echo ($r['platform'] === $p) ? 'selected' : ''; ?>><?php echo ucfirst($p); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Label</label>
                                                                                    <input type="text" class="form-control" name="label" value="<?php echo htmlspecialchars((string)$r['label'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">URL *</label>
                                                                                    <input type="text" class="form-control" name="url" required value="<?php echo htmlspecialchars((string)$r['url'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Icon</label>
                                                                                    <div class="input-group">
                                                                                          <input type="text" class="form-control" name="icon" value="<?php echo htmlspecialchars((string)$r['icon'], ENT_QUOTES); ?>" readonly>
                                                                                          <button type="button" class="btn btn-outline-secondary" onclick="this.closest('form').querySelector('[name=icon]').value='<?php echo social_default_icon((string)$r['platform']); ?>'">Use default</button>
                                                                                    </div>
                                                                                    <div class="form-text">Leave blank to auto-set based on platform, or click Use default.</div>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Position</label>
                                                                                    <input type="number" class="form-control" name="position" value="<?php echo (int)$r['position']; ?>">
                                                                              </div>
                                                                              <div class="form-check form-switch mb-3">
                                                                                    <input class="form-check-input" type="checkbox" id="is_active_<?php echo (int)$r['id']; ?>" name="is_active" <?php echo ((int)$r['is_active']) ? 'checked' : ''; ?>>
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

<div class="modal fade" id="addSocialModal" tabindex="-1" aria-labelledby="addSocialModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSocialModalLabel">Add Social Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_social">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Platform</label>
                                    <select class="form-select" name="platform">
                                          <?php $platforms = ['facebook', 'instagram', 'twitter', 'tiktok', 'youtube', 'linkedin', 'pinterest', 'telegram', 'whatsapp', 'other'];
                                          foreach ($platforms as $p): ?>
                                                <option value="<?php echo $p; ?>"><?php echo ucfirst($p); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Label</label>
                                    <input type="text" class="form-control" name="label">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">URL *</label>
                                    <input type="text" class="form-control" name="url" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <div class="input-group">
                                          <input type="text" class="form-control" name="icon" placeholder="Leave blank to auto-set" readonly>
                                          <button type="button" class="btn btn-outline-secondary" onclick="this.closest('form').querySelector('[name=icon]').value=''">Clear</button>
                                    </div>
                                    <div class="form-text">If blank, an icon is auto-set based on platform (e.g., facebook → bi bi-facebook).</div>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="number" class="form-control" name="position" value="1">
                              </div>
                              <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active_add" name="is_active" checked>
                                    <label class="form-check-label" for="is_active_add">Active</label>
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
      <nav aria-label="Social links pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=social_links&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=social_links&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=social_links&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>