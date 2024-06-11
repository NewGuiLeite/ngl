<?php
include '../dbconfig/configbd.php'; // Inclui o arquivo de configuração do banco de dados

$sql = "SELECT * FROM treino";
$retorno = mysqli_query($conn, $sql);

$treinos = [];
if ($retorno && mysqli_num_rows($retorno) > 0) {
    while ($row = mysqli_fetch_assoc($retorno)) {
        $treinos[] = $row;
    }
}

echo json_encode($treinos);

$conn->close();
?>
