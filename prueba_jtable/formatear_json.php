
<?php
// Nombre del archivo JSON que deseas leer
$jsonFile = 'datos_guardados.json'; // Cambia esto al nombre real de tu archivo JSON

// Comprobar si el archivo JSON existe
if (!file_exists($jsonFile)) {
    die('El archivo JSON no se encontró.');
}

// Leer el contenido del archivo JSON
$jsonString = file_get_contents($jsonFile);

// Decodificar el JSON en un array asociativo
$data = json_decode($jsonString, true); // true para obtener un array asociativo

$result = [];

// Suponiendo que tienes un conjunto de encabezados para tu base de datos
$headers = ['aid_oid', 'cliente', 'vehiculo', 't_vehiculo', 'bl', 'destino', 'paletas', 'cajas', 'unidades', 'pedidos_en_proceso', 'fecha_objetivo', 'comentario_bodega', 'comentario_oficina'];

// Procesar cada fila y combinar con los encabezados
foreach ($data as $row) {
    $result[] = array_combine($headers, $row); // Combina los nombres de las columnas con sus valores
}

// Ahora $result contiene tus datos en formato de diccionario
print_r($result); // Para mostrar el resultado en la pantalla, o puedes guardar a la base de datos aquí

// Aquí puedes agregar la lógica para insertar los datos en la base de datos
// Configuración de la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos está en otro host
$db   = 'daily_plan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Configurar el DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Ahora que tienes el array $result, puedes insertar los datos
    $insertSQL = "INSERT INTO export (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_bodega, comentario_oficina) 
                  VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_bodega, :comentario_oficina)";

    // Preparar la consulta
    $stmt = $pdo->prepare($insertSQL);

    // Iterar sobre el array y ejecutar la inserción
    foreach ($result as $row) {
        $stmt->execute([
            ':aid_oid' => $row['aid_oid'],
            ':cliente' => $row['cliente'],
            ':vehiculo' => $row['vehiculo'],
            ':t_vehiculo' => $row['t_vehiculo'],
            ':bl' => $row['bl'],
            ':destino' => $row['destino'],
            ':paletas' => $row['paletas'],
            ':cajas' => $row['cajas'],
            ':unidades' => $row['unidades'],
            ':pedidos_en_proceso' => $row['pedidos_en_proceso'],
            ':fecha_objetivo' => $row['fecha_objetivo'],
            ':comentario_bodega' => $row['comentario_bodega'],
            ':comentario_oficina' => $row['comentario_oficina'],
        ]);
    }

    echo "Datos insertados correctamente.";

} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>