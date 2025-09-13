<?php
// listar_maquinas.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permitir acesso de qualquer origem

require 'conexao.php';

// Query para selecionar os dados
$sql = "SELECT localidade, dispositivo, serie, nota_fiscal, responsavel, email, setor, win_update, sistema_operacional FROM maquinas ORDER BY id DESC";

$result = $con->query($sql);

$maquinas = [];

if ($result) {
    // Itera sobre os resultados e os adiciona a um array
    while ($row = $result->fetch_assoc()) {
        $maquinas[] = $row;
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao executar a consulta: " . $con->error]);
    $con->close();
    exit;
}

// Retorna o array de máquinas em formato JSON
echo json_encode($maquinas);

// Fecha a conexão
$con->close();
?>