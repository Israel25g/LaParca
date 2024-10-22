<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistema de tickets</title>
    <link rel="stylesheet" href="../../main-global.css">
    <!--Datatable-->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <link rel="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel=" https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
  <!--Datatable-->
    <link rel="shortcut icon" href="../images/ICO.png">
     
</head>
<body style=" margin: 0; padding: 0; background-image: url('../../host_virtual_TI/images/Motivo2.png');font-family:montserrat;">
    <!-- Header -->
    <div class="header-error">
    <div class="logo-container">
      <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
    </div>
    <h1><a href="../../helpdesk.php">Sistema de Tickets</a></h1>
    <div class="cuadroFecha-error">
      <p id="fecha-actual"></p>
      <p id="hora-actual"></p>
    </div>
  </div>
  <!-- Fin del Header -->

    <?php
    session_start();
    include '../funciones.php';
    $error = false;
    $config = include '../config.php';

    try {
      $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
      $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

      $consultaSQL = "SELECT * FROM tickets";

      $sentencia = $conexion->prepare($consultaSQL);
      $sentencia->execute();

      $tickets = $sentencia->fetchAll();

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



    <div class="container" style="margin-top: 200px !important; margin-left:200px">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-3"><a href="../../helpdesk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Listado de tickets de TI</h2>
          <table id="admin_ti" class="table shadow-sm p-3 mb-5 bg-body-tertiary table-striped" style="--bs-border-opacity: 0.5;">
            <thead>
            <tr>
                <th>TID</th>
                <th>Solicitante</th>
                <th>Correo</th>
                <th>Departamento</th>
                <th>Tipo de problema</th>
                <th>Nivel de urgencia</th>
                <th>Respuesta</th>
                <th>Estado</th>
                <th>Fecha de creacion</th>
                <th>Ultima actualizacion</th>
                <th>Acciones</th>
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
                    <td class="text-break"><?php echo escapar($fila["correo"]); ?></td>
                    <td class="text-break"><?php echo escapar($fila["ubicacion"]); ?></td>
                    <td class="text-break"><?php echo escapar($fila["descripcion"]); ?></td>
                    <td class="text-break"><?php echo escapar($fila["urgencia"]); ?></td> 
                    <td class="text-break"><?php echo escapar($fila["respuesta"]); ?></td> 
                    <td class="text-break"><?php echo escapar($fila["estado"]); ?></td> 
                    <td class="text-break"><?php echo escapar($fila["created_at"]); ?></td> 
                    <td class="text-break"><?php echo escapar($fila["updated_at"]); ?></td> 
                <td>  
                <a type="button" class="btn btn-outline-danger fs-1" data-toggle="modal" data-target="#login-<?= $fila["id"] ?>"><i class="bi bi-trash3-fill"></i></a>
                <form class="form-inline">
                    <!-- Modal -->
                        <div id="login-<?= $fila["id"] ?>" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">¿Está seguro de eliminar este registro?</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <p>Este no podra ser recuperado.</p>
                              </div>
                              <div class="modal-footer">
                                <a type="button" class="btn btn-default" data-dismiss="modal">Cerrar</a>
                                <a class="btn btn-danger" href="<?='borrar.php?id=' . escapar($fila["id"]) ?>">Borrar</a>
                              </div>
                            </div>
                          </div>
                        </div>
                    <!--modal -->
                </form>
                <a class="btn btn-outline-success fs-1 "  href="<?='responder_ti.php?id=' . escapar($fila["id"]) ?>"><i class="bi bi-envelope-fill"></i></a>
                </td>
                </tr>
                  <?php
                }
              }
              ?>
            <tbody>
              <tfoot>
              <tr>
                <th>TID</th>
                <th>Solicitante</th>
                <th>Correo</th>
                <th>Departamento</th>
                <th>Tipo de problema</th>
                <th>Nivel de urgencia</th>
                <th>Respuesta</th>
                <th>Estado</th>
                <th>Fecha de creacion</th>
                <th>Ultima actualizacion</th>
                <th>Acciones</th>
              </tr>
              </tfoot>
          </table>
        </div>
      </div>
    </div>
    <script src="../../host_virtual_TI/js/script.js"></script>
    <?php include "../../daily_plan/datatable.php" ?>
    <script>
      $(document).ready(function() {
        new DataTable('#admin_ti', {
          paging: false,
          scrollCollapse: true,
          scrollY: '450px',
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
    <?php include "../templates/footer.php"; ?>
  </body>
</html>