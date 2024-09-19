<?php
// Archivo para extraer los datos de la base de datos (get_data.php)
include '../daily_plan/funcionalidades/config_G.php';

// Inicializar un array para los datos
$data = array();

// Función para obtener el porcentaje de cada tabla
function obtenerCumplimiento($tabla, $conn) {
    // Consultas específicas para cada tabla
    if ($tabla == 'export') {
        $query = "SELECT SUM(pedidos_en_proceso) AS total_pedidos_en_proceso, SUM(pedidos_despachados) AS total_pedidos_despachados FROM export";
    } elseif ($tabla == 'import') {
        $query = "SELECT SUM(contenedor_recibido) AS total_contenedor_recibido, SUM(contenedor_cerrado) AS total_contenedor_cerrado FROM import";
    } elseif ($tabla == 'picking') {
        $query = "SELECT SUM(pedidos_en_proceso) AS total_pedidos_en_proceso, SUM(pedidos_despachados) AS total_pedidos_despachados FROM picking";
    } else {
        return 0; // Retorna 0 si la tabla no es válida
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($tabla == 'export' || $tabla == 'picking') {
            $total_meta = $row['total_pedidos_en_proceso'];
            $total_listo = $row['total_pedidos_despachados'];
        } elseif ($tabla == 'import') {
            $total_meta = $row['total_contenedor_recibido'];
            $total_listo = $row['total_contenedor_cerrado'];
        }

        // Calcular el porcentaje
        if ($total_meta > 0) {
            $porcentaje = ($total_listo / $total_meta) * 100;
        } else {
            $porcentaje = 0;
        }

        // Redondear el porcentaje a dos decimales
        return round($porcentaje, 0);
    } else {
        return 0;
    }
}

// Obtener el cumplimiento de las tablas import, export y picking
$cumplimiento_import = obtenerCumplimiento('import', $conn);
$cumplimiento_export = obtenerCumplimiento('export', $conn);
$cumplimiento_picking = obtenerCumplimiento('picking', $conn);

// Añadir los datos al array
$data[] = array(
    'name' => 'Import',
    'value' => $cumplimiento_import,
    'title' => array(
        'offsetCenter' => array('-100%', '20%')
    ),
    'detail' => array(
        'valueAnimation' => true,
        'offsetCenter' => array('-100%', '50%')
    )
);
$data[] = array(
    'name' => 'Export',
    'value' => $cumplimiento_export,
    'title' => array(
        'offsetCenter' => array('0%', '20%')
    ),
    'detail' => array(
        'valueAnimation' => true,
        'offsetCenter' => array('0%', '50%')
    )
);

$data[] = array(
    'name' => 'Picking',
    'value' => $cumplimiento_picking,
    'title' => array(
        'offsetCenter' => array('100%', '20%')
    ),
    'detail' => array(
        'valueAnimation' => true,
        'offsetCenter' => array('100%', '50%')
    )
);

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>
