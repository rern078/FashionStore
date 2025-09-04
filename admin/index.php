<?php
// Admin front controller / simple router
ob_start();
$config = require __DIR__ . '/config/config.php';

date_default_timezone_set($config['site']['timezone'] ?? 'UTC');

// Start session for accessing logged-in user context
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

$routes = $config['routes'] ?? [];
$route = isset($_GET['p']) ? trim((string)$_GET['p'], "/ \t\n\r\0\x0B") : '';

if (!array_key_exists($route, $routes)) {
      $primary = $route !== '' ? explode('/', $route)[0] : '';
      if ($primary !== '' && array_key_exists($primary, $routes)) {
            $route = $primary;
      } else {
            $route = '';
      }
}

$moduleRel = $routes[$route] ?? 'dashboard.php';
$modulePath = __DIR__ . '/' . $moduleRel;

$pageTitle = $config['site']['name'] ?? 'Admin';
// Optional title override: admin/title.php may set $pageTitle
$titleOverride = __DIR__ . '/title.php';
if (is_file($titleOverride)) {
      include $titleOverride;
}
$__CONFIG = $config;
$__ROUTE = $route;

require __DIR__ . '/header.php';

if (is_file($modulePath)) {
      include $modulePath;
} else {
      http_response_code(404);
      echo '<div class="container py-4"><h1>404</h1><p>Admin page not found.</p></div>';
}

require __DIR__ . '/footer.php';

ob_end_flush();
