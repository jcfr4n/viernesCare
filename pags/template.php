  <?php
        session_start();
        $_SESSION["AdminU"]="Carlos"; // Esto habra que quitarlo, solo para pruebas
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/template.css" />
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
</body>
</html>

<?php
        // Desloguearse
        if(isset($_GET['deslogin'])){
            session_destroy();
        }
?>