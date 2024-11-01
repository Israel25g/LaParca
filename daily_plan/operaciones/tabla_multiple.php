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
        $encabezado = ["#", "OID", "Cliente", "# Vehículo / Placa", "Pedidos en proceso", "Pedidos despachados", "Fecha estimada de salida", "Llegada a rampa", "Salida de rampa", "Acciones"];
    } elseif ($filtro == 'import') {
        $consultaSQL = "SELECT * FROM import";
        if (!empty($condiciones)) {
            $consultaSQL .= " WHERE " . implode(" AND ", $condiciones);
        }
        $encabezado = ["#", "AID", "Cliente", "Vehículo / Placa", "Contenedor a recibir", "Contenedor recibido", "Tipo de carga", "Paletas", "Cajas", "Unidades", "Fecha estimada de llegada", "Llegada a rampa", "Salida de rampa", "Acciones"];
    } else {
        $consultaSQL = " NULL ";
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
<div class="container-lg start-0" style="margin-top: 150px;">

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
                          <label for="fecha_estimacion_llegada" class="form-label mb-0 small">Fecha de Llegada</label>
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
                      <li><a class="dropdown-item bg-info " href="../hoja_im/hoja_im.php">Hoja de datos</a></li>
                    </ul>
                  <a class="btn btn-primary ingreso-data" style="margin-bottom: 10px;"mb-5 href="../grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
                  <br/>
                <?php elseif ($filtro == 'export'): ?>
                  <button class="btn btn-danger ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../formulario_ex.php">Ingresar datos de Export</button>
                    <ul class="dropdown-menu bg-danger">
                      <li><a class="dropdown-item bg-danger text-light " href="./formulario_m.php?filtro=export">Formulario singular</a></li>
                      <li><a class="dropdown-item bg-danger text-light" href="../hoja_ex/hoja_ex.php">Hoja de datos</a></li>
                    </ul>
                  <a class="btn btn-primary ingreso-data" style="margin-bottom: 10px;"mb-5 href="../grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
                  <br/>
                <?php elseif ($filtro == 'picking'): ?>
                  <button class="btn btn-warning ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../formulario_pk.php">Ingresar datos de Picking</button>
                    <ul class="dropdown-menu bg-warning">
                      <li><a class="dropdown-item bg-warning " href="./formulario_m.php?filtro=picking">Formulario singular</a></li>
                      <li><a class="dropdown-item bg-warning " href="../hoja_pk/hoja_pk.php">Hoja de datos</a></li>
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
      <!-- botones de acceso a formularios y tablas -->
       
          

      <table id="tabla_MOP" class="display table">
    <thead>
        <tr>
            <?php if ($datos && $sentencia->rowCount() > 0): ?>
                <?php if ($filtro == 'import'): ?>
                    <?php foreach ($encabezado as $titulo): ?>
                        <th class="border end text-bg-info"><?= $titulo ?></th>
                    <?php endforeach; ?>
                <?php elseif ($filtro == 'export'): ?>
                    <?php foreach ($encabezado as $titulo): ?>
                        <th class="border end text-bg-danger"><?= $titulo ?></th>
                    <?php endforeach; ?>
                <?php elseif ($filtro == 'picking'): ?>
                    <?php foreach ($encabezado as $titulo): ?>
                        <th class="border end text-bg-warning"><?= $titulo ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
                <!-- Si no hay datos, se muestra solo una cabecera que abarca toda la fila -->
                <th colspan="1" class="border end text-center text-bg-secondary">No se encontraron resultados</th>
            <?php endif; ?>
        </tr>
    </thead>
    
    <tbody>
        <?php if ($datos && $sentencia->rowCount() > 0): ?>
            <?php foreach ($datos as $fila): ?>
                <tr>
                    <?php if ($filtro == 'picking'): ?>
                        <td class="border end"><?= $fila['id'] ?></td>
                        <td class="border end"><?= $fila['aid_oid'] ?></td>
                        <td class="border end"><?= $fila['cliente'] ?></td>
                        <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                        <td class="border end"><?= $fila['paletas'] ?></td>
                        <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                        <td class="border end"><?= $fila['cajas'] ?></td>
                        <td class="border end"><?= $fila['fecha_objetivo'] ?></td>
                        <td class="border end"><?= $fila['vacio_lleno'] ?></td>
                        <td class="border end"><?= $fila['division_dp'] * 100 ?>%</td>
                        <td class="border end">
                            <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=picking"><i class="bi bi-envelope-fill"></i></a>
                            <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= '../funcionalidades/borrar_pk.php?id=' . escapar($fila["id"]) ?>"></a>
                        </td>
                    <?php elseif ($filtro == 'export'): ?>
                        <td class="border end"><?= $fila['id'] ?></td>
                        <td class="border end"><?= $fila['aid_oid'] ?></td>
                        <td class="border end"><?= $fila['cliente'] ?></td>
                        <td class="border end"><?= $fila['vehiculo'] ?></td>
                        <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                        <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                        <td class="border end"><?= $fila['fecha_objetivo'] ?></td>
                        <td class="border end"><?= $fila['fecha_lleg_rampa'] ?></td>
                        <td class="border end"><?= $fila['fecha_sal_rampa'] ?></td>
                        <td class="border end">
                            <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=export"><i class="bi bi-envelope-fill"></i></a>
                            <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= '../funcionalidades/borrar_ex.php?id=' . escapar($fila["id"]) ?>"></a>
                        </td>
                    <?php elseif ($filtro == 'import'): ?>
                        <td class="border end"><?= $fila['id'] ?></td>
                        <td class="border end"><?= $fila['aid_oid'] ?></td>
                        <td class="border end"><?= $fila['cliente'] ?></td>
                        <td class="border end"><?= $fila['vehiculo'] ?></td>
                        <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                        <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                        <td class="border end"><?= $fila['t_carga'] ?></td>
                        <td class="border end"><?= $fila['paletas'] ?></td>
                        <td class="border end"><?= $fila['cajas'] ?></td>
                        <td class="border end"><?= $fila['unidades'] ?></td>
                        <td class="border end"><?= $fila['fecha_objetivo'] ?></td>
                        <td class="border end"><?= $fila['fecha_lleg_rampa'] ?></td>
                        <td class="border end"><?= $fila['fecha_sal_rampa'] ?></td>
                        <td class="border end">
                            <a class="btn btn-outline-warning fs-6 border end" href="<?='./editar_m.php?id='.escapar($fila["id"])?>&type=import"><i class="bi bi-envelope-fill"></i></a>
                            <a class="btn btn-outline-danger fs-6 border end bi bi-trash3-fill" href="<?= '../funcionalidades/borrar_im.php?id=' . escapar($fila["id"]) ?>"></a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Si no hay datos, muestra una fila única con el mensaje -->
            <tr>
                <td colspan="1" class="text-center">No se encontraron resultados</td>
            </tr>
        <?php endif; ?>
    </tbody>

    <tfoot>
        <tr>
            <?php if ($datos && $sentencia->rowCount() > 0): ?>
                <?php foreach ($encabezado as $titulo): ?>
                    <th class="border end"><?= $titulo ?></th>
                <?php endforeach; ?>
            <?php else: ?>
                <th colspan="1" class="border end text-center text-bg-secondary">No se encontraron resultados</th>
            <?php endif; ?>
        </tr>
    </tfoot>
</table>

    </div>
<?php include '../../daily_plan/datatable.php'?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../host_virtual_TI/js/script.js"></script>
    <script>
      $(document).ready(function() {
        new DataTable('#tabla_MOP', {
          paging: false,
          scrollCollapse: false,
          scrollY: '350px',
          scrollX: '1700px',

          initComplete: function() {
            this.api()
              .columns()
              .every(function() {
                let column = this;
                let title = column.footer().textContent;

                // Create input element
                let input = document.createElement('input');
                input.placeholder = title;
                column.footer().replaceChildren(input);

                // Event listener for user input
                input.addEventListener('keyup', () => {
                  if (column.search() !== this.value) {
                    column.search(input.value).draw();
                  }
                });
              });
          },
                  buttons: [
                    {
                      extend: 'copy',
                      text: 'Copiar',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8] 

                      }
                    },
                    {
                      extend: 'csv',
                      text: 'CSV',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'pdf',
                      text: 'PDF',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    },
                    {
                      extend: 'print',
                      text: 'Imprimir',
                      exportOptions: {
                        columns: [0, 1, 2,3,4,5,6,7,8]
                      }
                    }
                  ],
                  dom: 'Bfrtip', // Asegura que los botones aparezcan en el lugar correcto
                  info: false,
                  language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                      "first": "<◀",
                      "last": "▶> ",
                      "next": "▶",
                      "previous": "◀"
                    },
                    "buttons": {
                      "copy": "Copiar",
                      "csv": "CSV",
                      "excel": "Excel",
                      "pdf": "PDF",
                      "print": "Imprimir"
                    }
                  }
                });
              });
          </script>

</div>
</body>
</html>