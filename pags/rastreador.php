<?php
        session_start();
        if(!isset ($_SESSION["rastreador"])){
            header("Location: http://localhost/viernescare/index.php");
        }
        include("funcionesUsu.php");
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
                    echo "<h2 id='saludo'>Hi, Rastreador</h2>";
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
                <button id="uno" name="añade_pac" type="submit">Añadir</button><br><br><br>
            </form>
        </div>
        <div>
            <h2>Ver Pacientes:</h2>
            <table>
            <tr>
                <th>DNI</th>
                <th>E-mail</th>
                <th>Telefono</th>
                <th>Estado</th>
            </tr>
        <?php
        mostrarPac();
        ?>

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

        //new paciente
        if(isset($_POST['dni_pac'])&& isset($_POST['email_pac']) && isset($_POST['tel_pac'])){
            $dni = $_POST['dni_pac'];
            $email = $_POST['email_pac'];
            $telefono = $_POST['tel_pac'];
            $clave20 = clave20();
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost/viernescare/cod/accionesPacientes.php?',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "clave20=1&dni={$dni}&email={$email}&telefono={$telefono}&idEstado=1",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            header('Location: http://localhost/viernesCare/pags/rastreador.php');
            

        }

?>