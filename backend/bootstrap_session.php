<?php
// C:\xampp\htdocs\ngl\backend\bootstrap_session.php
// Padroniza TUDO da sessão pra não “cair” e nem conflitar entre páginas.

session_name('NGLSESS'); // nome fixo, evita conflito com outros projetos

$secure   = false;          // true se usar https
$httponly = true;
$samesite = 'Lax';          // Lax é bom pra navegação normal
$lifetime = 60 * 60 * 6;    // 6 horas

// Define cookie da sessão com path DO PROJETO
session_set_cookie_params([
  'lifetime' => $lifetime,
  'path'     => '/ngl',
  'domain'   => '',         // host atual (localhost ou 127.0.0.1)
  'secure'   => $secure,
  'httponly' => $httponly,
  'samesite' => $samesite,
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Mantém a sessão “viva” estendendo o cookie a cada request
setcookie(session_name(), session_id(), [
  'expires'  => time() + $lifetime,
  'path'     => '/ngl',
  'domain'   => '',
  'secure'   => $secure,
  'httponly' => $httponly,
  'samesite' => $samesite,
]);
