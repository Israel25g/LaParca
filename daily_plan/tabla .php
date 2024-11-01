
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Datos - Daily Plan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../estilos.css">
  </head>
  <body style="background-image:url('../host_virtual_TI/images/Motivo2.png');margin: 0;padding: 0;">
    <div style="margin-top: 90px;" >
      <!-- Header -->
      <div class="header">
          <div class="logo-container">
              <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/Salida2.gif" alt="Logo_IPL_Group" ></a>
          </div>
          <h1>Daily plan</h1>
          <div class="cuadroFecha">
              <p id="fecha-actual"></p>
              <p id="hora-actual">prueba</p>
          </div>
      </div>
      <!-- Fin del Header -->

      <!-- Navbar -->
      
      <div class="container-nav" style="margin-top: -120px; margin-left: 33% ;position:fixed; z-index: 999">
          <div class="navbarr">
              <ul class="nav" id="detallesOps">
                  <li class="nav-li"><a href="../helpdesk.html">Mesa de Ayuda (Tickets)</a></li>
                  <li class="nav-li"><a href="../daily_plan/grafico.php">graficas</a></li>
                  <li class="nav-li"><a href="../daily_plan/tabla .php">Daily Plan</a></li>
                  <li class="nav-li"><a href="#">Dashboards</a></li>
                  <!-- <li class="nav-li"><a class="cierre" href="../CerrarSesion.php">Cerrar Sesión</a></li> -->
              </ul>
          </div>
      </div>
      <?php
      session_start();
      include '../daily_plan/funcionalidades/funciones.php';
      $error = false;
      $config = include '../daily_plan/funcionalidades/config_DP.php';

      try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $consultaSQL = "SELECT * FROM datos";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute();

        $datos = $sentencia->fetchAll();

      } catch(PDOException $error) {
        $error= $error->getMessage();
      }
      ?>
      <?php
      if ($error) {
        ?>
        <div class="container mt-2">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                <?= $error ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
      <div id="carouselExampleFade" class="carousel slide carousel-fade" style="margin-top:200px">
        <div class="carousel-inner">
          <div class="carousel-item active">
        
            <div class="container">
              <div class="row">
              <div class="col-md-12">
                <h2 class="mt-3" >Export</h2>
                <a class="btn btn-success" href="../daily_plan/formulario_ex.php">Ingresar datos de Export</a>
                <table class="table shadow p-3 mb-5 bg-body-tertiary rounded table-striped" style="--bs-border-opacity: .5;">
                  <thead>
                  <tr>
                      <th class="border end">ID</th>
                      <th class="border end">Cliente</th>
                      <th class="border end">Meta despacho</th>
                      <th class="border end">Listo para despachar</th>
                      <th class="border end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($datos && $sentencia->rowCount() > 0) {
                      foreach ($datos as $fila) {
                        ?>
                        <tr>
                          <td class="border end"><?php echo escapar($fila["id"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["meta_despacho"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["listo_para_despachar"]); ?></td>
                          <td class="border end">  
                      <a class="btn btn-outline-warning fs-1 border end"  href="<?='responder_ti.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
                      <a class="btn btn-outline-danger fs-1 border end bi bi-trash3-fill"  href="<?='./funcionalidades/borrar.php?id=' . escapar($fila["id"]) ?>"></i></a>
                      </td>
                      </tr>
                        <?php
                      }
                    }
                    ?>
                  <tbody>
                </table>
              </div>
            </div>
          </div>

          </div>
          <div class="carousel-item">

          <div class="container">
              <div class="row">
              <div class="col-md-12">
                <h2 class="mt-3">Import</h2>
                <a class="btn btn-success" href="../daily_plan/formulario_ex.php">Ingresar datos de import</a>
                <table class="table shadow p-3 mb-5 bg-body-tertiary rounded table-striped" style="--bs-border-opacity: .5;">
                  <thead>
                  <tr>
                      <th class="border end">ID</th>
                      <th class="border end">Cliente</th>
                      <th class="border end">Meta despacho</th>
                      <th class="border end">Listo para despachar</th>
                      <th class="border end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($datos && $sentencia->rowCount() > 0) {
                      foreach ($datos as $fila) {
                        ?>
                        <tr>
                          <td class="border end"><?php echo escapar($fila["id"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["meta_despacho"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["listo_para_despachar"]); ?></td>
                          <td class="border end">  
                      <a type="button" class="btn btn-outline-danger fs-1 border end" data-toggle="modal" data-target="#login-<?= $fila["id"] ?>"><i class="bi bi-trash3-fill"></i></a>
                      <a class="btn btn-outline-warning fs-1 border end"  href="<?='responder_ti.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
                      </td>
                      </tr>
                        <?php
                      }
                    }
                    ?>
                  <tbody>
                </table>
              </div>
            </div>
          </div>
        
          </div>
          <div class="carousel-item">
        
          <div class="container">
              <div class="row">
              <div class="col-md-12">
                <h2 class="mt-3">Picking</h2>
                <a class="btn btn-success" href="../daily_plan/formulario_ex.php">Ingresar datos de picking</a>
                <table class="table shadow p-3 mb-5 bg-body-tertiary rounded table-striped" style="--bs-border-opacity: .5;">
                  <thead>
                  <tr>
                      <th class="border end">ID</th>
                      <th class="border end">Cliente</th>
                      <th class="border end">Meta despacho</th>
                      <th class="border end">Listo para despachar</th>
                      <th class="border end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($datos && $sentencia->rowCount() > 0) {
                      foreach ($datos as $fila) {
                        ?>
                        <tr>
                          <td class="border end"><?php echo escapar($fila["id"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["meta_despacho"]); ?></td>
                          <td class="border end"><?php echo escapar($fila["listo_para_despachar"]); ?></td>
                          <td class="border end">  
                      <a type="button" class="btn btn-outline-danger fs-1 border end" data-toggle="modal" data-target="#login-<?= $fila["id"] ?>"><i class="bi bi-trash3-fill"></i></a>
                      <a class="btn btn-outline-warning fs-1 border end"  href="<?='responder_ti.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
                      </td>
                      </tr>
                        <?php
                      }
                    }
                    ?>
                  <tbody>
                </table>
              </div>
            </div>
          </div>

          </div>
        </div>
        <button class="carousel-control-prev text-warning bg-dark" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev"  style="margin-left:-50px;">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next text-warning bg-dark" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next" style="margin-right:-50px;">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script src="../host_virtual_TI/js/script.js"></script>
  </body>
</html>


