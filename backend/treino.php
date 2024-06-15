<?php
session_start();
include(__DIR__ . '/../dbconfig/configbd.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../frontend/login.html');
    exit();
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $grupo = $_POST['grupo'];
        $parte = $_POST['parte'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $observacao = $_POST['observacao'];

        $sql = "INSERT INTO treino (grupo, parte, nome, series, repeticao, observacao, idusuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssissi', $grupo, $parte, $nome, $series, $repeticao, $observacao, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Treino cadastrado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar treino.']);
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $grupo = $_POST['grupo'];
        $parte = $_POST['parte'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $observacao = $_POST['observacao'];

        $sql = "UPDATE treino SET grupo = ?, parte = ?, nome = ?, series = ?, repeticao = ?, observacao = ? WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssissii', $grupo, $parte, $nome, $series, $repeticao, $observacao, $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Treino atualizado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar treino.']);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM treino WHERE id = ? AND idusuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id, $userid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Treino excluído com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir treino.']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
}
?>
