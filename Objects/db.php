<?php

    class BaseDeDatos {

        private $server = "127.0.0.1";
        private $user = "root";
        private $password = "";
        private $database = "db_miTurno";
        private $port = "3306";
        public  $conexion;

        //Establece la conexión con la base de datos
        public function __construct() {
            $this->conexion = new mysqli($this->server, $this->user, $this->password,$this->database) or die(mysql_error());
            $this->conexion->set_charset("utf8");
        }

        //Ingresa un usuario a la base de datos
        public function insertUser($dataUser) {
            $resultado = $this->conexion->query("INSERT INTO users (`name`, `email`, `password` ) VALUES ('". $dataUser["name"] ."', '". $dataUser["email"] ."', '". $dataUser["password"] ."')") or die($this->conexion->error);
            if($this->conexion->affected_rows == 1) {
                return true;
            } else {
                return false;
            }
        }

        //Verifica si el correo se encuentra en la base de datos
        public function emailCheck($email) {
            $resultado = $this->conexion->query("SELECT * FROM users WHERE email = '$email' ") or die($this->conexion->error);
            if($resultado->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }

        //Solicita los datos de usuario a la base de datos
        public function userName($email) {
            $resultado = $this->conexion->query("SELECT * FROM users WHERE email = '$email' ") or die($this->conexion->error);
            $arrayData = $resultado->fetch_array(MYSQLI_ASSOC);
            foreach($arrayData as $buff => $aux) {
                if($buff == "name") {
                    return $aux;
                }
            }
        }

        //Valida el correo y la contraseña ingresada para iniciar sesión
        public function loginData($dataUser) {

            $resultado = $this->conexion->query("SELECT * FROM users WHERE email = '". $dataUser["email"] ."'") or die($this->conexion->error);

            if($resultado->num_rows > 0) {
                
                $row = $resultado->fetch_array(MYSQLI_ASSOC);
                foreach($row as $name => $value) {
                    if($name == "password" && (password_verify($dataUser["password"], $value)) == true) {
                        return true;
                    }
                }
                return false;
            } else {
                return false;
            }
        }

        //Guarda un token en la base de datos para el usuario que solicitó una nueva contraseña, el mismo caduca en cinco minutos
        public function tokenData($token, $email) {

            date_default_timezone_set("America/Argentina/Buenos_Aires");

            $date = new DateTime();
            $date->modify('+5 minute');
            $dateExpired = $date->format('Y-m-d H:i:s');
            $resultado = $this->conexion->query("UPDATE users SET token = '$token', expired_token = '$dateExpired' WHERE email = '$email'") or die($this->conexion->error);
            
            if($this->conexion->affected_rows == 1) {
                return true;
            } else {
                return false;
            }
        }

        //Verifica que el token enviado al servidor corresponde a uno valido, y no este caducado
        public function tokenVerify($token) {

            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $resultado = $this->conexion->query("SELECT * FROM users WHERE token = '$token'") or die($this->conexion->error);

            if($resultado->num_rows > 0) {

                $dataToken = $resultado->fetch_array(MYSQLI_ASSOC);
                $date = new DateTime();
                $currentDate = $date->format('Y-m-d H:i:s');
                $expired = $dataToken["expired_token"];

                if($currentDate <= $expired) {
                    return true;
                }
                if($currentDate > $expired) {
                    return false;
                }
            } else {
                return false;
            }
        }

        //Establece la nueva contraseña y caduca el token
        public function setPassword($token, $new) {

            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $resultado = $this->conexion->query("SELECT * FROM users WHERE token = '$token'") or die($this->conexion->error);

            if($resultado->num_rows > 0) {

                $date = new DateTime();
                $currentDate = $date->format('Y-m-d H:i:s');
                $resultado = $this->conexion->query("UPDATE users SET `password` = '$new', expired_token = '$currentDate' WHERE token = '$token'") or die($this->conexion->error);

                return true;
            } else {
                return false;
            }
        }
    }

?>