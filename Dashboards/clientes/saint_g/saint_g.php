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

    <div class="btn-group" style="margin-top: 143px; z-index: 999; margin-left: 38%; border-radius: 50px 50% 50% 50px; background-color: black; position: fixed">
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
            <div class="container mt-5">
    <div class="row gy-4 justify-content-center align-items-center" style="margin-top: 140px;">
        <!-- Primer gráfico -->
        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
            <div id="barChart1" class="border border-dark border-4 rounded bg-light" style="width: 1500px; max-width: 1200px; height: 600px; background-color:aliceblue"></div>
        </div>
        <!-- Segundo gráfico -->
        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
            <div id="barChart2" class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 800px; height: 600px;background-color:aliceblue"></div>
        </div>
        <!-- Tercer gráfico -->
        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
            <div class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 1000px; height: 600px;background-color:white"></div>
        </div>
        <!-- Cuarto gráfico -->
        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
            <div class="border border-dark border-4 rounded bg-light" style="width: 100%; max-width: 800px; height: 600px; background-color:white"></div>
        </div>
    </div>
</div>

            </div>
            <div class="carousel-item border border-4">
                <div class="d-flex justify-content-center align-items-center" style="height: 900px; padding :2%">
                    <div id="scatterChart" style="width: 400px; height: 300px; background-color:aliceblue"></div>
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
        <div class="container mt-5">
    <h2 class="text-center mb-4">Consulta Agrupada Dinámica</h2>
    <form method="GET" action="get_data_imp_r.php">
        <div class="mb-3">
            <label for="groupBy" class="form-label">Agrupar por:</label>
            <select id="groupBy" name="groupBy" class="form-select" required>
                <option value="mes">Mes</option>
                <option value="cliente">Cliente</option>
                <option value="mes_cliente">Mes y Cliente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>
</div>
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
// Datos para el primer gráfico
const data1 = [
  {
    "month": "Enero",
    "sales": [
      { "name": "cajas ", "value": 421 },
      { "name": "CBM", "value": 312 },
      { "name": "KG", "value": 180 },
      { "name": "Paletas recibidas", "value": 240 },
      { "name": "posicion por paleta ", "value": 510 }
    ],
    "expense": [
      { "name": "40 pies", "value": 403 },
      { "name": "20 pies", "value": 267 },
      { "name": "LCL", "value": 333 }
    ]
  },
  {
    "month": "Febrero",
    "sales": [
      { "name": "cajas ", "value": 390 },
      { "name": "CBM", "value": 344 },
      { "name": "KG", "value": 250 },
      { "name": "Paletas recibidas", "value": 206 },
      { "name": "posicion por paleta", "value": 398 }
    ],
    "expense": [
      { "name": "40 pies", "value": 342 },
      { "name": "20 pies", "value": 225 },
      { "name": "LCL", "value": 120 }
    ]
  },
  {
    "month": "Marzo",
    "sales": [
      { "name": "cajas ", "value": 378 },
      { "name": "CBM", "value": 401 },
      { "name": "KG", "value": 320 },
      { "name": "Paletas recibidas", "value": 190 },
      { "name": "posicion por paleta", "value": 429 }
    ],
    "expense": [
      { "name": "40 pies", "value": 420 },
      { "name": "20 pies", "value": 490 },
      { "name": "LCL", "value": 368 }
    ]
  }
];


// Datos para el segundo gráfico
const data2 = [
    {
    "month": "Enero",
    "sales": [
      { "name": "Brazil", "value": 411 },
      { "name": "España", "value": 495 },
      { "name": "polonoia", "value": 411 },
      { "name": "Aruba", "value": 495 },
      { "name": "Italia X", "value": 411 },
      { "name": "Argentina", "value": 495 },
      { "name": "Indonesia", "value": 411 },
      { "name": "Namibia", "value": 495 },
      { "name": "ecuador", "value": 200 }
    ],
  },
  {
    "month": "Febrero",
    "sales": [
      { "name": "producto X", "value": 300 },
      { "name": "producto Y", "value": 420 },
      { "name": "producto Z", "value": 250 }
    ],
  },
  {
    "month": "Marzo",
    "sales": [
      { "name": "producto X", "value": 378 },
      { "name": "producto Y", "value": 401 },
      { "name": "producto Z", "value": 320 }
    ],
  }
  // Agrega más datos para `data2` si necesitas más meses
];

// Configuración para el primer gráfico
let chart1 = echarts.init(document.getElementById('barChart1'));
const option1 = {
  baseOption: {
    timeline: {
      axisType: 'category',
      data: data1.map(item => item.month),
      autoPlay: false,
      playInterval: 2500,
      right:'0%',
      left: ',0%',  // Ajusta la posición desde la izquierda
      bottom: '1%', // Ajusta la posición desde la parte inferior
    },
    toolbox: {
      feature: {
        saveAsImage: {},
        dataZoom: {},
        restore: {},
        magicType: { type: ['line', 'bar','pie'] },
      },
    },
    tooltip: {
      trigger: 'axis',
    },
    grid: {
      left: '3%',
      right: '30%',
      bottom: '10%',
      top: '15%',
      containLabel: true,
    },
    legend: {
      left:'0%',
      data: ['Cajas/CBM/KG/PALETAS RECIBIDOS', 'Embarques totales recibidos'],
    },
    xAxis: {
      type: 'category',
    },
    yAxis: {
      type: 'value',
    },
    series: [
      {
        type: 'bar',
        name: 'Cajas/CBM/KG/PALETAS RECIBIDOS',
        itemStyle: { color: '#4CAF50' },
        label: { show: true, position: 'top' },
      },
      {
        type: 'pie',
        name: 'Embarques totales recibidos',
        radius: ['10%', '30%'],
        center: ['85%', '50%'],
        label: {
          show: true,
          position:'inner',
          formatter: '{b}: {d}%',
        },
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b}: {c} ({d}%)',
        },
      },
    ],
  },
  options: data1.map((monthData) => {
    const salesData = monthData.sales.map((item, index) => ({
      value: item.value,
      name: item.name,
    }));
    const expensesData = monthData.expense;

    return {
      title: {
        text: `Ventas y Gastos en ${monthData.month}`,
        top : '10%',
      },
      xAxis: {
        data: salesData.map(item => item.name),
      },
      series: [
        {
          name: 'Cajas/CBM/KG/PALETAS RECIBIDOS',
          type: 'bar',
          data: salesData,
        },
        {
          name: 'Embarques totales recibidos',
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

// Configuración para el segundo gráfico
let chart2 = echarts.init(document.getElementById('barChart2'));
const option2 = {
  baseOption: {
    timeline: {
      axisType: 'category',
      left:'0%',
      right:'0%',
      data: data2.map(item => item.month),
      autoPlay: false,
      playInterval: 2500,
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
      left: '3%',
      right: '2%',
      bottom: '10%',
      top: '15%',
      containLabel: true,
    },
    legend: {
      data: ['Ventas', 'Distribución de Gastos'],
    },
    xAxis: {
      type: 'category',
    },
    yAxis: {
      type: 'value',
    },
    series: [
      {
        type: 'bar',
        name: 'Ventas',
        itemStyle: { color: '#2196F3' },
        label: { show: true, position: 'top' },
      },

    ],
  },
  options: data2.map((monthData) => {
    const salesData = monthData.sales.map((item, index) => ({
      value: item.value,
      name: item.name,
    }));
    const expensesData = monthData.expense;

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
          data: salesData,
        },

      ],
    };
  }),
};

// Renderiza ambos gráficos con sus configuraciones independientes
chart1.setOption(option1);
chart2.setOption(option2);
</script>

</body>
</html>