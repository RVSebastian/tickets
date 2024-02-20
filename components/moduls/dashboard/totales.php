<?php
include '../../db/cn.php';
session_start();
$empresa = $_SESSION['key']['empresa'];
if ($empresa == 'ADMINISTRADOR') {
  // Consulta 2
$q2 = "SELECT 
SUM(costo_unitario*existencia) as valor, 
SUM(existencia) as stock
FROM `inventario`";

// Consulta 3
$q3 = "SELECT 
COUNT(dt.cantidad) AS cantidad_v,
ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
FROM 
`encabeza_coti` AS enc 
LEFT JOIN 
`detall_coti` AS dt ON dt.id_coti = enc.id
WHERE 
enc.estado = 1";
// Consulta 4
$q4 = "SELECT 
COUNT(dt.cantidad) AS cantidad_v,
ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
FROM 
`encabeza_coti` AS enc 
LEFT JOIN 
`detall_coti` AS dt ON dt.id_coti = enc.id
WHERE 
enc.estado = 1";

}else{
// Consulta 2
$q2 = "SELECT 
        SUM(costo_unitario*existencia) as valor, 
        SUM(existencia) as stock
    FROM `inventario` WHERE empresa = '$empresa'";

// Consulta 3
$q3 = "SELECT 
        COUNT(dt.cantidad) AS cantidad_v,
        SUM(dt.cantidad) AS cantidad_n,
        ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
    FROM 
        `encabeza_coti` AS enc 
    LEFT JOIN 
        `detall_coti` AS dt ON dt.id_coti = enc.id
    WHERE 
        enc.estado = 1
        AND enc.empresa = '$empresa'";
// Consulta 4
$q4 = "SELECT 
        COUNT(dt.cantidad) AS cantidad_v,
        SUM(dt.cantidad) AS cantidad_n,
        ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
    FROM 
        `encabeza_coti` AS enc 
    LEFT JOIN 
        `detall_coti` AS dt ON dt.id_coti = enc.id
    WHERE 
        enc.estado = 2
        AND enc.empresa = '$empresa'";

}



$stmt2 = $conn->prepare($q2);
$stmt2->execute();
$result2 = $stmt2->get_result();

$stmt3 = $conn->prepare($q3);
$stmt3->execute();
$result3 = $stmt3->get_result();

$stmt4 = $conn->prepare($q4);
$stmt4->execute();
$result4 = $stmt4->get_result();

$inventory = $result2->fetch_assoc();
$values = $result3->fetch_assoc();
$facturado = $result4->fetch_assoc();
?>

<script>
$("#valor_rep").text('<?php echo number_format($inventory['valor'], 0, ',', '.'); ?>');
$("#rep_stock").text('<?php echo $inventory['stock'] ?>');
$("#valor_cot").text('<?php echo number_format($values['valor_total_cotizacion'], 0, ',', '.'); ?>');
$("#cot_totales").text('<?php echo $values['cantidad_v'] ?>');
// facturados
$("#facturado").text('<?php echo number_format($facturado['valor_total_cotizacion'], 0, ',', '.'); ?>');
$("#rep-vendidos").text('<?php echo $facturado['cantidad_n'] ?>');
</script>