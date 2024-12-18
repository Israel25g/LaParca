<?php

// Incluir la configuración
$config = include './config_dash.php'; 

// Crear conexión PDO usando la configuración
try {
    $dbConfig = $config['db'];
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], $dbConfig['options']);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener parámetros de la URL
$Cliente = isset($_GET['Cliente']) ? $_GET['Cliente'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;

// Validar que los parámetros requeridos están presentes
if (!$Cliente || !$fecha_inicio || !$fecha_final) {
    die("No cuenta con los parametros necesarios para acceder a este recurso.");
}

// Consultas SQL
try {
    // Consulta para sumar la columna 'cajas' en la tabla 'picking'
    $stmt1 = $pdo->prepare("
        SELECT SUM(piezas) AS suma_caja_pk,
          SUM(Paletas) AS suma_paletas_pk, 
          SUM(CBM) AS suma_pedidos_en_proceso_pk,
          SUM(KG) AS suma_unidad_pk
        FROM picking
        WHERE CIA = :cliente AND EJE >= :fecha_inicio AND EJE <= :fecha_final
    ");
    $stmt1->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $suma_caja_pk = $resultado1['suma_caja_pk'] ?? 0;
    $suma_paletas_pk = $resultado1['suma_paletas_pk'] ?? 0;
    $suma_pedidos_en_proceso_pk = $resultado1['suma_pedidos_en_proceso_pk'] ?? 0;
    $suma_unidad_pk = $resultado1['suma_unidad_pk'] ?? 0;

    // Consulta para sumar las columnas 'paletas', 'cajas', y 'unidades' en la tabla 'import'
    $stmt2 = $pdo->prepare("
        SELECT cajas,
          paletas, 
          und_recibidas,
          CBM
        FROM imports
        WHERE CIA = :cliente AND EJE >= :fecha_inicio AND EJE <= :fecha_final
    ");
    $stmt2->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $suma_caja_im = $resultado2['paletas'] ?? 0;
    $suma_paletas_im = $resultado2['und_recibidas'] ?? 0;
    $suma_pedidos_en_proceso_im = $resultado2['CBM'] ?? 0;
    $suma_unidad_im = $resultado2['cajas'] ?? 0;

    // Consulta para sumar la columna 'pedidos_en_proceso' en la tabla 'export'
    $stmt3 = $pdo->prepare("
        SELECT SUM(Piezas) AS suma_caja_ex,
          SUM(paletas) AS suma_paletas_ex, 
          SUM(CBM) AS suma_pedidos_en_proceso_ex,
          SUM(KG) AS suma_unidad_ex
        FROM exports
        WHERE CIA = :cliente AND EJE >= :fecha_inicio AND EJE <= :fecha_final
    ");
    $stmt3->execute(['cliente' => $Cliente, 'fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final]);
    $resultado3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $suma_caja_ex = $resultado3['suma_caja_ex'] ?? 0;
    $suma_paletas_ex = $resultado3['suma_paletas_ex'] ?? 0;
    $suma_pedidos_en_proceso_ex = $resultado3['suma_pedidos_en_proceso_ex'] ?? 0;
    $suma_unidad_ex = $resultado3['suma_unidad_ex'] ?? 0;

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
</style>

</head>

<body style="background-color:lightslategray">
    <!-- Header -->
    <div class="header" style="background-color: orange !important; box-shadow: -10px 5px 5px #a77700">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <a style=" font-size:xxx-large; margin:auto" href="../../dashboard_extern.php" class="link-offset-2 link-underline link-underline-opacity-0 fw-bold link-light ">Reportes externos: <?php echo($Cliente)?></a>
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

<div class="carousel-item">
              <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!--  Quinto gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center" >
              <div id="chart5" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px; background-color:aliceblue; overflow: hidden;"></div>
          </div>
          <!-- Sexto gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart6" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px;background-color:aliceblue; overflow: hidden;"></div>
          </div>
          <!-- septimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart7" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px;background-color:white; overflow: hidden;"></div>
          </div>
          <!-- octavo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart8" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 628px; background-color:white; overflow: hidden;"></div>
          </div>
      </div>
  </div>
</div>
<div class="carousel-item">
              <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!-- noveno gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart9" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px; background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- decimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart10" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- undecimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart11" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:white ; overflow: hidden;"></div>
          </div>
          <!-- duodecimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart12" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px; background-color:white ; overflow: hidden;"></div>
          </div>
      </div>
  </div>
</div>
<div class="carousel-item">
              <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!-- decimo tercero gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart13" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px; background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- decimocuarto gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart14" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:aliceblue ; overflow: hidden;"></div>
          </div>
          <!-- decimoquinto gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart15" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:white ; overflow: hidden;"></div>
          </div>
          <!-- decimo sexto gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart16" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px; background-color:white ; overflow: hidden;"></div>
          </div>
      </div>
  </div>
</div>
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
        <h2 class="text-primary">Import</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $importData = [
                "Cajas totales" => $suma_caja_im,
                "Paletas totales" => $suma_paletas_im,
                "Unidades totales" => $suma_unidad_im,
                "Pedidos totales" => $suma_pedidos_en_proceso_im
            ];
            foreach ($importData as $titulo => $valor) {
                echo "
                <div class='col'>
                    <div class='card h-100 border-primary border-2'>
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
        <h2 class="text-success">Export</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $exportData = [
                "Cajas Exportadas" => $suma_caja_ex,
                "Paletas Exportadas" => $suma_paletas_ex,
                "Unidades Exportadas" => $suma_unidad_ex,
                "Exportaciones abiertas" => $suma_pedidos_en_proceso_ex
            ];
            foreach ($exportData as $titulo => $valor) {
                echo "
                <div class='col'>
                    <div class='card h-100 border-success border-2'>
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
        <h2 class="text-warning">Picking</h2>
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
                    <div class='card h-100 border-warning border-2'>
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
// Función para gráficos de barra
function createBarChart_multiseries(containerId, chartData1, chartData2, chartData3, chartData4, chartData5, chartData6, title) {
    const chart = echarts.init(document.getElementById(containerId));
    const options = {
        
        title: { text: title, left: '0%' },

        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        xAxis: { type: 'category', bottom:'100%', data: chartData1.map(item => item.name), axisLabel: { fontSize: "12px", rotate: 89 } },
        yAxis: { type: 'value' },
        series: [
            {
                data: chartData1.map(item => item.value[1]),
                type: 'bar',
                name: 'Unidades recibidas',
                color: [
            '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#6e7074',
    '#546570',
    '#c4ccd3'
  ],
            },
            {
                data: chartData2.map(item => item.value[1]),
                type: 'bar',
                name: 'unidades esperadas',
                color: [
    '#c4ccd3',
    '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#6e7074',
    '#546570',
  ],
            },            {
                data: chartData3.map(item => item.value[1]),
                type: 'bar',
                name: 'total de cajas',
                color: [
                    '#ca8622',
            '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#bda29a',
    '#6e7074',
    '#546570',
    '#c4ccd3'
  ],
            },
            {
                data: chartData4.map(item => item.value[1]),
                type: 'bar',
                name: 'KG totales',
                color: [
                    '#2f4554',
    '#c4ccd3',
    '#61a0a8',
    '#c23531',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#6e7074',
    '#546570',
  ],
            },            {
                data: chartData5.map(item => item.value[1]),
                type: 'line',
                name: 'CBM totales',
                color: [
                    '#6e7074',
            '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#546570',
    '#c4ccd3'
  ],
            },
            {
                data: chartData6.map(item => item.value[1]),
                type: 'line',
                name: 'SKUs totales',
                color: [
                    '#ca8622',
    '#c4ccd3',
    '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#bda29a',
    '#6e7074',
    '#546570',
  ],
            },          
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
        legend:{
            type: 'scroll',
    // orient: 'vertical',
    right: 10,
    top: 20,
    bottom: 20,
        },
    };
    chart.setOption(options);
}


// Función para gráficos de barra
function createBarChart(containerId, chartData1, chartData2, title) {
    const chart = echarts.init(document.getElementById(containerId));
    const options = {
        
        title: { text: title, left: '0%' },

        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        xAxis: { type: 'category', bottom:'100%', data: chartData1.map(item => item.name), axisLabel: { fontSize: "12px", rotate: 89 } },
        yAxis: { type: 'value' },
        series: [
            {
                data: chartData1.map(item => item.value[1]),
                type: 'bar',
                name: title,
                color: [
            '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#6e7074',
    '#546570',
    '#c4ccd3'
  ],
            },
            {
                data: chartData2.map(item => item.value[1]),
                type: 'line',
                name: title,
                color: [
    '#c4ccd3',
    '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#ca8622',
    '#bda29a',
    '#6e7074',
    '#546570',
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
        }
    };
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
                smooth: true,
                areaStyle: {},
                color: [
            '#ca8622',
            '#61a0a8',
    '#c23531',
    '#2f4554',
    '#d48265',
    '#91c7ae',
    '#749f83',
    '#bda29a',
    '#6e7074',
    '#546570',
    '#c4ccd3'
  ],
            },
            {
                data: chartData2.map(item => item.value[1]),
                type: 'line',
                name: title,
                smooth: true,
                areaStyle: {},
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
        }
    };
    chart.setOption(options);
}

// Función para gráficos de pastel
function createPieChart(containerId, chartData, title,rad1,rad2) {
    const chart = echarts.init(document.getElementById(containerId));
    const options = {
        title: { text: title, left: '0%' },
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
        legend: {
            top: '5%',
            left: 'center'
          },
        tooltip: { trigger: 'item' },
        series: [
            {
                type: 'pie',
                startAngle: 180,
                endAngle: 4,
                radius: [rad1, rad2],
                data: chartData.map(item => ({
                    value: item.value[1],
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
                name: title
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
    createBarChart_multiseries('chart1', data.chart1,data.line1,data.line2,data.line3,data.line4,data.line5, 'Gráfico 1: Clientes y Unidades');
    createBarChart('chart2', data.chart2,data.chart3, 'Gráfico 2: Destinos y Paletas');
    createBarChart('chart3', data.chart3,data.chart4, 'Gráfico 3: Clientes y Cajas');
    createPieChart('chart4', data.chart4, 'Gráfico 4: Embarques totales recibidos','40%','60%');

    // import

    // export
    createPieChart('chart5', data.chart5, 'Gráfico 5: Clientes y Unidades','40%','60%');
    createPieChart('chart6', data.chart6, 'Gráfico 6: Destinos y Paletas','50%','60%');
    createPieChart('chart7', data.chart7, 'Gráfico 7: Clientes y Cajas','20%','30%');
    createPieChart('chart8', data.chart8, 'Gráfico 8: Repetición de Clientes','40%','60%');
    // export

    // picking
    createLineChart('chart9', data.chart9, data.line9, 'Gráfico 9: Clientes y Unidades');
    createLineChart('chart10', data.chart10, data.line9, 'Gráfico 10: Destinos y Paletas');
    createLineChart('chart11', data.chart11, data.line9, 'Gráfico 11: Clientes y Cajas');
    createLineChart('chart12', data.chart12, data.line9,'Gráfico 12: Repetición de Clientes');
    // picking

    // varios
    createScatterChart('chart13', data.chart13, 'Gráfico 13: Clientes y Unidades');
    createLineChart('chart14', data.chart14, 'Gráfico 14: Destinos y Paletas ');
    createPieChart('chart15', data.chart15, 'Gráfico 15: Clientes y Cajas','40%','60%');
    createBarChart('chart16', data.chart16, 'Gráfico 16: Repetición de Clientes');
    // varios
    
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
    });
    
    document.write(<?php $fechaInicio; $fechaFinal; $Cliente;?>)
</script>


</body>
</html>