<?php
include("../../apertura_sesion.php");
$usuarios_admitidos = ['igondola', 'agaray', 'nrivas', 'wlemos', 'riromero', 'kdelgado', 'ssalazar', 'abethancourt', 'jgrant', 'rolivero', 'igondola01'];
if (!in_array($_SESSION['user'], $usuarios_admitidos)) {
  echo "<script>location.href='../../helpdesk.php?error=No tienes permisos para acceder a esta página'</script>";
  exit;
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
    $consultaSQL = "SELECT *, DATE(created_at)  fechaCreado,
       DATE(updated_at)  fechaActualizado FROM tickets_seguimiento";
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
      <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/Salida2.gif" alt="Logo_IPL_Group"></a>
    </div>
    <h1><a href="../../helpdesk.php">Sistema de Tickets</a></h1>
    <div class="cuadroFecha-error">
      <p id="fecha-actual"></p>
      <p id="hora-actual"></p>
    </div>
  </div>
  <!-- Fin del Header -->

  <div class="espacio">
    <div class="tabla-container">
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

      <div class="row">
        <div class="col-md-12">
          <h2><a href="../../helpdesk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Seguimiento de temas Pendientes</h2>
          <div class="col-md-12">
            <a href="crear_seguimiento.php" class="btn btn-success "><i class="bi bi-pen-fill"></i> Crear Ticket</a>
          </div>

          <div class="filtros pt-2">
            <div class="container">
              <div
                class="row justify-content-center align-items-center g-2">
                <div class="card">
                  <div class="card-body">
                    <div class="row justify-content align-items-center">
                      <div class="col">
                        <label for="creacion">Fecha de creacion:</label>
                        <input type="date" id="creacion" class="form-control">
                      </div>
                      <div class="col">
                        <label for="filter-date-to">Fecha de Actualización:</label>
                        <input type="date" id="filter-date-to" class="form-control">
                      </div>
                      <div class="col">
                        <label for="urgencia">Nivel de urgencia:</label>
                        <select id="urgencia" class="form-control">
                          <option value="">Todos</option>
                          <option value="Regular">Regular</option>
                          <option value="Urgente">Urgente</option>
                        </select>
                      </div>
                      <div class="col">
                        <label for="filter-status">Estado:</label>
                        <select id="filter-status" class="form-control">
                          <option value="">Todos</option>
                          <option value="Recibido">Recibido</option>
                          <option value="En proceso">En Proceso</option>
                          <option value="Terminado">Terminado</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="card text-start mt-1">
              <div class="card-body">
                <table id="tickEemptable" class="table p-3 bg-body-tertiary rounded compact hover cell-border" style="background-color:#fff; width: 100%; margin-top: 1%;">
                  <thead>
                    <tr class="table-warning">
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
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["id"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["nombrecompleto"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["ubicacion"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["descripcion"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["urgencia"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["respuesta"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["estado"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["fechaCreado"]); ?></td>
                          <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#modal_eemp<?= $fila["id"] ?>"><?php echo escapar($fila["fechaActualizado"]); ?></td>
                          <div
                            class="modal fade"
                            id="modal_eemp<?= $fila["id"] ?>"
                            tabindex="-1"
                            data-bs-backdrop="static"
                            data-bs-keyboard="false"

                            role="dialog"
                            aria-labelledby="modalTitleId"
                            aria-hidden="true">
                            <div
                              class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
                              role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-warning">
                                  <h5 class="modal-title" id="modalTitleId">
                                    Detalles del ticket # <?= $fila["id"] ?> - Usuario: <?= $fila["nombrecompleto"] ?>
                                  </h5>
                                  <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="container-fluid">
                                    <!-- Id ticket -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Id de Ticket</strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<textarea class='p-1' rows='1' cols='5' disabled> " . $fila["id"] . " </textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Id Ticket -->

                                    <!-- Descripción -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Descripción del problema</strong> " ?>
                                      </div>
                                      <div class="col-md-4">
                                        <?php echo "<textarea class='p-1' rows='6' cols='50' disabled>" . $fila["descripcion"] . "</textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Descripción -->

                                    <!-- Respuesta -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Respuesta: </strong> " ?>
                                      </div>
                                      <div class="col-md-4">
                                        <?php echo "<textarea class='p-1' rows='6' cols='50' disabled>" . $fila["respuesta"] . "</textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Respuesta -->

                                    <!-- Nivel de Urgencia -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Nivel de Urgencia: </strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<textarea class='p-1' rows='1' cols='10'> " . $fila["urgencia"] . " </textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Nivel de Urgencia -->

                                    <!-- Estado -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Estado: </strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<textarea class='p-1' rows='1' cols='10'> " . $fila["estado"] . " </textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Estado -->

                                    <!-- Fecha de creación -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Creado el: </strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<input type='date' class='p-1' value=" . $fila["created_at"] . ">" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Fecha de creación -->


                                    <!-- Destinatarios -->
                                    <?php
                                    $desdeDB = $fila["correo_receiver"];
                                    $destinatarios = explode(",", $desdeDB);
                                    $destinatarios = array_map('trim',$destinatarios);
                                    $destinatariosUI = implode("\n ", $destinatarios);

                                    ?>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Destinatario(s): </strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<textarea class='p-1' rows='4' cols='30'> " . htmlspecialchars($destinatariosUI) . " </textarea>" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Destinatarios -->

                                    <!-- Fecha de Actualización -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <?php echo "<strong>Actualizado el: </strong> " ?>
                                      </div>
                                      <div class="col-md-4 pb-2">
                                        <?php echo "<input type='date' class='p-1' value=" . $fila["updated_at"] . ">" . "<br>"; ?>
                                      </div>
                                    </div>
                                    <!-- Fecha de Actualización -->
                                    <?php
                                    ?>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                    Cerrar
                                  </button>
                                  <?php
                                  if ($_SESSION['rol'] == 'EEMP') {
                                  ?>
                                    <a class="btn btn-success" href="../indexAdmin/responder_eemp.php?id=<?= $fila["id"] ?>" style="color: white; text-decoration: none;">Responder</a>
                                  <?php
                                  }
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div>
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
        </div>
      </div>


      <script src="../../host_virtual_TI/js/script.js"></script>
      <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
      <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
      <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>

      <script>
        $(document).ready(function() {
          var table = $('#tickEemptable').DataTable({
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
            },
            layout: {
              topStart: {
                pageLength: {
                  menu: [10, 25, 50, 100],
                }
              },
              topEnd: {
                search: true
              },

              bottomEnd: {
                paging: {
                  buttons: 3
                }
              }
            },
            scrollX: '170vh',
            scrollY: '270px',

            columnDefs: [{
              targets: '_all', // Aplica a todas las columnas
              render: function(data, type, row) {
                if (type === 'display' && data.length > 20) {
                  return data.substr(0, 20) + '...'; // Trunca el texto a 20 caracteres
                }
                return data;
              },
            }, ],

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
          // Filtros personalizados
          $('#creacion, #filter-date-to').on('change', function() {
            table.draw();
          });

          $('#urgencia, #filter-status').on('change', function() {
            table.draw();
          });
          // Comienza aqui
          $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
              var creacion = $('#creacion').val();
              var dateTo = $('#filter-date-to').val();
              var urgency = $('#urgencia').val();
              var status = $('#filter-status').val();

              var createdAt = data[7] || ''; // Usa el índice correcto para la columna de fecha de creación
              var updatedAt = data[8] || ''; // Usa el índice correcto para la columna de fecha de creación
              var ticketUrgency = data[4] || ''; // Usa el índice correcto para la columna de urgencia
              var ticketStatus = data[6] || ''; // Usa el índice correcto para la columna de estado

              // Convertir las fechas a objetos Date para compararlas
              var createdAtDate = new Date(createdAt);
              var updatedAtDate = new Date(updatedAt);
              var creacionDate = creacion ? new Date(creacion) : null;
              var dateToDate = dateTo ? new Date(dateTo) : null;

              if (creacionDate) {
                creacionDate.setHours(0, 0, 0, 0);
              }
              if (dateToDate) {
                dateToDate.setHours(23, 59, 59, 999);
              }
              createdAtDate.setHours(0, 0, 0, 0);
              if (
                (creacionDate === null || createdAtDate.toDateString() === creacionDate.toDateString()) &&
                (dateToDate === null || updatedAtDate.toDateString() === dateToDate.toDateString()) &&
                (urgency === '' || ticketUrgency === urgency) &&
                (status === '' || ticketStatus === status)
              ) {
                return true;
              }
              return false;
            }
          );
        });
      </script>
</body>

</html>