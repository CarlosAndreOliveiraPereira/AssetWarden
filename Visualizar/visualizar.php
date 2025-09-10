<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";  // Troque para sua senha real
$database = "inventario_ti";

$con = new mysqli($host, $user, $password, $database);

if ($con->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão: " . $con->connect_error]);
    exit;
}

$sql = "SELECT localidade, dispositivo, serie, nota_fiscal, responsavel, email, setor, win_update, sistema_operacional FROM maquinas"; 

$result = $con->query($sql);

$maquinas = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $maquinas[] = $row;
    }
}

echo json_encode($maquinas);

$con->close();
?>