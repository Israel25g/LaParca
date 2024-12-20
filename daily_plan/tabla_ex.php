
<?php
include("../apertura_sesion.php");
?>
    <?php
    include '../daily_plan/funcionalidades/funciones.php';
    $error = false;
    $config = include '../daily_plan/funcionalidades/config_DP.php';

      try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

      // Consulta para la tabla 'export'
      $consultaSQL = "SELECT * FROM export WHERE division_dp < 1.00";
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
  <title>Daily Plan - Export</title>
  <link rel="stylesheet" href="../main-global.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <link rel="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel=" https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <link rel="shortcut icon" href="../images/ICO.png">
</head>

<body style="background-image:url('../host_virtual_TI/images/Motivo2.png')!important ;margin: 0;padding: 0; font-family:montserrat;">
  <div class="espacio">
    <!-- Header -->
    <div class="header-error">
      <div class="logo-container">
        <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group"></a>
      </div>
      <h1>Daily plan</h1>
      <div class="cuadroFecha">
        <p id="fecha-actual"></p>
        <p id="hora-actual">prueba</p>
      </div>
    </div>
    <!-- Fin del Header -->

    <!-- Navbar
    <div class="container-nav" style="margin-top: 0px; margin-left:36%; position: fixed; z-index: 999;">
      <div class="navbarr">
        <ul class="nav" id="detallesOps">
          <li class="nav-li"><a href="../helpdesk.php">Mesa de Ayuda (Tickets)</a></li>
          <li class="nav-li"><a href="../daily_plan/index_DP.php">Daily Plan</a></li>
          <li class="nav-li"><a href="#">Dashboards</a></li>
          <li class="nav-li"><a href="#">logout</a></li>
        </ul>
      </div>
    </div>
  </div> -->


<!-- Tabla 'datos' -->
<div class="tabla-container">
      <?php if ($error): ?>
        <div class="container mt-2">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-danger" role="alert">
                <?= $error ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-13">
          <h2 class="mt-3 nombre-tabla"><a href="../daily_plan/index_DP.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Export</h2>
          <button class="btn btn-success ingreso-data dropdown-toggle" style="margin-bottom: 10px;" data-bs-toggle="dropdown" aria-expanded="false"  href="../daily_plan/formulario_ex.php">Ingresar datos de Export</button>
            <ul class="dropdown-menu bg-success">
              <li><a class="dropdown-item bg-success text-light" href="../daily_plan/formulario_ex.php">Formulario singular</a></li>
              <li><a class="dropdown-item bg-success text-light" href="../daily_plan/hoja_ex/hoja_ex.php">Hoja de datos</a></li>
            </ul>
          <a class="btn btn-warning ingreso-data" style="margin-bottom: 10px;"mb-5 href="../daily_plan/grafico.php"><i class="bi bi-pie-chart-fill"></i> Ir a Gráficos</a>
          <br/>
          <table id="tablaExport" class="display table shadow p-3 mb-5 mt-2 bg-body-tertiary rounded table-striped border">
            <thead>
              <tr>
                <th class="border end">#</th>
                <th class="border end">OID</th>
                <th class="border end">Cliente</th>
                <th class="border end"># Vehículo / Placa</th>
                <th class="border end">Pedidos en proceso</th>
                <th class="border end">Pedidos despachados</th>
                <th class="border end">Fecha estimada de salida</th>
                <th class="border end">Llegada a rampa</th>
                <th class="border end">Salida de rampa</th>
                <th class="border end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($datos && $sentencia->rowCount() > 0): ?>
                      <?php foreach ($datos as $fila): ?>
                        <tr>
                            <td class="border end"><?php echo escapar($fila["id"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["aid_oid"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["vehiculo"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["pedidos_en_proceso"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["pedidos_despachados"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["fecha_objetivo"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["fecha_lleg_rampa"]); ?></td>
                            <td class="border end"><?php echo escapar($fila["fecha_sal_rampa"]); ?></td>
                            <td class="border end">
                              <a class="btn btn-outline-warning fs-6 border end" href="<?= './editar_ex.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
                              <a class="btn btn-outline-danger fs-6 bi bi-trash3-fill border end" href="<?= './funcionalidades/borrar_ex.php?id=' . escapar($fila["id"]) ?>"></a>
                            </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                        <th class="border end">#</th>
                        <th class="border end">OID</th>
                        <th class="border end">Cliente</th>
                        <th class="border end"># Vehículo / Placa</th>
                        <th class="border end">Pedidos en proceso</th>
                        <th class="border end">Pedidos despachados</th>
                        <th class="border end">Fecha estimada de salida</th>
                        <th class="border end">Llegada a rampa</th>
                        <th class="border end">Salida de rampa</th>
                        <th class="border end">Acciones</th>
                    </tr>
                   </tfoot>
                </table>
              </div>
            </div>
          </div>

          <!-- Scripts -->
          <?php include "../daily_plan/datatable.php" ?>
          <script src="../host_virtual_TI/js/script.js"></script>
          <script>
                  new DataTable('#tablaExport', {
                  paging: false,
                  scrollCollapse: true,
                  scrollY: '500px',
                  scrollX: '1700px',
                  initComplete: function() {
                    this.api()
                      .columns()
                      .every(function() {
                        let column = this;
                        let footer = column.footer();

                        if (footer) { 
                          let title = footer.textContent;
                          let input = document.createElement('input');
                          input.placeholder = title;
                          column.footer().replaceChildren(input);

                          input.addEventListener('keyup', () => {
                            if (column.search() !== this.value) {
                              column.search(input.value).draw();
                            }
                          });
                        }
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

          </script>
        </div>
      </body>

      </html>
