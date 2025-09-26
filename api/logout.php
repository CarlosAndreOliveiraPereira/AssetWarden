<?php
// Inicia a sessão para poder acessá-la
session_start();

// Remove todas as variáveis de sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Define o cabeçalho da resposta para indicar que não há conteúdo a ser retornado
// e que o cliente deve ser redirecionado.
header('Content-Type: application/json');
http_response_code(200);

// Retorna uma resposta JSON para o frontend, indicando o sucesso do logout
// e a página para a qual o cliente deve ser redirecionado.
echo json_encode([
    'message' => 'Logout bem-sucedido!',
    'redirect' => '../html/login.html'
]);

exit;
?>