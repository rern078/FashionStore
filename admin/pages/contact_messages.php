<?php
require_once __DIR__ . '/../config/function.php';

// Bulk actions: mark read/archived, delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form'])) {
      $form = $_POST['form'];
      if ($form === 'update_status') {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'new';
            if ($id > 0 && in_array($status, ['new', 'read', 'archived'], true)) {
                  db_exec('UPDATE contact_messages SET status = ? WHERE id = ?', [$status, $id]);
            }
            header('Location: /admin/?p=contact_messages&updated=1');
            exit;
      }
      if ($form === 'delete_message') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                  db_exec('DELETE FROM contact_messages WHERE id = ?', [$id]);
            }
            header('Location: /admin/?p=contact_messages&deleted=1');
            exit;
      }
}

// Filters and pagination
$statusFilter = isset($_GET['status']) && in_array($_GET['status'], ['new', 'read', 'archived'], true) ? $_GET['status'] : '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$limit = (int)$perPage;
$off = (int)$offset;

if ($statusFilter !== '') {
      $totalRow = db_one('SELECT COUNT(*) AS c FROM contact_messages WHERE status = ?', [$statusFilter]);
      $rows = db_all("SELECT * FROM contact_messages WHERE status = ? ORDER BY id DESC LIMIT $limit OFFSET $off", [$statusFilter]);
} else {
      $totalRow = db_one('SELECT COUNT(*) AS c FROM contact_messages');
      $rows = db_all("SELECT * FROM contact_messages ORDER BY id DESC LIMIT $limit OFFSET $off");
}
$total = (int)($totalRow['c'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
?>

<div class="page-header">
      <h3 class="page-title"> Contact Messages </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success" role="alert">Message updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Message deleted.</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
      <form class="d-flex" method="get">
            <input type="hidden" name="p" value="contact_messages">
            <select class="form-select" name="status" onchange="this.form.submit()" style="width: 220px;">
                  <option value="" <?php echo $statusFilter === '' ? 'selected' : ''; ?>>All statuses</option>
                  <option value="new" <?php echo $statusFilter === 'new' ? 'selected' : ''; ?>>New</option>
                  <option value="read" <?php echo $statusFilter === 'read' ? 'selected' : ''; ?>>Read</option>
                  <option value="archived" <?php echo $statusFilter === 'archived' ? 'selected' : ''; ?>>Archived</option>
            </select>
      </form>
</div>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Subject</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r): ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['name'], ENT_QUOTES); ?></td>
                                                      <td><a href="mailto:<?php echo htmlspecialchars((string)$r['email'], ENT_QUOTES); ?>"><?php echo htmlspecialchars((string)$r['email'], ENT_QUOTES); ?></a></td>
                                                      <td><?php echo htmlspecialchars((string)$r['phone'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['subject'], ENT_QUOTES); ?></td>
                                                      <td style="max-width:280px;">
                                                            <div class="text-truncate" style="max-width:280px;"><?php echo htmlspecialchars((string)$r['message'], ENT_QUOTES); ?></div>
                                                            <?php if (!empty($r['attachment_url'] ?? '')): ?>
                                                                  <div class="mt-1">
                                                                        <a href="/<?php echo htmlspecialchars((string)$r['attachment_url'], ENT_QUOTES); ?>" target="_blank" class="badge badge-outline-primary">Attachment</a>
                                                                  </div>
                                                            <?php endif; ?>
                                                      </td>
                                                      <td><?php echo htmlspecialchars((string)$r['status'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['created_at'], ENT_QUOTES); ?></td>
                                                      <td>
                                                            <div class="btn-group">
                                                                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewMessageModal-<?php echo (int)$r['id']; ?>">View</button>
                                                                  <div class="dropdown">
                                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                              Status
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                              <?php foreach (['new', 'read', 'archived'] as $st): ?>
                                                                                    <li>
                                                                                          <form method="post" class="px-3 py-1">
                                                                                                <input type="hidden" name="form" value="update_status">
                                                                                                <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                                                <input type="hidden" name="status" value="<?php echo $st; ?>">
                                                                                                <button type="submit" class="dropdown-item <?php echo ($r['status'] === $st) ? 'active' : ''; ?>">Mark <?php echo ucfirst($st); ?></button>
                                                                                          </form>
                                                                                    </li>
                                                                              <?php endforeach; ?>
                                                                        </ul>
                                                                  </div>
                                                                  <form method="post" onsubmit="return confirm('Delete this message?');">
                                                                        <input type="hidden" name="form" value="delete_message">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                                  </form>
                                                            </div>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="viewMessageModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-labelledby="viewMessageModalLabel-<?php echo (int)$r['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewMessageModalLabel-<?php echo (int)$r['id']; ?>">Message #<?php echo (int)$r['id']; ?></h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                        <dl class="row">
                                                                              <dt class="col-sm-3">Name</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['name'], ENT_QUOTES); ?></dd>
                                                                              <dt class="col-sm-3">Email</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['email'], ENT_QUOTES); ?></dd>
                                                                              <dt class="col-sm-3">Phone</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['phone'], ENT_QUOTES); ?></dd>
                                                                              <dt class="col-sm-3">Subject</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['subject'], ENT_QUOTES); ?></dd>
                                                                              <dt class="col-sm-3">Message</dt>
                                                                              <dd class="col-sm-9">
                                                                                    <pre class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars((string)$r['message'], ENT_QUOTES); ?></pre>
                                                                              </dd>
                                                                              <dt class="col-sm-3">Status</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['status'], ENT_QUOTES); ?></dd>
                                                                              <dt class="col-sm-3">Created</dt>
                                                                              <dd class="col-sm-9"><?php echo htmlspecialchars((string)$r['created_at'], ENT_QUOTES); ?></dd>
                                                                        </dl>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                  </div>
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
      <nav aria-label="Contact messages pagination">
            <ul class="pagination">
                  <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=contact_messages&status=<?php echo urlencode($statusFilter); ?>&page=<?php echo max(1, $page - 1); ?>">Previous</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                              <a class="page-link" href="/admin/?p=contact_messages&status=<?php echo urlencode($statusFilter); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="/admin/?p=contact_messages&status=<?php echo urlencode($statusFilter); ?>&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                  </li>
            </ul>
      </nav>
<?php endif; ?>