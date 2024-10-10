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

<body>
    <style>
        body {
            background-image: url('../../../images/Motivo2.png')!important;
            margin: 0;
            padding: 0;
            background-size: cover;
        }

        .container_d {
            /* display: flex; */
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px !important;
            margin-left: 350px !important;
        }

        .bloques_d {
            margin-top: 20px;
            margin-left: 100px;
            padding: 15px;
            display: grid;
            grid-template-columns: auto auto;
            gap: 20px;
        }

        .bloque_d {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-btn {
        opacity: 1 !important ;
        font-weight:500;
        font-size: 9px; /* Ajusta el tamaño de la fuente */
        text-align: right !important;
        margin-top: 10px!important;
        padding: 28px 50px ; /* Tamaño del botón */
        border-radius: 0px 50px 50px 5px; /* Bordes redondeados */
        width: 200px !important; /* Ajusta el ancho del botón */
        height: 50px !important;
    }

    .side_menu{
            position: fixed;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 150%; width: 231px; 
            position:absolute; 
            margin-top: -200px; 
            background-color:#FD9901; 
            z-index: 999;
    }

    .carousel-indicators {
    position: relative !important; /* O asegúrate de que no sea static */
    z-index: 1 !important; /* Dale un valor bajo para que esté detrás */
}

#grafico-pastel1 {
    position: relative; /* Asegúrate de que esté posicionado para aplicar z-index */
    z-index: 10 !important; /* Dale un valor más alto para que esté por encima del carousel-indicators */
}
    </style>

    <!-- Header -->
    <div class="header" style="background-color: orange !important; box-shadow: -10px 5px 5px #a77700">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1 style="margin-left: 41% !important;">Saint Gobain</h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual">prueba</p>
        </div>
    </div>
    <!-- Fin del Header -->

    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2><a href="../../dashboard_extern.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Presentamos los Dashboards de Saint Gobain</h2>
        </div>
    </div>

    <!-- Dashboard STG -->
    <div class="side_menu" id="side_menu" >

    <div class="bloque_d border border-success-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
        <img loading="lazy" class="img_helpdesk  bg-success" id="logo-stg" src="../../../images/Saint-Gobain.png" alt="">
    </div>

    <div class="" role="group" aria-label="Vertical button group" style="margin-top: 300px;" aria-label="Basic mixed styles example">
        <button type="button" class="btn btn-success custom-btn" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" aria-label="Slide 1"><p style="font-family:montserrat !important; font-size:large; color: black;">Import</p></button>
        <button type="button" class="btn btn-danger custom-btn" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"><p style="font-family:montserrat !important ; font-size:large; color:black; ">Export</p></button>
        <button type="button" class="btn btn-info custom-btn" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"><p style=" font-family:montserrat !important;font-size:large; color:black; ">Varios</p></button>
        <button type="button" class="btn btn-warning custom-btn" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"><p style="font-family:montserrat !important;font-size:large; color:black; ">Facturación</p></button>
    </div>
        
    </div>


<div id="carouselExampleIndicators" class="carousel slide btn-group" role="group">

  <div class="carousel-inner">
    <div class="carousel-item active">

    <div class="container_d" style="margin-left: 400px !important" >
        <div class="bloques_d ">

            <div class="bloque_d border border-success-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
                <div id="grafico-pastel1" class="bg-white" style="width: 100%; height: 100%;"></div>
            </div>

            <div class="bloque_d border border-success-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
          <p>IMPORT</p>
            </div>

            <div class="bloque_d border border-success-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">

            </div>
        </div>
    </div>

    </div>
    <div class="carousel-item">

        <div class="container_d" style="margin-left: 250px;">
        <div class="bloques_d ">

            <div class="bloque_d border border-danger-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
                
            </div>

            <div class="bloque_d border border-danger-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
            <p>Export</p>
            </div>

            <div class="bloque_d border border-danger-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">

            </div>
   
        </div>
    </div>
    </div>
    <div class="carousel-item">

    <div class="container_d" style="margin-left: 250px;">
        <div class="bloques_d ">

            <div class="bloque_d border border-info-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">

            </div>

            <div class="bloque_d border border-info-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
            <p>detalles varios</p>
            </div>

            <div class="bloque_d border border-info-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">

            </div>
        </div>
    </div>

    </div>
    <div class="carousel-item">

<div class="container_d" style="margin-left: 250px;">
    <div class="bloques_d ">

        <div class="bloque_d border border-warning-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
        </div>

        <div class="bloque_d border border-warning-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">
        <p>facturacion</p>
        </div>

        <div class="bloque_d border border-warning-subtle border-5" id="grafico1" style="height: 500px; width: 550px;">

        </div>
    </div>
</div>

</div>
  </div>

</div>



    <script>
        // Inicializar los gráficos de ECharts
        var chart1 = echarts.init(document.getElementById('grafico-pastel1'));

        // Función para obtener los datos del servidor y actualizar el gráfico
        function fetchData() {
            fetch('get_data_stg.php')
                .then(response => response.json())
                .then(data => {
                    // Configurar el gráfico de export
                    chart1.setOption({
                        color:['#2E8B57', '#FF8C33', '#FCEC52', '#ADEEE3', '#995FA3 ', '#F45B69', '#C3E991', '#8EA4D2', '#FFE1C6'],
                        title: {text: 'Importaciones', subtext: '', left: 'center'},
                        tooltip: {trigger: 'item'},
                        legend: {orient: 'vertical', left: 'left'},
                        series: [{
                            name: 'Export',
                            type: 'pie',
                            radius: ['30%', '80%'],
                            label: {formatter: '{c}', position: 'inside', fontSize: 25},
                            data: data,
                            itemStyle: {
                                borderRadius: 10,
                                borderColor:  '#fff',
                                borderWidth: 3
                            }
                        }]
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        
        // Llamar a la función fetchData para obtener los datos al cargar la página
        fetchData();
    </script>
    <script src="../../../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
