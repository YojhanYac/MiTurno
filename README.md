# MiTurno

Aplicación para autentificación de usuarios, realizada inicialmente para solicitar turnos para tramites.

Proyecto para poseer un inicio de sesión básico para implementar en próximos proyectos.

Funciones actuales:
* Ingresar, registrar y solicitar cambió de contraseña
* Envío de correo al registrarse y requerir una contraseña nueva, con token y tiempo de caducidad para el cambio
* Validación de campos de formulario de GET y POST para evitar caracteres no validos
* Validaciones de los campos de formulario si contiene errores o están vacíos

Funciones para implementar:
* Panel de altas, bajas y modificaciones para administrador

Estado del proyecto: en curso.

Herramientas utilizadas: PHP, SQL, Bootstrap, XAMPP, MySQL WorkBench

```
Para poder usar la aplicación deberá ingresar un correo real, ya que envía el correo de bienvenida al mismo y si solicita la url para el cambio de contraseña,
también se enviará un correo desde la casilla del servidor a la casilla que registró o utilizará en la aplicación.
```

* Visualizar en línea: https://yojhanyac.000webhostapp.com/MiTurno

Nota: El envio de correo con Bienvenida lo realiza correctamente. Sin embargo al solicitar cambio de contraseña, por la url que contiene el mensaje y el correo del servidor, pude tardar o hasta no recibirlo... Como recomendación si al primer intento no recibe el correo con la url, lamentablemente debe desistir, ya que si solicita uno nuevamente, puede que reciba el token de la primera solicitud que ya no existe o que haya caducado, son válidos por cinco minutos.
De manera local junto con gmail se envían correctamente. Probaré en otro hosting.


# Créditos: 

```
Algoritmo para verificar caracteres no validos: BastianBurst 
https://es.stackoverflow.com/questions/18232/c%C3%B3mo-evitar-la-inyecci%C3%B3n-sql-en-php

Algoritmo para generar token único: Xve
https://www.lawebdelprogramador.com/foros/PHP/1459058-Manera-sencilla-de-crear-un-token-unico.html
```

# Instalación de proyecto con XAMPP:

Para enviar correos usando una cuenta de Gmail
* Se debe modificar el archivo "php.ini", generalmente ubicado en la carpeta "C:\xampp\php"
* Buscar el campo [mail function] y copiar lo siguiente, modificando el valor de "sendmail_from" por el correo que enviará los mensajes(ejemplo: correoqueenviaralosmensajes@gmail.com)

Debería quedar así guardado:
```
[mail function]
SMTP=smtp.gmail.com
smtp_port=587
sendmail_from = correoqueenviaralosmensajes@gmail.com
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
mail.add_x_header=Off
```
El resto de las opciones de [mail function] se pueden comentar.

* También debemos modificar el archivo "sendmail.ini", generalmente ubicado en "C:\xampp\sendmail"
* Buscar el campo [sendmail] y copiar lo siguiente, modificando el valor "auth_username" por el correo que enviará los mensajes(ejemplo: correoqueenviaralosmensajes@gmail.com), modificando el valor "auth_password" por la contraseña del correo(ejemplo: Contraseña_correoqueenviaralosmensajes01)

```
 Para el primer envio de correo, llegará un correo de gmail a la casilla configurada (ejemplo: correoqueenviaralosmensajes@gmail.com)
 Indicando que se detectó una alerta de seguridad crítica, indicando que puede habilitar la opción de "acceso a aplicaciones menos seguras" si desea continuar, con los pasos a seguir para realizarlo.
```

Debería quedar así guardado:
```
[sendmail]
smtp_server=smtp.gmail.com
smtp_port=587
error_logfile=error.log
debug_logfile=debug.log
auth_username= correoqueenviaralosmensajes@gmail.com
auth_password= Contraseña_correoqueenviaralosmensajes01
force_sender= correoqueenviaralosmensajes@gmail.com
```

El resto de código se puede comentar mientras no utilicemos certificado ssl.

Genial, con eso podemos enviar correos con la función mail() de PHP.

* Las configuraciones adiciones son la conexión de la base de datos en "db.php", que está dentro de la carpeta "Objects" del proyecto.
```
El script para MySQL es:

CREATE DATABASE  IF NOT EXISTS `db_miturno`;
USE `db_miturno`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(25) NOT NULL,
  `email` varchar(45) NOT NULL UNIQUE KEY,
  `password` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` varchar(64) DEFAULT NULL,
  `expired_token` datetime DEFAULT NULL
)
```

* Para que funcione localmente, deberá descargar el proyecto y pegarlo dentro de la carpeta "htdocs", generalmente ubicada en "C:\xampp\htdocs" (quedaría: "C:\xampp\htdocs\MiTurno", donde se encontraría por ejemplo index.php). Luego activando los módulos de "Apache" y "MySQL" de XAMPP, podremos correr la página con la url: "http://localhost/MiTurno/" , también "http://localhost/MiTurno/index.php"

# Importante

_Para realizar pruebas localmente con la creación de usuarios, y no enviar los correos del servidor a correos reales, puede definir en register.php y password.php la variable
```$to_email = $dataUser["email"];```
con el correo del servidor(ejemplo: 
```$to_email = "correoqueenviaralosmensajes@gmail.com"; )```
para que al querer registrarse poder utilizar cualquier correo, y con este cambio le llegará el mensaje a la casilla del servidor que utiliza, simulando que se envió al registrado. También podrá recibir el correo con la url para cambiar la contraseña.
Sin este cambio al registrarnos se enviará el correo al registrado por lo que si no tenemos acceso al mismo, no podremos cambiar la contraseña, y no se recibirá el correo de Bienvenida._

Espero que hayan podido hacerlo correr y ver una estructura básica que tiene un ingreso, registro y solicitud de nueva contraseña.
