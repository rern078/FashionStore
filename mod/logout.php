<?php
// Logout: destroy session and redirect to home
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

$baseUrl = isset($__CONFIG['site']['base_url']) ? (string)$__CONFIG['site']['base_url'] : '/';
header('Location: ' . $baseUrl . '?p=home');
exit;
