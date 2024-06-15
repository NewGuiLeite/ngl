<?php
session_start();
include(__DIR__ . '/../dbconfig/configbd.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../frontend/login.html');
    exit();
}

$userid = $_SESSION['userid'];

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
?>
