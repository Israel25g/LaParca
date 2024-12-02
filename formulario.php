<?php

include 'apertura_sesion.php';
include 'config.php';


if (isset($_POST['formulario'])) {


    $id_usuario = $_SESSION['id'];

    $formularioIngresado = $_POST['formulario'];
    $getLastForm = "SELECT nombre_formulario FROM u366386740_formularios order by nombre_formulario desc limit 1";
    $formBD = 1;
    $result2 = mysqli_query($conexion, $getLastForm);
    if (mysqli_num_rows($result2) > 0) {
        $row = mysqli_fetch_array($result2);
        $lastForm = $row['nombre_formulario'];
        if ($formularioIngresado == $lastForm) {
?> <script>
                console.log('Ha visto el último formulario');
                window.location.href = 'helpdesk.php';
            </script><?php
                    } elseif ($formularioIngresado < $lastForm) {
                        $userFormSeen = "SELECT last_seen_form_id FROM u366386740_versions_user WHERE user_id = '$id_usuario'";
                        $resultUserForm = mysqli_query($conexion, $userFormSeen);

                        if (mysqli_num_rows($resultUserForm) >= 0) {
                            $ver_row = mysqli_fetch_array($resultUserForm);
                            $formVisto = $ver_row['last_seen_form_id'];
                            if ($formVisto == $formBD) {
                        ?>
                    <script>
                        console.log('El formulario es el último');
                        window.location.href = 'helpdesk.php';
                    </script>
                    <?php
                            } else {
                                $consultaFormBD = "SELECT * FROM u366386740_versions_user";
                                $consultaFBD = mysqli_query($conexion, $consultaFormBD);
                                if (mysqli_num_rows($consultaFBD) >= 0) {
                                    $insertForm = "INSERT INTO u366386740_versions_user (user_id,last_seen_form_id) VALUES ('$id_usuario', '$formBD')";
                                    $result2 = mysqli_query($conexion, $insertForm);
                                    if ($result2) {
                    ?>
                            <script>
                                console.log('Formulario actualizado');
                                window.location.href = 'helpdesk.php';
                            </script>
                        <?php
                                    } else {
                        ?>
                            <script>
                                console.log('Error al actualizar el Formulario');
                            </script>
                <?php
                                    }
                                }
                            }
                        } else {
                ?>
                <script>
                    console.log('No se encontró el formulario');
                </script>
<?php
                        }
                    }
                }
            }
