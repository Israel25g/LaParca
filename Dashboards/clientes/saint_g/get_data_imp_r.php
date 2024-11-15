<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include '../../../daily_plan/funcionalidades/config_G.php';

// Consulta a la base de datos agrupada por mes y año
$query = "SELECT DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes, cliente, SUM(pedidos_en_proceso) AS total_mes
          FROM picking 
          GROUP BY mes, cliente 
          ORDER BY mes";

$result = $conn->query($query);

// Inicializar un array para almacenar los datos en formato de gráfico de dispersión
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while ($row = $result->fetch_assoc()) {
        // Cada entrada será un par [fecha, valor]
        $data[] = [
            'name' => $row['cliente'],
            'value' => [$row['mes'], (int)$row['total_mes']]
        ];
    }
} else {
    echo json_encode(["message" => "No se encontraron datos."]);
    exit;
}

// Devolver los datos en formato JSON para que ECharts los consuma
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>
