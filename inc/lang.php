<?php

// Translation helpers
// - translate($key): returns translated string or falls back to the key
// - tranSlate($key): alias kept for compatibility

// Handle language switch globally
if (isset($_GET['set_lang'])) {
      $code = strtolower(preg_replace('/[^a-z]/i', '', (string)$_GET['set_lang']));
      if ($code !== '') {
            $_SESSION['lang'] = $code;
            $redirect = $_SERVER['HTTP_REFERER'] ?? ($config['site']['base_url'] ?? '/');
            header('Location: ' . $redirect);
            exit;
      }
}

// Supported language codes
$__SUPPORTED_LANGS = ['en', 'cn', 'kh', 'vn', 'lo', 'ma', 'ph', 'sp', 'fr', 'gm', 'ru','jp','kr','de','it','es','pt','nl','sv','no','fi','da','is','lt','lv','et','pl','ro','bg','hr','sl','sq','mk','az','hy','ka','ru','ja','ko','zh','th','vi','id','ms','fil','ml','ta','te','ur','hi','bn','mr','gu','pa','or','sd','as','ks','ne','bh','np'];

// Always attempt to detect language from request path (works with both pretty and query routes)
$__LANG_CODE = null;
$__REQUEST_PATH = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$__BASE_URL_PREFIX = rtrim((string)($config['site']['base_url'] ?? '/'), '/');
if ($__BASE_URL_PREFIX !== '' && $__BASE_URL_PREFIX !== '/' && strpos($__REQUEST_PATH, $__BASE_URL_PREFIX) === 0) {
      $__REQUEST_PATH = (string)substr($__REQUEST_PATH, strlen($__BASE_URL_PREFIX));
}
$__REQUEST_SEGMENTS = explode('/', trim($__REQUEST_PATH, "/ \t\n\r\0\x0B"));
$__FIRST_SEGMENT = strtolower((string)($__REQUEST_SEGMENTS[0] ?? ''));
if ($__FIRST_SEGMENT !== '' && in_array($__FIRST_SEGMENT, $__SUPPORTED_LANGS, true)) {
      $__LANG_CODE = $__FIRST_SEGMENT;
      $_SESSION['lang'] = $__LANG_CODE;
}

// If route is empty, attempt to infer it from REQUEST_URI (pretty URLs)
if ($route === '') {
      $requestPath = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
      $baseUrlPrefix = rtrim((string)($config['site']['base_url'] ?? '/'), '/');
      if ($baseUrlPrefix !== '' && $baseUrlPrefix !== '/' && strpos($requestPath, $baseUrlPrefix) === 0) {
            $requestPath = (string)substr($requestPath, strlen($baseUrlPrefix));
      }
      $route = trim($requestPath, "/ \t\n\r\0\x0B");
}

// Extract leading language segment from $route for pretty URLs when route came from path
if ($route !== '') {
      $parts = explode('/', $route);
      $first = strtolower((string)($parts[0] ?? ''));
      if ($first !== '' && in_array($first, $__SUPPORTED_LANGS, true)) {
            $__LANG_CODE = $first;
            $_SESSION['lang'] = $__LANG_CODE;
            array_shift($parts);
            $route = implode('/', $parts);
      }
}

// Load language strings with English as base and selected language overriding
$__LANG_CODE = $__LANG_CODE ?? (isset($_SESSION['lang']) ? (string)$_SESSION['lang'] : 'en');
$lang = [];
$rootDir = dirname(__DIR__);
$defaultLangPath = $rootDir . '/lang/en/mod_config_lang.php';
if (is_file($defaultLangPath)) {
      include $defaultLangPath; // populates $lang
}
$langDefault = $lang;
$lang = [];
$selectedLangPath = $rootDir . '/lang/' . $__LANG_CODE . '/mod_config_lang.php';
if ($__LANG_CODE !== 'en' && is_file($selectedLangPath)) {
      include $selectedLangPath; // populates $lang
}
$langSelected = $lang;
$lang = array_replace(is_array($langDefault) ? $langDefault : [], is_array($langSelected) ? $langSelected : []);
// Derived language-aware base paths for link generation
$__BASE_URL = rtrim((string)($config['site']['base_url'] ?? '/'), '/');
if ($__BASE_URL === '') {
      $__BASE_URL = '/';
}
$__LANG_PREFIX = '/' . $__LANG_CODE;
$__LANG_BASE = rtrim($__BASE_URL, '/') . $__LANG_PREFIX . '/';

if (!array_key_exists($route, $routes)) {
      $primary = $route !== '' ? explode('/', $route)[0] : '';
      if ($primary !== '' && array_key_exists($primary, $routes)) {
            $route = $primary;
      } else {
            $route = '';
      }
}

if (!function_exists('translate')) {
      function translate($key)
      {
            global $lang;
            if (isset($lang[$key]) && is_string($lang[$key]) && $lang[$key] !== '') {
                  return $lang[$key];
            }
            return $key;
      }
}

if (!function_exists('tranSlate')) {
      function tranSlate($key)
      {
            return translate($key);
      }
}
