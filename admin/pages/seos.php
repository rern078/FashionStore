<?php
require_once __DIR__ . '/../config/function.php';

// Add SEO
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_seo') {
      $pageName = trim($_POST['page'] ?? '');
      $slug = trim($_POST['slug'] ?? '');
      $metaTitle = trim($_POST['meta_title'] ?? '');
      $metaDescription = trim($_POST['meta_description'] ?? '');
      $metaKeywords = trim($_POST['meta_keywords'] ?? '');
      $ogTitle = trim($_POST['og_title'] ?? '');
      $ogDescription = trim($_POST['og_description'] ?? '');
      $ogImageUrl = trim($_POST['og_image_url'] ?? '');

      if ($pageName === '') {
            header('Location: /admin/?p=seos&error=Page%20name%20is%20required');
            exit;
      }
      $exists = db_one('SELECT id FROM seos WHERE page = ?', [$pageName]);
      if ($exists) {
            header('Location: /admin/?p=seos&error=Page%20already%20exists');
            exit;
      }
      db_exec('INSERT INTO seos (page, slug, meta_title, meta_description, meta_keywords, og_title, og_description, og_image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            $pageName !== '' ? $pageName : null,
            $slug !== '' ? $slug : null,
            $metaTitle !== '' ? $metaTitle : null,
            $metaDescription !== '' ? $metaDescription : null,
            $metaKeywords !== '' ? $metaKeywords : null,
            $ogTitle !== '' ? $ogTitle : null,
            $ogDescription !== '' ? $ogDescription : null,
            $ogImageUrl !== '' ? $ogImageUrl : null,
      ]);
      header('Location: /admin/?p=seos&added=1');
      exit;
}

// Delete SEO
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_seo') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM seos WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=seos&deleted=1');
      exit;
}

// Update SEO
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_seo') {
      $id = (int)($_POST['id'] ?? 0);
      $pageName = trim($_POST['page'] ?? '');
      $slug = trim($_POST['slug'] ?? '');
      $metaTitle = trim($_POST['meta_title'] ?? '');
      $metaDescription = trim($_POST['meta_description'] ?? '');
      $metaKeywords = trim($_POST['meta_keywords'] ?? '');
      $ogTitle = trim($_POST['og_title'] ?? '');
      $ogDescription = trim($_POST['og_description'] ?? '');
      $ogImageUrl = trim($_POST['og_image_url'] ?? '');
      if ($id > 0 && $pageName !== '') {
            $dup = db_one('SELECT id FROM seos WHERE page = ? AND id <> ?', [$pageName, $id]);
            if ($dup) {
                  header('Location: /admin/?p=seos&error=Page%20already%20exists');
                  exit;
            }
            db_exec('UPDATE seos SET page = ?, slug = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, og_title = ?, og_description = ?, og_image_url = ? WHERE id = ?', [
                  $pageName !== '' ? $pageName : null,
                  $slug !== '' ? $slug : null,
                  $metaTitle !== '' ? $metaTitle : null,
                  $metaDescription !== '' ? $metaDescription : null,
                  $metaKeywords !== '' ? $metaKeywords : null,
                  $ogTitle !== '' ? $ogTitle : null,
                  $ogDescription !== '' ? $ogDescription : null,
                  $ogImageUrl !== '' ? $ogImageUrl : null,
                  $id,
            ]);
            header('Location: /admin/?p=seos&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=seos&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM seos');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$rows = db_all('SELECT id, page, slug, meta_title, meta_description, meta_keywords, og_title, og_description, og_image_url, updated_at FROM seos ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> SEO </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Configuration</a></li>
                  <li class="breadcrumb-item active" aria-current="page">SEO</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">SEO created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">SEO updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">SEO deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All SEO Pages</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSeoModal">Add SEO</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="seos-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Page</th>
                                                <th>Slug</th>
                                                <th>Meta Title</th>
                                                <th>Meta Description</th>
                                                <th>Updated</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['page'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['slug'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['meta_title'], ENT_QUOTES); ?></td>
                                                      <td style="max-width:300px;">
                                                            <div class="text-truncate" style="max-width:300px;"><?php echo htmlspecialchars((string)$r['meta_description'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td><?php echo htmlspecialchars((string)$r['updated_at'], ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSeoModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this SEO entry?');">
                                                                  <input type="hidden" name="form" value="delete_seo">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSeoModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="editSeoModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSeoModalLabel-<?php echo (int)$r['id']; ?>">Edit SEO</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_seo">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Page *</label>
                                                                                    <input type="text" class="form-control" name="page" required value="<?php echo htmlspecialchars($r['page'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Slug</label>
                                                                                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars((string)$r['slug'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Meta Title</label>
                                                                                    <input type="text" class="form-control" name="meta_title" value="<?php echo htmlspecialchars((string)$r['meta_title'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Meta Description</label>
                                                                                    <textarea class="form-control" name="meta_description" rows="3"><?php echo htmlspecialchars((string)$r['meta_description'], ENT_QUOTES); ?></textarea>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Meta Keywords</label>
                                                                                    <input type="text" class="form-control" name="meta_keywords" value="<?php echo htmlspecialchars((string)$r['meta_keywords'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">OG Title</label>
                                                                                    <input type="text" class="form-control" name="og_title" value="<?php echo htmlspecialchars((string)$r['og_title'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">OG Description</label>
                                                                                    <textarea class="form-control" name="og_description" rows="3"><?php echo htmlspecialchars((string)$r['og_description'], ENT_QUOTES); ?></textarea>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">OG Image URL</label>
                                                                                    <input type="text" class="form-control" name="og_image_url" value="<?php echo htmlspecialchars((string)$r['og_image_url'], ENT_QUOTES); ?>">
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

<div class="modal fade" id="addSeoModal" tabindex="-1" aria-labelledby="addSeoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSeoModalLabel">Add SEO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_seo">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Page *</label>
                                    <input type="text" class="form-control" name="page" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3"></textarea>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">OG Title</label>
                                    <input type="text" class="form-control" name="og_title">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">OG Description</label>
                                    <textarea class="form-control" name="og_description" rows="3"></textarea>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">OG Image URL</label>
                                    <input type="text" class="form-control" name="og_image_url">
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
      <nav aria-label="SEO pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=seos&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=seos&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=seos&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>