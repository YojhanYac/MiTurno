<?php

    require_once 'Objects/user.php';
    require_once 'Objects/user_session.php';
    require_once 'Objects/form.php';

    $dataUser["password"] = "";
    $dataUser["email"] = "";
    $validate = true;
    $error = [];

    //Validamos que tenga caracteres validos enviados en el formulario
    if(!empty($_POST) && $validate == true) {

        $dataUser["password"] = $_POST["password"];
        $dataUser["email"] = $_POST["email"];

        $errorForm = new Form();
        $validatePassword = $errorForm->verifyString("contraseña", $_POST['password']);
        $validateEmail = $errorForm->verifyString("correo", $_POST['email']);

        if($validateEmail == true && $validatePassword == true) {
            $validate = true;
        } else {
            $validate = false;
            $error [] = "Datos incorrectos";
        }
    }

    //Validamos los errores de los campos del formulario
    if(!empty($_POST) && $validate == true) { 

        if(!empty($errorForm->errorForm("correo", $_POST['email']))) {
            $error [] = $errorForm->errorForm("correo", $_POST['email']);
        }
        if(!empty($errorForm->errorForm("contraseña", $_POST['password']))) {
            $error [] = "Datos incorrectos";
        }
        if(empty($error)) {
            $validate = true;
        } else {
            $validate = false;
        }
    }

    //Si todo esta bien, valida los datos con la base de datos e ingresa al home
    if(!empty($_POST) && $validate == true) {

        $user = new User();
        $state = $user->login($dataUser);

        if($state == true) {
            
            $session = new UserSession();
            $data = $user->getName($dataUser["email"]);
            $session->setCurrentName($data);
            header("Location:home.php");
            exit();
        } else{
            $error [] = "Datos incorrectos";
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
        <div class="form-login border-line-red">
            <form action="" name="login" class="form-input border-line-green" method="post">
                <input class="input-text form-control" type="text" placeholder="Correo" name="email" value="<?php if(!empty($_POST)){ echo $dataUser["email"];} ?>">
                <input class="input-text form-control" type="password" placeholder="Contraseña" name="password" value="<?php if(!empty($_POST)){ echo $dataUser["password"];} ?>">
                <?php
                    //Se muestran los errores de los campos del formulario
                    if(!empty($error)) { 
                        echo "<span>";
                        for($i = 0; $i < count($error); $i++) {
                            echo $error[$i]."<br>";
                        }
                        echo"</span>";
                    }
                ?>
                <button type="submit" class="btn btn-primary button-send">Ingresar</button>
            </form>
            <div class="function-login border-line-red">
                <a class="btn btn-link border-line-green font-game-shadow" href="password.php">Olvidé la contraseña</a>
                <p class="mb-3 border-line-blue font-game-shadow">No estás registrado?<a href="register.php" class="border-line-orange font-game-shadow"> Registrarse</a></p>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>