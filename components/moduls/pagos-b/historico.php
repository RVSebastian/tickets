<?php
   // leemos los pagos
   include '../../db/cn.php';
   session_start();
   $usuarios = "SELECT DISTINCT usuario FROM pagos ORDER BY usuario DESC";
   $result_user = mysqli_query($conn, $usuarios);
   $result_task = json_decode($_POST['pagosData'], true);
   $contadorcuenta = 0;
   $cuentas =  $_SESSION['cuentas'];
?>

<div class="my-2">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 m-0 mb-0 pb-0 pt-0 mt-0">
        <!-- LISTADO DE PAGOS POR CUENTA -->
        <div class="p-5 pt-0">
            <div id="accordion-flush" data-accordion="collapse">
                <?php foreach ($cuentas as $cuenta) { ?>
                <h2 id="accordion-flush-<?php echo $contadorcuenta ?>">
                    <button type="button"
                        class="bg-white flex items-center justify-between w-full p-4 py-5 font-medium text-center text-gray-500 border-b border-gray-200"
                        data-accordion-target="#accordion-flush-body-<?php echo $contadorcuenta ?>"
                        aria-controls="accordion-flush-body-<?php echo $contadorcuenta ?>">
                        <span><i class='bx bx-copy'></i> <?php echo $cuenta; ?> (<span
                                id="contador2-<?php echo $contadorcuenta; ?>"></span>)</span>
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="accordion-flush-body-<?php echo $contadorcuenta ?>" class="hidden"
                    aria-labelledby="accordion-flush-heading-<?php echo $contadorcuenta ?>">
                    <div style="height: 30rem">
                        <div class="relative overflow-x-auto bg-white shadow p-4 h-full">
                            <table class="w-full text-xs text-center text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase border-b">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Imagen
                                        </th>
                                        <th scope="col" class="px-1 py-1">
                                            Referencia
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
                                        <th scope="col" class="px-1 py-3">
                                            Fecha
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
                                    if($row['cuenta'] == $cuenta){
                                    $contadorpagos++;
                                    ?>
                                    <tr class="bg-white border-b hover:bg-gray-200" id='<?php echo $contadorcuenta ?>'
                                        onclick="openModal(id='<?php echo $row['id'];?>')">
                                        <td class="px-1 py-3">
                                            <img class="h-40 w-40 object-cover"
                                                src="./galery/Pagos/<?php echo $row['img'] ?>"
                                                onerror="this.onerror=null;this.src='https://automarcol.com/image/SUBIR%20PAGO.png';"
                                                alt="image description">
                                        </td>

                                        <td class="px-1 py-3">
                                            <?php echo $row['id'] ?>
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
                                            <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                        </td>
                                        <td class="px-1 py-3 text-center align-items-center">
                                            <div class="flex items-center"
                                                data-tooltip-target="tooltip-default-<?php  echo $row['id'] ?>">
                                                <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mr-2">
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
                        <?php } ?>
                        <?php } ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php 
              // Mostrar el contador de pagos pendientes para esta cuenta
            echo '<script>document.getElementById("contador2-' . $contadorcuenta . '").innerText = ' . $contadorpagos . ';</script>';
            $contadorcuenta++; 
            } ?>
        </div>


    </div>

    <!-- LISTADO DE USUARIO CON SU RESPECTIVOS PAGOS -->
    <div class="p-5 pt-0" style="height: 50rem">
        <div id="accordion-flush" data-accordion="collapse">
            <?php foreach ($result_user as $users) { ?>
            <h2 id="accordion-flush-<?php echo $users['usuario'] ?>">
                <button type="button"
                    class="bg-white flex items-center justify-between w-full p-4 py-5 font-medium text-center text-gray-500 border-b border-gray-200"
                    data-accordion-target="#accordion-flush-body-<?php echo $users['usuario'] ?>"
                    aria-controls="accordion-flush-body-<?php echo $users['usuario'] ?>">
                    <span><i class='bx bx-copy'></i> <?php echo $users['usuario']; ?> (<span
                            id="contador3-<?php echo  $users['usuario']; ?>"></span>)</span>
                    <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </h2>
            <div id="accordion-flush-body-<?php echo $users['usuario'] ?>" class="hidden"
                aria-labelledby="accordion-flush-heading-<?php echo $users['usuario'] ?>">
                <div style="height: 30rem">
                    <div class="relative overflow-x-auto bg-white shadow p-4 h-full">
                        <table class="w-full text-xs text-center text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
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
                                    <th scope="col" class="px-1 py-3">
                                        Rc
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                 $contadorpagos=0;
                                  foreach($result_task as $row){ 
                                    if($row['usuario'] == $users['usuario']){
                                        $contadorpagos++;
                        ?>
                                <tr class="bg-white border-b hover:bg-gray-200"
                                    onclick="openModal(id='<?php echo $row['id'];?>')">
                                    <td class="px-1 py-3">
                                        <img class="h-40 w-40 object-cover"
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
                                        $<?php echo $row['valor'] ?>
                                    </td>
                                    <td class="px-1 py-3">
                                        <?php echo $row['nombret'] ?>
                                    </td>
                                    <td class="px-1 py-3">
                                        <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                                    </td>
                                    <td class="px-1 py-3">
                                        <div class="flex items-center"
                                            data-tooltip-target="tooltip-default-<?php  echo $row['id'] ?>">
                                            <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mr-2">
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
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php 
        $usuario = $users['usuario'];
        echo '<script>document.getElementById("contador3-' . $usuario . '").innerText = ' . $contadorpagos . ';</script>';
        $contadorcuenta++; 
        } ?>
        </div>
    </div>
</div>