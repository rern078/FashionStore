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
$phone = trim($_POST['phone'] ?? ''); // optional
// Handle optional attachment upload
$attachmentPath = null;
if (!empty($_FILES['attachment']['name'] ?? '')) {
      $tmp = $_FILES['attachment']['tmp_name'] ?? '';
      $attachmentOriginalName = basename((string)($_FILES['attachment']['name'] ?? ''));
      $size = (int)($_FILES['attachment']['size'] ?? 0);
      $ext = strtolower((string)pathinfo($attachmentOriginalName, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
      if ($size > 0 && $size <= 3 * 1024 * 1024 && in_array($ext, $allowed, true)) {
            $uploadDir = __DIR__ . '/../admin/assets/images/contact_attachments';
            if (!is_dir($uploadDir)) {
                  @mkdir($uploadDir, 0775, true);
            }
            $safeName = 'contact_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dest = $uploadDir . '/' . $safeName;
            if (is_uploaded_file($tmp) && move_uploaded_file($tmp, $dest)) {
                  $attachmentPath = 'admin/assets/images/contact_attachments/' . $safeName;
            }
      }
}

// Basic validation
if ($name === '' || $email === '' || $subject === '' || $message === '') {
      redirect_contact('error=All%20fields%20are%20required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      redirect_contact('error=Invalid%20email%20address');
}

try {
      // Insert into DB (attachment if present; if column missing, ignore)
      try {
            db_exec(
                  'INSERT INTO contact_messages (name, email, phone, subject, message, status, attachment_url) VALUES (?, ?, ?, ?, ?, ?, ?)',
                  [
                        $name,
                        $email,
                        $phone !== '' ? $phone : null,
                        $subject,
                        $message,
                        'new',
                        $attachmentPath,
                  ]
            );
      } catch (Throwable $e) {
            // Fallback if attachment_url column does not exist
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
      }
      redirect_contact('sent=1');
} catch (Throwable $e) {
      redirect_contact('error=Could%20not%20send%20message');
}
