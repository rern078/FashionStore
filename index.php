<?php
// Front controller / simple router
ob_start();
$config = require __DIR__ . '/config.php';

date_default_timezone_set($config['site']['timezone'] ?? 'UTC');

// Start session early for auth handling
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

$routes = $config['routes'] ?? [];
$route = isset($_GET['p']) ? trim((string)$_GET['p'], "/ \t\n\r\0\x0B") : '';

// Allow nested paths like product/123 to resolve to 'product'
if (!array_key_exists($route, $routes)) {
      $primary = $route !== '' ? explode('/', $route)[0] : '';
      if ($primary !== '' && array_key_exists($primary, $routes)) {
            $route = $primary;
      } else {
            $route = '';
      }
}

$moduleRel = $routes[$route] ?? 'index.php';
$modulePath = __DIR__ . '/mod/' . $moduleRel;

// Default title; can be overridden in inc/title.inc.php
$pageTitle = $config['site']['name'] ?? 'Website';
$titleOverride = __DIR__ . '/inc/title.inc.php';
if (is_file($titleOverride)) {
      include $titleOverride; // may set $pageTitle using $__ROUTE or other context
}

// Make config and route available to templates
$__CONFIG = $config;
$__ROUTE = $route;

require __DIR__ . '/inc/header.php';

if (is_file($modulePath)) {
      include $modulePath;
} else {
      http_response_code(404);
      echo '<main class="container"><h1>404</h1><p>Page not found.</p></main>';
}

require __DIR__ . '/inc/footer.php';

ob_end_flush();
