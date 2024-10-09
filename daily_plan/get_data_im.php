<?php
header('Content-Type: application/json');

include '../daily_plan/funcionalidades/config_G.php'; // Suponiendo que este archivo contiene la conexión a la base de datos.

$query = "
    SELECT 
        cliente, 
        SUM(grafica_dp) AS total_grafico, 
        SUM(pedidos_despachados) AS total_meta 
    FROM 
        import 
    WHERE 
        fecha_objetivo = CURDATE() 
    GROUP BY 
        cliente
";

$result = $conn->query($query);

$clientes = [];
$total_grafico = [];
$total_meta = [];

while ($row = $result->fetch_assoc()) {
    $clientes[] = $row['cliente'];
    $total_grafico[] = (int)$row['total_grafico']; // Asegúrate de que los valores sean enteros
    $total_meta[] = (int)$row['total_meta'];
}

echo json_encode([
    'clientes' => $clientes,
    'total_grafico' => $total_grafico,
    'total_meta' => $total_meta
]);
?>
