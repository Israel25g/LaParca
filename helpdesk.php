<?php
include 'apertura_sesion.php';
include 'config.php';

// Jala el id del usuario
$id_usuario = $_SESSION['id'];

// Jala la versión ingresada
$getLastVersion = "SELECT version_number FROM u366386740_versions order by version_number desc limit 1";
$result = mysqli_query($conexion, $getLastVersion);

// Jala el formulario ingresado
$getLastForm = "SELECT nombre_formulario FROM u366386740_formularios order by nombre_formulario desc limit 1";
$result2 = mysqli_query($conexion, $getLastForm);

if ($result && mysqli_num_rows($result) > 0) {
    $lastVersion = mysqli_fetch_array($result)['version_number'];
} else {
    $lastVersion = 0.0;
}

if ($result2 && mysqli_num_rows($result2) > 0) {
    $lastForm = mysqli_fetch_array($result2)['nombre_formulario'];
} else {
    $lastForm = 0;
}

// version del usuario
$getUserVersion = "SELECT last_seen_version_id FROM u366386740_versions_user WHERE user_id = '$id_usuario' ORDER BY last_seen_version_id DESC LIMIT 1";
$getUserForm = "SELECT last_seen_form_id FROM u366386740_versions_user WHERE user_id = '$id_usuario' ORDER BY last_seen_form_id DESC LIMIT 1";
$getUserNotis = "SELECT last_seen_notis_id FROM u366386740_versions_user WHERE user_id = '$id_usuario' ORDER BY last_seen_notis_id DESC LIMIT 1";

$userResult = mysqli_query($conexion, $getUserVersion);
$userResult2 = mysqli_query($conexion, $getUserForm);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userVersion = mysqli_fetch_array($userResult)['last_seen_version_id'];
} else {
    $userVersion = 0.0;
}

if ($userResult2 && mysqli_num_rows($userResult2) > 0) {
    $userForm = mysqli_fetch_array($userResult2)['last_seen_form_id'];
} else {
    $userForm = 0;
}

// mostrar modal
$showModal = $userVersion !== null && $lastVersion !== null && $userVersion < $lastVersion;
// $showModal2 = $userForm === null;
$showModal3 = $userForm === null;


?>

<script>
    Notification.requestPermission().then(function(result) {
        console.log(result);
    });

    var img = 'https://iplgsc.com/images/ADOC.jpg';
    var text = 'Esta es una notificación push de IPL Group';
    var notificacion = new Notification('IPL Group', {
        body: text,
        icon: img
    });
</script>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk</title>
    <link rel="shortcut icon" href="images\ICO.png">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- estilos -->
    <link rel="stylesheet" href="main-global.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado completamente');
            var showModal = <?php echo $showModal ? 'true' : 'false'; ?>;
            if (showModal) {
                var myModal = new bootstrap.Modal(document.getElementById('version'), {
                    keyboard: false
                });
                myModal.show();
            } else {
                console.log('No se muestra el modal');
            }
        });
    </script>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var showModal2 = <?php echo $showModal2 ? 'true' : 'false'; ?>;
            if (showModal2) {
                var myModal2 = new bootstrap.Modal(document.getElementById('formulario'), {
                    keyboard: false
                });
                myModal2.show();
            } else {
                console.log('No se muestra el modal');
            }
        });
    </script> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado completamente');
            var showModal3 = <?php echo $showModal3 ? 'true' : 'false'; ?>;
            if (showModal3) {
                var myModal = new bootstrap.Modal(document.getElementById('notificaciones'), {
                    keyboard: false
                });
                myModal.show();
            } else {
                console.log('No se muestra el modal');
            }
        });
    </script>
</head>

<body style="background-image: url('./images/Motivo2.png')">
    <!-- Header -->
    <div class="header-error">
        <div class="logo-container">
            <a href="https://iplgsc.com" target="_blank"><img class="logo" src="images/IPL.png" alt="Logo_IPL_Group"></a>
        </div>
        <h1>Helpdesk</h1>
        <div class="cuadroFecha">
            <p id="fecha-actual"></p>
            <p id="hora-actual"></p>
        </div>
    </div>
    <!-- Fin del Header -->

    <!-- Navbar -->
    <div class="container-nav">
        <div class="my-navbar">
            <ul class="my-nav" id="detallesOps">
                <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
                <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                <li class="nav-li"><a class="active" href="#">Mesa de Ayuda (Tickets)</a></li>
                <li class="nav-li"><a href="./daily_plan/index_DP.php<?php session_id() ?>">Daily Plan</a></li>
                <?php
                if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'EEMP') {
                    echo '<li class="nav-li"><a href="Dashboards/dashboards.php">Dashboards</a></li>';
                }
                ?>

                <?php
                if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'EEMP') {
                    echo '<li class="nav-li"><a href="access_control/index/index_users2.php">Control de Usuarios</a></li>';
                }
                ?>
                <?php
                if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'EEMP') {
                    echo '<li class="nav-li"><a href="./hora_servidor.php">Hora del Servidor</a></li>';
                }
                ?>
                <li class="nav-li" onclick="push();"><a href="#" class="cierre">Notificación push</a></li>
                <li class="nav-li"><a class="cierre" href="login/CerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
            <div class="sessid"><span class="id_sesion">Usuario: <?php echo ($_SESSION['usuario']) ?></span></div>
        </div>
    </div>
    <!-- Fin Navbar -->

    <!-- Links -->
    <div class="container-descripcion">
        <div class="bloque-descripcion">
            <h2>¿En qué podemos ayudarte?</h2>
            <h4>Selecciona una de las siguientes opciones para generar un ticket de soporte.</h4>
        </div>
    </div>

    <!-- Mantenimiento -->
    <div class="container-block">
        <div class="bloques-grid">
            <a href="host_virtual_M\index\index_m.php" rel="noopener noreferrer">
                <div class="bloque" id="bloque_mantenimiento">
                    <img loading="lazy" class="img_helpdesk" id="img_mantenimiento" src="images\Mantenimiento.webp" alt="">
                    <div class="my-text-overlay">
                        <h3>Soporte de mantenimiento</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna reparación en la infraestructura de la empresa y/o departamento.</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Mantenimiento -->

            <!-- IT -->
            <a href="host_virtual_TI/index/index_ti.php">
                <div class="bloque" id="bloque_IT">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="images\Tecnología.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Soporte de Tecnología</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna reparación en el apartado de IT (Reparaciones y mantenimiento de equipos, reparación de software interno, etc...).</p>
                    </div>
                </div>
            </a>
            <!-- Fin de IT -->

            <!-- EEMP -->
            <a href="host_virtual_EEMP/index/index_eemp.php">
                <div class="bloque" id="bloque_EEMP">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://cdn.udax.edu.mx/blog/la-clave-del-exito-en-los-negocios-mejora-continua-y-optimizacion-de-procesos_1.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Soporte de Mejoras</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar solicitudes de mejora/DEPIC's en cuanto al sistema DIPROP</p>
                    </div>
                </div>
            </a>
            <!-- Fin de EEMP -->
            <!-- Seguimiento de temas -->
            <?php
            $usuarios_admitidos = ['igondola', 'agaray', 'nrivas', 'wlemos', 'riromero', 'kdelgado', 'ssalazar', 'abethancourt', 'jgrant', 'rolivero', 'igondola01'];
            if (in_array($_SESSION['user'], $usuarios_admitidos)) {
                echo '<a href="host_virtual_seguimiento/index/index_seguimiento.php">
                                <div class="bloque" id="bloque_seguimiento">
                                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://www.marketingdirecto.com/wp-content/uploads/2021/09/atencion-al-cliente.png" alt="">
                                    <div class="my-text-overlay">
                                        <h3>Seguimiento de temas pendientes</h3>
                                        <p>Este formulario es para darle seguimiento a los temas pendientes referentes al flujo de comunicación entre los departamentos de Operaciones y SAC</p>
                                    </div>
                                </div>
                              </a>';
            }
            ?>

            <!-- <a href="host_virtual_seguimiento/index/index_seguimiento.php">
                <div class="bloque" id="bloque_seguimiento">
                    <img loading="lazy" class="img_helpdesk" id="img_IT" src="https://www.marketingdirecto.com/wp-content/uploads/2021/09/atencion-al-cliente.png" alt="">
                    <div class="my-text-overlay">
                        <h3>Seguimiento de temas pendientes</h3>
                        <p>Este formulario es para darle seguimiento a los temas pendientes referentes al flujo de comunicación entre los departamentos de Operaciones y SAC</p>
                    </div>
                </div>
            </a> -->
            <!-- Fin de Seguimiento de temas -->

            <!-- Sobre tiempo -->
            <a target="https://forms.office.com/Pages/ShareFormPage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u&sharetoken=NhdNt0HuLG7WFd5Sc9QH" href="https://forms.office.com/Pages/ResponsePage.aspx?id=1za0vDzJD0-phmo__OXrx2b99J_0mT9Gmm12a6wX-nhUNU9NN0lCUTY0RzFENE1WRU5USFJEOE4zWC4u">
                <div class="bloque" id="bloque_sobretiempo">

                    <img loading="lazy" class="img_helpdesk" id="img_overtime" src="images\Sobretiempo.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Solicitud de Sobretiempo</h3>
                        <p>Este formulario es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud de sobretiempo.</p>
                    </div>
                </div>
            </a>
            <!-- Fín de sobretiempo -->

            <!-- Compras -->
            <a href="https://forms.office.com/r/JSRrVt475n" target="_blank" rel="noopener noreferrer">
                <div class="bloque" id="bloque_cotización">
                    <img loading="lazy" class="img_helpdesk" id="img_cotizacion" src="https://consultorfinancontable.com/wp-content/uploads/2024/03/mujer-asiatica-trabajando-traves-papeleo_53876-138148.jpg" alt="">
                    <div class="my-text-overlay">
                        <h3>Solicitud de orden de compra</h3>
                        <p>Este helpdesk es exclusivamente para la generación de tickets con el objetivo de realizar alguna solicitud cotizaciones.</p>
                    </div>
                </div>
            </a>
            <!-- Fin de Compras -->
        </div>
    </div>



    <?php
    $url = $url = "https://api.github.com/repos/Israel25g/LaParca/tags";

    // Inicializamos cURL
    $ch = curl_init($url);

    // token
    $token = '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';

    // Configuramos cURL para que nos devuelva el resultado como cadena
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'IPL_Group');

    // Ejecutamos la petición
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificamos el JSON
    $tags = json_decode($response, true);


    // verificar los tags recibidos

    if (!empty($tags)) {
        $lastTag = $tags[0]['name'];
    } else {
        echo "No se encontraron tags";
    }
    ?>

    <!-- Notas de la versión -->
    <div class="version-notes" id="version-sistema" data-bs-toggle="modal" data-bs-target="#version">
        <p class="m-0">Versión <?php echo $lastTag; ?></p>
    </div>


    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div
        class="modal fade"
        id="version"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitleId"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitleId">
                        Notas de la versión <?php echo $lastTag ?>
                    </h3>
                </div>
                <div class="modal-body">
                    <?php include 'notas_version.php' ?>

                </div>
                <div class="modal-footer">
                    <p class="text-center">Para ver la imagen con más detalle, haga click derecho sobre ella y luego "Abre la imagen en nueva pestaña"</p>
                    <br>

                    <form action="version.php" method="post">
                        <input type="hidden" name="version" value="<?php echo $lastTag ?>">
                        <button
                            type="submit"
                            class="btn btn-success"
                            data-bs-dismiss="modal">
                            ¡He visto la actualización!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================================================================================================================== -->
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div
        class="modal fade"
        id="formulario"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitleId"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitleId">
                        Encuesta de satisfacción
                    </h3>
                </div>
                <div class="modal-body" style="::-webkit-scrollbar{width:0px ;}">
                    <iframe width="1140px" height="480px" src="https://forms.office.com/Pages/ResponsePage.aspx?id=1za0vDzJD0-phmo__OXrx_i4ZMVL7d5Bl3Uid2V54-BURENSSE5RODNRRjRNVDJQVUU1RzdUUkRQMi4u&embed=true" frameborder="0" marginwidth="0" marginheight="0" style="border: none; max-width:100%; max-height:100vh; ::-webkit-scrollbar{width:0px ;}" allowfullscreen webkitallowfullscreen mozallowfullscreen msallowfullscreen></iframe>
                </div>
                <div class="modal-footer">
                    <p class="text-center"> En caso de no mostrarse el formulario, verifique su sesión en <a href="https://www.office.com/?auth=2">Office 365</a></p>
                    <br>

                    <form action="formulario.php" method="post">
                        <?php $ultForm ?>
                        <input type="hidden" name="formulario" value="<?php echo $ultForm ?>">
                        <button
                            id="botonHabilitar"
                            type="submit"
                            class="btn btn-success"
                            data-bs-dismiss="modal"
                            disabled>
                            Favor llenar el formulario. Tiempo restante: (<strong><span id="countdown">25</span></strong>) segundos...
                        </button>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const botonHabilitar = document.getElementById('botonHabilitar');
                                const countdownSpan = document.getElementById('countdown');
                                let remainingTime = 25; // Tiempo inicial en segundos

                                // Actualiza la cuenta regresiva cada segundo
                                const countdownInterval = setInterval(() => {
                                    remainingTime--;
                                    countdownSpan.textContent = remainingTime;

                                    if (remainingTime <= 0) {
                                        clearInterval(countdownInterval); // Detiene la cuenta regresiva
                                        botonHabilitar.disabled = false; // Habilita el botón
                                        document.getElementById('botonHabilitar').textContent = "He llenado el formulario";
                                    }
                                }, 1000);
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <?php
            #if (isset($_GET['error'])) {
            ?>
        <script>
            Command: toastr["error"]("No tienes permiso para acceder a este apartado", <?php #$_GET['error'] 
                                                                                        ?>)
        </script><?php
                    #}
                    ?> -->
    <!-- Optional: Place to the bottom of scripts -->
    <!-- <script>
        const myModal = new bootstrap.Modal(
            document.getElementById("version"),
            options,
        );
    </script> -->

    <!-- ========================================================================================================================================== -->

    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div
        class="modal fade"
        id="notificaciones"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalTitleId"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        aria-hidden="true">
        <div
            class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitleId">
                        Implementación de notificaciones push
                    </h3>
                </div>
                <div class="modal-body" style="::-webkit-scrollbar{width:0px ;}">
                    <p>
                        A partir de la fecha, el sistema DIPROP cuenta con la implementación de notificaciones push directamente integradas en el equipo esto con el objetivo de mejorar la trazabilidad de tareas y mejorar la comunicación dentro de las operaciones que se realicen dentro de la plataforma. Para esto, es necesario que el usuario autorice las notificaciones push haciendo click en este boton.
                    </p>
                </div>
                <div class="modal-footer">
                    <button id="habilitarNotificaciones" class="btn btn-primary">Hablitar Notificaciones</button>
                        <script>
                            window.addEventListener('load', () => {
                                document.getElementById('habilitarNotificaciones').addEventListener('click', () =>{
                                    Notification.requestPermission().then(function(permiso){
                                        if(permiso === 'granted'){
                                         console.log('Notificaciones habilitadas');
                                        }
                                    })
                                })
                            })
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./host_virtual_TI/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/2.1.8/dataRender/datetime.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.2/kt-2.12.1/r-3.0.3/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>




</body>

</html>