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
    
    // Define la consulta SQL y el encabezado según el filtro y el estado del checkbox
    if ($filtro == 'picking') {
        $consultaSQL = $mostrarTodo 
            ? "SELECT * FROM picking" 
            : "SELECT * FROM picking WHERE division_dp < 1.00";
        $encabezado = [
            "#", "OID", "Cliente", "Unidades por pickear", "Paletas",
            "Unidades pickeadas", "Cajas", "Fecha de requerido", "Prioridad de picking", "Porcentaje de cumplimiento", "Acciones"
        ];
    } elseif ($filtro == 'export') {
        $consultaSQL = $mostrarTodo 
            ? "SELECT * FROM export" 
            : "SELECT * FROM export WHERE division_dp < 1.00";
        $encabezado = [
            "#", "OID", "Cliente", "# Vehículo / Placa", "Pedidos en proceso",
            "Pedidos despachados", "Fecha estimada de salida", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } elseif ($filtro == 'import') {
        $consultaSQL = $mostrarTodo 
            ? "SELECT * FROM import" 
            : "SELECT * FROM import WHERE division_dp < 1.00";
        $encabezado = [
            "#", "AID", "Cliente", "Vehículo / Placa", "Contenedor a recibir",
            "Contenedor recibido", "Tipo de carga", "Paletas", "Cajas", "Unidades",
            "Fecha estimada de llegada", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } else {
        $consultaSQL = " NULL ";
        $mensaje = "Seleccione un tipo de operación";
    }
    

    $sentencia = $conexion->prepare($consultaSQL);
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
    <div class="header">
            <div class="logo-container">
                <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group"></a>
            </div>
            <h1 href="../../daily_plan/index_DP.php">Daily plan</h1>
            <div class="cuadroFecha">
                <p id="fecha-actual"></p>
                <p id="hora-actual">prueba</p>
            </div>
        </div>
  <div class="container" style="margin-left:-200px">
    <!-- Filtro para la consulta -->
    <h2 class="mt-4 nombre-tabla"><a href="../helpdesk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Operaciones</h2>
    <form method="GET" class="mb-3">
    <label for="filtro">Elige la operación:</label>
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <?= isset($_GET['filtro']) ? ucfirst($_GET['filtro']) : 'Selecciona una opción' ?>
        </button>
        <ul class="dropdown-menu bg bg-outline-secondary" aria-labelledby="dropdownMenuButton">
            <li>
                <a class="dropdown-item bg bg-info" href="#" onclick="document.getElementById('filtro').value='import'; this.closest('form').submit(); return false;">
                    Import
                </a>
            </li>
            <li>
                <a class="dropdown-item bg bg-danger" href="#" onclick="document.getElementById('filtro').value='export'; this.closest('form').submit(); return false;">
                    Export
                </a>
            </li>
            <li>
                <a class="dropdown-item bg bg-warning" href="#" onclick="document.getElementById('filtro').value='picking'; this.closest('form').submit(); return false;">
                    Picking
                </a>
            </li>
        </ul>
    </div>

    <!-- Checkbox para mostrar toda la tabla -->
    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="mostrar_todo" id="mostrar_todo" <?= isset($_GET['mostrar_todo']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="mostrar_todo">Mostrar toda la tabla</label>
    </div>

    <!-- Campo oculto para almacenar el valor seleccionado -->
    <input type="hidden" name="filtro" id="filtro" value="<?= isset($_GET['filtro']) ? $_GET['filtro'] : '' ?>">
</form>



    <!-- Mostrar el mensaje sobre el filtro aplicado -->
        <?php if ($filtro == 'import'): ?>
          <div class="alert alert-info w-25 p3">
            <p >Mostrando datos de Import.</p>
        <?php elseif ($filtro == 'export'): ?>
          <div class="alert alert-danger w-25 p3">
            <p >Mostrando datos de Export.</p>
        <?php elseif ($filtro == 'picking'): ?>
          <div class="alert alert-warning w-25 p3">
            <p >Mostrando los datos de Picking.</p>
        <?php endif; ?>
    </div>
  </div>
    <!-- Tabla 'datos' -->
    <div class="tabla-container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table id="tabla_MOP" class="display table">
            <thead>
            <tr>
            <?php if ($filtro == 'import'): ?>
              <?php foreach ($encabezado as $titulo): ?>
                    <th class="border end text-bg-info "><?= $titulo ?></th>
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
                            <td class="border end"><?= $fila['division_dp'] *100?>%</td>
                        <?php elseif ($filtro == 'export'): ?>
                            <td class="border end"><?= $fila['id'] ?></td>
                            <td class="border end"><?= $fila['aid_oid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['vehiculo'] ?></td>
                            <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end"><?= $fila['fecha_objetivo'] ?></td>
                            <td class="border end"><?= $fila['fecha_lleg_rampa'] ?></td>
                            <td class="border end"><?= $fila['fech_sal_rampa'] ?></td>
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
                            <td class="border end"><?= $fila['fech_sal_rampa'] ?></td>
                        <?php endif; ?>
                        <td class="border end">
                            <!-- Aquí puedes añadir las acciones necesarias -->
                            <button class="btn btn-primary">Acción</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($encabezado) ?>" class="text-center">No hay registros disponibles.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <?php foreach ($encabezado as $titulo): ?>
                    <th class="border end"><?= $titulo ?></th>
                <?php endforeach; ?>
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
          scrollCollapse: true,
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
