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

// Capturar rango de fechas y cliente
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;
$cliente = isset($_GET['cliente']) ? $conn->real_escape_string($_GET['cliente']) : null;

// Validar formato de fechas (opcional)
if ($fecha_inicio && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio)) {
    die(json_encode(["error" => "Formato de fecha_inicio inválido"]));
}
if ($fecha_final && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_final)) {
    die(json_encode(["error" => "Formato de fecha_final inválido"]));
}

// Generar condición de rango de fechas
$dateCondition = "";
if ($fecha_inicio && $fecha_final) {
    $dateCondition = "fecha_objetivo BETWEEN '$fecha_inicio' AND '$fecha_final'";
} elseif ($fecha_inicio) {
    $dateCondition = "fecha_objetivo >= '$fecha_inicio'";
} elseif ($fecha_final) {
    $dateCondition = "fecha_objetivo <= '$fecha_final'";
}

// Generar condición para cliente
$clientCondition = "";
if ($cliente) {
    $clientCondition = "cliente = '$cliente'";
}

// Combinar condiciones (si existen ambas)
$whereClause = "";
if ($dateCondition && $clientCondition) {
    $whereClause = "WHERE $dateCondition AND $clientCondition";
} elseif ($dateCondition) {
    $whereClause = "WHERE $dateCondition";
} elseif ($clientCondition) {
    $whereClause = "WHERE $clientCondition";
}

// Consultas para múltiples gráficos

// Gráfico 1: Total de unidades por cliente y mes
$query1 = "
    SELECT 
        DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes,
        cliente,
        SUM(unidades) AS total_unidades
    FROM 
        import
    $whereClause
    GROUP BY 
        mes, cliente
    ORDER BY 
        mes, cliente
";
$result1 = $conn->query($query1);
$chart1 = [];
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $chart1[] = [
            'name' => $row['cliente'] ? $row['cliente'] : 'Sin Cliente',
            'value' => [
                $row['mes'],
                (int)$row['total_unidades'],
            ],
        ];
    }
}

// Gráfico 2: Total de paletas por destino y mes
$query2 = "
    SELECT 
        DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes,
        destino,
        SUM(paletas) AS total_paletas
    FROM 
        import
    $whereClause
    GROUP BY 
        mes, destino
    ORDER BY 
        mes, destino
";
$result2 = $conn->query($query2);
$chart2 = [];
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $chart2[] = [
            'name' => $row['destino'] ? $row['destino'] : 'Sin Destino',
            'value' => [
                $row['mes'],
                (int)$row['total_paletas'],
            ],
        ];
    }
}

// Gráfico 3: Total de cajas por cliente y mes
$query3 = "
    SELECT 
        DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes,
        cliente,
        SUM(cajas) AS total_cajas
    FROM 
        import
    $whereClause
    GROUP BY 
        mes, cliente
    ORDER BY 
        mes, cliente
";
$result3 = $conn->query($query3);
$chart3 = [];
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $chart3[] = [
            'name' => $row['cliente'] ? $row['cliente'] : 'Sin Cliente',
            'value' => [
                $row['mes'],
                (int)$row['total_cajas'],
            ],
        ];
    }
}

// Gráfico 4: Ejemplo adicional de consulta
$query4 = "
    SELECT 
        DATE_FORMAT(fecha_objetivo, '%Y-%m') AS mes,
        cliente,
        SUM(cajas) AS total_cajas
    FROM 
        export
    $whereClause
    GROUP BY 
        mes, cliente
    ORDER BY 
        mes, cliente
";
$result4 = $conn->query($query4);
$chart4 = [];
if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        $chart4[] = [
            'name' => $row['cliente'] ? $row['cliente'] : 'Sin Cliente',
            'value' => [
                $row['mes'],
                (int)$row['total_cajas'],
            ],
        ];
    }
}

// Empaqueta los datos en un solo array
$data = [
    'chart1' => $chart1,
    'chart2' => $chart2,
    'chart3' => $chart3,
    'chart4' => $chart4,
];

// Cierra la conexión
$conn->close();

// Devuelve los datos en formato JSON
echo json_encode($data);
?>
