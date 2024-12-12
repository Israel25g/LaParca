<?php
include("../../apertura_sesion.php");
?>

<script>
    console.log("<?php echo date_default_timezone_get(); ?>")
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Usuarios</title>a
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

    <!-- sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">

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
        $consultaSQL = "SELECT *,users.id, roles.nombre_rol, estados.estado, (users.created_at) created_at_fechahora, (users.updated_at) updated_at_fechahora, DATE(users.created_at) created_at, DATE(users.updated_at) updated_at
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
        <div class="cuadroFecha-error">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->

    <div class="tabla-container">
        <div class="espacio">

            <!-- Error -->
            <?php
            if (isset($_GET['error'])) {
            ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 7000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "error",
                                text: "<?php echo $_GET["error"] ?>"
                            });
                        }, 700)
                    });
                </script>
            <?php
            }
            ?>

            <!-- Success -->
            <?php
            if (isset($_GET['success'])) {
            ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 7000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success",
                                text: "<?php echo $_GET["success"] ?>"
                            });
                        }, 700)
                    });
                </script>
            <?php
            }
            ?>




            <br>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="lista"><a href="../../helpdesk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Listado de Usuarios</h2>
                    <div class="col-md-12">
                        <a href="../../login/index_registro.php" class="btn btn-success " disabled><i class="bi bi-pen-fill"></i> Crear usuario</a>
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
                                            <!-- <div class="col">
                                                <label for="urgencia">Nivel de urgencia:</label>
                                                <select id="urgencia" class="form-control">
                                                    <option value="">Todos</option>
                                                    <option value="Regular">Regular</option>
                                                    <option value="Urgente">Urgente</option>
                                                </select>
                                            </div> -->
                                            <div class="col">
                                                <label for="filter-status">Estado:</label>
                                                <select id="filter-status" class="form-control">
                                                    <option value="">Todos</option>
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card text-start mt-1">
                            <div class="card-body">
                                <table id="tickTItable" class="table  p-3 mt-1 bg-body-tertiary rounded compact hover cell-border" style="background-color:#fff; width: 100%; margin-top: 1%;">
                                    <thead>
                                        <tr class="table-warning">
                                            <th class="border-end">UID</th>
                                            <th class="border-end">Nombre de usuario</th>
                                            <th class="border-end">Correo</th>
                                            <th class="border-end">Departamento</th>
                                            <th class="border-end">Estado</th>
                                            <th class="border-end">Fecha de creación</th>
                                            <th class="border-end">Fecha de actualización</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($tickets && $sentencia->rowCount() > 0) {
                                            foreach ($tickets as $fila) {

                                        ?>
                                                <tr>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["id"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["usuario"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["email"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["nombre_rol"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["estado"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["created_at"]); ?></td>
                                                    <td class="text-break tabla-hover" data-bs-toggle="modal" data-bs-target="#login<?= $fila["id"] ?>"><?php echo escapar($fila["updated_at"]); ?></td>

                                                    <!-- Modal Body -->
                                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                                    <div
                                                        class="modal fade"
                                                        id="login<?= $fila["id"] ?>"
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
                                                                    <h3 class="modal-title" id="modalTitleId">
                                                                        Detalles de usuario
                                                                    </h3>
                                                                    <button
                                                                        type="button"
                                                                        class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        <!-- Fila 1  -->
                                                                        <div class="form-row col-md-12 mb-3">
                                                                            <div class="col-md-4">
                                                                                <label for="id" class="fs-5">ID de Usuario:</label>
                                                                                <input class='p-1 rounded fs-6' rows='1' cols='10' disabled placeholder="<?= $fila["id"] ?>"><br>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label for="user" class="fs-5">User:</label>
                                                                                <input class='p-1 rounded fs-6' rows='1' cols='20' disabled placeholder="<?= $fila["user"] ?>"><br>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label for="departamento" class="fs-5">Departamento:</label>
                                                                                <input class='p-1 rounded fs-6' rows='1' cols='10' disabled placeholder="<?= $fila["nombre_rol"] ?>"><br>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Fila 1 - Usuario -->

                                                                        <!-- Fila 2 -->


                                                                        <div class="form-row col-md-12 mb-3">
                                                                            <div class="col-md-4">
                                                                                <label for="Correo" class="fs-5 ">Correo:</label>
                                                                                <input class='p-1 rounded fs-6' rows='1' cols='30' disabled placeholder="<?= $fila["email"] ?>"><br>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="Estado" class="fs-5 ">Estado:</label>
                                                                                <input class='p-1 rounded fs-6' rows='1' cols='30' disabled placeholder="<?= $fila["estado"] ?>"><br>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 3 -->

                                                                        <div class="form-row col-md-12 mb3">
                                                                            <div class="col-md-5">
                                                                                <label for="Creado" class="fs-5 ">Creado: </label>
                                                                                <input disabled type='datetime-local' class='p-1 rounded fs-6' value="<?= $fila["created_at_fechahora"] ?>"><br>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <label for="Actualizado" class="fs-5 ">Actualizado: </label>
                                                                                <input disabled type='datetime-local' class='p-1 rounded fs-6' cols='30' value="<?= $fila["updated_at_fechahora"] ?>"><br>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Fila 3 -->

                                                                        <!-- Fila 4 -->
                                                                        <!-- <div class="form-row col-md-12 mb3">
                                                                            <div class="col-md-5">
                                                                                <label for="Creado" class="fs-5 ">Editado por: </label>
                                                                                <input disabled type='text' class='p-1 rounded fs-6' value=" "><br>
                                                                            </div>
                                                                        </div> -->
                                                                        <!-- Fila 4 -->
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
                                                                    if ($fila["estado"] == 'Activo') {
                                                                    ?>
                                                                        <button class="btn btn-danger" data-bs-target="#inhabilitar<?= $fila["id"] ?>" data-bs-toggle="modal">Inhabilitar <i class="bi bi-person-dash-fill"></i></button>
                                                                    <?php
                                                                    } elseif ($fila["estado"] == 'Inactivo') {
                                                                    ?>
                                                                        <button class="btn btn-success" data-bs-target="#inhabilitar<?= $fila["id"] ?>" data-bs-toggle="modal">Habilitar <i class="bi bi-person-check-fill"></i></button>
                                                                    <?php
                                                                    }
                                                                    ?>



                                                                    <?php
                                                                    $id = $fila["id"];
                                                                    $nombre = $fila["usuario"];
                                                                    $usuario = $fila["user"];
                                                                    $correo = $fila["email"];
                                                                    $departamento = $fila["nombre_rol"];
                                                                    $id_departamento = $fila["rol_id"];

                                                                    if ($_SESSION['rol'] == 'Admin' || $_SESSION['rol'] == 'EEMP') {
                                                                    ?>
                                                                        <a class="btn btn-warning" href="./edicion.php?id=<?php echo ($id) ?>&nombre=<?php echo ($nombre) ?>&correo=<?php echo ($correo) ?>&usuario=<?php echo $usuario ?>&departamento=<?php echo $departamento ?>&id_departamento=<?php echo ($id_departamento) ?>" style="color: white; text-decoration: none;">Editar <i class="bi bi-pencil-square"></i> </a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal trigger button -->
                                                    <!-- Modal Body -->
                                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                                    <div
                                                        class="modal fade"
                                                        id="inhabilitar<?= $fila["id"] ?>"
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
                                                                    <h3 class="modal-title" id="modalTitleId">
                                                                        <?php
                                                                        $estado_BD = "";

                                                                        if ($fila["estado"] == 'Activo') {
                                                                            $estado_BD = "Inhabilitar";
                                                                        } elseif ($fila["estado"] == 'Inactivo') {
                                                                            $estado_BD = "Habilitar";
                                                                        }
                                                                        ?>
                                                                        Va a <?= $estado_BD ?> al usuario con id <?= $fila["id"] ?> - <?= $fila["usuario"] ?>
                                                                    </h3>
                                                                    <button
                                                                        type="button"
                                                                        class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">Está seguro de realizar este cambio (Este cambio puede ser revertido) </div>
                                                                <div class="modal-footer">
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        Cancelar
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        class="btn btn-primary"
                                                                        data-bs-target="#login<?= $fila["id"] ?>"
                                                                        data-bs-toggle="modal">
                                                                        Volver a detalles
                                                                    </button>
                                                                    <form action="estado_user.php" method="post">
                                                                        <?php
                                                                        if ($fila["estado"] == 'Activo') {
                                                                            $estado_BD = "Inhabilitar";
                                                                        ?>
                                                                            <input name="id" type="text" id="id" value="<?= $fila["id"] ?>" hidden>
                                                                            <input name="estado" type="text" id="estado" value="2" hidden>
                                                                            <button type="submit" class="btn btn-danger">Inhabilitar <i class="bi bi-person-dash-fill"></i></button>
                                                                        <?php
                                                                        } elseif ($fila["estado"] == 'Inactivo') {
                                                                            $estado_BD = "Habilitar";
                                                                        ?>
                                                                            <input name="id" type="text" id="id" value="<?= $fila["id"] ?>" hidden>
                                                                            <input name="estado" type="text" id="estado" value="1" hidden>
                                                                            <button type="submit" class="btn btn-success">Habilitar <i class="bi bi-person-check-fill"></i></button>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Optional: Place to the bottom of scripts -->
                                                    <script>
                                                        const myModal = new bootstrap.Modal(
                                                            document.getElementById("modalId"),
                                                            options,
                                                        );
                                                    </script>

                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="border-end">UID</th>
                                            <th class="border-end">Nombre de usuario</th>
                                            <th class="border-end">Correo</th>
                                            <th class="border-end">Departamento</th>
                                            <th class="border-end">Estado</th>
                                            <th class="border-end">Fecha de creación</th>
                                            <th class="border-end">Fecha de actualización</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script src="../js/script.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/2.1.8/dataRender/datetime.js"></script>
        <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
        <!-- <script src="sweetalert2.min.js"></script> -->

        <script>
            $(document).ready(function() {
                var table = $('#tickTItable').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    layout: {
                        topStart: {
                            pageLength: {
                                menu: [10, 25, 50, 100],
                            }
                        },
                        topEnd: {
                            search: true,
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

                                // Crear elemento input
                                let input = document.createElement('input');
                                input.placeholder = title;
                                column.footer().replaceChildren(input);

                                // Event listener para la entrada del usuario
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

                // #urgencia y #filter-status son los id de los select (saque a este wey de acá)
                $('#filter-status').on('change', function() {
                    table.draw();
                });
                // Comienza aqui
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var creacion = $('#creacion').val();
                        var dateTo = $('#filter-date-to').val();
                        // var urgency = $('#urgencia').val();
                        var status = $('#filter-status').val();

                        var createdAt = data[5] || ''; // Usa el índice correcto para la columna de fecha de creación
                        var updatedAt = data[6] || ''; // Usa el índice correcto para la columna de fecha de creación
                        // var ticketUrgency = data[4] || ''; // Usa el índice correcto para la columna de urgencia
                        var ticketStatus = data[4] || ''; // Usa el índice correcto para la columna de estado

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
                            // (urgency === '' || ticketUrgency === urgency) &&
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