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
    $fechaEstimacionLlegada = isset($_GET['fecha_estimacion_llegada']) ? $_GET['fecha_estimacion_llegada'] : '';
    $condiciones = [];

    if (!$mostrarTodo) {
        $condiciones[] = "division_dp < 1.00";
    }

    if ($fechaEstimacionLlegada) {
        $condiciones[] = "fecha_objetivo = :fecha_objetivo";
    }

    // Define la consulta SQL y el encabezado según el filtro
    if ($filtro == 'picking') {
        $consultaSQL = "SELECT * FROM picking";
        $encabezado = [
            "#", "OID", "Cliente", "Unidades por pickear", "Paletas",
            "Unidades pickeadas", "Cajas", "Fecha de requerido", "Prioridad de picking", "Porcentaje de cumplimiento", "Acciones"
        ];
    } elseif ($filtro == 'export') {
        $consultaSQL = "SELECT * FROM export";
        $encabezado = [
            "#", "OID", "Cliente", "# Vehículo / Placa", "Pedidos en proceso",
            "Pedidos despachados", "Fecha estimada de salida", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } elseif ($filtro == 'import') {
        $consultaSQL = "SELECT * FROM import";
        $encabezado = [
            "#", "AID", "Cliente", "Vehículo / Placa", "Contenedor a recibir",
            "Contenedor recibido", "Tipo de carga", "Paletas", "Cajas", "Unidades",
            "Fecha estimada de llegada", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } else {
        $consultaSQL = " NULL ";
        $mensaje = "Seleccione un tipo de operación";
    }

    if (!empty($condiciones)) {
        $consultaSQL .= " WHERE " . implode(" AND ", $condiciones);
    }

    $sentencia = $conexion->prepare($consultaSQL);
    if ($fechaEstimacionLlegada) {
        $sentencia->bindValue(':fecha_objetivo', $fechaEstimacionLlegada, PDO::PARAM_STR);
    }

    $sentencia->execute();
    $datos = $sentencia->fetchAll();

    // Verificar si hay datos en la consulta
    $hayDatos = !empty($datos);
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
    <?php else: ?>
        <title>Daily Plan - Operaciones</title>
    <?php endif; ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../main-global.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
</head>
<body background="../../images/Motivo2.png">
<div class="container-lg start-0" style="margin-top: 150px;">

    <!-- Header -->
    <div class="header-error">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1><a href="../../helpdesk.php">Daily Plan</a></h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>

    <div class="container" style="margin-left:-200px">
        <!-- Filtro para la consulta -->
        <h2 class="mt-4 nombre-tabla"><a href="../helpdesk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Operaciones</h2>
        <form method="GET" class="mb-3">
            <label for="filtro">Elige la operación:</label>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Consultar
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
                <p>Mostrando datos de Import.</p>
        <?php elseif ($filtro == 'export'): ?>
            <div class="alert alert-danger w-25 p3">
                <p>Mostrando datos de Export.</p>
        <?php elseif ($filtro == 'picking'): ?>
            <div class="alert alert-warning w-25 p3">
                <p>Mostrando los datos de Picking.</p>
        <?php endif; ?>
        </div>
    </div>

    <!-- Tabla 'datos' -->
    <div class="tabla-container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif ($hayDatos): ?>
            <table id="tabla_MOP" class="display table">
                <thead>
                <tr>
                    <?php foreach ($encabezado as $titulo): ?>
                        <th class="border end"><?= $titulo ?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
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
                            <td class="border end"><?= $fila['prioridad_picking'] ?></td>
                            <td class="border end"><?= $fila['cumplimiento'] ?></td>
                            <td class="border end">
                                <button class="btn btn-success btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </td>
                        <?php elseif ($filtro == 'export'): ?>
                            <td class="border end"><?= $fila['id'] ?></td>
                            <td class="border end"><?= $fila['oid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['vehiculo'] ?></td>
                            <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end"><?= $fila['fecha_salida'] ?></td>
                            <td class="border end"><?= $fila['llegada_rampa'] ?></td>
                            <td class="border end"><?= $fila['salida_rampa'] ?></td>
                            <td class="border end">
                                <button class="btn btn-success btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </td>
                        <?php elseif ($filtro == 'import'): ?>
                            <td class="border end"><?= $fila['id'] ?></td>
                            <td class="border end"><?= $fila['aid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['vehiculo'] ?></td>
                            <td class="border end"><?= $fila['contenedor_a_recibir'] ?></td>
                            <td class="border end"><?= $fila['contenedor_recibido'] ?></td>
                            <td class="border end"><?= $fila['tipo_carga'] ?></td>
                            <td class="border end"><?= $fila['paletas'] ?></td>
                            <td class="border end"><?= $fila['cajas'] ?></td>
                            <td class="border end"><?= $fila['unidades'] ?></td>
                            <td class="border end"><?= $fila['fecha_objetivo'] ?></td>
                            <td class="border end"><?= $fila['llegada_rampa'] ?></td>
                            <td class="border end"><?= $fila['salida_rampa'] ?></td>
                            <td class="border end">
                                <button class="btn btn-success btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No hay registros disponibles para la consulta realizada.
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabla_MOP').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>

</body>
</html>
