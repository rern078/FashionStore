<?php
require_once __DIR__ . '/../config/function.php';

// Add subcategory
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_subcategory') {
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      if ($categoryId <= 0 || $name === '') {
            header('Location: /admin/?p=subcategories&error=Category%20and%20Name%20are%20required');
            exit;
      }
      $slug = $slugInput !== '' ? make_slug($slugInput) : make_slug($name);
      if ($slug === '') {
            $slug = 'subcat-' . time();
      }
      $base = $slug;
      $i = 1;
      while (db_one('SELECT id FROM subcategories WHERE category_id = ? AND slug = ?', [$categoryId, $slug])) {
            $slug = $base . '-' . (++$i);
      }
      db_exec('INSERT INTO subcategories (category_id, name, slug) VALUES (?, ?, ?)', [$categoryId, $name, $slug]);
      header('Location: /admin/?p=subcategories&added=1');
      exit;
}

// Delete subcategory
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_subcategory') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM subcategories WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=subcategories&deleted=1');
      exit;
}

// Update subcategory
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_subcategory') {
      $id = (int)($_POST['id'] ?? 0);
      $categoryId = (int)($_POST['category_id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      if ($id > 0 && $categoryId > 0 && $name !== '') {
            $existing = db_one('SELECT slug, category_id FROM subcategories WHERE id = ?', [$id]);
            $slug = $slugInput !== '' ? make_slug($slugInput) : ($existing['slug'] ?? make_slug($name));
            $base = $slug;
            $i = 1;
            while (db_one('SELECT id FROM subcategories WHERE category_id = ? AND slug = ? AND id <> ?', [$categoryId, $slug, $id])) {
                  $slug = $base . '-' . (++$i);
            }
            db_exec('UPDATE subcategories SET category_id = ?, name = ?, slug = ? WHERE id = ?', [$categoryId, $name, $slug, $id]);
            header('Location: /admin/?p=subcategories&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=subcategories&error=Invalid%20data');
            exit;
      }
}

// Pagination and lists
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM subcategories');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$subcats = db_all('SELECT s.id, s.name, s.slug, s.category_id, c.name AS category_name FROM subcategories s JOIN categories c ON c.id = s.category_id ORDER BY s.id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
$categories = db_all('SELECT id, name FROM categories ORDER BY name ASC');
?>

<div class="page-header">
      <h3 class="page-title"> Subcategories </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Subcategories</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Subcategory created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Subcategory updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Subcategory deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Subcategories</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">Add Subcategory</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="subcategories-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Category</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($subcats as $s): ?>
                                                <tr>
                                                      <td><?php echo (int)$s['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($s['slug'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($s['category_name'], ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal-<?php echo (int)$s['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this subcategory?');">
                                                                  <input type="hidden" name="form" value="delete_subcategory">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editSubcategoryModal-<?php echo (int)$s['id']; ?>" tabindex="-1" aria-labelledby="editSubcategoryModalLabel-<?php echo (int)$s['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editSubcategoryModalLabel-<?php echo (int)$s['id']; ?>">Edit Subcategory</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_subcategory">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Category *</label>
                                                                                    <select class="form-select" name="category_id" required>
                                                                                          <?php foreach ($categories as $c): ?>
                                                                                                <option value="<?php echo (int)$c['id']; ?>" <?php echo ($s['category_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Slug</label>
                                                                                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($s['slug'], ENT_QUOTES); ?>">
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

<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubcategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addSubcategoryModalLabel">Add Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_subcategory">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Category *</label>
                                    <select class="form-select" name="category_id" required>
                                          <option value="">— Select —</option>
                                          <?php foreach ($categories as $c): ?>
                                                <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Slug (optional)</label>
                                    <input type="text" class="form-control" name="slug" placeholder="auto-from-name">
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
      <nav aria-label="Subcategories pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=subcategories&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=subcategories&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=subcategories&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>


