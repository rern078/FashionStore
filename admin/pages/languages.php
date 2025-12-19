<?php
require_once __DIR__ . '/../config/function.php';

// Create (Add Language)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_language') {
      $code = strtolower(trim((string)($_POST['code'] ?? '')));
      $name = trim((string)($_POST['name'] ?? ''));
      $nativeName = trim((string)($_POST['native_name'] ?? ''));
      $position = (int)($_POST['position'] ?? 1);
      $isActive = isset($_POST['is_active']) ? 1 : 0;

      if ($code === '' || $name === '') {
            header('Location: /admin/?p=languages&error=Invalid%20code%20or%20name');
            exit;
      }

      // prevent duplicate codes
      $dup = db_one('SELECT id FROM languages WHERE code = ?', [$code]);
      if ($dup) {
            header('Location: /admin/?p=languages&error=Language%20code%20already%20exists');
            exit;
      }

      db_exec('INSERT INTO languages (code, name, native_name, position, is_active) VALUES (?, ?, ?, ?, ?)', [
            substr($code, 0, 10),
            substr($name, 0, 100),
            $nativeName !== '' ? substr($nativeName, 0, 100) : null,
            max(1, $position),
            $isActive,
      ]);

      header('Location: /admin/?p=languages&added=1');
      exit;
}

// Bulk Save Active toggles
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'save_languages') {
      $enabledIds = isset($_POST['enabled']) && is_array($_POST['enabled']) ? $_POST['enabled'] : [];
      $enabledIds = array_values(array_unique(array_map(static function ($v) {
            return (int)$v;
      }, $enabledIds)));

      // Fetch all ids to scope updates
      $allRows = db_all('SELECT id FROM languages');
      $allIds = array_map(static function ($r) {
            return (int)$r['id'];
      }, $allRows);

      // Mark active for selected, inactive for others
      if ($allIds) {
            if ($enabledIds) {
                  // Set selected to active
                  $inPlaceholders = implode(',', array_fill(0, count($enabledIds), '?'));
                  db_exec('UPDATE languages SET is_active = 1 WHERE id IN (' . $inPlaceholders . ')', $enabledIds);

                  // Set others to inactive
                  // Compute remaining
                  $remaining = array_values(array_diff($allIds, $enabledIds));
                  if ($remaining) {
                        $remPlaceholders = implode(',', array_fill(0, count($remaining), '?'));
                        db_exec('UPDATE languages SET is_active = 0 WHERE id IN (' . $remPlaceholders . ')', $remaining);
                  }
            } else {
                  // If nothing checked, set all to inactive
                  db_exec('UPDATE languages SET is_active = 0');
            }
      }

      header('Location: /admin/?p=languages&saved=1');
      exit;
}

// Read search query (GET)
$q = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
      $q = trim((string)($_GET['q'] ?? ''));
}

// Load languages from DB (optionally filtered by search query)
$rows = [];
$tableError = false;
try {
      if ($q !== '') {
            $like = '%' . $q . '%';
            $rows = db_all(
                  'SELECT id, code, name, native_name, position, is_active
                   FROM languages
                   WHERE code LIKE ? OR name LIKE ? OR native_name LIKE ?
                   ORDER BY position ASC, id ASC',
                  [$like, $like, $like]
            );
      } else {
            $rows = db_all('SELECT id, code, name, native_name, position, is_active FROM languages ORDER BY position ASC, id ASC');
      }
} catch (Throwable $e) {
      $tableError = true;
      $rows = [];
}

// Compute next position default using total languages count
$nextPosition = 1;
try {
      $countRow = db_one('SELECT COUNT(*) AS c FROM languages');
      $totalCount = (int)($countRow['c'] ?? 0);
      $nextPosition = $totalCount + 1;
} catch (Throwable $e) {
      // Fallback to visible rows count if total count query fails
      $nextPosition = count($rows) + 1;
}

?>

<div class="page-header">
      <h3 class="page-title"> Languages </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Configuration</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Languages</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Language created.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
      <div class="alert alert-success" role="alert">Languages updated.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>
<?php if ($tableError): ?>
      <div class="alert alert-warning" role="alert">
            The <code>languages</code> table was not found. Please create it:
            <pre class="mb-0" style="white-space:pre-wrap">CREATE TABLE languages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(10) NOT NULL,
  name VARCHAR(100) NOT NULL,
  native_name VARCHAR(100) NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_languages_code (code),
  INDEX idx_languages_active_position (is_active, position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;</pre>
      </div>
<?php endif; ?>

<style>
      /* Compact yes/no pill switch, matching screenshot */
      .lang-list {
            max-width: 100%;
            display: grid;
            grid-template-columns: repeat(1, minmax(220px, 1fr));
            gap: 14px 24px;
      }

      .lang-item {
            display: flex;
            align-items: center;
            margin: 0;
      }

      .lang-item .lang-label {
            margin-left: 12px;
            color: #6c7a89;
            font-weight: 500;
      }

      .lang-checkbox {
            display: none;
      }

      .lang-switch {
            position: relative;
            width: 82px;
            height: 34px;
            border-radius: 17px;
            background: #d7dde5;
            cursor: pointer;
            display: inline-block;
            transition: background-color .2s ease;
      }

      .lang-switch .knob {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #a6b1bf;
            transition: left .18s ease, background-color .18s ease;
      }

      .lang-switch .text {
            position: absolute;
            top: 7px;
            left: 42px;
            font-size: 12px;
            font-weight: 700;
            color: #8a97a6;
            pointer-events: none;
            line-height: 20px;
            text-transform: uppercase;
      }

      .lang-switch .text::before {
            content: 'no';
      }

      .lang-checkbox:checked+.lang-switch {
            background: #2e3f53;
      }

      .lang-checkbox:checked+.lang-switch .knob {
            left: 52px;
            background: #2ad7c1;
      }

      .lang-checkbox:checked+.lang-switch .text {
            left: 12px;
            color: #eaf6ff;
      }

      .lang-checkbox:checked+.lang-switch .text::before {
            content: 'yes';
      }

      /* Responsive columns: 1 -> 2 -> 3 -> 4 */
      @media (min-width: 576px) {
            .lang-list {
                  grid-template-columns: repeat(2, minmax(220px, 1fr));
            }
      }

      @media (min-width: 992px) {
            .lang-list {
                  grid-template-columns: repeat(3, minmax(220px, 1fr));
            }
      }

      @media (min-width: 1200px) {
            .lang-list {
                  grid-template-columns: repeat(4, minmax(220px, 1fr));
            }
      }

      /* Header search alignment and seamless button join */
      .search-actions .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
      }

      .search-actions .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
      }
      .btn-clear-search,
      .btn-add-language{
            height: 46px;
            margin-top: -16px
      }
</style>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 search-actions">
                              <h4 class="card-title mb-0">Enable Languages</h4>
                              <div class="d-flex align-items-center gap-2">
                                    <form method="get" class="me-0" autocomplete="off">
                                          <input type="hidden" name="p" value="languages">
                                          <div class="input-group" style="min-width: 280px;">
                                                <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" placeholder="Search languages...">
                                                <button type="submit" class="btn btn-gradient-primary">Search</button>
                                          </div>
                                    </form>
                                    <?php if ($q !== ''): ?>
                                          <a class="btn btn-outline-secondary btn-clear-search" href="/admin/?p=languages">Clear</a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-gradient-primary btn-add-language" data-bs-toggle="modal" data-bs-target="#addLanguageModal">Add Language</button>
                              </div>
                        </div>
                        <p class="card-description">Toggle languages on or off, then save.</p>

                        <form method="post" autocomplete="off">
                              <input type="hidden" name="form" value="save_languages">
                              <div class="lang-list">
                                    <?php foreach ($rows as $r): ?>
                                          <?php
                                          $id = (int)$r['id'];
                                          $label = (string)($r['name'] !== '' ? $r['name'] : $r['code']);
                                          $checked = ((int)$r['is_active']) === 1;
                                          $inputId = 'lang_' . $id;
                                          ?>
                                          <div class="lang-item">
                                                <input class="lang-checkbox" type="checkbox" id="<?php echo htmlspecialchars($inputId, ENT_QUOTES); ?>" name="enabled[]" value="<?php echo $id; ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                                <label class="lang-switch" for="<?php echo htmlspecialchars($inputId, ENT_QUOTES); ?>">
                                                      <span class="knob"></span>
                                                      <span class="text"></span>
                                                </label>
                                                <span class="lang-label"><?php echo htmlspecialchars($label, ENT_QUOTES); ?></span>
                                          </div>
                                    <?php endforeach; ?>
                                    <?php if (!$rows && !$tableError): ?>
                                          <?php if ($q !== ''): ?>
                                                <div class="text-muted">No languages match "<?php echo htmlspecialchars($q, ENT_QUOTES); ?>".</div>
                                          <?php else: ?>
                                                <div class="text-muted">No languages yet. Add one to get started.</div>
                                          <?php endif; ?>
                                    <?php endif; ?>
                              </div>

                              <div class="mt-3">
                                    <button type="submit" class="btn btn-gradient-primary">Save Changes</button>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addLanguageModalLabel">Add Language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_language">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Code *</label>
                                    <input type="text" class="form-control" name="code" maxlength="10" required placeholder="e.g., en, kh, cn">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required placeholder="English">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Native Name</label>
                                    <input type="text" class="form-control" name="native_name" placeholder="English / Khmer / 中文">
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="number" class="form-control" name="position" min="1" value="<?php echo (int)$nextPosition; ?>">
                              </div>
                              <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new_lang" checked>
                                    <label class="form-check-label" for="is_active_new_lang">Active</label>
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