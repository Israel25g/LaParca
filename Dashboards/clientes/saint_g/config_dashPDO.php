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
            'name' => 'u366386740_db_dailyplan',
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
            http_response_code(400); // BAD conn
            throw new Exception("No existe configuracion para la base de datos");
        }

        return self::$dbConfigs[$dbName];
    }
}

// Obtener parámetros de la URL
$conexionType = isset($_GET['conexion']) ? strtoupper(trim($_GET['conexion'])) : null;
$dbName = isset($_GET['BaseD']) ? trim($_GET['BaseD']) : null;
$ipl = isset($_GET['IPL']) ? trim($_GET['IPL']) : null;

// Validar parámetros
if (!$conexionType || !$dbName || !$ipl) {
    http_response_code(403); // Forbidden
    echo json_encode([
        "error" => "No cuenta con los accesos necesarios. Faltan parametros."
    ]);
    exit;
}

if (!in_array($conexionType, ['PDO'], true)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "error" => "Tipo de conexión inválido."
    ]);
    exit;
}

if (!in_array($ipl, ['VALOR1', 'VALOR2'], true)) {
    http_response_code(403); // Forbidden
    echo json_encode([
        "error" => "Valor de IPL invalido."
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
