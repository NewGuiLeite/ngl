<?php
include '../dbconfig/configbd.php'; // Inclui o arquivo de configuração do banco de dados

$response = [];

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    $action = $_POST['action'];

    if ($action == 'register') {
        // Recuperar os valores do formulário
        $grupo = $_POST['grupo'];
        $parte = $_POST['parte'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $observacao = $_POST['observacao'];

        // Inserir novo treino
        $sql = "INSERT INTO treino (grupo, parte, nome, series, repeticao, observacao) VALUES ('$grupo', '$parte', '$nome', '$series', '$repeticao', '$observacao')";
        $retorno = mysqli_query($conn, $sql);

        if ($retorno === true) {
            $response = ['status' => 'success', 'message' => 'Treino cadastrado com sucesso!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Erro ao cadastrar o treino: ' . $conn->error];
        }
    }

    if ($action == 'edit') {
        $id = $_POST['id'];
        $grupo = $_POST['grupo'];
        $parte = $_POST['parte'];
        $nome = $_POST['nome'];
        $series = $_POST['series'];
        $repeticao = $_POST['repeticao'];
        $observacao = $_POST['observacao'];

        $sql = "UPDATE treino SET grupo = '$grupo', parte = '$parte', nome = '$nome', series = '$series', repeticao = '$repeticao', observacao = '$observacao' WHERE id = $id";
        $retorno = mysqli_query($conn, $sql);

        if ($retorno === true) {
            $response = ['status' => 'success', 'message' => 'Treino atualizado com sucesso!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Erro ao atualizar treino: ' . $conn->error];
        }
    }

    if ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM treino WHERE id = $id";
        $retorno = mysqli_query($conn, $sql);

        if ($retorno === true) {
            $response = ['status' => 'success', 'message' => 'Treino excluído com sucesso!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Erro ao excluir treino: ' . $conn->error];
        }
    }

    echo json_encode($response);
    $conn->close();
    exit();
}

// Recuperar lista de treinos cadastrados (para chamada AJAX)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'list') {
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
}
?>
