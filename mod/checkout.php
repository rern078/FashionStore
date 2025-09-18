<?php
$sid = session_id();
if ($sid === '') {
      session_start();
      $sid = session_id();
}
$checkoutCartItems = [];
$checkoutSubtotal = 0.0;
$cart = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
if ($cart) {
      $rows = db_all(
            "SELECT ci.id AS cart_item_id, ci.qty, ci.unit_price, v.color, v.size, p.title, p.featured_image\n" .
                  "FROM cart_items ci\n" .
                  "JOIN variants v ON v.id = ci.variant_id\n" .
                  "JOIN products p ON p.id = v.product_id\n" .
                  "WHERE ci.cart_id = ?\n" .
                  "ORDER BY ci.id DESC",
            [(int)$cart['id']]
      );
      foreach ($rows as $r) {
            $checkoutCartItems[] = $r;
            $checkoutSubtotal += (float)($r['unit_price'] ?? 0) * (int)($r['qty'] ?? 0);
      }
}
$currencySymbol = isset($currentCurrencySymbol) ? (string)$currentCurrencySymbol : '$';
// Handle promo code apply/remove
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
      $form = (string)($_POST['form'] ?? '');
      if ($form === 'apply_promo') {
            $code = strtoupper(trim((string)($_POST['promo_code'] ?? '')));
            if ($code !== '' && $checkoutSubtotal > 0) {
                  $promo = db_one('SELECT id, code, type, value, starts_at, ends_at, min_subtotal FROM promotions WHERE code = ? LIMIT 1', [$code]);
                  $now = date('Y-m-d H:i:s');
                  $ok = false;
                  if ($promo) {
                        $starts = (string)($promo['starts_at'] ?? '');
                        $ends = (string)($promo['ends_at'] ?? '');
                        if (($starts === '' || $now >= $starts) && ($ends === '' || $now <= $ends)) {
                              $minSub = $promo['min_subtotal'] !== null ? (float)$promo['min_subtotal'] : null;
                              if ($minSub === null || $checkoutSubtotal >= $minSub) {
                                    // Evaluate simple rules (country, include_product_slug, include_category_slug)
                                    $rules = db_all('SELECT rule_type, rule_value FROM promotion_rules WHERE promotion_id=?', [(int)$promo['id']]);
                                    $passes = true;
                                    $hasIncludeRule = false;
                                    $includePass = false;
                                    $sessCountry = isset($_SESSION['checkout_country']) && $_SESSION['checkout_country'] !== '' ? strtoupper((string)$_SESSION['checkout_country']) : null;
                                    foreach ($rules as $r) {
                                          $type = (string)$r['rule_type'];
                                          $val = (string)$r['rule_value'];
                                          if ($type === 'country') {
                                                if ($sessCountry !== null && strtoupper($val) !== $sessCountry) {
                                                      $passes = false;
                                                      break;
                                                }
                                          } elseif ($type === 'include_product_slug') {
                                                $hasIncludeRule = true;
                                                $cnt = db_one('SELECT COUNT(*) AS c FROM cart_items ci JOIN variants v ON v.id=ci.variant_id JOIN products p ON p.id=v.product_id WHERE ci.cart_id=? AND p.slug=?', [(int)$cart['id'], $val]);
                                                if ((int)($cnt['c'] ?? 0) > 0) {
                                                      $includePass = true;
                                                }
                                          } elseif ($type === 'include_category_slug') {
                                                $hasIncludeRule = true;
                                                $cnt = db_one('SELECT COUNT(*) AS c FROM cart_items ci JOIN variants v ON v.id=ci.variant_id JOIN products p ON p.id=v.product_id JOIN product_categories pc ON pc.product_id=p.id JOIN categories c ON c.id=pc.category_id WHERE ci.cart_id=? AND c.slug=?', [(int)$cart['id'], $val]);
                                                if ((int)($cnt['c'] ?? 0) > 0) {
                                                      $includePass = true;
                                                }
                                          }
                                    }
                                    if ($hasIncludeRule && !$includePass) {
                                          $passes = false;
                                    }
                                    if ($passes) {
                                          $ok = true;
                                    }
                              }
                        }
                  }
                  if ($ok) {
                        $_SESSION['promo_code'] = (string)$promo['code'];
                        $_SESSION['promo_type'] = (string)$promo['type'];
                        $_SESSION['promo_value'] = (float)$promo['value'];
                        header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&promo=applied'));
                        exit;
                  } else {
                        $_SESSION['promo_code'] = null;
                        unset($_SESSION['promo_code']);
                        $_SESSION['promo_type'] = null;
                        unset($_SESSION['promo_type']);
                        $_SESSION['promo_value'] = null;
                        unset($_SESSION['promo_value']);
                        header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&promo=invalid'));
                        exit;
                  }
            }
      } elseif ($form === 'remove_promo') {
            $_SESSION['promo_code'] = null;
            unset($_SESSION['promo_code']);
            $_SESSION['promo_type'] = null;
            unset($_SESSION['promo_type']);
            $_SESSION['promo_value'] = null;
            unset($_SESSION['promo_value']);
            header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&promo=removed'));
            exit;
      } elseif ($form === 'place_order') {
            // Basic validation of required fields
            $first = trim((string)($_POST['first-name'] ?? ''));
            $last = trim((string)($_POST['last-name'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $phone = trim((string)($_POST['phone'] ?? ''));
            $line1 = trim((string)($_POST['address'] ?? ''));
            $line2 = trim((string)($_POST['apartment'] ?? ''));
            $cityPost = trim((string)($_POST['city'] ?? ''));
            $statePost = trim((string)($_POST['state'] ?? ''));
            $postalPost = trim((string)($_POST['zip'] ?? ''));
            $countryPost = strtoupper(trim((string)($_POST['country'] ?? '')));
            $termsOk = isset($_POST['terms']);
            $paymentMethod = (string)($_POST['payment-method'] ?? 'card');

            if ($first === '' || $last === '' || $email === '' || $phone === '' || $line1 === '' || $cityPost === '' || $postalPost === '' || $countryPost === '' || !$termsOk) {
                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&error=missing'));
                  exit;
            }

            // Refresh session location for tax logic
            $_SESSION['checkout_country'] = $countryPost;
            $_SESSION['checkout_state'] = $statePost !== '' ? $statePost : null;
            $_SESSION['checkout_city'] = $cityPost !== '' ? $cityPost : null;
            $_SESSION['checkout_postal'] = $postalPost !== '' ? $postalPost : null;

            // Recompute amounts based on current cart and location
            $cartRow = db_one('SELECT id FROM carts WHERE session_id=?', [$sid]);
            if (!$cartRow) {
                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart'));
                  exit;
            }
            $cartId = (int)$cartRow['id'];
            $items = db_all('SELECT variant_id, qty, unit_price FROM cart_items WHERE cart_id=? ORDER BY id ASC', [$cartId]);
            if (empty($items)) {
                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=cart'));
                  exit;
            }

            $subtotal = 0.0;
            foreach ($items as $it) {
                  $subtotal += (float)($it['unit_price'] ?? 0) * (int)($it['qty'] ?? 0);
            }

            // Shipping
            $shippingAmountX = 0.0;
            $shippingRowX = db_one('SELECT name, code, base_cost, min_subtotal_free FROM shipping_methods WHERE is_active = 1 ORDER BY sort_order ASC, base_cost ASC LIMIT 1');
            if ($shippingRowX) {
                  $baseCostX = (float)($shippingRowX['base_cost'] ?? 0);
                  $minFreeX = $shippingRowX['min_subtotal_free'] !== null ? (float)$shippingRowX['min_subtotal_free'] : null;
                  $shippingAmountX = ($minFreeX !== null && $subtotal >= $minFreeX) ? 0.0 : $baseCostX;
            }

            // Tax (use posted address)
            $taxAmountX = 0.0;
            $tx = db_one(
                  "SELECT rate_percent FROM tax_rates\n" .
                        "WHERE is_active = 1\n" .
                        "  AND (country IS NULL OR country = ?)\n" .
                        "  AND (state IS NULL OR state = ? OR ? IS NULL)\n" .
                        "  AND (city IS NULL OR city = ? OR ? IS NULL)\n" .
                        "  AND (postal IS NULL OR postal = ? OR ? IS NULL)\n" .
                        "ORDER BY sort_order ASC, (country IS NOT NULL) DESC, (state IS NOT NULL) DESC, (city IS NOT NULL) DESC, (postal IS NOT NULL) DESC\n" .
                        "LIMIT 1",
                  [
                        $countryPost,
                        $statePost,
                        $statePost,
                        $cityPost,
                        $cityPost,
                        $postalPost,
                        $postalPost,
                  ]
            );
            if ($tx) {
                  $ratePctX = (float)($tx['rate_percent'] ?? 0);
                  if ($ratePctX > 0 && $subtotal > 0) {
                        $taxAmountX = ($subtotal * $ratePctX) / 100.0;
                  }
            }

            // Promotion
            $discountX = 0.0;
            if (!empty($_SESSION['promo_code'])) {
                  $ptype = (string)($_SESSION['promo_type'] ?? '');
                  $pval = (float)($_SESSION['promo_value'] ?? 0);
                  if ($ptype === 'percentage' && $pval > 0) {
                        $discountX = ($subtotal * $pval) / 100.0;
                  } elseif ($ptype === 'fixed' && $pval > 0) {
                        $discountX = min($pval, $subtotal);
                  } elseif ($ptype === 'free_shipping') {
                        $discountX = 0.0;
                        $shippingAmountX = 0.0;
                  }
            }

            $grand = max(0, $subtotal - $discountX) + $shippingAmountX + $taxAmountX;
            $currencyCode = strtoupper((string)($_SESSION['currency'] ?? 'USD'));

            // Determine user id (existing session or create a new customer)
            $userId = (int)($_SESSION['user']['id'] ?? 0);
            if ($userId <= 0) {
                  $name = trim($first . ' ' . $last);
                  $randPass = bin2hex(random_bytes(8));
                  $hash = password_hash($randPass, PASSWORD_DEFAULT);
                  db_exec('INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)', [
                        $name,
                        $email,
                        ($phone === '' ? null : $phone),
                        $hash,
                        'customer'
                  ]);
                  $userId = db_last_insert_id();
                  $_SESSION['user'] = [
                        'id' => (int)$userId,
                        'name' => (string)$name,
                        'email' => (string)$email,
                        'role' => 'customer',
                  ];
            }

            // Place order in a transaction
            $pdo = db();
            try {
                  $pdo->beginTransaction();

                  // Unique order number
                  $orderNumber = 'FS' . date('YmdHis') . '-' . random_int(100, 999);
                  // Ensure uniqueness loop (very unlikely collision)
                  while (db_one('SELECT id FROM orders WHERE order_number=?', [$orderNumber])) {
                        $orderNumber = 'FS' . date('YmdHis') . '-' . random_int(100, 999);
                  }

                  db_exec('INSERT INTO orders (user_id, order_number, status, subtotal, discount_total, shipping_total, tax_total, grand_total, currency, placed_at, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $userId,
                        substr($orderNumber, 0, 30),
                        'pending',
                        $subtotal,
                        $discountX,
                        $shippingAmountX,
                        $taxAmountX,
                        $grand,
                        substr($currencyCode, 0, 3),
                        date('Y-m-d H:i:s'),
                        'unpaid'
                  ]);
                  $orderId = db_last_insert_id();

                  // Optional: also store a flat snapshot into checkout_orders if such table exists
                  try {
                        // Probe table existence; will throw if not present
                        db_one('SELECT 1 FROM checkout_orders LIMIT 1');
                        // Parse limited card metadata safely (no PAN/CVV storage)
                        $cardNumberRaw = isset($_POST['card-number']) ? preg_replace('~[^0-9]~', '', (string)$_POST['card-number']) : '';
                        $cardLast4 = strlen($cardNumberRaw) >= 4 ? substr($cardNumberRaw, -4) : null;
                        $expiryRaw = (string)($_POST['expiry'] ?? '');
                        $expMonth = null;
                        $expYear = null;
                        if (preg_match('~^(\d{2})\/(\d{2})$~', $expiryRaw, $m)) {
                              $expMonth = (int)$m[1];
                              $expYear = 2000 + (int)$m[2];
                        }
                        $cardName = trim((string)($_POST['card-name'] ?? ''));

                        db_exec(
                              'INSERT INTO checkout_orders (
                                    first_name, last_name, email, phone,
                                    address_line1, address_line2, city, state, postal_code, country_code,
                                    payment_method, card_last4, card_exp_month, card_exp_year, card_name,
                                    terms_accepted
                              ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                              [
                                    $first,
                                    $last,
                                    $email,
                                    $phone,
                                    $line1,
                                    ($line2 !== '' ? $line2 : null),
                                    $cityPost,
                                    ($statePost !== '' ? $statePost : null),
                                    $postalPost,
                                    substr($countryPost, 0, 2),
                                    ($paymentMethod === 'paypal' ? 'paypal' : ($paymentMethod === 'apple_pay' ? 'apple_pay' : 'card')),
                                    $cardLast4,
                                    $expMonth,
                                    $expYear,
                                    ($cardName !== '' ? $cardName : null),
                                    $termsOk ? 1 : 0,
                              ]
                        );
                  } catch (Throwable $e) {
                        // Silently ignore if table does not exist or any error occurs
                  }

                  // Shipping address snapshot
                  db_exec('INSERT INTO order_addresses (order_id, address_type, full_name, email, phone, line1, line2, city, state, postal, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $orderId,
                        'shipping',
                        trim($first . ' ' . $last),
                        $email,
                        $phone,
                        $line1,
                        ($line2 !== '' ? $line2 : null),
                        $cityPost,
                        ($statePost !== '' ? $statePost : null),
                        $postalPost,
                        substr($countryPost, 0, 2)
                  ]);

                  // Order items from cart
                  foreach ($items as $it) {
                        db_exec('INSERT INTO order_items (order_id, variant_id, qty, unit_price, discount_amount, tax_amount) VALUES (?, ?, ?, ?, ?, ?)', [
                              $orderId,
                              (int)$it['variant_id'],
                              (int)$it['qty'],
                              (float)$it['unit_price'],
                              0.0,
                              0.0
                        ]);
                  }

                  // Payment placeholder
                  $provider = ($paymentMethod === 'paypal' ? 'paypal' : ($paymentMethod === 'apple_pay' ? 'apple_pay' : 'card'));
                  db_exec('INSERT INTO payments (order_id, provider, provider_txn_id, amount, status, captured_at) VALUES (?, ?, ?, ?, ?, ?)', [
                        $orderId,
                        $provider,
                        null,
                        $grand,
                        'pending',
                        null
                  ]);

                  // Clear cart
                  db_exec('DELETE FROM cart_items WHERE cart_id=?', [$cartId]);

                  $pdo->commit();

                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&placed=1&order=' . urlencode($orderNumber)));
                  exit;
            } catch (Throwable $e) {
                  if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                  }
                  header('Location: ' . (($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout&error=failed'));
                  exit;
            }
      }
}
// Shipping: pick the cheapest active method by sort_order/base_cost and apply free threshold
$shippingAmount = 0.0;
$shippingLabel = 'Shipping';
$shippingRow = db_one('SELECT name, code, base_cost, min_subtotal_free FROM shipping_methods WHERE is_active = 1 ORDER BY sort_order ASC, base_cost ASC LIMIT 1');
if ($shippingRow) {
      $shippingLabel = 'Shipping (' . (string)($shippingRow['code'] ?? 'Method') . ')';
      $baseCost = (float)($shippingRow['base_cost'] ?? 0);
      $minFree = $shippingRow['min_subtotal_free'] !== null ? (float)$shippingRow['min_subtotal_free'] : null;
      if ($checkoutSubtotal > 0) {
            $shippingAmount = ($minFree !== null && $checkoutSubtotal >= $minFree) ? 0.0 : $baseCost;
      }
}
// Tax: find first active rate matching session-provided location or fallback to country 'US'
$taxCountry = isset($_SESSION['checkout_country']) && $_SESSION['checkout_country'] !== '' ? strtoupper((string)$_SESSION['checkout_country']) : 'US';
$taxState = isset($_SESSION['checkout_state']) ? (string)$_SESSION['checkout_state'] : null;
$taxCity = isset($_SESSION['checkout_city']) ? (string)$_SESSION['checkout_city'] : null;
$taxPostal = isset($_SESSION['checkout_postal']) ? (string)$_SESSION['checkout_postal'] : null;
$taxAmount = 0.0;
$taxRow = db_one(
      "SELECT rate_percent FROM tax_rates\n" .
            "WHERE is_active = 1\n" .
            "  AND (country IS NULL OR country = ?)\n" .
            "  AND (state IS NULL OR state = ? OR ? IS NULL)\n" .
            "  AND (city IS NULL OR city = ? OR ? IS NULL)\n" .
            "  AND (postal IS NULL OR postal = ? OR ? IS NULL)\n" .
            "ORDER BY sort_order ASC,\n" .
            "  (country IS NOT NULL) DESC, (state IS NOT NULL) DESC, (city IS NOT NULL) DESC, (postal IS NOT NULL) DESC\n" .
            "LIMIT 1",
      [
            $taxCountry,
            $taxState,
            $taxState,
            $taxCity,
            $taxCity,
            $taxPostal,
            $taxPostal,
      ]
);
if ($taxRow) {
      $ratePct = (float)($taxRow['rate_percent'] ?? 0);
      if ($ratePct > 0 && $checkoutSubtotal > 0) {
            $taxAmount = ($checkoutSubtotal * $ratePct) / 100.0;
      }
}
// Promo application to amounts
$discountAmount = 0.0;
if (!empty($_SESSION['promo_code'])) {
      $ptype = (string)($_SESSION['promo_type'] ?? '');
      $pval = (float)($_SESSION['promo_value'] ?? 0);
      if ($ptype === 'percentage' && $pval > 0) {
            $discountAmount = ($checkoutSubtotal * $pval) / 100.0;
      } elseif ($ptype === 'fixed' && $pval > 0) {
            $discountAmount = min($pval, $checkoutSubtotal);
      } elseif ($ptype === 'free_shipping') {
            $discountAmount = 0.0;
            $shippingAmount = 0.0;
      }
}
$orderTotal = max(0, $checkoutSubtotal - $discountAmount) + $shippingAmount + $taxAmount;
?>
<main class="main">
      <!-- Page Title -->
      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Checkout</li>
                        </ol>
                  </nav>
                  <h1>Checkout</h1>
            </div>
      </div><!-- End Page Title -->

      <!-- Checkout Section -->
      <section id="checkout" class="checkout section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                  <div class="row">
                        <div class="col-lg-8">
                              <!-- Checkout Steps -->
                              <div class="checkout-steps mb-4" data-aos="fade-up">
                                    <div class="step active" data-step="1">
                                          <div class="step-number">1</div>
                                          <div class="step-title">Information</div>
                                    </div>
                                    <div class="step-connector"></div>
                                    <div class="step" data-step="2">
                                          <div class="step-number">2</div>
                                          <div class="step-title">Shipping</div>
                                    </div>
                                    <div class="step-connector"></div>
                                    <div class="step" data-step="3">
                                          <div class="step-number">3</div>
                                          <div class="step-title">Payment</div>
                                    </div>
                                    <div class="step-connector"></div>
                                    <div class="step" data-step="4">
                                          <div class="step-number">4</div>
                                          <div class="step-title">Review</div>
                                    </div>
                              </div>

                              <div class="checkout-forms" data-aos="fade-up" data-aos-delay="150">
                                    <form method="post" id="checkout-form">
                                          <input type="hidden" name="form" value="place_order">
                                          <div class="checkout-form active" data-form="1">
                                                <div class="form-header">
                                                      <h3>Customer Information</h3>
                                                      <p>Please enter your contact details</p>
                                                </div>
                                                <div class="checkout-form-element">
                                                      <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                  <label for="first-name">First Name</label>
                                                                  <input type="text" name="first-name"
                                                                        class="form-control" id="first-name"
                                                                        placeholder="Your First Name" required="">
                                                            </div>
                                                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                                                  <label for="last-name">Last Name</label>
                                                                  <input type="text" name="last-name"
                                                                        class="form-control" id="last-name"
                                                                        placeholder="Your Last Name" required="">
                                                            </div>
                                                      </div>
                                                      <div class="form-group mt-3">
                                                            <label for="email">Email Address</label>
                                                            <input type="email" class="form-control" name="email"
                                                                  id="email" placeholder="Your Email" required="">
                                                      </div>
                                                      <div class="form-group mt-3">
                                                            <label for="phone">Phone Number</label>
                                                            <input type="tel" class="form-control" name="phone"
                                                                  id="phone" placeholder="Your Phone Number"
                                                                  required="">
                                                      </div>
                                                      <div class="text-end mt-4">
                                                            <button type="button" class="btn btn-primary next-step"
                                                                  data-next="2">Continue to Shipping</button>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="checkout-form" data-form="2">
                                                <div class="form-header">
                                                      <h3>Shipping Address</h3>
                                                      <p>Where should we deliver your order?</p>
                                                </div>
                                                <div class="checkout-form-element">
                                                      <div class="form-group">
                                                            <label for="address">Street Address</label>
                                                            <input type="text" class="form-control" name="address"
                                                                  id="address" placeholder="Street Address" required="">
                                                      </div>
                                                      <div class="form-group mt-3">
                                                            <label for="apartment">Apartment, Suite, etc.
                                                                  (optional)</label>
                                                            <input type="text" class="form-control" name="apartment"
                                                                  id="apartment"
                                                                  placeholder="Apartment, Suite, Unit, etc.">
                                                      </div>
                                                      <div class="row mt-3">
                                                            <div class="col-md-4 form-group">
                                                                  <label for="city">City</label>
                                                                  <input type="text" name="city" class="form-control"
                                                                        id="city" placeholder="City" required="">
                                                            </div>
                                                            <div class="col-md-4 form-group mt-3 mt-md-0">
                                                                  <label for="state">State</label>
                                                                  <input type="text" name="state" class="form-control"
                                                                        id="state" placeholder="State" required="">
                                                            </div>
                                                            <div class="col-md-4 form-group mt-3 mt-md-0">
                                                                  <label for="zip">ZIP Code</label>
                                                                  <input type="text" name="zip" class="form-control"
                                                                        id="zip" placeholder="ZIP Code" required="">
                                                            </div>
                                                      </div>
                                                      <div class="form-group mt-3">
                                                            <label for="country">Country</label>
                                                            <select class="form-select" id="country" name="country"
                                                                  required="">
                                                                  <option value="">Select Country</option>
                                                                  <option value="US">United States</option>
                                                                  <option value="CA">Canada</option>
                                                                  <option value="UK">United Kingdom</option>
                                                                  <option value="AU">Australia</option>
                                                                  <option value="DE">Germany</option>
                                                                  <option value="FR">France</option>
                                                            </select>
                                                      </div>
                                                      <div class="form-check mt-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                  id="save-address" name="save-address">
                                                            <label class="form-check-label" for="save-address">
                                                                  Save this address for future orders
                                                            </label>
                                                      </div>
                                                      <div class="d-flex justify-content-between mt-4">
                                                            <button type="button"
                                                                  class="btn btn-outline-secondary prev-step"
                                                                  data-prev="1">Back to Information</button>
                                                            <button type="button" class="btn btn-primary next-step"
                                                                  data-next="3">Continue to Payment</button>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="checkout-form" data-form="3">
                                                <div class="form-header">
                                                      <h3>Payment Method</h3>
                                                      <p>Choose how you'd like to pay</p>
                                                </div>
                                                <div class="checkout-form-element">
                                                      <div class="payment-methods">
                                                            <div class="payment-method active">
                                                                  <div class="payment-method-header">
                                                                        <div class="form-check">
                                                                              <input class="form-check-input"
                                                                                    type="radio" name="payment-method"
                                                                                    id="credit-card" value="card" checked="">
                                                                              <label class="form-check-label"
                                                                                    for="credit-card">
                                                                                    Credit / Debit Card
                                                                              </label>
                                                                        </div>
                                                                        <div class="payment-icons">
                                                                              <i class="bi bi-credit-card-2-front"></i>
                                                                              <i class="bi bi-credit-card"></i>
                                                                        </div>
                                                                  </div>
                                                                  <div class="payment-method-body">
                                                                        <div class="row">
                                                                              <div class="col-12 form-group">
                                                                                    <label for="card-number">Card
                                                                                          Number</label>
                                                                                    <input type="text"
                                                                                          class="form-control"
                                                                                          name="card-number"
                                                                                          id="card-number"
                                                                                          placeholder="1234 5678 9012 3456"
                                                                                          required="">
                                                                              </div>
                                                                        </div>
                                                                        <div class="row mt-3">
                                                                              <div class="col-md-6 form-group">
                                                                                    <label for="expiry">Expiration
                                                                                          Date</label>
                                                                                    <input type="text"
                                                                                          class="form-control"
                                                                                          name="expiry" id="expiry"
                                                                                          placeholder="MM/YY"
                                                                                          required="">
                                                                              </div>
                                                                              <div
                                                                                    class="col-md-6 form-group mt-3 mt-md-0">
                                                                                    <label for="cvv">Security Code
                                                                                          (CVV)</label>
                                                                                    <input type="text"
                                                                                          class="form-control"
                                                                                          name="cvv" id="cvv"
                                                                                          placeholder="123" required="">
                                                                              </div>
                                                                        </div>
                                                                        <div class="form-group mt-3">
                                                                              <label for="card-name">Name on
                                                                                    Card</label>
                                                                              <input type="text" class="form-control"
                                                                                    name="card-name" id="card-name"
                                                                                    placeholder="John Doe" required="">
                                                                        </div>
                                                                  </div>
                                                            </div>

                                                            <div class="payment-method mt-3">
                                                                  <div class="payment-method-header">
                                                                        <div class="form-check">
                                                                              <input class="form-check-input"
                                                                                    type="radio" name="payment-method"
                                                                                    id="paypal" value="paypal">
                                                                              <label class="form-check-label"
                                                                                    for="paypal">
                                                                                    PayPal
                                                                              </label>
                                                                        </div>
                                                                        <div class="payment-icons">
                                                                              <i class="bi bi-paypal"></i>
                                                                        </div>
                                                                  </div>
                                                                  <div class="payment-method-body d-none">
                                                                        <p>You will be redirected to PayPal to complete
                                                                              your purchase securely.</p>
                                                                  </div>
                                                            </div>

                                                            <div class="payment-method mt-3">
                                                                  <div class="payment-method-header">
                                                                        <div class="form-check">
                                                                              <input class="form-check-input"
                                                                                    type="radio" name="payment-method"
                                                                                    id="apple-pay" value="apple_pay">
                                                                              <label class="form-check-label"
                                                                                    for="apple-pay">
                                                                                    Apple Pay
                                                                              </label>
                                                                        </div>
                                                                        <div class="payment-icons">
                                                                              <i class="bi bi-apple"></i>
                                                                        </div>
                                                                  </div>
                                                                  <div class="payment-method-body d-none">
                                                                        <p>You will be prompted to authorize payment
                                                                              with Apple Pay.</p>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="d-flex justify-content-between mt-4">
                                                            <button type="button"
                                                                  class="btn btn-outline-secondary prev-step"
                                                                  data-prev="2">Back to Shipping</button>
                                                            <button type="button" class="btn btn-primary next-step"
                                                                  data-next="4">Review Order</button>
                                                      </div>
                                                </div>
                                          </div>

                                          <div class="checkout-form" data-form="4">
                                                <div class="form-header">
                                                      <h3>Review Your Order</h3>
                                                      <p>Please review your information before placing your order</p>
                                                </div>
                                                <div class="checkout-form-element">
                                                      <div class="review-sections">
                                                            <div class="review-section">
                                                                  <div class="review-section-header">
                                                                        <h4>Contact Information</h4>
                                                                        <button type="button" class="btn-edit"
                                                                              data-edit="1">Edit</button>
                                                                  </div>
                                                                  <div class="review-section-content">
                                                                        <p class="review-name">John Doe</p>
                                                                        <p class="review-email"><a
                                                                                    href="/cdn-cgi/l/email-protection"
                                                                                    class="__cf_email__"
                                                                                    data-cfemail="48222720262c272d082d30292538242d662b2725">[email&#160;protected]</a>
                                                                        </p>
                                                                        <p class="review-phone">+1 (555) 123-4567</p>
                                                                  </div>
                                                            </div>

                                                            <div class="review-section mt-3">
                                                                  <div class="review-section-header">
                                                                        <h4>Shipping Address</h4>
                                                                        <button type="button" class="btn-edit"
                                                                              data-edit="2">Edit</button>
                                                                  </div>
                                                                  <div class="review-section-content">
                                                                        <p>123 Main Street, Apt 4B</p>
                                                                        <p>New York, NY 10001</p>
                                                                        <p>United States</p>
                                                                  </div>
                                                            </div>

                                                            <div class="review-section mt-3">
                                                                  <div class="review-section-header">
                                                                        <h4>Payment Method</h4>
                                                                        <button type="button" class="btn-edit"
                                                                              data-edit="3">Edit</button>
                                                                  </div>
                                                                  <div class="review-section-content">
                                                                        <p><i class="bi bi-credit-card-2-front me-2"></i>
                                                                              Credit Card ending in 3456</p>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <div class="form-check mt-4">
                                                            <input class="form-check-input" type="checkbox" id="terms"
                                                                  name="terms" required="">
                                                            <label class="form-check-label" for="terms">
                                                                  I agree to the <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#termsModal">Terms and
                                                                        Conditions</a> and <a href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#privacyModal">Privacy
                                                                        Policy</a>
                                                            </label>
                                                      </div>
                                                      <div class="success-message d-none">Your order has been placed
                                                            successfully! Thank you for your purchase.</div>
                                                      <div class="d-flex justify-content-between mt-4">
                                                            <button type="button"
                                                                  class="btn btn-outline-secondary prev-step"
                                                                  data-prev="3">Back to Payment</button>
                                                            <button type="submit"
                                                                  class="btn btn-success place-order-btn">Place
                                                                  Order</button>
                                                      </div>
                                                </div>
                                          </div>
                                    </form>
                              </div>
                        </div>

                        <div class="col-lg-4">
                              <!-- Order Summary -->
                              <div class="order-summary" data-aos="fade-left" data-aos-delay="200">
                                    <div class="order-summary-header">
                                          <h3>Order Summary</h3>
                                          <?php if (!empty($_GET['placed']) && !empty($_GET['order'])) { ?>
                                                <div class="alert alert-success mt-2" role="alert">
                                                      Order placed successfully. Your order number is <strong><?php echo htmlspecialchars((string)$_GET['order'], ENT_QUOTES); ?></strong>.
                                                </div>
                                          <?php } elseif (!empty($_GET['error'])) { ?>
                                                <div class="alert alert-danger mt-2" role="alert">
                                                      Unable to place order. Please try again.
                                                </div>
                                          <?php } ?>
                                          <button type="button" class="btn-toggle-summary d-lg-none">
                                                <i class="bi bi-chevron-down"></i>
                                          </button>
                                    </div>

                                    <div class="order-summary-content">
                                          <div class="order-items">
                                                <?php if (empty($checkoutCartItems)) { ?>
                                                      <div class="text-muted text-center py-3">Your cart is empty.</div>
                                                <?php } else { ?>
                                                      <?php foreach ($checkoutCartItems as $ci) {
                                                            $img = (string)($ci['featured_image'] ?? '');
                                                            if ($img === '') $img = 'assets/img/product/product-1.webp';
                                                            $imgSrc = (strpos($img, 'admin/') === 0 || strpos($img, '/admin') === 0 || strpos($img, 'assets/') === 0) ? $img : ('admin/' . $img);
                                                            $variantText = trim(((string)($ci['color'] ?? '')) . ' ' . ((string)($ci['size'] ?? '')));
                                                      ?>
                                                            <div class="order-item">
                                                                  <div class="order-item-image">
                                                                        <img src="admin/<?php echo htmlspecialchars($imgSrc, ENT_QUOTES); ?>" alt="Product" class="img-fluid">
                                                                  </div>
                                                                  <div class="order-item-details">
                                                                        <h4><?php echo htmlspecialchars((string)($ci['title'] ?? ''), ENT_QUOTES); ?></h4>
                                                                        <p class="order-item-variant"><?php echo htmlspecialchars($variantText !== '' ? $variantText : '—', ENT_QUOTES); ?></p>
                                                                        <div class="order-item-price">
                                                                              <span class="quantity"><?php echo (int)$ci['qty']; ?> ×</span>
                                                                              <span class="price"><?php echo $currencySymbol . number_format((float)$ci['unit_price'], 2); ?></span>
                                                                        </div>
                                                                  </div>
                                                            </div>
                                                      <?php } ?>
                                                <?php } ?>
                                          </div>

                                          <div class="order-totals">
                                                <div class="order-subtotal d-flex justify-content-between">
                                                      <span>Subtotal</span>
                                                      <span><?php echo $currencySymbol . number_format($checkoutSubtotal, 2); ?></span>
                                                </div>
                                                <div class="order-shipping d-flex justify-content-between">
                                                      <span><?php echo htmlspecialchars($shippingLabel, ENT_QUOTES); ?></span>
                                                      <span><?php echo $currencySymbol . number_format($shippingAmount, 2); ?></span>
                                                </div>
                                                <div class="order-tax d-flex justify-content-between">
                                                      <span>Tax</span>
                                                      <span><?php echo $currencySymbol . number_format($taxAmount, 2); ?></span>
                                                </div>
                                                <div class="order-total d-flex justify-content-between">
                                                      <span>Total</span>
                                                      <span><?php echo $currencySymbol . number_format($orderTotal, 2); ?></span>
                                                </div>
                                          </div>

                                          <div class="promo-code mt-3">
                                                <form method="post" class="input-group" style="gap:8px;">
                                                      <input type="hidden" name="form" value="apply_promo">
                                                      <input type="text" class="form-control" name="promo_code" placeholder="Promo Code" aria-label="Promo Code" value="<?php echo htmlspecialchars((string)($_SESSION['promo_code'] ?? ''), ENT_QUOTES); ?>">
                                                      <button class="btn btn-outline-primary" type="submit">Apply</button>
                                                      <?php if (!empty($_SESSION['promo_code'])) { ?>
                                                            <button class="btn btn-outline-secondary" type="submit" formaction="<?php echo htmlspecialchars(($__CONFIG['site']['base_url'] ?? '/') . '?p=checkout', ENT_QUOTES); ?>" formmethod="post" name="form" value="remove_promo">Remove</button>
                                                      <?php } ?>
                                                </form>
                                                <?php if (!empty($_GET['promo']) && $_GET['promo'] === 'invalid') { ?>
                                                      <div class="text-danger small mt-2">Invalid or ineligible promo code.</div>
                                                <?php } ?>
                                                <?php if (!empty($_SESSION['promo_code']) && $discountAmount > 0) { ?>
                                                      <div class="text-success small mt-2">Applied <?php echo htmlspecialchars((string)$_SESSION['promo_code'], ENT_QUOTES); ?>: -<?php echo $currencySymbol . number_format($discountAmount, 2); ?></div>
                                                <?php } elseif (!empty($_SESSION['promo_code']) && $shippingAmount === 0 && (string)($_SESSION['promo_type'] ?? '') === 'free_shipping') { ?>
                                                      <div class="text-success small mt-2">Free shipping applied (<?php echo htmlspecialchars((string)$_SESSION['promo_code'], ENT_QUOTES); ?>)</div>
                                                <?php } ?>
                                          </div>

                                          <div class="secure-checkout mt-4">
                                                <div class="secure-checkout-header">
                                                      <i class="bi bi-shield-lock"></i>
                                                      <span>Secure Checkout</span>
                                                </div>
                                                <div class="payment-icons mt-2">
                                                      <i class="bi bi-credit-card-2-front"></i>
                                                      <i class="bi bi-credit-card"></i>
                                                      <i class="bi bi-paypal"></i>
                                                      <i class="bi bi-apple"></i>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>

                  <!-- Terms and Privacy Modals -->
                  <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                              <div class="modal-content">
                                    <div class="modal-header">
                                          <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in
                                                dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed
                                                auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in
                                                nulla enim. Phasellus molestie magna non est bibendum non
                                                venenatis nisl tempor.</p>
                                          <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie
                                                vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh
                                                porttitor. Ut in nulla enim. Phasellus molestie magna non est
                                                bibendum non venenatis nisl tempor.</p>
                                          <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie
                                                vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh
                                                porttitor. Ut in nulla enim. Phasellus molestie magna non est
                                                bibendum non venenatis nisl tempor.</p>
                                    </div>
                                    <div class="modal-footer">
                                          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I
                                                Understand</button>
                                    </div>
                              </div>
                        </div>
                  </div>

                  <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                              <div class="modal-content">
                                    <div class="modal-header">
                                          <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in
                                                dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed
                                                auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in
                                                nulla enim.</p>
                                          <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie
                                                vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh
                                                porttitor. Ut in nulla enim. Phasellus molestie magna non est
                                                bibendum non venenatis nisl tempor.</p>
                                          <p>Suspendisse in orci enim. Vivamus hendrerit arcu sed erat molestie
                                                vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh
                                                porttitor. Ut in nulla enim. Phasellus molestie magna non est
                                                bibendum non venenatis nisl tempor.</p>
                                    </div>
                                    <div class="modal-footer">
                                          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I
                                                Understand</button>
                                    </div>
                              </div>
                        </div>
                  </div>

            </div>

      </section><!-- /Checkout Section -->

</main>