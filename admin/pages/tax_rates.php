<?php
require_once __DIR__ . '/../config/function.php';

// Create
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_tax') {
      $name = trim((string)($_POST['name'] ?? ''));
      $country = strtoupper(trim((string)($_POST['country'] ?? '')));
      $state = trim((string)($_POST['state'] ?? ''));
      $city = trim((string)($_POST['city'] ?? ''));
      $postal = trim((string)($_POST['postal'] ?? ''));
      $rate = (float)($_POST['rate_percent'] ?? 0);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
      if ($name === '' || $rate < 0) {
            header('Location: /admin/?p=tax_rates&error=Invalid%20data');
            exit;
      }
      db_exec('INSERT INTO tax_rates (name, country, state, city, postal, rate_percent, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
            substr($name, 0, 150),
            $country !== '' ? substr($country, 0, 2) : null,
            $state !== '' ? substr($state, 0, 100) : null,
            $city !== '' ? substr($city, 0, 100) : null,
            $postal !== '' ? substr($postal, 0, 20) : null,
            $rate,
            $isActive,
            $sortOrder,
      ]);
      header('Location: /admin/?p=tax_rates&added=1');
      exit;
}

// Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_tax') {
      $id = (int)($_POST['id'] ?? 0);
      $name = trim((string)($_POST['name'] ?? ''));
      $country = strtoupper(trim((string)($_POST['country'] ?? '')));
      $state = trim((string)($_POST['state'] ?? ''));
      $city = trim((string)($_POST['city'] ?? ''));
      $postal = trim((string)($_POST['postal'] ?? ''));
      $rate = (float)($_POST['rate_percent'] ?? 0);
      $isActive = isset($_POST['is_active']) ? 1 : 0;
      $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
      if ($id <= 0 || $name === '' || $rate < 0) {
            header('Location: /admin/?p=tax_rates&error=Invalid%20data');
            exit;
      }
      db_exec('UPDATE tax_rates SET name=?, country=?, state=?, city=?, postal=?, rate_percent=?, is_active=?, sort_order=? WHERE id=?', [
            substr($name, 0, 150),
            $country !== '' ? substr($country, 0, 2) : null,
            $state !== '' ? substr($state, 0, 100) : null,
            $city !== '' ? substr($city, 0, 100) : null,
            $postal !== '' ? substr($postal, 0, 20) : null,
            $rate,
            $isActive,
            $sortOrder,
            $id,
      ]);
      header('Location: /admin/?p=tax_rates&updated=1');
      exit;
}

// Delete
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_tax') {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0) db_exec('DELETE FROM tax_rates WHERE id=?', [$id]);
      header('Location: /admin/?p=tax_rates&deleted=1');
      exit;
}

$rows = db_all('SELECT id, name, country, state, city, postal, rate_percent, is_active, sort_order FROM tax_rates ORDER BY sort_order ASC, id DESC');
?>

<div class="page-header">
      <h3 class="page-title"> Tax Rates </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tax Rates</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?><div class="alert alert-success">Tax rate created.</div><?php endif; ?>
<?php if (!empty($_GET['updated'])): ?><div class="alert alert-success">Tax rate updated.</div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Tax rate deleted.</div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div><?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">All Tax Rates</h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addTaxModal">Add Tax</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Country</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Postal</th>
                                                <th>Rate %</th>
                                                <th>Active</th>
                                                <th>Sort</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($rows as $r) { ?>
                                                <tr>
                                                      <td><?php echo (int)$r['id']; ?></td>
                                                      <td><?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['country'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['state'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['city'], ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars((string)$r['postal'], ENT_QUOTES); ?></td>
                                                      <td><?php echo number_format((float)$r['rate_percent'], 3); ?></td>
                                                      <td><?php echo (int)$r['is_active'] === 1 ? 'Yes' : 'No'; ?></td>
                                                      <td><?php echo (int)$r['sort_order']; ?></td>
                                                      <td>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTaxModal-<?php echo (int)$r['id']; ?>">Edit</button>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this tax rate?');">
                                                                  <input type="hidden" name="form" value="delete_tax">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <div class="modal fade" id="editTaxModal-<?php echo (int)$r['id']; ?>" tabindex="-1" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                  <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Tax</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                  </div>
                                                                  <form method="post">
                                                                        <input type="hidden" name="form" value="update_tax">
                                                                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                                                        <div class="modal-body">
                                                                              <div class="mb-3"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Country</label><input type="text" maxlength="2" class="form-control" name="country" value="<?php echo htmlspecialchars((string)$r['country'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">State</label><input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars((string)$r['state'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">City</label><input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars((string)$r['city'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Postal</label><input type="text" class="form-control" name="postal" value="<?php echo htmlspecialchars((string)$r['postal'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Rate Percent *</label><input type="number" step="0.001" min="0" class="form-control" name="rate_percent" required value="<?php echo htmlspecialchars((string)$r['rate_percent'], ENT_QUOTES); ?>"></div>
                                                                              <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" min="0" class="form-control" name="sort_order" value="<?php echo (int)$r['sort_order']; ?>"></div>
                                                                              <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" id="is_active_tax_<?php echo (int)$r['id']; ?>" <?php echo ((int)$r['is_active'] === 1) ? 'checked' : ''; ?>><label class="form-check-label" for="is_active_tax_<?php echo (int)$r['id']; ?>">Active</label></div>
                                                                        </div>
                                                                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Save</button></div>
                                                                  </form>
                                                            </div>
                                                      </div>
                                                </div>
                                          <?php } ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="addTaxModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Add Tax</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_tax">
                        <div class="modal-body">
                              <div class="mb-3"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" required></div>
                              <div class="mb-3"><label class="form-label">Country</label><input type="text" maxlength="2" class="form-control" name="country" placeholder="US"></div>
                              <div class="mb-3"><label class="form-label">State</label><input type="text" class="form-control" name="state"></div>
                              <div class="mb-3"><label class="form-label">City</label><input type="text" class="form-control" name="city"></div>
                              <div class="mb-3"><label class="form-label">Postal</label><input type="text" class="form-control" name="postal"></div>
                              <div class="mb-3"><label class="form-label">Rate Percent *</label><input type="number" step="0.001" min="0" class="form-control" name="rate_percent" required value="0.000"></div>
                              <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" min="0" class="form-control" name="sort_order" value="0"></div>
                              <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" id="is_active_tax_new" checked><label class="form-check-label" for="is_active_tax_new">Active</label></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Create</button></div>
                  </form>
            </div>
      </div>
</div>