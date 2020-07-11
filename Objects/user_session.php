<?php

    class UserSession{

        public function __construct(){
            session_start();
        }

        //Guarda el nombre de usuario
        public function setCurrentName($name){
            $_SESSION['name'] = $name;
        }

        //Obtiene el nombre de usuario
        public function getCurrentName(){
            return $_SESSION['name'];
        }

        //Finaliza la sesión
        public function closeSession(){
            session_unset();
            session_destroy();
        }
    }

?>