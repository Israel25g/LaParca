<?php

    $host = "localhost";
    $user = "u366386740_adminDP";
    $password = "1plGr0up01*";
    $db = "u366386740_db_dailyplan";

    $conexion = new mysqli($host, $user, $password, $db);

    if ($conexion->connect_error) {
        die("La conexión falló: " . $conexion->connect_error);
    }

?>
