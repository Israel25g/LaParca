<?php
// Archivo de conexión a la base de datos (config_G.php)

// Configuración de la conexión
$servername = "localhost";
$username = "u366386740_admin123";
$password = "1plGr0up01*"; 
$dbname = "u366386740_db_mainbase";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {

}
?>

<!--==============================testing=====================================
    $servername = "localhost";
    $database = "u366386740_db_test_main";
    $username = "u366386740_adminTestMain";
    $password = "1plGr0up01*"; 
    ==============================testing======================================-->

    <!--==============================localhost================================
    $servername = "localhost";
    $database = "db_mainbase";
    $username = "root";
    $password = "";
    ==============================localhost=====================================-->

    <!-- ==============================PRODUCTIVO================================
    $servername = "localhost";
    $username = "u366386740_adminDP";
    $password = "1plGr0up01*"; 
    $dbname = "u366386740_db_dailyplan";
    ==============================PRODUCTIVO======================================-->



