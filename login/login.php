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
        $sql = "SELECT u.id, r.nombre_rol, usuario, pass, estado_id  FROM users u INNER JOIN roles r ON r.id = u.rol_id WHERE user = '$user';";
        $result = mysqli_query($conexion, $sql);
        

            if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_array($result);
                $password_hash = $row['pass'];

                if(password_verify($password, $password_hash)){
                    if ($row['nombre_rol'] == 'Admin'){
                        $_SESSION['user'] = $user;
                        $_SESSION['usuario'] = $row['usuario'];
                        $_SESSION['rol'] = $row['nombre_rol'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['estado'] = $row['estado_id'];
                        header("Location: ../helpdesk.php");
                    }
                    elseif ($row['nombre_rol'] == 'TV'){
                        $_SESSION['user'] = $user;
                        $_SESSION['usuario'] = $row['usuario'];
                        $_SESSION['rol'] = $row['nombre_rol'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['estado'] = $row['estado_id'];
                        header("Location: ../daily_plan/grafico.php");
                    }
                    elseif ($row['estado_id'] == 2){
                        header("Location: ../index.php?error=Usuario bloqueado");
                        exit();
                    }
                    else{
                        $_SESSION['user'] = $user;
                        $_SESSION['usuario'] = $row['usuario'];
                        $_SESSION['rol'] = $row['nombre_rol'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['estado'] = $row['estado_id'];
                        header("Location: ../helpdesk.php");
                        echo session_id();
                    }
                }
                else{
                    header("Location: ../index.php?error=Usuario o contraseña incorrectos");	
                    exit();
                }
            }
            else{
                header("Location: ../index.php?error=Usuario no existe");
                exit();
            }
    }
    
?>
