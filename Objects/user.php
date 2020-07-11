<?php

    require_once 'db.php';

    class User extends BaseDeDatos{

        private $nombre;
        private $email;

        //Ingresa un nuevo usuario a la base de datos
        public function setUser($dataUser) {
            return $this->insertUser($dataUser);
        }

        //Verifica si el correo existe o no en la base de datos
        public function emailVerify($email) {
            return $this->emailCheck($email);
        }

        //Verifica si las credenciales de autentificación son validas para iniciar sesión
        public function login($dataUser) {
            return $this->loginData($dataUser);
        }

        //Guarda el token generado para el usuario que solicitó recuperar la contraseña
        public function setToken($token, $email) {
            return $this->TokenData($token, $email);
        }

        //Verifica que el token que recibió se encuentre en la base de datos y no este caducado
        public function verifyToken($token) {
            return $this->tokenVerify($token);
        }

        //Cambia la contraseña y hace que caduque el token
        public function setNewPassword($token, $new) {
            return $this->setPassword($token, $new);
        }

        //Obtiene una cadena con los datos del usuario
        public function getName($email) {
            return $this->userName($email);
        }
    }

?>