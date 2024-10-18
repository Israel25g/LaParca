<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include '../daily_plan/funcionalidades/config_G.php';

// Consulta a la base de datos
$query = "SELECT SUM(grafica_dp) AS total_grafico, SUM(pedidos_despachados) AS total_meta, cliente FROM import WHERE fecha_objetivo = CURDATE()";

$result = $conn->query($query);

// Inicializar un array para los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'name' => ['cliente'],
            'total_meta' => (int)$row['total_meta'],
            'total_grafico' => (int)$row['total_grafico']
            
        );
    }
} else {
    echo "No se encontraron datos.";
}

// Devolver los datos en formato JSON
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>