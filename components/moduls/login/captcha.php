<?php

session_start();
$captchaText = substr(md5(mt_rand()), 0, 6);

// Guarda el texto del captcha en la sesión para su verificación posterior
$_SESSION['captcha'] = $captchaText;

// Crea una imagen con el texto del captcha y la muestra
header('Content-type: image/png');
$font = 4;
$imageWidth = 270;
$imageHeight = 40;
$image = imagecreatetruecolor($imageWidth, $imageHeight);
$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);
imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $bgColor);
imagestring($image, $font, 20, 12, $captchaText, $textColor);
imagepng($image);
imagedestroy($image);


?>
