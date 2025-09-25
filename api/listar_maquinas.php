<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Verifica se o método da requisição é GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Método não permitido
    echo json_encode(['message' => 'Método não permitido.']);
    exit;
}

try {
    // Prepara e executa a consulta para buscar todos os dispositivos
    $sql = "SELECT
                localidade,
                nome_dispositivo AS dispositivo, -- Renomeia para corresponder ao frontend
                numero_serie AS serie,          -- Renomeia para corresponder ao frontend
                nota_fiscal,
                responsavel,
                email,
                setor,
                windows_update_ativo AS win_update, -- Renomeia para corresponder ao frontend
                sistema_operacional,
                observacao
            FROM
                dispositivos
            ORDER BY
                id DESC"; // Ordena pelos mais recentes

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Busca todos os resultados
    $maquinas = $stmt->fetchAll();

    // Retorna os resultados como JSON
    echo json_encode($maquinas);

} catch (PDOException $e) {
    // Em caso de erro no banco de dados, retorna uma mensagem de erro
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['error' => 'Erro ao buscar os dados do inventário: ' . $e->getMessage()]);
}

?>