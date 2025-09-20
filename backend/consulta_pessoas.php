<?php
session_start();
include('../dbconfig/configbd.php');
mysqli_set_charset($conn, 'utf8mb4');
header('Content-Type: application/json; charset=utf-8');

// verifica login (igual ao seu padrão)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../frontend/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // filtros opcionais
    $cpf   = $_GET['cpf']  ?? '';
    $nome  = $_GET['nome'] ?? '';

    // paginação simples
    $limit  = max(1, min(200, (int)($_GET['limit']  ?? 100)));
    $offset = max(0,         (int)($_GET['offset'] ?? 0));

    // base SQL
    $sql = "SELECT cpf, nome, genero, dt_nasc
            FROM consulta_pessoas
            WHERE 1=1";

    $types  = '';
    $params = [];

    if ($cpf !== '')  { $sql .= " AND cpf  LIKE ?"; $types .= 's'; $params[] = "%$cpf%"; }
    if ($nome !== '') { $sql .= " AND nome LIKE ?"; $types .= 's'; $params[] = "%$nome%"; }

    $sql .= " ORDER BY nome ASC LIMIT ? OFFSET ?";
    $types .= 'ii'; $params[] = $limit; $params[] = $offset;

    $stmt = $conn->prepare($sql);
    if (!$stmt) { echo json_encode([]); exit; }

    // bind dinâmico
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($r = $result->fetch_assoc()) { $rows[] = $r; }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
    exit;
}

// se não for GET
http_response_code(405);
echo json_encode([]);
