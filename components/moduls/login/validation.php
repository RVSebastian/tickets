<?php

include '../../db/cn.php';

session_start();

$user = $_POST['user'];
$contraseña = $_POST['contraseña'];

$query = "SELECT * FROM USUARIOS WHERE usuario='$user' and contraseña='$contraseña' LIMIT 1";
$result_task = mysqli_query($conn, $query);

if (mysqli_num_rows($result_task) > 0) {
    echo "Usuario autenticado correctamente";
    $res = mysqli_fetch_assoc($result_task);
    $_SESSION['key']['usuario'] = $res['usuario'];
    $_SESSION['key']['rol'] = $res['rol'];
    $_SESSION['key']['nombre'] = $res['nombre'];
} else {
    echo "Usuario o contraseña incorrectos";
}

mysqli_free_result($result_task);
mysqli_close($conn);


?>