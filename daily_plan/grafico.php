<?php
      session_start();
      include '../daily_plan/funcionalidades/funciones.php';
      $error = false;
      $config = include '../daily_plan/funcionalidades/config_DP.php';


    $consultaSQL = "UPDATE import SET
    cumplimiento_im = :cumplimiento_im,
    WHERE id = :id";

    $consultaSQL = "UPDATE export SET
    cumplimiento_ex = :cumplimiento_ex,
    WHERE id = :id";

    $consultaSQL = "UPDATE picking SET
    cumplimiento_pk = :cumplimiento_pk,
    WHERE id = :id";
?>

<?php
      try {
          $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
          $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

          // Consulta para la tabla 'import'
          $consultaSQL_i = "SELECT * FROM import  WHERE fecha_objetivo = CURDATE() GROUP BY aid_oid";
          $sentencia_i = $conexion->prepare($consultaSQL_i);
          $sentencia_i->execute();
          $import = $sentencia_i->fetchAll();


          // Consulta para la tabla 'export'
          $consultaSQL_e = "SELECT * FROM export  WHERE fecha_objetivo = CURDATE() GROUP BY vehiculo";
          $sentencia_e = $conexion->prepare($consultaSQL_e);
          $sentencia_e->execute();
          $export = $sentencia_e->fetchAll();

          // Consulta para la tabla 'datos'
          $consultaSQL_pk = "SELECT * FROM picking  WHERE fecha_objetivo = CURDATE() GROUP BY cliente";
          $sentencia_pk = $conexion->prepare($consultaSQL_pk);
          $sentencia_pk->execute();
          $picking = $sentencia_pk->fetchAll();
        } catch (PDOException $error) {
            $error = $error->getMessage();
        }
        ?>
      <?php
      header("Refresh:81");
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


    <style>
  /* Estilo general para pantallas grandes NO SE TOCA, PRODUCTIVO*/
  .bloquess {
    display: grid !important;
    grid-template-columns: auto auto !important;
    gap: 10px!important;
    margin-top: 10% !important;
    margin-left: 45% !important;
  }

  /* Consulta de medios para pantallas pequeñas NO PASAR A MAS DE AQUÍ */
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
            <div class="bloquee border border-5 border-info" id="import"  style="position: relative;width: 800px; height: 300px;border-radius: 15px; overflow: hidden;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <!-- Gráfico import -->
            <div class="col-md-6 " >
                    <div id="grafico-barras" class="bg-white " style="width: 200%; height: 325%;"></div>
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
                    <p class="titulo_gauge" style="font-family: montserrat; font-size:200%; font-weight: bold;">Porcentaje de cumplimiento.</p>
                    <div id="grafico-gauge" style="width: 90%; height: 350px;margin-top:0px;margin-left:50px"></div>
                </div>
            </div>
        </div>


    </div>
    </div>

            <div class="carousel-item" data-bs-interval="15000">
            <div class="container" style="margin-top: 0%">
            <div class="bloquess"style=";display: grid;grid-template-columns: auto auto;gap: 10px; margin-left: -10% !important;  margin-top: 0% !important">


        <div class="bloquee " id="export" style="position:relative;width: 900px; height: 400px;border-radius: 15px; overflow: hidden;margin-top:2%" >        
          <div class="col-md-6 ">
              <div class="container">
                <div class="row">
                  <div class="col-md-3"  style=" width: 700px; height: 60%; margin-left: 250px">
                    <h2 class="mt-3" style="margin-bottom: 10px; font-size:30px; margin-left: 25% !important">Export</h2>
                    <table id="tablaExport" class=" tabla-ajustada display table shadow p-3 mb-5 bg-body-info rounded table-striped border" style=" margin-left: 25% !important">
                      <thead>
                        <tr style="font-family: montserrat; font-size: 15px">
                          <th class="border end" style="background-color: #dc3545">OID</th>
                          <th class="border end" style="background-color: #dc3545">Cliente</th>
                          <th class="border end" style="background-color: #dc3545">Vehiculo</th>
                          <th class="border end" style="background-color: #dc3545">Pedidos en proceso</th>
                          <th class="border end" style="background-color: #dc3545">Pedidos despachados</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($export && $sentencia_e->rowCount() > 0): ?>
                          <?php foreach ($export as $fila): ?>
                            <tr style="font-family: montserrat; font-size: 14px">
                              <td class="border end"><?php echo escapar($fila["aid_oid"]); ?></td>
                              <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                              <td class="border end"><?php echo escapar($fila["vehiculo"]); ?></td>
                              <td class="border end"><?php echo escapar($fila["pedidos_en_proceso"]); ?></td>
                              <td class="border end"><?php echo escapar($fila["pedidos_despachados"]); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

                    </div>   
                </div>

                <div class="bloquee " id="import" style="position: relative;width: 900px; height: 400px;border-radius: 15px; overflow: hidden;margin-top:2%" >
                    <div class="col-md-6">
                    <div class="container">
                          <div class="row">
                            <div class="col-md-3" style=" width: 700px; height: 60%; margin-left: 250px">
                              <h2 class="mt-3" style="margin-bottom: 10px; font-size:30px; margin-left: 25% !important">Import</h2>
                              <table id="tablaImport" class="tabla-ajustada  display table shadow p-3 mb-5 bg-body-info rounded table-striped border" style=" margin-left: 25% !important">
                          <thead>
                            <tr  style="font-family: montserrat; font-size: 15px">
                              <th class="border end" style="background-color: #0dcaf0">AID</th>
                              <th class="border end" style="background-color: #0dcaf0">Cliente</th>
                              <th class="border end" style="background-color: #0dcaf0">Contenedores recibidos</th>
                              <th class="border end" style="background-color: #0dcaf0">Contenedores cerrados</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php if ($import && $sentencia_i->rowCount() > 0): ?>
                                    <?php foreach ($import as $fila): ?>
                                      <tr style="font-family: montserrat; font-size: 14px">
                                        <td class="border end"><?php echo escapar($fila["aid_oid"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["pedidos_en_proceso"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["pedidos_despachados"]); ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                          </tbody>
                        </table>

                            </div>
                          </div>
                        </div> 

                    </div>
                </div>   

                <div class="bloquee " id="barras" style="position: relative;width: 900px; height: 60%px;border-radius: 15px; overflow: hidden;; margin-top:0%" >
                    <div class="col-md-6 " >
                    <div class="container">
                    <div class="row">
                      <div class="col-md-2" style=" width: 700px; height: 60%; margin-left: 250px">
                        <h2 class="mt-2" style="margin-bottom: 10px; font-size:30px ; margin-left: 25% !important">Picking</h2>
                        <table   id="tablapicking" class="tabla-ajustada display table shadow p-3 mb-5 bg-body-info rounded table-striped border" style="  margin-left: 25% !important">
                                <thead>
                                  <tr style="font-family: montserrat; font-size: 14px">
                                    <th class="border end" style="background-color: #ffc107">OID</th>
                                    <th class="border end" style="background-color: #ffc107">Cliente</th>
                                    <th class="border end" style="background-color: #ffc107">Prioridad de picking</th>
                                    <th class="border end" style="background-color: #ffc107">Unidades por pickear</th>
                                    <th class="border end" style="background-color: #ffc107">Unidades pickeadas</th>
                                    <th class="border end" style="background-color: #ffc107">Porcentaje de avance</th>
                                    <th class="border end" style="background-color: #ffc107">Fecha de requerido</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php if ($picking && $sentencia_pk->rowCount() > 0): ?>
                                    <?php foreach ($picking as $fila): ?>
                                      <tr>
                                        <td class="border end"><?php echo escapar($fila["aid_oid"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["cliente"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["vacio_lleno"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["pedidos_en_proceso"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["pedidos_despachados"]); ?></td>
                                        <td class="border end"><?php echo escapar($fila["division_dp"])*100.00; ?>%</td>
                                        <td class="border end"><?php echo escapar($fila["fecha_objetivo"]); ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                </tbody>
                              </table>
                      </div>
                    </div>
                  </div>
                    </div>
                </div>


                <div class="bloquee" id="porcentaje" style="position: relative;width: 200%; height: 400px;border-radius: 15px; overflow: hidden; margin-top:-5%" >
                    <div class="col-md-6 " >
                        <p style="font-family: montserrat; font-size:180%; margin-top: 30px !important;margin-left: 20% !important;font-weight: bold;">Porcentaje de cumplimiento</p>
                        <div  id="grafico-gauge_d" style="width: 900%; height: 400px;margin-top:0px;margin-left:5% !important"></div>
                    </div>
                </div>
            </div>
            </div>

    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../images/ADOC.jpg"  alt="ADOC" style="width: 100%; height:90% !important; position: flex; z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/3.jpg"  alt="cumpleaños2"  style="width: 100%; height: 100%;display: flex;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/4.jpg"  alt="cumpleaños" style="width: 100%; height: 90%;display: flex;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/13.png"  alt="Seguridad" style="width: 100%; height: 90%;display: flex;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/2.png"  alt="Proposito"  style="width: 100%; height: 90%;display: flex;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/12.png"  alt="cumpleaños1"  style="width: 100%; height: 90%;display: flex;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/5.png" alt="mision"   style="width: 100%; height: 90%;display: flex;z-index: 999;">
    </div>
  </div>
  <button class="carousel-control-prev btn-primary" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="false"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="false"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    <?php
    ?>

    <script>
        // Inicializar los gráficos de ECharts
        var chart1 = echarts.init(document.getElementById('grafico-pastel1'));
        var chart2 = echarts.init(document.getElementById('grafico-pastel2'));
        var barChart = echarts.init(document.getElementById('grafico-barras'));
        var gaugeChart = echarts.init(document.getElementById('grafico-gauge'));
        var gaugeChart_d = echarts.init(document.getElementById('grafico-gauge_d'));
        

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
        // Configurar el gráfico de import
        barChart.setOption({
            color: ['#00CED1 ', '#4682B4'],
            title: {text: 'Import',subtext: '',left: 'center',fontSize: 20},
            tooltip: {trigger: 'item'},
            legend: {orient: 'vertical',left: 'left'},
            yAxis: {type: 'category',data: [""], fontSize: 20},
            xAxis: {type: 'value'},
            series: [
                {
                    name: 'Recibido',
                    type: 'bar',
                    showBackground: true,
                    backgroundStyle: {
                    color: 'rgba(220, 220, 220, 0.8)',
                    borderRadius: [1,30,30,1],},
                    data: data.map(item => item.total_meta), // Los valores de meta_despacho
                    category:["Recibido"],
                    itemStyle: {
                        borderRadius: [1,30,30,1],
                        },
                    label: {
                        show: true,
                        position: 'insideRight',
                        fontSize: 35
                    },
                },
                {
                    name: 'En espera',
                    type: 'bar',
                    showBackground: true,
                    backgroundStyle: {
                    color: 'rgba(220, 220, 220, 0.8)',
                    borderRadius: [1,30,30,1],},
                    data: data.map(item => item.total_grafico), // Los valores de grafica_dp
                    itemStyle: {
                            borderRadius: [1,30,30,1],
                        },
                    label: {
                        show: true,
                        position: 'insideRight',
                        fontSize: 35
                    }
                }
            ]
            
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
              detail: { formatter: '{value}%', fontSize: 20, color: 'inherit', borderColor: 'inherit', borderRadius: [10,10,1000,10], borderWidth: 1,},
            }]
          });
        });
        fetch('get_data_porcen.php')
        .then(response => response.json())
        .then(gaugeData => {
          // Configuración del gráfico de porcentaje de cumplimiento
          gaugeChart_d.setOption({
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
              detail: { formatter: '{value}%', fontSize: 20, color: 'inherit', borderColor: 'inherit', borderRadius: [10,10,1000,10], borderWidth: 1,},
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../host_virtual_TI/js/script.js"></script>

</body>
</html>
