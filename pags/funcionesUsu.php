
<?php

// Obtener roles de usuarios
function obtenerRoles(){
            $conn = new mysqli("localhost","root","","usuarios_viernescare");
            if($conn->connect_error) {
                echo "error";
            }else{
                $consultaRoles = "select id_rol,rol from rol_usuario";
                $result = $conn->query($consultaRoles);
                $numfilas = $result->num_rows;
                for ($x=0;$x<$numfilas;$x++) {
                    $fila = $result->fetch_assoc();
                    echo "<option value='$fila[id_rol]'>$fila[rol]</option>";
                }
            }
}



function newUser($nombre,$apellido1,$apellido2,$email,$clave,$id_rol){
    $conn = new mysqli("localhost","root","","usuarios_viernescare");
            if($conn->connect_error) {
                echo "error";
            }else{
                $insertUsu = "INSERT INTO usuario (`nombre`, `apellido_1`, `apellido_2`, `email`, `clave`, `id_rol`) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$clave', '$id_rol')";
                if ($conn->query($insertUsu) === FALSE) {
                    echo "Error: " . $insertUsu . "<br>" . $conn->error;
                }

    $conn->close();
            }
}

function mostrarUsu(){
    $conn = new mysqli("localhost","root","","usuarios_viernescare");
            if($conn->connect_error) {
                echo "error";
            }else{
                $mostrarUsus= "select id,nombre,apellido_1,apellido_2, email,rol from usuario INNER JOIN rol_usuario ON usuario.id_rol = rol_usuario.id_rol";
                $result = $conn->query($mostrarUsus);
                $numfilas = $result->num_rows;
                for ($x=0;$x<$numfilas;$x++) {
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

function borrarUsu($idUsu){
$conn = new mysqli("localhost","root","","usuarios_viernescare");
            if($conn->connect_error) {
            echo "error";
            }else{
            $sql="DELETE FROM usuario WHERE id = $idUsu";
                  if ($conn->query($sql) === TRUE) {
                  }
            }
            $conn->close();
}

function updateUsu($idU,$nameU,$apellidoU1,$apellidoU2,$mailU){
    $conn = new mysqli("localhost","root","","usuarios_viernescare");
            if($conn->connect_error) {
            echo "error";
            }else{
                $sql="UPDATE `usuario` SET `nombre` = '$nameU', `apellido_1` = '$apellidoU1', `apellido_2` = '$apellidoU2', `email` = '$mailU' WHERE `usuario`.`id` = '$idU'";
                if ($conn->query($sql) === TRUE) {
                  }
            }
            $conn->close();
}

?>
