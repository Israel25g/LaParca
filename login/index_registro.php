<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <!-- <meta http-equiv="refresh" content="5"> -->

    <!-- Libreria para alertas ----->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Inicio de Sesión</title>
</head>
<body>

    <style>
        body{
            background-image: url('../images/IMG_9533.JPG');
            margin: 0;
            padding: 0;
            background-size: cover;
        }
    </style>

    <div class="overlay"></div>
    <h1>Registro de usuarios</h1>

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
 

    <div class="form-container">
        <form id="formRegistro" action="registro.php" method="POST">
            <label for="text"><i class="fa-solid fa-user"></i> Usuario / Correo</label>
            <input type="text" name="user" id="user" required>
    
            <label for="password"><i class="fa-solid fa-unlock"></i> Contraseña</label>
            <input type="password" name="password" id="password" required>
            
            <label for="password2"><i class="fa-solid fa-unlock"></i> Vuelva a introducir la contraseña</label>
            <input type="password" name="password2" id="password2" required>
            
            <button type="submit" class="edit"><i class="fa-solid fa-pencil"></i> Registrar</button>
        </form>
        <a class="recuperar" href="index.php"><button class="btn-recover">Inicio de sesión</button></a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/alertas.js"></script>
</body>
</html>