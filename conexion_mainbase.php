<?php

    $servername = "localhost"; // o el nombre del servidor proporcionado por Hostinger
    $username = "root";
    $password = "";
    $dbname = "db_mainbase";
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }
    header('Location: ../helpdesk.php');

?>

