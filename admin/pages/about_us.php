<?php
require_once __DIR__ . '/../config/function.php';

// Helpers
function about_get(int $id): ?array
{
      return db_one('SELECT id, title, content, image_url, created_at, updated_at FROM about_us WHERE id = ?', [$id]);
}

function about_list(): array
{
      return db_all('SELECT id, title, content, image_url, created_at, updated_at FROM about_us ORDER BY id DESC');
}

// Handle delete (by id)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'delete_about') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) {
            $row = about_get($id);
            if ($row) {
                  if (!empty($row['image_url'])) {
                        $imgPath = __DIR__ . '/../' . ltrim((string)$row['image_url'], '/');
                        if (is_file($imgPath)) {
                              @unlink($imgPath);
                        }
                  }
                  db_exec('DELETE FROM about_us WHERE id = ?', [$id]);
            }
      }
      header('Location: /admin/?p=about_us&deleted=1');
      exit;
}

// Handle create/update save
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['form']) && $_POST['form'] === 'save_about') {
      $id = (int)($_POST['id'] ?? 0);
      $title = trim($_POST['title'] ?? '');
      $content = trim($_POST['content'] ?? '');
      $imageUrlText = trim($_POST['image_url'] ?? '');
      $removeImage = isset($_POST['remove_image']) && $_POST['remove_image'] === '1';

      $currentImage = null;
      if ($id > 0) {
            $existing = about_get($id);
            $currentImage = $existing['image_url'] ?? null;
      }

      $finalImage = $currentImage;

      if ($id > 0 && $removeImage && !empty($currentImage)) {
            $rel = ltrim((string)$currentImage, '/');
            $base = (strpos($rel, 'admin/') === 0) ? (__DIR__ . '/../../') : (__DIR__ . '/../');
            $path = $base . $rel;
            if (is_file($path)) {
                  @unlink($path);
            }
            $finalImage = null;
      }

      // Upload new image if provided
      $newImagePath = null;
      if (!empty($_FILES['image_file']['name'] ?? '')) {
            $tmp = $_FILES['image_file']['tmp_name'] ?? '';
            $name = basename((string)($_FILES['image_file']['name'] ?? ''));
            $ext = strtolower((string)pathinfo($name, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                  $uploadDir = __DIR__ . '/../assets/images/content';
                  if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0775, true);
                  }
                  $safeName = 'about_' . ($id > 0 ? $id . '_' : '') . bin2hex(random_bytes(4)) . '.' . $ext;
                  $dest = $uploadDir . '/' . $safeName;
                  if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                        $newImagePath = '/admin/assets/images/content/' . $safeName;
                  }
            }
      }

      // If creating, insert first (without image), then update with image if any
      if ($id === 0) {
            db_exec('INSERT INTO about_us (title, content, image_url) VALUES (?, ?, ?)', [
                  $title !== '' ? $title : null,
                  $content !== '' ? $content : null,
                  null,
            ]);
            $id = db_last_insert_id();
            if ($newImagePath !== null) {
                  $finalImage = $newImagePath;
            } elseif ($imageUrlText !== '') {
                  $finalImage = $imageUrlText;
            } else {
                  $finalImage = null;
            }
            db_exec('UPDATE about_us SET image_url = ? WHERE id = ?', [
                  $finalImage !== '' ? $finalImage : null,
                  $id,
            ]);
            header('Location: /admin/?p=about_us&created=1');
            exit;
      }

      // Updating existing
      if ($newImagePath !== null) {
            if (!empty($finalImage)) {
                  $relOld = ltrim((string)$finalImage, '/');
                  $baseOld = (strpos($relOld, 'admin/') === 0) ? (__DIR__ . '/../../') : (__DIR__ . '/../');
                  $oldPath = $baseOld . $relOld;
                  if (is_file($oldPath)) {
                        @unlink($oldPath);
                  }
            }
            $finalImage = $newImagePath;
      } elseif ($imageUrlText !== '') {
            $finalImage = $imageUrlText;
      }

      db_exec('UPDATE about_us SET title = ?, content = ?, image_url = ? WHERE id = ?', [
            $title !== '' ? $title : null,
            $content !== '' ? $content : null,
            $finalImage !== '' ? $finalImage : null,
            $id,
      ]);
      header('Location: /admin/?p=about_us&saved=1');
      exit;
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
      <div class="alert alert-success" role="alert">About Us entry saved.</div>
<?php endif; ?>
<?php if (!empty($_GET['created'])): ?>
      <div class="alert alert-success" role="alert">About Us entry created.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-warning" role="alert">About Us entry deleted.</div>
<?php endif; ?>

<?php
$mode = 'list';
$editId = 0;
if (isset($_GET['action']) && $_GET['action'] === 'create') {
      $mode = 'create';
} elseif (isset($_GET['edit'])) {
      $editId = (int)$_GET['edit'];
      if ($editId > 0) {
            $mode = 'edit';
      }
}

$editing = null;
if ($mode === 'edit') {
      $editing = about_get($editId);
      if (!$editing) {
            $mode = 'list';
      }
}
?>

<?php if ($mode === 'list'): ?>
      <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                        <div class="card-body">
                              <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0">About Us Entries</h4>
                                    <a href="/admin/?p=about_us&action=create" class="btn btn-gradient-primary">Add New</a>
                              </div>
                              <?php $rows = about_list(); ?>
                              <div class="table-responsive">
                                    <table class="table">
                                          <thead>
                                                <tr>
                                                      <th>ID</th>
                                                      <th>Title</th>
                                                      <th>Content</th>
                                                      <th>Image</th>
                                                      <th>Updated</th>
                                                      <th>Actions</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                <?php if (empty($rows)) { ?>
                                                      <tr>
                                                            <td colspan="5" class="text-center text-muted">No entries yet. Click "Add New" to create one.</td>
                                                      </tr>
                                                <?php } else { ?>
                                                      <?php foreach ($rows as $row) { ?>
                                                            <tr>
                                                                  <td><?php echo (int)$row['id']; ?></td>
                                                                  <td><?php echo htmlspecialchars((string)($row['title'] ?? ''), ENT_QUOTES); ?></td>
                                                                  <td><?php echo htmlspecialchars((string)($row['content'] ?? ''), ENT_QUOTES); ?></td>
                                                                  <td>
                                                                        <?php if (!empty($row['image_url'])) { ?>
                                                                              <img src="<?php echo htmlspecialchars((string)$row['image_url'], ENT_QUOTES); ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;" />
                                                                        <?php } else { ?>
                                                                              <span class="text-muted">-</span>
                                                                        <?php } ?>
                                                                  </td>
                                                                  <td><small class="text-muted"><?php echo htmlspecialchars((string)($row['updated_at'] ?? ''), ENT_QUOTES); ?></small></td>
                                                                  <td>
                                                                        <a href="/admin/?p=about_us&edit=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this entry?');">
                                                                              <input type="hidden" name="form" value="delete_about">
                                                                              <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                                                                              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                                        </form>
                                                                  </td>
                                                            </tr>
                                                      <?php } ?>
                                                <?php } ?>
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
<?php else: ?>
      <?php $isEdit = ($mode === 'edit'); ?>
      <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                        <div class="card-body">
                              <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0"><?php echo $isEdit ? 'Edit Entry' : 'Create Entry'; ?></h4>
                                    <a href="/admin/?p=about_us" class="btn btn-outline-secondary">Back</a>
                              </div>
                              <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form" value="save_about">
                                    <input type="hidden" name="id" value="<?php echo $isEdit ? (int)$editing['id'] : 0; ?>">
                                    <div class="mb-3">
                                          <label class="form-label">Title</label>
                                          <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars((string)($isEdit ? ($editing['title'] ?? '') : ''), ENT_QUOTES); ?>">
                                    </div>
                                    <?php if ($isEdit) { ?>
                                          <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div>
                                                      <?php if (!empty($editing['image_url'])) { ?>
                                                            <img src="<?php echo htmlspecialchars((string)$editing['image_url'], ENT_QUOTES); ?>" alt="" style="width:120px;height:120px;object-fit:cover;border-radius:4px;" />
                                                      <?php } else { ?>
                                                            <span class="text-muted">No image</span>
                                                      <?php } ?>
                                                </div>
                                          </div>
                                    <?php } ?>
                                    <div class="mb-3">
                                          <label class="form-label">Upload Image</label>
                                          <input type="file" class="form-control" name="image_file" accept="image/*">
                                          <small class="text-muted">Optional. Upload to set/replace image.</small>
                                    </div>
                                    <div class="mb-3">
                                          <label class="form-label">External Image URL (optional)</label>
                                          <input type="text" class="form-control" name="image_url" value="<?php echo htmlspecialchars((string)($isEdit ? ($editing['image_url'] ?? '') : ''), ENT_QUOTES); ?>" placeholder="https://...">
                                          <small class="text-muted">If provided and no upload is selected, this will be used.</small>
                                    </div>
                                    <?php if ($isEdit && !empty($editing['image_url'])) { ?>
                                          <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1" id="removeImageCheck" name="remove_image">
                                                <label class="form-check-label" for="removeImageCheck">Remove current image</label>
                                          </div>
                                    <?php } ?>
                                    <div class="mb-3">
                                          <label class="form-label">Content</label>
                                          <textarea class="form-control" name="content" rows="10"><?php echo htmlspecialchars((string)($isEdit ? ($editing['content'] ?? '') : ''), ENT_QUOTES); ?></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                          <button type="submit" class="btn btn-gradient-primary"><?php echo $isEdit ? 'Save Changes' : 'Create'; ?></button>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
      </div>
<?php endif; ?>