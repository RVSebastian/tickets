<style>
.viewpay-ajust {
    transform: translate(-50%, -47%);
}

@media screen and (max-width: 500px) {
    .viewpay-ajust {
        transform: translate(-50%, -10%) !important;
    }
}
</style>
<script>
function mostrarv2(id) {
    const v1 = document.getElementById('v-p-1-' + id);
    const v2 = document.getElementById('v-p-2-' + id);
    $("#v-p-1-" + id).toggleClass("hidden");
    $("#v-p-2-" + id).toggleClass("hidden");
}

function openModal(id) {
    const modalID = document.getElementById('viewpay' + id);
    const modalBG = document.getElementById('backdrop' + id);
    modalID.classList.remove("hidden");
    modalBG.classList.remove("hidden");
    const list_pagos = document.getElementById('consultador_pagos_view');
    list_pagos.classList.add("hidden");

}

function closeModal(id) {
    const modalID = document.getElementById('viewpay' + id);
    const modalBG = document.getElementById('backdrop' + id);
    modalID.classList.add("hidden");
    modalBG.classList.add("hidden");
}

function imagenError(img) {
    img.onerror = null;
    img.src = 'https://automarcol.com/image/SUBIR%20PAGO.png';
    img.alt = 'Imagen no disponible';
}
</script>
<?php
include '../../db/cn.php';
session_start();

$result_task_all = json_decode($_POST['pagosData'], true);


$compararCampos = ['tipo_tercero' ,'cuenta', 'valor', 'nombret', 'documentot','fecha'];
$pagosIguales = [];

foreach ($result_task_all as $indicePago => $pago) {
    foreach ($result_task_all as $indiceComparacion => $pagoComparacion) {
        if ($indicePago !== $indiceComparacion) {
            $iguales = [];
            foreach ($compararCampos as $campo) {
                if ($campo === 'valor') {
                    $valorPago = str_replace(',', '', $pago['valor']);
                    $valorPago = str_replace('.', '.', $valorPago);
                    $valorComparacion = str_replace(',', '', $pagoComparacion['valor']);
                    $valorComparacion = str_replace('.', '.', $valorComparacion);
                    $valorPago = floatval($valorPago);
                    $valorComparacion = floatval($valorComparacion);
                    $valorPago = round($valorPago, 2);
                    $valorComparacion = round($valorComparacion, 2);
                    $pago[$campo] = $valorPago;
                    $pagoComparacion[$campo] = $valorComparacion;
                    $df = abs($pago['valor'] - $pagoComparacion[$campo]);
                }
                if ($pago[$campo] == $pagoComparacion[$campo] or isset($df) and $df < 5000 ) {
                    $iguales[] = $campo;
                }
            }
            if (count($iguales) > 3) {
                $pagosIguales[$pago['id']] = "Pago {$pago['id']} es igual a pago {$pagoComparacion['id']} por los siguientes motivos: " . implode(', ', $iguales);
                $pagosIguales[$pagoComparacion['id']] = "Pago {$pagoComparacion['id']} es igual a pago {$pago['id']}  por los siguientes motivos: " . implode(', ', $iguales);
            }
        }
       
    }
}

$recibos = array(); // Un array para almacenar los números de factura

foreach ($result_task_all as $pago) {
    $recibo = $pago['recibo'];
    // Añadir el número de factura al array si aún no está presente
    if (!in_array($recibo, $recibos)) {
        $recibos[] = $recibo;
    }
}

// Convierte el array de recibos en una cadena JSON
$recibosJson = json_encode($recibos);

// Genera un script JavaScript para guardar los recibos en el localStorage
echo "<script>localStorage.setItem('recibos', '$recibosJson');</script>";



foreach ($result_task_all as $row) {
?>
<div id="viewpay<?php echo $row['id'] ?>" tabindex="20000" style='z-index: 20000 !important;' aria-hidden="true"
    class="viewpay hidden w-full p-2.5 pt-0 mt-0  absolute inset-0 transition-2">
    <div class="absolute viewpay-ajust w-full h-full max-w-5xl h-4xl rounded overflow-y-auto top-0 left-0 right-0 z-50 top-modal"
        style="margin:0px; left: 50%; position: fixed;">
        <div class="bg-white rounded-lg shadow rounded border-b-3  border-<?php echo $row['estado'] ?>">
            <div class="grid sm:grid-cols-1 md:grid-cols-2" style="border: 0 !important;">
                <div class="basis-11/12 md:basis-2/4 mx-3 mb-0" id='d<?php echo $row['id'] ?>' class="sideimg"
                    style="border: 0 !important;">
                    <a href="./galery/Pagos/<?php echo $row['img'] ?>" style="border: 0 !important;" class="p-2"
                        target="_blank">
                        <img style="margin: 0 auto !important;border: 0 !important; height: 36rem !important;"
                            class="w-fill" src="./galery/Pagos/<?php echo $row['img'] ?>"
                            id="imgv<?php echo $row['id'] ?>" onerror="imagenError(this);">
                    </a>
                </div>
                <div class="basis-11/12 md:basis-2/4 bg-gray-100" id="v-p-1-<?php echo $row['id']; ?>">
                    <div class="flex items-start justify-between p-4 pb-0 rounded-t ">
                        <button type="button" onclick="closeModal(<?php echo $row['id'] ?>);"
                            class="text-slate-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            onclick="closeModal(<?php echo $row['id'] ?>);">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-1.5 pt-0 mt-0 pb-0 overflow-y-auto text-justify ">
                        <p class="text-base leading-relaxed text-gray-800 pb-0 mt-0 mb-0 pb-0">
                            <i class='bx bx-user'></i>Usuario: <?php echo $row['usuario'] ?>
                        </p>
                        <p class="text-base leading-relaxed text-gray-800 pt-0 mt-0 mb-0">
                            <i class='bx bxs-calendar'></i>Subido el <?php echo $row['fecha'] ?>
                        </p>
                        <p class="text-base leading-relaxed text-gray-800 mt-0 mb-0"><i
                                class='bx bx-cylinder'></i>Estado:
                            <span class="text-<?php echo $row['estado'] ?>"><?php echo $row['estado'] ?> por
                                <?php echo $row['autorizador'] = (isset($row['estado']) and $row['estado'] == 'Pendiente') ? 'Revisar' : $row['autorizador']; ?></span>
                        </p>
                        <form class="text-gray-500 p-0 m-0 docu-link" id='ver_pagos_tercero'>
                            <button type="submit" class="consultador_pagos"><i class='bx bx-history'></i> Ver
                                Pagos</button>
                            <input type="hidden" name="nit" value="<?php echo $row['documentot'] ?>">
                            <input type="hidden" name="ver_pagos" value="<?php echo $row['id'] ?>">
                        </form>

                        <hr>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-coin-stack'></i><span class="text-gray-900 text-semibold">Banco de
                                receptor:</span>
                        </p>
                        <?php echo $row['cuenta'] ?>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-barcode-reader'></i><span class="text-gray-900 text-semibold">Codigo
                                Verificacion: <?php echo $row['codigo_baucher'] ?></span>
                        </p>

                        <p class="text-base leading-relaxed text-gray-800 pt-0 mt-0 mb-0">
                            <i class='bx bx-calendar-week'></i>Fecha del Pago: <?php echo $row['fecha_pago'] ?>
                        </p>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bxs-credit-card'></i><span class="text-gray-900 text-semibold">Valor: </span>
                            $<?php echo $row['valor'] ?>
                        </p>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bxs-file'></i><span class="text-gray-900 text-semibold">Factura asociada:
                            </span>
                            <?php $f = empty($row['e_factura']) ?  'N/A' : strtoupper($row['e_factura']); echo $f; ?>
                        </p>
                        <hr>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-user'></i><span class="text-gray-900 text-semibold"> Tercero</span>
                        </p>
                        <?php echo $row['nombret'] ?>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-id-card'></i><span class="text-gray-900 text-semibold"> Documento </span>
                        </p>
                        <?php echo $row['documentot'] ?>
                        <div class="mb-2">
                            <p class="text-base leading-relaxed text-gray-800 mt-0">
                                <i class='bx bx-text'></i>Nota
                            </p>
                            <?php 
                            if (!empty($row['notas_2'])) {
                                $t = empty($row['notas_2']) ?  'N/A' : $row['notas_2']; echo $t;
                            }else{
                                $t = empty($row['otro']) ?  'N/A' : $row['otro']; echo $t; 
                            }
                            ?>
                        </div>

                        <?php
                        if (!empty($row['recibo'])) {
                        ?>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-file'></i><span class="text-gray-900 text-semibold">Recibo de Caja</span>
                        </p>
                        <?php echo $row['recibo']?>
                        <?php } ?>

                        <?php if ($row['estado'] == 'Rechazado') {
                        ?>
                        <p class="text-base leading-relaxed text-gray-800 mt-0">
                            <i class='bx bx-message-alt-x'></i><span class="text-gray-900 text-semibold">Motivo del
                                Rechazo:</span>
                        </p>
                        <?php $cancelado = empty($row['cancelado']) ? 'Sin Motivo' : $row['cancelado'];
                        echo $cancelado;
                        ?>
                        <?php } ?>
                        <hr>
                        <a class="text-base leading-relaxed text-gray-500" href="../generar?id=<?php echo $row['id'] ?>"
                            target="_blank"><i class='bx bx-printer'></i> Generar
                            Comprobante</a>

                        <!--
                        <form class="text-gray-500 p-0 m-0 docu-link">
                            <button type="submit" class="consultador"><i class='bx bx-history'></i> Consultar
                                Documentos</button>
                            <input type="hidden" name="nit" value="<?php echo $row['documentot'] ?>">
                        </form>
                        -->
                    </div>
                    <?php if ($_SESSION['key']['permisos']>=8 or $_SESSION['key']['cargo'] == 'Cajera' and $row['estado'] != 'Rechazado') { ?>
                    <div class="h-fill" style="margin-top: 6%;">
                        <?php if(empty($row['recibo']) and $row['estado'] == 'Autorizado') { ?>
                        <div class="flex flex-row items-center p-6 space-x-2 bg-gray-200 border-t border-gray-200">
                            <form method="POST" class="flex items-center">
                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                <input type="hidden" name="user" value="<?php echo $_SESSION['key']['usuario']?>">
                                <input type="hidden" name="guardarc" value="true">
                                <button data-modal-hide="defaultModal<?php echo $row['id'] ?>"
                                    data-form="formulario<?php echo $row['id'] ?>" type="button" name="guardarc"
                                    id="guardarc<?php echo $row['id'] ?>"
                                    class="enviador text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm text-center p-2">Guardar</button>
                                <input required type="text" id="recibo" name="recibo"
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ml-2"
                                    placeholder="RC 3959" required>
                            </form>
                        </div>
                        <?php } ?>
                        <?php if($row['estado'] == 'Pendiente') { // vista para autorizar o rechazar ?>
                        <div class="flex flex-row items-center p-6 space-x-2 bg-gray-200 border-t border-gray-200">
                            <form method="POST">
                                <button type="button" onclick="mostrarv2(id='<?php echo $row['id']; ?>')"
                                    class="text-white bg-slate-700 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm text-center p-2 m-2">Autorizar</button>
                            </form>
                            <div class="flex flex-row items-center space-x-2">
                                <form method="POST" class="flex items-center">
                                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                    <input type="hidden" name="user" value="<?php echo $_SESSION['key']['usuario']?>">
                                    <input type="hidden" name="Rechazar" value="true">
                                    <button data-modal-hide="defaultModal<?php echo $row['id'] ?>" type="button"
                                        name="Rechazar"
                                        class="enviador text-white bg-slate-700 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm text-center p-2">Rechazar</button>
                                    <input required type="text" id="recibo" name="motivo_rechazo"
                                        class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ml-2"
                                        placeholder="Motivo" required>
                                </form>
                            </div>

                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>



                </div>
                <div class="basis-11/12 md:basis-2/4 bg-gray-100 hidden" id="v-p-2-<?php echo $row['id']; ?>">
                    <div class="flex items-start justify-between p-4 pb-0 rounded-t ">
                        <div class="inline-flex items-center">
                            <p class="text-base leading-relaxed text-gray-800 hover:cursor-pointer"
                                onclick="mostrarv2(id='<?php echo $row['id']; ?>')">
                                <i class='bx bx-left-arrow-alt'></i> Regresar
                            </p>
                        </div>
                        <button type="button" onclick="closeModal(<?php echo $row['id'] ?>);"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            onclick="closeModal(<?php echo $row['id'] ?>);">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <form method="POST" enctype="multipart/form-data" id="form_validar_pago">
                        <div class="p-6 space-y-2 pt-0 mt-0 overflow-y-auto text-justify ">

                            <label for="cuenta" class="block text-sm font-medium text-gray-900 mt-2">Cuenta</label>
                            <select required id="cuenta" name='cuenta'
                                class="bg-gray-100 border border-gray-300 mb-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="BANCOLOMBIA: 49784801986 CORRIENTE">BANCOLOMBIA:
                                    49784801986 CORRIENTE
                                </option>
                                <option value="BANCOLOMBIA: 49796209967 AHORROS">BANCOLOMBIA:
                                    49796209967 AHORROS
                                </option>
                                <option value="BANCOLOMBIA: 49796484542 AHORROS">BANCOLOMBIA:
                                    49796484542 AHORROS
                                </option>
                                <option value="BANCO OCCIDENTE: 600882203 AHORROS">BANCO OCCIDENTE:
                                    600882203 AHORROS
                                </option>
                                <option value="BANCO OCCIDENTE: 600100234 CORRIENTE">BANCO
                                    OCCIDENTE: 600100234
                                    CORRIENTE</option>
                                <option value="BBVA: 697910100007623 CORRIENTE">BBVA:
                                    697910100007623 CORRIENTE</option>
                                <option value="BANCOLOMBIA: 49798522255 AHORROS">BANCOLOMBIA:
                                    49798522255 AHORROS
                                </option>
                                <option value="DAVIVIENDA: 66800158801 AHORROS">DAVIVIENDA:
                                    66800158801 AHORROS</option>
                                <option value="BANCO DE BOGOTA: 614247591 CORRIENTE">BANCO DE
                                    BOGOTA: 614247591
                                    CORRIENTE</option>
                            </select>
                            <div class="mb-2">
                                <label for="small-input"
                                    class="block mb-1 text-sm font-medium text-gray-900">Valor</label>
                                <input required type="text" id="small-input" name='valor' oninput="formatNumber(this)"
                                    class="block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-2">
                                <label for="tercero" class="block text-sm font-medium text-gray-900 ">Tipo
                                    de
                                    Tercero</label>
                                <select required id="tercero" name='tipo_tercero'
                                    class="bg-gray-100 border border-gray-300 mb-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="CEDULA">Cedula</option>
                                    <option value="NIT">Nit</option>
                                </select>
                                <label for="small-input" class="block mb-1 text-sm font-medium text-gray-900">Numero de
                                    Documento o Nit</label>
                                <input required type="text" id="small-input" name='documentot'
                                    class="block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-2">
                                <label for="small-input" class="block mb-1 text-sm font-medium text-gray-900">Nombre
                                    Completo o Razon Social</label>
                                <input required type="text" id="small-input" name='nombret'
                                    class="block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-2">
                                <label for="small-input" class="block mb-1 text-sm font-medium text-gray-900">Fecha
                                    Pago</label>
                                <input type="date" name="fecha_pago"
                                    class="block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                                    required />
                            </div>
                            <div class="mb-2">
                                <label for="small-input" class="block mb-1 text-sm font-medium text-gray-900">Codigo
                                    Verificacion</label>
                                <select required id="codigo_baucher" name='codigo_baucher'
                                    class="bg-gray-100 border border-gray-300 mb-2 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="small-input"
                                    class="block mb-1 text-sm font-medium text-gray-900">Notas</label>
                                <input required type="text" id="small-input" name='notas_2'
                                    class="block w-full p-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-100 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>

                        </div>
                        <div class="flex flex-row items-center p-6 space-x-3 bg-gray-200 border-t border-gray-200">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <input type="hidden" name="user" value="<?php echo $_SESSION['key']['usuario']?>">
                            <input type="hidden" name="Autorizar" value="true">
                            <button data-modal-hide="defaultModal<?php echo $row['id'] ?>" type="button"
                                name="Autorizar"
                                class="validar_pago text-white bg-slate-600 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm text-center p-2 m-2">Autorizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden opacity-50 fixed inset-0 z-40 bg-gray-900 backdrop" id="backdrop<?php echo $row['id'] ?>"
    onclick="closeModal(<?php echo $row['id'] ?>);"></div>

<?php
};
?>