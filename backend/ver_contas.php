<?php
session_start();
include('../dbconfig/configbd.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../frontend/login.html');
    exit();
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'];

    if ($action === 'markAsPaid') {
        $sql = "UPDATE contas SET status_pagamento = 'paga' WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Conta marcada como paga com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao marcar a conta como paga.']);
        }
    } elseif ($action === 'markAsUnpaid') {
        $sql = "UPDATE contas SET status_pagamento = 'nao paga' WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Conta marcada como não paga com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao marcar a conta como não paga.']);
        }
    }
}
?>
