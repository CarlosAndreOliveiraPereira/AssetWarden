<?php
// conexao.php

$host = "localhost";
$user = "root"; // Usuário padrão do XAMPP/WAMP
$password = "";  // Senha padrão do XAMPP/WAMP
$database = "inventario_ti";

// Cria a conexão
$con = new mysqli($host, $user, $password, $database);

// Define o charset para utf8 para evitar problemas com acentuação
$con->set_charset("utf8");

// Verifica se há erro na conexão
if ($con->connect_error) {
    // Em um ambiente de produção, não é recomendado expor detalhes do erro.
    // Você pode registrar o erro em um log.
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Erro de conexão com o banco de dados."]);
    exit; // Para a execução do script
}
?>