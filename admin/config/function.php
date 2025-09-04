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
