<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

$serverName = "sql_server,1433";
$connectionOptions = array(
    "Database" => "master",
    "Uid" => "sa",
    "PWD" => "YourStrong!Passw0rd"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
$sql = "SELECT TOP 1 [Num. Registro] AS registro FROM SCDA01";
$stmt = sqlsrv_query($conn, $sql);
$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
