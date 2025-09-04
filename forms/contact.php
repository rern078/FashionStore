<?php
// Handle Contact form submission: insert into contact_messages and redirect back
if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
}

require_once __DIR__ . '/../admin/config/function.php';

// Simple helper to redirect
function redirect_contact(string $query): void
{
      header('Location: /?p=contact' . ($query !== '' ? ('&' . $query) : ''));
      exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
      redirect_contact('error=Invalid%20request');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$phone = trim($_POST['phone'] ?? ''); // optional (not present in current form)

// Basic validation
if ($name === '' || $email === '' || $subject === '' || $message === '') {
      redirect_contact('error=All%20fields%20are%20required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      redirect_contact('error=Invalid%20email%20address');
}

try {
      // Insert into DB
      db_exec(
            'INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, ?)',
            [
                  $name,
                  $email,
                  $phone !== '' ? $phone : null,
                  $subject,
                  $message,
                  'new',
            ]
      );
      redirect_contact('sent=1');
} catch (Throwable $e) {
      redirect_contact('error=Could%20not%20send%20message');
}
