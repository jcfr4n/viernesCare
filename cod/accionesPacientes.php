<?php
include "config.php";
include "utils.php";


$dbConn =  connect($db);

/**
 * listar todos los pacientes o solo uno
 * Método: GET.
 * Parámetros recibidos:
 * clave20
 * idPaciente (opcional)
 * Descripción: * Si el método usado es GET y clave20 está
 * presente se listan todos los pacientes, 
 * si se recibe además, el parámetro idPaciente se lista 
 * solo el paciente con ese parámetro
 */

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['clave20'])) {
    if ((isset ($_GET['mail'])) && (isset($_GET['pass']))){
      $sql = $dbConn->prepare("SELECT p.idPaciente FROM paciente p INNER JOIN estado e ON p.idEstado=e.idEstado where email=:mail AND codPaciente=:pass");
      $sql->bindValue(':mail', $_GET['mail']);
      $sql->bindValue(':pass', $_GET['pass']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
      exit();
      
    }else{

    
    if (isset($_GET['idPaciente'])) {
      //Mostrar un paciente
      $sql = $dbConn->prepare("SELECT p.dni, p.email, p.telefono, e.estado FROM paciente p INNER JOIN estado e ON p.idEstado=e.idEstado where idPaciente=:idPaciente");
      $sql->bindValue(':idPaciente', $_GET['idPaciente']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
      exit();
    } else {
      //Mostrar lista de pacientes
      $sql = $dbConn->prepare("SELECT p.dni, p.email, p.telefono, e.estado FROM paciente p INNER JOIN estado e ON p.idEstado=e.idEstado");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetchAll());
      exit();
    } 
    }
  }
}
/**
 * Crear nuevo paciente
 * Método: POST.
 * Parámetros recibidos:
 * clave20
 * dni
 * email
 * idEstado
 * telefono
 * Descripción: Si el método usado es POST y clave20 está
 * presente se crea un nuevo paciente con los datos recibidos
 * se esperan los datos desde un formulario enviados por POST. La API
 * genera un código de paciente y lo almacena en la base de datos
 * para el posterior acceso del paciente. el código deberá ser recibido
 * por email, sin intervención de ningún usuario.
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = $_POST;
  if (isset($input['clave20'])) {
    unset($input['clave20']);
    $cod = codigoUsuario();
    $sql = "INSERT INTO paciente
          (codPaciente, dni, email, idEstado, idPaciente, telefono)
          VALUES
          (:codPaciente,:dni, :email, :idEstado, null, :telefono)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->bindValue(':codPaciente', $cod);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if ($postId) {
      $input['idPaciente'] = $postId;
      $toAddress = $input['email'];
      $dni = $input['dni'];
      enviarCorreo($toAddress,$dni,$cod,$email);
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
    }
  }
}
/**
 * Borrar paciente
 * Método: DELETE.
 * Parámetros recibidos:
 * clave20
 * idPaciente
 * Descripción: Si el método usado es DELETE y clave20 está
 * presente se borra el usuario con el idPaciente recibido
 * se esperan los datos desde un formulario enviados por GET.
 * Se puede, haciendo una pequeña modificación, enviar los parámetros
 * en un archivo json, tal como hicimos para el método PUT.
 */
if (($_SERVER['REQUEST_METHOD'] == 'DELETE') && isset($_GET['clave20'])) {
  $id = $_GET['idPaciente'];
  $statement = $dbConn->prepare("DELETE FROM paciente where idPaciente=:idPaciente");
  $statement->bindValue(':idPaciente', $id);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}

/**
 * Hacer un Update de un paciente
 * Método: PUT.
 * Parámetros recibidos:
 * clave20 (requerido)
 * idPaciente (requerido)
 * dni (opcional)
 * email (opcional)
 * idEstado (opcional)
 * telefono (opcional)
 * Descripción: Si el método usado es PUT y clave20 está
 * presente se crea un nuevo usuario con los datos recibidos
 * se esperan los datos en un formato json. La API
 * recoge los datos enviados y los decodifica, verifica la 
 * existencia del parámetro clave20 y luego hace el update de 
 * los datos que se le enviaron en el idPaciente suministrado
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  $input = json_decode(file_get_contents('php://input'), true);

  if (isset($input['clave20'])) {
    unset($input['clave20']);
    $postId = $input['idPaciente'];

    $fields = getParams($input);

    $sql = "
          UPDATE paciente
          SET $fields
          WHERE idPaciente='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
  }
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>