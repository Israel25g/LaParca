<?php

include 'apertura_sesion.php';
include 'config.php';


if (isset($_POST['version'])) {

    $id_usuario = $_SESSION['id'];

    $versionIngresada = $_POST['version'];
    $getLastVersion = "SELECT version_number FROM u366386740_versions order by version_number desc limit 1";
    $result = mysqli_query($conexion, $getLastVersion);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $lastVersion = $row['version_number'];
        if ($versionIngresada == $lastVersion) {
?> <script>
                console.log('La versión es la última');
                window.location.href = 'helpdesk.php';
            </script><?php
                    } elseif ($versionIngresada < $lastVersion) {
                        $consultaVersionBD = "SELECT * FROM u366386740_versions_user";
                        $consultaBD = mysqli_query($conexion, $consultaVersionBD);
                        if (mysqli_num_rows($consultaBD) > 0) {
                            $insertVersion = "INSERT INTO u366386740_versions_user (user_id,last_seen_version_id) VALUES ('$id_usuario', '2')";
                            $result = mysqli_query($conexion, $insertVersion);
                            if ($result) {
                        ?>
                    <script>
                        console.log('Versión actualizada');
                        window.location.href = 'helpdesk.php';
                    </script>
<?php
                            } else {
                                echo "Error al actualizar la versión";
                            }
                        }
                    }
                } else {
                    echo "No se encontró la versión";
                }
            }

?>