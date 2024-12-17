<?php

class DBConnection {
    // Configuraciones de conexión
    private static $dbConfigs = [
        'estandar' => [
            'host' => 'localhost',
            'user' => 'u366386740_IPLGroup',
            'pass' => '1plGr0up01*',
            'name' => 'u366386740_dataWarehouse',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ],
        ],
        'alternative' => [
            'host' => 'localhost',
            'user' => 'u366386740_IPLGroup',
            'pass' => '1plGr0up01*',
            'name' => 'alternative_database',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ],
        ],
    ];

    // Método para obtener configuración según parámetros
    public static function getConfig($dbName) {
        if (!isset(self::$dbConfigs[$dbName])) {
            throw new Exception("No existe configuración para la base de datos: '$dbName'");
        }

        return self::$dbConfigs[$dbName];
    }
}

// Obtener parámetros de la URL
$conexionType = isset($_GET['conexion']) ? strtoupper($_GET['conexion']) : null; // Valores posibles: 'PDO', 'MySQLi'
$dbName = isset($_GET['BaseD']) ? $_GET['BaseD'] : null;

// Validar parámetros
if (!$conexionType || !$dbName) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Faltan parámetros necesarios: 'conexion' o 'BaseD'."
    ]);
    exit;
}

if (!in_array($conexionType, ['PDO', 'MySQLi'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Tipo de conexión inválido. Use 'PDO' o 'MySQLi'."
    ]);
    exit;
}

try {
    // Obtener la configuración de la base de datos
    $config = DBConnection::getConfig($dbName);

    // Añadir el tipo de conexión a la respuesta
    $config['conexionType'] = $conexionType;

    // Establecer encabezado JSON y devolver respuesta
    header('Content-Type: application/json');
    echo json_encode($config);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "error" => $e->getMessage()
    ]);
    exit;
}
?>
