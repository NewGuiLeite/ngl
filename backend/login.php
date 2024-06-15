<?php
session_start();

// Caminho correto para incluir o arquivo configbd.php
include(__DIR__ . '/../dbconfig/configbd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower($_POST['username']); // Convertendo para minúsculas
    $password = $_POST['senha'];

    // Verifica se a conexão com o banco de dados está configurada
    if (isset($conn)) {
        // Nome da tabela é `usuario`
        $sql = "SELECT * FROM usuario WHERE LOWER(usuario) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($password, $user['senha'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['usuario'];
                $_SESSION['userid'] = $user['id'];

                header('Location: ../frontend/index.html');
                exit();
            } else {
                header('Location: ../frontend/login.html?error=Senha%20incorreta&username='.$username);
                exit();
            }
        } else {
            header('Location: ../frontend/login.html?error=Usuário%20não%20encontrado&username='.$username);
            exit();
        }
    } else {
        die("Erro na conexão com o banco de dados.");
    }
} else {
    echo "Método de requisição inválido.";
}
?>
