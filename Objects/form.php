<?php

    class Form {

        private $url_recovery = "http://localhost/MiTurno/recovery.php?value=";

        //Verifica los errores dentro de los campos del formulario
        public function errorForm($name, $string) {
            if($name == "contraseña" && empty($string)) {
                $errorForm = "Ingrese una " . $name . ", para registrarse";
            }
            else if($name == "contraseña nueva" && empty($string)) {
                $errorForm = "Ingrese una " . $name ;
            }
            else if($name == "contraseña nueva" && strlen($string) < 8) {
                $errorForm = "La contraseña debe tener 8 caracteres mínimo";
            }
            else if($name == "contraseña" && strlen($string) < 8) {
                $errorForm = "La contraseña debe tener 8 caracteres mínimo";
            }
            else if(empty($string)) {
                $errorForm = "Ingrese un " . $name . ", para registrarse";
            }
            else if($name == "correo" && filter_var($string, FILTER_VALIDATE_EMAIL) == false) {
                $errorForm = "El correo ingresado no es valido";
            }
            if(!isset($errorForm)) {
                return NULL;
            } else {
                return $errorForm;
            }
        }

        //Solicita la url del servidor para unirla con el token y que pueda cambiar la contraseña
        public function getUrlRecovery() {
            return $this->url_recovery;
        }
    
        //Verifica que las cadenas que se enviaran al servidor poseean caracteres validos
        public function verifyString($name, $string) {

            $permitidos = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@-_. ';
            $count = 0;

            for($i = 0; $i < strlen($string); $i++) { 
                if(strpos($permitidos, substr($string,$i,1))===false) { 
                    return false;
                }else{
                    $count++;
                }
            }
            if(strlen($string) == $count) {
                return true;
            }
        }
    }

?>