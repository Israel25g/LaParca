<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPL Group-Saint Gobain</title>
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

<body style="background-color:lightgrey">
    <!-- Header -->
    <div class="header" style="background-color: orange !important; box-shadow: -10px 5px 5px #a77700">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/Salida2.gif" alt="Logo_IPL_Group"></a>
        </div>
        <h1 style="margin-left: 35% !important;">Reportes externos</h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual">prueba</p>
        </div>
    </div>

    <div class="btn-group" style="margin-top: 143px; z-index: 999; margin-left: 38%; border-radius: 50px 50% 50% 50px; background-color: black; position: fixed">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="btn btn-warning active btn-md" aria-current="true" aria-label="Slide 0">Import</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" class="btn btn-warning btn-md" aria-current="true" aria-label="Slide 1">Export</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" class="btn btn-warning btn-md" aria-label="Slide 3">Picking</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" class="btn btn-warning btn-md" aria-label="Slide 4">Detalles Varios</button>
        <button class="btn btn-warning text-dark btn-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#Id2" aria-controls="Id2">Clientes</button>
    </div>

    <!-- Carrusel -->
    <div id="carouselExampleCaptions" class="carousel slide mt-4" style="margin-bottom: 100px;">
        <div class="carousel-inner" style="margin-top: -70px;">
              <div class="carousel-item active ">
              <div class="container mt-5">
      <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
          <!-- Primer gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart1" class="border border-dark border-4 rounded bg-light" style="width: 1200px; max-width: 1200px; height: 600px; background-color:aliceblue"></div>
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
              <div id="chart6" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:aliceblue; overflow: hidden;"></div>
          </div>
          <!-- septimo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart7" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px;background-color:white; overflow: hidden;"></div>
          </div>
          <!-- octavo gráfico -->
          <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
              <div id="chart8" class="border border-dark border-4 rounded bg-light" style="width: 628px; max-width: 628px; height: 600px; background-color:white; overflow: hidden;"></div>
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
<div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="Id2" aria-labelledby="staticBackdropLabel">
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
                    <input type="date" id="fechaInicio" name="fecha_inicio" class="form-control" required>
                </div>
                <!-- Campo Fecha de Final -->
                <div class="mb-3">
                    <label for="fechaFinal" class="form-label">Fecha Final:</label>
                    <input type="date" id="fechaFinal" name="fecha_final" class="form-control" required>
                </div>
                <!-- Campo Cliente -->
                <div class="mb-3">
                    <label for="cliente" class="form-label">Cliente:</label>
                    <input type="text" id="cliente" name="cliente" class="form-control" placeholder="Ingrese el cliente" >
                </div>
                <!-- Botón para aplicar los filtros -->
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            </form>
        </div>
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
    const cliente = urlParams.get('cliente') || '';

    // Asignar los valores de los parámetros al formulario
    document.getElementById('fechaInicio').value = fechaInicio;
    document.getElementById('fechaFinal').value = fechaFinal;
    document.getElementById('cliente').value = cliente;

    // Función para obtener datos desde el servidor
    async function fetchData(endpoint) {
        const response = await fetch(endpoint);
        if (!response.ok) {
            console.error("Error al obtener los datos:", response.statusText);
            return {};
        }
        return await response.json();
    }

    // Función para inicializar un gráfico dinámico con toolbox y tooltip
    function initChart(containerId, chartData, title) {
        const chart = echarts.init(document.getElementById(containerId));
        const options = {
            title: { text: title, left: '0%' },
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
            xAxis: { type: 'category', data: chartData.map(item => item.name), axisLabel: { fontSize: "12px", rotate: 90 } },
            yAxis: { type: 'value' },
            series: [{ data: chartData.map(item => item.value[1]), type: 'bar', name: title, areaStyle: {} }],
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

    // Función para cargar y renderizar los gráficos
    async function loadCharts() {
        // Construir la URL con las variables persistentes
        const endpoint = `get_data.php?fecha_inicio=${fechaInicio}&fecha_final=${fechaFinal}&cliente=${cliente}`;
        const data = await fetchData(endpoint);

        if (!data) {
        console.error("No se recibieron datos válidos del servidor.");
        return;
    }

    // Configurar cada gráfico usando los datos recibidos

    // import
    initChart('chart1', data.chart1, 'Gráfico 1: Clientes y Unidades');
    initChart('chart2', data.chart2, 'Gráfico 2: Destinos y Paletas');
    initChart('chart3', data.chart3, 'Gráfico 3: Clientes y Cajas');
    initChart('chart4', data.chart4, 'Gráfico 4: Repetición de Clientes ');
    // import

    // export
    initChart('chart5', data.chart5, 'Gráfico 5: Clientes y Unidades');
    initChart('chart6', data.chart6, 'Gráfico 6: Destinos y Paletas ');
    initChart('chart7', data.chart7, 'Gráfico 7: Clientes y Cajas ');
    initChart('chart8', data.chart8, 'Gráfico 8: Repetición de Clientes');
    // export

    // picking
    initChart('chart9', data.chart9, 'Gráfico 9: Clientes y Unidades');
    initChart('chart10', data.chart10, 'Gráfico 10: Destinos y Paletas ');
    initChart('chart11', data.chart11, 'Gráfico 11: Clientes y Cajas ');
    initChart('chart12', data.chart12, 'Gráfico 12: Repetición de Clientes');
    // picking

    // varios
    initChart('chart13', data.chart13, 'Gráfico 13: Clientes y Unidades');
    initChart('chart14', data.chart14, 'Gráfico 14: Destinos y Paletas ');
    initChart('chart15', data.chart15, 'Gráfico 15: Clientes y Cajas ');
    initChart('chart16', data.chart16, 'Gráfico 16: Repetición de Clientes');
    // varios
    
    // Agregar más inicializaciones si es necesario
}
    

    // Cargar los gráficos al inicio
    loadCharts();

    

    // Capturar el evento de envío del formulario
    document.getElementById('filterForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        // Obtener los valores de los campos
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFinal = document.getElementById('fechaFinal').value;
        const cliente = document.getElementById('cliente').value;

        // Recargar la página con los nuevos parámetros en la URL
        window.location.href = `?fecha_inicio=${fechaInicio}&fecha_final=${fechaFinal}&cliente=${cliente}`;
    });
</script>


</body>
</html>