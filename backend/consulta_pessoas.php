<?php
session_start();
include('../dbconfig/configbd.php');
mysqli_set_charset($conn, 'utf8mb4');
header('Content-Type: application/json; charset=utf-8');

// Em vez de redirecionar, responde 401 para o AJAX tratar
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'not_auth']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed']);
    exit();
}

$cpf    = $_GET['cpf']  ?? '';
$nome   = $_GET['nome'] ?? '';
$limit  = max(1, min(200, (int)($_GET['limit']  ?? 100)));
$offset = max(0,         (int)($_GET['offset'] ?? 0));

$sql = "SELECT cpf, nome, genero, dt_nasc
        FROM consulta_pessoas
        WHERE 1=1";

$types = '';
$params = [];

if ($cpf !== '')  { $sql .= " AND cpf  LIKE ?"; $types .= 's'; $params[] = "%$cpf%"; }
if ($nome !== '') { $sql .= " AND nome LIKE ?"; $types .= 's'; $params[] = "%$nome%"; }

$sql .= " ORDER BY nome ASC LIMIT ? OFFSET ?";
$types .= 'ii'; $params[] = $limit; $params[] = $offset;

$stmt = $conn->prepare($sql);
if (!$stmt) { http_response_code(500); echo json_encode(['error'=>$conn->error]); exit; }
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while ($r = $res->fetch_assoc()) $rows[] = $r;

echo json_encode($rows, JSON_UNESCAPED_UNICODE);
