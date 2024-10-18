<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include '../daily_plan/funcionalidades/config_G.php';

// Consulta a la base de datos, agrupando por cliente y aplicando la lógica de pedidos despachados
$query = "SELECT cliente, 
                 SUM(CASE WHEN pedidos_despachados >= 1 THEN grafica_dp ELSE 0 END) AS total_grafico, 
                 SUM(CASE WHEN pedidos_despachados >= 1 THEN pedidos_despachados ELSE 0 END) AS total_meta 
          FROM import 
          WHERE fecha_objetivo = CURDATE() 
          GROUP BY cliente";

$result = $conn->query($query);

// Inicializar un array para los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'cliente' => $row['cliente'],  // Nombre del cliente
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

