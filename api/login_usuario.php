<?php
// Inicia a sessão ANTES de qualquer saída de HTML ou PHP.
// Isso é crucial para o gerenciamento do estado de login.
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método não permitido
    echo json_encode(['message' => 'Método não permitido.']);
    exit;
}

// Pega o corpo da requisição e decodifica o JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validação dos dados recebidos
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400); // Requisição inválida
    echo json_encode(['message' => 'E-mail e senha são obrigatórios.']);
    exit;
}

$email = trim($data['email']);
$senha = $data['password'];

// Validações adicionais
if (empty($email) || empty($senha)) {
    http_response_code(400);
    echo json_encode(['message' => 'E-mail e senha não podem estar vazios.']);
    exit;
}

// Tenta executar as operações no banco de dados
try {
    // 1. Busca o usuário pelo e-mail
    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    
    $usuario = $stmt->fetch();

    // 2. Verifica se o usuário existe e se a senha está correta
    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        http_response_code(401); // Não autorizado
        echo json_encode(['message' => 'E-mail ou senha inválidos.']);
        exit;
    }

    // 3. Se a autenticação for bem-sucedida, armazena os dados na sessão
    // Regenera o ID da sessão para evitar ataques de fixação de sessão
    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['logged_in'] = true;

    // Resposta de sucesso
    http_response_code(200); // OK
    echo json_encode([
        'message' => 'Login bem-sucedido!',
        'redirect' => 'Inve.html' // Informa ao frontend para onde redirecionar
    ]);

} catch (PDOException $e) {
    // Em caso de erro no banco de dados, retorna uma mensagem genérica
    http_response_code(500); // Erro interno do servidor
    // Em produção, logar o erro em vez de exibi-lo
    echo json_encode(['message' => 'Erro ao processar sua solicitação: ' . $e->getMessage()]);
}

?>