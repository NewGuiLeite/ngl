<?php
// C:\xampp\htdocs\ngl\backend\ver_treinos.php
require __DIR__ . '/bootstrap_session.php';
include(__DIR__ . '/../dbconfig/configbd.php');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($conn) || !($conn instanceof mysqli)) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'conexao_nao_definida']);
  exit;
}

// Se não logado, não redireciona: devolve 401 em JSON
if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  http_response_code(401);
  echo json_encode(['status'=>'error','message'=>'not_auth']);
  exit;
}

$userid = (int)($_SESSION['userid'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // LISTA treinos do usuário
  $sql = "SELECT id, grupo, parte, nome, series, repeticao, observacao, estado
          FROM treino
          WHERE idusuario = ?
          ORDER BY grupo ASC, parte ASC, nome ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $userid);
  if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'execute_failed','detail'=>$stmt->error]);
    exit;
  }
  $res = $stmt->get_result();
  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action    = $_POST['action']     ?? '';
  $idTreino  = (int)($_POST['id_treino'] ?? 0);

  if ($action === 'toggle') {
    $estado = $_POST['estado'] ?? 'normal';
    // normaliza valor
    $estado = ($estado === 'ativo') ? 'ativo' : 'normal';

    $sql = "UPDATE treino SET estado = ? WHERE id = ? AND idusuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $estado, $idTreino, $userid);

    if ($stmt->execute()) {
      echo json_encode(['status'=>'success','message'=>'Estado atualizado']);
    } else {
      http_response_code(500);
      echo json_encode(['status'=>'error','message'=>'Erro ao atualizar','detail'=>$stmt->error]);
    }
    exit;
  }

  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'acao_invalida']);
  exit;
}

// Método não permitido
http_response_code(405);
echo json_encode(['status'=>'error','message'=>'method_not_allowed']);
