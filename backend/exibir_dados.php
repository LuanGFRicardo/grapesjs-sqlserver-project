<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

$serverName = "sql_server,1433";
$connectionOptions = array(
    "Database" => "master", // Altere para o nome do seu banco de dados real
    "Uid" => "sa",
    "PWD" => "YourStrong!Passw0rd"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Recupera o tipo de dado via parâmetro GET
$tipo = $_GET['tipo'] ?? 'registro';  // Valor padrão 'registro' caso não fornecido

$data = [];

switch ($tipo) {
    case 'registro':
        // Consulta para o tipo 'registro'
        $sql = "SELECT TOP 1 [Num. Registro] AS registro FROM SCDA01"; // Exemplo: 1 registro
        break;
    
    case 'lista-registros':
        //
        $sql = "SELECT TOP 5 [Num. Registro] AS registro FROM SCDA01";
        break;
    
    // case 'produtos':
    //     // Consulta para o tipo 'produtos'
    //     $sql = "SELECT TOP 5 [ProductID], [ProductName], [Price] FROM Products"; // Exemplo: tabela 'Products'
    //     break;

    default:
        http_response_code(400); // Retorna um erro caso o tipo não seja encontrado
        echo json_encode(["erro" => "Tipo de dado inválido."]);
        exit;
}

// Executa a consulta SQL com base no tipo
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Preenche o array de dados com o resultado da consulta
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);  // Retorna os dados em formato JSON

// Libera o statement e fecha a conexão
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
