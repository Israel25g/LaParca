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
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../../../images/Salida2.gif" alt="Logo_IPL_Group"></a>
        </div>
        <h1 style="margin-left: 35% !important;">Reportes externos</h1>
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

    <div id="carouselExampleCaptions" class="carousel slide">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="bg bg-dan active bg bg-danger" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" class="bg bg-success" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" class="bg bg-warning" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" class="bg bg-info" aria-label="Slide 4"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
        <div
            class="row justify-content-center align-items-center g-2"
        >
            <div class="col">Column</div>
            <div class="col">Column</div>
            <div class="col">Column</div>
            <div class="col">Column</div>
            <div class="col">Column</div>
            <div class="col">Column</div>
        </div>
        
        <p>the first slide.</p>

    </div>
    <div class="carousel-item">

        <p>second slide.</p>

    </div>

    <div class="carousel-item">

        <p>the third slide.</p>
    </div>

    <div class="carousel-item">

        <p>the fourth slide.</p>

    </div>

  </div>
  <button class="carousel-control-prev bg-primary" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="false"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next bg-primary" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="false"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    <button
        class="btn btn-primary"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#Id2"
        aria-controls="Id1"
    >
        Enable both scrolling & backdrop
    </button>
    
    <div
        class="offcanvas offcanvas-start"
        data-bs-scroll="true"
        tabindex="-1"
        id="Id2"
        aria-labelledby="Enable both scrolling & backdrop"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">
                Backdrop with scrolling AAAAAAAAAAA
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
            ></button>
        </div>
        <div class="offcanvas-body">
            <p>
                Try scrolling the rest of the page to see this option in
                action.
            </p>
        </div>
    </div>
    

    <button
        class="btn btn-primary"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#Id1"
        aria-controls="Id1"
    >
        Enable both scrolling & backdrop
    </button>
    
    <div
        class="offcanvas offcanvas-start"
        data-bs-scroll="true"
        tabindex="-1"
        id="Id1"
        aria-labelledby="Enable both scrolling & backdrop"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="Enable both scrolling & backdrop">
                Backdrop with scrolling
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
            ></button>
        </div>
        <div class="offcanvas-body">
            <p>
                Try scrolling the rest of the page to see this option in
                action.
            </p>
        </div>
    </div>
    

    <script src="../../../host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
