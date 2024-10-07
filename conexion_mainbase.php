<?php

    $host = "88.223.84.91";
    $user = "u366386740_adminDP";
    $password = "1plGr0up01*";
    $db = "u366386740_db_mainbase";

    $conexion = new mysqli($host, $user, $password, $db);

    if ($conexion->connect_error) {
        die("La conexión falló: " . $conexion->connect_error);
    }

?>

<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">