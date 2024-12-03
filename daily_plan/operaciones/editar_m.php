<?php 
    include '../funcionalidades/funciones.php';
    $config = include '../funcionalidades/config_DP.php';

    $resultado = [
        'error' => false,
        'mensaje' => 'El registro se editó satisfactoriamente.'
    ];

    // Comprobar que el ID y el tipo de operación existen en la URL
    if (!isset($_GET['id']) || !isset($_GET['type'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El registro o el tipo de operación no existen';
        die();  // Evita continuar si no hay ID o type
    }

    // Definir el tipo de operación
$tipo = $_GET['type']; // Definir la variable $type

    // Obtener el valor de la tabla en función del tipo
    $table = '';
    $table_record = '';
    switch ($_GET['type']) {
        case 'import':
            $table = 'import';
            $table_record = 'import_r';
            break;
        case 'export':
            $table = 'export';
            $table_record = 'export_r';
            break;
        case 'picking':
            $table = 'picking';
            $table_record = 'picking_r';
            break;
        default:
            $resultado['error'] = true;
            $resultado['mensaje'] = 'Tipo de operación no válida';
            die();
    }

    if (isset($_POST['submit'])) {
      try {
          $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
          $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

          switch ($_GET['type']) {
            case 'import':
                
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
                    "t_carga" => $_POST['t_carga'],  
                    "paletas" => $_POST['paletas'],  
                    "cajas" => $_POST['cajas'],      
                    "unidades" => $_POST['unidades'],
                    "fecha_objetivo"=> $_POST['fecha_objetivo'],
                    "fecha_lleg_rampa"=> $_POST['fecha_lleg_rampa'],
                    "fecha_sal_rampa"=> $_POST['fecha_sal_rampa'],
                    "comentario_oficina"=> $_POST['comentario_oficina'],
                    "comentario_bodega"=> $_POST['comentario_bodega'],
                ];
                $consultaSQL = "UPDATE import SET
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
                    comentario_bodega = :comentario_bodega,
                    updated_at = current_timestamp
                    WHERE id = :id";


                
      
                $consulta = $conexion->prepare($consultaSQL);
                $consulta->execute($datos);
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
      
                $consultaRecordSQL = "INSERT INTO import_r 
                    (aid_oid, cliente, pedidos_en_proceso, pedidos_despachados, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, fecha_objetivo, fecha_lleg_rampa,  comentario_oficina, comentario_bodega, updated_at) 
                    VALUES 
                    (:aid_oid, :cliente, :pedidos_en_proceso, :pedidos_despachados, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :fecha_objetivo, :fecha_lleg_rampa, :comentario_oficina, :comentario_bodega, current_timestamp)";
      
                $consultaRecord = $conexion->prepare($consultaRecordSQL);
                $consultaRecord->execute($exportRecord);
                break;
            case 'export':
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
                    comentario_bodega = :comentario_bodega,
                    updated_at = current_timestamp
                    WHERE id = :id";
      
                $consulta = $conexion->prepare($consultaSQL);
                $consulta->execute($datos);
      
                // Inserción en la tabla export_r
                $exportRecord = [
                    "aid_oid" => $_POST['aid_oid'],  // Asegúrate de que estén estos datos
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
                    (aid_oid, cliente, pedidos_en_proceso, pedidos_despachados, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, fecha_objetivo, fecha_lleg_rampa,  fecha_sal_rampa, comentario_oficina, comentario_bodega,updated_at) 
                    VALUES 
                    (:aid_oid, :cliente, :pedidos_en_proceso, :pedidos_despachados, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :fecha_objetivo, :fecha_lleg_rampa, :fecha_sal_rampa,:comentario_oficina, :comentario_bodega, current_timestamp)";
      
                $consultaRecord = $conexion->prepare($consultaRecordSQL);
                $consultaRecord->execute($exportRecord);
                break;
            case 'picking':
                $datos = [
                    "id" => $_GET['id'],
                    "aid_oid" => $_POST['aid_oid'],
                    "cliente" => $_POST['cliente'],
                    "pedidos_despachados" => $_POST['pedidos_despachados'],
                    "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
                    "t_carga" => $_POST['t_carga'],
                    "paletas" => $_POST['paletas'],
                    "cajas" => $_POST['cajas'],
                    "fecha_objetivo" => $_POST['fecha_objetivo'],
                    "vacio_lleno" => $_POST['vacio_lleno'],
                    "comentario_oficina" => $_POST['comentario_oficina'],
                    "comentario_bodega" => $_POST['comentario_bodega'],
                ];
      
                // Actualización de la tabla principal según el tipo
                $consultaSQL = "UPDATE $table SET
                    aid_oid = :aid_oid,
                    cliente = :cliente,
                    pedidos_despachados = :pedidos_despachados,
                    pedidos_en_proceso = :pedidos_en_proceso,
                    t_carga = :t_carga,
                    paletas = :paletas,
                    cajas = :cajas,
                    fecha_objetivo = :fecha_objetivo,
                    vacio_lleno = :vacio_lleno,
                    comentario_oficina = :comentario_oficina,
                    comentario_bodega = :comentario_bodega,
                    updated_at = current_timestamp
                    WHERE id = :id";
      
                $consulta = $conexion->prepare($consultaSQL);
                $consulta->execute($datos);
      
                // Inserción en la tabla de registro según el tipo
                $exportRecord = [
                    "aid_oid" => $_POST['aid_oid'],
                    "cliente" => $_POST['cliente'],
                    "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
                    "pedidos_despachados" => $_POST['pedidos_despachados'],
                    "t_carga" => $_POST['t_carga'],
                    "paletas" => $_POST['paletas'],
                    "cajas" => $_POST['cajas'],
                    "fecha_objetivo" => $_POST['fecha_objetivo'],
                    "vacio_lleno" => $_POST['vacio_lleno'],
                    "comentario_oficina" => $_POST['comentario_oficina'],
                    "comentario_bodega" => $_POST['comentario_bodega']
                ];
      
                $consultaRecordSQL = "INSERT INTO $table_record 
                    (aid_oid, cliente, pedidos_en_proceso, pedidos_despachados, t_carga, paletas, cajas, fecha_objetivo, vacio_lleno, comentario_oficina, comentario_bodega,updated_at) 
                    VALUES 
                    (:aid_oid, :cliente, :pedidos_en_proceso, :pedidos_despachados, :t_carga, :paletas, :cajas, :fecha_objetivo, :vacio_lleno, :comentario_oficina, :comentario_bodega, current_timestamp)";
      
                $consultaRecord = $conexion->prepare($consultaRecordSQL);
                $consultaRecord->execute($exportRecord);
                break;
            default:
                $resultado['error'] = true;
                $resultado['mensaje'] = 'Tipo de operación no válida';
                die();
        }

         

      } catch(PDOException $error) {
          $resultado['error'] = true;
          $resultado['mensaje'] = $error->getMessage();
      }
    }

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $id = $_GET['id'];
        $consultaSQL = "SELECT * FROM $table WHERE id = :id";

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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Plan - Import</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../main-global.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
  </head>
  <body style="background-image:url('../../images/Motivo2.png')!important;margin: 0;padding: 0; font-family:montserrat;">
    <div style="margin-top: 90px;">
      <!-- Header -->
      <div class="header-error">
          <div class="logo-container">
              <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group" ></a>
          </div>
          <h1>Daily plan</h1>
          <div class="cuadroFecha">
              <p id="fecha-actual" style="font-size: small"></p>
              <p id="hora-actual"></p>
          </div>
      </div>



    <?php if ($resultado['error']): ?>
        <p style="color: red;"><?php echo $resultado['mensaje']; ?></p>
    <?php endif; ?>

    <!-- Formulario para Import -->
    <?php if ($tipo === 'import'): ?>
        
        
        <form method="POST" class="container mt-5" style="max-width: 1500px;">
            <div class="form-row">
            <div class="col-md-12">
                        <h2 class="mt-4 nombre-tabla"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=import"><i class="bi bi-caret-left-fill arrow-back"></i></a>Editando el campo  #<?= escapar($export['id'])?> de la tabla Import, del cliente <?= escapar($export['cliente'])?>.</h2>
                        <a class="btn btn-success" href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=import">Regresar a la tabla import</a>
            </div>
            </div>
            <hr/>
        <div class="form-row">
        <div class="form-group col-md-12">
            <label for="pedidos_en_proceso">Nueva cantidad de entradas por recibir (modificar solo de ser necesario).</label>
            <textarea name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>"><?= escapar($export['pedidos_en_proceso']) ?></textarea>
        </div>
        <div class="form-group col-md-2">
            <label for="aid_oid">AID</label>
            <input name="aid_oid" id="aid_oid" class="form-control" placeholder="<?= escapar($export['aid_oid']) ?>" value="<?= escapar($export['aid_oid']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="cliente">Cliente</label>
            <input name="cliente" id="cliente" class="form-control" placeholder="<?= escapar($export['cliente']) ?>" value="<?= escapar($export['cliente']) ?>">
        </div>
        

        <div class="form-group col-md-2">
            <label for="pedidos_despachados">Entrada recibida</label>
            <input name="pedidos_despachados" id="pedidos_despachados" class="form-control" placeholder="<?= escapar($export['pedidos_despachados']) ?>" value="<?= escapar($export['pedidos_despachados']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="vehiculo">Vehículo / Placa</label>
            <textarea name="vehiculo" id="vehiculo" rows="1" class="form-control" value="<?= escapar($export['vehiculo']) ?>"><?= escapar($export['vehiculo']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="t_vehiculo">Tipo de vehículo</label>
            <textarea name="t_vehiculo" id="t_vehiculo" rows="1" class="form-control" value="<?= escapar($export['t_vehiculo']) ?>"><?= escapar($export['t_vehiculo']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="bl">BL / Contenedor</label>
            <textarea name="bl" id="bl" rows="1" class="form-control" value="<?= escapar($export['bl']) ?>"><?= escapar($export['bl']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="destino">Origen</label>
            <textarea name="destino" id="destino" rows="1" class="form-control" value="<?= escapar($export['destino']) ?>"><?= escapar($export['destino']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="t_carga">Tipo de carga</label>
            <textarea name="t_carga" id="t_carga" rows="1" class="form-control" value="<?= escapar($export['t_carga']) ?>"><?= escapar($export['t_carga']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="paletas">Paletas</label>
            <textarea name="paletas" id="paletas" rows="1" class="form-control" value="<?= escapar($export['paletas']) ?>"><?= escapar($export['paletas']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="cajas">Cajas</label>
            <textarea name="cajas" id="cajas" rows="1" class="form-control" value="<?= escapar($export['cajas']) ?>"><?= escapar($export['cajas']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="unidades">Unidades</label>
            <textarea name="unidades" id="unidades" rows="1" class="form-control" value="<?= escapar($export['unidades']) ?>"><?= escapar($export['unidades']) ?></textarea>
        </div>
    </div>
    <hr/>

    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="">Fecha estimada de llegada</label>
            <input type="date" name="" id="" class="form-control" value="<?= escapar($export['eta_etd']) ?>" value="<?= escapar($export['eta_etd']) ?>" readonly>
        </div>


        <div class="form-group col-md-2">
            <label for="fecha_objetivo">Fecha de programacion</label>
            <input type="date" name="fecha_objetivo" id="fecha_objetivo" class="form-control" value="<?= escapar($export['fecha_objetivo']) ?>" value="<?= escapar($export['fecha_objetivo']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="fecha_lleg_rampa">Fecha de llegada a Rampa </label>
            <input type="date" name="fecha_lleg_rampa" id="fecha_lleg_rampa" class="form-control" value="<?= escapar($export['fecha_lleg_rampa']) ?>" value="<?= escapar($export['fecha_lleg_rampa']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="fecha_sal_rampa">Fecha de salida de Rampa</label>
            <input type="date" name="fecha_sal_rampa" id="fecha_sal_rampa" class="form-control" value="<?= escapar($export['fecha_sal_rampa']) ?>" value="<?= escapar($export['fecha_sal_rampa']) ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="comentario_oficina">Comentarios de oficina</label>
            <textarea name="comentario_oficina" id="comentario_oficina" rows="3" class="form-control" value="<?= escapar($export['comentario_oficina']) ?>"><?= escapar($export['comentario_oficina']) ?></textarea>
        </div>

        <div class="form-group col-md-12">
            <label for="comentario_bodega">Comentarios de bodega</label>
            <textarea name="comentario_bodega" id="comentario_bodega" rows="3" class="form-control" value="<?= escapar($export['comentario_bodega']) ?>"><?= escapar($export['comentario_bodega']) ?></textarea>
        </div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary col-md-3" style="margin-bottom: 100px !important;">Actualizar</button>
</form>



    <!-- Formulario para Export -->
    <?php elseif ($tipo === 'export'): ?>

        <form method="POST" class="container mt-5" style="max-width: 1500px;">
        <div class="form-row">
            <div class="col-md-12">
                        <h2 class="mt-4 nombre-tabla"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=export"><i class="bi bi-caret-left-fill arrow-back"></i></a>Editando el campo  #<?= escapar($export['id'])?> de la tabla Export, del cliente <?= escapar($export['cliente'])?>.</h2>
                        <a class="btn btn-success" href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=export">Regresar a la tabla export</a>
            </div>
        </div>

            <hr/>
            <div class="form-row">  
            <div class=" form-group col-md-12">
              <label for="pedidos_en_proceso">Nueva cantidad de pedidos en proceso (modificar solo de ser necesario).</label>
              <textarea name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" value="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>"><?= escapar($export['pedidos_en_proceso']) ?></textarea>
            </div>
            
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="aid_oid">OID</label>
                <input name="aid_oid" id="aid_oid" rows="1" class="form-control" value="<?= escapar($export['aid_oid']) ?>" value="<?= escapar($export['aid_oid']) ?>"></input>
              </div>

              <div class="form-group col-md-2">
                <label for="cliente">Cliente</label>
                <input name="cliente" id="cliente" rows="1" class="form-control" value="<?= escapar($export['cliente']) ?>" value="<?= escapar($export['cliente']) ?>"></input>
              </div>

              <div class="form-group col-md-2">
                <label for="pedidos_despachados">Pedidos despachados</label>
                <textarea name="pedidos_despachados" id="pedidos_despachados" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos despachados: <?= escapar($export['pedidos_despachados']) ?>"><?= escapar($export['pedidos_despachados']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="vehiculo">Vehículo / Placa</label>
                <textarea name="vehiculo" id="vehiculo" rows="1" class="form-control" value="<?= escapar($export['vehiculo']) ?>"><?= escapar($export['vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="t_vehiculo">Tipo de vehículo</label>
                <textarea name="t_vehiculo" id="t_vehiculo" rows="1" class="form-control" value="<?= escapar($export['t_vehiculo']) ?>"><?= escapar($export['t_vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="bl">BL / Contenedor</label>
                <textarea name="bl" id="bl" rows="1" class="form-control" value="<?= escapar($export['bl']) ?>"><?= escapar($export['bl']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="destino">Destino</label>
                <textarea name="destino" id="destino" rows="1" class="form-control" value="<?= escapar($export['destino']) ?>"><?= escapar($export['destino']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="t_carga">Tipo de carga</label>
                <textarea name="t_carga" id="t_carga" rows="1" class="form-control" value="<?= escapar($export['t_carga']) ?>"><?= escapar($export['t_carga']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="paletas">Paletas</label>
                <textarea name="paletas" id="paletas" rows="1" class="form-control" value="<?= escapar($export['paletas']) ?>"><?= escapar($export['paletas']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="cajas">Cajas</label>
                <textarea name="cajas" id="cajas" rows="1" class="form-control" value="<?= escapar($export['cajas']) ?>"><?= escapar($export['cajas']) ?></textarea>
              </div>

              <div class="form-group col-md-2">
                <label for="unidades">Unidades</label>
                <textarea name="unidades" id="unidades" rows="1" class="form-control" value="<?= escapar($export['unidades']) ?>"><?= escapar($export['unidades']) ?></textarea>
              </div>
            </div>
            <hr/>

            <div class="form-row">
            <div class="form-group col-md-3">
                  <label for="">Fecha estimada de salida</label>
                  <input type="date" name="" id="" rows="1" class="form-control" value="<?= escapar($export['eta_etd']) ?>" readonly></input>
                </div>

                <div class="form-group col-md-3">
                  <label for="fecha_objetivo">Fecha de <br/> programacion</label>
                  <input type="date" name="fecha_objetivo" id="fecha_objetivo" rows="1" class="form-control" value="<?= escapar($export['fecha_objetivo']) ?>" ></input>
                </div>
  
                <div class="form-group col-md-3">
                  <label for="fecha_lleg_rampa">Fecha de <br/>llegada a Rampa</label>
                  <input type="date" name="fecha_lleg_rampa" id="fecha_lleg_rampa" rows="1" class="form-control" value="<?= escapar($export['fecha_lleg_rampa']) ?>" ></input>
                </div>
  
                <div class="form-group col-md-3">
                  <label for="fecha_sal_rampa">Fecha de salida de Rampa</label>
                  <input type="date" name="fecha_sal_rampa" id="fecha_sal_rampa" rows="1" class="form-control" value="<?= escapar($export['fecha_sal_rampa']) ?>"></input>
                </div>
            </div>

            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="comentario_oficina">Comentarios de oficina</label>
                <textarea type="text" name="comentario_oficina" id="comentario_oficina" rows="3" class="form-control" value="<?= escapar($export['comentario_oficina']) ?>"><?= escapar($export['comentario_oficina']) ?></textarea>
              </div>
              <div class="form-group col-md-12">
                <label for="comentario_bodega">Comentarios de bodega</label>
                <textarea type="text" name="comentario_bodega" id="comentario_bodega" rows="3" class="form-control" value="<?= escapar($export['comentario_bodega']) ?>"><?= escapar($export['comentario_bodega']) ?></textarea>
              </div>
              <button type="submit" name="submit" class="btn btn-primary col-md-3" style="margin-bottom: 100px !important;">Actualizar</button>
            </div>
          </form>

    <!-- Formulario para Picking -->
    <?php elseif ($tipo === 'picking'): ?>

        <form method="POST" class="container mt-5" style="max-width: 1500px;">

        <div class="form-row">
            <div class="col-md-12">
                        <h2 class="mt-4 nombre-tabla"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking"><i class="bi bi-caret-left-fill arrow-back"></i></a>Editando el campo  #<?= escapar($export['id'])?> de la tabla Picking, del cliente <?= escapar($export['cliente'])?>.</h2>
                        <a class="btn btn-success" href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking">Regresar a la tabla Picking</a>
            </div>
        </div>
        <hr/>
    <div class="form-group">
        <label for="pedidos_en_proceso">Cantidad de Unidades por pickear (modificar solo de ser necesario).</label>
        <textarea name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" value="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>"><?= escapar($export['pedidos_en_proceso']) ?></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="aid_oid">OID</label>
            <input name="aid_oid" id="aid_oid" class="form-control" value="<?= escapar($export['aid_oid']) ?>" value="<?= escapar($export['aid_oid']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="cliente">Cliente</label>
            <input name="cliente" id="cliente" class="form-control" value="<?= escapar($export['cliente']) ?>" value="<?= escapar($export['cliente']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="pedidos_despachados">Unidades ya pickeadas</label>
            <textarea name="pedidos_despachados" id="pedidos_despachados" rows="1" class="form-control" value="<?= escapar($export['pedidos_despachados']) ?>"><?= escapar($export['pedidos_despachados']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="t_carga">Tipo de carga</label>
            <textarea name="t_carga" id="t_carga" rows="1" class="form-control" value="<?= escapar($export['t_carga']) ?>"><?= escapar($export['t_carga']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="paletas">Paletas</label>
            <textarea name="paletas" id="paletas" rows="1" class="form-control" value="<?= escapar($export['paletas']) ?>"><?= escapar($export['paletas']) ?></textarea>
        </div>

        <div class="form-group col-md-2">
            <label for="cajas">Cajas</label>
            <textarea name="cajas" id="cajas" rows="1" class="form-control" value="<?= escapar($export['cajas']) ?>"><?= escapar($export['cajas']) ?></textarea>
        </div>
        
        <div class="form-group col-md-2">
            <label for="vacio_lleno">Prioridad de picking</label>
            <input type="text" name="vacio_lleno" id="vacio_lleno" class="form-control" value="<?= escapar($export['vacio_lleno']) ?>" value="<?= escapar($export['vacio_lleno']) ?>">
        </div>

        <div class="form-group col-md-2">
            <label for="fecha_objetivo">Fecha de requerido</label>
            <input type="date" name="fecha_objetivo" id="fecha_objetivo" class="form-control" value="<?= escapar($export['fecha_objetivo']) ?>" value="<?= escapar($export['fecha_objetivo']) ?>">
        </div>


        <div class="form-group col-md-12">
            <label for="comentario_oficina">Comentarios de oficina</label>
            <textarea name="comentario_oficina" id="comentario_oficina" rows="3" class="form-control" value="<?= escapar($export['comentario_oficina']) ?>"><?= escapar($export['comentario_oficina']) ?></textarea>
        </div>

        <div class="form-group col-md-12">
            <label for="comentario_bodega">Comentarios de bodega</label>
            <textarea name="comentario_bodega" id="comentario_bodega" rows="3" class="form-control" value="<?= escapar($export['comentario_bodega']) ?>"><?= escapar($export['comentario_bodega']) ?></textarea>
        </div>
    </div>

    <button type="submit" name="submit" class="btn btn-primary col-md-3" style="margin-bottom: 100px;">Actualizar</button>
</form>

          </div>
    <?php endif; ?>
    <script src="../../host_virtual_TI/js/script.js"></script>
</body>
</html>