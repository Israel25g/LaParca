<?php
include("../../apertura_sesion.php");
include '../funcionalidades/funciones.php';
$config = include '../funcionalidades/config_DP.php';
$error = false;

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
    $mostrarTodo = isset($_GET['mostrar_todo']);

    // Filtro de fecha estimada de llegada
    $fechaEstimacionLlegada = isset($_GET['fecha_estimacion_llegada']) ? $_GET['fecha_estimacion_llegada'] : '';

    // Condiciones adicionales
    $condiciones = [];

    // Condición para mostrar solo los registros con division_dp < 1.00
    if (!$mostrarTodo) {
        $condiciones[] = "division_dp < 1.00";
    }

    // Condición para fecha estimada de llegada
    if ($fechaEstimacionLlegada) {
        $condiciones[] = "fecha_objetivo = :fecha_objetivo";
    }

    // Construye la consulta SQL según el filtro y condiciones
    if ($filtro == 'picking') {
        $consultaSQL = "SELECT * FROM picking";
    if (!empty($condiciones)) {
            $consultaSQL .= " WHERE " . implode(" AND ", $condiciones);
        }
        $encabezado = ["#", "OID", "Cliente", "Unidades por pickear", "Paletas", "Unidades pickeadas", "Cajas", "Fecha de requerido", "Prioridad de picking", "Porcentaje de cumplimiento", "Acciones"];
    } elseif ($filtro == 'export') {
        $consultaSQL = "SELECT * FROM export";
        if (!empty($condiciones)) {
            $consultaSQL .= " WHERE " . implode(" AND ", $condiciones);
        }
        $encabezado = ["#", "OID", "Cliente", "# Vehículo / Placa", "Pedidos en proceso", "Pedidos despachados", "Fecha estimada de salida", "Fecha de programacion", "Llegada a rampa", "Salida de rampa", "Acciones"];
    } elseif ($filtro == 'import') {
        $consultaSQL = "SELECT * FROM import";
        if (!empty($condiciones)) {
            $consultaSQL .= " WHERE " . implode(" AND ", $condiciones);
        }
        $encabezado = ["#", "AID", "Cliente", "Contenedor a recibir", "Contenedor recibido", "Tipo de carga", "Paletas","Cajas","Unidades", "Fecha estimada de llegada","Fecha programada", "Llegada a rampa", "Salida de rampa", "Acciones"];
    } else {
        $consultaSQL = "SELECT * FROM import WHERE 1 = 0";
        $mensaje = "Seleccione un tipo de operación";
    }

    // Preparar y ejecutar la consulta
    $sentencia = $conexion->prepare($consultaSQL);

    // Asignación del valor para el filtro de fecha
    if ($fechaEstimacionLlegada) {
        $sentencia->bindValue(':fecha_objetivo', $fechaEstimacionLlegada, PDO::PARAM_STR);
    }

    $sentencia->execute();
    $datos = $sentencia->fetchAll();
} catch (PDOException $error) {
    $error = $error->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($filtro == 'import'): ?>
        <title>Daily Plan - Import</title>
        <?php elseif ($filtro == 'export'): ?>
            <title>Daily Plan - Export</title>
        <?php elseif ($filtro == 'picking'): ?>
            <title>Daily Plan - Picking</title>
        <?php elseif ($filtro == NULL): ?>
            <title>Daily Plan - Operaciones</title>
        <?php endif; ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <link rel="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel=" https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  
    <link rel="stylesheet" href="../../main-global.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>
<body background="../../images/Motivo2.png">
<div class="container-lg start-0" style="margin-top: 90px;">

    <!-- Header -->
        <div class="header-error">
        <div class="logo-container">
          <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/Salida2.gif" alt="Logo_IPL_Group"></a>
        </div>
        <h1><a href="../index_DP.php">Daily Plan</a></h1>
        <div class="cuadroFecha">
          <p id="fecha-actual" style="font-size: small;"></p>
          <p id="hora-actual"></p>
        </div>
      </div>

  <div class="container" style="margin-left:-200px">
    <!-- Filtro para la consulta -->
    <?php if ($filtro == 'import'): ?>
      <h2 class="mt-4 nombre-tabla"><a href="../index_DP.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Import</h2>
        <?php elseif ($filtro == 'export'): ?>
          <h2 class="mt-4 nombre-tabla"><a href="../index_DP.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Export</h2>
        <?php elseif ($filtro == 'picking'): ?>
          <h2 class="mt-4 nombre-tabla"><a href="../index_DP.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Picking</h2>
        <?php elseif ($filtro == NULL): ?>
            <h2 class="mt-4 nombre-tabla"><a href="../index_DP.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Import</h2>
        <?php endif; ?>
    

        <div class="container mt-3" style="margin-left: -25px;">
        <form method="GET" class="p-2 mb-2 bg-light rounded-3 border">
            <div class="row align-items-center g-2">
                
              
              <!-- Menú desplegable para elegir operación -->
              <div class="col-auto">
                <label for="filtro" class="form-label mb-0 small">Operación</label>
                <div class="dropdown">
                  <button class="btn btn-secondary btn-sm dropdown-toggle w-100" type="button" 
                  id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <?php echo($filtro)?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <li>
                    <a class="dropdown-item" href="#" onclick="document.getElementById('filtro').value='import'; this.closest('form').submit(); return false;">
                                    Import
                                  </a>
                                </li>
                                <li>
                                <a class="dropdown-item" href="#" onclick="document.getElementById('filtro').value='export'; this.closest('form').submit(); return false;">
                                  Export
                                </a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="#" onclick="document.getElementById('filtro').value='picking'; this.closest('form').submit(); return false;">
                                Picking
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      
                      <!-- Filtro de fecha para fecha estimada de llegada -->
                      <div class="col-auto">
                          <label for="fecha_estimacion_llegada" class="form-label mb-0 small">Fecha programada</label>
                          <input type="date" name="fecha_estimacion_llegada" id="fecha_estimacion_llegada" 
                                 class="form-control form-control-sm" 
                                 value="<?= isset($_GET['fecha_estimacion_llegada']) ? $_GET['fecha_estimacion_llegada'] : '' ?>">
                      </div>
                      
                      <!-- Checkbox para mostrar toda la tabla -->
                      <div class="col-auto">
                        <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="mostrar_todo" id="mostrar_todo" <?= isset($_GET['mostrar_todo']) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="mostrar_todo">Mostrar operaciones completadas</label>
                      </div>
                </div>

                <!-- Botón de filtro -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm mt-3">Filtrar</button>
                  </div>
                  
                  <div class=" col-auto d-flex" style="height: 70px;">
                    <div class="vr"></div>
                  </div>

                <div class="col-auto">
                  <div class="form-check mt-4">
                      <?php if ($filtro == 'import'): ?>
                  <button class="btn btn-info ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../formulario_im.php">Ingresar datos de Import</button>
                    <ul class="dropdown-menu bg-info">
                      <li><a class="dropdown-item bg-info " href="./formulario_m.php?filtro=import">Formulario singular</a></li>
                      <li><a class="dropdown-item bg-info " href="./hoja_m/hoja_m.php?filtro=import">Hoja de datos</a></li>
                    </ul>
                  <a class="btn btn-primary ingreso-data" style="margin-bottom: 10px;"mb-5 href="../grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
                  <br/>
                <?php elseif ($filtro == 'export'): ?>
                  <button class="btn btn-danger ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../formulario_ex.php">Ingresar datos de Export</button>
                    <ul class="dropdown-menu bg-danger">
                      <li><a class="dropdown-item bg-danger text-light " href="./formulario_m.php?filtro=export">Formulario singular</a></li>
                      <li><a class="dropdown-item bg-danger text-light" href="./hoja_m/hoja_m.php?filtro=export">Hoja de datos</a></li>
                    </ul>
                  <a class="btn btn-primary ingreso-data" style="margin-bottom: 10px;"mb-5 href="../grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
                  <br/>
                <?php elseif ($filtro == 'picking'): ?>
                  <button class="btn btn-warning ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../formulario_pk.php">Ingresar datos de Picking</button>
                    <ul class="dropdown-menu bg-warning">
                      <li><a class="dropdown-item bg-warning " href="./formulario_m.php?filtro=picking">Formulario singular</a></li>
                      <li><a class="dropdown-item bg-warning " href="./hoja_m/hoja_m.php?filtro=picking">Hoja de datos</a></li>
                    </ul>
                  <a class="btn btn-primary ingreso-data" style="margin-bottom: 10px;"mb-5 href="../grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
                  <br/>
                <?php endif; ?>

              <?php if ($error): ?>
                  <div class="alert alert-danger"><?= $error ?></div>
              <?php endif; ?>
                </div>
                </div>
                
                <!-- Campo oculto para almacenar el valor seleccionado -->
                <input type="hidden" name="filtro" id="filtro" value="<?= isset($_GET['filtro']) ? $_GET['filtro'] : '' ?>">
            </div>
        </form>
    </div>

</div>
<!-- Tabla 'datos' -->
<div class="tabla-container">
    <!-- Tabla de datos -->
    <table id="tabla_MOP" class="display table">
        <thead>
            <tr>
                <?php if ($datos && $sentencia->rowCount() > 0): ?>
                    <?php foreach ($encabezado as $titulo): ?>
                        <?php 
                            $bgClass = $filtro === 'import' ? 'text-bg-info' : ($filtro === 'export' ? 'text-bg-danger' : 'text-bg-warning');
                        ?>
                        <th class="border end <?= $bgClass ?>"><?= $titulo ?></th>
                    <?php endforeach; ?>
                <?php else: ?>
                    <th colspan="1" class="border end text-center text-bg-secondary">No se encontraron resultados</th>
                <?php endif; ?>
            </tr>
        </thead>
        
        <tbody>
            <?php if ($datos && $sentencia->rowCount() > 0): ?>
                <?php foreach ($datos as $fila): ?>
                    <?php $modalId = "modal_" . $filtro . "_" . $fila['id']; // ID único para cada modal ?>
                    <tr>
                        <?php if ($filtro == 'picking'): ?>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['id'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['aid_oid'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['cliente'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['paletas'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['cajas'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['eta_etd'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['vacio_lleno'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['division_dp'] * 100 ?>%</td>
                            <td class="border end">
                                <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=picking"><i class="bi bi-envelope-fill"></i></a>
                                <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= './borrar_m.php?filtro=picking&id=' . escapar($fila["id"]) ?>"></a>
                            </td>
                        <?php elseif ($filtro == 'export'): ?>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['id'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['aid_oid'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['cliente'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['vehiculo'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['eta_etd'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_objetivo'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_lleg_rampa'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_sal_rampa'] ?></td>
                            <td class="border end">
                                <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=export"><i class="bi bi-envelope-fill"></i></a>
                                <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= './borrar_m.php?filtro=export&id=' . escapar($fila["id"]) ?>"></a>
                            </td>
                        <?php elseif ($filtro == 'import'): ?>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['id'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['aid_oid'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['cliente'] ?></td>
                            <!-- <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['vehiculo'] ?></td> -->
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['t_carga'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['paletas'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['cajas'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['unidades'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['eta_etd'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_objetivo'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_lleg_rampa'] ?></td>
                            <td class="border end" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"><?= $fila['fecha_sal_rampa'] ?></td>
                            <td class="border end">
                                <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=import"><i class="bi bi-envelope-fill"></i></a>
                                <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= './borrar_m.php?filtro=import&id=' . escapar($fila["id"]) ?>"></a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="1" class="text-center">No se encontraron resultados</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <?php if ($datos && $sentencia->rowCount() > 0): ?>
                    <?php foreach ($encabezado as $titulo): ?>
                        <?php 
                            $bgClass = $filtro === 'import' ?  : ($filtro === 'export' ?  : '');
                        ?>
                        <th class="border end <?= $bgClass ?>"><?= $titulo ?></th>
                    <?php endforeach; ?>
                <?php else: ?>
                    <th colspan="1" class="border end text-center text-bg-secondary">No se encontraron resultados</th>
                <?php endif; ?>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Modales individuales para cada fila -->
<?php foreach ($datos as $fila): ?>
    <?php $modalId = "modal_" . $filtro . "_" . $fila['id']; ?>
    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <?php if ($filtro == "import"): ?>
                <div class="modal-header bg bg-info" >
              <?php elseif ($filtro == "export"): ?>
                <div class="modal-header bg bg-danger" >
                <?php elseif ($filtro == "picking"): ?>
                    <div class="modal-header bg bg-warning" >
                  <?php else:?>
                    <div class="modal-header bg bg-secondary" >
                      <?php endif?>


                      <?php if ($filtro == "export"): ?>
                    <h5 class="modal-title text-light" id="modalTitleId" style="font-weight: 800;">
                    <?= $fila['cliente'] ?>
                      <?php else: ?>
                        <h5 class="modal-title " id="modalTitleId" style="font-weight: 800;">
                    <?= $fila['cliente'] ?>
                    <?php endif?>
                    </h5>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-row">
                                <?php if ($filtro == "import"):?>
                                  <div class="form-group col-md-6">
                                  <label for="cliente">AID</label>
                                <?php elseif($filtro == "export"): ?>
                                  <div class="form-group col-md-6">
                                  <label for="cliente">OID</label>
                                  <?php elseif($filtro == "picking"): ?>
                                  <div class="form-group col-md-12">
                                  <label for="cliente">OID</label>
                                <?php endif?>
                                <?php if ($filtro == "picking"):?>
                                  <input type="text" name="vehiculo" id="vehiculo" class="form-control" value="<?= escapar($fila['aid_oid']) ?>" readonly>
                                  </div>
                                  <?php else:?>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" value="<?= escapar($fila['aid_oid']) ?>" readonly>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="vehiculo">Vehículo/Placa</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" value="<?= $fila['vehiculo'] ?>" readonly>
                            </div>
                            
                            <div class="form-group col-md-4">
                              <label for="t_vehiculo">Tipo de vehiculo</label>
                              <input type="text" name="t_vehiculo" id="t_vehiculo" class="form-control" value="<?= $fila['t_vehiculo'] ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="bl">HBL<br/> </label>
                              <br/><input type="text" name="bl" id="bl" class="form-control" value="<?= $fila['bl'] ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="destino">Origen<br/> </label>
                              <br/><input type="text" name="destino" id="destino" class="form-control" value="<?= $fila['destino'] ?>" readonly>
                            </div>
                            <?php endif?><?php if ($filtro == "picking"):?>
                            <div class="form-group col-md-4">
                                <label for="unidades">unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control" value="<?= $fila['pedidos_en_proceso'] ?>" readonly>
                            </div>
                            <?php else:?>
                            <div class="form-group col-md-4">
                                <label for="unidades">unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control" value="<?= $fila['unidades'] ?>" readonly>
                            </div>
                                <?php endif?>
                            <div class="form-group col-md-4">
                                <label for="cajas">cajas</label>
                                <input type="text" name="cajas" id="cajas" class="form-control" value="<?= $fila['cajas'] ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="paletas">paletas</label>
                                <input type="text" name="paletras" id="paletas" class="form-control" value="<?= $fila['paletas'] ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                    <label for="fecha">fecha de creacion</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" value="<?= $fila['created_at'] ?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                    <label for="fecha">fecha de actualizacion</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" value="<?= $fila['updated_at'] ?>" readonly>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_oficina">Comentario de oficina</label>
                                <textarea type="text" name="comentario_oficina" id="comentario_oficina" class="form-control" value="<?= $fila['comentario_oficina'] ?>" readonly><?= $fila['comentario_oficina'] ?></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="comentario_bodega">Comentario de bodega</label>
                                <textarea type="text" name="comentario_bodega" id="comentario_bodega" class="form-control" value="<?= $fila['comentario_bodega'] ?>" readonly><?= $fila['comentario_bodega'] ?></textarea>
                            </div>
                            
                            <div class="form-group col-md-4">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

    
    <script>
      var modalId = document.getElementById('modalId');
    
      modalId.addEventListener('show.bs.modal', function (event) {
          // Button that triggered the modal
          let button = event.relatedTarget;
          // Extract info from data-bs-* attributes
          let recipient = button.getAttribute('data-bs-whatever');
    
        // Use above variables to manipulate the DOM
      });
    </script>

    <?php include '../../daily_plan/datatable.php'?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../host_virtual_TI/js/script.js"></script>

    <script>
  $(document).ready(function() {
    let table = new DataTable('#tabla_MOP', {
      paging: false,
      scrollCollapse: false,
      scrollY: '350px',
      scrollX: '1700px',
      deferRender:    true,
      scroller:       true,
      
      initComplete: function() {
        this.api()
          .columns()
          .every(function() {
            let column = this;
            let title = column.footer().textContent;

            // Crear input para filtrar cada columna
            let input = document.createElement('input');
            input.placeholder = title;
            column.footer().replaceChildren(input);

            // Evento para filtrar la columna con el input
            input.addEventListener('keyup', () => {
              if (column.search() !== this.value) {
                column.search(input.value).draw();
              }
            });
          });
      },

      // Configuración de botones y layout para visibilidad y exportación
      buttons: [
        'colvis',
        {
          extend: 'copy',
          text: '<button class="btn btn-secondary"><i class="bi bi-copy"> Copiar</i></button>',
          exportOptions: {
            columns: ':visible'  
          }
        },
        {
          extend: 'csv',
          text: '<button class="btn btn-secondary"> <i class="bi bi-filetype-csv"> CSV</i></button>',
          exportOptions: {
            columns: ':visible'
          }
        },
        {
          extend: 'excel',
          text: '<button class="btn btn-secondary"> <i class="bi bi-file-earmark-excel"> Excel</i></button>',
          exportOptions: {
            columns: ':visible'
          }
        },
        {
          extend: 'pdf',
          text: '<button class="btn btn-secondary"><i class="bi bi-file-earmark-pdf"> PDF </i></button>',
          exportOptions: {
            columns: ':visible'
          }
        },
        {
          extend: 'print',
          text: '<button class="btn btn-secondary"><i class="bi bi-printer-fill"> Imprimir</i></button>',
          exportOptions: {
            columns: ':visible'
          }
        }
      ],
      dom: 'Bfrtip',  // Posición de botones y opciones en la interfaz
      info: false,

      // Traducción al español
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        buttons: {
          copy: "Copiar",
          csv: "CSV",
          excel: "Excel",
          pdf: "PDF",
          print: "Imprimir",
          colvis: '<button class="btn btn-secondary"> <i class="bi bi-shadows"> Mostrar/Ocultar</i></button>'
        }
      }
    });
  });
</script>



</body>
</html>