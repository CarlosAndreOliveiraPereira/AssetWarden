<?php
// cadastrar_dispositivo.php

// Define que a resposta será no formato JSON
header('Content-Type: application/json');
// Permite requisições de qualquer origem (CORS), ajuste se necessário por segurança
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Se a requisição for OPTIONS, apenas retorne os headers e saia.
// Isso é necessário para o pré-voo (preflight) do CORS.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Inclui o arquivo de conexão
require 'conexao.php';

// Pega o corpo da requisição (que está em JSON) e decodifica
$data = json_decode(file_get_contents("php://input"));

// Validação simples dos dados recebidos
if (
    !isset($data->localidade) || !isset($data->nome_dispositivo) || !isset($data->numero_serie) ||
    !isset($data->responsavel) || !isset($data->email) || !isset($data->setor)
) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Dados incompletos!']);
    exit;
}

// Atribui os dados a variáveis
$localidade = $data->localidade;
$dispositivo = $data->nome_dispositivo;
$serie = $data->numero_serie;
$nota_fiscal = $data->nota_fiscal ?? ''; // Operador de coalescência nula
$responsavel = $data->responsavel;
$email = $data->email;
$setor = $data->setor;
$win_update = $data->windows_update_ativo;
$sistema_operacional = $data->sistema_operacional;
$observacao = $data->observacao ?? '';

// Prepara a query SQL para evitar SQL Injection
$sql = "INSERT INTO maquinas (localidade, dispositivo, serie, nota_fiscal, responsavel, email, setor, win_update, sistema_operacional, observacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao preparar a query.', 'error' => $con->error]);
    exit;
}

// 'ssssssssss' indica que todos os 10 parâmetros são strings
$stmt->bind_param('ssssssssss', $localidade, $dispositivo, $serie, $nota_fiscal, $responsavel, $email, $setor, $win_update, $sistema_operacional, $observacao);

// Executa a query e verifica o resultado
if ($stmt->execute()) {
    http_response_code(201); // Created
    echo json_encode(['message' => 'Dispositivo cadastrado com sucesso!']);
} else {
    // Verifica se o erro é de entrada duplicada (pelo número de série)
    if ($con->errno == 1062) {
        http_response_code(409); // Conflict
        echo json_encode(['message' => 'Este número de série já está cadastrado!']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Erro ao cadastrar o dispositivo.', 'error' => $stmt->error]);
    }
}

// Fecha o statement e a conexão
$stmt->close();
$con->close();
?>