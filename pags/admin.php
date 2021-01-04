  <?php
        session_start();
        $_SESSION["AdminU"]="Carlos"; // Esto habra que quitarlo, solo para pruebas
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css"/>
    <title>Admin</title>
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

    <h2 id="tittle">Introducir usuario</h2>
    <form action="admin.php" method="POST" id="formNewUser">
        <div id="labels">
            <label for="name">Dni</label>
            <input type="text" placeholder="Dni" id="dni" name="dni">
        </div>
        <div id="labels">
            <label for="name">Nombre</label>
            <input type="text" placeholder="Nombre" id="name" name="nombreUsu">
        </div>

        <div id="labels">
            <label for="primerA">1º Apellido</label>
            <input type="text" placeholder="1º Apellido" id="primerA" name="primerA">
        </div>

        <div id="labels">
            <label for="segundoA">2º Apellido</label>
            <input type="text" placeholder="2º Apellido" id="segundoA" name="segundoA">
        </div>

        <div id="labels">
            <label for="mail">E-mail</label>
            <input type="email" placeholder="E-mail" id="mail" name="mailUsu">
        </div>

        <div id="labels">
            <label for="passW">Contraseña</label>
            <input type="password" placeholder="Contraseña" id="passW" name="passUsu">
        </div>


            <?php
                // sacar los roles de la BD
            ?>
            <div id="Sub">
                <br>
            <button type="submit" id="Busernew">Añadir</button>
            </div>
        </form>


        <h2 id="tview">Ver Usuarios</h2>

        <!-- Ejemplo para php -->
        <table>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>1º Apellido</th>
                <th>2º Apellido</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
            <tr>
                <td>dni</td>
                <td>nombre</td>
                <td>1apesdgsgsdgsg</td>
                <td>2ape</td>
                <td>email</td>
                <td>rol</td>
                <td><img id="imgT" src='../images/edit.png'></td>
                <td><img id="imgT" src='../images/remove.png'></td>
            </tr>
            <tr>
                <td>dni</td>
                <td>nombre</td>
                <td>1apeasdfsgsgs</td>
                <td>2ape</td>
                <td>email</td>
                <td>rol</td>
                <td><img id="imgT" src='../images/edit.png'></td>
                <td><img id="imgT" src='../images/remove.png'></td>
            </tr>
        </table>
</body>
</html>

<?php



        // Desloguearse
        if(isset($_GET['deslogin'])){
            session_destroy();
        }
?>