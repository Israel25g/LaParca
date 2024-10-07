<?php

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "db_mainbase";

    $conexion = new mysqli($host, $user, $password, $db);

    if ($conexion->connect_error) {
        die("La conexión falló: " . $conexion->connect_error);
    }

?>
