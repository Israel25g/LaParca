<?php

class DBConnection {
    // Configuraciones de conexión
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

    // Método para crear conexión MySQLi
    public static function createConnection($config) {
        $connection = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);

        // Verificar conexión
        if ($connection->connect_error) {
            throw new Exception("Error de conexión MySQLi: " . $connection->connect_error);
        }

        return $connection;
    }
}

// Obtener parámetros de la URL
$dbName = isset($_GET['BaseD']) ? $_GET['BaseD'] : null;

// Validar parámetros
if (!$dbName) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Falta el parámetro necesario: 'BaseD'."
    ]);
    exit;
}

try {
    // Obtener la configuración de la base de datos
    $config = DBConnection::getConfig($dbName);

    // Crear la conexión MySQLi
    $connection = DBConnection::createConnection($config);

    // Responder con éxito
    header('Content-Type: application/json');
    echo json_encode($config);

    // Cerrar la conexión
    $connection->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "error" => $e->getMessage()
    ]);
    exit;
}
?>