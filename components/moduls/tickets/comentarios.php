<?php
    include '../../db/cn.php';
    
if ($_POST['action'] == 'delete' ) {
    $id = $_POST['id'];
    $query = "DELETE FROM solicitudes WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Eliminado';
    }
}

if ($_POST['action'] == 'create') {
    $solicitud = $_POST['1'];
    $usuario = $_POST['2'];
    $texto = $_POST['3'];
    $query = "INSERT INTO comentarios (solicitud,usuario,texto) VALUES ('$solicitud','$usuario','$texto');";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Comentario Creado';
    }
}
if ($_POST['action'] == 'update') {
    $id = $_POST['id'];
    $v1 = $_POST['1'];
    $query = "UPDATE solicitudes SET detalle = '$v1' WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);

    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
?>