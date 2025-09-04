<?php
require_once __DIR__ . '/../config/function.php';

$cartId = (int)($_GET['cart_id'] ?? 0);
$carts = db_all('SELECT id, session_id FROM carts ORDER BY id DESC');
$variants = db_all('SELECT v.id, v.sku, p.title AS product_title FROM variants v JOIN products p ON p.id=v.product_id ORDER BY v.id DESC');

// Add item
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_cart_item') {
      $cart = (int)($_POST['cart_id'] ?? 0);
      $variantId = (int)($_POST['variant_id'] ?? 0);
      $qty = max(1, (int)($_POST['qty'] ?? 1));
      $unitPrice = isset($_POST['unit_price']) ? (float)$_POST['unit_price'] : 0.0;

      if ($cart <= 0 || $variantId <= 0 || $unitPrice <= 0) {
            header('Location: /admin/?p=cart_items&cart_id=' . $cart . '&error=Invalid%20data');
            exit;
      }

      db_exec('INSERT INTO cart_items (cart_id, variant_id, qty, unit_price) VALUES (?, ?, ?, ?)', [$cart, $variantId, $qty, $unitPrice]);
      header('Location: /admin/?p=cart_items&cart_id=' . $cart . '&added=1');
      exit;
}

// Delete item
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'delete_cart_item') {
      $id = (int)($_POST['id'] ?? 0);
      $cart = (int)($_POST['cart_id'] ?? 0);
      if ($id > 0) {
            db_exec('DELETE FROM cart_items WHERE id = ?', [$id]);
      }
      header('Location: /admin/?p=cart_items&cart_id=' . $cart . '&deleted=1');
      exit;
}

// Items list (scoped by cart if provided)
if ($cartId > 0) {
      $items = db_all('SELECT ci.id, ci.qty, ci.unit_price, v.sku, p.title AS product_title FROM cart_items ci JOIN variants v ON v.id=ci.variant_id JOIN products p ON p.id=v.product_id WHERE ci.cart_id=? ORDER BY ci.id DESC', [$cartId]);
} else {
      $items = db_all('SELECT ci.id, ci.cart_id, ci.qty, ci.unit_price, v.sku, p.title AS product_title FROM cart_items ci JOIN variants v ON v.id=ci.variant_id JOIN products p ON p.id=v.product_id ORDER BY ci.id DESC');
}
?>

<div class="page-header">
      <h3 class="page-title"> Cart Items </h3>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Sales</a></li>
                  <li class="breadcrumb-item"><a href="/admin/?p=carts">Carts</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Items</li>
            </ol>
      </nav>
</div>

<?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success" role="alert">Item added.</div>
<?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?>
      <div class="alert alert-success" role="alert">Item deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
<?php endif; ?>

<div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                              <h4 class="card-title mb-0">Items<?php echo $cartId ? (' for Cart #' . (int)$cartId) : ''; ?></h4>
                              <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#addCartItemModal">Add Item</button>
                        </div>
                        <div class="table-responsive">
                              <table class="table" id="cart-items-table">
                                    <thead>
                                          <tr>
                                                <th>ID</th>
                                                <?php if (!$cartId): ?><th>Cart</th><?php endif; ?>
                                                <th>Product</th>
                                                <th>Variant SKU</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php foreach ($items as $it): ?>
                                                <tr>
                                                      <td><?php echo (int)$it['id']; ?></td>
                                                      <?php if (!$cartId): ?><td><?php echo (int)($it['cart_id'] ?? $cartId); ?></td><?php endif; ?>
                                                      <td><?php echo htmlspecialchars($it['product_title'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo htmlspecialchars($it['sku'] ?? '', ENT_QUOTES); ?></td>
                                                      <td><?php echo (int)$it['qty']; ?></td>
                                                      <td><?php echo number_format((float)$it['unit_price'], 2); ?></td>
                                                      <td>
                                                            <form method="post" style="display:inline-block" onsubmit="return confirm('Delete this item?');">
                                                                  <input type="hidden" name="form" value="delete_cart_item">
                                                                  <input type="hidden" name="id" value="<?php echo (int)$it['id']; ?>">
                                                                  <input type="hidden" name="cart_id" value="<?php echo (int)$cartId; ?>">
                                                                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                          <?php endforeach; ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>

<div class="modal fade" id="addCartItemModal" tabindex="-1" aria-labelledby="addCartItemModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="addCartItemModalLabel">Add Cart Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post">
                        <input type="hidden" name="form" value="add_cart_item">
                        <div class="modal-body">
                              <div class="mb-3">
                                    <label class="form-label">Cart *</label>
                                    <select class="form-select" name="cart_id" required>
                                          <?php if ($cartId > 0): ?>
                                                <option value="<?php echo (int)$cartId; ?>" selected>Cart #<?php echo (int)$cartId; ?></option>
                                          <?php else: ?>
                                                <option value="">— Select Cart —</option>
                                                <?php foreach ($carts as $c): ?>
                                                      <option value="<?php echo (int)$c['id']; ?>"><?php echo 'Cart #' . (int)$c['id'] . ' — ' . htmlspecialchars($c['session_id'] ?? '', ENT_QUOTES); ?></option>
                                                <?php endforeach; ?>
                                          <?php endif; ?>
                                    </select>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Variant *</label>
                                    <select class="form-select" name="variant_id" required>
                                          <option value="">— Select Variant —</option>
                                          <?php foreach ($variants as $v): ?>
                                                <option value="<?php echo (int)$v['id']; ?>"><?php echo htmlspecialchars(($v['product_title'] ?? '') . ' — ' . ($v['sku'] ?? ''), ENT_QUOTES); ?></option>
                                          <?php endforeach; ?>
                                    </select>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 mb-3">
                                          <label class="form-label">Qty</label>
                                          <input type="number" step="1" min="1" name="qty" class="form-control" value="1">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                          <label class="form-label">Unit Price</label>
                                          <input type="number" step="0.01" min="0" name="unit_price" class="form-control" value="0.00">
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-gradient-primary">Add</button>
                        </div>
                  </form>
            </div>
      </div>
</div>