<?php

include '../../db/cn.php';

session_start();

function error($error_message){
    header('Content-Type: application/json');
    echo json_encode(['error' => $error_message]);
}
function print_value($result_array){
    header('Content-Type: application/json');
    echo json_encode($result_array);
}

$empresa = $_SESSION['key']['empresa'];


if($_POST['action'] == 'search'){
    $f1 = $_POST['nit'];
    $query = "SELECT * FROM terceros where nit = '$f1' and empresa = '$empresa'";
    $result_task = mysqli_query($conn, $query);
    if ($result_task) {
        $result_array = mysqli_fetch_assoc($result_task);
        print_value($result_array);
    } else {
        error(mysqli_error($conn));
    }
}

if ($_POST['action'] == 'search_rep') {
    $codigo = $_POST['codigo'];
    $query = "SELECT * FROM inventario where parte = '$codigo' ";
    $result_task = mysqli_query($conn, $query);
    if ($result_task) {
        $result_array = mysqli_fetch_assoc($result_task);
        print_value($result_array);
    } else {
        error(mysqli_error($conn));
    }
}

if ($_POST['action'] == 'insert') {
    $f1 = $_POST['nit'];
    $f2 = $_POST['nombres'];
    $f3 = $_POST['apellidos'];
    $f4 = $_POST['tipodocu'];
    $f5 = $_POST['telefono'];
    $f6 = $_POST['email'];
    $f7 = $_POST['direccion'];
    $f8 = $_POST['cumpleaños'];

    // Utiliza la cláusula ON DUPLICATE KEY UPDATE para manejar duplicados
    $query = "
        INSERT INTO terceros (nit, empresa, nombres, apellidos, tipodocu, telefono, email, direccion, cumpleaños)
        VALUES ('$f1','$empresa', '$f2', '$f3', '$f4', '$f5', '$f6', '$f7', '$f8')
        ON DUPLICATE KEY UPDATE
        nombres = '$f2',
        apellidos = '$f3',
        tipodocu = '$f4',
        telefono = '$f5',
        email = '$f6',
        direccion = '$f7',
        cumpleaños = '$f8'
    ";

    $result_task = mysqli_query($conn, $query);

    if ($result_task) {
        echo 'ok';
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}




mysqli_close($conn);


?>