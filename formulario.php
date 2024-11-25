<?php

include 'apertura_sesion.php';
include 'config.php';


if (isset($_POST['form'])) {

    $id_usuario = $_SESSION['id'];
    $idFormulario = $_POST['form'];
    $getLastForm = "SELECT nombre_formulario FROM u366386740_formularios order by nombre_formulario desc limit 1";
    $formBD = 1;
    $result = mysqli_query($conexion, $getLastForm);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $lastForm = $id_usuario;
        if ($idFormulario == $lastForm) {
?> <script>
                console.log('Es el último formulario');
                window.location.href = 'helpdesk.php';
            </script><?php
                    } elseif ($idFormulario < $lastForm) {
                        $userFormSeen = "SELECT last_seen_form_id FROM u366386740_versions_user WHERE user_id = '$id_usuario'";
                        $resultUserForm = mysqli_query($conexion, $userFormSeen);

                        if (mysqli_num_rows($resultUserForm) >= 0) {
                            $form_row = mysqli_fetch_array($resultUserForm);
                            $formVisto = $form_row['last_seen_form_id'];
                            if ($formVisto == $formBD) {
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
                                    $insertVersion = "INSERT INTO u366386740_versions_user (user_id,last_seen_form_id) VALUES ('$id_usuario', '$versionBD')";
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
            }



?>