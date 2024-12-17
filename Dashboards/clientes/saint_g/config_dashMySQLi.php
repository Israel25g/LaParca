<?php

class DBConnectionMySQLi {
    // Configuraciones de conexión MySQLi
    private static $dbConfigs = [
        'estandar' => [
            'host' => 'localhost',
            'user' => 'u366386740_IPLGroup',
            'pass' => '1plGr0up01*',
            'name' => 'u366386740_dataWarehouse',
        ],
        'alternative' => [
            'host' => 'localhost',
            'user' => 'u366386740_IPLGroup',
            'pass' => '1plGr0up01*',
            'name' => 'alternative_database',
        ],
    ];

    // Método para obtener configuración según parámetros
    public static function getConfig($dbName) {
        if (!isset(self::$dbConfigs[$dbName])) {
            throw new Exception("No existe configuración para la base de datos: '$dbName'");
        }

        return self::$dbConfigs[$dbName];
    }

    // Método para crear la conexión MySQLi
    public static function createConnection($config) {
        $mysqli = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);

        // Verificar si hay errores de conexión
        if ($mysqli->connect_error) {
            throw new Exception("Error en la conexión MySQLi: " . $mysqli->connect_error);
        }

        return $mysqli;
    }
}

// Obtener parámetros de la URL
$conexionType = isset($_GET['conexion']) ? strtoupper($_GET['conexion']) : null; // Valores posibles: 'MySQLi'
$dbName = isset($_GET['BaseD']) ? $_GET['BaseD'] : null;

// Validar parámetros
if (!$conexionType || !$dbName) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Faltan parámetros necesarios: 'conexion' o 'BaseD'."
    ]);
    exit;
}

if ($conexionType !== 'MySQLi') {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Tipo de conexión inválido. Use 'MySQLi'."
    ]);
    exit;
}

try {
    // Obtener la configuración de la base de datos
    $config = DBConnectionMySQLi::getConfig($dbName);

    // Crear la conexión con MySQLi
    $db = DBConnectionMySQLi::createConnection($config);

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
