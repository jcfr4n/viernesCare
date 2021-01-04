<?php

use PHPMailer\PHPMailer\PHPMailer;

//Abrir conexion a la base de datos
function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8", $db['username'], $db['password']);

          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }


 //Obtener parametros para updates
 function getParams($input)
 {
    $filterParams = [];
    foreach($input as $param => $value)
    {
            $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
	}

  //Asociar todos los parametros a un sql
	function bindAllValues($statement, $params)
  {
		foreach($params as $param => $value)
    {

        $statement->bindValue(':'.$param, $value);

		}
		return $statement;
   }

   /**
 * Esta función genera un código de ocho caracteres como clave de usuario
 */
function codigoUsuario(){
  //Carácteres para la contraseña
  $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
  $codigo = "";
  //Reconstruimos la contraseña segun la longitud que se quiera
  for($i=0;$i<8;$i++) {
     //obtenemos un caracter aleatorio escogido de la cadena de caracteres
     $codigo .= substr($str,rand(0,62),1);
  }
  //Mostramos la contraseña generada
 return $codigo;
}
/**
 * Esta función hace uso de la clase PHPMailer la cual está contenida en los archivos de la carpeta PHPMailer y se usa para facilitar el envío de correo electrónico.
 */
function enviarCorreo($toAddress, $dni, $cod, $email){
  
  require 'phpmailer/PHPMailer.php';
  require 'phpmailer/SMTP.php';
  require 'phpmailer/OAuth.php';
  require 'phpmailer/Exception.php';

  $mail = new PHPMailer();
  $mail->isSMTP();

  $mail->SMTPDebug = 0;
  $mail->Host = $email['host'];
  $mail->Port = $email['port'];
  $mail->SMTPSecure = 'tls';
  $mail->SMTPAuth = true;
  $mail->Username = $email['user'];
  $mail->Password = $email['password'];
  $mail->setFrom($email['user'],'Viernes Care');
  $mail->addAddress($toAddress);
  $mail->Subject = 'Alta en el sistema Viernes Care';
  $mail->Body = "<h1>Viernes Care</h1>
  <h2>Bienvenido:</h2>
  <p>Hola, este correo electrónico es generado automáticamente por el sistema de seguimiento de pacientes de COVID, usted ha sido registrado con el dni: ".$dni.", y su clave de acceso al sistema es: <b>".$cod."</b></p>
  <p>Guarde el presente correo como referencia.</p>
  <p>No responda a esta dirección, ya que esta es una cuenta no monitorizada</p>";
  $mail->isHTML(true);
  // $mail->send();
  if (!$mail->send()){
    echo "Error al enviar el email: ".$mail->ErrorInfo;
  }else{
    echo "Email enviado correctamente";
  }
}
?>