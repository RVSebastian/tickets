<?php
// leemos los pagos
include '../../db/cn.php';
session_start();

$result_task = json_decode($_POST['pagosData'], true);
$cuentas = $_SESSION['cuentas'];
$meses_letra = array(
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
);
$año_superior = 2024;
?>

<div class="my-2">
    <div class="grid grid-cols-1 md:grid-cols-1 gap-0 m-0 mb-0 pb-0 pt-0 mt-0">
        <div class="p-5 pt-0">

            <?php 
            // recorremos por año
            for ($año_inferior = 2023; $año_inferior <= $año_superior; $año_inferior++) { 
                $contadorcuenta = 1;
                $contador_pagos_año = 0;
            ?>

            <div id="accordion-collapse-<?php echo $año_inferior ?>" data-accordion="collapse">
                <h2 id="accordion-collapse-heading-<?php echo $año_inferior ?>">
                    <button type="button"
                        class="bg-white flex items-center justify-between w-full p-4 py-5 font-medium text-center text-gray-500 border-b border-gray-200"
                        data-accordion-target="#accordion-collapse-body-<?php echo $año_inferior ?>"
                        aria-expanded="true" aria-controls="accordion-collapse-body-<?php echo $año_inferior ?>">
                        <span><i class='bx bx-hdd'></i> <?php echo $año_inferior ?> (<span
                                id="contador-total-<?php echo $año_inferior ?>"></span>)</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-<?php echo $año_inferior ?>" class="hidden"
                    aria-labelledby="accordion-collapse-heading-<?php echo $año_inferior ?>">
                    <div class="relative overflow-x-auto bg-white p-4 h-full">
                        <?php
                        // recorremos por meses
                        for ($i = 1; $i <= 12; $i++) { 
                            $pagospormes = 0;
                        ?>
                        <div id="accordion-flush2-<?php echo $año_inferior . '-' . $i ?>" data-accordion="collapse">
                            <h2 id="accordion-flush-<?php echo $año_inferior . '-' . $i ?>">
                                <button type="button"
                                    class="bg-white flex items-center justify-between w-full p-4 py-5 font-medium text-center text-gray-500 border-b border-gray-200"
                                    data-accordion-target="#mes-flush-body-<?php echo $i . '-' . $año_inferior ?>"
                                    aria-controls="mes-flush-body-<?php echo $i . '-' . $año_inferior ?>">
                                    <span><i class='bx bx-calendar'></i> <?php echo $meses_letra[$i]; ?> (<span
                                            id="año-<?php echo $año_inferior;?>-mes-<?php echo $i; ?>"></span>)</span>
                                    <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="mes-flush-body-<?php echo $i . '-' . $año_inferior ?>" class="hidden">
                                <!-- Contenido del acordeón de meses -->
                                <div style="height: auto" class="mb-2">
                                    <div id="accordion-flush" data-accordion="collapse">
                                        <?php foreach ($cuentas as $cuenta) { ?>
                                        <h2
                                            id="accordion-flush-<?php echo $año_inferior . '-' . $i . '-' . $contadorcuenta; ?>">
                                            <button type="button"
                                                class="bg-white flex items-center justify-between w-full p-4 pl-6 py-4 font-medium text-center text-gray-500 border-b border-gray-200"
                                                data-accordion-target="#accordion3-flush-body-<?php echo $año_inferior . '-' . $i . '-' . $contadorcuenta; ?>"
                                                aria-controls="accordion3-flush-body-<?php echo $año_inferior . '-' . $i . '-' . $contadorcuenta; ?>">
                                                <span><i class='bx bx-copy'></i> <?php echo $cuenta; ?> (<span
                                                        id="año-<?php echo $año_inferior; ?>-contador3-<?php echo $contadorcuenta; ?>"></span>)</span>
                                                <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0"
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="accordion3-flush-body-<?php echo $año_inferior . '-' . $i . '-' . $contadorcuenta; ?>"
                                            class="hidden">
                                            <!-- Contenido del acordeón de cuentas -->
                                            <div style="height: auto">
                                                <table class="w-full text-xs text-center  text-gray-500">
                                                    <thead class="text-xs text-gray-700 uppercase border-b">
                                                        <tr>
                                                            <th scope="col" class="px-1 py-1">
                                                                Imagen
                                                            </th>
                                                            <th scope="col" class="px-1 py-1">
                                                                Referencia
                                                            </th>
                                                            <th scope="col" class="px-1 py-1">
                                                                Cuenta
                                                            </th>
                                                            <th scope="col" class="px-1 py-1">
                                                                Usuario
                                                            </th>
                                                            <th scope="col" class="px-1 py-1">
                                                                Valor
                                                            </th>
                                                            <th scope="col" class="px-1 py-3">
                                                                Tercero
                                                            </th>
                                                            <th scope="col" class="px-1 py-1">
                                                                Nit
                                                            </th>
                                                            <th scope="col" class="px-1 py-3">
                                                                Fecha
                                                            </th>
                                                            <th scope="col" class="px-1 py-3">
                                                                Autorizador
                                                            </th>
                                                            <th scope="col" class="px-1 py-3">
                                                                Estado
                                                            </th>
                                                            <th scope="col" class="px-1 py-3">
                                                                Rc
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $contadorpagos=0;
                                                            foreach($result_task as $row){ 
                                                            $fechaParts = explode('-', $row['fecha']);
                                                            $anio = $fechaParts[0];
                                                            $mes = $fechaParts[1];
                                                            if($row['cuenta'] == $cuenta && $anio == $año_inferior && $mes == $i && $row['estado']=='Autorizado' && $row['recibo'] != '' ){
                                                            $contadorpagos++;
                                                            $pagospormes++;
                                                            $contador_pagos_año++;
                                                            ?>
                                                        <tr class="bg-white border-b hover:bg-gray-200"
                                                            id='<?php echo $año_inferior.'-'.$contadorcuenta.'-'.$i; ?>'
                                                            onclick="openModal(id='<?php echo $row['id'];?>')">
                                                            <td class="px-1 py-3">
                                                                <img class="h-40 w-40 object-cover mx-auto"
                                                                    src="./galery/Pagos/<?php echo $row['img'] ?>"
                                                                    onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                                                    alt="image description">
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['id'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['cuenta'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['usuario'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                $<?php echo $row['valor'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['nombret'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['documentot'] ?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <?php echo $row['autorizador'] ?>
                                                            </td>
                                                            <td class="px-1 py-3 text-center align-items-center">
                                                                <div class="flex items-center"
                                                                    data-tooltip-target="tooltip-default-<?php  echo $row['id'] ?>">
                                                                    <div
                                                                        class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mr-2">
                                                                        <i class='bx bx-file text-xl'></i>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-1 py-3">
                                                                <div class="flex items-center">
                                                                    <div class="h-2.5 w-2.5 rounded-full mr-2">
                                                                        <i class='text-xl <?php if (!empty($row['recibo'])) {
                                                 echo 'bx bx-file-blank';
                                            }?>'></i>
                                                                    </div>
                                                                </div>

                                                            </td>
                                            </div>
                                            </tr>
                                            <?php 
                                    } 
                                    
                                    ?>

                                            <?php 
                                    } 
                                   
                                    ?>
                                            </tbody>
                                            </table>
                                            <?php
                                     if ($pagospormes == 0) {
                                        echo '<div class="p-4 text-center bg-gray-100">no se encontro ningun pago en el rango selecionado</div>';
                                    }
                                    ?>
                                        </div>
                                    </div>
                                    <?php 
                                    // Mostramos el contador de pagos pendientes por las cuentas
                                     echo '<script>document.getElementById("año-'.$año_inferior.'-contador3-' . $contadorcuenta . '").innerText = ' . $contadorpagos . ';</script>';
                                            // Incrementar el contador de cuentas
                                            $contadorcuenta++; 
                                        } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Mostramos el contador de pagos pendientes por mes
                       echo '<script>document.getElementById("año-'.$año_inferior.'-mes-' . $i . '").innerText = ' . $pagospormes . ';</script>';
                        }
                        ?>
                </div>
            </div>
        </div>
        <?php 
        // MOSTRAMOS EL CONTADOR DE PAGOS POR AÑO
        echo '<script>document.getElementById("contador-total-'.$año_inferior.'").innerText = ' . $contador_pagos_año . ';</script>';
            }
            ?>

    </div>
</div>
</div>