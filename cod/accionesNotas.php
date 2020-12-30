<?php
include "config.php";
include "utils.php";


$dbConn =  connect($db);

/**
 * listar todas las notas de los pacientes
 * Método: GET.
 * Parámetros recibidos:
 * clave20
 * idPaciente
 * Descripción: Si el método usado es GET, clave20 está
 * presente y se indica el idPaciente, se listarán todas 
 * las notas correspondientes a dicho paciente.
 */

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['clave20']) && (isset($_GET['idPaciente']))) {
        
            $sql = $dbConn->prepare("SELECT n.idNota, n.fechaNota, n.horaNota, n.idPaciente, n.nota FROM notas n INNER JOIN paciente p ON p.idPaciente=n.idPaciente where n.idPaciente=:idPaciente");
            $sql->bindValue(':idPaciente', $_GET['idPaciente']);
            $sql->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit();       
        
    }
}
/**
 * Crear nueva nota de paciente
 * Método: POST.
 * Parámetros recibidos:
 * clave20
 * fechaNota
 * horaNota
 * idNota
 * idPaciente
 * nota
 * Descripción: Si el método usado es POST y clave20 está
 * presente se crea una nueva nota con los datos recibidos
 * se esperan los datos desde un formulario enviados por POST.
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST;
    if (isset($input['clave20'])) {
        unset($input['clave20']);
        $sql = "INSERT INTO notas
          (idNota, fechaNota, horaNota, idPaciente, nota)
          VALUES
          (null,:fechaNota, :horaNota, :idPaciente, :nota)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);       
        $statement->execute();
        $postId = $dbConn->lastInsertId();
        if ($postId) {            
            header("HTTP/1.1 200 OK");
            echo json_encode('OK');
            exit();
        }
    }
}
/**
 * Borrar nota
 * Método: DELETE.
 * Parámetros recibidos:
 * clave20
 * idNota
 * Descripción: Si el método usado es DELETE y clave20 está
 * presente se borra la nota con el idNota recibido
 * se esperan los datos desde un formulario enviados por GET.
 * Se puede, haciendo una pequeña modificación, enviar los parámetros
 * en un archivo json, tal como hicimos para el método PUT.
 */
if (($_SERVER['REQUEST_METHOD'] == 'DELETE') && isset($_GET['clave20'])) {
    $id = $_GET['idNota'];
    $statement = $dbConn->prepare("DELETE FROM notas where idNota=:idNota");
    $statement->bindValue(':idNota', $id);
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
    //Esta instrucción recoge el flujo de datos enviado y lo decodifica,
    //almacenándolos en la variable $input.
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['clave20'])) {
        unset($input['clave20']);
        $postId = $input['idNota'];

        $fields = getParams($input);

        $sql = "
          UPDATE notas
          SET $fields
          WHERE idNota='$postId'
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