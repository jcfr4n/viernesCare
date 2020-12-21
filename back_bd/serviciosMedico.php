<?php
include "config.php";
include "utils.php";
$dbConn =  connect($db);

/**
 * Listar paciente.
 * Lo usan el médico y el rastreador
 * Recibe los parámetros:
 * clave20
 * dni
 * 
 */

if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['buscarPaciente'])) {


    if(isset($_GET['clave20']) && isset($_GET['dni'])){
        $sql = $dbConn->prepare("SELECT p.*, e.estado FROM paciente p
                                INNER JOIN estado e
                                ON p.idEstado = e.idEstado
                                WHERE dni=:dni");
        $sql->bindValue('dni',$_GET['dni']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll() );
        exit();
    }else {

        $sql = $dbConn->prepare("SELECT p.*, e.estado FROM paciente p
                                INNER JOIN estado e
                                ON p.idEstado = e.idEstado");
        $sql->bindValue('dni',$_GET['dni']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll() );
        exit();
    }

}

/**
 * Busca notas de paciente por dni.
 * Lo usan el médico y el paciente
 * Recibe los parámetros:
 * clave20
 * dni
 * 
 */

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(isset($_GET['clave20']) && isset($_GET['dni'])){

        $sql = $dbConn->prepare("SELECT n.* FROM notas n INNER JOIN paciente p ON n.idPaciente = p.idPaciente WHERE p.dni=:dni");
        $sql->bindValue('dni',$_GET['dni']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll() );
        exit();
        
    }
    

}

/**
 * Aquí hacemos la inclusión de una nota. Necesita recibir los parámetros:
 * clave20
 * idPaciente
 * fechaNota
 * horaNota
 * nota
 */

if($_SERVER['REQUEST_METHOD'] == 'POST'){



    if(isset($_POST['clave20']) && isset($_POST['idPaciente']) && isset($_POST['fechaNota']) && isset($_POST['horaNota']) && isset($_POST['nota'])){


         $sql = $dbConn->prepare("INSERT INTO notas (idNota, idPaciente, fechaNota, horaNota, nota)
         VALUES (null, :idPaciente, :fechaNota, :horaNota ,:nota)");

        $sql->bindValue(':idPaciente',$_POST['idPaciente']);
        $sql->bindValue(':fechaNota',$_POST['fechaNota']);
        $sql->bindValue(':horaNota',$_POST['horaNota']);
        $sql->bindValue(':nota',$_POST['nota']);

         $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        // echo json_encode( $sql->fetchAll() );
        exit();
        }

}