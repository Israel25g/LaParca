<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Datos - Daily Plan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">
  </head>
  <body style="background-image:url('../host_virtual_TI/images/Motivo2.png');margin: 0;padding: 0;  font-family:montserrat;">
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
      <!-- Fin del Header -->

      <!-- Navbar -->
      <div class="container-nav" style="margin-top: -4%; margin-left: 33%; position: fixed; z-index: 999;">
          <div class="navbarr">
              <ul class="nav" id="detallesOps">
                  <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
                  <li class="nav-li"><a href="../daily_plan/grafico.php">Gráficas</a></li>
                  <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
                  <li class="nav-li"><a href="#">Dashboards</a></li>
              </ul>
          </div>
      </div>
    <?php
    include './funcionalidades/funciones.php';
    $config = include './funcionalidades/config_DP.php';

    $resultado = [
        'error' => false,
        'mensaje' => 'El registro se editó exitosamente'
    ];

    if (!isset($_GET['id'])) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'El registro no existe';
    }

    if (isset($_POST['submit'])) {
      try {
          $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
          $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

          $import = [
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

          $consultaSQL = "UPDATE import SET
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
          $consulta->execute($import);

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
            (pedidos_en_proceso, pedidos_despachados, vehiculo, t_vehiculo, bl, destino, fecha_objetivo, fecha_lleg_rampa, fecha_sal_rampa) 
            VALUES 
            (:pedidos_en_proceso, :pedidos_despachados, :vehiculo, :t_vehiculo, :bl, :destino, :fecha_objetivo, :fecha_lleg_rampa, :fecha_sal_rampa)";

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
        $consultaSQL = "SELECT * FROM import WHERE id = :id";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute(['id' => $id]);

        $import = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$import) {
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
              <?= escapar($resultado['mensaje']) ?>
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
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert" style="margin-top: 140px; position:absolute">
              <?= escapar($resultado['mensaje']) ?>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <?php
    if (isset($import) && $import) {
      ?>
      <form method="POST">
      <div class="container" style="margin-top: 0%;">
        <div class="row">
          <div class="col-md-12">
            <h2 class="mt-4">Editando el campo  #<?= escapar($import['id'])?> de la tabla Import, del cliente <?= escapar($import['cliente'])?>.</h2>
            <a class="btn btn-success" href="../daily_plan/tabla_im.php">Regresar a la tabla Import</a>
            <hr>
            <div class="form-group">
                <label for="pedidos_en_proceso">Nueva cantidad de contenedores recibidos (modificar solo de ser necesario).</label>
                <input type="number" name="pedidos_en_proceso" id="pedidos_en_proceso" class="form-control" placeholder="Anterior cantidad de contenedores recibidos: <?= escapar($import['pedidos_en_proceso']) ?>" value="<?= escapar($import['pedidos_en_proceso']) ?>">
              </div>
              <div class="form-group">
                <label for="pedidos_despachados">Contenedores ya despachados</label>
                <input type="number" name="pedidos_despachados" id="pedidos_despachados" class="form-control" placeholder="Anterior cantidad de contenedores ya cerrados: <?= escapar($import['pedidos_despachados']) ?>" value="<?= escapar($import['pedidos_despachados']) ?>">
              </div>
              <div class="form-group">
                <label for="vehiculo">Vehículo</label>
                <input type="text" name="vehiculo" id="vehiculo" class="form-control" placeholder=" Anterior vehículo: <?= escapar($import['vehiculo']) ?>" value="<?= escapar($import['vehiculo']) ?>">
              </div>
              <div class="form-group">
                <label for="t_vehiculo">Tipo de vehículo</label>
                <input type="text" name="t_vehiculo" id="t_vehiculo" class="form-control" placeholder="Anterior tipo de vehículo: <?= escapar($import['t_vehiculo']) ?>" value="<?= escapar($import['t_vehiculo']) ?>">
              </div>
              <div class="form-group">
                <label for="bl">BL</label>
                <input type="text" name="bl" id="bl" class="form-control" placeholder="Anterior BL: <?= escapar($import['bl']) ?>" value="<?= escapar($import['bl']) ?>">
              </div>
              <div class="form-group">
                <label for="destino">Destino</label>
                <input type="text" name="destino" id="destino" class="form-control" placeholder="Anterior destino: <?= escapar($import['destino']) ?>" value="<?= escapar($import['destino']) ?>">
              </div>
              <div class="form-group">
                <label for="fecha_objetivo">Fecha Estimada de llegada</label>
                <input type="date" name="fecha_objetivo" id="fecha_objetivo" class="form-control" placeholder="Anterior fecha objetivo: <?= escapar($import['fecha_objetivo']) ?>" value="<?= escapar($import['fecha_objetivo']) ?>">
              </div>
              <div class="form-group">
                <label for="fecha_lleg_rampa">Llegada a rampa</label>
                <input type="date" name="fecha_lleg_rampa" id="fecha_lleg_rampa" class="form-control" placeholder="Anterior fecha objetivo: <?= escapar($import['fecha_lleg_rampa']) ?>" value="<?= escapar($import['fecha_lleg_rampa']) ?>">
              </div>
              <div class="form-group">
                <label for="fecha_sal_rampa">Salida de rampa</label>
                <input type="date" name="fecha_sal_rampa" id="fecha_sal_rampa" class="form-control" placeholder="Anterior fecha objetivo: <?= escapar($import['fecha_sal_rampa']) ?>" value="<?= escapar($import['fecha_sal_rampa']) ?>">
              </div>
              <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
              </div>
          </div>
        </div>
      </div>
      </form>
      <?php
    }
    ?>
  </body>
  <script src="../host_virtual_TI/js/script.js"></script>
</html>
