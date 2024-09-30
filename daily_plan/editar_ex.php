<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Plan - Export</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">
  </head>
  <body style="background-image:url('../host_virtual_TI/images/Motivo2.png');margin: 0;padding: 0; font-family:montserrat;">
    <div style="margin-top: 90px;">
      <!-- Header -->
      <div class="header">
          <div class="logo-container">
              <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group" ></a>
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
        'mensaje' => 'el registro se editó satisfactoriamente.'
    ];

    if (!isset($_GET['id'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El registro no existe';
    }

    if (isset($_POST['submit'])) {
      try {
          $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
          $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

          $datos = [
              "id"=> $_GET['id'],
              "pedidos_despachados" => $_POST['pedidos_despachados'],
              "pedidos_en_proceso"=> $_POST['pedidos_en_proceso'],
              "vehiculo"=> $_POST['vehiculo'],
              "t_vehiculo"=> $_POST['t_vehiculo'],
              "bl"=> $_POST['bl'],
              "destino"=> $_POST['destino'],
              "fecha_objetivo"=> $_POST['fecha_objetivo'],
              "fecha_lleg_rampa"=> $_POST['fecha_lleg_rampa'],
              "fecha_sal_rampa"=> $_POST['fecha_sal_rampa'],
          ];

        $consultaSQL = "UPDATE export SET
          pedidos_en_proceso = :pedidos_en_proceso,
          pedidos_despachados = :pedidos_despachados,
          vehiculo = :vehiculo,
          t_vehiculo = :t_vehiculo,
          bl = :bl,
          destino = :destino,
          fecha_objetivo = :fecha_objetivo,
          fecha_lleg_rampa = :fecha_lleg_rampa,
          fecha_sal_rampa = :fecha_sal_rampa
          WHERE id = :id";


          $consulta = $conexion->prepare($consultaSQL);
          $consulta->execute($datos);

          $exportRecord = [
            "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
            "pedidos_despachados" => $_POST['pedidos_despachados'],
            "vehiculo" => $_POST['vehiculo'],
            "t_vehiculo" => $_POST['t_vehiculo'],
            "bl" => $_POST['bl'],
            "destino" => $_POST['destino'],
            "fecha_objetivo" => $_POST['fecha_objetivo'],
            "fecha_lleg_rampa" => $_POST['fecha_lleg_rampa'],  // Datos opcionales si son necesarios
            "fecha_sal_rampa" => $_POST['fecha_sal_rampa']    // Datos opcionales si son necesarios
        ];

        // Consulta SQL para insertar un nuevo registro en la tabla `export_r`
        $consultaRecordSQL = "INSERT INTO export_r 
            (aid_oid, cliente, pedidos_en_proceso, pedidos_despachados, vehiculo, t_vehiculo, bl, destino, fecha_objetivo, fecha_lleg_rampa, fecha_sal_rampa) 
            VALUES 
            (:aid_oid, :cliente, :pedidos_en_proceso, :pedidos_despachados, :vehiculo, :t_vehiculo, :bl, :destino, :fecha_objetivo, :fecha_lleg_rampa, :fecha_sal_rampa)";

        $consultaRecord = $conexion->prepare($consultaRecordSQL);
        $consultaRecord->execute($exportRecord);

    } catch(PDOException $error) {
        // Manejo de errores
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    




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
    <?php
    if ($resultado['error']) {
      ?>
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (isset($_POST['submit']) && !$resultado['error']) {
      ?>
      <div class="container mt-2" >
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert" style="position: absolute ; margin-top:500% !important">
              <?= $resultado['mensaje'] ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (isset($export) && $export) {
      ?>

      <div class="container" style="margin-top: 10%;">
        <div class="form-row">
          <div class="col-md-12">
            <h2 class="mt-4">Editando el campo  #<?= escapar($export['id'])?> de la tabla Export, del cliente <?= escapar($export['cliente'])?>.</h2>
            <a class="btn btn-success" href="../daily_plan/tabla_ex.php">Regresar a la tabla Export</a>
            <hr>
          <form method="POST">
          <div class="form-group">
                <label for="pedidos_en_proceso">Nueva cantidad de pedidos en proceso (modificar solo de ser necesario).</label>
                <textarea type="numbre" name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>"><?= escapar($export['pedidos_en_proceso']) ?></textarea>
            </div>
          <div class="form-row">
          <div class="form-group col-md-3">
                <label for="aid_oid">OID</label>
                <textarea type="text" name="aid_oid" id="aid_oid" rows="1" class="form-control" placeholder="<?= escapar($export['aid_oid']) ?>"><?= escapar($export['aid_oid']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="cliente">Cliente</label>
                <textarea type="text" name="cliente" id="cliente" rows="1" class="form-control" placeholder="<?= escapar($export['cliente']) ?>"><?= escapar($export['cliente']) ?></textarea>
              </div>
              <div class="form-group col-md-3">
                <label for="pedidos_despachados">Pedidos despachados</label>
                <textarea type="number" name="pedidos_despachados" id="pedidos_despachados" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos despachados: <?= escapar($export['pedidos_despachados']) ?>"><?= escapar($export['pedidos_despachados']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="vehiculo">Vehículo / Placa</label>
                <textarea type="text" name="vehiculo" id="vehiculo" rows="1" class="form-control" placeholder=" Anterior vehículo: <?= escapar($export['vehiculo']) ?>" ><?= escapar($export['vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="t_vehiculo">Tipo de vehículo</label>
                <textarea type="text" name="t_vehiculo" id="t_vehiculo" rows="1" class="form-control" placeholder="Anterior tipo de vehículo: <?= escapar($export['t_vehiculo']) ?>" ><?= escapar($export['t_vehiculo']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="bl">BL</label>
                <textarea type="text" name="bl" id="bl" rows="1" class="form-control" placeholder="Anterior BL: <?= escapar($export['bl']) ?>" ><?= escapar($export['bl']) ?></textarea>
              </div>

              <div class="form-group col-md-3">
                <label for="destino">Destino</label>
                <textarea type="text" name="destino" id="destino" rows="1" class="form-control" placeholder="Anterior destino: <?= escapar($export['destino']) ?>" ><?= escapar($export['destino']) ?></textarea>
              </div>
        </div>
        <div class="form-row">
              <div class="form-group col-md-3">
                <label for="destino">Fecha estimada de salida</label>
                <input type="date" name="fecha_objetivo" id="fecha_objetivo" rows="1" class="form-control" placeholder="<?= escapar($export['fecha_objetivo']) ?>" value="<?= escapar($export['fecha_objetivo']) ?>" ></input>
              </div>

              <div class="form-group col-md-3">
                <label for="fecha_lleg_rampa">Llegada a rampa</label>
                <input type="date" name="fecha_lleg_rampa" id="fecha_lleg_rampa" class="form-control" placeholder="Anterior fecha objetivo: <?= escapar($export['fecha_lleg_rampa']) ?>" value="<?= escapar($export['fecha_lleg_rampa']) ?>">
              </div>

                <div class="form-group col-md-3">
                  <label for="fecha_sal_rampa">Salida de rampa</label>
                  <input type="date" name="fecha_sal_rampa" id="fecha_sal_rampa" class="form-control" placeholder="Anterior fecha objetivo: <?= escapar($export['fecha_sal_rampa']) ?>" value="<?= escapar($export['fecha_sal_rampa']) ?>">
               </div>
              </div>
            </div>
            </form>
            <div class="form-group col-md-3">
                <input type="submit" name="submit" class="btn btn-primary" id="submit" value="Editar">
              </div>
          </div>
        </div>
      </div>
    </div>
      <?php
    }
    ?>
    <script src="../host_virtual_TI/js/script.js"></script>
  </body>
</html>
