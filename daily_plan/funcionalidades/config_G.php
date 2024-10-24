<?php
// Archivo de conexión a la base de datos (config_G.php)

// Configuración de la conexión
// Archivo de conexión a la base de datos (config_G.php)

// Configuración de la conexión
$servername = "localhost";
$database = "u366386740_db_test_dp";
$username = "u366386740_adminTestDP";
$password = "1plGr0up01*"; 

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
