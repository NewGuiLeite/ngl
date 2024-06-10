<?php
include '../dbconfig/configbd.php'; // Inclui o arquivo de configuração do banco de dados

$response = [];

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    $action = $_POST['action'];

    if ($action == 'register') {
        // Recuperar os valores do formulário
        $usuario = $_POST['username'];
        $senha = $_POST['senha'];
        $confirmaSenha = $_POST['confirmaSenha'];
        $theme = $_POST['theme'];

        // Verificar se as senhas coincidem
        if ($senha !== $confirmaSenha) {
            $response = ['status' => 'error', 'message' => 'As senhas não são iguais!'];
        } else {
            // Verificar se o usuário já existe
            $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
            $retorno = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($retorno);

            if ($row) {
                $response = ['status' => 'error', 'message' => 'Usuário já existe!'];
            } else {
                // Inserir novo usuário
                $hashsenha = password_hash($senha, PASSWORD_BCRYPT);
                $sql = "INSERT INTO usuario (usuario, senha, theme) VALUES ('$usuario', '$hashsenha', '$theme')";
                $retorno = mysqli_query($conn, $sql);

                if ($retorno === true) {
                    $response = ['status' => 'success', 'message' => 'Usuário cadastrado com sucesso!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Erro ao cadastrar o usuário: ' . $conn->error];
                }
            }
        }
    }

    if ($action == 'edit') {
        $id = $_POST['id'];
        $usuario = $_POST['username'];
        $senha = $_POST['senha'];

        $hashsenha = password_hash($senha, PASSWORD_BCRYPT);
        $sql = "UPDATE usuario SET usuario = '$usuario', senha = '$hashsenha' WHERE id = $id";
        $retorno = mysqli_query($conn, $sql);

        if ($retorno === true) {
            $response = ['status' => 'success', 'message' => 'Usuário atualizado com sucesso!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Erro ao atualizar usuário: ' . $conn->error];
        }
    }

    if ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM usuario WHERE id = $id";
        $retorno = mysqli_query($conn, $sql);

        if ($retorno === true) {
            $response = ['status' => 'success', 'message' => 'Usuário excluído com sucesso!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Erro ao excluir usuário: ' . $conn->error];
        }
    }

    echo json_encode($response);
    $conn->close();
    exit();
}

// Recuperar lista de usuários cadastrados (para chamada AJAX)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'list') {
    $sql = "SELECT id, usuario FROM usuario";
    $retorno = mysqli_query($conn, $sql);

    $usuarios = [];
    if ($retorno && mysqli_num_rows($retorno) > 0) {
        while ($row = mysqli_fetch_assoc($retorno)) {
            $usuarios[] = $row;
        }
    }

    echo json_encode($usuarios);
    $conn->close();
}
?>
