<?php
// C:\xampp\htdocs\ngl\backend\treino.php
require __DIR__ . '/bootstrap_session.php';
include(__DIR__ . '/../dbconfig/configbd.php');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  http_response_code(401);
  echo json_encode(['status'=>'error','message'=>'not_auth']);
  exit;
}

if (!isset($conn) || !($conn instanceof mysqli)) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'conexao_nao_definida']);
  exit;
}

$userid = (int)($_SESSION['userid'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // LISTAR
  $sql = "SELECT id, grupo, parte, nome, series, repeticao, observacao
          FROM treino
          WHERE idusuario = ?
          ORDER BY grupo ASC, parte ASC, nome ASC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $userid);
  $stmt->execute();
  $res = $stmt->get_result();

  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'register') {
    $grupo      = trim($_POST['grupo'] ?? '');
    $parte      = trim($_POST['parte'] ?? '');
    $nome       = trim($_POST['nome'] ?? '');
    $series     = (int)($_POST['series'] ?? 0);
    $repeticao  = trim($_POST['repeticao'] ?? '');
    $observacao = trim($_POST['observacao'] ?? '');

    $sql = "INSERT INTO treino (grupo, parte, nome, series, repeticao, observacao, idusuario)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissi', $grupo, $parte, $nome, $series, $repeticao, $observacao, $userid);

    if ($stmt->execute()) {
      echo json_encode(['status'=>'success','message'=>'Treino cadastrado com sucesso!']);
    } else {
      http_response_code(500);
      echo json_encode(['status'=>'error','message'=>'Erro ao cadastrar','detail'=>$stmt->error]);
    }
    exit;
  }

  if ($action === 'edit') {
    $id         = (int)($_POST['id'] ?? 0);
    $grupo      = trim($_POST['grupo'] ?? '');
    $parte      = trim($_POST['parte'] ?? '');
    $nome       = trim($_POST['nome'] ?? '');
    $series     = (int)($_POST['series'] ?? 0);
    $repeticao  = trim($_POST['repeticao'] ?? '');
    $observacao = trim($_POST['observacao'] ?? '');

    $sql = "UPDATE treino
            SET grupo=?, parte=?, nome=?, series=?, repeticao=?, observacao=?
            WHERE id=? AND idusuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissii', $grupo, $parte, $nome, $series, $repeticao, $observacao, $id, $userid);

    if ($stmt->execute()) {
      echo json_encode(['status'=>'success','message'=>'Treino atualizado com sucesso!']);
    } else {
      http_response_code(500);
      echo json_encode(['status'=>'error','message'=>'Erro ao atualizar','detail'=>$stmt->error]);
    }
    exit;
  }

  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);

    $sql = "DELETE FROM treino WHERE id=? AND idusuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id, $userid);

    if ($stmt->execute()) {
      echo json_encode(['status'=>'success','message'=>'Treino excluído com sucesso!']);
    } else {
      http_response_code(500);
      echo json_encode(['status'=>'error','message'=>'Erro ao excluir','detail'=>$stmt->error]);
    }
    exit;
  }

  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'acao_invalida']);
  exit;
}

// método inválido
http_response_code(405);
echo json_encode(['status'=>'error','message'=>'method_not_allowed']);
