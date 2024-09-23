<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include '../daily_plan/funcionalidades/config_G.php';

// Consulta a la base de datos para obtener el total de grafica_dp por cliente
$query = "SELECT cliente, SUM(grafica_dp) as total_grafica_dp FROM picking WHERE fecha_objetivo = CURDATE() GROUP BY cliente";
$result = $conn->query($query);

// Inicializar un array para los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'name' => $row['cliente'],
            'value' => (int)$row['total_grafica_dp']
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
