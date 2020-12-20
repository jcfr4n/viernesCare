<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Document</title>
  </head>
  <body>
    <div id="formularioLog">
      <form action="indexvariante.php" method="post">
        <h1>Sign up</h1>
        <fieldset>
          <legend>Dni</legend>
          <input type="text" placeholder="Nombre" name="nombre" />
        </fieldset>
        <br />
        <fieldset>
          <legend>Pass</legend>
          <input type="password" placeholder="Pass" name="pass" />
        </fieldset>
        <br /><br />
        <button type="submit">Login</button>
      </form>
    </div>
  </body>
</html>

<?php
if($_POST['indexvariante.php']){
  echo "Informado<br>";
  if(isset($_POST['nombre']) && isset($_POST['pass']) && $_POST['dni'] !="" && $_POST['pass'] !=""){
    echo "Informado2 y rellenado campos<br>";
    $nameUser = $_POST['user'];
    $passUser = $_POST['pass'];

    //abro conexion de mi bd local en PDO
    
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $bd_name="bd_usuario";
    
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$bd_name", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE,       PDO::ERRMODE_EXCEPTION);
      echo "Conexión exitosa<br>";
      //consulta
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt=$conn->prepare("SELECT id,nombre, clave FROM usuario");
      $smt->execute();

      //para ver el resultado como array asoc
      $result = $smt->setFetchMode(PDO::FETCH_ASSOC);
      print_r($result);
        


    } catch(PDOException $e) {
      echo "Error de conexión: " . $e->getMessage();
    }

    //cerrar conexion
    $conn =null;
    

    
    }

}

/*
  if(isset($_SESSION['rol'])){
      if($_SESSION['rol'] == "administrador"){
          header("Location: viernesAdmin.php");
      }else{
          header("Location: viernesEmpleado.php");
      }
  }else{
      header("Location: index.php?user=error");
  }
}
*/

?>
