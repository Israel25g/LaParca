<?php
    include '../funcionalidades/funciones.php';
    $config = include '../funcionalidades/config_DP.php';

    $resultado = [
        'error' => false,
        'mensaje' => 'El registro se editó satisfactoriamente.'
    ];

    if (!isset($_GET['id'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El registro no existe';
        die();  // Evita continuar si no hay ID
    }

    $operacion = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    if (isset($_POST['submit'])) {
      try {
          $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
          $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

          $datos = [
              "id"=> $_GET['id'],
              "aid_oid" => $_POST['aid_oid'],
              "cliente" => $_POST['cliente'],
              "pedidos_despachados" => $_POST['pedidos_despachados'],
              "pedidos_en_proceso"=> $_POST['pedidos_en_proceso'],
              "vehiculo"=> $_POST['vehiculo'],
              "t_vehiculo"=> $_POST['t_vehiculo'],
              "bl"=> $_POST['bl'],
              "destino"=> $_POST['destino'],
              "t_carga" => $_POST['t_carga'],  // Añadido
            "paletas" => $_POST['paletas'],  // Añadido
            "cajas" => $_POST['cajas'],      // Añadido
            "unidades" => $_POST['unidades'],// Añadido
              "fecha_objetivo"=> $_POST['fecha_objetivo'],
              "fecha_lleg_rampa"=> $_POST['fecha_lleg_rampa'],
              "fecha_sal_rampa"=> $_POST['fecha_sal_rampa'],
              "comentario_oficina"=> $_POST['comentario_oficina'],
              "comentario_bodega"=> $_POST['comentario_bodega'],
          ];

          $consultaSQL = "UPDATE export SET
              aid_oid = :aid_oid,
              cliente = :cliente,
              pedidos_despachados = :pedidos_despachados,
              pedidos_en_proceso = :pedidos_en_proceso,
              vehiculo = :vehiculo,
              t_vehiculo = :t_vehiculo,
              bl = :bl,
              destino = :destino,
              t_carga = :t_carga,
              paletas = :paletas,
              cajas = :cajas,
              unidades = :unidades,
              fecha_objetivo = :fecha_objetivo,
              fecha_lleg_rampa = :fecha_lleg_rampa,
              fecha_sal_rampa = :fecha_sal_rampa,
              comentario_oficina = :comentario_oficina,
              comentario_bodega = :comentario_bodega
              WHERE id = :id";

          $consulta = $conexion->prepare($consultaSQL);
          $consulta->execute($datos);

          // Inserción en la tabla export_r
          $exportRecord = [
              "aid_oid" => $_POST['aid_oid'],  
              "cliente" => $_POST['cliente'],
              "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
              "pedidos_despachados" => $_POST['pedidos_despachados'],
              "vehiculo" => $_POST['vehiculo'],
              "t_vehiculo" => $_POST['t_vehiculo'],
              "bl" => $_POST['bl'],
              "destino" => $_POST['destino'],
              "t_carga" => $_POST['t_carga'],
              "paletas" => $_POST['paletas'],
              "cajas" => $_POST['cajas'],
              "unidades" => $_POST['unidades'],
              "fecha_objetivo" => $_POST['fecha_objetivo'],
              "fecha_lleg_rampa" => $_POST['fecha_lleg_rampa'],
              "fecha_sal_rampa" => $_POST['fecha_sal_rampa'],
              "comentario_oficina" => $_POST['comentario_oficina'],
              "comentario_bodega" => $_POST['comentario_bodega']
          ];

          $consultaRecordSQL = "INSERT INTO export_r 
              (aid_oid, cliente, pedidos_en_proceso, pedidos_despachados, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, fecha_objetivo, fecha_lleg_rampa, fecha_sal_rampa, comentario_oficina, comentario_bodega) 
              VALUES 
              (:aid_oid, :cliente, :pedidos_en_proceso, :pedidos_despachados, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :fecha_objetivo, :fecha_lleg_rampa, :fecha_sal_rampa,:comentario_oficina, :comentario_bodega)";

          $consultaRecord = $conexion->prepare($consultaRecordSQL);
          $consultaRecord->execute($exportRecord);

      } catch(PDOException $error) {
          $resultado['error'] = true;
          $resultado['mensaje'] = $error->getMessage();
      }
    }

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $id = $_GET['id'];
        $consultaSQL = "SELECT * FROM export WHERE id = :id";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute(['id' => $id]);

        $export = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$export) {
            $resultado['error'] = true;
            $resultado['mensaje'] = 'No se ha encontrado el registro';
        }

    } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
    ?>