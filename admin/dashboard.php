<?php require_once __DIR__ . '/config/function.php';
$currencyCode = strtoupper((string)($_SESSION['currency'] ?? 'USD'));
$monthStart = date('Y-m-01');
$totalsMonth = null;
try {
      $totalsMonth = db_one(
            "SELECT 
                  SUM(CASE WHEN type='income' THEN amount ELSE 0 END) AS income_total,
                  SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) AS expense_total
             FROM finance_entries
             WHERE currency = ? AND entry_date >= ?",
            [$currencyCode, $monthStart]
      );
} catch (Throwable $e) {
      $totalsMonth = ['income_total' => 0, 'expense_total' => 0];
}
$monthIncome = (float)($totalsMonth['income_total'] ?? 0);
$monthExpense = (float)($totalsMonth['expense_total'] ?? 0);
$monthNet = $monthIncome - $monthExpense;

// Weekly metrics
$sevenDaysAgo = date('Y-m-d H:i:s', time() - 7 * 24 * 60 * 60);
$weeklySales = 0.0;
$weeklyOrders = 0;
$visitorsOnline = 0;
try {
      $row = db_one(
            "SELECT SUM(p.amount) AS total
             FROM payments p
             JOIN orders o ON o.id = p.order_id
             WHERE p.status = 'captured' AND p.captured_at IS NOT NULL AND p.captured_at >= ? AND o.currency = ?",
            [$sevenDaysAgo, $currencyCode]
      );
      $weeklySales = (float)($row['total'] ?? 0);
} catch (Throwable $e) {
      $weeklySales = 0.0;
}
try {
      $row = db_one(
            "SELECT COUNT(*) AS c FROM orders WHERE placed_at IS NOT NULL AND placed_at >= ? AND status <> 'cancelled'",
            [$sevenDaysAgo]
      );
      $weeklyOrders = (int)($row['c'] ?? 0);
} catch (Throwable $e) {
      $weeklyOrders = 0;
}
try {
      $onlineSince = date('Y-m-d H:i:s', time() - 5 * 60);
      $row = db_one(
            "SELECT COUNT(DISTINCT session_id) AS c FROM carts WHERE updated_at >= ?",
            [$onlineSince]
      );
      $visitorsOnline = (int)($row['c'] ?? 0);
} catch (Throwable $e) {
      $visitorsOnline = 0;
}

// Today sales (captured payments today in current currency)
$todaySales = 0.0;
try {
      $todayStart = date('Y-m-d 00:00:00');
      $tomorrowStart = date('Y-m-d 00:00:00', time() + 24 * 60 * 60);
      $row = db_one(
            "SELECT SUM(p.amount) AS total
             FROM payments p
             JOIN orders o ON o.id = p.order_id
             WHERE p.status = 'captured' AND p.captured_at >= ? AND p.captured_at < ? AND o.currency = ?",
            [$todayStart, $tomorrowStart, $currencyCode]
      );
      $todaySales = (float)($row['total'] ?? 0);
} catch (Throwable $e) {
      $todaySales = 0.0;
}
?>
<div class="page-header">
      <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
            </span> Dashboard
      </h3>
      <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
            </ul>
      </nav>
</div>
</div>
<div class="row">
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">This Month Income <i class="mdi mdi-cash mdi-24px float-end"></i></h4>
                        <h2 class="mb-0">$ <?php echo number_format($monthIncome, 2); ?> <?php echo htmlspecialchars($currencyCode, ENT_QUOTES); ?></h2>
                  </div>
            </div>
      </div>
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">This Month Expenses <i class="mdi mdi-cash-remove mdi-24px float-end"></i></h4>
                        <h2 class="mb-0">$ <?php echo number_format($monthExpense, 2); ?> <?php echo htmlspecialchars($currencyCode, ENT_QUOTES); ?></h2>
                  </div>
            </div>
      </div>
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">This Month Net <i class="mdi mdi-scale-balance mdi-24px float-end"></i></h4>
                        <h2 class="mb-0">$ <?php echo number_format($monthNet, 2); ?> <?php echo htmlspecialchars($currencyCode, ENT_QUOTES); ?></h2>
                  </div>
            </div>
      </div>
</div>
<div class="row">
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Today's Sales <i class="mdi mdi-cash-multiple mdi-24px float-end"></i></h4>
                        <h2 class="mb-0">$ <?php echo number_format($todaySales, 2); ?> <?php echo htmlspecialchars($currencyCode, ENT_QUOTES); ?></h2>
                  </div>
            </div>
      </div>
</div>
<div class="row">
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Weekly Sales <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-0">$ <?php echo number_format($weeklySales, 2); ?> <?php echo htmlspecialchars($currencyCode, ENT_QUOTES); ?></h2>
                        <h6 class="card-text">Last 7 days</h6>
                  </div>
            </div>
      </div>
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Weekly Orders <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-0"><?php echo (int)$weeklyOrders; ?></h2>
                        <h6 class="card-text">Placed last 7 days</h6>
                  </div>
            </div>
      </div>
      <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                  <div class="card-body">
                        <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Visitors Online <i class="mdi mdi-diamond mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-0"><?php echo (int)$visitorsOnline; ?></h2>
                        <h6 class="card-text">Active past 5 minutes</h6>
                  </div>
            </div>
      </div>
</div>
<div class="row">
      <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <div class="clearfix">
                              <h4 class="card-title float-start">Visit And Sales Statistics</h4>
                              <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="visit-sale-chart" class="mt-4"></canvas>
                  </div>
            </div>
      </div>
      <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Traffic Sources</h4>
                        <div class="doughnutjs-wrapper d-flex justify-content-center">
                              <canvas id="traffic-chart"></canvas>
                        </div>
                        <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                  </div>
            </div>
      </div>
</div>
<div class="row">
      <div class="col-12 grid-margin">
            <div class="card">
                  <div class="card-body">
                        <h4 class="card-title">Recent Tickets</h4>
                        <div class="table-responsive">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th> Assignee </th>
                                                <th> Subject </th>
                                                <th> Status </th>
                                                <th> Last Update </th>
                                                <th> Tracking ID </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                                <td>
                                                      <img src="assets/images/faces/face1.jpg" class="me-2" alt="image"> David Grey
                                                </td>
                                                <td> Fund is not recieved </td>
                                                <td>
                                                      <label class="badge badge-gradient-success">DONE</label>
                                                </td>
                                                <td> Dec 5, 2017 </td>
                                                <td> WD-12345 </td>
                                          </tr>
                                          <tr>
                                                <td>
                                                      <img src="assets/images/faces/face2.jpg" class="me-2" alt="image"> Stella Johnson
                                                </td>
                                                <td> High loading time </td>
                                                <td>
                                                      <label class="badge badge-gradient-warning">PROGRESS</label>
                                                </td>
                                                <td> Dec 12, 2017 </td>
                                                <td> WD-12346 </td>
                                          </tr>
                                          <tr>
                                                <td>
                                                      <img src="assets/images/faces/face3.jpg" class="me-2" alt="image"> Marina Michel
                                                </td>
                                                <td> Website down for one week </td>
                                                <td>
                                                      <label class="badge badge-gradient-info">ON HOLD</label>
                                                </td>
                                                <td> Dec 16, 2017 </td>
                                                <td> WD-12347 </td>
                                          </tr>
                                          <tr>
                                                <td>
                                                      <img src="assets/images/faces/face4.jpg" class="me-2" alt="image"> John Doe
                                                </td>
                                                <td> Loosing control on server </td>
                                                <td>
                                                      <label class="badge badge-gradient-danger">REJECTED</label>
                                                </td>
                                                <td> Dec 3, 2017 </td>
                                                <td> WD-12348 </td>
                                          </tr>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>
</div>