<?php 
include '../../db/cn.php';
session_start();
$empresa = $_SESSION['key']['empresa'];
// Parámetros para la paginación
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$itemsPorPagina = 10;
$offset = ($pagina - 1) * $itemsPorPagina;

// Término de búsqueda
$terminoBusqueda = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '';
if ($empresa == 'ADMINISTRADOR') {
// Consulta SQL
$q = "SELECT 
        enc.id AS id_cot,
        enc.usuario,
        enc.estado AS estado_encabezado,
        enc.tercero,
        enc.fecha,
        tc.nombres,
        tc.apellidos,
        tc.tipodocu,
        tc.telefono,
        tc.email,
        enc.empresa,
        SUM(dt.cantidad) AS cantidad_v,
        ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
    FROM 
        `encabeza_coti` AS enc 
    LEFT JOIN 
        `terceros` AS tc ON tc.nit = enc.tercero
    LEFT JOIN 
        `detall_coti` AS dt ON dt.id_coti = enc.id
    WHERE 
        enc.estado = 2 
        AND dt.estado = 1
        AND (enc.id LIKE ? OR enc.usuario LIKE ?)
    GROUP BY 
        enc.id
    ORDER BY 
        enc.id DESC
    LIMIT ?, ?";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($q);
$stmt->bind_param('ssii', $terminoBusqueda, $terminoBusqueda, $offset, $itemsPorPagina);
}else{
// Consulta SQL
$q = "SELECT 
        enc.id AS id_cot,
        enc.usuario,
        enc.estado AS estado_encabezado,
        enc.tercero,
        enc.fecha,
        enc.empresa,
        tc.nombres,
        tc.apellidos,
        tc.tipodocu,
        tc.telefono,
        tc.email,
        SUM(dt.cantidad) AS cantidad_v,
        ROUND(SUM(((dt.valor_unitario * dt.cantidad) - ((dt.valor_unitario * dt.cantidad) * (dt.descuento / 100))) * (1 + (dt.iva / 100))), 2) AS valor_total_cotizacion
    FROM 
        `encabeza_coti` AS enc 
    LEFT JOIN 
        `terceros` AS tc ON tc.nit = enc.tercero
    LEFT JOIN 
        `detall_coti` AS dt ON dt.id_coti = enc.id
    WHERE 
        enc.empresa = ?
        AND enc.estado = 2 
        AND dt.estado = 1
        AND (enc.id LIKE ? OR enc.usuario LIKE ?)
    GROUP BY 
        enc.id
    ORDER BY 
        enc.id DESC
    LIMIT ?, ?";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($q);
$stmt->bind_param('ssiii', $empresa, $terminoBusqueda, $terminoBusqueda, $offset, $itemsPorPagina);
}
$stmt->execute();
$result = $stmt->get_result();
// Obtener resultados y devolverlos como JSON
$rows = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($rows);

?>