<?php
      session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabla de Datos - Daily Plan</title>
    <!-- Incluir Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">
    <!-- Incluir ECharts desde el CDN -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.0/dist/echarts.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
  /* Estilo general para pantallas grandes NO SE TOCA, PRODUCTIVONO SE TOCA, PRODUCTIVO*/
  .bloquess {
    display: grid !important;
    grid-template-columns: auto auto !important;
    gap: 10px!important;
    margin-top: 8% !important;
  }

  .table-container {
            width: 50%; /* Puedes ajustar el tamaño */
            height: 300px; /* Altura fija con scroll */
            overflow-y: auto; /* Activa el scroll vertical */
            border: 1px solid #ccc;
            margin-bottom: 20px; /* Espaciado entre tablas */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }

  /* Consulta de medios para pantallas pequeñas NO PASAR A MAS DE AQUÍ NO PASAR A MAS DE AQUÍ */
  @media (max-width: 1280px) and (max-height: 620px) {
    .bloquess {
      display: grid !important;
    grid-template-columns: auto auto !important;
    margin-top: 10% !important;
    margin-left: -1000px !important;
      width: 50%;
      height: 50%;
      gap: 10px !important; /* Espacio entre los gráficos */
    }

    /* Ajustar el tamaño de los gráficos */
    .bloquee {
      position: fixed;
      width: 50%;
       height: 100%;
       border-radius: 15px;
        overflow: hidden;
      border: black;
    }

    #grafico-pastel1, #grafico-pastel2, #grafico-barras,#grafico-gauge {
      width: 250% !important; /* Hacer que el gráfico ocupe todo el ancho */
      height: 225px !important; /* Ajustar la altura del gráfico */
    }

        /* Ajustar las tablas para pantallas pequeñas */
        #tablaExport,#tablaImport,#tablapicking {
      font-size: 10px !important; /* Texto aún más pequeño en pantallas pequeñas */
      width: 100% !important; /* Ocupar el ancho completo */
      margin: 0 auto !important; /* Centrar la tabla */
    }

    .titulo_gauge{
      font-size: 100% !important;
    }
  }
</style>


</head>
<body style="overflow: hidden;">
<div style= "margin-top: 70px;" >
     <!-- Header -->
     <div class="header" style="border-radius: 0 0 0px 50px !important;">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group" ></a>
        </div>
        <h1><a style="color: #fff; text-decoration:none" href="../daily_plan/index_DP.php">Daily plan</a></h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
  </div>
    <!-- Fin del Header -->
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="display: block-inline;"> 

  <div class="carousel-inner">
    <!--data-bs-interval ajusta el tiempo de las graficas en pantalla -->
    <div class="carousel-item active "data-bs-interval="15000" style="height: 50%; height: 100%;position: fixed;">
    <div class="container" style="margin-top: 0%">

        <div class="bloquess" style="margin-left:-0% !important;margin-top:5% !important; display: grid; grid-template-columns: auto auto; gap: 50px !important">
          <!-- Gráfico import -->
            <div class="bloquee border border-5 border-info" id="import"  style="position: relative;width: 800px; height: 300px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="col-md-6 " >
                    <div id="grafico-barras" class="bg-white " style="width: 200%; height: 290%;"></div>
                </div>   
            </div>
                <!-- grafico piking -->
            <div class="bloquee border border-5 border-warning" id="picking" style="position: relative;width: 800px; height: 300px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" >
                <div class="col-md-6">
                    <div id="grafico-pastel2" class="bg-white " style="width: 200%; height: 300%;"></div>
                    </div>
            </div>   
            <!-- grafico de export -->
            <div class="bloquee border border-5 border-danger" id="export" style="position: relative;width: 800px; height: 350px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" >
            <div class="col-md-6 ">
                    <div id="grafico-pastel1" class="bg-white " href="../daily_plan/tabla_ex.php" style="width: 200%; height: 350%;"></div>
                </div>    
            </div>
        <!-- grafico de gauge -->
             <div class="bloquee" id="porcentaje" style="position: relative;width: 200%; height: 400px;border-radius: 15px; overflow: hidden;" >
                <div class="col-md-6 " >
                    <p class="titulo_gauge" style="font-family: montserrat; font-size:200%; font-weight: bold;">Porcentaje de cumplimiento</p>
                    <div id="grafico-gauge" style="width: 900; height: 450px;margin-top:-50px;margin-left:50px"></div>
                </div>
            </div>
        </div>


    </div>
    </div>

            <div class="carousel-item" data-bs-interval="15000">
            <div class="container" >
            <div class="bloquess"style=";display: grid;grid-template-columns: auto auto;gap: 5px">

            <div class="bloquee border border-5 border-danger" id="import"  style="position: relative;width: 800px; height: 400px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="margin-left:45%">Export</h2>
            <div class="container">
                <table id="clientes-table_TEX"  class="table table-danger table-striped" style="width: 800px;" >
                  <thead class="bg-danger">
                    <tr>
                      <th class="text-bg-danger">OID</th>
                      <th class="text-bg-danger">Cliente</th>
                      <th class="text-bg-danger">Vehículo</th>
                      <th class="text-bg-danger">Pedidos en Proceso</th>
                      <th class="text-bg-danger">Pedidos Despachados</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
            <div class="bloquee border border-5 border-info" id="import"  style="position: relative;width: 800px; height: 400px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
              <h2 style="margin-left:45%">Import</h2>
              <div class="container">
                <table id="clientes-table_TIM"  class="table table-info table-striped" style="width: 800px;">
                  <thead class="bg-info">
                    <tr>
                      <th class="text-bg-info">AID</th>
                      <th class="text-bg-info">Cliente</th>
                      <th class="text-bg-info">Contenedores Recibidos</th>
                      <th class="text-bg-info">Contenedores Cerrados</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>

            <div class="bloquee border border-5 border-warning" id="import"  style="position: relative;width: 800px; height: 450px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="margin-left:45%">Picking</h2>
            <div class="col-md-12" >
              <div class="container" >
                <table id="clientes-table_TPK"  class="table table-warning table-striped" >
                  <thead class="bg-warning">
                    <tr>
                      <th class="text-bg-warning">OID</th>
                      <th class="text-bg-warning">Cliente</th>
                      <th class="text-bg-warning">Prioridad de Picking</th>
                      <th class="text-bg-warning">Unidades por Pickear</th>
                      <th class="text-bg-warning">Porcentaje de Avance</th>
                      <th class="text-bg-warning">Fecha de Requerido</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
            </div>
            <div class="bloquee" id="porcentaje" style="position: relative;width: 800; height: 450px;border-radius: 15px; overflow: hidden;" >
                <div style="position: relative; width: 800px; height: 800px; padding: 10px; margin-left: -80px;  border-width: 10px;">
                    <p class="titulo_gauge" style="font-family: montserrat; font-size:200%; font-weight: bold;margin-left:150px">Porcentaje de cumplimiento</p>
                    <div id="grafico-gauge_d" style="position: relative; width: 900px; height: 450px; padding: 10px;  border-width: 10px;"></div>
                </div>
            </div>
              </div>
            </div>
          </div>

    <div class="carousel-item" data-bs-interval="7500">
      <div  style="height: 1100px;padding-top: 0px; margin: top 4%;">
      <img loading="lazy" src="../images/ADOC.jpg"  alt="ADOC" style="width: 100%; height:90% !important; position: flex; z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/3.jpg"  alt="cumpleaños2"  style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/4.jpg"  alt="cumpleaños" style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/13.png"  alt="Seguridad" style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
    <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/2.png"  alt="Proposito"  style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/12.png"  alt="cumpleaños1"  style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <div style="height: 1100px;padding-top: 0px;">
      <img loading="lazy" src="../daily_plan/imagenes/5.png" alt="mision"   style="width: 100%; height: 90%;display: flex;z-index: 999;">
      </div>
    </div>
  </div>
  <button class="carousel-control-prev btn-primary" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev" style="width: 100px;height: 1000px;padding-top: 500px !important;">
    <span class="carousel-control-prev-icon visually-hidden" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next" style="width: 100px;height: 1000px;padding-top: 500px !important;">
    <span class="carousel-control-next-icon visually-hidden" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<script type="text/javascript">
    function cargarClientesTIM() {
        $.ajax({
            url: 'get_data_TIM.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#clientes-table_TIM tbody').empty();
                $.each(data, function(index, cliente) {
                    $('#clientes-table_TIM tbody').append(
                        '<tr>' +
                        '<td>' + cliente.aid_oid + '</td>' +
                        '<td>' + cliente.cliente + '</td>' +
                        '<td>' + cliente.pedidos_en_proceso + '</td>' +
                        '<td>' + cliente.pedidos_despachados + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los datos de TIM:", error);
            }
        });
    }
    
    function cargarClientesTEX() {
        $.ajax({
            url: 'get_data_TEX.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#clientes-table_TEX tbody').empty();
                $.each(data, function(index, cliente) {
                    $('#clientes-table_TEX tbody').append(
                        '<tr>' +
                        '<td>' + cliente.aid_oid + '</td>' +
                        '<td>' + cliente.cliente + '</td>' +
                        '<td>' + cliente.vehiculo + '</td>' +
                        '<td>' + cliente.pedidos_en_proceso + '</td>' +
                        '<td>' + cliente.pedidos_despachados + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los datos de TEX:", error);
            }
        });
    }

    function cargarClientesTPK() {
        $.ajax({
            url: 'get_data_TPK.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#clientes-table_TPK tbody').empty();
                $.each(data, function(index, cliente) {
                    $('#clientes-table_TPK tbody').append(
                        '<tr>' +
                        '<td>' + cliente.aid_oid + '</td>' +
                        '<td>' + cliente.cliente + '</td>' +
                        '<td>' + cliente.vacio_lleno + '</td>' +
                        '<td>' + cliente.pedidos_en_proceso + '</td>' +
                        '<td>' + cliente.division_dp + '</td>' +
                        '<td>' + cliente.fecha_objetivo + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los datos de TPK:", error);
            }
        });
    }

    // Cargar los datos cuando la página se carga por primera vez
    $(document).ready(function() {
        cargarClientesTIM();
        cargarClientesTEX();
        cargarClientesTPK();

        // Actualizar automáticamente cada 5 segundos
        setInterval(cargarClientesTIM, 5000);
        setInterval(cargarClientesTEX, 5000);
        setInterval(cargarClientesTPK, 5000);
    });
</script>


<script>
        // Inicializar los gráficos de ECharts
        var chart1 = echarts.init(document.getElementById('grafico-pastel1'));
        var chart2 = echarts.init(document.getElementById('grafico-pastel2'));
        var barChart = echarts.init(document.getElementById('grafico-barras'));
        var gaugeChart = echarts.init(document.getElementById('grafico-gauge'));
        var grafico_gauge_d = echarts.init(document.getElementById('grafico-gauge_d'));
        

        // Función para obtener los datos del servidor y actualizar los gráficos
        function fetchData() {
            fetch('get_data_ex.php')
                .then(response => response.json())
                .then(data => {
                    // Configurar el grafico de export
                    chart1.setOption(
                        option = {
                            color:['#2E8B57', '#FF8C33', '#FCEC52', '#ADEEE3', '#995FA3 ', '#F45B69', '#C3E991', '#8EA4D2', '#FFE1C6'],
                            title: {text: 'Export',subtext: '',left: 'center'},
                            tooltip: {trigger: 'item'},
                            legend: {orient: 'vertical',left: 'left'},
                        series: [{
                            name: 'Export',
                            type: 'pie',
                            radius: ['30%', '80%'],
                            label: {formatter: '{c}',position: 'inside',fontSize: 25},
                            data: data,
                        itemStyle: {
                            borderRadius: 10,
                            borderColor:  '#fff',
                            borderWidth: 3
                        },
                        }]
                    });
        });
                    fetch('get_data_pk.php')
            .then(response => response.json())
            .then(data => {
                    // Configurar el grafico de picking
                    chart2.setOption(
                        option = {
                            color:['#63E2C6', '#FFBA08 ', '#FF8552', '#CA6680', '#AFDEDC', '#33FFA1', '#FF8C33', '#33A1FF', '#A1FF33'],
                            title: {text: 'Picking',subtext: '',left: 'center'},
                            tooltip: {trigger: 'item' },
                            legend: {orient: 'vertical',left: 'left'},
                        
                        series: [{
                            name: 'Picking',
                            type: 'pie',
                            radius: ['30%','60%'],
                            label: {formatter: '{c}',position: 'outside',fontSize: 20,},
                            data: data,
                        itemStyle: {
                            borderRadius: 10,
                            borderColor:  '#fff',
                            borderWidth: 3
                        },
                            
                        }]
                    });
                });



                fetch('get_data_im.php')
    .then(response => response.json())
    .then(data => {
        // Crear la estructura de series según el formato deseado
        const series = [];

        // Agrupar los datos en la estructura esperada
        const clientes = [...new Set(data.map(item => item.name))]; // Obtener clientes únicos

        // Iterar sobre cada cliente para construir la serie
        clientes.forEach(cliente => {
            // Filtrar datos para el cliente actual
            const clienteData = data.filter(item => item.name === cliente);
            
            // Añadir datos de "Recibido"
            series.push({
                name: cliente, // Nombre del cliente
                type: 'bar',
                stack: 'total', // Para apilar
                label: {
                    show: true // Mostrar etiquetas
                },
                data: clienteData[0].data // Recibido
            });
        });

        // Configurar el gráfico de barras
        const option = {
          title: {text: 'Import',subtext: '',left: 'center'},
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow' 
                }
            },
            legend: {left: 'left', orient: 'vertical',},
            grid: {
                left: '10%',
                right: '4%',
                bottom: '3%',
                top: '30%',
                containLabel: true
            },
            xAxis: {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: ['Recibido', 'En espera']
            },
            series: series // se reemplaza la parte de series con la nueva estructura
        };

        // Establecer la opción en el gráfico
        barChart.setOption(option);
    });

    fetch('get_data_porcen.php')
    .then(response => response.json())
    .then(gaugeData => {
          // Configuración del gráfico de porcentaje de cumplimiento
          grafico_gauge_d.setOption({
            series: [{
              name:'Porcentaje',
              type: 'gauge',
              startAngle: 180,
              endAngle: 0,
              color:['#0dcaf0', '#DC143C ', ' #FFA500'],
              pointer: { show: false },
              progress: { show: true, clip: true ,overlap: false, roundCap: true, itemStyle: { borderWidth: 0, borderColor: '#fff',borderRadius: [1,50,50,1], } },
              axisLine: { lineStyle: { width: 30 } },
              splitLine: { show: false },
              axisTick: {show: false},
              axisLabel: { show: true },
              data: gaugeData,
              title: { text: 'Porcentaje de cumplimiento', fontFamily: 'montserrat'},
              detail: { formatter: '{value}%', fontSize: 20, color: 'inherit', borderColor: 'inherit', borderRadius: [10,10,10,10], borderWidth: 1,},
            }]
          });
        });
        fetch('get_data_porcen.php')
        .then(response => response.json())
        .then(gaugeData => {
          // Configuración del gráfico de porcentaje de cumplimiento
          gaugeChart.setOption({
            series: [{
              name:'Porcentaje',
              type: 'gauge',
              startAngle: 180,
              endAngle: 0,
              color:['#0dcaf0', '#DC143C ', ' #FFA500'],
              pointer: { show: false },
              progress: { show: true, clip: true ,overlap: false, roundCap: true, itemStyle: { borderWidth: 0, borderColor: '#fff',borderRadius: [1,50,50,1], } },
              axisLine: { lineStyle: { width: 30 } },
              splitLine: { show: false },
              axisTick: {show: false},
              axisLabel: { show: true },
              data: gaugeData,
              title: { text: 'Porcentaje de cumplimiento', fontFamily: 'montserrat'},
              detail: { formatter: '{value}%', fontSize: 20, color: 'inherit', borderColor: 'inherit', borderRadius: [10,10,10,10], borderWidth: 1,},
            }]
          });
        });
    }
        // Llamar a la función fetchData para obtener los datos al cargar la página
        fetchData();

        // Opcional: Actualizar los gráficos cada 5 segundos
        setInterval(fetchData, 5000);

    </script>


    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../host_virtual_TI/js/script.js"></script>


</body>
</html>
