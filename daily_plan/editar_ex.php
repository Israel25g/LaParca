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
          ];

        $consultaSQL = "UPDATE export SET
          pedidos_en_proceso = :pedidos_en_proceso,
          pedidos_despachados = :pedidos_despachados,
          vehiculo = :vehiculo,
          t_vehiculo = :t_vehiculo,
          bl = :bl,
          destino = :destino
          WHERE id = :id";


          $consulta = $conexion->prepare($consultaSQL);
          $consulta->execute($datos);

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
            <div class="alert alert-success" role="alert" style="margin-top: 150px;position: fixed;margin-left: 100px ">
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
      <form method="POST">
      <div class="container" style="margin-top: 0%;">
        <div class="row">
          <div class="col-md-12">
            <h2 class="mt-4">Editando el campo  #<?= escapar($export['id'])?> de la tabla Export, del cliente <?= escapar($export['cliente'])?>.</h2>
            <a class="btn btn-success" href="../daily_plan/tabla_ex.php">Regresar a la tabla Export</a>
            <hr>
            <div class="form-group">
                <label for="pedidos_en_proceso">Nueva cantidad de pedidos en proceso (modificar solo de ser necesario).</label>
                <textarea type="numbre" name="pedidos_en_proceso" id="pedidos_en_proceso" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos en proceso: <?= escapar($export['pedidos_en_proceso']) ?>" > <?= escapar($export['pedidos_en_proceso']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="pedidos_despachados">Pedidos ya despachados</label>
                <textarea type="number" name="pedidos_despachados" id="pedidos_despachados" rows="1" class="form-control" placeholder="Anterior cantidad de pedidos despachados: <?= escapar($export['pedidos_despachados']) ?>" > <?= escapar($export['pedidos_despachados']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="vehiculo">vehículo</label>
                <textarea type="text" name="vehiculo" id="vehiculo" rows="1" class="form-control" placeholder=" Anterior vehículo: <?= escapar($export['vehiculo']) ?>" > <?= escapar($export['vehiculo']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="t_vehiculo">Tipo de vehículo</label>
                <textarea type="text" name="t_vehiculo" id="t_vehiculo" rows="1" class="form-control" placeholder="Anterior tipo de vehículo: <?= escapar($export['t_vehiculo']) ?>" > <?= escapar($export['t_vehiculo']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="bl">BL</label>
                <textarea type="text" name="bl" id="bl" rows="1" class="form-control" placeholder="Anterior BL: <?= escapar($export['bl']) ?>" > <?= escapar($export['bl']) ?></textarea>
              </div>
              <div class="form-group">
                <label for="destino">Destino</label>
                <textarea type="text" name="destino" id="destino" rows="1" class="form-control" placeholder="Anterior destino: <?= escapar($export['destino']) ?>" > <?= escapar($export['destino']) ?></textarea>
              </div>
              <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" id="submit" value="Editar">
              </div>
            </form>
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
