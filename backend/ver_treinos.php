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
    $sql = "SELECT * FROM treino WHERE idusuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $treinos = array();
    while ($row = $result->fetch_assoc()) {
        $treinos[] = $row;
    }

    echo json_encode($treinos);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id_treino = $_POST['id_treino'];

    if ($action === 'toggle') {
        $estado = $_POST['estado'];

        // Atualiza o estado do treino
        $sql = "UPDATE treino SET estado = ? WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $estado, $id_treino, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Estado do treino atualizado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar estado do treino.']);
        }
    }
}
?>
