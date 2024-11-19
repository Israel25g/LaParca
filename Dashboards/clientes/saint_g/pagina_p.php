<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos con Apache ECharts</title>
    <!-- Incluyendo ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .chart-container {
            width: 45%;
            height: 300px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1 style="width: 100%; text-align: center;">Gráficos dinámicos </h1>
    
    <!-- Contenedores para los 8 gráficos -->
    <div id="chart1" class="chart-container"></div>
    <div id="chart2" class="chart-container"></div>
    <div id="chart3" class="chart-container"></div>
    <div id="chart4" class="chart-container"></div>
    <div id="chart5" class="chart-container"></div>
    <div id="chart6" class="chart-container"></div>
    <div id="chart7" class="chart-container"></div>
    <div id="chart8" class="chart-container"></div>

    <script>
        // Función para cargar datos desde PHP
        async function fetchData(endpoint) {
            const response = await fetch(endpoint);
            return await response.json();
        }

        // Inicializa un gráfico
        function initChart(containerId, data, title = "Gráfico") {
            const chart = echarts.init(document.getElementById(containerId));
            const options = {
                title: {
                    text: title,
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                xAxis: {
                    type: 'category',
                    data: data.categories || []
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        data: data.values || [],
                         // Cambia a 'line', 'pie', etc. según el gráfico
                    }
                ]
            };
            chart.setOption(options);
        }

        // Cargar los datos y renderizar los gráficos
        async function loadCharts() {
            // Endpoint donde se obtienen los datos
            const data = await fetchData('get_data.php');
            
            // Inicializa 8 gráficos con datos distintos
            for (let i = 1; i <= 8; i++) {
                initChart(`chart${i}`, data[`chart${i}`] || {}, `Gráfico ${i}`);
            }
        }

        // Llama la función al cargar la página
        loadCharts();
    </script>
</body>
</html>
