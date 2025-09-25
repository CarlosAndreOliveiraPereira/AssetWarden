<?php
// Configurações do banco de dados
$host = 'localhost'; // Ou o IP do seu servidor de banco de dados
$dbname = 'mysa_db';
$user = 'root';
$pass = '';

// String de conexão (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna os resultados como array associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação de prepared statements
];

try {
    // Cria a instância do PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Em caso de erro na conexão, termina o script e exibe uma mensagem de erro
    // Em um ambiente de produção, é recomendável logar o erro em vez de exibi-lo
    header('Content-Type: application/json');
    http_response_code(500); // Erro interno do servidor
    echo json_encode(['message' => 'Falha na conexão com o banco de dados: ' . $e->getMessage()]);
    exit; // Impede a execução do resto do script
}

// O objeto $pdo está pronto para ser usado nos outros scripts que incluírem este arquivo.
?>