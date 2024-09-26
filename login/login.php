<?php

    session_start();

    include("Conexion.php");

    if(isset($_POST['user']) && isset($_POST['password'])){
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $user = validate($_POST['user']);
        $password = $_POST['password'];
        

        // $sql = "SELECT * FROM users WHERE user='$user'";
        $sql = "SELECT u.id, r.nombre_rol AS role, pass FROM users u INNER JOIN roles r ON r.id = u.rol_id WHERE user = '$user';";
        $result = mysqli_query($conexion, $sql);
        

            if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);
                $password_hash = $row['pass'];

                if(password_verify($password, $password_hash)){
                    // $_SESSION['user'] = $row['usuario'];
                    // $_SESSION['pass'] = $row['pass'];
                    // $_SESSION['id_user'] = $row['id_user'];
                    $startingPage = [
                        "Admin" => "../helpdesk.php",
                        "EEMP" => header("Location: ../helpdesk.php"),
                    ];


                    // header("Location: ../helpdesk.php");
                    // exit();
                }
                else{
                    header("Location: index.php?error=Usuario o contraseña incorrectos");
                    exit();
                }
            }
            else{
                header("Location: index.php?error=Usuario y/o contraseña incorrectos");
                exit();
            }
    }
?>

<script src="../js/alertas.js"></script>
<script src="../js/script.js"></script>