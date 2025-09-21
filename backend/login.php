<?php
// C:\xampp\htdocs\ngl\backend\login.php
require __DIR__ . '/bootstrap_session.php';
include(__DIR__ . '/../dbconfig/configbd.php'); // usa sua conexão existente

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "Método de requisição inválido.";
  exit;
}

$username = strtolower(trim($_POST['username'] ?? ''));
$password = $_POST['senha'] ?? '';

if ($username === '' || $password === '') {
  header('Location: /ngl/frontend/login.html?error=Preencha%20usu%C3%A1rio%20e%20senha&username='.$username);
  exit;
}

if (!isset($conn)) { die("Erro na conexão com o banco de dados."); }

$sql = "SELECT * FROM usuario WHERE LOWER(usuario) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['senha'])) {
    session_regenerate_id(true);
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user['usuario'];
    $_SESSION['userid']   = $user['id'];
    header('Location: /ngl/frontend/index.html');
    exit;
  }
  header('Location: /ngl/frontend/login.html?error=Senha%20incorreta&username='.$username);
  exit;
}

header('Location: /ngl/frontend/login.html?error=Usu%C3%A1rio%20n%C3%A3o%20encontrado&username='.$username);
exit;
