<?php

include '../../db/cn.php';
session_start();

$estado = $_POST['status'];
$empresa = $_SESSION['key']['empresa'];

if ($estado == 'update_prices_uni') {
    $id_coti = $_SESSION['coti'];
    $id = $_POST['idrepuesto'];
    $cantidad=$_POST['nuevaCantidad'];
    $valor_u=$_POST['nuevoPrecio'];
    $descuento=$_POST['nuevoDescuento'];
    $iva=$_POST['nuevoIva'];
    $query = "UPDATE detall_coti SET valor_unitario='$valor_u',iva='$iva',descuento='$descuento',cantidad='$cantidad' WHERE id_coti = '$id_coti' and codigo = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}

if ($estado == 'enc_del') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE encabeza_coti SET estado=3 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}
if ($estado == 'enc_fac') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE encabeza_coti SET estado=2 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}
if ($estado == 'enc_reop') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE encabeza_coti SET estado=1 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}

if ($estado == 'autorizate_item') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE detall_coti SET estado=1 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}
if ($estado == 'desautori_item') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE detall_coti SET estado=0 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}
if ($estado == 'delete_item') {
    $id = $_POST['id'];
    $id_coti = $_SESSION['coti'];
    $query = "UPDATE detall_coti SET estado=5 WHERE id = '$id'";
    $result_task = mysqli_query($conn, $query);
    echo $id_coti;
}
if ($estado == 'search_id') {
    $id = $_POST['id'];
    $query = "SELECT * FROM terceros WHERE nit=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result_task = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result_task);
    echo json_encode($data);

}
if($estado == 'insert'){
    $id = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $stock = $_POST['stock'];
    $descuento = $_POST['descuento'];
    $precio = $_POST['precio'];
    $iva = $_POST['iva'];
    $nit_tercero = $_POST['nit_tercero'];
    $usuario = $_SESSION['key']['usuario'];
    $id_coti = $_SESSION['coti'];
    if ($id_coti == 0) {
        $query = "INSERT INTO encabeza_coti (usuario,estado,tercero,empresa) VALUES ('$usuario','1','$nit_tercero','$empresa')";
        $result_task = mysqli_query($conn, $query);
        if ($result_task) {
            $id_coti_insert = mysqli_insert_id($conn);
            $id_coti = $id_coti_insert;
        } else {
            echo "Error al insertar en encabeza_coti: " . mysqli_error($conn);
        }
    }else{
        $id_coti_insert=$id_coti;
    }
    $query = "INSERT INTO detall_coti (id_coti,usuario,codigo,valor_unitario,iva,descuento,cantidad) VALUES ('$id_coti_insert','$usuario','$id','$precio','$iva','$descuento','$cantidad')";
    $result_task = mysqli_query($conn, $query);
    if ($result_task) {
        echo $id_coti;
    } else {
        echo "Error al insertar en detalle_coti: " . mysqli_error($conn);
    }
}





?>