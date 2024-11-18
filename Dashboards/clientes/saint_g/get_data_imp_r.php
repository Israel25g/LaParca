<?php
include '../../../daily_plan/funcionalidades/config_G.php';

// Validar entrada
$groupBy = isset($_GET['groupBy']) ? $_GET['groupBy'] : 'mes_cliente';

// Determinar la cláusula GROUP BY basada en el parámetro
switch ($groupBy) {
    case 'mes':
        $groupByClause = "DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes";
        $groupByField = "mes";
        break;
    case 'cliente':
        $groupByClause = "cliente";
        $groupByField = "cliente";
        break;
    case 'mes_cliente':
    default:
        $groupByClause = "DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes, cliente";
        $groupByField = "mes, cliente";
        break;
}

// Construir la consulta SQL dinámica
$query = "SELECT $groupByClause, SUM(pedidos_en_proceso) AS total
          FROM picking 
          GROUP BY $groupByField
          ORDER BY mes";

// Ejecutar la consulta
$result = $conn->query($query);

// Inicializar un array para almacenar los datos
$data = array();

if ($result->num_rows > 0) {
    // Almacenar los resultados en el array
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'name' => isset($row['cliente']) ? $row['cliente'] : 'Sin Cliente',
            'value' => [
                isset($row['mes']) ? $row['mes'] : 'Sin Mes',
                (int)$row['total']
            ]
        ];
    }
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>
