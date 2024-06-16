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
}
?>
