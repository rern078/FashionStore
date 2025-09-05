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

// Handle currency switch globally
if (isset($_GET['set_currency'])) {
      $code = strtoupper(substr(trim((string)$_GET['set_currency']), 0, 3));
      if ($code !== '') {
            $_SESSION['currency'] = $code;
            $redirect = $_SERVER['HTTP_REFERER'] ?? ($config['site']['base_url'] ?? '/');
            header('Location: ' . $redirect);
            exit;
      }
}

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

$pageTitle = $config['site']['name'] ?? 'Website';

$__CONFIG = $config;
$__ROUTE = $route;

// require_once __DIR__ . '/admin/config/database.php';

require_once __DIR__ . '/admin/config/function.php';

require_once __DIR__ . '/admin/config/global.php';

require __DIR__ . '/inc/header.php';

if (is_file($modulePath)) {
      include $modulePath;
} else {
      http_response_code(404);
      echo '<main class="container"><h1>404</h1><p>Page not found.</p></main>';
}

require __DIR__ . '/inc/footer.php';

ob_end_flush();
