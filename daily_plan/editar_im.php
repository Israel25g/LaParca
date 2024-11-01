<?php
  include("../apertura_sesion.php")
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Plan - Import</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">
  </head>
  <body style="background-image:url('../images/Motivo2.png')!important;margin: 0;padding: 0; font-family:montserrat;">
    <div style="margin-top: 90px;">
      <!-- Header -->
      <div class="header">
          <div class="logo-container">
              <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/Salida2.gif" alt="Logo_IPL_Group" ></a>
          </div>
          <h1>Daily plan</h1>
          <div class="cuadroFecha">
              <p id="fecha-actual"></p>
              <p id="hora-actual"></p>
          </div>
      </div>

    <?php
    include './funcionalidades/funciones.php';
    $config = include './funcionalidades/config_DP.php';

    $resultado = [
        'error' => false,
        'mensaje' => 'El registro se editó satisfactoriamente.'
    ];

    if (!isset($_GET['id'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El registro no existe';
        die();  // Evita continuar si no hay ID
    }

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
              comentario_bodega = :comentario_bodega
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

          $consultaRecordSQL = "INSERT INTO import_r 
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
        $consultaSQL = "SELECT * FROM import WHERE id = :id";

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

    <?php if ($resultado['error']) { ?>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <?php if (isset($_POST['submit']) && !$resultado['error']) { ?>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert" style="margin-top: 490%; margin-left: 600%; position: absolute">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <?php if (isset($export) && $export) { ?>
      <div class="container" style="margin-top: 10%;">
        <div class="form-row">
          <div class="col-md-12">
            <h2 class="mt-4">Editando el campo  #<?= escapar($export['id'])?> de la tabla Import, del cliente <?= escapar($export['cliente'])?>.</h2>
            <a class="btn btn-success" href="../daily_plan/tabla_im.php">Regresar a la tabla import</a>
    </br>
          <form method="POST" style="margin-top: 50px;">
            <div class="form-group">
              <label for="pedidos_en_proceso">Nueva cantidad de entradas por recibir (modificar solo de ser necesario).</label>
              <textarea name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>"><?= escapar($export['pedidos_en_proceso']) ?></textarea>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="aid_oid">AID</label>
                <input name="aid_oid" id="aid_oid" rows="1" class="form-control" placeholder="<?= escapar($export['aid_oid']) ?>" value="<?= escapar($export['aid_oid']) ?>"></input>
              </div>

              <div class="form-group col-md-3">
                <label for="cliente">Cliente</label>
                <input name="cliente" id="cliente" rows="1" class="form-control" placeholder="<?= escapar($export['cliente']) ?>" value="<?= escapar($export['cliente']) ?>"></input>
              </div>

              <div class="form-group col-md-3">
                <label for="pedidos_despachados">Entrada recibida</label>
                <input name="pedidos_despachados" id="pedidos_despachados" rows="1" class="form-control" placeholder="<?= escapar($export['pedidos_despachados']) ?>"></input>
                
              </div>
              

              <div class="form-group col-md-3">
                <label for="vehiculo">Vehículo / Placa</label>
                <textarea name="vehiculo" id="vehiculo" rows="1" class="form-control" placeholder="<?= escapar($export['vehiculo']) ?>"><?= escapar($export['vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="t_vehiculo">Tipo de vehículo</label>
                <textarea name="t_vehiculo" id="t_vehiculo" rows="1" class="form-control" placeholder="<?= escapar($export['t_vehiculo']) ?>"><?= escapar($export['t_vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="bl">BL / Contenedor</label>
                <textarea name="bl" id="bl" rows="1" class="form-control" placeholder="<?= escapar($export['bl']) ?>"><?= escapar($export['bl']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="destino">Origen</label>
                <textarea name="destino" id="destino" rows="1" class="form-control" placeholder="<?= escapar($export['destino']) ?>"><?= escapar($export['destino']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="t_carga">Tipo de carga</label>
                <textarea name="t_carga" id="t_carga" rows="1" class="form-control" placeholder="<?= escapar($export['t_carga']) ?>"><?= escapar($export['t_carga']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="paletas">Paletas</label>
                <textarea name="paletas" id="paletas" rows="1" class="form-control" placeholder="<?= escapar($export['paletas']) ?>"><?= escapar($export['paletas']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="cajas">Cajas</label>
                <textarea name="cajas" id="cajas" rows="1" class="form-control" placeholder="<?= escapar($export['cajas']) ?>"><?= escapar($export['cajas']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="unidades">Unidades</label>
                <textarea name="unidades" id="unidades" rows="1" class="form-control" placeholder="<?= escapar($export['unidades']) ?>"><?= escapar($export['unidades']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="fecha_objetivo">Fecha estimada de llegada</label>
                <input type="date" name="fecha_objetivo" id="fecha_objetivo" rows="1" class="form-control" placeholder="<?= escapar($export['fecha_objetivo']) ?>" value="<?= escapar($export['fecha_objetivo']) ?>" ></input>
              </div>

              <div class="form-group col-md-3">
                <label for="fecha_lleg_rampa">Fecha de llegada a Rampa</label>
                <input type="date" name="fecha_lleg_rampa" id="fecha_lleg_rampa" rows="1" class="form-control" placeholder="<?= escapar($export['fecha_lleg_rampa']) ?>" value="<?= escapar($export['fecha_lleg_rampa']) ?>"></input>
              </div>

              <div class="form-group col-md-3">
                <label for="fecha_sal_rampa">Fecha de salida de Rampa</label>
                <input type="date" name="fecha_sal_rampa" id="fecha_sal_rampa" rows="1" class="form-control" placeholder="<?= escapar($export['fecha_sal_rampa']) ?>" value="<?= escapar($export['fecha_sal_rampa']) ?>"></input>
              </div>
              <div class="form-group col-md-12">
                <label for="comentario_oficina">Comentarios de oficina</label>
                <textarea type="text" name="comentario_oficina" id="comentario_oficina" rows="3" class="form-control" placeholder="<?= escapar($export['comentario_oficina']) ?>" value="<?= escapar($export['comentario_oficina']) ?>"></textarea>
              </div>
              <div class="form-group col-md-12">
                <label for="comentario_bodega">Comentarios de bodega</label>
                <textarea type="text" name="comentario_bodega" id="comentario_bodega" rows="3" class="form-control" placeholder="<?= escapar($export['comentario_bodega']) ?>" value="<?= escapar($export['comentario_bodega']) ?>"></textarea>
              </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary col-md-3"  style="margin-bottom: 100px !important;">Actualizar</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </div>
  </body>
  <footer></footer>
  <script src=".././host_virtual_TI/js/script.js"></script>
</html>