<?php
require_once __DIR__ . '/../config/function.php';

// Handlers: add, delete, update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_user') {
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $role = $_POST['role'] ?? 'customer';

      if ($name === '' || $email === '' || $password === '') {
            header('Location: /admin/?p=users&error=Missing%20required%20fields');
            exit;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /admin/?p=users&error=Invalid%20email');
            exit;
      }

      $exists = db_one('SELECT id FROM users WHERE email = ?', [$email]);
      if ($exists) {
            header('Location: /admin/?p=users&error=Email%20already%20exists');
            exit;
      }

      $hash = password_hash($password, PASSWORD_BCRYPT);
      db_exec('INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)', [
            $name,
            $email,
            $phone !== '' ? $phone : null,
            $hash,
            in_array($role, ['customer', 'admin'], true) ? $role : 'customer'
      ]);

      header('Location: /admin/?p=users&added=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_user') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM users WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=users&deleted=1');
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_user') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $phone = trim($_POST['phone'] ?? '');
      $password = trim($_POST['password'] ?? '');
      $role = $_POST['role'] ?? 'customer';

      if ($id <= 0 || $name === '' || $email === '') {
            header('Location: /admin/?p=users&error=Invalid%20data');
            exit;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /admin/?p=users&error=Invalid%20email');
            exit;
      }

      $exists = db_one('SELECT id FROM users WHERE email = ? AND id <> ?', [$email, $id]);
      if ($exists) {
            header('Location: /admin/?p=users&error=Email%20already%20exists');
            exit;
      }

      db_exec('UPDATE users SET name=?, email=?, phone=?, role=? WHERE id=?', [
            $name,
            $email,
            $phone !== '' ? $phone : null,
            in_array($role, ['customer', 'admin'], true) ? $role : 'customer',
            $id
      ]);

      if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            db_exec('UPDATE users SET password_hash=? WHERE id=?', [$hash, $id]);
      }

      header('Location: /admin/?p=users&updated=1');
      exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$totalRow = db_one('SELECT COUNT(*) AS c FROM users');
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
$users = db_all('SELECT id, name, email, phone, role, created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
?>

<div class="page-header">
      <h3 class="page-title"> Users </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Customers</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">User created.</div>
<?php endif; ?>
<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">User updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">User deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Users</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="users-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Role</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($users as $u): ?>
                                                <tr>
                                                      <td><?php echo (int)$u['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($u['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($u['email'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($u['phone'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><label class="badge badge-<?php echo ($u['role'] === 'admin') ? 'primary' : 'secondary'; ?>"><?php echo htmlspecialchars($u['role'], ENT_QUOTES); ?></label></td>
                                                      <td><?php echo htmlspecialchars($u['created_at'] ?? '', ENT_QUOTES); ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal-<?php echo (int)$u['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this user?');">
                                                                  <input type="hidden" name="form" value="delete_user">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editUserModal-<?php echo (int)$u['id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel-<?php echo (int)$u['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="editUserModalLabel-<?php echo (int)$u['id']; ?>">Edit User</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_user">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Name *</label>
                                                                                    <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($u['name'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Email *</label>
                                                                                    <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($u['email'], ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Phone</label>
                                                                                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($u['phone'] ?? '', ENT_QUOTES); ?>">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">New Password</label>
                                                                                    <input type="password" class="form-control" name="password" placeholder="leave blank to keep">
                                                                              </div>
                                                                              <div class="mb-3">
                                                                                    <label class="form-label">Role</label>
                                                                                    <select name="role" class="form-select">
                                                                                          <option value="customer" <?php echo ($u['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                                                                                          <option value="admin" <?php echo ($u['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
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

<?php if ($totalPages > 1): ?>
      <nav aria-label="Users pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=users&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=users&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=users&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_user">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password" required>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-select">
                                          <option value="customer" selected>Customer</option>
                                          <option value="admin">Admin</option>
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