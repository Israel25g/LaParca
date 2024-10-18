<?php
// Archivo para extraer los datos de la base de datos (get_data_im.php)
include '../daily_plan/funcionalidades/config_G.php';

// Consulta a la base de datos agrupando por cliente
$query = "SELECT cliente, 
                 SUM(CASE WHEN pedidos_despachados >= 1 THEN pedidos_despachados ELSE 0 END) AS total_recibido,
                 SUM(CASE WHEN pedidos_despachados < 1 THEN grafica_dp ELSE 0 END) AS total_espera 
          FROM import 
          WHERE fecha_objetivo = CURDATE() 
          GROUP BY cliente";

$result = $conn->query($query);

// Inicializar un array para los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while ($row = $result->fetch_assoc()) {
        // Agregar datos de "Recibido"
        $data[] = array(
            'name' => $row['cliente'],  // Nombre del cliente
            'type' => 'bar',             // Tipo de gráfico
            'stack' => 'total',          // Para apilar
            'label' => array(
                'show' => true            // Mostrar etiquetas
            ),
            'data' => array(             
                (int)$row['total_recibido'],(int)$row['total_espera'],  // Primer dato: Recibido
                0                              // Placeholder para "En espera" en esta serie
            )
        );

        // Agregar datos de "En espera"
        $data[] = array(
            'name' => $row['cliente'],  // Nombre del cliente
            'type' => 'bar',             // Tipo de gráfico
            'stack' => 'total',          // Para apilar
            'label' => array(
                'show' => true            // Mostrar etiquetas
            ),
            'data' => array(
                0,                          // Placeholder para "Recibido" en esta serie
                (int)$row['total_espera']  // Segundo dato: En espera
            )
        );
    }
} else {
    echo json_encode([]); // Devolver un array vacío si no hay datos
    exit(); // Finalizar el script
}

// Devolver los datos en formato JSON
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>
