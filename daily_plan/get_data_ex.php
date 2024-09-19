<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include 'C:\xampp\htdocs\sistema_de_tickets\sistema_de_tickets\daily_plan\config_G.php';

// Consulta a la base de datos
$query = "SELECT cliente, grafica_dp FROM export WHERE fecha_objetivo = CURDATE()";

$result = $conn->query($query);

// Inicializar un array para los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while($row = $result->fetch_assoc()) {
        $data[] = array(
            'name' => $row['cliente'],
            'value' => (int)$row['grafica_dp']
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
