<?php
session_start();
include('../dbconfig/configbd.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../frontend/login.html');
    exit();
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $status_pagamento = $_POST['status_pagamento'];
        $observacao = $_POST['observacao'];

        $sql = "INSERT INTO contas (descricao, valor, status_pagamento, observacao, idusuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdssi', $descricao, $valor, $status_pagamento, $observacao, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Conta cadastrada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar conta.']);
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $status_pagamento = $_POST['status_pagamento'];
        $observacao = $_POST['observacao'];

        $sql = "UPDATE contas SET descricao = ?, valor = ?, status_pagamento = ?, observacao = ? WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdssii', $descricao, $valor, $status_pagamento, $observacao, $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Conta atualizada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar conta.']);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM contas WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Conta excluída com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir conta.']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM contas WHERE idusuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $contas = array();
    while ($row = $result->fetch_assoc()) {
        $contas[] = $row;
    }

    echo json_encode($contas);
}
?>
