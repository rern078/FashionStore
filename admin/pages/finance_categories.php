<?php
require_once __DIR__ . '/../config/function.php';

// Handle create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_finance_category') {
      $name = trim($_POST['name'] ?? '');
      $type = $_POST['type'] ?? 'expense';
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      if ($name === '' || !in_array($type, ['income', 'expense'], true)) {
            header('Location: /admin/?p=finance_categories&error=Invalid%20data');
            exit;
      }
      // Ensure unique within type
      $exists = db_one('SELECT id FROM finance_categories WHERE name = ? AND type = ?', [$name, $type]);
      if ($exists) {
            header('Location: /admin/?p=finance_categories&error=Category%20already%20exists');
            exit;
      }
      db_exec('INSERT INTO finance_categories (name, type, is_active) VALUES (?, ?, ?)', [$name, $type, $isActive]);
      header('Location: /admin/?p=finance_categories&added=1');
      exit;
}

// Handle delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_finance_category') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            // Ensure no entries depend on it
            $used = db_one('SELECT id FROM finance_entries WHERE category_id = ? LIMIT 1', [$id]);
            if ($used) {
                  header('Location: /admin/?p=finance_categories&error=Category%20in%20use');
                  exit;
            }
            db_exec('DELETE FROM finance_categories WHERE id = ?', [$id]);
            header('Location: /admin/?p=finance_categories&deleted=1');
            exit;
      }
}

// Handle update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_finance_category') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $type = $_POST['type'] ?? 'expense';
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      if ($id <= 0 || $name === '' || !in_array($type, ['income', 'expense'], true)) {
            header('Location: /admin/?p=finance_categories&error=Invalid%20data');
            exit;
      }
      $exists = db_one('SELECT id FROM finance_categories WHERE name = ? AND type = ? AND id <> ?', [$name, $type, $id]);
      if ($exists) {
            header('Location: /admin/?p=finance_categories&error=Duplicate%20name');
            exit;
      }
      db_exec('UPDATE finance_categories SET name=?, type=?, is_active=? WHERE id=?', [$name, $type, $isActive, $id]);
      header('Location: /admin/?p=finance_categories&updated=1');
      exit;
}

$categories = db_all('SELECT * FROM finance_categories ORDER BY type ASC, name ASC');
?>

<div class="page-header">
      <h3 class="page-title"> Finance Categories </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Finance</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
      </nav>
      <div>
            <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
      </div>

</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Category added.</div>
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
                        <h4 class="card-title">All Categories</h4>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Active</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($categories as $c): ?>
                                                <tr>
                                                      <td><?php echo (int)$c['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?></td>
                                                      <td><span class="badge badge-<?php echo $c['type'] === 'income' ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($c['type'], ENT_QUOTES); ?></span></td>
                                                      <td><?php echo ((int)$c['is_active'] === 1) ? 'Yes' : 'No'; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCat-<?php echo (int)$c['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this category?');">
                                                                  <input type="hidden" name="form" value="delete_finance_category">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editCat-<?php echo (int)$c['id']; ?>" tabindex="-1" aria-labelledby="editCatLabel-<?php echo (int)$c['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editCatLabel-<?php echo (int)$c['id']; ?>">Edit Category</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_finance_category">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($c['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Type *</label>
                                                                                    <select name="type" class="form-select" required>
                                                                                          <option value="income" <?php echo $c['type'] === 'income' ? 'selected' : ''; ?>>Income</option>
                                                                                          <option value="expense" <?php echo $c['type'] === 'expense' ? 'selected' : ''; ?>>Expense</option>
                                                                                    </select>
                                                                              </div>
                                                                              <div class="form-check mb-3">
                                                                                    <input class="form-check-input" type="checkbox" id="edit_active_<?php echo (int)$c['id']; ?>" name="is_active" <?php echo ((int)$c['is_active'] === 1) ? 'checked' : ''; ?>>
                                                                                    <label class="form-check-label" for="edit_active_<?php echo (int)$c['id']; ?>">Active</label>
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
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Finance Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_finance_category">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Type *</label>
                                    <select name="type" class="form-select" required>
                                          <option value="income">Income</option>
                                          <option value="expense" selected>Expense</option>
                                    </select>
                              </div>
                              <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="add_active" name="is_active" checked>
                                    <label class="form-check-label" for="add_active">Active</label>
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