<?php
// Configurações do banco de dados
$servidor = "localhost"; // ou o endereço do servidor do banco de dados
$user = "root"; // seu usuário do banco de dados
$password = ""; // sua senha do banco de dados
$bd = "bd"; // o nome do banco de dados

// Criar conexão
$conn = new mysqli($servidor, $user, $password, $bd);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>



