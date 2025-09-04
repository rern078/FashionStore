<?php
require_once __DIR__ . '/../config/function.php';

// Add banner
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_banner') {
      $title = trim($_POST['title'] ?? '');
      $subtitle = trim($_POST['subtitle'] ?? '');
      $imageUrl = trim($_POST['image_url'] ?? '');
      $linkUrl = trim($_POST['link_url'] ?? '');
      $altText = trim($_POST['alt_text'] ?? '');
      $position = (int)($_POST['position'] ?? 1);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $startsAt = trim($_POST['starts_at'] ?? '');
      $endsAt = trim($_POST['ends_at'] ?? '');

      if ($imageUrl === '') {
            header('Location: /admin/?p=banners&error=Image%20URL%20is%20required');
            exit;
      }
      db_exec('INSERT INTO banners (title, subtitle, image_url, link_url, alt_text, position, is_active, starts_at, ends_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $title !== '' ? $title : null,
            $subtitle !== '' ? $subtitle : null,
            $imageUrl,
            $linkUrl !== '' ? $linkUrl : null,
            $altText !== '' ? $altText : null,
            $position > 0 ? $position : 1,
            $isActive,
            $startsAt !== '' ? $startsAt : null,
            $endsAt !== '' ? $endsAt : null,
      ]);
      header('Location: /admin/?p=banners&added=1');
      exit;
}

// Delete banner
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_banner') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM banners WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=banners&deleted=1');
      exit;
}

// Update banner
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_banner') {
      $id = (int)($_POST['id'] ?? 0);
      $title = trim($_POST['title'] ?? '');
      $subtitle = trim($_POST['subtitle'] ?? '');
      $imageUrl = trim($_POST['image_url'] ?? '');
      $linkUrl = trim($_POST['link_url'] ?? '');
      $altText = trim($_POST['alt_text'] ?? '');
      $position = (int)($_POST['position'] ?? 1);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $startsAt = trim($_POST['starts_at'] ?? '');
      $endsAt = trim($_POST['ends_at'] ?? '');
      if ($id > 0 && $imageUrl !== '') {
            db_exec('UPDATE banners SET title = ?, subtitle = ?, image_url = ?, link_url = ?, alt_text = ?, position = ?, is_active = ?, starts_at = ?, ends_at = ? WHERE id = ?', [
                  $title !== '' ? $title : null,
                  $subtitle !== '' ? $subtitle : null,
                  $imageUrl,
                  $linkUrl !== '' ? $linkUrl : null,
                  $altText !== '' ? $altText : null,
                  $position > 0 ? $position : 1,
                  $isActive,
                  $startsAt !== '' ? $startsAt : null,
                  $endsAt !== '' ? $endsAt : null,
                  $id,
            ]);
            header('Location: /admin/?p=banners&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=banners&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM banners');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT id, title, subtitle, image_url, link_url, alt_text, position, is_active, starts_at, ends_at, updated_at FROM banners ORDER BY position ASC, id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Banners </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Marketing</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Banners</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Banner created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Banner updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Banner deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Banners</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">Add Banner</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="banners-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Preview</th>
                                                <th>Title</th>
                                                <th>Subtitle</th>
                                                <th>Link</th>
                                                <th>Position</th>
                                                <th>Active</th>
                                                <th>Window</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo $r['image_url'] ? '<img src="' . htmlspecialchars($r['image_url'], ENT_QUOTES) . '" alt="banner" style="height:40px">' : '—'; ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['title'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['subtitle'], ENT_QUOTES); ?></td>
                                                      <td style="max-width:200px;">
                                                            <div class="text-truncate" style="max-width:200px;"><?php echo htmlspecialchars((string)$r['link_url'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td><?php echo (int)$r['position']; ?></td>
                                                      <td><?php echo ((int)$r['is_active']) ? 'Yes' : 'No'; ?></td>
                                                      <td>
                                                            <?php
                                                            $win = [];
                                                            if (!empty($r['starts_at'])) {
                                                                  $win[] = htmlspecialchars((string)$r['starts_at'], ENT_QUOTES);
                                                            }
                                                            if (!empty($r['ends_at'])) {
                                                                  $win[] = htmlspecialchars((string)$r['ends_at'], ENT_QUOTES);
                                                            }
                                                            echo $win ? implode(' → ', $win) : '—';
                                                            ?>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBannerModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this banner?');">
                                                                  <input type="hidden" name="form" value="delete_banner">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editBannerModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editBannerModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editBannerModalLabel-<?php echo (int)$r['id']; ?>">Edit Banner</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_banner">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Title</label>
                                                                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars((string)$r['title'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Subtitle</label>
                                                                                    <input type="text" class="form-control" name="subtitle" value="<?php echo htmlspecialchars((string)$r['subtitle'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Image URL *</label>
                                                                                    <input type="text" class="form-control" name="image_url" required value="<?php echo htmlspecialchars($r['image_url'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Link URL</label>
                                                                                    <input type="text" class="form-control" name="link_url" value="<?php echo htmlspecialchars((string)$r['link_url'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Alt Text</label>
                                                                                    <input type="text" class="form-control" name="alt_text" value="<?php echo htmlspecialchars((string)$r['alt_text'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Position</label>
                                                                                    <input type="number" class="form-control" name="position" value="<?php echo (int)$r['position']; ?>">
                                                                              </div>
                                                                              <div class="form-check form-switch mb-3">
                                                                                    <input class="form-check-input" type="checkbox" id="is_active_<?php echo (int)$r['id']; ?>" name="is_active" <?php echo ((int)$r['is_active']) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="is_active_<?php echo (int)$r['id']; ?>">Active</label>
                                                                              </div>
                                                                              <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Starts At</label>
                                                                                          <input type="datetime-local" class="form-control" name="starts_at" value="<?php echo $r['starts_at'] ? date('Y-m-d\TH:i', strtotime($r['starts_at'])) : ''; ?>">
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                          <label class="form-label">Ends At</label>
                                                                                          <input type="datetime-local" class="form-control" name="ends_at" value="<?php echo $r['ends_at'] ? date('Y-m-d\TH:i', strtotime($r['ends_at'])) : ''; ?>">
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

<div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addBannerModalLabel">Add Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_banner">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" name="subtitle">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Image URL *</label>
                                    <input type="text" class="form-control" name="image_url" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Link URL</label>
                                    <input type="text" class="form-control" name="link_url">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Alt Text</label>
                                    <input type="text" class="form-control" name="alt_text">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="number" class="form-control" name="position" value="1">
                              </div>
                              <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active_add" name="is_active" checked>
                                    <label class="form-check-label" for="is_active_add">Active</label>
                              </div>
                              <div class="row">
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Starts At</label>
                                          <input type="datetime-local" class="form-control" name="starts_at">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                          <label class="form-label">Ends At</label>
                                          <input type="datetime-local" class="form-control" name="ends_at">
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

<?php if ($totalPages > 1): ?>
      <nav aria-label="Banners pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=banners&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=banners&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=banners&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>