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

    <div class="btn-group" style="margin-top: 100px; z-index: 999; margin-left: 38%; border-radius: 50px 50% 50% 50px; background-color: black">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="btn btn-info active btn-md" aria-current="true" aria-label="Slide 0">Import</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" class="btn btn-danger btn-md" aria-current="true" aria-label="Slide 1">Export</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" class="btn btn-warning btn-md" aria-label="Slide 3">Picking</button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" class="btn btn-primary btn-md" aria-label="Slide 4">Detalles Varios</button>
        <button class="btn btn-success text-dark btn-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#Id2" aria-controls="Id2">Clientes</button>
    </div>

    <!-- Carrusel -->
    <div id="carouselExampleCaptions" class="carousel slide mt-4">
        <div class="carousel-inner" style="margin-top: -70px;">
            <div class="carousel-item active ">
                <div class="d-flex justify-content-center align-items-center " style="height: 900px;">
                    <div id="barChart" style="width: 1200px; height: 400px;" class="border border-dark bg bg-light"></div>
                    <div id="barChart2" style="width: 800px; height: 400px;" class="border border-warning "></div>
                </div>
            </div>
            <div class="carousel-item border border-4">
                <div class="d-flex justify-content-center align-items-center" style="height: 900px;">
                    <div id="scatterChart" style="width: 400px; height: 300px; background-color:aliceblue"></div>
                    <div id="scatterChart2" style="width: 400px; height: 300px; background-color:aliceblue"></div>
                    </div>
            </div>
            <div class="carousel-item">
                <div class="d-flex justify-content-center align-items-center" style="height: 900px;">
                    <p>Contenido de la tercera diapositiva</p>
                </div>
            </div>
            <div class="carousel-item">
                <div class="d-flex justify-content-center align-items-center" style="height: 900px;">
                    <p>Contenido de la cuarta diapositiva</p>
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
            <div>I will not close if you click outside of me.</div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    // Función para cargar los datos desde la API y renderizar el gráfico
    async function renderScatterChart() {
        // Realizar la solicitud a tu API para obtener los datos
        const response = await fetch('./get_data_imp_r.php');
        const data = await response.json();

        // Formato de datos para el gráfico de dispersión
        const scatterData = data.map(item => {
            // Convertimos "2024-08" a "2024-08-01" para que sea una fecha válida
            const fechaCompleta = item.value[0] + "-01";
            const total = item.value[1];
            return {
                name: item.name,
                value: [new Date(fechaCompleta).getTime(), total]
            };
        });

        // Inicializar el gráfico en el contenedor
        const chart = echarts.init(document.getElementById('scatterChart'));

        // Configuración del gráfico
        const option = {
            title: {
                text: 'Datos Mensuales por Cliente',
                left: 'center',
                textStyle: {
                    fontSize: 18
                }
            },
            tooltip: {
                trigger: 'item',
                formatter: function (params) {
                    const date = new Date(params.value[0]);
                    return `${params.name}<br/>Fecha: ${date.getFullYear()}-${date.getMonth() + 1}<br/>Total: ${params.value[1]}`;
                }
            },
            xAxis: {
                type: 'time',
                name: 'Mes',
                nameLocation: 'middle',
                nameTextStyle: {
                    fontSize: 14
                },
                axisLabel: {
                    formatter: function (value) {
                        const date = new Date(value);
                        return `${date.getFullYear()}-${date.getMonth() + 1}`;
                    }
                }
            },
            yAxis: {
                type: 'value',
                name: 'Total',
                nameLocation: 'middle',
                nameTextStyle: {
                    fontSize: 14
                },
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [{
                name: 'Clientes',
                type: 'scatter',
                data: scatterData,
                symbolSize: 10,
                emphasis: {
                    focus: 'series'
                }
            }]
        };

        // Asignar la configuración al gráfico
        chart.setOption(option);
    }

    // Llamar a la función para cargar y mostrar el gráfico
    renderScatterChart();
</script>

<script>
// Definimos los datos de ejemplo para múltiples series, ahora por mes en lugar de año
const data = [
  {
    "month": "Enero",
    "sales": [
      { "name": "cajas A", "value": 411 },
      { "name": "unidades B", "value": 495 },
      { "name": "paletas C", "value": 200 },
      { "name": "Producto D", "value": 156 },
      { "name": "Producto E", "value": 464 }
    ],
    "expense": [
      { "name": "40 pies", "value": 483 },
      { "name": "20 pies", "value": 289 },
      { "name": "LCL", "value": 327 }
    ]
  },
  {
    "month": "Febrero",
    "sales": [
      { "name": "cajas A", "value": 352 },
      { "name": "unidades B", "value": 310 },
      { "name": "paletas C", "value": 183 },
      { "name": "Producto D", "value": 241 },
      { "name": "Producto E", "value": 219 }
    ],
    "expense": [
      { "name": "40 pies", "value": 241 },
      { "name": "20 pies", "value": 208 },
      { "name": "LCL", "value": 104 }
    ]
  },
  {
    "month": "Marzo",
    "sales": [
      { "name": "cajas A", "value": 349 },
      { "name": "unidades B", "value": 396 },
      { "name": "paletas C", "value": 411 },
      { "name": "Producto D", "value": 162 },
      { "name": "Producto E", "value": 138 }
    ],
    "expense": [
      { "name": "40 pies", "value": 464 },
      { "name": "20 pies", "value": 497 },
      { "name": "LCL", "value": 453 }
    ]
  }
];

// Configuración general del gráfico
let chart = echarts.init(document.getElementById('barChart'));

// Colores para las barras (colores variados)
const barColors = ['#4CAF50', '#FF9800', '#2196F3', '#FF5722', '#9C27B0'];

// Configuración del timeline y las opciones de gráficos
let option = {
  baseOption: {
    timeline: {
      axisType: 'category',
      data: data.map(item => item.month),
      autoPlay: true,
      playInterval: 2000,
    },
    toolbox: {
      feature: {
        saveAsImage: {},
        dataZoom: {},
        restore: {},
        magicType: { type: ['line', 'bar'] },
      },
    },
    tooltip: {
      trigger: 'axis',
    },
    grid: {
      left: '3%', // Puedes cambiar este valor para ajustar el tamaño desde la izquierda
      right: '30%', // Ajusta el tamaño desde la derecha
      bottom: '10%', // Ajusta el tamaño desde la parte inferior
      top: '15%', // Ajusta el tamaño desde la parte superior
      containLabel: true, // Asegura que las etiquetas estén contenidas
    },
    legend: {
      data: ['Ventas', 'Distribución de Gastos'], // Se eliminó "Embarques"
    },
    xAxis: {
      type: 'category',
    },
    yAxis: {
      type: 'value',
    },
    series: [
      // Solo mantendremos la serie de ventas
      {
        type: 'bar',
        name: 'Ventas',
        itemStyle: { color: '#4CAF50' },
        label: { show: true, position: 'top' },
      },
      // Este gráfico será de tipo pie para la distribución de gastos
      {
        type: 'pie',
        name: 'Distribución de Gastos',
        radius: ['20%', '70%'],
        center: ['40%', '30%'],
        left:'80%' ,
        label: {
          show: true,
          formatter: '{b}: {d}%',
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b}: {c} ({d}%)',
        },
      },
    ],
  },
  options: data.map((monthData) => {
    const salesData = monthData.sales.filter(item => item.value > 0);
    const expensesData = monthData.expense;

    // Combinamos las ventas en una sola serie
    const combinedData = salesData.map((item, index) => ({
      value: item.value,
      name: item.name,
      itemStyle: { color: barColors[index % barColors.length] },
    }));

    return {
      title: {
        text: `Ventas y Gastos en ${monthData.month}`,
      },
      xAxis: {
        data: salesData.map(item => item.name),
      },
      series: [
        {
          name: 'Ventas',
          type: 'bar',
          data: combinedData,
        },
        {
          name: 'Distribución de Gastos',
          type: 'pie',
          data: expensesData.map(item => ({
            value: item.value,
            name: item.name,
          })),
        },
      ],
    };
  }),
};

// Renderizamos el gráfico con la configuración completa
chart.setOption(option);
</script>









</body>
</html>
