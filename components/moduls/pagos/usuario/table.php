<?php
  include '../../db/cn.php';
  if (!isset($_SESSION)) {
   session_start();
   }
   $usuario = $_SESSION['key']['usuario'];
   $result_task = json_decode($_POST['pagosData'], true);
?>


<div class="grid grid-cols-1 md:grid-cols-2 gap-0 mb-0 pb-0 pt-0 mt-0">
    <div class="m-4 mt-0  rounded bg-white pb-0" style="height: 50rem">
        <div id="accordion-collapse" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-2">
                <button type="button"
                    class="bg-white flex items-center justify-between w-full p-4 py-4 font-medium text-center text-gray-500 border-b border-gray-200 text-gray-100"
                    style="background-color: #0A2558" aria-controls="accordion-collapse-body-2">
                    <span>HISTORIAL DE PAGOS PENDIENTES</span>
                </button>
            </h2>
            <div id="accordion-collapse-body-2" aria-labelledby="accordion-collapse-heading-2" style="height: 45rem">
                <div class="relative overflow-x-auto h-full p-4">
                    <table class="w-full text-center text-gray-500 text-xs p-4">
                        <thead class="text-xs text-gray-700 uppercase border-b">
                            <tr>
                                <th scope="col" class="px-1 py-3">
                                    Imagen
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Referencia
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Cuenta
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Valor
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Tercero
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Fecha
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="p-5">
                            <?php 
                                  foreach($result_task as $row){ 
                                    $permisosSuficientes = isset($_SESSION['key']['permisos']) && $_SESSION['key']['permisos'] >= 8;

                                    if($row['estado'] == 'Pendiente'){
                                        if ($permisosSuficientes) {
                                        
                        ?>
                            <tr class="bg-white border-b hover:bg-gray-200"
                                onclick="openModal(id='<?php echo $row['id'];?>')">
                                <td class="px-1 py-3">
                                    <img class="h-40 w-40 object-cover mx-auto"
                                        src="./galery/Pagos/<?php echo $row['img'] ?>"
                                        onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                        alt="image description">
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['id'] ?>
                                </td>

                                <td class="px-1 py-1">
                                    <?php echo $row['cuenta'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    $<?php echo $row['valor'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['nombret'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center"
                                        data-tooltip-target="tooltip-no-arrow-<?php  echo $row['id'] ?>">
                                        <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mb-2">
                                            <i class='bx bx-file text-xl'></i>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <?php 
                            }elseif ($row['usuario'] == $usuario) {
                            ?>
                            <tr class="bg-white border-b hover:bg-gray-200"
                                onclick="openModal(id='<?php echo $row['id'];?>')">
                                <td class="px-1 py-3">
                                    <img class="h-40 w-40 object-cover mx-auto"
                                        src="./galery/Pagos/<?php echo $row['img'] ?>"
                                        onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                        alt="image description">
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['id'] ?>
                                </td>

                                <td class="px-1 py-1">
                                    <?php echo $row['cuenta'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    $<?php echo $row['valor'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['nombret'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center"
                                        data-tooltip-target="tooltip-no-arrow-<?php  echo $row['id'] ?>">
                                        <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mb-2">
                                            <i class='bx bx-file text-xl'></i>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="m-4 mt-0  rounded bg-white pb-0" style="height: 50rem">
        <div id="accordion-collapse" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-2">
                <button type="button"
                    class="bg-white flex items-center justify-between w-full p-4 py-5 font-medium text-center text-gray-500 border-b border-gray-200 text-gray-100"
                    style="background-color: #0A2558" aria-controls="accordion-collapse-body-2">
                    <span>HISTORIAL DE PAGOS CAUSADOS</span>
                </button>
            </h2>
            <div id="accordion-collapse-body-2" aria-labelledby="accordion-collapse-heading-2" style="height: 45rem">
                <div class="relative overflow-x-auto h-full p-4">
                    <table class="w-full text-center text-gray-500 text-xs p-4">
                        <thead class="text-xs text-gray-700 uppercase border-b">
                            <tr>
                                <th scope="col" class="px-1 py-3">
                                    Imagen
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Referencia
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Cuenta
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Valor
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Tercero
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Fecha
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="p-5">
                            <?php 
                                  foreach($result_task as $row){ 
                                    if($row['estado'] != 'Pendiente'){
                                        if ($permisosSuficientes) {
                        ?>
                            <tr class="bg-white border-b hover:bg-gray-200"
                                onclick="openModal(id='<?php echo $row['id'];?>')">
                                <td class="px-1 py-3">
                                    <img class="h-40 w-40 object-cover mx-auto"
                                        src="./galery/Pagos/<?php echo $row['img'] ?>"
                                        onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                        alt="image description">
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['id'] ?>
                                </td>

                                <td class="px-1 py-1">
                                    <?php echo $row['cuenta'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    $<?php echo $row['valor'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['nombret'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center"
                                        data-tooltip-target="tooltip-default-<?php  echo $row['id']?>">
                                        <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mb-2">
                                            <i class='bx bx-file text-xl'></i>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                            <?php 
                            }elseif ($row['usuario'] == $usuario) {
                            ?>
                            <tr class="bg-white border-b hover:bg-gray-200"
                                onclick="openModal(id='<?php echo $row['id'];?>')">
                                <td class="px-1 py-3">
                                    <img class="h-40 w-40 object-cover mx-auto"
                                        src="./galery/Pagos/<?php echo $row['img'] ?>"
                                        onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                        alt="image description">
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['id'] ?>
                                </td>

                                <td class="px-1 py-1">
                                    <?php echo $row['cuenta'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    $<?php echo $row['valor'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo $row['nombret'] ?>
                                </td>
                                <td class="px-1 py-1">
                                    <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center"
                                        data-tooltip-target="tooltip-default-<?php  echo $row['id']?>">
                                        <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mb-2">
                                            <i class='bx bx-file text-xl'></i>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>