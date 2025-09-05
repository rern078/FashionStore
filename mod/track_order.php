<?php
// Track Order page
// Allows customers to check status by Order Number and optional Email
?>
<main class="main">

      <div class="page-title light-background">
            <div class="container">
                  <nav class="breadcrumbs">
                        <ol>
                              <li><a href="index.html">Home</a></li>
                              <li class="current">Track Order</li>
                        </ol>
                  </nav>
                  <h1>Track Order</h1>
            </div>
      </div>

      <section id="track-order" class="section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                  <div class="row">
                        <div class="col-lg-8 mx-auto">
                              <div class="card p-4">
                                    <h3 class="mb-3">Find your order</h3>
                                    <p class="text-muted mb-4">Enter your order number and email to see the latest status.</p>
                                    <form method="get" action="" class="row g-3">
                                          <input type="hidden" name="p" value="track-order">
                                          <div class="col-md-6">
                                                <label for="order_number" class="form-label">Order Number</label>
                                                <input type="text" class="form-control" id="order_number" name="order_number" value="<?php echo htmlspecialchars((string)($_GET['order_number'] ?? ''), ENT_QUOTES); ?>" placeholder="e.g. FS-100045" required>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="email" class="form-label">Email (optional)</label>
                                                <input type="email" class="form-control line-height-1.8" id="email" name="email" value="<?php echo htmlspecialchars((string)($_GET['email'] ?? ''), ENT_QUOTES); ?>" placeholder="you@example.com">
                                          </div>
                                          <div class="col-12 text-end">
                                                <button type="submit" class="btn btn-primary">
                                                      <i class="bi bi-search me-1"></i> Track
                                                </button>
                                          </div>
                                    </form>
                              </div>

                              <?php
                              $order = null;
                              $shipments = [];
                              $items = [];
                              $error = '';
                              $queryNumber = isset($_GET['order_number']) ? trim((string)$_GET['order_number']) : '';
                              $queryEmail = isset($_GET['email']) ? trim((string)$_GET['email']) : '';

                              if ($queryNumber !== '') {
                                    try {
                                          if ($queryEmail !== '') {
                                                $order = db_one(
                                                      'SELECT o.* FROM orders o JOIN users u ON u.id = o.user_id WHERE o.order_number = ? AND u.email = ? LIMIT 1',
                                                      [$queryNumber, $queryEmail]
                                                );
                                          } else {
                                                $order = db_one('SELECT * FROM orders WHERE order_number = ? LIMIT 1', [$queryNumber]);
                                          }

                                          if ($order) {
                                                $items = db_all(
                                                      'SELECT oi.qty, oi.unit_price, v.sku, p.title
                                                       FROM order_items oi
                                                       JOIN variants v ON v.id = oi.variant_id
                                                       JOIN products p ON p.id = v.product_id
                                                       WHERE oi.order_id = ?
                                                       ORDER BY oi.id ASC',
                                                      [(int)$order['id']]
                                                );
                                                $shipments = db_all(
                                                      'SELECT * FROM shipments WHERE order_id = ? ORDER BY id ASC',
                                                      [(int)$order['id']]
                                                );
                                          } else {
                                                $error = 'No order found matching those details.';
                                          }
                                    } catch (Throwable $e) {
                                          $error = 'Unable to lookup order right now.';
                                    }
                              }
                              ?>

                              <?php if ($queryNumber !== '') { ?>
                                    <div class="mt-4">
                                          <?php if ($error !== '') { ?>
                                                <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
                                          <?php } elseif ($order) { ?>
                                                <div class="card p-4">
                                                      <div class="d-flex justify-content-between flex-wrap">
                                                            <div>
                                                                  <h4 class="mb-1">Order <?php echo htmlspecialchars((string)$order['order_number'], ENT_QUOTES); ?></h4>
                                                                  <div class="text-muted">Placed: <?php echo htmlspecialchars((string)($order['placed_at'] ?? '—'), ENT_QUOTES); ?></div>
                                                                  <div class="mt-1"><span class="badge bg-secondary text-uppercase">Status: <?php echo htmlspecialchars((string)$order['status'], ENT_QUOTES); ?></span></div>
                                                            </div>
                                                            <div class="text-end">
                                                                  <div>Subtotal: <strong><?php echo htmlspecialchars(number_format((float)$order['subtotal'], 2), ENT_QUOTES); ?></strong></div>
                                                                  <div>Shipping: <strong><?php echo htmlspecialchars(number_format((float)$order['shipping_total'], 2), ENT_QUOTES); ?></strong></div>
                                                                  <div>Tax: <strong><?php echo htmlspecialchars(number_format((float)$order['tax_total'], 2), ENT_QUOTES); ?></strong></div>
                                                                  <div class="fs-5 mt-1">Total: <strong><?php echo htmlspecialchars(number_format((float)$order['grand_total'], 2), ENT_QUOTES); ?></strong> <span class="text-muted"><?php echo htmlspecialchars((string)$order['currency'], ENT_QUOTES); ?></span></div>
                                                            </div>
                                                      </div>

                                                      <?php if (!empty($items)) { ?>
                                                            <div class="mt-4">
                                                                  <h5 class="mb-3">Items</h5>
                                                                  <div class="table-responsive">
                                                                        <table class="table align-middle">
                                                                              <thead>
                                                                                    <tr>
                                                                                          <th>Product</th>
                                                                                          <th>SKU</th>
                                                                                          <th class="text-end">Qty</th>
                                                                                          <th class="text-end">Unit Price</th>
                                                                                          <th class="text-end">Line Total</th>
                                                                                    </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                    <?php foreach ($items as $it) {
                                                                                          $line = (float)$it['qty'] * (float)$it['unit_price']; ?>
                                                                                          <tr>
                                                                                                <td><?php echo htmlspecialchars((string)$it['title'], ENT_QUOTES); ?></td>
                                                                                                <td><?php echo htmlspecialchars((string)$it['sku'], ENT_QUOTES); ?></td>
                                                                                                <td class="text-end"><?php echo htmlspecialchars((string)$it['qty'], ENT_QUOTES); ?></td>
                                                                                                <td class="text-end"><?php echo htmlspecialchars(number_format((float)$it['unit_price'], 2), ENT_QUOTES); ?></td>
                                                                                                <td class="text-end"><?php echo htmlspecialchars(number_format($line, 2), ENT_QUOTES); ?></td>
                                                                                          </tr>
                                                                                    <?php } ?>
                                                                              </tbody>
                                                                        </table>
                                                                  </div>
                                                            </div>
                                                      <?php } ?>

                                                      <div class="mt-4">
                                                            <h5 class="mb-3">Shipment</h5>
                                                            <?php if (!empty($shipments)) { ?>
                                                                  <?php foreach ($shipments as $s) { ?>
                                                                        <div class="border rounded p-3 mb-3">
                                                                              <div class="d-flex justify-content-between flex-wrap">
                                                                                    <div>
                                                                                          <div class="mb-1"><span class="badge bg-info text-dark text-uppercase"><?php echo htmlspecialchars((string)$s['status'], ENT_QUOTES); ?></span></div>
                                                                                          <div>Carrier: <strong><?php echo htmlspecialchars((string)($s['carrier'] ?? '—'), ENT_QUOTES); ?></strong></div>
                                                                                          <div>Service: <strong><?php echo htmlspecialchars((string)($s['service'] ?? '—'), ENT_QUOTES); ?></strong></div>
                                                                                          <div>Tracking #: <strong><?php echo htmlspecialchars((string)($s['tracking_no'] ?? '—'), ENT_QUOTES); ?></strong></div>
                                                                                    </div>
                                                                                    <div class="text-end">
                                                                                          <div>Shipped: <strong><?php echo htmlspecialchars((string)($s['shipped_at'] ?? '—'), ENT_QUOTES); ?></strong></div>
                                                                                          <div>Delivered: <strong><?php echo htmlspecialchars((string)($s['delivered_at'] ?? '—'), ENT_QUOTES); ?></strong></div>
                                                                                    </div>
                                                                              </div>
                                                                        </div>
                                                                  <?php } ?>
                                                            <?php } else { ?>
                                                                  <div class="alert alert-warning mb-0" role="alert">No shipment information yet. Your order will appear here once it ships.</div>
                                                            <?php } ?>
                                                      </div>
                                                </div>
                                          <?php } else { ?>
                                                <div class="alert alert-info" role="alert">Enter your order details above to see status.</div>
                                          <?php } ?>
                                    </div>
                              <?php } ?>

                        </div>
                  </div>
            </div>
      </section>

</main>