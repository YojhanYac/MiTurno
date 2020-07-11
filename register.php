<?php

    require_once 'Objects/user.php';
    require_once 'Objects/user_session.php';
    require_once 'Objects/form.php';

    $dataUser["name"] = "";
    $dataUser["email"] = "";
    $dataUser["password"] = "";

    $error = [];
    $validate = true;

    //Validamos que tenga caracteres validos enviados en el formulario
    if(!empty($_POST) && $validate == true) {
    
        $errorForm = new Form();
        $validateName = $errorForm->verifyString("nombre", $_POST['name']);
        $validateEmail = $errorForm->verifyString("correo", $_POST['email']);
        $validatePassword = $errorForm->verifyString("contraseña", $_POST['password']);

        if($validateName == true && $validateEmail == true && $validatePassword == true){
            $validate = true;
        } else {
            $validate = false;
            $error [] = "Datos incorrectos";
        }
    }

    //Validamos los errores de los campos del formulario
    if(!empty($_POST) && $validate == true) { 
        if(!empty($errorForm->errorForm("nombre", $_POST['name']))){
            $error [] = $errorForm->errorForm("nombre", $_POST['name']);
        }
        if(!empty($errorForm->errorForm("correo", $_POST['email']))){
            $error [] = $errorForm->errorForm("correo", $_POST['email']);
        }
        if(!empty($errorForm->errorForm("contraseña", $_POST['password']))){
            $error [] = $errorForm->errorForm("contraseña", $_POST['password']);
        }

        if(empty($error)){
            $validate = true;
        } else {
            $validate = false;
        }

        $dataUser["name"] = $_POST["name"];
        $dataUser["email"] = $_POST["email"];
        $dataUser["password"] = $_POST["password"];

    }

    //Si todo esta bien, valida el correo y si no existe se crea el usuario
    if(!empty($_POST) && $validate == true) {

        $user = new User();
        $email = $user->emailVerify($dataUser["email"]);

        if($email == true) {
                $error [] = "Error, verifique los datos";
        } else {

            $dataUser["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);

            //Ingresa al nuevo usuario en la base de datos
            if($user->setUser($dataUser)) {
                $to_email = $dataUser['email'];
                $subject = "Bienvenido a MiTurno";
                $body =
                "<html>
                    <head>
                        <style>
                            body {
                                display: flex;
                                height: 500px;
                                flex-direction: column;
                                text-align: center;
                                margin: 5px 0;
                            }
                            h1 {
                                width: 90%;
                                background-color: orange;
                                color:white;
                                margin: 0 auto;
                                text-align:center;
                                padding: 1.5% 0;
                                border-top-left-radius: 7px;
                                border-top-right-radius: 7px;
                            }
                            #messager {
                                width: 90%;
                                margin: 0 auto;
                                padding-top: 2.5%;
                                padding-bottom: 2.5%;
                                text-align: center;
                            }
                            #banner {
                                width: 90%;
                                background-color: orange;
                                color: white;
                                margin: 0 auto;
                                padding: 2% 0;
                                text-align: center;
                                border-bottom-left-radius: 7px;
                                border-bottom-right-radius: 7px;
                            }
                        </style>
                    </head>
                    <body>
                        <h1>Bienvenido ". $dataUser["name"] ."!</h1>
                        <div id='messager'>
                            <p>Gracias por registrarte a MTurno! esperamos que se encuentre bien!<br>
                                <br><br>Correo de prueba con PHP para app en hosting gratuito. Mensaje enviado automaticamente, por favor no responder.
                            </p>  
                        </div>
                        <div id='banner'>App de prueba MiTurno</div>
                    </body>
                </html>";
                                
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF8' . "\r\n";
                $headers .= 'From: MiTurno <no-reply@example.com>' . "\r\n";

                //Envia un correo indicando que se registro correctamente y lo llevamos al home
                if(mail($to_email, $subject, $body, $headers)) {

                    $session = new UserSession();
                    $data = $user->getName($dataUser["email"]);
                    $session->setCurrentName($data);
                    header("Location:home.php");
                    exit();
                } else {
                    $error [] = "Error, por favor vuelva a intentarlo nuevamente";
                }
            } else {
                $error [] = "Error, por favor vuelva a intentarlo nuevamente";
            }
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
            <form action="" name="register" class="form-input-register border-line-green" method="post">
                <input class="input-text form-control" type="text" placeholder="Nombre" name="name" value="<?php if(!empty($_POST)){ echo $dataUser["name"];} ?>">
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
                <button type="submit" class="btn btn-primary button-send">Registrarme</button>
            </form>
            <div class="function-login border-line-red">
                <a class="btn btn-link border-line-green font-game-shadow" href="password.php">Olvidé mi contraseña</a>
                <p class="mb-3 border-line-blue font-game-shadow">Estás registrado?<a href="index.php" class="border-line-orange font-game-shadow"> Ingresar</a></p>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>