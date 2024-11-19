<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u366386740_db_dailyplan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Consulta a la base de datos
$query = "SELECT * FROM import GROUP BY t_carga";
$result = $conn->query($query);

// Inicializa arrays para categorías y valores
$categories0 = [];
$categories1 = [];
$values0 = [];
$values1 = [];
$type = 'bar';

// Recorre los resultados de la consulta
while ($row = $result->fetch_assoc()) {
    $values0[] = $row['aid_oid'];
    $values1[] = $row['id'];
    $categories0[] = $row['cliente'];
    $categories1[] = $row['destino'];
}

// Prepara los datos para los gráficos con opciones avanzadas
$data["chart1"] = [
    "categories" => $categories0,
    "values" => $values0,
    "type" => $type,
    "title" => "Gráfico 1: Clientes y AID OID",
    "tooltip" => ["trigger" => "axis"],
    "toolbox" => ["show" => true, "feature" => ["saveAsImage" => ["show" => true]]]
];
$data["chart2"] = [
    "categories" => $categories1,
    "values" => $values1,
    "type" => "line",
    "title" => "Gráfico 2: Destinos e ID",
    "tooltip" => ["trigger" => "item"],
    "toolbox" => ["show" => true, "feature" => ["dataZoom" => ["show" => true]]]
];
$data["chart3"] = [
    "categories" => $categories0,
    "values" => $values1,
    "type" => "pie",
    "title" => "Gráfico 3: Clientes e ID",
    "tooltip" => ["trigger" => "item"],
    "toolbox" => ["show" => true, "feature" => ["saveAsImage" => ["show" => true]]]
];

// Puedes seguir agregando configuraciones similares para chart4 a chart8

// Cierra la conexión
$conn->close();

// Devuelve los datos como JSON
echo json_encode($data);
?>
