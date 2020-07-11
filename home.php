<?php 

    require_once 'Objects/user_session.php';

    //Si no está autentificado y presiona el botón de ingresar, redireccionamos al formulario de inicio de sesión
    if(isset($_POST["Ingresar"])) {
        header("Location:index.php");
        exit();
    }

    //Se inicia sesión
    $session = new UserSession();

    //Si está autentificado y presiona el botón de desconectarse, se cierra sesión
    if(isset($_POST["Desconectarse"])) {
        session_unset();
        session_destroy();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>MiTurno - Home</title>
</head>

    <body id="home">
        <?php 

            //Si la sesión está abierta
            if(isset($_SESSION["name"])) {
                echo "<h1 id='text-home' class='font-game-shadow'>Bienvenido/a ". $session->getCurrentName() ."!</h1>";
                $nameButton = "Desconectarse";
            } else {
                echo "<h1 id='text-home' class='font-game-shadow'>Bienvenido/a visitante!</h1>";
                $nameButton = "Ingresar";
            }

        ?>

        <form id="button-home" action="" method="post">
            <button type="submit" class="btn btn-primary button-log" name="<?php echo $nameButton; ?>"><?php echo $nameButton; ?></button>
        </form>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>