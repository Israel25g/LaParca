<?php
include("../apertura_sesion.php");
include '../daily_plan/funcionalidades/funciones.php';
$config = include '../daily_plan/funcionalidades/config_DP.php';
$error = false;

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    // Define la consulta SQL y el encabezado según el filtro
    if ($filtro == 'import') {
        $consultaSQL = "SELECT * FROM import";
        $encabezado = [
            "#", "OID", "Cliente", "Unidades por pickear", "Paletas",
            "Unidades pickeadas", "Cajas", "Fecha de requerido", "Prioridad de picking", "Porcentaje de avance", "Acciones"
        ];
    } elseif ($filtro == 'export') {
        $consultaSQL = "SELECT * FROM export";
        $encabezado = [
            "#", "OID", "Cliente", "# Vehículo / Placa", "Pedidos en proceso",
            "Pedidos despachados", "Fecha estimada de salida", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } elseif ($filtro == 'picking') {
        $consultaSQL = "SELECT * FROM picking WHERE division_dp < 1.00";
        $encabezado = [
            "#", "AID", "Cliente", "Vehículo / Placa", "Contenedor a recibir",
            "Contenedor recibido", "Tipo de carga", "Paletas", "Cajas", "Unidades",
            "Fecha estimada de llegada", "Llegada a rampa", "Salida de rampa", "Acciones"
        ];
    } else {
        // En caso de un filtro no reconocido, podrías optar por redirigir a una opción por defecto o mostrar un error.
        $consultaSQL = "SELECT * FROM picking"; // Cambiar a lo que necesites
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
    <title>Daily Plan - Picking</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../main-global.css">
    <link rel="shortcut icon" href="../images/ICO.png">
</head>
<body>
<div style="margin-top: 90px;">
    <!-- Header -->
    <div class="header-error">
        <h1>Daily plan</h1>
    </div>

    <!-- Filtro para la consulta -->
    <form method="GET" class="mb-3">
        <label for="filtro">Elige el filtro:</label>
        <select name="filtro" id="filtro" class="form-control">
            <option value="import" <?= isset($_GET['filtro']) && $_GET['filtro'] == 'import' ? 'selected' : '' ?>>Import</option>
            <option value="export" <?= isset($_GET['filtro']) && $_GET['filtro'] == 'export' ? 'selected' : '' ?>>Export</option>
            <option value="picking" <?= isset($_GET['filtro']) && $_GET['filtro'] == 'picking' ? 'selected' : '' ?>>Picking</option>
        </select>
        <button type="submit" class="btn btn-primary mt-2">Aplicar Filtro</button>
    </form>

    <!-- Mostrar el mensaje sobre el filtro aplicado -->
    <div class="alert alert-info mt-2">
        <?php if ($filtro == 'import'): ?>
            <p>Mostrando datos de import.</p>
        <?php elseif ($filtro == 'export'): ?>
            <p>Mostrando datos de export.</p>
        <?php elseif ($filtro == 'picking'): ?>
            <p>Mostrando solo los registros con avance incompleto (division_dp < 1.00).</p>
        <?php endif; ?>
    </div>

    <!-- Tabla 'datos' -->
    <div class="tabla-container">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table id="tablaPicking" class="display table">
            <thead>
            <tr>
                <?php foreach ($encabezado as $titulo): ?>
                    <th class="border end"><?= $titulo ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($datos && $sentencia->rowCount() > 0): ?>
                <?php foreach ($datos as $fila): ?>
                    <tr>
                        <td class="border end"><?= $fila['id'] ?></td>
                        <?php if ($filtro == 'import'): ?>
                            <td class="border end"><?= $fila['oid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['unidades_por_pickear'] ?></td>
                            <td class="border end"><?= $fila['paletas'] ?></td>
                            <td class="border end"><?= $fila['unidades_pickeadas'] ?></td>
                            <td class="border end"><?= $fila['cajas'] ?></td>
                            <td class="border end"><?= $fila['fecha_de_requerido'] ?></td>
                            <td class="border end"><?= $fila['prioridad_de_picking'] ?></td>
                            <td class="border end"><?= $fila['porcentaje_de_avance'] ?></td>
                        <?php elseif ($filtro == 'export'): ?>
                            <td class="border end"><?= $fila['oid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['vehiculo'] ?></td>
                            <td class="border end"><?= $fila['pedidos_en_proceso'] ?></td>
                            <td class="border end"><?= $fila['pedidos_despachados'] ?></td>
                            <td class="border end"><?= $fila['fecha_estimada_salida'] ?></td>
                            <td class="border end"><?= $fila['llegada_a_rampa'] ?></td>
                            <td class="border end"><?= $fila['salida_de_rampa'] ?></td>
                        <?php elseif ($filtro == 'picking'): ?>
                            <td class="border end"><?= $fila['aid'] ?></td>
                            <td class="border end"><?= $fila['cliente'] ?></td>
                            <td class="border end"><?= $fila['vehiculo'] ?></td>
                            <td class="border end"><?= $fila['contenedor_a_recibir'] ?></td>
                            <td class="border end"><?= $fila['contenedor_recibido'] ?></td>
                            <td class="border end"><?= $fila['tipo_carga'] ?></td>
                            <td class="border end"><?= $fila['paletas'] ?></td>
                            <td class="border end"><?= $fila['cajas'] ?></td>
                            <td class="border end"><?= $fila['unidades'] ?></td>
                            <td class="border end"><?= $fila['fecha_estimada_llegada'] ?></td>
                            <td class="border end"><?= $fila['llegada_a_rampa'] ?></td>
                            <td class="border end"><?= $fila['salida_de_rampa'] ?></td>
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
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../host_virtual_TI/js/script.js"></script>
    <script>
        $(document).ready(function() {
            new DataTable('#tablaPicking', {
                paging: false,
                scrollCollapse: true,
                scrollY: '350px',
                scrollX: '1700px',
                dom: 'Bfrtip',
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
                    }
                }
            });
        });
    </script>
</body>
</html>
