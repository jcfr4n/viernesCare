  <?php
        session_start();
        $_SESSION["AdminU"]="Carlos"; // Esto habra que quitarlo, solo para pruebas
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css"/>
    <title>Document</title>
</head>
<body>
    <div id="menu">
        <h1>Viernes Care</h1>
        <div id="deslog">
            <form action='../index.php' method='GET' id='usermenu'>
                <img src="../images/icon-user.png" id="iconUser">
                <?php
                    $nameU =$_SESSION["AdminU"];
                    echo "<h2 id='saludo'>Hi, $nameU</h2>";
                    echo "<button type='submit' name='deslogin' id='botonDeslog'>Salir</button>";
                ?>
            </form>
        </div>
    </div>

    <div id="newUser">
        <div id="labels">
        <p>Nombre</p>
        <p>1º Apellido</p>
        <p>2º Apellido</p>
        <p>E-mail</p>
        <p>Contraseña</p>
        </div>
        <form action="admin.php" method="POST" id="formNewUser">
            <input type="text" placeholder="Nombre" name="nombreUsu">
            <input type="text" placeholder="1º Apellido" name="primerA">
            <input type="text" placeholder="2º Apellido" name="segundoA">
            <input type="email" placeholder="E-mail" name="mailUsu">
            <input type="password" placeholder="Contraseña" name="passUsu">
            <?php
                // sacar los roles de la BD
            ?>

            <button type="submit">Añadir</button>
        </form>
    </div>
</body>
</html>

<?php



        // Desloguearse
        if(isset($_GET['deslogin'])){
            session_destroy();
        }
?>