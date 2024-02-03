<?php


session_start();

// Verifica si el texto ingresado coincide con el captcha almacenado en la sesión
if (isset($_POST['captcha']) && $_POST['captcha'] === $_SESSION['captcha']) {
    echo "true";
} else {
    echo "false";
}

?>