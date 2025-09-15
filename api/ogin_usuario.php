<?php
// api/login_usuario.php

// Inicia a sessão para que possamos usar variáveis de sessão se necessário no futuro
session_start();

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
if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'E-mail e senha são obrigatórios!']);
    exit;
}

$email = $data->email;
$senha = $data->password;

// Prepara a query SQL para buscar o usuário pelo e-mail
$sql = "SELECT id, nome, senha_hash FROM usuarios WHERE email = ? LIMIT 1";

$stmt = $con->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao preparar a consulta.']);
    exit;
}

// 's' indica que o parâmetro é uma string
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Usuário encontrado, agora verifica a senha
    $usuario = $result->fetch_assoc();
    
    // password_verify() compara a senha enviada com o hash salvo no banco
    if (password_verify($senha, $usuario['senha_hash'])) {
        // Senha correta
        
        // Opcional: Salvar informações do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        http_response_code(200); // OK
        echo json_encode(['message' => 'Login realizado com sucesso!']);
    } else {
        // Senha incorreta
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'E-mail ou senha inválidos.']);
    }
} else {
    // Usuário não encontrado
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'E-mail ou senha inválidos.']);
}

// Fecha o statement e a conexão
$stmt->close();
$con->close();
?>