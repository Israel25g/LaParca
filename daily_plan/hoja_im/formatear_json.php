<?php
// Nombre del archivo JSON que deseas leer
$jsonFile = 'arreglo_im.json'; 

// Comprobar si el archivo JSON existe
if (!file_exists($jsonFile)) {
    die('Los datos no se encontraron.');
}

// Leer el contenido del archivo JSON
$jsonString = file_get_contents($jsonFile);

// Decodificar el JSON en un array asociativo
$data = json_decode($jsonString, true); // true para obtener un array asociativo

$result = [];

//  conjunto de encabezados para tu base de datos
$headers = ['aid_oid', 'cliente', 'vehiculo', 't_vehiculo', 'bl', 'destino', 't_carga', 'paletas', 'cajas', 'unidades', 'pedidos_en_proceso', 'fecha_objetivo', 'comentario_oficina'];

// Procesar cada fila y combinar con los encabezados
foreach ($data as $row) {
    $result[] = array_combine($headers, $row); // Combina los nombres de las columnas con sus valores
}

// Configuración de la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos está en otro host
$db   = 'u366386740_db_mainbase';
$user = 'u366386740_admin123';
$pass = '1plGr0up01*';
$charset = 'utf8mb4';

// Configurar el DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass, $options);

    // SQL para insertar en la tabla 'import'
    $insertSQL_import = "INSERT INTO import (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) 
                         VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

    // SQL para insertar en la tabla 'import_r'
    $insertSQL_import_r = "INSERT INTO import_r (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) 
                           VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

    // Preparar la consulta para 'import'
    $stmt_import = $pdo->prepare($insertSQL_import);

    // Preparar la consulta para 'import_r'
    $stmt_import_r = $pdo->prepare($insertSQL_import_r);

    // Iniciar transacción para garantizar la consistencia de las inserciones
    $pdo->beginTransaction();

    // Iterar sobre el array y ejecutar las inserciones
    foreach ($result as $row) {
        // Inserción en la tabla 'import'
        $stmt_import->execute([
            ':aid_oid' => $row['aid_oid'],
            ':cliente' => $row['cliente'],
            ':vehiculo' => $row['vehiculo'],
            ':t_vehiculo' => $row['t_vehiculo'],
            ':bl' => $row['bl'],
            ':destino' => $row['destino'],
            ':t_carga' => $row['t_carga'],
            ':paletas' => $row['paletas'],
            ':cajas' => $row['cajas'],
            ':unidades' => $row['unidades'],
            ':pedidos_en_proceso' => $row['pedidos_en_proceso'],
            ':fecha_objetivo' => $row['fecha_objetivo'],
            ':comentario_oficina' => $row['comentario_oficina']
        ]);

        // Inserción en la tabla 'import_r'
        $stmt_import_r->execute([
            ':aid_oid' => $row['aid_oid'],
            ':cliente' => $row['cliente'],
            ':vehiculo' => $row['vehiculo'],
            ':t_vehiculo' => $row['t_vehiculo'],
            ':bl' => $row['bl'],
            ':destino' => $row['destino'],
            ':t_carga' => $row['t_carga'],
            ':paletas' => $row['paletas'],
            ':cajas' => $row['cajas'],
            ':unidades' => $row['unidades'],
            ':pedidos_en_proceso' => $row['pedidos_en_proceso'],
            ':fecha_objetivo' => $row['fecha_objetivo'],
            ':comentario_oficina' => $row['comentario_oficina']
        ]);
    }

    // Confirmar la transacción
    $pdo->commit();

    echo "Datos insertados correctamente en ambas tablas.";

} catch (PDOException $e) {
    // En caso de error, deshacer la transacción
    $pdo->rollBack();
    echo "Error al insertar datos: " . $e->getMessage();
}
?>
<script>
  // Redirigir a la pagina de datos
  setTimeout(function() {
    window.location.href = '../hoja_im/hoja_im.php';
  },);
</script>
