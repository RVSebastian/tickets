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
if ($_POST['action'] == 'cancelar' ) {
    $id = $_POST['id'];
    $query = "UPDATE solicitudes SET estado = CASE WHEN estado = 'Pendiente' THEN 'Cancelado' WHEN estado = 'Cancelado' THEN 'Pendiente' END WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
if ($_POST['action'] == 'aprobar' ) {
    $id = $_POST['id'];
    $query = "UPDATE solicitudes SET estado = 'Aprobado' WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
if ($_POST['action'] == 'asignar' ) {
    $id = $_POST['id'];
    $query = "UPDATE solicitudes SET estado = 'Asignar' WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
if ($_POST['action'] == 'finalizar' ) {
    $id = $_POST['id'];
    $query = "UPDATE solicitudes SET estado = 'Finalizado' WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
if ($_POST['action'] == 'rechazar' ) {
    $id = $_POST['id'];
    $query = "UPDATE solicitudes SET estado = 'Rechazado' WHERE id='$id'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
if ($_POST['action'] == 'create') {
    $v1 = $_POST['1'];
    $usuario = $_POST['2'];
    $query = "INSERT INTO solicitudes (detalle,usuario,estado,asignado) VALUES ('$v1','$usuario','Pendiente','');";
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
    $query = "UPDATE solicitudes SET detalle = '$v1' WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);

    if ($result_task === false) {
        die("Error en la consulta: " . mysqli_error($conn));
    } else {
        echo 'Registro Actualizado';
    }
}
?>