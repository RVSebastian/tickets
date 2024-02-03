<?php
include '../../db/cn.php';
?>

<script>
function cerrar() {
    $('#listado_pagos , #back_ls').hide();
}
</script>
<div id="listado_pagos" tabindex="201000" style='z-index: 21000 !important;' aria-hidden="true"
    class="viewpay w-full p-4 pt-0 mt-0  absolute inset-0 transition-2">
    <div class="absolute w-full h-full max-w-6xl h-5xl rounded overflow-y-auto top-0 left-0 right-0 z-50 top-modal"
        style="margin:0px; left: 50%; transform: translate(-50%, -50%); position: fixed;">
        <div class="bg-white rounded-lg shadow rounded border-b-0">
            <div class="grid sm:grid-cols-1 md:grid-cols-1" style="border: 0 !important;">
                <div class="basis-11/12 md:basis-3/4 bg-white">
                    <div class="flex items-start justify-between p-4 pb-0 rounded-t ">
                        <button type="button" onclick="cerrar();"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            onclick="cerrar();">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-3 pt-0 mt-0 overflow-y-auto text-justify ">
                        <div class="relative overflow-x-auto">
                            <?php 
            if (isset($_POST['ver_pagos'])) {
                $nit = $_POST['nit'];
                $sql = "SELECT * FROM pagos where documentot='$nit' order by fecha desc";
                $result_task = mysqli_query($conn, $sql);
            ?>
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase border-b">
                                    <tr>
                                        <th scope="col" class="px-1 py-2">
                                            REF
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Cuenta
                                        </th>
                                        <th scope="col" class="px-1 py-2">
                                            Codigo
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Valor
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Tercero
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Fecha Subida
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Fecha Pago
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Autorizador
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Estado
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Rc
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="p-5">
                                    <?php 
                                  foreach($result_task as $row){ 
                                    if($row['estado'] != 'Pendiente'){
                        ?>
                                    <tr class="bg-white border-b hover:bg-gray-200"
                                        onclick="openModal(id='<?php echo $row['id'];?>')">
                                        <td class="px-1 py-1">
                                            <?php echo $row['id'] ?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo $row['cuenta'] ?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo $row['codigo_baucher'] ?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            $<?php echo $row['valor'] ?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo $row['nombret'] ?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo date("d/m/Y", strtotime($row['fecha_pago'] ));?>
                                        </td>
                                        <td class="px-1 py-1 ">
                                            <?php echo $row['autorizador'] ?>
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center"
                                                data-tooltip-target="tooltip-default-<?php  echo $row['id']?>">
                                                <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mb-2">
                                                    <i class='bx bx-file text-xl'></i>
                                                </div>
                                            </div>

                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full mb-2">
                                                    <i class='text-xl <?php if (!empty($row['recibo'])) {
                                                echo 'bx bx-file-blank';
                                            }?>'></i>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php
                                        if (mysqli_num_rows($result_task) > 0) {
                                            echo '<td colspan="10" class="p-4 bg-gray-100">No hay pagos referente al tercero '.$nit.'</td>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php
            }else{ 
                $apiUrl = "https://apiautomarcol.up.railway.app/api/clients/1105";

                $postData = array(
                    'nit' => '901448497',
                    'initialMonth' => '01',
                    'finalMonth' => '12',
                    'initialYear' => '2023',
                    'finalYear' => '2023',
                );
                
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error en la solicitud cURL: ' . curl_error($ch);
                }
                curl_close($ch);
                $data = json_decode($response, true);
                $datosPorMes = array();
                foreach ($data as $datos) {
                    $mes = (new DateTime($datos['fecha']))->format('Y-m'); // Obtener el mes en formato "año-mes"
                    if (!isset($datosPorMes[$mes])) {
                        $datosPorMes[$mes] = array();
                    }
                    $datosPorMes[$mes][] = $datos;
                }
            ?>
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-2">
                                            Factura
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Fecha
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Documento
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Nombre
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Vencimiento
                                        </th>
                                        <th scope="col" class="px-3 py-2">
                                            Dias
                                        </th>

                                        <th scope="col" class="px-3 py-2">
                                            Total
                                        </th>
                                        <th scope="col" class="px-1 py-1">
                                            Anulado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
// Mostrar las facturas del mes y luego el total por mes
foreach ($datosPorMes as $mes => $datos) {
    ?>
                                    <!-- Mostrar las facturas del mes -->
                                    <?php
    foreach ($datos as $dato) {
    ?>
                                    <tr class="bg-white text-gray-900 border-b
            <?php
            if (str_contains($dato['tipo'], 'DF') or str_contains($dato['tipo'], 'DC')) {
                echo 'bg-red-100';
            }
            if (str_contains($dato['tipo'], 'CRU') or str_contains($dato['tipo'], 'CE')) {
                echo 'bg-yellow-100';
            }
            if (str_contains($dato['tipo'], 'RC')) {
                echo 'bg-green-100';
            }
            ?>
        ">
                                        <th scope="row" class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            <?php echo $dato['tipo'] . '-' . $dato['numero'] ?>
                                        </th>
                                        <td class="px-3 py-2">
                                            <?php echo (new DateTime($dato['fecha']))->format('Y-m-d'); ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?php echo $dato['prefijo'] . '-' . $dato['documento'] ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?php echo $dato['nombres'] ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?php echo (new DateTime($dato['vencimiento']))->format('Y-m-d'); ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            <?php echo $dato['duracion'] ?>
                                        </td>
                                        <td class="px-3 py-2">
                                            $<?php echo number_format($dato['valor_total'], 0, ',', '.') ?>
                                        </td>

                                        <td class="px-1 py-1">
                                            <?php echo $dato['anulado'] ?>
                                        </td>
                                    </tr>
                                    <?php
    }
    ?>

                                    <!-- Agregar una fila para el total del mes -->
                                    <tr class="bg-gray-400 text-gray-900">
                                        <th colspan="6" class="px-3 py-2 text-center font-medium">
                                            <?php echo date('F Y', strtotime($mes)); ?>
                                            <!-- Mostrar el nombre del mes y año -->
                                        </th>
                                        <th class="px-3 py-2">
                                            <?php
            // Calcular y mostrar la sumatoria de valor_total para el mes
            $sumatoriaMes = array_reduce($datos, function ($acumulado, $dato) {
                return $acumulado + $dato['valor_total'];
            }, 0);
            echo '$'.number_format($sumatoriaMes, 0, ',', '.');
            ?>
                                        </th>
                                        <th></th>
                                    </tr>
                                    <?php
}
?>
                                </tbody>
                            </table>
                            <?php
            }
            ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="opacity-50 fixed inset-0 z-40 bg-gray-900 backdrop" id="back_ls" style="z-index:20005 !important"
    onclick="cerrar();" id="backdrop-pagos"></div>