<?php
include("../../apertura_sesion.php");
if ($_SESSION['rol'] !== 'Admin' && $_SESSION['rol'] !== 'EEMP') {
  header("Location: ../../index.php?error=Acesso no autorizado");
}
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
  <!-- Librerías de la modal -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">    
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

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
    $consultaSQL = "SELECT *,users.id, roles.nombre_rol, estados.estado
FROM users
JOIN roles ON users.rol_id = roles.id
JOIN estados ON users.estado_id = estados.id;
";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $tickets = $sentencia->fetchAll();

  } catch (PDOException $error) {
    $error = $error->getMessage();
  }
  ?>

  <!-- Header -->
  <div class="header-error">
    <div class="logo-container">
      <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
    </div>
    <h1><a href="../../helpdesk.php">Control de Usuarios</a></h1>
    <div class="cuadroFecha">
      <p id="fecha-actual"></p>
      <p id="hora-actual"></p>
    </div>
  </div>
  <!-- Fin del Header -->
  
  <!-- <?php #include "../templates/header.php"; ?> -->

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


  <div class="tabla-container espacio">
    <div class="row">
      <div class="col-md-12">
        <h2>Listado de Usuarios</h2>
        <div class="col-md-12">
          <a href="../../login/index_registro.php" class="btn btn-success mb-3"><i class="bi bi-pen-fill"></i> Crear usuario</a>
        </div>
        <table id="tickEemptable" class="table shadow p-3 mb-5 bg-body-tertiary rounded compact hover cell-border" style="background-color:#fff; width: 100%; margin-top: 1%;">
          <thead>
            <tr>
              <th class="border-end">UID</th>
              <th class="border-end">Usuario</th>
              <th class="border-end">Nombre de usuario</th>
              <th class="border-end">Correo</th>
              <th class="border-end">Departamento</th>
              <th class="border-end">Estado</th>
              <th class="border-end">Acciones</th>
              <!-- <th class="border-end">Descripción del problema</th>
              <th class="border-end">Nivel de urgencia</th>
              <th class="border-end">Respuesta</th>
              <th class="border-end">Estado</th>
              <th class="border-end">Fecha de creacion</th>
              <th class="border-end">Ultima actualizacion</th> -->
            </tr>
          </thead>
          <tbody data-toggle="modal" data-target="#login<?= $fila["id"] ?>">
            <?php
            if ($sentencia->rowCount() > 0) {
              foreach ($tickets as $fila) {
                ?>
                <tr>
                  <td class="text-break"><?php echo escapar($fila["id"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["user"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["usuario"]);?></td>
                  <td class="text-break"><?php echo escapar($fila["email"]);?></td>
                  <td class="text-break"><?php echo escapar($fila["nombre_rol"]); ?></td>
                  <td class="text-break"><?php echo escapar($fila["estado"]); ?></td>
                  <!-- <td class="text-break"><?php #echo escapar($fila["descripcion"]); ?></td> -->
                  <!-- <td class="text-break"><?php #echo escapar($fila["urgencia"]); ?></td> -->
                  <!-- <td class="text-break"><?php #echo escapar($fila["respuesta"]); ?></td> -->
                  <!-- <td class="text-break"><?php #echo escapar($fila["estado"]); ?></td> -->
                  <!-- <td class="text-break"><?php #echo escapar($fila["created_at"]); ?></td> -->
                  <!-- <td class="text-break"><?php #echo escapar($fila["updated_at"]); ?></td> -->
                  <td>  
                <a type="button" class="btn btn-outline-warning d-block m-1" data-toggle="modal" data-target="#login<?= $fila["id"] ?>"><i class="bi bi-gear-fill"></i></a>
                <form class="form-inline">
                    <!-- Modal -->
                        <div id="login<?= $fila["id"] ?>" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-sm modal-dialog-centered">
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Modificación del usuario <?= $fila["user"]?></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <p>Nota: no es necesario llenar todos los campos, solo actualice lo que requiera.</p>
                                <?php include ("editar_user.php")?>
                              </div>
                              <div class="modal-footer">
                                <a class="btn btn-default" role="button" data-dismiss="modal">Cerrar</a>
                              </div>
                            </div>
                          </div>
                        </div>
                    <!--modal -->
                </form>
                </td>
                </tr>
              <?php
              }
            }
            ?>
          </tbody>
          <tfoot>
          <tr>
              <th class="border-end">UID</th>
              <th class="border-end">Usuario</th>
              <th class="border-end">Nombre de usuario</th>
              <th class="border-end">Correo</th>
              <th class="border-end">Departamento</th>
              <th class="border-end">Estado</th>
              <!-- <th class="border-end">Descripción del problema</th>
              <th class="border-end">Nivel de urgencia</th>
              <th class="border-end">Respuesta</th>
              <th class="border-end">Estado</th>
              <th class="border-end">Fecha de creacion</th>
              <th class="border-end">Ultima actualizacion</th> -->
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
            placeholder: 'Busca un usuario'
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
      fixedColumns: true,
      columnDefs: [{
        width: 5,
        targets: [0],
        className: 'dt-body-center'
      }],

    });
    $('#container').css('display', 'block');
    table.columns.adjust().draw();
  </script>
</body>

</html>