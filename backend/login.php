<?php
session_start();
include '../dbconfig/configbd.php'; // Inclui o arquivo de configuração

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os valores do formulário
    $usuario = $_POST['username'];
    $senha = $_POST['senha'];
    $theme = $_POST['theme'];

    // Verificar se o usuário existe e a senha está correta
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $retorno = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($retorno);

    if ($row) {
        // Verificar a senha
        if (password_verify($senha, $row['senha'])) {
            // Iniciar a sessão e armazenar as informações do usuário, incluindo o tema
            $_SESSION['username'] = $usuario;
            $_SESSION['theme'] = $theme;
            header('Location: ../frontend/index.html'); // Redirecionar para a página inicial após login bem-sucedido
        } else {
            $error_message = urlencode("Senha incorreta!");
            header('Location: ../frontend/login.html?error=' . $error_message . '&username=' . urlencode($usuario)); // Redirecionar de volta para a página de login
        }
    } else {
        $error_message = urlencode("Usuário não encontrado!");
        header('Location: ../frontend/login.html?error=' . $error_message . '&username=' . urlencode($usuario)); // Redirecionar de volta para a página de login
    }

    exit();
}

$conn->close();
?>
