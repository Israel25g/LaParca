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
function createChartConfig($categories, $values, $type, $title) {
    return [
        "categories" => $categories,
        "values" => $values,
        "type" => $type,
        "title" => $title,
        "tooltip" => ["trigger" => "item"],
        "toolbox" => ["show" => true, "feature" => ["saveAsImage" => ["show" => true],["dataZoom"=> true]]]
    ];
}

// Configuración dinámica de gráficos
$data["chart1"] = createChartConfig($categories0, $values0, "bar", "Gráfico 1: Clientes y AID OID");
$data["chart2"] = createChartConfig($categories1, $values1, "line", "Gráfico 2: Destinos e ID");
$data["chart3"] = createChartConfig($categories0, $values1, "pie", "Gráfico 3: Clientes e ID");
$data["chart4"] = createChartConfig($categories1, $values0, "scatter", "Gráfico 4: Destinos y AID OID");
$data["chart5"] = createChartConfig($categories0, $values0, "bar", "Gráfico 5: Clientes y AID OID (Bar)");
$data["chart6"] = createChartConfig($categories1, $values1, "line", "Gráfico 6: Destinos e ID (Line)");
$data["chart7"] = createChartConfig($categories0, $values0, "pie", "Gráfico 7: Clientes y AID OID (Pie)");
$data["chart8"] = createChartConfig($categories1, $values1, "gauge", "Gráfico 8: Radar de ID y Destinos");

// Cierra la conexión
$conn->close();

// Devuelve los datos como JSON
echo json_encode($data);
?>
