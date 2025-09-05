<?php
ob_start();
http_response_code(404);

$config = require __DIR__ . '/config.php';

date_default_timezone_set($config['site']['timezone'] ?? 'UTC');

if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

$__CONFIG = $config;
$__ROUTE = '404';
$pageTitle = '404 - Page Not Found';

require_once __DIR__ . '/admin/config/function.php';
require_once __DIR__ . '/admin/config/global.php';

require __DIR__ . '/inc/header.php';
?>
<main class="container py-5">
      <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-8 col-lg-7">
                  <div class="display-1 fw-bold">404</div>
                  <h1 class="mb-3">Page not found</h1>
                  <p class="text-muted mb-4">The page you’re looking for doesn’t exist or has been moved.</p>
                  <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>" class="btn btn-primary">
                              <i class="bi bi-house-door me-1"></i> Back to Home
                        </a>
                        <a href="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>?p=category" class="btn btn-outline-primary">
                              <i class="bi bi-grid me-1"></i> Browse Categories
                        </a>
                  </div>
                  <form action="<?php echo htmlspecialchars($__CONFIG['site']['base_url'], ENT_QUOTES); ?>" method="get" class="mt-4" role="search">
                        <input type="hidden" name="p" value="category">
                        <div class="input-group input-group-lg">
                              <input type="text" name="q" class="form-control" placeholder="Search for products...">
                              <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                  </form>
            </div>
      </div>
</main>
<?php
require __DIR__ . '/inc/footer.php';
ob_end_flush();
?>