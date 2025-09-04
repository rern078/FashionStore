<?php
require_once __DIR__ . '/../config/function.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'add_category') {
      $name = trim($_POST['name'] ?? '');
      $parentId = trim($_POST['parent_id'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');

      if ($name === '') {
            header('Location: /admin/?p=category&error=Name%20is%20required');
            exit;
      }

      $slug = $slugInput !== '' ? make_slug($slugInput) : make_slug($name);
      if ($slug === '') {
            $slug = 'cat-' . time();
      }

      // ensure unique slug
      $base = $slug;
      $i = 1;
      while (db_one('SELECT id FROM categories WHERE slug = ?', [$slug])) {
            $slug = $base . '-' . (++$i);
      }

      $parentId = $parentId !== '' ? (int)$parentId : null;

      db_exec('INSERT INTO categories (name, slug, parent_id) VALUES (?, ?, ?)', [$name, $slug, $parentId]);
      header('Location: /admin/?p=category&added=1');
      exit;
}

$deleted = false;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_category') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM categories WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=category&deleted=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'update_category') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $slugInput = trim($_POST['slug'] ?? '');
      $parentId = trim($_POST['parent_id'] ?? '');
      if ($id > 0 && $name !== '') {
            $parentId = $parentId !== '' ? (int)$parentId : null;
            if ($parentId === $id) {
                  $parentId = null;
            }
            $existing = db_one('SELECT slug FROM categories WHERE id = ?', [$id]);
            $slug = $slugInput !== '' ? make_slug($slugInput) : ($existing['slug'] ?? make_slug($name));
            $base = $slug;
            $i = 1;
            while (db_one('SELECT id FROM categories WHERE slug = ? AND id <> ?', [$slug, $id])) {
                  $slug = $base . '-' . (++$i);
            }
            db_exec('UPDATE categories SET name = ?, slug = ?, parent_id = ? WHERE id = ?', [$name, $slug, $parentId, $id]);
            header('Location: /admin/?p=category&updated=1');
            exit;
      } else {
            header('Location: /admin/?p=category&error=Invalid%20data');
            exit;
      }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM categories');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$cats = db_all('SELECT id, name, slug, parent_id FROM categories ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Categories </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Catalog</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Category created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Category updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Category deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Categories</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="categories-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Parent</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($cats as $c): ?>
                                                <tr>
                                                      <td><?php echo (int)$c['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($c['slug'], ENT_QUOTES); ?></td>
                                                      <td><?php echo $c['parent_id'] ? (int)$c['parent_id'] : '—'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal-<?php echo (int)$c['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this category?');">
                                                                  <input type="hidden" name="form" value="delete_category">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editCategoryModal-<?php echo (int)$c['id']; ?>" tabindex="-1" aria-labelledby="editCategoryModalLabel-<?php echo (int)$c['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editCategoryModalLabel-<?php echo (int)$c['id']; ?>">Edit Category</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_category">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Slug</label>
                                                                                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($c['slug'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Parent Category</label>
                                                                                    <select class="form-select" name="parent_id">
                                                                                          <option value="">— None —</option>
                                                                                          <?php foreach ($cats as $pc): if ($pc['id'] == $c['id']) continue; ?>
                                                                                                <option value="<?php echo (int)$pc['id']; ?>" <?php echo ($c['parent_id'] == $pc['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pc['name'], ENT_QUOTES); ?></option>
                                                                                          <?php endforeach; ?>
                                                                                    </select>
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

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_category">
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
                                    <label class="form-label">Parent Category</label>
                                    <select class="form-select" name="parent_id">
                                          <option value="">— None —</option>
                                          <?php foreach ($cats as $c): ?>
                                                <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
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
      <nav aria-label="Categories pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=category&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=category&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=category&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>