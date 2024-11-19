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
    <h1 style="width: 100%; text-align: center;">Gráficos dinámicos</h1>
    
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
        // Función para obtener datos desde el servidor
        async function fetchData(endpoint) {
            const response = await fetch(endpoint);
            if (!response.ok) {
                console.error("Error al obtener los datos:", response.statusText);
                return {};
            }
            return await response.json();
        }

        // Inicializa un gráfico dinámico
        function initChart(containerId, data, title = "Gráfico") {
            const chart = echarts.init(document.getElementById(containerId));
            const options = {
                title: {
                    text: data.title || title,
                    left: 'center',
                },
                tooltip: data.tooltip || {
                    trigger: 'item',
                },
                toolbox: data.toolbox || {
                    show: true,
                    feature: {
                        saveAsImage: { show: true },
                    },
                },
                xAxis: data.type === 'pie' || data.type === 'radar' ? undefined : {
                    type: 'category',
                    data: data.categories || [],
                },
                yAxis: data.type === 'pie' || data.type === 'radar' ? undefined : {
                    type: 'value',
                },
                series: [
                    {
                        data: data.values || [],
                        type: data.type || 'bar', // Tipo dinámico
                        name: data.title || title,
                    },
                ],
            };
            chart.setOption(options);
        }

        // Cargar y renderizar gráficos
        async function loadCharts() {
            const data = await fetchData('get_data.php'); // Obtiene datos del servidor

            // Inicializa 8 gráficos con datos distintos
            for (let i = 1; i <= 8; i++) {
                initChart(`chart${i}`, data[`chart${i}`] || {}, `Gráfico ${i}`);
            }
        }

        // Llamar a la función para cargar los gráficos
        loadCharts();
    </script>
</body>
</html>
