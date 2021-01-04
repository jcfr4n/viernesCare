<?php
        session_start();
        $_SESSION["AdminU"]="Carlos"; // Esto habra que quitarlo, solo para pruebas
?>

<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/rastreador.css" />
    <title>Rastreador</title>
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
    <div id="rastreador">
    <h2>Introducir paciente</h2>
        <div id="formPacientes">

            <form action="rastreador.php" name="f_rastreador" method="POST">
                <label for="name">Dni:</label>
                <input type="text" name="dni_pac">
                <label for="name">Email:</label>
                <input type="text" name="email_pac">
                <label for="name">Teléfono:</label>
                <input type="text" name="tel_pac">
                <?php
                // sacar los roles de la BD
                ?>
                <br><br>
                <label for="name">Notas:</label><br>
                <textarea name="nota" id="nota" cols="50" rows="10"></textarea><br><br>
                <button id="uno" name="añade_pac" type="submit">Añadir</button><br><br><br>
            </form>
        </div>
        <div>
            <h2>Ver Pacientes:</h2>
            <button id="dos" type="submit" name="consulta_pac">Consulta</button><br>
            <table id="consulta_paciente">
            </table>
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