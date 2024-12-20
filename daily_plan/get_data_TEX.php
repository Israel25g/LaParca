<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'u366386740_db_dailyplan';
$user = 'u366386740_adminDP';
$pass = '1plGr0up01*';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Consulta para obtener los datos de Export
$stmt = $pdo->query('SELECT aid_oid, cliente, vehiculo, pedidos_en_proceso, pedidos_despachados FROM export WHERE fecha_objetivo = CURDATE() GROUP BY vehiculo');
$clientes = $stmt->fetchAll();

// Log de prueba: Ver los resultados antes de codificarlos en JSON
if (empty($clientes)) {
    echo json_encode(["error" => "Sin resultados para la fecha de hoy."]);
} else {
    echo json_encode($clientes);
}
?>
