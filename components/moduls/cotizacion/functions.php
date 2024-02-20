<?php

include '../../db/cn.php';
session_start();

$estado = $_POST['status'];
$empresa = $_SESSION['key']['empresa'];
$usuario = $_SESSION['key']['usuario'];

if ($estado == 'update_prices_uni') {
    $id_coti = $_SESSION['coti'];
    $id = $_POST['idrepuesto'];
    $cantidad = $_POST['nuevaCantidad'];
    $valor_u = $_POST['nuevoPrecio'];
    $descuento = $_POST['nuevoDescuento'];
    $iva = $_POST['nuevoIva'];
    $inventoryQuery = "SELECT existencia FROM inventario WHERE parte = ? AND empresa = ?";
    $stmtInventory = $conn->prepare($inventoryQuery);
    $stmtInventory->bind_param('ss', $id, $empresa);
    $stmtInventory->execute();
    $resultInventory = $stmtInventory->get_result();
    $rowInventory = $resultInventory->fetch_assoc();
    if ($rowInventory && $rowInventory['existencia'] < $cantidad) {
        echo "Error: No hay inventario en stock";
    } else {
        $updateQuery = "UPDATE detall_coti SET valor_unitario=?, iva=?, descuento=?, cantidad=? WHERE id_coti = ? AND codigo = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param('ssssss', $valor_u, $iva, $descuento, $cantidad, $id_coti, $id);

        if ($stmtUpdate->execute()) {
            echo $id_coti;
        } 
    }
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

    // Variable para indicar si se encontraron errores
    $errores = false;

    // Busca los autorizados
    $q = "SELECT codigo,cantidad,id_coti FROM detall_coti WHERE estado = 1 AND id_coti = '$id'";
    $r = mysqli_query($conn, $q);

    // Variable para almacenar las líneas que se insertarán en el historial
    $lineasHistorial = array();

    foreach ($r as $linea) {
        $inventoryQuery = "SELECT existencia FROM inventario WHERE parte = ? AND empresa = ?";
        $stmtInventory = $conn->prepare($inventoryQuery);
        $stmtInventory->bind_param('ss', $linea['codigo'], $empresa);
        $stmtInventory->execute();
        $resultInventory = $stmtInventory->get_result();
        $rowInventory = $resultInventory->fetch_assoc();
        if ($rowInventory && $rowInventory['existencia'] < $linea['cantidad']) {
            echo "Error: No hay inventario suficiente para el código " . $linea['codigo'];
            // Indicar que hubo un error
            $errores = true;
            break; // Salir del bucle al encontrar un error
        } else {
            // Almacenamos la línea para insertar en el historial
            $fin = $linea['cantidad'] - $rowInventory['existencia'];
            $lineasHistorial[] = array(
                'codigo' => $linea['codigo'],
                'cantidad' => $linea['cantidad'],
                'final' => $fin,
                'id_coti' => $linea['id_coti']
            );
        }
    }

    // Si no hubo errores, realizar las inserciones en el historial
    if (!$errores) {
        foreach ($lineasHistorial as $lineaHistorial) {
            // Insertamos en el historial
            $q = "INSERT INTO historial_inv (codigo, inicial, final, usuario, empresa, doc) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtUpdate = $conn->prepare($q);
            $stmtUpdate->bind_param('ssssss', $lineaHistorial['codigo'], $lineaHistorial['cantidad'], $lineaHistorial['final'], $usuario, $empresa, $lineaHistorial['id_coti']);
            if ($stmtUpdate->execute()) {
                // Eliminamos en el inventario (código correspondiente)
                $codigo = $lineaHistorial['codigo'];
                $stock = $lineaHistorial['final'];
                $query = "UPDATE inventario SET existencia = '$stock' WHERE parte = '$codigo' AND empresa = '$empresa' ";
                $result_task = mysqli_query($conn, $query);
                // Actualizamos encabezado
                $query = "UPDATE encabeza_coti SET estado = 2 WHERE id = '$id'";
                $result_task = mysqli_query($conn, $query);
            } else {
                // Manejar el error en caso de que la ejecución de la consulta falle
                echo "Error al ejecutar la consulta de inserción en historial: " . $stmtUpdate->error;
            }
        }
        echo $id_coti;
    }
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
    $codigo = $_POST['codigo'];
    $cantidad = $_POST['cantidad'];
    $inventoryQuery = "SELECT existencia FROM inventario WHERE parte = ? AND empresa = ?";
    $stmtInventory = $conn->prepare($inventoryQuery);
    $stmtInventory->bind_param('ss', $codigo, $empresa);
    $stmtInventory->execute();
    $resultInventory = $stmtInventory->get_result();
    $rowInventory = $resultInventory->fetch_assoc();
    if ($rowInventory && $rowInventory['existencia'] < $cantidad) {
        echo "Error: No hay inventario en stock";
    }else{
        $query = "UPDATE detall_coti SET estado=1 WHERE id = '$id'";
        $result_task = mysqli_query($conn, $query);
        echo $id_coti;
    }
   
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
    $query = "SELECT * FROM terceros WHERE nit=? AND empresa = '$empresa'";
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
    $checkQuery = "SELECT COUNT(*) AS count FROM detall_coti WHERE id_coti = ? AND codigo = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param('ss', $id_coti_insert, $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $rowCheck = $resultCheck->fetch_assoc();

    if ($rowCheck['count'] > 0) {
        // Ya existe un registro con el mismo id_coti y codigo
        echo "Error: Ya existe ese articulo en la cotizacion.";
    } else {
        $query = "INSERT INTO detall_coti (id_coti,usuario,codigo,valor_unitario,iva,descuento,cantidad) VALUES ('$id_coti_insert','$usuario','$id','$precio','$iva','$descuento','$cantidad')";
        $result_task = mysqli_query($conn, $query);
        if ($result_task) {
            echo $id_coti;
        } else {
            echo "Error al insertar en detalle_coti: " . mysqli_error($conn);
        }
    }
}





?>