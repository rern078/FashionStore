<?php
// Simple PDO connection helper for Admin
// Usage:
//   require __DIR__ . '/database.php';
//   $pdo = db();

/**
 * Get singleton PDO instance.
 * Connection settings are read from environment variables with sensible defaults.
 *
 * FS_DB_DSN example: mysql:host=127.0.0.1;port=3306;dbname=fashionstore;charset=utf8mb4
 * FS_DB_USER example: root
 * FS_DB_PASS example: secret
 */
function db(): PDO
{
      static $pdo = null;
      if ($pdo instanceof PDO) {
            return $pdo;
      }

      $dsn = getenv('FS_DB_DSN') ?: 'mysql:host=127.0.0.1;port=3306;dbname=fashionstore;charset=utf8mb4';
      $user = getenv('FS_DB_USER') ?: 'root';
      $pass = getenv('FS_DB_PASS') ?: '';

      $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
      ];

      $pdo = new PDO($dsn, $user, $pass, $options);
      // Ensure strict SQL mode when MySQL is used
      try {
            $pdo->exec("SET SESSION sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
      } catch (Throwable $e) {
            // ignore if not supported
      }

      return $pdo;
}
