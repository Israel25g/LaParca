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
        // $password = $_POST['password'];
        $password = $_POST['password'];
        

        $sql = "SELECT * FROM users WHERE user='$user'";
        $result = mysqli_query($conexion, $sql);
        
        // if(password_verify($_POST['password'], $password_hash)){

            if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);
                $password_hash = $row['pass'];

                if(password_verify($password, $password_hash)){
                    $_SESSION['user'] = $row['usuario'];
                    $_SESSION['pass'] = $row['pass'];
                    $_SESSION['id_user'] = $row['id_user'];
                    header("Location: ../helpdesk.php");
                    exit();
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
        // }
        
    }

?>

<script src="../js/alertas.js"></script>
<script src="../js/script.js"></script>