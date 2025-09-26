<?php
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
if (!isset($data['nome']) || !isset($data['email']) || !isset($data['senha'])) {
    http_response_code(400); // Requisição inválida
    echo json_encode(['message' => 'Dados incompletos. Por favor, preencha todos os campos.']);
    exit;
}

$nome = trim($data['nome']);
$email = trim($data['email']);
$senha = $data['senha'];

// Validações adicionais
if (empty($nome) || empty($email) || empty($senha)) {
    http_response_code(400);
    echo json_encode(['message' => 'Nenhum campo pode estar vazio.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Formato de e-mail inválido.']);
    exit;
}

// Validação do domínio do e-mail
$allowed_domain = '@grupomysa.com.br';
if (!str_ends_with($email, $allowed_domain)) {
    http_response_code(400);
    echo json_encode(['message' => 'O e-mail deve pertencer ao domínio @grupomysa.com.br.']);
    exit;
}

if (strlen($senha) < 6) {
    http_response_code(400);
    echo json_encode(['message' => 'A senha deve ter no mínimo 6 caracteres.']);
    exit;
}

// Tenta executar as operações no banco de dados
try {
    // 1. Verifica se o e-mail já está cadastrado
    $sql_check = "SELECT id FROM usuarios WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email]);

    if ($stmt_check->fetch()) {
        http_response_code(409); // Conflito
        echo json_encode(['message' => 'Este e-mail já está em uso.']);
        exit;
    }

    // 2. Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_ARGON2ID);

    // 3. Insere o novo usuário no banco de dados
    $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt_insert = $pdo->prepare($sql_insert);

    $stmt_insert->execute([
        'nome' => $nome,
        'email' => $email,
        'senha' => $senhaHash
    ]);

    // Resposta de sucesso
    http_response_code(201); // Criado
    echo json_encode(['message' => 'Usuário cadastrado com sucesso!']);

} catch (PDOException $e) {
    // Em caso de erro no banco de dados, retorna uma mensagem genérica
    http_response_code(500); // Erro interno do servidor
    // Em produção, logar o erro em vez de exibi-lo
    echo json_encode(['message' => 'Erro ao processar sua solicitação: ' . $e->getMessage()]);
}

?>