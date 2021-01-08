  <?php
        session_start();
        if (!isset($_SESSION['paciente'])){
            header("Location: http://localhost/viernescare/index.php");

        }
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
                    echo "<h2 id='saludo'>Hi, Paciente</h2>";
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