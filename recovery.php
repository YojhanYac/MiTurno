<?php

    require_once 'Objects/user.php';
    require_once 'Objects/user_session.php';
    require_once 'Objects/form.php';

    $dataUser["password"] = "";
    $dataUser["token"] = "";
    $validate = true;
    $error = [];
    
    //Se guarda la contraseña ingresada
    if(isset($_POST["password"])) {
        $dataUser["password"] = $_POST["password"];
    }

    //Validamos que las cadenas de texto tengan caracteres validos
    if(!empty($_GET) && $validate == true) {
    
        $errorForm = new Form();
        $validateToken = $errorForm->verifyString("token", $_GET["value"]);
        $validatePassword = true;

        if(isset($_POST["password"])) {
            $validatePassword = $errorForm->verifyString("contraseña", $_POST["password"]);
            if($validatePassword == false) {
                $error [] = "Ingrese una contraseña valida";
            }
            if(!empty($errorForm->errorForm("contraseña", $_POST['password']))){
                $error [] = $errorForm->errorForm("contraseña nueva", $_POST['password']);
            }    
        }

        if($validateToken == true && $validatePassword == true && empty($error) == true){
            $validate = true;
        } else {
            $validate = false;
        }
        
    }

    //Validamos el token que le llego por correo para cambiar la contraseña, si es correcta podrá cambiar la contraseña
    if(!empty($_GET) && $validate == true) {

        $user = new User();
        $token = $user->verifyToken($_GET["value"]);

        if(!empty($_POST["password"]) && $token == true) {
            $val = $user->setNewPassword($_GET["value"], password_hash($_POST["password"], PASSWORD_DEFAULT));
            if($val == true) {
                session_start();
                $msg = "Se ha establecido la nueva contraseña, aguarde un instante por favor";
                $_SESSION["ip"] = false;
            }
        } else if(!empty($_POST["password"]) && $token == false) {
            $error [] = "Enlace caducado, solicite nuevamente recuperar su contraseña";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>MiTurno</title>
    </head>
    <body id="page">
        <div class="form-login border-line-red ">
            <form action="" name="recoveryPassword" class="form-input-password border-line-green" method="post">
                <input class="input-text form-control" type="password" placeholder="Nueva contraseña" name="password" value="<?php if(!empty($_POST)){ echo $dataUser["password"];} ?>">
                <?php
                    //Se muestran los errores de los campos del formulario
                    if(!empty($error)) { 
                        echo "<span>";
                        for($i = 0; $i < count($error); $i++) {
                            echo $error[$i]."<br>";
                        }
                        echo"</span>";
                    }
                    //Se muestra mensaje de validación de contraseña
                    if(!empty($msg)) { 
                        echo '<span style="color: rgb(4, 165, 4);">'. $msg .'</span>';
                    }
                    //Si todo salió bien, lo redirigimos al inicio para que ingrese las credenciales
                    if(isset($_SESSION["ip"])) {
                        header( "refresh:7; url=index.php" );
                    }
                ?>
                <button type="submit" class="btn btn-primary button-send">Confirmar nueva contraseña</button>
            </form>
            <div class="function-login border-line-red">
                <a class="btn btn-link border-line-green font-game-shadow" href="register.php">Registrarse</a>
                <p class="mb-3 border-line-blue font-game-shadow">Estás registrado?<a href="index.php" class="border-line-orange font-game-shadow"> Ingresar</a></p>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>