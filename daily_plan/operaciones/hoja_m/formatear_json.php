<?php
// Nombre del archivo JSON que deseas leer
$jsonFile = 'arreglo_m.json';

// Configuración de la base de datos
include "../../funcionalidades/config_h.php";

try {
    
    
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
    $mostrarTodo = isset($_GET['mostrar_todo']);
    
    // Comprobar si el archivo JSON existe
    if (!file_exists($jsonFile)) {
        die('Los datos no se encontraron.');
    }
    
    // Leer el contenido del archivo JSON
    $jsonString = file_get_contents($jsonFile);
    
    // Decodificar el JSON en un array asociativo
    $data = json_decode($jsonString, true); // true para obtener un array asociativo
    
    $result = [];
    if ($filtro == "import") {
        // Conjunto de encabezados para tu base de datos
        $headers = ['aid_oid', 'cliente', 'vehiculo', 't_vehiculo', 'bl', 'destino', 't_carga', 'paletas', 'cajas', 'unidades', 'pedidos_en_proceso', 'fecha_objetivo', 'comentario_oficina'];
        
        // Procesar cada fila y combinar con los encabezados
        foreach ($data as $row) {
            $result[] = array_combine($headers, $row); // Combina los nombres de las columnas con sus valores
        }
    
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
    }
elseif ($filtro == "export"){
     // Crear la conexión PDO
     $pdo = new PDO($dsn, $user, $pass, $options);
    
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
     $headers = ['aid_oid', 'cliente', 'vehiculo', 't_vehiculo', 'bl', 'destino','t_carga', 'paletas', 'cajas', 'unidades', 'pedidos_en_proceso', 'fecha_objetivo', 'comentario_oficina'];
     
     // Procesar cada fila y combinar con los encabezados
     foreach ($data as $row) {
         $result[] = array_combine($headers, $row); // Combina los nombres de las columnas con sus valores
     }
     // SQL para insertar en la tabla 'export'
     $insertSQL_export = "INSERT INTO export (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) 
                          VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";
 
     // SQL para insertar en la tabla 'export_r'
     $insertSQL_export_r = "INSERT INTO export_r (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) 
                            VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";
 
     // Preparar la consulta para 'export'
     $stmt_export = $pdo->prepare($insertSQL_export);
 
     // Preparar la consulta para 'export_r'
     $stmt_export_r = $pdo->prepare($insertSQL_export_r);
 
     // Iniciar transacción para garantizar la consistencia de las inserciones
     $pdo->beginTransaction();
 
     // Iterar sobre el array y ejecutar las inserciones
     foreach ($result as $row) {
         // Inserción en la tabla 'export'
         $stmt_export->execute([
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
 
         // Inserción en la tabla 'export_r'
         $stmt_export_r->execute([
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
}
elseif ($filtro == "picking") {
    // Crear la conexión PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
    
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
}

    // Redirigir a hoja_m.php con un parámetro de éxito
    if ($filtro == "import") {
        header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=import&status=success");
        exit();
    } elseif ($filtro =="export"){
        header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=export&status=success");
        exit();
    }elseif ($filtro =="picking") {
        header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=picking&status=success");
        exit();
    }

} catch (PDOException $e) {
    // En caso de error, deshacer la transacción
    $pdo->rollBack();

    // Redirigir a hoja_m.php con un parámetro de error
        // Redirigir a hoja_m.php con un parámetro de error
        if ($filtro == "import") {
            header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=import&status=error");
            exit();
           
        } elseif ($filtro =="export"){
            header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=export&status=error");
            exit();
        }elseif ($filtro =="picking") {
            header("Location: ./hoja_m.php?fecha_estimacion_llegada=&filtro=picking&status=error");
            exit();
    }}
?>
