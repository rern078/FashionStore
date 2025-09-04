<?php
// Admin logout: destroy session then redirect to frontend home
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// Load frontend config to get base URL
$frontendConfigPath = __DIR__ . '/../config.php';
if (is_file($frontendConfigPath)) {
      $frontendConf = require $frontendConfigPath;
      $frontendBase = isset($frontendConf['site']['base_url']) ? (string)$frontendConf['site']['base_url'] : '/';
} else {
      $frontendBase = '/';
}

header('Location: ' . $frontendBase);
exit;
