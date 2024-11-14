<?php

include 'apertura_sesion.php';
include 'config.php';


if (isset($_POST['version'])) {
    $versionIngresada = $_POST['version'];
    $getLastVersion = "SELECT version_number FROM u366386740_versions order by version_number desc limit 1";
    $result = mysqli_query($conexion, $getLastVersion);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $lastVersion = $row['version_number'];
        if ($versionIngresada == $lastVersion) {
            echo "La versión es la más reciente";
        } elseif ($versionIngresada < $lastVersion) {
            echo "Hay una nueva versión disponible";
        } elseif ($versionIngresada > $lastVersion) {
            echo "La versión ingresada es mayor a la más reciente";
        }
    } else {
        echo "No se encontró la versión";
    }
}

?>