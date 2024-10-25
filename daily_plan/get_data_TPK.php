<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'u366386740_db_test_dp';
$user = 'u366386740_adminTestDP';
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
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Consulta para obtener los datos de clientes
$stmt = $pdo->query('SELECT * FROM picking  WHERE fecha_objetivo = CURDATE() GROUP BY cliente');
$clientes = $stmt->fetchAll();

// Devolver los datos en formato JSON
echo json_encode($clientes);
?>
