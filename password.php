<?php

    require_once 'Objects/user.php';
    require_once 'Objects/user_session.php';
    require_once 'Objects/form.php';

    $dataUser["email"] = "";
    $ps = "JHsd2z39sa00d1";
    $validate = true;
    $error = [];
    $msg = "";

    //Validamos que tenga caracteres validos enviados en el formulario
    if(!empty($_POST) && $validate == true) {

        $errorForm = new Form();
        $urlRecovery = $errorForm->getUrlRecovery();
        $validateEmail = $errorForm->verifyString("correo", $_POST['email']);

        if($validateEmail == true) {
            $validate = true;
        } else {
            $validate = false;
            $msg = "Hemos enviado el correo de recuperación, en caso de no recibirlo verifique el correo ingresado o vuelva a intentarlo";
        }
    }

    //Validamos los errores de los campos del formulario
    if(!empty($_POST) && $validate == true) { 

        if(empty($_POST['email'])){
            $error [] = "Ingrese el correo que registró para su cuenta";
        }
        if(empty($error)){
            $dataUser["email"] = $_POST["email"];
            $validate = true;
        } else {
            $validate = false;
        }
    }

    //Si todo esta bien, valida el correo ingresado
    if(!empty($_POST) && $validate == true) {

        $user = new User();
        $email = $user->emailVerify($dataUser["email"]);

        if($email == false) {
            $msg = "Hemos enviado el correo de recuperación, en caso de no recibirlo verifique el correo ingresado o vuelva a intentarlo";
        } else if($email == true) {

            $urlKey = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid().$ps);
            
            //Se genera token para cambiar la contraseña y se guarda en la base de datos para el correo que lo solicito
            if($user->setToken($urlKey, $dataUser["email"]) == true) {

                $to_email = $dataUser["email"];
                $subject = "Autentificación MiTurno";
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
                        <h1>Restablecimiento de contraseña</h1>
                        <div id='messager'>
                            <p>Se ha solicitado recuperar la contraseña del portal MiTurno!<br>
                                <br>Con el siguiente link podrás establecer una nueva:<br>". $urlRecovery . $urlKey ."<br>
                                <br>Si no solicitaste el cambio de contraseña, desestime el correo.<br>Disculpe el inconveniente.<br>
                                <br><br>Correo de prueba con PHP para app en hosting gratuito. Mensaje enviado automaticamente, por favor no responder.</p>  
                        </div>
                        <div id='banner'>App de prueba MiTurno</div>
                    </body>
                </html>";
                                
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF8' . "\r\n";
                $headers .= 'From: MiTurno <no-reply@example.com>' . "\r\n";
            
                //Se envía correo con link para poder cambiar la contraseña
                if (mail($to_email, $subject, $body, $headers)) {
                    $msg = "Se ha enviado el correo de recuperación, en caso de no recibirlo verifique el correo ingresado o vuelva a intentarlo";
                } else {
                    $error [] = "Error, favor de probar nuevamente en unos instantes";
                }
            } else {
                $error [] = "Error, favor de probar nuevamente en unos instantes";
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
            <form action="" name="recoveryPassword" class="form-input-password border-line-green" method="post">
                <input class="input-text form-control" type="text" placeholder="Correo" name="email" value="<?php if(!empty($_POST)){ echo $dataUser["email"];} ?>">
                <?php
                    //Se muestran los errores de los campos del formulario
                    if(!empty($error)) { 
                        echo "<span>";
                        for($i = 0; $i < count($error); $i++) {
                            echo $error[$i]."<br>";
                        }
                        echo"</span>";
                    }
                    //Se muestra mensaje de validación de solicitud de cambio de contraseña
                    if(!empty($msg)) { 
                        echo '<span style="color: rgb(4, 165, 4);">'. $msg .'</span>';
                    }
                ?>
                <button type="submit" class="btn btn-primary button-send">Recuperar contraseña</button>
            </form>
            <div class="function-login border-line-red">
                <a class="btn btn-link border-line-green font-game-shadow" href="register.php">No estás registrado? Registrarse</a>
                <p class="mb-3 border-line-blue font-game-shadow">Tenés cuenta?<a href="index.php" class="border-line-orange font-game-shadow"> Ingresar</a></p>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>