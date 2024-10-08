<?php
// Nombre del archivo JSON que deseas leer
$jsonFile = 'arreglo_pk.json';

// Comprobar si el archivo JSON existe
if (!file_exists($jsonFile)) {
    die('El archivo no se encontró.');
}

// Leer el contenido del archivo JSON
$jsonString = file_get_contents($jsonFile);

// Decodificar el JSON en un array asociativo
$data = json_decode($jsonString, true);

// Suponiendo que tienes un conjunto de encabezados para tu base de datos
$headers = ['aid_oid', 'cliente', 'paletas', 'cajas', 'pedidos_en_proceso', 'fecha_objetivo', 'vacio_lleno', 'comentario_oficina'];

// Procesar cada fila y combinar con los encabezados
$result = [];
foreach ($data as $row) {
    $result[] = array_combine($headers, $row);
}

// Configuración de la base de datos
$host = 'localhost'; 
$db   = 'u366386740_db_dailyplan';
$user = 'u366386740_adminDP';
$pass = '1plGr0up01*';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass, $options);

    // SQL para insertar en la tabla 'picking'
    $insertSQL_picking = "INSERT INTO picking (aid_oid, cliente, paletas, cajas, pedidos_en_proceso, fecha_objetivo, vacio_lleno, comentario_oficina) 
                         VALUES (:aid_oid, :cliente, :paletas, :cajas, :pedidos_en_proceso, :fecha_objetivo, :vacio_lleno, :comentario_oficina)";

$insertSQL_picking_r = "INSERT INTO picking_r (aid_oid, cliente, paletas, cajas, pedidos_en_proceso, fecha_objetivo, vacio_lleno, comentario_oficina) 
                         VALUES (:aid_oid, :cliente, :paletas, :cajas, :pedidos_en_proceso, :fecha_objetivo, :vacio_lleno, :comentario_oficina)";

    // Preparar la consulta para 'picking'
    $stmt_picking = $pdo->prepare($insertSQL_picking);

        // Preparar la consulta para 'picking'
    $stmt_picking_r = $pdo->prepare($insertSQL_picking_r);

    // Iniciar transacción
    $pdo->beginTransaction();

    // Iterar sobre el array y ejecutar las inserciones
    foreach ($result as $row) {
        $stmt_picking->execute([
            ':aid_oid' => $row['aid_oid'],
            ':cliente' => $row['cliente'],
            ':paletas' => $row['paletas'],
            ':cajas' => $row['cajas'],
            ':pedidos_en_proceso' => $row['pedidos_en_proceso'],
            ':fecha_objetivo' => $row['fecha_objetivo'],
            ':vacio_lleno' => $row['vacio_lleno'],
            ':comentario_oficina' => $row['comentario_oficina']
        ]);

        $stmt_picking_r->execute([
            ':aid_oid' => $row['aid_oid'],
            ':cliente' => $row['cliente'],
            ':paletas' => $row['paletas'],
            ':cajas' => $row['cajas'],
            ':pedidos_en_proceso' => $row['pedidos_en_proceso'],
            ':fecha_objetivo' => $row['fecha_objetivo'],
            ':vacio_lleno' => $row['vacio_lleno'],
            ':comentario_oficina' => $row['comentario_oficina']
        ]);
    }

    // Confirmar la transacción
    $pdo->commit();

    // Redirigir a hoja_pk.php con un parámetro de éxito
    header("Location: ../hoja_pk/hoja_pk.php?status=success");
    exit();

} catch (PDOException $e) {
    $pdo->rollBack();
    // Redirigir a hoja_pk.php con un parámetro de error
    header("Location: ../hoja_pk/hoja_pk.php?status=error");
    exit();
}
?>
