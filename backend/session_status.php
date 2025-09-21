<?php
// C:\xampp\htdocs\ngl\backend\session_status.php
require __DIR__ . '/bootstrap_session.php';
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
  'is_logged_in' => isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true,
  'username'     => $_SESSION['username'] ?? null,
  'userid'       => $_SESSION['userid']   ?? null,
], JSON_UNESCAPED_UNICODE);
