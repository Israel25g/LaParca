
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrusel - IPL</title>
    <!-- Incluir Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../daily_plan/css/estilos.css">
    <link rel="shortcut icon" href="../images/ICO.png">
    <!-- Incluir ECharts desde el CDN -->
</head>
<body style="background-image:url('../host_virtual_TI/images/Motivo2.png');margin: 0;padding: 0; overflow:hidden">
  <div style="margin-top: 90px;" >
     <!-- Header -->
     <div class="headerr" style="width: 100%;background-color: var(--color1);display: flex;justify-content: space-between;align-items: center;padding: 1.5%;position: fixed;top: 0;left: 0;z-index: 1000;border-radius: 0 0 0px 50px;">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group" ></a>
        </div>
        <h1><a style="color: #fff; text-decoration:none" href="../daily_plan/index_DP.php">IPL Group</a></h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="display: block-inline;"> 

  <div class="carousel-inner">
    <!--data-bs-interval ajusta el tiempo de las graficas en pantalla -->
    <div class="carousel-item active "data-bs-interval="7500" style="height: 50%; height: 100%;position: fixed;">
      <img src="./imagenes/vision.png"  alt="vision" style="width: 100%; height:90% !important; position: flex;margin-top:1.9%;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/mision.png"  alt="mision"  style="width: 100%; height: 100%;display: flex;margin-top:2%;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/proposito.png"  alt="proposito" style="width: 100%; height: 90%;display: flex;margin-top:1%;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/seguridad.png"  alt="Seguridad" style="width: 100%; height: 90%;display: flex;margin-top:1%;z-index: 999;">
    </div>
    <!-- <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/cumpleaños_nov_1.jpg"  alt="cumpleaños 1"  style="width: 100%; height: 90%;display: flex;margin-top:1%;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/cumpleaños_nov_2.jpg"  alt="cumpleaños 2"  style="width: 100%; height: 90%;display: flex;;margin-top:1%;z-index: 999;">
    </div>
    <div class="carousel-item" data-bs-interval="7500">
      <img src="../daily_plan/imagenes/cumpleaños_nov_3.jpg" alt="cumpleaños 3"   style="width: 100%; height: 90%;display: flex;margin-top:1%;z-index: 999;"> -->
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
    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../host_virtual_TI/js/script.js"></script>

</body>
</html>
