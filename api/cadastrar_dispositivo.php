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

// Validação básica dos dados recebidos
$required_fields = [
    'localidade', 'nome_dispositivo', 'numero_serie', 'nota_fiscal',
    'responsavel', 'email', 'setor', 'windows_update_ativo', 'sistema_operacional'
];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        http_response_code(400); // Requisição inválida
        echo json_encode(['message' => "O campo '{$field}' é obrigatório."]);
        exit;
    }
}

// Tenta executar as operações no banco de dados
try {
    // Verifica se o número de série já existe
    $sql_check = "SELECT id FROM dispositivos WHERE numero_serie = :numero_serie";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['numero_serie' => $data['numero_serie']]);

    if ($stmt_check->fetch()) {
        http_response_code(409); // Conflito
        echo json_encode(['message' => 'Este número de série já está cadastrado.']);
        exit;
    }

    // Insere o novo dispositivo no banco de dados
    $sql_insert = "INSERT INTO dispositivos (
        localidade, nome_dispositivo, numero_serie, nota_fiscal, responsavel,
        email, setor, windows_update_ativo, sistema_operacional, observacao
    ) VALUES (
        :localidade, :nome_dispositivo, :numero_serie, :nota_fiscal, :responsavel,
        :email, :setor, :windows_update_ativo, :sistema_operacional, :observacao
    )";

    $stmt_insert = $pdo->prepare($sql_insert);

    $stmt_insert->execute([
        'localidade' => $data['localidade'],
        'nome_dispositivo' => $data['nome_dispositivo'],
        'numero_serie' => $data['numero_serie'],
        'nota_fiscal' => $data['nota_fiscal'],
        'responsavel' => $data['responsavel'],
        'email' => $data['email'],
        'setor' => $data['setor'],
        'windows_update_ativo' => $data['windows_update_ativo'],
        'sistema_operacional' => $data['sistema_operacional'],
        'observacao' => $data['observacao'] ?? null // Permite observação nula
    ]);

    // Resposta de sucesso
    http_response_code(201); // Criado
    echo json_encode(['message' => 'Dispositivo cadastrado com sucesso!']);

} catch (PDOException $e) {
    // Em caso de erro no banco de dados, retorna uma mensagem genérica
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['message' => 'Erro ao processar sua solicitação: ' . $e->getMessage()]);
}

?>