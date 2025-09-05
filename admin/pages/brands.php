<?php
require_once __DIR__ . '/../config/function.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_brand') {
      $name = trim($_POST['name'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      $description = trim($_POST['description'] ?? '');
      $logoUrl = trim($_POST['logo_url'] ?? '');

      if ($name === '') {
            header('Location: /admin/?p=brands&error=Name%20is%20required');
            exit;
      }

      $slug = $slugInput !== '' ? make_slug($slugInput) : make_slug($name);
      if ($slug === '') {
            $slug = 'brand-' . time();
      }

      $base = $slug;
      $i = 1;
      while (db_one('SELECT id FROM brands WHERE slug = ?', [$slug])) {
            $slug = $base . '-' . (++$i);
      }

      // Try to save uploaded file if provided; fallback to text URL
      $uploadedUrl = fs_save_uploaded_logo('logo_file');
      if ($uploadedUrl) {
            $logoUrl = $uploadedUrl;
      }

      db_exec('INSERT INTO brands (name, slug, description, logo_url) VALUES (?, ?, ?, ?)', [$name, $slug, $description, $logoUrl]);
      header('Location: /admin/?p=brands&added=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_brand') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            // Delete logo file if exists
            $existing = db_one('SELECT logo_url FROM brands WHERE id = ?', [$id]);
            if ($existing && !empty($existing['logo_url'])) {
                  fs_delete_admin_file_by_url($existing['logo_url']);
            }
            db_exec('DELETE FROM brands WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=brands&deleted=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_brand') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      $description = trim($_POST['description'] ?? '');
      $logoUrlInput = trim($_POST['logo_url'] ?? '');
      if ($id > 0 && $name !== '') {
            $existing = db_one('SELECT slug, logo_url FROM brands WHERE id = ?', [$id]);
            $slug = $slugInput !== '' ? make_slug($slugInput) : ($existing['slug'] ?? make_slug($name));
            $base = $slug;
            $i = 1;
            while (db_one('SELECT id FROM brands WHERE slug = ? AND id <> ?', [$slug, $id])) {
                  $slug = $base . '-' . (++$i);
            }
            // Decide logo URL: uploaded file has priority
            $newUploaded = fs_save_uploaded_logo('logo_file');
            $finalLogoUrl = $newUploaded ?: $logoUrlInput;
            // If replacing with a new uploaded file and previous file exists, delete old
            if ($newUploaded && !empty($existing['logo_url']) && $existing['logo_url'] !== $newUploaded) {
                  fs_delete_admin_file_by_url($existing['logo_url']);
            }
            db_exec('UPDATE brands SET name = ?, slug = ?, description = ?, logo_url = ? WHERE id = ?', [$name, $slug, $description, $finalLogoUrl, $id]);
            header('Location: /admin/?p=brands&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=brands&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM brands');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$brands = db_all('SELECT id, name, slug, description, logo_url FROM brands ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Brands </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Brands</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Brand created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Brand updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Brand deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Brands</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">Add Brand</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="brands-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Logo</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($brands as $b): ?>
                                                <tr>
                                                      <td><?php echo (int)$b['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($b['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($b['slug'], ENT_QUOTES); ?></td>
                                                      <td><?php echo $b['logo_url'] ? '<img src="' . htmlspecialchars($b['logo_url'], ENT_QUOTES) . '" alt="logo" style="height:32px">' : '—'; ?></td>
                                                      <td style="max-width:300px;">
                                                            <div class="text-truncate" style="max-width: 300px;"><?php echo htmlspecialchars($b['description'], ENT_QUOTES); ?></div>
                                                      </td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBrandModal-<?php echo (int)$b['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this brand?');">
                                                                  <input type="hidden" name="form" value="delete_brand">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editBrandModal-<?php echo (int)$b['id']; ?>" tabindex="-1" aria-labelledby="editBrandModalLabel-<?php echo (int)$b['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editBrandModalLabel-<?php echo (int)$b['id']; ?>">Edit Brand</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="form" value="update_brand">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($b['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Slug</label>
                                                                                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($b['slug'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Logo URL</label>
                                                                                    <input type="text" class="form-control" name="logo_url" value="<?php echo htmlspecialchars($b['logo_url'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Or Upload New Logo</label>
                                                                                    <input type="file" class="form-control" name="logo_file" accept="image/*">
                                                                                    <div class="form-text">Uploading a file will replace the current logo.</div>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Description</label>
                                                                                    <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($b['description'], ENT_QUOTES); ?></textarea>
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

<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form" value="add_brand">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Slug (optional)</label>
                                    <input type="text" class="form-control" name="slug" placeholder="auto-from-name">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Logo URL</label>
                                    <input type="text" class="form-control" name="logo_url">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Or Upload Logo</label>
                                    <input type="file" class="form-control" name="logo_file" accept="image/*">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4"></textarea>
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
      <nav aria-label="Brands pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=brands&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=brands&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=brands&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>