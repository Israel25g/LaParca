<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exports - Daily Plan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.0.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="../../estilos.css">
    <link rel="shortcut icon" href="../../images/ICO.png">

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
            <h3 class="mb-2"><a href="../tabla_ex.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Ingrese los datos para crear el Daily plan de Export</h3>
            <a class="btn btn-success" id="guardarDatos" href="../hoja_ex/formatear_json.php">Guardar datos</a>
         </div>
    
    <div id="tablaExcel" style="margin: 60px;" class="shadow  mb-5 bg-light table-striped "></div>
   

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('tablaExcel');
            
            // Columnas de tu tabla
            const colHeaders = ['Aid/Oid', 'Cliente', 'Vehículo', 'Tipo de Vehículo', 'BL', 'Destino', 'Paletas', 'Cajas', 'Unidades', 'Pedidos en Proceso', 'Fecha estimada de salida', 'Comentario Oficina'];
            
            const columns = [
                { data: 0, type: 'text' }, // aid_oid
                { data: 1, type: 'text' }, // cliente
                { data: 2, type: 'text' }, // vehiculo
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
                data: Handsontable.helper.createEmptySpreadsheetData(1, 14), // 14 filas, 14 columnas
                rowHeaders: true,
                colHeaders: colHeaders,
                columns: columns,
                minSpareRows: 0,
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                stretchH: 'all',
                licenseKey: 'non-commercial-and-evaluation', // Necesario para la versión gratuita
            });

            // Función para guardar los datos en un archivo JSON
            document.getElementById('guardarDatos').addEventListener('click', function () {
                const data = hot.getData(); // Obtener los datos de la tabla

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
