<?php

// Construir la URL para solicitar a config_dashPDO.php
$url = "http://localhost/sistema_de_tickets/Dashboards/clientes/saint_g/config_dashPDO.php?conexion=PDO&BaseD=estandar";

// Obtener la respuesta de config_dashPDO.php
$response = file_get_contents($url);
if ($response === false) {
    die("Error al solicitar la configuración de la base de datos.");
}

// Convertir la respuesta JSON en un array PHP (si se devuelve en ese formato)
$config = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error al decodificar la respuesta: " . json_last_error_msg());
}

// Crear conexión PDO usando la configuración obtenida
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], $config['options']);
    // echo "Conexión establecida con éxito.";


} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener parámetros de la URL
$Cliente = isset($_GET['Cliente']) ? $_GET['Cliente'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;

// Validar que los parámetros requeridos están presentes
if (!$fecha_inicio || !$fecha_final) {
   die("No cuenta con los permisos o parametros necesarios");
}

// Consultas SQL
try {
    // Consulta para sumar la columna 'cajas' en la tabla 'picking'
    $stmt1 = $pdo->prepare("
        SELECT SUM(Cajas_Pick) AS suma_caja_pk,
        SUM(Paletas) AS suma_paletas_pk, 
        COUNT(CASE WHEN Liberado IS NOT NULL AND Pickeado IS NULL THEN OID ELSE 0 END) AS suma_pedidos_en_proceso_pk,
        SUM(CASE WHEN Pickeado IS NOT NULL THEN KG ELSE 0 END) AS suma_CBM_finalizado_ex,
        SUM(CASE WHEN Categoria = 'Categoría B' THEN Pend ELSE 0 END) AS suma_CBM_pendiente_ex,
        SUM(Und) AS suma_unidad_pk
        FROM picking
        WHERE CIA = :cliente AND Confirmado >= :fecha_inicio AND Confirmado <= :fecha_final
    ");
    $stmt1->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $suma_caja_pk = $resultado1['suma_caja_pk'] ?? 0;
    $suma_paletas_pk = $resultado1['suma_paletas_pk'] ?? 0;
    $suma_pedidos_en_proceso_pk = $resultado1['suma_pedidos_en_proceso_pk'] ?? 0;
    $suma_unidad_pk = $resultado1['suma_unidad_pk'] ?? 0;

    // Consulta para sumar las columnas 'paletas', 'cajas', y 'unidades' en la tabla 'import'
    $stmt2 = $pdo->prepare("
        SELECT 
        SUM(cajas) AS sum_cajas,
        SUM(paletas) AS sum_paletas, 
        SUM(CBM) AS sum_CBM,
        SUM(SKU) AS sum_SKUs
        FROM imports
        WHERE CIA = :cliente AND ETA >= :fecha_inicio AND ETA <= :fecha_final
    ");
    $stmt2->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $suma_caja_im = $resultado2['sum_cajas'] ?? 0;
    $suma_CBM_im = $resultado2['sum_CBM'] ?? 0;
    $suma_paletas_im = $resultado2['sum_paletas'] ?? 0;
    $suma_SKU_im = $resultado2['sum_SKUs'] ?? 0;

    // Consulta para obtener datos de la tabla 'export'
    $stmt3 = $pdo->prepare("
        SELECT
        COUNT(CASE WHEN Empacado IS NOT NULL THEN OID ELSE 0 END) AS suma_Pedidos_empacados_ex,
        SUM(CASE WHEN Empacado IS NOT NULL THEN Paletas ELSE 0 END) AS suma_paletas_ex,
        SUM(CASE WHEN Empacado IS NOT NULL THEN Cajas ELSE 0 END) AS suma_Cajas_empacadas_ex,
        SUM(CASE WHEN Empacado IS NOT NULL THEN UND_Pick ELSE 0 END) AS suma_UND_Pick_empacadas_ex
        FROM exports
        WHERE CIA = :cliente AND FRD >= :fecha_inicio AND FRD <= :fecha_final
    ");
    $stmt3->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $suma_Cajas_empacadas_ex = $resultado3['suma_Cajas_empacadas_ex'] ?? 0;
    $suma_paletas_ex = $resultado3['suma_paletas_ex'] ?? 0;
    $suma_pedidos_empacados_ex = $resultado3['suma_Pedidos_empacados_ex'] ?? 0;
    $suma_unidad_pickeada_y_empacada_ex = $resultado3['suma_UND_Pick_empacadas_ex'] ?? 0;

    // Imprimir resultados

} catch (PDOException $e) {
    die("Error al ejecutar las consultas: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPL Group</title>
    <link rel="stylesheet" href="../../../estilos.css">
    <link rel="shortcut icon" href="../../../images/ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Incluyendo la librería de ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.0.2/dist/echarts.min.js"></script>
<style>
::-webkit-scrollbar{
  width:0px ;
}

::-webkit-scrollbar-track{
  background: #0000;
}
::-webkit-scrollbar-thumb{
  background: #a77700;
  border-radius: 10px;
  border: #a77700;
}

.custom-card {
    border: 2px solid #61a0a8 !important; /* Equivalente a 'border-primary border-2' */
}
body {
  margin: 0;
  padding: 0;
  background: linear-gradient(20deg, rgba(1, 23, 45, 0.8), rgba(1, 23, 45, 0.2));
}
</style>

</head>

<body >
    <!-- Header -->
    <div class="header" style="background-color: orange !important; box-shadow: -10px 5px 5px #a77700">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <a style=" font-size:xxx-large; margin:auto" href="../../dashboards.php" class="link-offset-2 link-underline link-underline-opacity-0 fw-bold link-light ">Reportes externos: <?php echo($Cliente)?></a>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual">prueba</p>
        </div>
    </div>

    <div class="btn-group" style="margin-top: 140px; z-index: 999; margin-left: 28%; border-radius: 50px 50% 50% 50px; background-color: black; position: fixed">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="btn btn-warning btn-md" aria-current="true" aria-label="Slide 0">Import</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" class="btn btn-warning btn-md" aria-current="true" aria-label="Slide 1">Export</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" class="btn btn-warning btn-md" aria-label="Slide 3">Picking</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" class="btn btn-warning btn-md" aria-label="Slide 4">Detalles Varios</button>
        <button class="btn btn-warning text-dark btn-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#Id2" aria-controls="Id2">Clientes</button>
        <button class="btn btn-primary"type="button"data-bs-toggle="offcanvas"data-bs-target="#Id1" aria-controls="Id1">Tarjeta de datos</button>
        <button class="btn btn-primary  active"type="button"><?php echo($fecha_inicio);echo(" / ");echo($fecha_final)?></button>
    </div>
    <!-- Carrusel -->
    <div id="carouselExampleCaptions" class="carousel slide mt-4" style="margin-bottom: 100px;">
        <div class="carousel-inner" style="margin-top: -70px;">

            <!-- import -->
<div class="carousel-item active ">
    <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!-- Primer gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart1" class="border border-dark border-4 rounded bg-light" style="width: 1500px; max-width: 1200px; height: 600px; background-color:aliceblue"></div>
          </div>
          <!-- Segundo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
                <div id="chart2" class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 800px; height: 600px;background-color:aliceblue"></div>
            </div>
            <!-- Tercer gráfico -->
            <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
                <div id="chart3" class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 800px; height: 600px;background-color:white"></div>
            </div>
            <!-- Cuarto gráfico -->
            <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
                <div id="chart4" class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 800px; height: 600px; background-color:white"></div>
            </div>
          
      </div>
  </div>
</div>
<!-- fin Import -->

<!-- Exports -->
<div class="carousel-item">
    <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!--  Quinto y sexto grafico-->
          <div class="col-12 col-md-6 col-lg-6 d-flex flex-column align-items-center" >
              <div id="chart5" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
              <div id="chart6" class="border border-dark border-4 rounded bg-light mt-1" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
          </div>
          <!-- septimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart7" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px;background-color:white; overflow: hidden;"></div>
          </div>
          <!-- octavo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart8" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px; background-color:aliceblue ; overflow: hidden;"></div>
              </div>
              <!-- noveno gráfico -->
              <div class="col-12 col-md-6 col-lg-6 d-flex flex-column align-items-center">
                  <div id="chart9" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px; background-color: white; overflow: hidden;"></div>
                </div> 
            </div>
                <!-- Decimo grafico y undecimo grafico-->
        <div class="container mt-6">
            <div class="row gy-4 justify-content-center align-items-center" style="margin-top: -20px;">
                <div class="col-12 col-md-12 col-lg-12 d-flex flex-column align-items-center">
                    <div id="chart10" class="border border-dark border-4 rounded bg-light  mt-0" style="width: 1296px; max-width: 1296px; height: 628px; background-color: white; overflow: hidden;"></div>
                    <div id="chart11" class="border border-dark border-4 rounded bg-light  mt-2" style="width: 1296px; max-width: 1296px; height: 628px; background-color: white; overflow: hidden;"></div>
                </div>
            </div>
        </div>
  </div>
</div>
<!-- fin export -->

<!-- picking -->
<div class="carousel-item">
              <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!-- Duodecimo  y Decimotercer grafico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex flex-column align-items-center" >
              <div id="chart12" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
              <div id="chart13" class="border border-dark border-4 rounded bg-light mt-1" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
          </div>
          <!-- Decimo cuarto grafico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart14" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 632px;background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- Decimo quinto grafico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart15" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 632px;background-color:white ; overflow: hidden;"></div>
          </div>
          <!-- Decimo sexto y Decimo semtimo grafico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex flex-column align-items-center" >
              <div id="chart16" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
              <div id="chart17" class="border border-dark border-4 rounded bg-light mt-1" style="width: 628px; max-width: 628px; height: 314px; background-color: white; overflow: hidden;"></div>
          </div>
      </div>
  </div>
</div>
<!-- fin picking -->


<!-- detalles varios -->
<div class="carousel-item">
    <!-- Decimo octavo grafico -->
    <div class="container mt-6">
            <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 160px;">
                <div class="col-12 col-md-12 col-lg-12 d-flex flex-column align-items-center">
                    <div id="chart18" class="border border-dark border-4 rounded bg-light  mt-1" style="width: 1296px; max-width: 1296px; height: 628px; background-color: white; overflow: hidden;"></div>
                </div>
            </div>
        </div>

    <div class="container mt-5">
                  <div class="row gy-4 justify-content-center align-items-center" style="margin-top: -50px;">
          <!-- Decimo noveno grafico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart19" class="border border-dark border-4 rounded bg-light" style="width: 634px; max-width: 634px; height: 600px;background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- Vigesimo grafico-->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart20" class="border border-dark border-4 rounded bg-light" style="width: 634px; max-width: 634px; height: 600px;background-color:white ; overflow: hidden;"></div>
          </div>
            <!-- vigesimo primer grafico -->
          <!-- <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart21" class="border border-dark border-4 rounded bg-light" style="width: 634px; max-width: 634px; height: 600px;background-color:white ; overflow: hidden;"></div>
          </div> -->
      </div>
  </div>
</div>
<!-- fin detalles varios -->

</div>
</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-start"  tabindex="-1" id="Id2" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="staticBackdropLabel">Clientes - IPL Group</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Consulta Personalizada</h2>
            <form id="filterForm" method="GET">
                <!-- Campo Fecha de Inicio -->
                <div class="mb-3">
                    <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                    <input type="date" id="fechaInicio" name="fecha_inicio" class="form-control" >
                </div>
                <!-- Campo Fecha de Final -->
                <div class="mb-3">
                    <label for="fechaFinal" class="form-label">Fecha Final:</label>
                    <input type="date" id="fechaFinal" name="fecha_final" class="form-control" >
                </div>
                <!-- Campo Cliente -->
                <div class="mb-3">
                    <label for="Cliente" class="form-label">Cliente:</label>
                    <input type="text" id="Cliente" name="Cliente" class="form-control" placeholder="<?php $Cliente ?>" >
                </div>
                <!-- Botón para aplicar los filtros -->
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            </form>
        </div>
    </div>
</div>


<div
  class="offcanvas offcanvas-end"
  data-bs-scroll="true"
  tabindex="-1"
  id="Id1"
  aria-labelledby="Enable both scrolling & backdrop"
>
  <div class="offcanvas-header">
    <h4 class="offcanvas-title" id="Enable both scrolling & backdrop">
      Tarjeta de datos
    </h4>
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="offcanvas"
      aria-label="Close"
    ></button>
  </div>
  <div class="offcanvas-body">
    <!-- Categorías de Tarjetas -->
    <div class="card-category mb-4">
        <h2 style="color: #61a0a8">Import</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $importData = [
                "Cajas totales" => $suma_caja_im,
                "Paletas totales" => $suma_paletas_im,
                "Total de SKU recibidos" => $suma_SKU_im,
                "CBM total recibido" => $suma_CBM_im
            ];
            foreach ($importData as $titulo => $valor) {
                echo "
                <div class='col'>
                    <div class='custom-card card h-100' style='border: 2px solid #61a0a8 !important;'>
                        <div class='card-body'>
                            <h6 class='card-title'>$titulo</h6>
                            <p class='card-text fs-4'>$valor</p>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
<hr/>
    <div class="card-category mb-4">
        <h2 style="color: #ca8622">Export</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $exportData = [
                "Cajas Empacadas" => $suma_Cajas_empacadas_ex,
                "Paletas Exportadas, empacadas" => $suma_paletas_ex,
                "Unidades exportadas y empacadas" => $suma_unidad_pickeada_y_empacada_ex,
                "Pedidos empacados" => $suma_pedidos_empacados_ex
            ];
            foreach ($exportData as $titulo => $valor) {
                echo "
                <div class='col'>
                    <div class='custom-card card h-100' style='border: 2px solid #ca8622 !important;'>
                        <div class='card-body'>
                            <h6 class='card-title'>$titulo</h6>
                            <p class='card-text fs-4'>$valor</p>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
    <hr/>
    <div class="card-category mb-4">
        <h2 style="color: #91c7ae">Picking</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $pickingData = [
                "Cajas Pickeadas" => $suma_caja_pk,
                "Paletas registradas" => $suma_paletas_pk,
                "unidades pickeadas" => $suma_unidad_pk,
                "Pedidos abiertos" => $suma_pedidos_en_proceso_pk
            ];
            foreach ($pickingData as $titulo => $valor) {
                echo "
                <div class='col'>
                    <div class='custom-card card h-100' style='border: 2px solid #91c7ae !important;'>
                        <div class='card-body'>
                            <h6 class='card-title'>$titulo</h6>
                            <p class='card-text fs-4'>$valor</p>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
    <hr/>
    <!-- Resumen con Totales -->
</div>


</div>


<!-- Scripts -->
<script src="../../../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    // Leer los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const fechaInicio = urlParams.get('fecha_inicio') || '';
    const fechaFinal = urlParams.get('fecha_final') || '';
    const Cliente = urlParams.get('Cliente') || '';

    // Asignar los valores de los parámetros al formulario
    document.getElementById('fechaInicio').value = fechaInicio;
    document.getElementById('fechaFinal').value = fechaFinal;
    document.getElementById('Cliente').value = Cliente;

    // Función para obtener datos desde el servidor
    async function fetchData(endpoint) {
        const response = await fetch(endpoint);
        if (!response.ok) {
            console.error("Error al obtener los datos:", response.statusText);
            return {};
        }
        return await response.json();
    }
    function createBarChart_multiseries(containerId, chartData1, chartData2, chartData3, chartData4, title) {
    const chart = echarts.init(document.getElementById(containerId));

        // Calculate dynamic max values for yAxes
    const maxY1 = Math.max(...chartData1.map(item => Math.max(...item.value)));
    const maxY2 = Math.max(...chartData2.map(item => Math.max(...item.value)));
    const maxY3 = Math.max(...chartData3.map(item => Math.max(...item.value)));
    const maxY4 = Math.max(...chartData4.map(item => Math.max(...item.value)));

    const options = {
        grid: {
            left: '10%',
            right: '10%',
            top: '20%',
            bottom: '10%',
            containLabel: true
        },
        title: { 
            text: title, 
            left: '0%', 
            textStyle: { fontSize: 14 } 
        },
        tooltip: { 
            trigger: 'axis', 
            axisPointer: { type: 'shadow' } 
        },
        xAxis: { 
            type: 'category', 
            data: chartData1.map(item => item.name), 
            axisLabel: { 
                fontSize: "12px", 
                rotate: 90 // Rotación condicional
            } 
        },
        yAxis: [
            {
                type: 'value',
                alignTicks: true,
                axisLabel: { formatter: '{value}' },
                max:maxY2+100,
            },
            {
                type: 'value',
                alignTicks: true,
                position: 'right',
                axisLabel: { formatter: '{value}' },
                max: ((maxY1+maxY2+maxY3+maxY4)+100)*1.5,
                splitLine: { show: false }
            }
        ],
        series: [
            {
                data: chartData1.map(item => item.value[0]),
                type: 'bar',
                name: 'Paletas recibidas',
                itemStyle: { color: '#61a0a8' },
                yAxisIndex: 0, // Asociado al primer eje Y
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx * 100;}
            },
            {
                data: chartData2.map(item => item.value[0]),
                type: 'line',
                name: 'Cajas recibidas',
                itemStyle: { color: '#c23531' },
                yAxisIndex: 0, // Asociado al primer eje Y
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx * 100;}
            },
            {
                data: chartData3.map(item => item.value[0]),
                type: 'line',
                name: 'KG totales',
                itemStyle: { color: '#ca8622' },
                yAxisIndex: 1, // Asociado al segundo eje Y
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx * 100;}
            },
            {
                data: chartData4.map(item => item.value[0]),
                type: 'line',
                name: 'CBM totales',
                itemStyle: { color: '#2f4554' },
                yAxisIndex: 1, // Asociado al segundo eje Y
                animationDelay: function(idx) { return idx * 100;}
            }
        ],
        toolbox: {
        right: '1%',
        orient: 'vertical',
        feature: {
            restore: { show: true },
            saveAsImage: { show: true },
            dataZoom: { show: true },
            magicType: { type: ['line', 'bar','stack'] },
            dataView: { show: true, readOnly: true }
        }
    },
        legend: {
            type: 'scroll',
            right: 150,
            top: 20,
            bottom: 0
        },
    };
    chart.setOption(options);
}


function createBarChart(containerId, chartData1,chartData2,chartData3,title) {
    const chart = echarts.init(document.getElementById(containerId));

    // Calculate dynamic max values for yAxes

    const maxY1 = Math.max(...chartData1.map(item => Math.max(...item.value)));
    const maxY2 = Math.max(...chartData2.map(item => Math.max(...item.value)));
    const maxY3 = Math.max(...chartData3.map(item => Math.max(...item.value)));



    const options = {
        grid: {
            left: '10%',
            right: '10%',
            top: '20%',
            bottom: '10%',
            containLabel: true
        },
        title: { 
            text: title, 
            left: '0%', 
            textStyle: { fontSize: 14 } 
        },
        tooltip: { 
            trigger: 'axis', 
            axisPointer: { type: 'shadow' } 
        },
        xAxis: { 
            type: 'category', 
            data: chartData1.map(item => item.name), 
            axisLabel: { 
                fontSize: "12px", 
                rotate: 90 
            } 
        },
        yAxis: {
            type: 'value',
            axisLabel: { formatter: '{value}' },
            max:( maxY1+maxY2+maxY3)+1000
        },
        
        series: [
            {
                data: chartData1.map(item => item.value[0]),
                type: 'bar',
                name: 'Grande',
                itemStyle: { color: '#91c7ae' },
                stack: 'Total', // Se agrega propiedad para apilar
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx *12;}
            },
            {
                data: chartData2.map(item => item.value[0]),
                type: 'bar',
                name: 'Mediano',
                itemStyle: { color: '#2f4554' },
                stack: 'Total', // Se agrega propiedad para apilar
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx * 48;}
            },
            {
                data: chartData3.map(item => item.value[0]),
                type: 'bar',
                name: 'Pequeño',
                itemStyle: { color: '#ca8622' },
                stack: 'Total', // Se agrega propiedad para apilar
                emphasis: {focus: 'series'},
                animationDelay: function(idx) { return idx * 60;}
            },
        ],
        toolbox: {
        right: '1%',
        orient: 'vertical',
        feature: {
            restore: { show: true },
            saveAsImage: { show: true },
            dataZoom: { show: true },
            magicType: { type: ['line', 'bar','stack','scatter'] },
            dataView: { show: true, readOnly: true }
        }
    },
        legend: {
            type: 'scroll',
            right: 350,
            top: 20,
            bottom: 0
        },
    };

    chart.setOption(options);
}

function createBar_dinamic(containerId, chartData1, title) {
    const chart = echarts.init(document.getElementById(containerId));

    // Calcular el valor máximo dinámico para el eje Y si lo deseas (aunque no está incluido en el ejemplo)
    // ...

    // Configurar las opciones del gráfico
    const options = {
        grid: { left: '10%', right: '10%', top: '25%', bottom: '0%', containLabel: true },
        title: { text: title, left: '0%' },
        tooltip: { trigger: 'axis', axisPointer: { type: 'cross' } },
        xAxis: { type: 'category', data: chartData1.categories, axisLabel: { fontSize: "12px", rotate: 25 } },
        yAxis: { type: 'value' },
        series: chartData1.series,
        legend: { type: 'scroll', top: '7%', right: '20%' },
        toolbox: {
        right: '1%',
        orient: 'vertical',
        feature: {
            restore: { show: true },
            saveAsImage: { show: true },
            dataZoom: { show: true },
            dataView: { show: true, readOnly: true }
        }
    }, // Usamos la configuración de toolbox que se recibe como parámetro
    };

    // Establecer las opciones en el gráfico
    chart.setOption(options);
}


function createBar_dinamic_XL_size(containerId, chartData1, title) {
    const chart = echarts.init(document.getElementById(containerId));

    // Calcular el valor máximo dinámico para el eje Y si lo deseas (aunque no está incluido en el ejemplo)
    // ...

    // Configurar las opciones del gráfico
    const options = {
        grid: { left: '10%', right: '10%', top: '25%', bottom: '0%', containLabel: true },
        title: { text: title, left: '0%' },
        tooltip: { trigger: 'axis', axisPointer: { type: 'cross' } },
        xAxis: { type: 'category', data: chartData1.categories, axisLabel: { fontSize: "15px", rotate: 25 } },
        yAxis: { type: 'value' },
        series: chartData1.series,
        legend: { type: 'scroll', top: '7%', right: '28%' },
        toolbox: {
        right: '1%',
        orient: 'vertical',
        feature: {
            restore: { show: true },
            saveAsImage: { show: true },
            dataZoom: { show: true },
            magicType: { type: ['line', 'bar','stack','scatter'] },
            dataView: { show: true, readOnly: true }
        }
    }, // Usamos la configuración de toolbox que se recibe como parámetro
    };

    // Establecer las opciones en el gráfico
    chart.setOption(options);
}



// Función para gráficos de línea
function createLineChart(containerId, chartData1,chartData2, title) {
    const chart = echarts.init(document.getElementById(containerId));
    const options = {
        title: { text: title, left: '0%' },

        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' }, },
        xAxis: { type: 'category', data: chartData1.map(item => item.name), axisLabel: { fontSize: "12px", rotate: 89 } },
        yAxis: { type: 'value' },
        series: [
            {
                data: chartData1.map(item => item.value[1]),
                type: 'line',
                name: title,
                stack: 'Total', // Se agrega propiedad para apilar
                emphasis: {focus: 'series'},
                smooth: true,

                color: [
            '#ca8622',
            '#61a0a8',
            '#c23531',
            '#2f4554',
            '#d48265',
            '#91c7ae',
            '#749f83',
            '#6e7074',
            '#546570',
            '#c4ccd3'],
            },
            {
                data: chartData2.map(item => item.value[1]),
                type: 'line',
                name: title,
                emphasis: {focus: 'series'},
                smooth: true,
                color: [
            '#546570',
            '#ca8622',
            '#61a0a8',
            '#c23531',
            '#2f4554',
            '#d48265',
            '#91c7ae',
            '#749f83',
            '#bda29a',
            '#6e7074',
            '#c4ccd3'
  ],
            }
        ],
        toolbox: {
            feature: {
                dataZoom: { yAxisIndex: 'none' },
                magicType: { type: ['line', 'bar'] },
                restore: { show: true },
                saveAsImage: { show: true },
                dataView: { show: true, readOnly: true }
            }
        },
    };
    chart.setOption(options);
}

function createPieChart(containerId, chartData, title, rad1, rad2) {
    const chart = echarts.init(document.getElementById(containerId));

    // Validar datos
    if (!chartData || chartData.length === 0) {
        chart.showLoading({ text: 'No data available' });
        return;
    }

    const options = {
        title: { 
            text: title, 
            left: '0%' 
        },
        color: [
            '#546570', '#ca8622', '#61a0a8', '#c23531', '#2f4554', 
            '#d48265', '#91c7ae', '#749f83', '#bda29a', '#6e7074', 
            '#c4ccd3'
        ],
        legend: {
            top: '5%',
            left: 'center'
        },
        tooltip: { trigger: 'item' },
        series: [
            {
                type: 'pie',
                radius: [rad1, rad2],
                animationDelay: idx => idx * 200,
                data: chartData.map(item => ({
                    value: item.value,
                    name: item.name
                })),
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ],
        toolbox: {
            feature: {
                saveAsImage: { show: true },
                dataView: { show: true, readOnly: true }
            }
        }
    };

    chart.setOption(options);
}


// Función para gráficos de dispersión
function createScatterChart(containerId, chartData, title) {
    const chart = echarts.init(document.getElementById(containerId));
    const options = {
        title: { text: title, left: '0%' },
        color: [
            '#6e7074',
            '#546570',
            '#ca8622',
            '#61a0a8',
            '#c23531',
            '#2f4554',
            '#d48265',
            '#91c7ae',
            '#749f83',
            '#bda29a',
            '#c4ccd3'
  ],
        tooltip: { trigger: 'axis', axisPointer: { type: 'cross' } },
        xAxis: { type: 'value' },
        yAxis: { type: 'value' },
        series: [
            {
                data: chartData.map(item => item.value),
                type: 'scatter',
                name: title,
                animationDelay: function(idx) { return idx * 100;}
            }
        ],
        toolbox: {
            feature: {
                saveAsImage: { show: true },
                dataView: { show: true, readOnly: true }
            }
        }
    };
    chart.setOption(options);
}

function createTreemapChart(containerId, chartData, title) {
    const chart = echarts.init(document.getElementById(containerId));
    
    const options = {
        title: { text: title, left: 'center' },
        tooltip: { 
            trigger: 'item',
            formatter: (params) => 
                `${params.name}<br/>Valor: ${params.value}<br/>Unidades: ${params.data?.extra?.unidades ?? 'N/A'}`
        },
        series: [
            {
                type: 'treemap',
                data: chartData, // Datos jerárquicos
                roam: true, // Permite desplazarse y hacer zoom
                label: {
                    show: true,
                    formatter: '{b}' // Mostrar solo el nombre del nodo
                },
                breadcrumb: {
                    show: true // Mostrar la navegación jerárquica
                },
                itemStyle: {
                    borderColor: '#fff',
                    borderWidth: 2,
                    gapWidth: 1
                }
            }
        ],
        toolbox: {
            feature: {
                saveAsImage: { show: true },
                dataView: { show: true, readOnly: true }
            }
        }
    };

    chart.setOption(options);
}

    // Función para cargar y renderizar los gráficos
    async function loadCharts() {
        // Construir la URL con las variables persistentes
        const endpoint = `get_data.php?fecha_inicio=${fechaInicio}&fecha_final=${fechaFinal}&Cliente=${Cliente}`;
        const data = await fetchData(endpoint);

        if (!data) {
        console.error("No se recibieron datos válidos del servidor.");
        return;
    }

    // Configurar cada gráfico usando los datos recibidos

    // import
    createBarChart_multiseries('chart1', data.total_paletas_Recibidas,data.total_cajas,data.total_KG,data.total_CBM,'1.CAJAS/CBM/KG/PALETAS MENSUALES'); 
    createBarChart('chart2', data.total_grande, data.total_mediano, data.total_pequeño,'2.Tamaño y cantidad de unidades por dia');
    createBar_dinamic('chart3', data.chart3,'3.Pedidos recibidos por Veículo');
    createPieChart('chart4', data.chart4, '4.Embarques totales recibidos','40%','60%');

    // import

    // export
    createBar_dinamic('chart5', data.chart5, '5.Paletas empacadas por país');
    createBar_dinamic('chart6', data.chart6, '6.Paletas Despachadas VS pendientes de despacho');
    createBar_dinamic('chart7', data.chart7, '7.Pedidos empacados VS Cajas empacadas por País');
    createBar_dinamic('chart8', data.chart8, '8.Proceso de empoaque por fecha y pedido (Cajas y paletas)');
    createBar_dinamic('chart9', data.chart9, '9.Proceso de empoaque por fecha y pedido (CBM y KG)');
    createBar_dinamic_XL_size('chart10', data.chart10, '10.Pedidos empacados Vs Despachados');
    createBar_dinamic_XL_size('chart11', data.chart11,'11.Paletas empacadas Vs despachadas');
    // export
    
    // picking
    createBar_dinamic('chart12', data.chart12,'12. Pedidos trabajados por día');
    createBar_dinamic('chart13', data.chart13, '13.SKU"s seleccionado por día');
    createBar_dinamic('chart14', data.chart14, '14.Ubicaciones recorridas por día');
    createBar_dinamic('chart15', data.chart15, '15.Piezas seleccionadas por día');
    createBar_dinamic('chart16', data.chart16, '16.Categorias más frecuantes');
    createBar_dinamic('chart17', data.chart17, '17.Ranking de Operadores');
    // picking

    // varios
    createBar_dinamic('chart18', data.chart18, '18.Top Clientes(cajas despachadas)');
    createBar_dinamic('chart19', data.chart19, '19.Paletas pendientes por despacho');
    createPieChart('chart20', data.chart20, '20.Vias de despacho por equipos','40%','60%');
    // varios
    

    // grafico de prueba
    // createTreemapChart('chart21',data.chatr21,'21. grafico de prueba');
    // grafioc de prueba
}
    

    // Cargar los gráficos al inicio
    loadCharts();

    
    // Capturar el evento de envío del formulario
    document.getElementById('filterForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        
        // Obtener los valores de los campos
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFinal = document.getElementById('fechaFinal').value;
        const Cliente = document.getElementById('Cliente').value;
        
        // Recargar la página con los nuevos parámetros en la URL
        window.location.href = `?fecha_inicio=${fechaInicio}&fecha_final=${fechaFinal}&Cliente=${Cliente}`;
        console.log(data.chart3);
    });
    
    document.write(<?php $fechaInicio; $fechaFinal; $Cliente;?>)
</script>


</body>
</html>