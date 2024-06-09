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
    $theme = $_POST['theme'];

    // Verificar se o usuário existe e a senha está correta
    $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
    $retorno = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($retorno);

    if ($row) {
        // Verificar a senha
        if (password_verify($senha, $row['senha'])) {
            echo "<p style='color:green; text-align:center; font-size:25px;'>Login bem-sucedido!</p>";
            echo "<p style='color:green; text-align:center; font-size:25px;'><a href='pagina_inicial.php'>Ir para página inicial</a></p>";
            
            // Aqui você pode iniciar a sessão e armazenar as informações do usuário, incluindo o tema
            session_start();
            $_SESSION['username'] = $usuario;
            $_SESSION['theme'] = $theme;
        } else {
            echo "<p style='color:red; text-align:center; font-size:25px;'>Senha incorreta!</p>";
            echo "<p style='color:red; text-align:center; font-size:25px;'><a href='login.html'>Voltar</a></p>";
        }
    } else {
        echo "<p style='color:red; text-align:center; font-size:25px;'>Usuário não encontrado!</p>";
        echo "<p style='color:red; text-align:center; font-size:25px;'><a href='login.html'>Voltar</a></p>";
    }
}

$conn->close();
?>