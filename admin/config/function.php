<?php
// Common helper functions for Admin can be added here as needed

require_once __DIR__ . '/database.php';

/**
 * Run a prepared statement and return all rows.
 */
function db_all(string $sql, array $params = []): array
{
      $stmt = db()->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll();
}

/**
 * Run a prepared statement and return first row or null.
 */
function db_one(string $sql, array $params = []): ?array
{
      $stmt = db()->prepare($sql);
      $stmt->execute($params);
      $row = $stmt->fetch();
      return $row === false ? null : $row;
}

/**
 * Run an INSERT/UPDATE/DELETE statement and return affected rows.
 */
function db_exec(string $sql, array $params = []): int
{
      $stmt = db()->prepare($sql);
      $stmt->execute($params);
      return $stmt->rowCount();
}

/**
 * Get last inserted ID as integer.
 */
function db_last_insert_id(): int
{
      return (int)db()->lastInsertId();
}

/**
 * Make URL-friendly slug from text.
 */
function make_slug(string $text): string
{
      $text = strtolower(trim($text));
      $text = preg_replace('~[^a-z0-9]+~', '-', $text);
      $text = trim($text, '-');
      return substr($text, 0, 200);
}


// Map platform to default Bootstrap Icon class
function social_default_icon(string $platform): string
{
      $map = [
            'facebook' => 'bi bi-facebook',
            'instagram' => 'bi bi-instagram',
            'twitter' => 'bi bi-twitter-x',
            'tiktok' => 'bi bi-tiktok',
            'youtube' => 'bi bi-youtube',
            'linkedin' => 'bi bi-linkedin',
            'pinterest' => 'bi bi-pinterest',
            'telegram' => 'bi bi-telegram',
            'whatsapp' => 'bi bi-whatsapp',
            'other' => '',
      ];
      $key = strtolower(trim($platform));
      return $map[$key] ?? '';
}

/**
 * Ensure directory exists (create recursively if missing).
 */
function fs_ensure_directory(string $directoryPath): void
{
      if (is_dir($directoryPath)) {
            return;
      }
      @mkdir($directoryPath, 0775, true);
}

/**
 * Get filesystem directory for admin brand images.
 */
function fs_admin_brands_images_dir(): string
{
      // admin/config -> admin
      $adminRoot = dirname(__DIR__);
      $dir = $adminRoot . '/assets/images/brands';
      return $dir;
}

/**
 * Get URL prefix for admin assets.
 */
function fs_admin_base_url_prefix(): string
{
      // Using hardcoded prefix to avoid relying on runtime globals
      return '/admin';
}

/**
 * Convert an admin-root-relative URL (e.g., /admin/assets/...) to filesystem path.
 */
function fs_admin_url_to_path(string $url): ?string
{
      $prefix = fs_admin_base_url_prefix();
      if (strpos($url, $prefix . '/') !== 0) {
            return null;
      }
      $adminRoot = dirname(__DIR__);
      $relative = substr($url, strlen($prefix));
      return $adminRoot . $relative;
}

/**
 * Sanitize a filename by keeping alphanumerics, dashes, underscores and dots.
 */
function fs_sanitize_filename(string $name): string
{
      $name = preg_replace('~[^a-zA-Z0-9_\.-]+~', '-', $name);
      return trim($name, '-');
}

/**
 * Save uploaded brand logo image and return its public URL, or null if none uploaded.
 */
function fs_save_uploaded_logo(string $fieldName = 'logo_file'): ?string
{
      if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
            return null;
      }

      $file = $_FILES[$fieldName];
      if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null; // nothing uploaded or error
      }

      // Basic validations
      $maxBytes = 5 * 1024 * 1024; // 5 MB
      if (($file['size'] ?? 0) <= 0 || $file['size'] > $maxBytes) {
            return null;
      }

      $tmpPath = $file['tmp_name'] ?? '';
      if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            return null;
      }

      // Detect MIME and extension
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $mime = $finfo->file($tmpPath) ?: '';
      $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
      ];
      if (!isset($allowed[$mime])) {
            return null;
      }

      $ext = $allowed[$mime];
      $origName = (string)($file['name'] ?? 'logo');
      $base = pathinfo($origName, PATHINFO_FILENAME);
      $base = strtolower(make_slug($base !== '' ? $base : 'logo'));
      $base = $base !== '' ? $base : 'logo';

      // Unique filename
      $filename = fs_sanitize_filename($base . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext);

      $targetDir = fs_admin_brands_images_dir();
      fs_ensure_directory($targetDir);
      $targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

      if (!@move_uploaded_file($tmpPath, $targetPath)) {
            return null;
      }

      // Build public URL
      $url = fs_admin_base_url_prefix() . '/assets/images/brands/' . $filename;
      return $url;
}

/**
 * Delete a file by its admin URL if it's within the admin directory; returns true on success.
 */
function fs_delete_admin_file_by_url(?string $url): bool
{
      if (!$url) {
            return false;
      }
      $path = fs_admin_url_to_path($url);
      if ($path && is_file($path)) {
            return @unlink($path) === true;
      }
      return false;
}
