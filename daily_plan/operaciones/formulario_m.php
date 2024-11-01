<?php
include '../../daily_plan/funcionalidades/funciones.php';
$config = include '../../daily_plan/funcionalidades/config_DP.php';
$error= false;

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    // Consulta para obtener los clientes desde la tabla "clientes" al cargar la página
    $sql = "SELECT id, nombre_cliente FROM clientes";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Guardar los resultados en un array

} catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = "Error al conectar a la base de datos: " . $error->getMessage();
}

$operacion = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

if (isset($_POST['submit'])) {
    $resultado = [
        'error' => false,
        'mensaje' => 'La planificación de ' . $_POST['cliente'] . ' ha sido agregada con éxito'
    ];

    try {

      if ($operacion == 'import'){
        
        $import = array(
          "aid_oid" => $_POST['aid_oid'],
          "cliente" => $_POST['cliente'],
          "vehiculo" => $_POST['vehiculo'],
          "t_vehiculo" => $_POST['t_vehiculo'],
          "bl" => $_POST['bl'],
          "destino" => $_POST['destino'],
          "t_carga" => $_POST['t_carga'],
          "paletas" => $_POST['paletas'],
          "cajas" => $_POST['cajas'],
          "unidades" => $_POST['unidades'],
          "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
          "fecha_objetivo" => $_POST['fecha_objetivo'],
          "comentario_oficina" => $_POST['comentario_oficina']
      );

      // Inserción en la tabla import
      $consultaSQL = "INSERT INTO import (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
      $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";
      $sentencia = $conexion->prepare($consultaSQL);
      $sentencia->execute($import);

      // Inserción en la tabla import_r
      $consultaSQL = "INSERT INTO import_r (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
      $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";
      $sentencia = $conexion->prepare($consultaSQL);
      $sentencia->execute($import);
       } elseif ($operacion == 'export'){
        $datos = array(
          "aid_oid" => $_POST['aid_oid'],
          "cliente" => $_POST['cliente'],
          "vehiculo" => $_POST['vehiculo'],
          "t_vehiculo" => $_POST['t_vehiculo'],
          "bl" => $_POST['bl'],
          "destino" => $_POST['destino'],
          "t_carga" => $_POST['t_carga'],
          "paletas" => $_POST['paletas'],
          "cajas" => $_POST['cajas'],
          "unidades" => $_POST['unidades'],
          "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
          "fecha_objetivo" => $_POST['fecha_objetivo'],
          "comentario_oficina" => $_POST['comentario_oficina']
      );

      $consultaSQL = "INSERT INTO export (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
      $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :paletas, :t_carga, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

      $sentencia = $conexion->prepare($consultaSQL);
      $sentencia->execute($datos);

      $consultaSQL = "INSERT INTO export_r (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, t_carga, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
      $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :t_carga, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

      $sentencia = $conexion->prepare($consultaSQL);
      $sentencia->execute($datos);
        } elseif ($operacion == 'picking'){
          $datos = array(
            "aid_oid" => $_POST['aid_oid'],
            "cliente" => $_POST['cliente'],
            "t_carga" => $_POST['t_carga'],
            "paletas" => $_POST['paletas'],
            "cajas" => $_POST['cajas'],
            "vacio_lleno" => $_POST['vacio_lleno'],
            "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
            "fecha_objetivo" => $_POST['fecha_objetivo'],
            "comentario_oficina" => $_POST['comentario_oficina']

        );

        $consultaSQL = "INSERT INTO picking (aid_oid, cliente, t_carga, paletas, cajas, vacio_lleno, pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
        $consultaSQL .= "VALUES (:aid_oid, :cliente, :t_carga, :paletas, :cajas, :vacio_lleno, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($datos);

        $consultaSQL = "INSERT INTO picking_r (aid_oid, cliente, t_carga, paletas, cajas, vacio_lleno,  pedidos_en_proceso, fecha_objetivo, comentario_oficina) ";
        $consultaSQL .= "VALUES (:aid_oid, :cliente, :t_carga, :paletas, :cajas, :vacio_lleno, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina)";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($datos);
         } elseif ($operacion == NULL) {  }


    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = "Error al insertar los datos: " . $error->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exports - Daily Plan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../estilos.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>
<body style="background-image:url('../../images/Motivo2.png')!important;margin: 0;padding: 0; font-family:montserrat;">

            <!-- Header -->
            <div class="header">
            <div class="logo-container">
                <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
            </div>
            <h1>Daily plan</h1>
            <div class="cuadroFecha">
                <p id="fecha-actual"></p>
                <p id="hora-actual">prueba</p>
            </div>
        </div>
        <!-- Fin del Header -->


<div class="container" >
            <div class="row">
                <div class="col-md-12" style="margin-top: 100px;">
                              <?php if ($operacion == 'import'): ?>
                          <h3 class="mb-2"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=import"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Daily plan de Import</h3>
                          <a class="mb-2 btn btn-success btn-lg" href="../../daily_plan/operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=import" style="margin-top: 2%">Volver a la tabla</a>
                      <?php elseif ($operacion == 'export'): ?>
                        <h3 class="mb-2"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=export"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Daily plan de Export</h3>
                        <a class="mb-2 btn btn-success btn-lg" href="../../daily_plan/operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=export" style="margin-top: 2%">Volver a la tabla</a>
                      <?php elseif ($operacion == 'picking'): ?>
                        <h3 class="mb-2"><a href="./tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Daily plan de Picking</h3>
                        <a class="mb-2 btn btn-success btn-lg" href="../../daily_plan/operaciones/tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking" style="margin-top: 2%">Volver a la tabla</a>
                      <?php elseif ($operacion == NULL): ?>
                        <h3 class="mb-2"><a href="../../index.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>operacion nula regrese a la pantalla de inicio</h3>
                        <a class="mb-2 btn btn-success btn-lg" href="../../index.php" style="margin-top: 2%">Volver al inicio</a>
                      <?php endif; ?>

                    <?php
                    if (isset($resultado)) {
                    ?>
                        <div style="margin-left: 15px">
                            <div class="row">
                                <div>
                                    <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert" style="position: flex">
                                        <?= $resultado['mensaje'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <hr>
<div class="form-container">
    <?php if ($error): ?>
        <p style="color: red;">Error en la conexión: <?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <?php if ($operacion == 'import'): ?>
          <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="aid_oid">AID</label>
                                <input type="text" name="aid_oid" id="aid_oid" class="form-control" required>
                            </div>

                            <!-- Selector de Cliente con datos de la base de datos -->
                            <div class="form-group col-md-3">
                                <label for="cliente">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control" required>
                                    <option value="">Seleccione un cliente</option> <!-- Opción por defecto -->
                                    <?php
                                    // Recorrer los clientes y generar las opciones del selector
                                    foreach ($clientes as $cliente) {
                                        echo '<option value="' . $cliente['nombre_cliente'] . '">' . $cliente['nombre_cliente'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vehiculo">Vehículo/Placa</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_vehiculo">Tipo de Vehículo</label>
                                <select type="text" name="t_vehiculo" list="datalistOptions" id="t_vehiculo" class="form-control">
                                    <option value="">...</option>
                                    <option value="Contenedor 20">Contenedor 20</option>
                                    <option value="Contenedor 40">Contenedor 40</option>
                                    <option value="Contenedor 45">Contenedor 45</option>
                                    <option value="Camion">Camion</option>
                                    <option value="furgon">furgon</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="bl">HBL</label>
                                <input type="text" name="bl" id="bl" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="destino">Origen</label>
                                <input type="text" name="destino" id="destino" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_carga">Tipo de carga</label>
                                <select type="text" name="t_carga" id="t_carga" class="form-control">
                                <option value="">...</option>
                                    <option value="FCL">FCL</option>
                                    <option value="LCL">LCL</option>
                                    <option value="FTL">FTL</option>
                                    <option value="LTL">LTL</option>
                                    <option value="AIR">AIR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="paletas">Paletas</label>
                                <input type="number" name="paletas" id="paletas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cajas">Cajas</label>
                                <input type="number" name="cajas" id="cajas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="unidades">Unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pedidos_en_proceso">Contenedores</label>
                                <input type="number" name="pedidos_en_proceso" id="pedidos_en_proceso" class="form-control" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="fecha_objetivo">Fecha estimada de llegada</label>
                                <input type="date" id="fecha_objetivo" name="fecha_objetivo" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_oficina">Comentario Oficina</label>
                                <textarea name="comentario_oficina" id="comentario_oficina" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <!-- <div class="form-row">
        <div class="form-group col-md-3">
            <label for="vacio_lleno">Vacío o Lleno</label>
            <input type="text" name="vacio_lleno" id="vacio_lleno" class="form-control">
        </div>
    </div> -->
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                        </div>
                    </form>
        <?php elseif ($operacion == 'export'): ?>
          <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="aid_oid">OID</label>
                                <input type="text" name="aid_oid" id="aid_oid" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cliente">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control" required>
                                    <option value="">Seleccione un cliente</option> <!-- Opción por defecto -->
                                    <?php
                                    // Recorrer los clientes y generar las opciones del selector
                                    foreach ($clientes as $cliente) {
                                        echo '<option value="' . $cliente['nombre_cliente'] . '">' . $cliente['nombre_cliente'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vehiculo">Vehículo/Placa</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_vehiculo">Tipo de Vehículo</label>
                                <select type="text" name="t_vehiculo" list="datalistOptions" id="t_vehiculo" class="form-control">
                                    <option value="">...</option>
                                    <option value="Contenedor 20">Contenedor 20</option>
                                    <option value="Contenedor 40">Contenedor 40</option>
                                    <option value="Contenedor 45">Contenedor 45</option>
                                    <option value="Camion">Camion</option>
                                    <option value="furgon">furgon</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="bl">BL</label>
                                <input type="text" name="bl" id="bl" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="destino">Destino</label>
                                <input type="text" name="destino" id="destino" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_carga">Tipo de carga</label>
                                <select type="text" name="t_carga" id="t_carga" class="form-control">
                                <option value="">...</option>
                                    <option value="FCL">FCL</option>
                                    <option value="LCL">LCL</option>
                                    <option value="FTL">FTL</option>
                                    <option value="LTL">LTL</option>
                                    <option value="AIR">AIR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="paletas">Paletas</label>
                                <input type="number" name="paletas" id="paletas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cajas">Cajas</label>
                                <input type="number" name="cajas" id="cajas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="unidades">Unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pedidos_en_proceso">Pedidos por despachar</label>
                                <input type="number" name="pedidos_en_proceso" id="pedidos_en_proceso" class="form-control" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="fecha_objetivo">Fecha estimada de salida</label>
                                <input type="date" id="fecha_objetivo" name="fecha_objetivo" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_oficina">Comentario Oficina</label>
                                <textarea name="comentario_oficina" id="comentario_oficina" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                        </div>
                    </form>
        <?php elseif ($operacion == 'picking'): ?>
          <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="aid_oid">OID</label>
                                <input type="text" name="aid_oid" id="aid_oid" class="form-control" required>
                            </div>
                            <!-- Selector de Cliente con datos de la base de datos -->
                            <div class="form-group col-md-3">
                                <label for="cliente">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control" required>
                                    <option value="">Seleccione un cliente</option> <!-- Opción por defecto -->
                                    <?php
                                    // Recorrer los clientes y generar las opciones del selector
                                    foreach ($clientes as $cliente) {
                                        echo '<option value="' . $cliente['nombre_cliente'] . '">' . $cliente['nombre_cliente'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_carga">Tipo de carga</label>
                                <select type="text" name="t_carga" id="t_carga" class="form-control">
                                <option value="">...</option>
                                    <option value="FCL">FCL</option>
                                    <option value="LCL">LCL</option>
                                    <option value="FTL">FTL</option>
                                    <option value="LTL">LTL</option>
                                    <option value="AIR">AIR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vacio_lleno">Prioridad de picking</label>
                                <select type="text" name="vacio_lleno" id="vacio_lleno" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="paletas">Paletas</label>
                                <input type="number" name="paletas" id="paletas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cajas">Cajas</label>
                                <input type="number" name="cajas" id="cajas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pedidos_en_proceso">Unidades</label>
                                <input type="number" name="pedidos_en_proceso" id="pedidos_en_proceso" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="fecha_objetivo">Fecha de requerido</label>
                                <input type="date" id="fecha_objetivo" name="fecha_objetivo" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_oficina">Comentario Oficina</label>
                                <textarea name="comentario_oficina" id="comentario_oficina" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                </div>
                </form>
        <?php else: ?>
            <div><span>Operación no válida. Por favor, vuelva al inicio.</span></div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<script src="../../host_virtual_TI/js/script.js"></script>
</body>
</html>
