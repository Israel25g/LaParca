<?php
$config = include '../../funcionalidades/config_DP.php';

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
    $mostrarTodo = isset($_GET['mostrar_todo']);

    // Consulta para obtener los clientes desde la tabla "clientes"
    $sql = "SELECT nombre_cliente FROM clientes";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Guardar los resultados en un array

    // Convertir los nombres de los clientes en un formato que Handsontable pueda usar
    $clientes_js = implode(", ", array_map(function($cliente) {
        return "'" . $cliente['nombre_cliente'] . "'";
    }, $clientes));

} catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = "Error al conectar a la base de datos: " . $error->getMessage();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Datos: Import</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../../estilos.css">
    <link rel="shortcut icon" href="../../../images/ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.js"></script>
</head>
<body style="background-image:url('../../../images/Motivo2.png')!important;margin: 0;padding: 0; font-family:montserrat;">
    <div class="form-table">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/IPL.png" alt="Logo_IPL_Group"></a>
            </div>
            <h1 href="../daily_plan/index_DP.php">Daily plan</h1>
            <div class="cuadroFecha">
                <p id="fecha-actual"></p>
                <p id="hora-actual">prueba</p>
            </div>
        </div>
        <!-- Fin del Header -->
    </div>

    <?php 
// Definir el mensaje y el enlace predeterminados según el valor de $filtro
$titulo = "Acceso nulo, Regrese a la pagina de inicio";
$enlace = "../../../index.php";
$botonClase = "btn btn-danger";
$botonTexto = "Volver al inicio";

// Personalizar los mensajes según el valor de $filtro
if ($filtro == 'import') {
    $titulo = "Ingrese los datos para crear el Daily plan de Import";
    $enlace = "../tabla_multiple.php?fecha_estimacion_llegada=&filtro=import";
    $botonClase = "btn btn-success";
    $botonTexto = "Guardar datos";
} elseif ($filtro == 'export') {
    $titulo = "Ingrese los datos para crear el Daily plan de Export";
    $enlace = "../tabla_multiple.php?fecha_estimacion_llegada=&filtro=export";
    $botonClase = "btn btn-success";
    $botonTexto = "Guardar datos";
} elseif ($filtro == 'picking') {
    $titulo = "Ingrese los datos para crear el Daily plan de Picking";
    $enlace = "../tabla_multiple.php?fecha_estimacion_llegada=&filtro=picking";
    $botonClase = "btn btn-success";
    $botonTexto = "Guardar datos";
}
?>

<div style="margin-top: -150px; margin-left: 60px">
    <h3 class="mb-2"><a href="<?= htmlspecialchars($enlace); ?>"><i class="bi bi-caret-left-fill arrow-back"></i></a><?= htmlspecialchars($titulo); ?></h3>
    <?php if ($filtro == 'import' || $filtro == 'export' || $filtro == 'picking'): ?>
        <button class="<?= htmlspecialchars($botonClase); ?>" id="guardarDatos"><?= htmlspecialchars($botonTexto); ?></button>
    <?php else: ?>
        <a class="<?= htmlspecialchars($botonClase); ?>" href="<?= htmlspecialchars($enlace); ?>"><?= htmlspecialchars($botonTexto); ?></a>
    <?php endif; ?>
</div>

    <div id="tablaExcel" style="margin: 60px;"></div>





    <?php if ($filtro == 'import'): ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('tablaExcel');

            // Columnas de tu tabla
            const colHeaders = ['Aid*', 'Cliente*', 'Vehículo/Placa*', 'Tipo de Vehículo', 'BL', 'Origen', 'Tipo de carga', 'Paletas', 'Cajas', 'Unidades', 'Pedidos por recibir*', 'Fecha estimada de llegada*', 'Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'dropdown', 
        source: [<?php echo $clientes_js; ?>], // Opciones del selector de clientes generadas por PHP
        strict: true,
        allowInvalid: false // No permite valores no válidos (fuera de la lista)
    }, // cliente
                { data: 2, type: 'text' }, // vehiculo/placa
                { data: 3, type: 'text' }, // tipo de vehículo
                { data: 4, type: 'text' }, // bl
                { data: 5, type: 'text' }, // destino
                { data: 6, type: 'text' }, // destino
                { data: 7, type: 'numeric' }, // paletas
                { data: 8, type: 'numeric' }, // cajas
                { data: 9, type: 'numeric' }, // unidades
                { data: 10, type: 'numeric' }, // pedidos en proceso
                { data: 11, type: 'date', dateFormat: 'YYYY-MM-DD' }, // fecha objetivo
                { data: 12, type: 'text' }  // comentario oficina
            ];

            // Configuración de Handsontable
            const hot = new Handsontable(container, {
                data: Handsontable.helper.createEmptySpreadsheetData(1, colHeaders.length), // Datos vacíos con el número de columnas adecuado
                rowHeaders: true,
                colHeaders: colHeaders,
                columns: columns,
                filters: true,
                dropdownMenu: true,
                minSpareRows: 0,
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                stretchH: 'all',
                licenseKey: 'non-commercial-and-evaluation', // Necesario para la versión gratuita
            });

            // Función para validar los datos antes de guardarlos
            function validarDatos(data) {
                for (let i = 0; i < data.length; i++) {
                    const row = data[i];
                    // Validar que las columnas específicas no estén vacías
                    // Columnas: 0 (Oid), 1 (Cliente), 2 (Vehículo/placa), 9 (Pedidos en Proceso), 10 (Fecha estimada de salida)
                    const requiredColumns = [0, 1, 2, 10, 11];
                    for (let j of requiredColumns) {
                        if (row[j] === null || row[j] === '') {
                            return false; // Devuelve falso si encuentra un campo obligatorio vacío
                        }
                    }
                }
                return true; // Devuelve verdadero si todos los campos obligatorios están completos
            }

            // Evento click para guardar datos
            document.getElementById('guardarDatos').addEventListener('click', function () {
                const data = hot.getData(); // Obtener los datos de la tabla

                if (!validarDatos(data)) {
                    alert('Por favor, complete todos los campos obligatorios antes de guardar, los campos obligatorios estan señalados con "*"');
                    return;
                }

                fetch('guardar_datos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ data: data })
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message); // Mensaje del servidor
                    // Redirigir
                    setTimeout(function() {
                        window.location.href = './formatear_json.php?filtro=import';
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            alert('Datos insertados correctamente.');
        } else if (status === 'error') {
            alert('Hubo un error al insertar los datos.');
        }
    });
</script>
        <?php elseif ($filtro == 'export'): ?>
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('tablaExcel');

            // Columnas de tu tabla
            const colHeaders = ['Oid*', 'Cliente*', 'Vehículo/Placa*', 'Tipo de Vehículo', 'BL', 'Destino','Tipo de carga', 'Paletas', 'Cajas', 'Unidades', 'Pedidos por despachar*', 'Fecha estimada de salida*', 'Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'dropdown', 
        source: [<?php echo $clientes_js; ?>], // Opciones del selector de clientes generadas por PHP
        strict: true,
        allowInvalid: false // No permite valores no válidos (fuera de la lista)
    }, // cliente
                { data: 2, type: 'text' }, // vehiculo/placa
                { data: 3, type: 'text' }, // tipo de vehículo
                { data: 4, type: 'text' }, // bl
                { data: 5, type: 'text' }, // destino
                { data: 6, type: 'text' }, // tipo de carga
                { data: 7, type: 'numeric' }, // paletas
                { data: 8, type: 'numeric' }, // cajas
                { data: 9, type: 'numeric' }, // unidades
                { data: 10, type: 'numeric' }, // pedidos en proceso
                { data: 11, type: 'date', dateFormat: 'YYYY-MM-DD' }, // fecha objetivo
                { data: 12, type: 'text' }  // comentario oficina
            ];

            // Configuración de Handsontable
            const hot = new Handsontable(container, {
                data: Handsontable.helper.createEmptySpreadsheetData(1, colHeaders.length), // Datos vacíos con el número de columnas adecuado
                rowHeaders: true,
                colHeaders: colHeaders,
                columns: columns,
                filters: true,
                dropdownMenu: true,
                minSpareRows: 0,
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                stretchH: 'all',
                licenseKey: 'non-commercial-and-evaluation', // Necesario para la versión gratuita
            });

            // Función para validar los datos antes de guardarlos
            function validarDatos(data) {
                for (let i = 0; i < data.length; i++) {
                    const row = data[i];
                    // Validar que las columnas específicas no estén vacías
                    // Columnas: 0 (Oid), 1 (Cliente), 2 (Vehículo/placa), 9 (Pedidos en Proceso), 10 (Fecha estimada de salida)
                    const requiredColumns = [0, 1, 2, 10, 11];
                    for (let j of requiredColumns) {
                        if (row[j] === null || row[j] === '') {
                            return false; // Devuelve falso si encuentra un campo obligatorio vacío
                        }
                    }
                }
                return true; // Devuelve verdadero si todos los campos obligatorios están completos
            }

            // Evento click para guardar datos
            document.getElementById('guardarDatos').addEventListener('click', function () {
                const data = hot.getData(); // Obtener los datos de la tabla

                if (!validarDatos(data)) {
                    alert('Por favor, complete todos los campos obligatorios antes de guardar, los campos obligatorios estan señalados con "*"');
                    return;
                }

                fetch('guardar_datos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ data: data })
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message); // Mensaje del servidor
                    // Redirigir
                    setTimeout(function() {
                        window.location.href = './formatear_json.php?filtro=export';
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
        <?php elseif ($filtro == 'picking'): ?>
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('tablaExcel');
            const customContextMenu = {
        items: {
            'row_above': {name: 'Insertar fila arriba'},
            'row_below': {name: 'Insertar fila abajo'},
            'col_left': {name: 'Insertar columna a la izquierda'},
            'col_right': {name: 'Insertar columna a la derecha'},
            'remove_row': {name: 'Eliminar fila'},
            'remove_col': {name: 'Eliminar columna'},
            'undo': {name: 'Deshacer'},
            'redo': {name: 'Rehacer'},
            'make_read_only': {name: 'Hacer solo lectura'},
            'alignment': {name: 'Alineación'},
            'copy': {name: 'Copiar'},
            'cut': {name: 'Cortar'},
            'clear_column': {name: 'Limpiar columna'},
            'filter_by_condition': {name: 'Filtrar por condición'},
            'filter_by_value': {name: 'Filtrar por valor'},
            'filter_action_bar': {name: 'Barra de acción de filtro'}
        }
    };

            // Columnas de tu tabla
            const colHeaders = ['Oid*', 'Cliente*', 'Paletas', 'Cajas', 'Unidades por pickear*', 'Fecha estimada de salida*', 'Prioridad de Picking*','Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'dropdown', 
        source: [<?php echo $clientes_js; ?>], // Opciones del selector de clientes generadas por PHP
        strict: true,
        allowInvalid: false // No permite valores no válidos (fuera de la lista)
    }, // cliente
                { data: 2, type: 'numeric' }, // paletas
                { data: 3, type: 'numeric' }, // cajas
                { data: 4, type: 'numeric' }, // unidades por pickear
                { data: 6, type: 'date', dateFormat: 'YYYY-MM-DD' }, // fecha objetivo
                { data: 8, type: 'text' },  // comentario oficina
                { data: 7, type: 'text' }  // prioridad de picking
            ];

            // Configuración de Handsontable
            const hot = new Handsontable(container, {
                data: Handsontable.helper.createEmptySpreadsheetData(1, colHeaders.length), // Datos vacíos con el número de columnas adecuado
                rowHeaders: true,
                colHeaders: colHeaders,
                columns: columns,
                filters: true,
                dropdownMenu: true,
                minSpareRows: 0,
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                stretchH: 'all',
                licenseKey: 'non-commercial-and-evaluation', // Necesario para la versión gratuita
            });

            // Función para validar los datos antes de guardarlos
            function validarDatos(data) {
                for (let i = 0; i < data.length; i++) {
                    const row = data[i];
                    // Validar que las columnas específicas no estén vacías
                    // Columnas: 0 (Oid), 1 (Cliente), 2 (Vehículo/placa), 9 (Pedidos en Proceso), 10 (Fecha estimada de salida)
                    const requiredColumns = [0, 1, 4, 6];
                    for (let j of requiredColumns) {
                        if (row[j] === null || row[j] === '') {
                            return false; // Devuelve falso si encuentra un campo obligatorio vacío
                        }
                    }
                }
                return true; // Devuelve verdadero si todos los campos obligatorios están completos
            }

            // Evento click para guardar datos
            document.getElementById('guardarDatos').addEventListener('click', function () {
                const data = hot.getData(); // Obtener los datos de la tabla

                if (!validarDatos(data)) {
                    alert('Por favor, complete todos los campos obligatorios antes de guardar, los campos obligatorios estan señalados con "*"');
                    return;
                }

                fetch('guardar_datos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ data: data })
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message); // Mensaje del servidor
                    // Redirigir
                    setTimeout(function() {
                        window.location.href = './formatear_json.php?filtro=picking';
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
        <?php elseif ($filtro == NULL): ?>
            <h3>Regrese a la pagina de inicio</h3>
        <?php endif; ?>
    

    <script src="../../../host_virtual_TI/js/script.js"></script>
</body>
</html>
