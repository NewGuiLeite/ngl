<?php
// Criar conexão
$servidor = "localhost";
$user = "root";
$password = "infopharma";
$bd = "bd";

$conn = new mysqli($servidor, $user, $password, $bd);
if ($conn->connect_error) {
    echo "<p style='color:red; text-align:center; font-size:25px;'>Erro de Conexão com o Banco de Dados!</p>";
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recuperar os valores do formulário
    $usuario = $_POST['username'];
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirmaSenha'];
    $theme = $_POST['theme'];

    // Verificar se as senhas coincidem
    if ($senha !== $confirmaSenha) {
        echo "<p style='color:red; text-align:center; font-size:25px;'>As senhas não são iguais!</p>";
        echo "<p style='color:red; text-align:center; font-size:25px;'><a href='usuario.html'>Voltar</a></p>";
    } else {
        // Verificar se o usuário já existe
        $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
        $retorno = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($retorno);

        if ($row) {
            echo "<p style='color:red; text-align:center; font-size:25px;'>Usuário já Existe!</p>";
            echo "<p style='color:red; text-align:center; font-size:25px;'><a href='usuario.html'>Voltar</a></p>";
        } else {
            // Inserir novo usuário
            $hashsenha = password_hash($senha, PASSWORD_BCRYPT);
            $sql = "INSERT INTO usuario (usuario, senha, theme) VALUES ('$usuario', '$hashsenha', '$theme')";
            $retorno = mysqli_query($conn, $sql);

            if ($retorno === true) {
                echo "<p style='color:green; text-align:center; font-size:25px;'>Usuário foi cadastrado com sucesso!</p>";
                echo "<p style='color:green; text-align:center; font-size:25px;'><a href='usuario.html'>Voltar</a></p>";
            } else {
                echo "<p style='color:red; text-align:center; font-size:25px;'>Erro ao cadastrar o usuário: " . $conn->error . "</p>";
                echo "<p style='color:red; text-align:center; font-size:25px;'><a href='usuario.html'>Voltar</a></p>";
            }
        }
    }
}

$conn->close();
?>
