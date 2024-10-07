<?php

    $servername = "localhost"; // o el nombre del servidor proporcionado por Hostinger
    $username = "u366386740_adminDP";
    $password = "1plGr0up01*";
    $dbname = "u366386740_db_dailyplan";
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }
    echo "Conexión exitosa";

?>

