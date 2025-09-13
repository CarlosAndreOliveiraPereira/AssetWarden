<?php
// api/cadastrar_usuario.php

// Define que a resposta será no formato JSON
header('Content-Type: application/json');
// Permite requisições de qualquer origem (CORS)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Se a requisição for OPTIONS, apenas retorne os headers e saia.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
require 'conexao.php';

// Pega os dados JSON enviados pelo frontend
$data = json_decode(file_get_contents("php://input"));

// Validação dos dados recebidos
if (!isset($data->nome) || !isset($data->email) || !isset($data->senha)) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Dados incompletos!']);
    exit;
}

$nome = $data->nome;
$email = $data->email;
$senha = $data->senha;

// Validação adicional no servidor
if (strlen($senha) < 6) {
    http_response_code(400);
    echo json_encode(['message' => 'A senha deve ter no mínimo 6 caracteres.']);
    exit;
}

// Criptografa a senha com o método mais seguro do PHP
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

// Prepara a query SQL para evitar SQL Injection
$sql = "INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?)";

$stmt = $con->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao preparar a query.']);
    exit;
}

// 'sss' indica que os 3 parâmetros são strings
$stmt->bind_param('sss', $nome, $email, $senha_hash);

// Executa a query
if ($stmt->execute()) {
    http_response_code(201); // Created
    echo json_encode(['message' => 'Usuário cadastrado com sucesso!']);
} else {
    // Verifica se o erro é de e-mail duplicado (código de erro 1062)
    if ($con->errno == 1062) {
        http_response_code(409); // Conflict
        echo json_encode(['message' => 'Este e-mail já está em uso!']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Erro ao salvar no banco de dados.', 'error' => $stmt->error]);
    }
}

// Fecha o statement e a conexão
$stmt->close();
$con->close();
?>