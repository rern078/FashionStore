<?php
// Handle Add to Cart
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'add_to_cart') {
      $productId = (int)($_POST['product_id'] ?? 0);
      $color = trim((string)($_POST['color'] ?? ''));
      $size = trim((string)($_POST['size'] ?? ''));
      $qty = max(1, (int)($_POST['qty'] ?? 1));

      if ($productId > 0 && $qty > 0) {
            // Ensure session cart exists
            $sid = session_id();
            if ($sid === '') {
                  session_start();
                  $sid = session_id();
            }

            $cart = db_one('SELECT id FROM carts WHERE session_id = ?', [$sid]);
            if (!$cart) {
                  db_exec('INSERT INTO carts (session_id, currency) VALUES (?, ?)', [$sid, 'USD']);
                  $cartId = db_last_insert_id();
            } else {
                  $cartId = (int)$cart['id'];
            }

            // Find variant by product/color/size
            $variant = db_one(
                  "SELECT id, price FROM variants\n" .
                        "WHERE product_id = ?\n" .
                        "  AND ( (size = ? AND size IS NOT NULL) OR (size_id IN (SELECT id FROM sizes WHERE label = ?) ) OR (? = '') )\n" .
                        "  AND ( (color = ? AND color IS NOT NULL) OR (color_id IN (SELECT id FROM colors WHERE name = ?) ) OR (? = '') )\n" .
                        "ORDER BY id ASC LIMIT 1",
                  [$productId, $size, $size, $size, $color, $color, $color]
            );

            if (!$variant) {
                  // fallback to any variant for the product
                  $variant = db_one('SELECT id, price FROM variants WHERE product_id = ? ORDER BY id ASC LIMIT 1', [$productId]);
            }

            if ($variant) {
                  $variantId = (int)$variant['id'];
                  $unitPrice = (float)($variant['price'] ?? 0);

                  $existing = db_one('SELECT id, qty FROM cart_items WHERE cart_id=? AND variant_id=?', [$cartId, $variantId]);
                  if ($existing) {
                        $newQty = max(1, (int)$existing['qty']) + $qty;
                        db_exec('UPDATE cart_items SET qty=?, unit_price=? WHERE id=?', [$newQty, $unitPrice, (int)$existing['id']]);
                  } else {
                        db_exec('INSERT INTO cart_items (cart_id, variant_id, qty, unit_price) VALUES (?, ?, ?, ?)', [$cartId, $variantId, $qty, $unitPrice]);
                  }

                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart&added=1'));
                  exit;
            } else {
                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart&error=No%20variant%20found'));
                  exit;
            }
      } else {
            header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart&error=Invalid%20data'));
            exit;
      }
}

// Handle quantity update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_qty') {
      $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
      $op = trim((string)($_POST['op'] ?? ''));
      $sid = session_id();
      if ($sid === '') {
            session_start();
            $sid = session_id();
      }
      $removed = false;
      if ($cartItemId > 0) {
            $row = db_one('SELECT ci.id, ci.qty, ci.cart_id FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE ci.id = ? AND c.session_id = ? LIMIT 1', [$cartItemId, $sid]);
            if ($row) {
                  $qty = max(1, (int)$row['qty']);
                  if ($op === 'inc') {
                        $qty += 1;
                  } else if ($op === 'dec') {
                        $qty -= 1;
                  } else if ($op === 'set') {
                        $qty = max(0, (int)($_POST['qty'] ?? $qty));
                  }
                  if ($qty <= 0) {
                        db_exec('DELETE FROM cart_items WHERE id=?', [$cartItemId]);
                        $removed = true;
                  } else {
                        db_exec('UPDATE cart_items SET qty=? WHERE id=?', [$qty, $cartItemId]);
                  }
                  $cartIdCurrent = (int)$row['cart_id'];
            }
      }
      // Compute new subtotal and current item totals
      $subtotal = 0.0;
      $itemQty = null;
      $itemUnit = null;
      $itemLine = null;
      $cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
      if ($cart) {
            $rows = db_all('SELECT qty, unit_price, id FROM cart_items WHERE cart_id=?', [(int)$cart['id']]);
            foreach ($rows as $r) {
                  $subtotal += (float)$r['unit_price'] * (int)$r['qty'];
            }
      }
      if (!$removed && $cartItemId > 0) {
            $ir = db_one('SELECT qty, unit_price FROM cart_items WHERE id=?', [$cartItemId]);
            if ($ir) {
                  $itemQty = (int)$ir['qty'];
                  $itemUnit = (float)$ir['unit_price'];
                  $itemLine = $itemQty * $itemUnit;
            }
      }
      if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
            header('Content-Type: application/json');
            echo json_encode([
                  'ok' => true,
                  'removed' => $removed,
                  'cart_item_id' => $cartItemId,
                  'qty' => $itemQty,
                  'line_total' => $itemLine,
                  'subtotal' => $subtotal,
            ]);
            exit;
      }
      header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart'));
      exit;
}

// Handle remove item
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'remove_item') {
      $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
      $sid = session_id();
      if ($sid === '') {
            session_start();
            $sid = session_id();
      }
      if ($cartItemId > 0) {
            $row = db_one('SELECT ci.id FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE ci.id = ? AND c.session_id = ? LIMIT 1', [$cartItemId, $sid]);
            if ($row) {
                  db_exec('DELETE FROM cart_items WHERE id=?', [$cartItemId]);
            }
      }
      // Compute new subtotal
      $subtotal = 0.0;
      $cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
      if ($cart) {
            $rows = db_all('SELECT qty, unit_price FROM cart_items WHERE cart_id=?', [(int)$cart['id']]);
            foreach ($rows as $r) {
                  $subtotal += (float)$r['unit_price'] * (int)$r['qty'];
            }
      }
      if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
            header('Content-Type: application/json');
            echo json_encode([
                  'ok' => true,
                  'removed' => true,
                  'cart_item_id' => $cartItemId,
                  'subtotal' => $subtotal,
            ]);
            exit;
      }
      header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart'));
      exit;
}
?>
<?php
// Handle Update Cart (bulk quantities)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'update_cart') {
      $qtyMap = isset($_POST['qty']) && is_array($_POST['qty']) ? $_POST['qty'] : [];
      $sid = session_id();
      if ($sid === '') {
            session_start();
            $sid = session_id();
      }
      $cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
      if ($cart) {
            foreach ($qtyMap as $cid => $q) {
                  $cartItemId = (int)$cid;
                  $newQty = max(0, (int)$q);
                  $row = db_one('SELECT ci.id FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE ci.id = ? AND c.id = ? LIMIT 1', [$cartItemId, (int)$cart['id']]);
                  if ($row) {
                        if ($newQty <= 0) {
                              db_exec('DELETE FROM cart_items WHERE id=?', [$cartItemId]);
                        } else {
                              db_exec('UPDATE cart_items SET qty=? WHERE id=?', [$newQty, $cartItemId]);
                        }
                  }
            }
      }
      header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart&updated=1'));
      exit;
}

// Handle Clear Cart
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form'] ?? '') === 'clear_cart') {
      $sid = session_id();
      if ($sid === '') {
            session_start();
            $sid = session_id();
      }
      $cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
      if ($cart) {
            db_exec('DELETE FROM cart_items WHERE cart_id=?', [(int)$cart['id']]);
      }
      header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart&cleared=1'));
      exit;
}
?>
<?php
// Load cart for display
$sid = session_id();
if ($sid === '') {
      session_start();
      $sid = session_id();
}
$cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
$cartId = $cart ? (int)$cart['id'] : 0;
$cartItems = [];
$subtotal = 0.0;
if ($cartId > 0) {
      $cartItems = db_all(
            "SELECT ci.id AS cart_item_id, ci.qty, ci.unit_price, v.id AS variant_id, v.color, v.size, p.id AS product_id, p.title, p.slug, p.featured_image\n" .
                  "FROM cart_items ci\n" .
                  "JOIN variants v ON v.id = ci.variant_id\n" .
                  "JOIN products p ON p.id = v.product_id\n" .
                  "WHERE ci.cart_id = ?\n" .
                  "ORDER BY ci.id DESC",
            [$cartId]
      );
      foreach ($cartItems as $it) {
            $subtotal += (float)($it['unit_price'] ?? 0) * (int)($it['qty'] ?? 0);
      }
}
$currencySymbol = isset($currentCurrencySymbol) ? (string)$currentCurrencySymbol : '$';
?>
<main class="main">

      <!-- Page Title -->
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Cart</li>
                        </ol>
                  </nav>
                  <h1>Cart</h1>
            </div>
      </div><!-- End Page Title -->

      <!-- Cart Section -->
      <section id="cart" class="cart section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row">
                        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                              <form method="post" id="update-cart-form">
                                    <input type="hidden" name="form" value="update_cart">
                                    <div class="cart-items">
                                          <div class="cart-header d-none d-lg-block">
                                                <div class="row align-items-center">
                                                      <div class="col-lg-6">
                                                            <h5>Product</h5>
                                                      </div>
                                                      <div class="col-lg-2 text-center">
                                                            <h5>Price</h5>
                                                      </div>
                                                      <div class="col-lg-2 text-center">
                                                            <h5>Quantity</h5>
                                                      </div>
                                                      <div class="col-lg-2 text-center">
                                                            <h5>Total</h5>
                                                      </div>
                                                </div>
                                          </div>

                                          <?php if (empty($cartItems)) { ?>
                                                <div class="alert alert-info">Your cart is empty.</div>
                                                <?php } else {
                                                foreach ($cartItems as $item) {
                                                      $img = (string)($item['featured_image'] ?? '');
                                                      if ($img === '') {
                                                            $img = 'assets/img/product/product-1.webp';
                                                      }
                                                      $imgSrc = (strpos($img, 'admin/') === 0 || strpos($img, '/admin') === 0 || strpos($img, 'assets/') === 0) ? $img : ('admin/' . $img);
                                                      $lineTotal = (float)$item['unit_price'] * (int)$item['qty'];
                                                ?>
                                                      <div class="cart-item">
                                                            <div class="row align-items-center">
                                                                  <div class="col-lg-6 col-12 mt-3 mt-lg-0 mb-lg-0 mb-3" data-cart-item-id="<?php echo (int)$item['cart_item_id']; ?>">
                                                                        <div class="product-info d-flex align-items-center">
                                                                              <div class="product-image">
                                                                                    <img src="admin/<?php echo htmlspecialchars($imgSrc, ENT_QUOTES); ?>" alt="Product" class="img-fluid" loading="lazy">
                                                                              </div>
                                                                              <div class="product-details">
                                                                                    <h6 class="product-title"><?php echo htmlspecialchars((string)$item['title'], ENT_QUOTES); ?></h6>
                                                                                    <div class="product-meta">
                                                                                          <span class="product-color">Color: <?php echo htmlspecialchars((string)($item['color'] ?? ''), ENT_QUOTES); ?></span>
                                                                                          <span class="product-size">Size: <?php echo htmlspecialchars((string)($item['size'] ?? ''), ENT_QUOTES); ?></span>
                                                                                    </div>
                                                                                    <button class="remove-item" type="button">
                                                                                          <i class="bi bi-trash"></i> Remove
                                                                                    </button>
                                                                              </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                                                                        <div class="price-tag">
                                                                              <span class="current-price"><?php echo $currencySymbol . number_format((float)$item['unit_price'], 2); ?></span>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                                                                        <div class="quantity-selector">
                                                                              <button class="quantity-btn decrease" type="button">
                                                                                    <i class="bi bi-dash"></i>
                                                                              </button>
                                                                              <input type="number" class="quantity-input" name="qty[<?php echo (int)$item['cart_item_id']; ?>]" value="<?php echo (int)$item['qty']; ?>" min="1" max="999">
                                                                              <button class="quantity-btn increase" type="button">
                                                                                    <i class="bi bi-plus"></i>
                                                                              </button>
                                                                        </div>
                                                                  </div>
                                                                  <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                                                                        <div class="item-total">
                                                                              <span><?php echo $currencySymbol . number_format($lineTotal, 2); ?></span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      </div><!-- End Cart Item -->
                                          <?php }
                                          } ?>

                                          <div class="cart-actions">
                                                <div class="row">
                                                      <div class="col-lg-6 mb-3 mb-lg-0">
                                                            <div class="coupon-form">
                                                                  <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                              placeholder="Coupon code">
                                                                        <button class="btn btn-outline-accent"
                                                                              type="button">Apply Coupon</button>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="col-lg-6 text-md-end">
                                                            <button class="btn btn-outline-heading me-2" type="submit">
                                                                  <i class="bi bi-arrow-clockwise"></i> Update Cart
                                                            </button>
                              </form>
                              <form method="post" style="display:inline-block" onsubmit="return confirm('Clear all items?');">
                                    <input type="hidden" name="form" value="clear_cart">
                                    <button class="btn btn-outline-remove" type="submit">
                                          <i class="bi bi-trash"></i> Clear Cart
                                    </button>
                              </form>
                        </div>
                  </div>
            </div>
            </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="300">
                  <div class="cart-summary">
                        <h4 class="summary-title">Order Summary</h4>

                        <div class="summary-item">
                              <span class="summary-label">Subtotal</span>
                              <span class="summary-value"><?php echo $currencySymbol . number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="summary-item shipping-item">
                              <span class="summary-label">Shipping</span>
                              <div class="shipping-options">
                                    <div class="form-check text-end">
                                          <input class="form-check-input" type="radio" name="shipping"
                                                id="standard" checked="">
                                          <label class="form-check-label" for="standard">
                                                Standard Delivery - $4.99
                                          </label>
                                    </div>
                                    <div class="form-check text-end">
                                          <input class="form-check-input" type="radio" name="shipping"
                                                id="express">
                                          <label class="form-check-label" for="express">
                                                Express Delivery - $12.99
                                          </label>
                                    </div>
                                    <div class="form-check text-end">
                                          <input class="form-check-input" type="radio" name="shipping"
                                                id="free">
                                          <label class="form-check-label" for="free">
                                                Free Shipping (Orders over $300)
                                          </label>
                                    </div>
                              </div>
                        </div>

                        <div class="summary-item">
                              <span class="summary-label">Tax</span>
                              <span class="summary-value"><?php echo $currencySymbol . number_format(0, 2); ?></span>
                        </div>

                        <div class="summary-item discount">
                              <span class="summary-label">Discount</span>
                              <span class="summary-value">-$0.00</span>
                        </div>

                        <div class="summary-total">
                              <span class="summary-label">Total</span>
                              <span class="summary-value"><?php echo $currencySymbol . number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="checkout-button">
                              <a href="<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout', ENT_QUOTES); ?>" class="btn btn-accent w-100">
                                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                              </a>
                        </div>

                        <div class="continue-shopping">
                              <a href="<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=products', ENT_QUOTES); ?>" class="btn btn-link w-100">
                                    <i class="bi bi-arrow-left"></i> Continue Shopping
                              </a>
                        </div>

                        <div class="payment-methods">
                              <p class="payment-title">We Accept</p>
                              <div class="payment-icons">
                                    <i class="bi bi-credit-card"></i>
                                    <i class="bi bi-paypal"></i>
                                    <i class="bi bi-wallet2"></i>
                                    <i class="bi bi-bank"></i>
                              </div>
                        </div>
                  </div>
            </div>
            </div>

            </div>

      </section><!-- /Cart Section -->

</main>
<script>
      (function() {
            function formatMoney(x) {
                  try {
                        return (new Intl.NumberFormat(undefined, {
                              minimumFractionDigits: 2,
                              maximumFractionDigits: 2
                        })).format(x);
                  } catch (e) {
                        return Number(x).toFixed(2);
                  }
            }
            async function postAjax(params) {
                  const form = new FormData();
                  for (const k in params) {
                        if (Object.prototype.hasOwnProperty.call(params, k)) form.append(k, params[k]);
                  }
                  const resp = await fetch('<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=cart', ENT_QUOTES); ?>', {
                        method: 'POST',
                        body: form,
                        credentials: 'same-origin'
                  });
                  return resp.json();
            }
            document.addEventListener('click', async function(e) {
                  const dec = e.target.closest('.quantity-btn.decrease');
                  if (dec) {
                        const row = dec.closest('.row');
                        const col = row ? row.querySelector('[data-cart-item-id]') : null;
                        const id = col ? col.getAttribute('data-cart-item-id') : null;
                        if (!id) return;
                        try {
                              const data = await postAjax({
                                    form: 'update_qty',
                                    op: 'dec',
                                    cart_item_id: id,
                                    ajax: '1'
                              });
                              if (!data || !data.ok) return;
                              if (data.removed) {
                                    const item = row.closest('.cart-item');
                                    if (item) item.remove();
                              } else {
                                    const qtyInput = row.querySelector('.quantity-input');
                                    if (qtyInput && (typeof data.qty === 'number')) qtyInput.value = String(data.qty);
                                    const lineEl = row.querySelector('.item-total span');
                                    if (lineEl && (typeof data.line_total === 'number')) lineEl.textContent = '<?php echo $currencySymbol; ?>' + formatMoney(data.line_total);
                              }
                              const subtotalEl = document.querySelector('.summary-item .summary-value');
                              if (subtotalEl && (typeof data.subtotal === 'number')) subtotalEl.textContent = '<?php echo $currencySymbol; ?>' + formatMoney(data.subtotal);
                        } catch (err) {
                              /* ignore */
                        }
                        return;
                  }
                  const inc = e.target.closest('.quantity-btn.increase');
                  if (inc) {
                        const row = inc.closest('.row');
                        const col = row ? row.querySelector('[data-cart-item-id]') : null;
                        const id = col ? col.getAttribute('data-cart-item-id') : null;
                        if (!id) return;
                        try {
                              const data = await postAjax({
                                    form: 'update_qty',
                                    op: 'inc',
                                    cart_item_id: id,
                                    ajax: '1'
                              });
                              if (!data || !data.ok) return;
                              const qtyInput = row.querySelector('.quantity-input');
                              if (qtyInput && (typeof data.qty === 'number')) qtyInput.value = String(data.qty);
                              const lineEl = row.querySelector('.item-total span');
                              if (lineEl && (typeof data.line_total === 'number')) lineEl.textContent = '<?php echo $currencySymbol; ?>' + formatMoney(data.line_total);
                              const subtotalEl = document.querySelector('.summary-item .summary-value');
                              if (subtotalEl && (typeof data.subtotal === 'number')) subtotalEl.textContent = '<?php echo $currencySymbol; ?>' + formatMoney(data.subtotal);
                        } catch (err) {
                              /* ignore */
                        }
                        return;
                  }
                  const rm = e.target.closest('.remove-item');
                  if (rm) {
                        if (!confirm('Remove this item from cart?')) return;
                        const row = rm.closest('.row');
                        const col = row ? row.querySelector('[data-cart-item-id]') : null;
                        const id = col ? col.getAttribute('data-cart-item-id') : null;
                        if (!id) return;
                        try {
                              const data = await postAjax({
                                    form: 'remove_item',
                                    cart_item_id: id,
                                    ajax: '1'
                              });
                              if (!data || !data.ok) return;
                              const item = row.closest('.cart-item');
                              if (item) item.remove();
                              const subtotalEl = document.querySelector('.summary-item .summary-value');
                              if (subtotalEl && (typeof data.subtotal === 'number')) subtotalEl.textContent = '<?php echo $currencySymbol; ?>' + formatMoney(data.subtotal);
                        } catch (err) {
                              /* ignore */
                        }
                        return;
                  }
            });
      })();
</script>