<?php
// Configuración de la conexión a la base de datos
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
    // Verificar conexión exitosa
    echo json_encode([""]);
} catch (\PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Consulta SQL
$query = 'SELECT aid_oid, cliente, pedidos_despachados, pedidos_en_proceso FROM import WHERE fecha_objetivo = CURDATE()';
try {
    $stmt = $pdo->query($query);
    $clientes = $stmt->fetchAll();

    // Verificar si la consulta devolvió resultados
    if (empty($clientes)) {
        echo json_encode(["error" => "Sin resultados en la tabla Import para la fecha actual."]);
    } else {
        echo json_encode($clientes);
    }
} catch (\PDOException $e) {
    echo json_encode(["error" => "Error en la consulta SQL: " . $e->getMessage()]);
}
?>
