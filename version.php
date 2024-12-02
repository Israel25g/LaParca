<?php

include 'apertura_sesion.php';
include 'config.php';


if (isset($_POST['version'])) {

    $id_usuario = $_SESSION['id'];

    $versionIngresada = $_POST['version'];
    $getLastVersion = "SELECT version_number FROM u366386740_versions order by version_number desc limit 1";
    $getLastForm = "SELECT nombre_formulario FROM u366386740_formularios order by nombre_formulario desc limit 1";
    $versionBD = 2;
    $formBD = 1;
    $result = mysqli_query($conexion, $getLastVersion);
    $result2 = mysqli_query($conexion, $getLastForm);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $lastVersion = $row['version_number'];
        if ($versionIngresada == $lastVersion) {
?> <script>
                console.log('La versión es la última');
                window.location.href = 'helpdesk.php';
            </script><?php
                    } elseif ($versionIngresada < $lastVersion) {
                        $userVersionSeen = "SELECT last_seen_version_id FROM u366386740_versions_user WHERE user_id = '$id_usuario'";
                        $resultUserVersion = mysqli_query($conexion, $userVersionSeen);

                        if (mysqli_num_rows($resultUserVersion) >= 0) {
                            $ver_row = mysqli_fetch_array($resultUserVersion);
                            $versionVista = $ver_row['last_seen_version_id'];
                            if ($versionVista == $versionBD) {
                        ?>
                    <script>
                        console.log('La versión es la última');
                        window.location.href = 'helpdesk.php';
                    </script>
                    <?php
                            } else {
                                $consultaVersionBD = "SELECT * FROM u366386740_versions_user";
                                $consultaBD = mysqli_query($conexion, $consultaVersionBD);
                                if (mysqli_num_rows($consultaBD) >= 0) {
                                    $insertVersion = "INSERT INTO u366386740_versions_user (user_id,last_seen_version_id) VALUES ('$id_usuario', '$versionBD')";
                                    $result = mysqli_query($conexion, $insertVersion);
                                    if ($result) {
                    ?>
                            <script>
                                console.log('Versión actualizada');
                                window.location.href = 'helpdesk.php';
                            </script>
                        <?php
                                    } else {
                        ?>
                            <script>
                                console.log('Error al actualizar la versión');
                            </script>
                <?php
                                    }
                                }
                            }
                        } else {
                ?>
                <script>
                    console.log('No se encontró la versión');
                </script>
<?php
                        }
                    }
                }

                // Formulario

                
            }



?>