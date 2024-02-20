<?php
require '../../../vendor/autoload.php'; // Asegúrate de cargar el archivo autoload de Composer
require '../../db/cn.php';
use Dompdf\Dompdf;
use Dompdf\Options;
session_start();

$id = $_GET['cotizacion_id'];

$query = "select dt.*,dt.id as id_detall,iv.*,dt.descuento as descuento_linea,dt.iva as iva_linea,ev.tercero as t_nit,tc.nombres as t_nombres,tc.apellidos as t_apellidos,tc.email as t_correos,ev.estado as enc_estado
from detall_coti as dt 
LEFT OUTER JOIN inventario as iv on iv.parte = dt.codigo 
LEFT OUTER JOIN encabeza_coti as ev on ev.id = dt.id_coti
LEFT OUTER JOIN terceros as tc on tc.nit = ev.tercero
where id_coti='$id'
";

$result_task = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result_task);
$row_count = mysqli_num_rows($result_task);

// Tu contenido HTML
$html = '
<!DOCTYPE html>
<html>

<head>
    <title>Cotización - '.$_SESSION['key']['empresa'].' </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
@page {
    margin: 1cm;
}

body {
    font-family: sans-serif;
    font-size: 14px;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Estilos para la tabla */
table {
    width: 100%;
    max-width: 800px;
    margin: auto;
    border-collapse: collapse;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    font-size: 12px;
    margin-top: 0;
    padding-top: 0;
}

th,
td {
    padding: 10px;
    padding-top: 5px;
    text-align: left;
    border: 1px solid #0A2558;
}

th {
    background-color: #0A2558;
    font-weight: bold;
    color: white;
}


li {
    line-height: 1.2;
}
.info{
    font-size: 15px !important;
    line-height: 0.2 !important;
    font-weight: bold;
}
.info2{
font-size: 12px;
line-height: 0.2 !important;
font-weight: 450;
text-align: right !important;
}
ol{
    padding-top: 0px;
    margin-top: 0px;
    font-size: 13px !important;
}
</style>

<body>
    <p class="info">AUTOMARCOL S.A.S</p>
    <p class="info">NIT: 900531238 - 9</p>
    <p class="info">TEL: 5876867 / 311 8300239</p>
    <p class="info">CORREO: 900531238@FACTUREINBOX.CO</p>
    <div style="position: fixed; top: 0; right: 0;">
     <p class="info2">FECHA: '.date('Y-m-d').'</p>
    </div>
<p>Estimado/a Cliente,</p>
    <p>Agradecemos su confianza en Automarcol para satisfacer sus necesidades de repuestos. Nos complace presentarle la cotización detallada de los artículos que nos ha solicitado:</p>';
$html .= '<p style="margin-bottom: 2px;">Términos y Condiciones:</p>';
$html = '
<table class="w-full text-sm text-left rtl:text-right text-gray-500 ">';
if ($row_count > 0) {
    $html = '<tbody>';
        $total_descuento = 0;
        $total_iva = 0;
        $total = 0;
        $total_t = 0;

        $total_descuento_autorizado = 0;
        $total_iva_autorizado = 0;
        $total_autorizado = 0;
        $total_t_autorizado = 0;
        $items_autorizados = 0;

        foreach ($result_task as $row) {
            $unidad = $row['precio'] * $row['cantidad'];
            $descuento = round($unidad * ($row['descuento_linea'] / 100));
            $bruto = round($unidad - $descuento);
            $iva = round($bruto * $row['iva_linea'] / 100);
            $total = $bruto + $iva;

            $total_descuento += $descuento;
            $total_iva += $iva;
            $total_t += $total;

            if ($row['estado'] == 1) {
                $items_autorizados += $row['cantidad'];
                $total_descuento_autorizado += $descuento;
                $total_iva_autorizado += $iva;
                $total_autorizado += $total;
                $total_t_autorizado += $total;
            }
            
            $html .= '<tr class="bg-white rounded border-b hover:bg-gray-100 <?php if ($row["estado"] == 5) {echo "hidden";} ?>"></tr>';
        }
        
        $html .= '
        <tr class="bg-gray-50 border rounded ">
            <td class="px-4 py-3">
                Total Autorizado:
            </td>
            <td class="px-4 py-3">
            </td>
            <!-- ... (resto de las celdas de la nueva tabla) ... -->
        </tr>
    </tbody>
</table>';
$html .= 
'<ol>
  
    <li>
    Los precios indicados son válidos por 1 día a partir de la fecha de esta cotización.
    <li>
    La cotización solo incluye el costo de los repuestos y no se incluye la mano de obra.
    <li>
    Los precios están sujetos a cambios sin previo aviso.
    <li>
    Los productos se enviarán a la dirección indicada por el cliente llegado caso que se requiera por parte del cliente.
    <li>
    El envío de los productos se realizará una vez que se haya confirmado el pago.
    <li>
    La garantía de los productos está sujeta a las políticas de garantía de los fabricantes.
    
</ol>';
$html .= '<p>Si necesita más información acerca de los repuestos, los términos y condiciones de esta cotización, o si tiene alguna pregunta adicional, no dude en contactarnos a través de nuestra línea de servicio automotriz al +57 311 8300239. Estamos a su disposición para ayudarle en lo que necesite. </p>'; 
$html .='
<p style="text-align: ">Zona Franca, Av. Libertadores #2-160, Cúcuta, Norte de Santander.</p>
';

// Configurar Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

// Cargar el HTML al Dompdf
$dompdf->loadHtml($html);

// Establecer el tamaño del papel (puedes ajustarlo según tus necesidades)
$dompdf->setPaper('A4', 'portrait');

// Renderizar el PDF
$dompdf->render();

// Enviar el PDF al cliente
$dompdf->stream('output.pdf', array('Attachment' => 0));

?>