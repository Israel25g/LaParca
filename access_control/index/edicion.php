<?php
include("../../apertura_sesion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../login/styles.css">
    <link rel="shortcut icon" href="images\ICO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <meta http-equiv="refresh" content="5"> -->

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Edición de usuarios - IPL DIPROP</title>
</head>
<body>

    <style>
        body{
            margin: 0;
            padding: 0;
            background: linear-gradient(195deg,rgba(12, 121, 72,0.5), rgba(38, 37, 68,0.95)), url('../../images/IMG_9533.jpg');
            background-size: cover;
        }
    </style>

    <div class="overlay"></div>
    <h1 class="mt-3 nombre-tabla" style="margin: 10px;"><a href="./index_users2.php"><i class="bi bi-caret-left-fill arrow-back"></i></a>Edición de usuarios</h1>

    <?php
        if(isset($_GET['error'])){
        ?>
        <div class="alert alert-danger" role="alert">
            <div class="error" >
                <?php 
                    echo $_GET['error']; 
                ?>
            </div>
        </div>
        <?php
        }
        ?>

    <?php
        include("../../config.php");
    ?>
 

    <div class="form-container">
        <form id="formRegistro" action="registro.php" method="POST">
            <label for="text"><i class="fa-solid fa-user"></i> Nombre</label>
            <input type="text" name="name" id="name" required>

            <label for="text"><i class="fa-solid fa-user"></i> Usuario</label>
            <input type="text" name="user" id="user" required>
            
            <label for="email"><i class="fa-solid fa-user"></i> Correo electrónico</label>
            <input type="email" name="email" id="email" required>
            
            <?php
                $sql = "SELECT * FROM roles";
                $result = mysqli_query($conexion, $sql);

                if(mysqli_num_rows($result) > 0){
                    ?>
                    <label for="text"><i class="fa-solid fa-user-group"></i> Departamento</label>
                    <br>
                    <select type="text" name="departamento" id="departamento" required>
                        <option value=" ">Seleccione un departamento...</option>
                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['nombre_rol'] ?></option>
                        <?php
                    }
                    ?>
                    </select>
                    <?php
                }
            ?>
            
            <button type="submit" class="edit"><i class="bi bi-pencil-square"></i> Editar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/alertas.js"></script>
</body>
</html>