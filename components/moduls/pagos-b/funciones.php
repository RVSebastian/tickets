<?php
include '../../db/cn.php';

  if (!isset($_SESSION)) {
    session_start();
    }

   if (isset($_POST['Rechazar'])) {
    $id = $_POST['id'];
    $motivo = $_POST['motivo_rechazo'];
    $autorizador = $_POST['user'];
    $query = "UPDATE pagos SET estado='Rechazado',autorizador='$autorizador',cancelado='$motivo', fecha_autorizado= NOW() WHERE id='$id'";
    mysqli_query($conn, $query);
    unset($result_task);
   }
   if (isset($_POST['Autorizar'])) {
    $id = $_POST['id'];
    $autorizador = $_POST['user'];
    $cuenta_banco = $_POST['cuenta'];
    $tercero_banco = $_POST['nombret'];
    $valor_banco = $_POST['valor'];
    $fecha_banco = $_POST['fecha_pago'];
    $nit_banco = $_POST['documentot'];
    $tipo_banco = $_POST['tipo_tercero'];
    $codigo_baucher = $_POST['codigo_baucher'];
    $notas_2 = $_POST['notas_2'];
    $query = "UPDATE pagos SET estado='Autorizado',autorizador='$autorizador',codigo_baucher='$codigo_baucher',notas_2='$notas_2',
    cuenta_banco='$cuenta_banco',tercero_banco='$tercero_banco',valor_banco='$valor_banco',fecha_banco='$fecha_banco',nit_banco='$nit_banco',tipo_banco='$tipo_banco',fecha_autorizado= NOW() WHERE id='$id'";
    mysqli_query($conn, $query);
    unset($result_task);
   }
   if (isset($_POST['guardarc'])) {
    $id = $_POST['id'];
    $recibo = $_POST['recibo'];
    $recibo_creador = $_POST['user'];
    $query = "UPDATE pagos SET recibo='$recibo',recibo_creador='$recibo_creador', recibo_fecha= NOW() WHERE id='$id'";
    mysqli_query($conn, $query);
   }
   if (isset($_POST['guardar'])) {
    $url_insert = "../../galery/Pagos";
    $usuario = $_POST['usuario'];
    $filename   = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
    $extension  = pathinfo( $_FILES["imagen1"]["name"], PATHINFO_EXTENSION ); // jpg
    $basename   = $filename . "." . $extension; // 5dab1961e93a7_1571494241.jpg
    $source       = $_FILES["imagen1"]["tmp_name"];
    $destination  = "../../galery/Pagos/{$basename}";
    move_uploaded_file( $source, $destination );
    $cuenta = $_POST['cuenta'];
    $valor = $_POST['valor'];
    $documentot = $_POST['documentot'];
    $nombret = $_POST['nombret'];
    $otro = $_POST['otro'];
    $fecha_pago = $_POST['fecha_pago'];
    $codigo_baucher = $_POST['codigo_baucher'];
    $tipo_tercero = $_POST['tipo_tercero'];
   
    $query = "INSERT INTO pagos(tipo_tercero,cuenta, valor, nombret, documentot, otro, usuario, img, estado,fecha,fecha_pago) 
    VALUES ('$tipo_tercero','$cuenta', '$valor', '$nombret','$documentot', '$otro', '$usuario','$basename','Pendiente', NOW() , '$fecha_pago')";
    mysqli_query($conn, $query);
    var_dump($_FILES);
   }

?>