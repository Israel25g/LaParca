<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Datos - Daily Plan</title>
    <link rel="stylesheet" href="../estilos.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../images/ICO.png">
</head>

<body style="background-image:url('../host_virtual_TI/images/Motivo2.png');margin: 0;padding: 0; font-family:montserrat;">
    <div class="form-table">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <a href="https://iplgsc.com" target="_blank"><img class="logo" src="../images/IPL.png" alt="Logo_IPL_Group"></a>
            </div>
            <h1>Daily plan</h1>
            <div class="cuadroFecha">
                <p id="fecha-actual"></p>
                <p id="hora-actual">prueba</p>
            </div>
        </div>
        <!-- Fin del Header -->

        <!-- Navbar -->
        <div class="container-nav">
            <div class="navbar">
                <ul class="nav" id="detallesOps">
                    <!-- <li class="nav-li"><a href="Index.html">Inicio</a></li> -->
                    <!-- <li class="nav-li"><a href="#">Capacitaciones</a></li> -->
                    <li class="nav-li"><a href="#">Mesa de Ayuda (Tickets)</a></li>
                    <li class="nav-li"><a class="active" href="#">Daily Plan</a></li>
                    <li class="nav-li"><a href="Dashboards/dashboards.php">Dashboards</a></li>
                    <li class="nav-li"><a class="cierre" href="./login/CerrarSesion.php">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
        <!-- Fin Navbar -->
        
        <?php
        include '../daily_plan/funcionalidades/funciones.php';

        if (isset($_POST['submit'])) {
            $resultado = [
                'error' => false,
                'mensaje' => 'La planificacion de ' . $_POST['cliente'] . ' ha sido agregada con éxito'
            ];
            $config = include '../daily_plan/funcionalidades/config_DP.php';

            try {
                $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
                $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

                $datos = array(
                    "aid_oid" => $_POST['aid_oid'],
                    "cliente" => $_POST['cliente'],
                    "vehiculo" => $_POST['vehiculo'],
                    "t_vehiculo" => $_POST['t_vehiculo'],
                    "bl" => $_POST['bl'],
                    "destino" => $_POST['destino'],
                    "paletas" => $_POST['paletas'],
                    "cajas" => $_POST['cajas'],
                    "unidades" => $_POST['unidades'],
                    "pedidos_en_proceso" => $_POST['pedidos_en_proceso'],
                    "fecha_objetivo" => $_POST['fecha_objetivo'],
                    "comentario_oficina" => $_POST['comentario_oficina'],
                    "comentario_bodega" => $_POST['comentario_bodega']

                );

                $consultaSQL = "INSERT INTO picking (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina, comentario_bodega) ";
                $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina, :comentario_bodega)";

                $sentencia = $conexion->prepare($consultaSQL);
                $sentencia->execute($datos);

                $consultaSQL = "INSERT INTO picking_r (aid_oid, cliente, vehiculo, t_vehiculo, bl, destino, paletas, cajas, unidades, pedidos_en_proceso, fecha_objetivo, comentario_oficina, comentario_bodega) ";
                $consultaSQL .= "VALUES (:aid_oid, :cliente, :vehiculo, :t_vehiculo, :bl, :destino, :paletas, :cajas, :unidades, :pedidos_en_proceso, :fecha_objetivo, :comentario_oficina, :comentario_bodega)";

                $sentencia = $conexion->prepare($consultaSQL);
                $sentencia->execute($datos);
            } catch (PDOException $error) {
                $resultado['error'] = true;
                $resultado['mensaje'] = $error->getMessage();
            }
        }
        ?>
        <div class="container" style="margin-top: 10%">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mb-2">Ingrese los datos para crear Daily plan de Picking</h3>
                    <a class="mb-2 btn btn-success btn-md" href="../daily_plan/tabla_pk.php" style="margin-top: 2%">Volver a la tabla</a>
                    <hr>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="aid_oid">OID</label>
                                <input type="text" name="aid_oid" id="aid_oid" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cliente">Cliente</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vehiculo">Vehículo</label>
                                <input type="text" name="vehiculo" id="vehiculo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="t_vehiculo">Tipo de Vehículo</label>
                                <input type="text" name="t_vehiculo" id="t_vehiculo" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="bl">BL</label>
                                <input type="text" name="bl" id="bl" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="destino">Destino</label>
                                <input type="text" name="destino" id="destino" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="paletas">Paletas</label>
                                <input type="number" name="paletas" id="paletas" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cajas">Cajas</label>
                                <input type="number" name="cajas" id="cajas" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="unidades">Unidades</label>
                                <input type="number" name="unidades" id="unidades" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pedidos_en_proceso">Pedidos en Proceso</label>
                                <input type="number" name="pedidos_en_proceso" id="pedidos_en_proceso" class="form-control" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="fecha_objetivo">Fecha Objetivo</label>
                                <input type="date" id="fecha_objetivo" name="fecha_objetivo" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_oficina">Comentario Oficina</label>
                                <textarea name="comentario_oficina" id="comentario_oficina" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario_bodega">Comentario Bodega</label>
                                <textarea name="comentario_bodega" id="comentario_bodega" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <!-- <div class="form-row">
        <div class="form-group col-md-3">
            <label for="vacio_lleno">Vacío o Lleno</label>
            <input type="text" name="vacio_lleno" id="vacio_lleno" class="form-control">
        </div> -->
    </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Enviar">
                        </div>
                    </form>

                </div>
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            </div>
        </div>
    </div>
    </div>
    <script src="../host_virtual_TI/js/script.js"></script>
    <?php
    if (isset($resultado)) {
    ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12" style="margin-top: -1000px; margin-left:30%">
                    <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                        <?= $resultado['mensaje'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>