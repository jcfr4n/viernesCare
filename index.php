<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Document</title>
  </head>
  <body>
    <div id="formularioLog">
      <form action="index.php" method="post">
        <h1>Sign up</h1>
        <fieldset>
          <legend>E-mail</legend>
          <input type="text" placeholder="E-mail" name="mail" />
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
if(isset($_POST["mail"]) && isset($_POST["pass"])){
    $mail = $_POST["mail"];
    $pass = $_POST["pass"];

    include("pags/funcionesUsu.php");
   $usuOk= comprobarLogin($mail,$pass);
   header("Location: http://localhost/viernescare/".$usuOk);

}

?>
