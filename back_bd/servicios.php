<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

/**
 * Esta función genera un código de ocho caracteres como clave de usuario
 */
function codigoUsuario(){
   //Carácteres para la contraseña
   $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
   $password = "";
   //Reconstruimos la contraseña segun la longitud que se quiera
   for($i=0;$i<8;$i++) {
      //obtenemos un caracter aleatorio escogido de la cadena de caracteres
      $password .= substr($str,rand(0,62),1);
   }
   //Mostramos la contraseña generada
  return $password;
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    // ---Listar Rastreador
    if(isset($_GET['listarPacientes'])){
        $sql = $dbConn->prepare("SELECT idPaciente,dni,email,telefono,estado FROM paciente
                                INNER JOIN estado
                                ON paciente.idEstado = estado.idEstado");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll() );
        exit();
    }

}
/**
 * Aquí hacemos la inclusión del nuevo paciente. Necesita recibir los parámetros:
 * clave20
 * dni
 * email
 * telefono
 * idEstado
 * este servicio genera la contraseña llamando a la función codigoUsuario() y la base de datos rellena el campo autonumérico de idPaciente
 */

if($_SERVER['REQUEST_METHOD'] == 'POST'){


    // añadir paciente Rastreador
    if(isset($_POST['clave20']) && isset($_POST['dni']) && isset($_POST['email']) && isset($_POST['telefono']) && isset($_POST['idEstado'])){

        $codigo8 = codigoUsuario();

         $sql = $dbConn->prepare("INSERT INTO paciente (idPaciente, codPaciente, dni, email, telefono, idEstado)
         VALUES (null, :codigo, :dni, :email, :telefono ,1)");

        $sql->bindValue('codigo',$codigo8);
        $sql->bindValue('dni',$_POST['dni']);
        $sql->bindValue('email',$_POST['email']);
        $sql->bindValue('telefono',$_POST['telefono']);

         $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        // echo json_encode( $sql->fetchAll() );
        exit();
        }

}


if($_SERVER['REQUEST_METHOD'] == 'DELETE'){

// --- 5 Ejercicio
     if(isset($_GET['postEliminar'])){
        $usuario = $_GET['usuario'];
        $pass = $_GET['pass'];
        $postEliminar = $_GET['postEliminar'];

        $consulta = " SELECT COUNT(*) as num FROM usuarios WHERE usuario ='$usuario' AND pass='$pass'";

        $result = $dbConn->query($consulta);
        // $consulta->execute();
        $numfilas = $result->rowCount();

        if($numfilas> 0){
        echo "entro";
        echo "$postEliminar";
        $statement = $dbConn->prepare("DELETE FROM post WHERE idpost = '$postEliminar'");
        // $statement->bindValue(':postEliminar', $postEliminar);
        $statement->execute();
        }

        header("HTTP/1.1 200 OK");
        exit();
        
    }
}
