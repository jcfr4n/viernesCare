
<?php
// Insercion a bd de usuarios
        if(isset($_POST["nombreUsu"]) && isset($_POST["primerA"]) && isset($_POST["segundoA"])&& isset($_POST["mailUsu"])&& isset($_POST["passUsu"])&& isset($_POST["rol"])){
                newUser($_POST["nombreUsu"],$_POST["primerA"],$_POST["segundoA"],$_POST["mailUsu"],$_POST["passUsu"],$_POST["rol"]);
                unset($_POST);
        }
//Eliminar usuarios
        if(isset($_POST["deleteU"])){
            $idUsu = $_POST["deleteU"];
            borrarUsu($idUsu);
            // header("http://localhost/viernesCare/pags/admin.php");
        }
// actualizar usuarios
 if(isset($_POST["UdName"]) && isset($_POST["UdApelli1"])&& isset($_POST["UdApelli2"])&& isset($_POST["UdMail"])){
            $newName = $_POST["UdName"];
            $newAp1 = $_POST["UdApelli1"];
            $newAp2 = $_POST["UdApelli2"];
            $newMail = $_POST["UdMail"];
            $idU = $_POST["editU"];
            updateUsu($idU,$newName,$newAp1,$newAp2,$newMail);
        }

// Comprueba el login si son usuarios o pacientes
function comprobarLogin($mail, $pass)
{
    $numfilas = 0;
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $consultaUsu = "SELECT email, clave, id_rol FROM usuario WHERE email ='$mail' AND clave='$pass'";
        $result = $conn->query($consultaUsu);
        $numfilas = $result->num_rows;
        $fila = $result->fetch_assoc();
        if ($numfilas > 0) {
            $idUsuario = $fila['id_rol'];
            if ($idUsuario == 1) {
                $_SESSION["admin"] = 1;
                return "pags/admin.php";
            } elseif ($idUsuario == 2) {
                $_SESSION["rastreador"] = 2;
                return "pags/rastreador.php";
            } else {
                $_SESSION["medico"] = 3;
                return "pags/medico.php";
            }
        } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "localhost/viernescare/cod/accionesPacientes.php?clave20&mail={$mail}&pass={$pass}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $id = json_decode($response, true);
            $_SESSION['paciente'] = $id['idPaciente'];
            return "pags/paciente.php";
        }
    }
}


// Obtener roles de usuarios
function obtenerRoles()
{
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $consultaRoles = "SELECT id_rol,rol FROM rol_usuario";
        $result = $conn->query($consultaRoles);
        $numfilas = $result->num_rows;
        for ($x = 0; $x < $numfilas; $x++) {
            $fila = $result->fetch_assoc();
            echo "<option value='$fila[id_rol]'>$fila[rol]</option>";
        }
    }
}


// Añade nuevos usuarios a la BD
function newUser($nombre, $apellido1, $apellido2, $email, $clave, $id_rol)
{
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $insertUsu = "INSERT INTO usuario (`nombre`, `apellido_1`, `apellido_2`, `email`, `clave`, `id_rol`) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$clave', '$id_rol')";
        if ($conn->query($insertUsu) === FALSE) {
            echo "Error: " . $insertUsu . "<br>" . $conn->error;
        }

        $conn->close();
        header('Location: http://localhost/viernesCare/pags/admin.php');
        die();
    }
}

//Muestra los usuarios en una tabla y ademas permite eliminarlos y editarlos desde la misma tabla
function mostrarUsu()
{
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $mostrarUsus = "select id,nombre,apellido_1,apellido_2, email,rol from usuario INNER JOIN rol_usuario ON usuario.id_rol = rol_usuario.id_rol ORDER BY usuario.id";
        $result = $conn->query($mostrarUsus);
        $numfilas = $result->num_rows;
        for ($x = 0; $x < $numfilas; $x++) {
            $fila = $result->fetch_assoc();
            echo "<tr>";
            echo "<form action='admin.php' method='post'>";
            echo  "<td><input type='text' id='update' placeholder='$fila[nombre]' name='UdName' value='$fila[nombre]'>
                </td>";
            echo  "<td><input type='text' id='update' placeholder='$fila[apellido_1]' name='UdApelli1' value='$fila[apellido_1]'>
                </td>";
            echo  "<td><input type='text' id='update' placeholder='$fila[apellido_2]' name='UdApelli2' value='$fila[apellido_2]'>
                </td>";
            echo  "<td><input type='email' id='update' placeholder='$fila[email]' name='UdMail' value='$fila[email]'>
                </td>";
            echo  "<td>$fila[rol]</td>";
            echo  "<td><button type='submit' id='uButton' name='editU' value='$fila[id]'> <img id='imgT' src='../images/edit.png'> </button></td>";
            echo "</form>";
            echo "<form action='admin.php' method='post'>";
            echo  "<td><button type='submit' id='delButton' name='deleteU' value='$fila[id]'> <img id='imgT' src='../images/remove.png'> </button></td>";
            echo "</form>";
            echo "</tr>";
        }
    }
}

// borra usuarios
function borrarUsu($idUsu)
{
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $sql = "DELETE FROM usuario WHERE id = $idUsu";
        if ($conn->query($sql) === TRUE) {
        }
    }
    $conn->close();
}

// actualiza usuarios
function updateUsu($idU, $nameU, $apellidoU1, $apellidoU2, $mailU)
{
    $conn = new mysqli("localhost", "root", "", "usuarios_viernescare");
    if ($conn->connect_error) {
        echo "error";
    } else {
        $sql = "UPDATE `usuario` SET `nombre` = '$nameU', `apellido_1` = '$apellidoU1', `apellido_2` = '$apellidoU2', `email` = '$mailU' WHERE `usuario`.`id` = '$idU'";
        if ($conn->query($sql) === TRUE) {
        }
    }
    $conn->close();
}

// Generar 20 caracteres aleatorios
function clave20()
{
    //Carácteres para la contraseña
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $codigo = "";
    //Reconstruimos la contraseña segun la longitud que se quiera
    for ($i = 0; $i < 20; $i++) {
        //obtenemos un caracter aleatorio escogido de la cadena de caracteres
        $codigo .= substr($str, rand(0, 62), 1);
    }
    //Mostramos la contraseña generada
    return $codigo;
}

// mostrar los pacientes para el rastreador (Todo menos notas)
function mostrarPac()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/viernescare/cod/accionesPacientes.php?clave20',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $datosDesc = json_decode($response, TRUE);

    $n = count($datosDesc);

    for ($i = 0; $i < $n; $i++) {
        $dni = $datosDesc[$i]['dni'];
        $email = $datosDesc[$i]['email'];
        $telefono = $datosDesc[$i]['telefono'];
        $estadop = $datosDesc[$i]['estado'];

        echo "<tr>";
        echo "<td>$dni</td>";
        echo "<td>$email</td>";
        echo "<td>$telefono</td>";
        echo "<td>$estadop</td>";
        echo "</tr>";
    }
}

//Mostrar todo de un paciente, menos notas ni clave.
function mostrarPacId($idPaciente)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "localhost/viernescare/cod/accionesPacientes.php?clave20&idPaciente={$idPaciente}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $datosDesc = json_decode($response, TRUE);


        $dni = $datosDesc['dni'];
        $email = $datosDesc['email'];
        $telefono = $datosDesc['telefono'];
        $estadop = $datosDesc['estado'];

        echo "<tr>";
        echo "<td>$dni</td>";
        echo "<td>$email</td>";
        echo "<td>$telefono</td>";
        echo "<td>$estadop</td>";
        echo "</tr>";
    // }
}

//Muestra las notas del paciente
function mostrarNotasId($idPaciente){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "localhost/viernescare/cod/accionesNotas.php?clave20=&idPaciente={$idPaciente}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);

$notasPac = json_decode($response, TRUE);

    $n = count($notasPac);

     for ($i = 0; $i < $n; $i++) {
        $nota = $notasPac[$i]['nota'];
        $fecha = $notasPac[$i]['fechaNota'];
        $hora = $notasPac[$i]['horaNota'];

        echo "<div id='notasindi'>";
        echo "<h3 id='notatex'>$nota</h3>";
        echo "<div id='fechahora'>";
        echo "<h4 id='fecha'>$fecha</h4>";
        echo "<h4>$hora</h4>";
        echo "</div>";
        echo "</div>";
     }
}

function montrarPacienteDni($dni){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/viernescare/cod/accionesPacientes.php?clave20&dni=252525E',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$notasPac = json_decode($response, TRUE);
        $dni = $notasPac['dni'];
        $email = $notasPac['email'];
        $telefono = $notasPac['telefono'];
        $estadop = $notasPac['estado'];
            echo "<h2>Información del Paciente:</h2>";
            echo "<table>";
            echo"<tr>";
            echo"<th>DNI</th>";
            echo "<th>E-mail</th>";
            echo "<th>Telefono</th>";
            echo "<th>Estado</th>";
            echo"</tr>";
        echo "<tr>";
        echo "<td>$dni</td>";
        echo "<td>$email</td>";
        echo "<td>$telefono</td>";
        echo "<td>$estadop</td>";
        echo "</tr>";
        echo "</table>";
}
?>