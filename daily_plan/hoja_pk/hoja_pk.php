<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Datos: Export</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../estilos.css">
    <link rel="shortcut icon" href="../../images/ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.js"></script>
</head>
<body style="background-image:url('../images/Motivo2.png');margin: 0;padding: 0; font-family:montserrat;">
    <div class="form-table">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../images/IPL.png" alt="Logo_IPL_Group"></a>
            </div>
            <h1 href="../daily_plan/index_DP.php">Daily plan</h1>
            <div class="cuadroFecha">
                <p id="fecha-actual"></p>
                <p id="hora-actual">prueba</p>
            </div>
        </div>
        <!-- Fin del Header -->
    </div>
    <div style="margin-top: -150px; margin-left: 60px">
        <h3 class="mb-2"><a href="../tabla_pk.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Daily plan de Picking</h3>
        <button class="btn btn-success" id="guardarDatos">Guardar datos</button>
    </div>

    <div id="tablaExcel" style="margin: 60px;"></div>

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
            const colHeaders = ['Oid*', 'Cliente*', 'Vehículo/Placa*', 'Tipo de Vehículo', 'BL', 'Destino', 'Paletas', 'Cajas', 'Unidades', 'Pedidos por despachar*', 'Fecha estimada de salida*', 'Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'text' }, // cliente
                { data: 2, type: 'text' }, // vehiculo/placa
                { data: 3, type: 'text' }, // tipo de vehículo
                { data: 4, type: 'text' }, // bl
                { data: 5, type: 'text' }, // destino
                { data: 6, type: 'numeric' }, // paletas
                { data: 7, type: 'numeric' }, // cajas
                { data: 8, type: 'numeric' }, // unidades
                { data: 9, type: 'numeric' }, // pedidos en proceso
                { data: 10, type: 'date', dateFormat: 'YYYY-MM-DD' }, // fecha objetivo
                { data: 11, type: 'text' }  // comentario oficina
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
                contextMenu: false,
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
                    const requiredColumns = [0, 1, 2, 9, 10];
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
                        window.location.href = '../hoja_pk/formatear_json.php';
                    });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
    <script src="../../host_virtual_TI/js/script.js"></script>
</body>
</html>