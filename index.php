<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Document</title>
  </head>
  <body>
    <div id="formularioLog">
      <form action="login.php" method="post">
        <h1>Sign up</h1>
        <fieldset>
          <legend>Dni</legend>
          <input type="text" placeholder="Dni" name="dni" />
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
if(isset($_POST["user"]) && isset($_POST["pass"])){
    $user = $_POST["user"];
    $pass = $_POST["pass"];
?>
