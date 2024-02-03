<?php
 include '../../../db/cn.php';
  $data = $_POST['valorBusqueda'];
  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];

  $buscador = "SELECT * FROM pagos WHERE fecha >= '$date1' AND fecha <= '$date2' AND id LIKE '%$data%' OR cuenta LIKE '%$data%' OR nombret LIKE '%$data%' OR valor = '$data' OR usuario ='$data' ORDER BY fecha DESC LIMIT 10";
  $historico = mysqli_query($conn, $buscador);
?>

<div class="p-2 bg-white">
    <div class="relative overflow-x-auto h-full mx-3">
        <div class="flex items-start justify-between p-4 pb-0 rounded-t ">
            <button type="button" onclick="close_buscador();"
                class="text-slate-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
               
            </button>
        </div>
        <table class="w-full text-stard text-gray-500 text-xs p-1">
            <thead class="text-xs text-gray-700 uppercase border-b">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Referencia
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cuenta
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Valor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tercero
                    </th>
                    <th scope="col" class="px-6 py-3">
                        N Documento
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Factura
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cancelado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3">
                        De
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Autorizador
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Rc
                    </th>
                </tr>
            </thead>
            <tbody class="p-1 text-center">
                <?php if (mysqli_num_rows($historico) > 0) { ?>
                <?php foreach($historico as $row) { ?>
                <tr class="bg-white border-b hover:bg-gray-200" onclick="openModal(id='<?php echo $row['id'];?>')">
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
                        <?php echo $row['documentot'] ?>
                    </td>
                    <td class="px-1 py-1">
                        <?php echo $row['e_factura'] ?>
                    </td>
                    <td class="px-1 py-1">
                        <?php echo $row['cancelado'] ?>
                    </td>
                    <td class="px-1 py-1">
                        <?php echo date("d/m/Y", strtotime($row['fecha'] ));?>
                    </td>
                    <td class="px-1 py-1">
                        <?php echo $row['usuario'] ?>
                    </td>
                    <td class="px-1 py-1">
                        <?php echo $row['autorizador'] ?>
                    </td>
                    <td class="px-6 py-6">
                        <div class="flex items-center" data-tooltip-target="tooltip-no-arrow-<?php  echo $row['id'] ?>">
                            <div class="h-2.5 w-2.5 rounded-full <?php echo $row['estado'] ?> mr-2">
                                <i class='bx bx-file text-xl'></i>
                            </div>
                        </div>
                    </td>
                    <td class="px-2 py-4">
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
                <?php } else { ?>
                <tr>
                    <td colspan="11">
                        <p class="p-2 text-md">No se encontraron documentos.</p>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>