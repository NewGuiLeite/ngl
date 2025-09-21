<?php
// C:\xampp\htdocs\ngl\backend\logout.php
require __DIR__ . '/bootstrap_session.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
  setcookie(session_name(), '', time() - 3600, '/ngl');
}
session_destroy();

header('Location: /ngl/frontend/login.html');
exit;
