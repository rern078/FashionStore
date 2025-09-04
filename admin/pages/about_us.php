<?php
require_once __DIR__ . '/../config/function.php';

// Ensure a single row exists. Create one if table is empty.
$existing = db_one('SELECT id, title, content, image_url, created_at, updated_at FROM about_us ORDER BY id ASC LIMIT 1');
if (!$existing) {
      db_exec('INSERT INTO about_us (title, content, image_url) VALUES (?, ?, ?)', [null, null, null]);
      $existing = db_one('SELECT id, title, content, image_url, created_at, updated_at FROM about_us ORDER BY id ASC LIMIT 1');
}

// Handle update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'save_about') {
      $id = (int)($existing['id'] ?? 0);
      $title = trim($_POST['title'] ?? '');
      $content = trim($_POST['content'] ?? '');
      $imageUrl = trim($_POST['image_url'] ?? '');
      if ($id > 0) {
            db_exec('UPDATE about_us SET title = ?, content = ?, image_url = ? WHERE id = ?', [
                  $title !== '' ? $title : null,
                  $content !== '' ? $content : null,
                  $imageUrl !== '' ? $imageUrl : null,
                  $id,
            ]);
            header('Location: /admin/?p=about_us&saved=1');
            exit;
      }
}
?>

<div class="page-header">
      <h3 class="page-title"> About Us </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['saved'])): ?>
      <div class="alert alert-success" role="alert">About Us saved.</div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title mb-3">Edit Content</h4>
                        <form method="post">
                              <input type="hidden" name="form" value="save_about">
                              <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars((string)($existing['title'] ?? ''), ENT_QUOTES); ?>">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="text" class="form-control" name="image_url" value="<?php echo htmlspecialchars((string)($existing['image_url'] ?? ''), ENT_QUOTES); ?>">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars((string)($existing['content'] ?? ''), ENT_QUOTES); ?></textarea>
                              </div>
                              <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary">Save</button>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>