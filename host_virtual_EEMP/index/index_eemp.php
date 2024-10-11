<?php
include("../../apertura_sesion.php")
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de tickets</title>
  <link rel="shortcut icon" href="../../images/ICO.png">
  <!-- estilo bootstrap css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../main-global.css">
  <!--estilos ccs-->
  <!--Datatable-->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
  <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.1.js">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css" rel="stylesheet">
  <!--estilos ccs-->
</head>

<body style="background-image: url('../../host_virtual_TI/images/Motivo2.png'); overflow: visible">

  <?php
  function escapar($html)
  {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
  }
  $error = false;
  $config = include '../config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $consultaSQL = "SELECT * FROM tickets_eemp";
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $tickets = $sentencia->fetchAll();
  } catch (PDOException $error) {
    $error = $error->getMessage();
  }
  ?>

  <!-- Header -->
  <div class="header">
    <div class="logo-container">
      <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
    </div>
    <h1><a href="../../helpdesk.php">Sistema de Tickets</a></h1>
    <div class="cuadroFecha">
      <p id="fecha-actual"></p>
      <p id="hora-actual"></p>
    </div>
  </div>
  <!-- Fin del Header -->

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
  <br>


  <div class="tabla-container">
    <div class="row">
      <div class="col-md-12">
        <h2>Listado de ticket de EEMP</h2>
        <div class="col-md-12">
          <a href="crear_eemp.php" class="btn btn-success "><i class="bi bi-pen-fill"></i> Crear Ticket</a>
          <?php
          if ($_SESSION['rol'] === 'EEMP') {
            echo '<a href="../indexAdmin/indexAdmin_eemp.php" class="btn btn-warning "><i class="bi bi-pencil-square"></i> Ver tickets de EEMP</a>';
          }
          ?>
        </div>
        <table id="tickEemptable" class="table shadow p-3 mb-5 bg-body-tertiary rounded compact hover cell-border" style="background-color:#fff; width: 100%; margin-top: 1%;">
          <thead>
            <tr>
              <th class="border-end">TID</th>
              <th class="border-end">Solicitante</th>
              <!-- <th>correo</th> -->
              <th class="border-end">Departamento</th>
              <th class="border-end">Descripción del problema</th>
              <th class="border-end">Nivel de urgencia</th>
              <th class="border-end">Respuesta</th>
              <th class="border-end">Estado</th>
              <th class="border-end">Fecha de creacion</th>
              <th class="border-end">Ultima actualizacion</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($tickets && $sentencia->rowCount() > 0) {
              foreach ($tickets as $fila) {
            ?>
                <tr>
                  <td class="text-break"><?php echo escapar($fila["id"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["nombrecompleto"]); ?></td>
                  <!-- <td><?php #echo escapar($fila["correo"]);?></td> -->
                  <td class="text-break"><?php echo escapar($fila["ubicacion"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["descripcion"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["urgencia"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["respuesta"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["estado"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["created_at"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["updated_at"]); ?></td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th>TID</th>
              <th>Solicitante</th>
              <!-- <th>correo</th> -->
              <th>Departamento</th>
              <th>Descripción del problema</th>
              <th>nivel de urgencia</th>
              <th>Respuesta</th>
              <th>Estado</th>
              <th>fecha de creacion</th>
              <th>ultima actualizacion</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <script src="../js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>

  <script>
    new DataTable('#tickEemptable', {
      layout: {
        topStart: {
          pageLength: {
            menu: [10, 25, 50, 100],
          }
        },
        topEnd: {
          search: {
            placeholder: 'Busca un ticket'
          }
        },

        bottomEnd: {
          paging: {
            buttons: 3
          }
        }
      },
      scrollX: '150vh',
      scrollY: '450px',
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
    });
  </script>
</body>

</html>