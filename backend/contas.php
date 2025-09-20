<?php
session_start();
include('../dbconfig/configbd.php');
header('Content-Type: application/json; charset=utf-8');

// exige login, igual ao seu fluxo
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

// filtros (todos opcionais)
$cpf     = $_GET['cpf']     ?? '';
$nome    = $_GET['nome']    ?? '';
$genero  = $_GET['genero']  ?? '';
$dt_nasc = $_GET['dt_nasc'] ?? '';

$limit   = max(1, min(200, (int)($_GET['limit']  ?? 100)));
$offset  = max(0,         (int)($_GET['offset'] ?? 0));

$sql = "SELECT cpf, nome, genero, dt_nasc
        FROM consulta_pessoas
        WHERE 1=1";

$params = [];
$types  = '';

// usa LIKE para permitir busca parcial (sem transformar nada)
if ($cpf !== '')     { $sql .= " AND cpf LIKE ?";     $params[] = "%$cpf%";     $types .= 's'; }
if ($nome !== '')    { $sql .= " AND nome LIKE ?";    $params[] = "%$nome%";    $types .= 's'; }
if ($genero !== '')  { $sql .= " AND genero LIKE ?";  $params[] = "%$genero%";  $types .= 's'; }
if ($dt_nasc !== '') { $sql .= " AND dt_nasc LIKE ?"; $params[] = "%$dt_nasc%"; $types .= 's'; }

$sql .= " ORDER BY nome ASC LIMIT ? OFFSET ?";
$params[] = $limit;  $types .= 'i';
$params[] = $offset; $types .= 'i';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$out = [];
while ($r = $res->fetch_assoc()) $out[] = $r;

echo json_encode($out);
