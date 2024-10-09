<?php
// Archivo de conexión a la base de datos (config_G.php)

// Configuración de la conexión
$servername = "localhost";
$username = "u366386740_adminDP";
$password = "1plGr0up01*";  // Sin contraseña
$dbname = "u366386740_db_dailyplan";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {

}
?>
