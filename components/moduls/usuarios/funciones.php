<?php
    include '../../db/cn.php';
    
if ($_POST['action'] == 'delete' ) {
    $id = $_POST['id'];
    $query = "DELETE FROM usuarios WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Eliminado';
    }
   }

if ($_POST['action'] == 'create') {
    $v1 = $_POST['1'];
    $v2 = $_POST['2'];
    $v3 = $_POST['3'];
    $v4 = $_POST['4'];
    $empresa = $_POST['empresa'];
    $query = "INSERT INTO usuarios (usuario,nombre,rol,contraseña,empresa) VALUES ('$v1','$v2','$v3','$v4','$empresa');";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Creado';
    }
}
if ($_POST['action'] == 'update') {
    $id = $_POST['id'];
    $v1 = $_POST['1'];
    $v2 = $_POST['2'];
    $v3 = $_POST['3'];
    $v4 = $_POST['4'];
    $query = "UPDATE usuarios SET usuario = '$v1', nombre = '$v2', rol = '$v3', contraseña = '$v4' WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);

    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
?>