<?php
include '../../db/cn.php';
session_start();
$empresa = $_SESSION['key']['empresa'];
if ($empresa == 'ADMINISTRADOR') {
    $query = "select * from encabeza_coti order by id desc";
}else{
    $query = "
    select 
    ec.*
    from encabeza_coti as ec
    where 
    ec.empresa='$empresa' 
    order by ec.id desc";
}

$result_task = mysqli_query($conn, $query);
$row_count = mysqli_num_rows($result_task);
?>
<script>
function cargarCoti(id) {
    $('#coti_list_all,#coti_search,#coti').fadeOut();
    $.ajax({
        type: "POST",
        url: "./components/moduls/cotizacion/list.php",
        data: {
            id
        },
        success: function(response) {
            $('#response').html(response).show();
        }
    });
}

$("#buscador_cot").on("input", function() {
    var valorFiltro = $(this).val().toLowerCase();

    // Contador para rastrear el número de filas visibles
    var filasVisibles = 0;

    $("#table_cotiz tbody tr").filter(function(index) {
        // Mostrar solo las primeras 10 filas que coincidan con la búsqueda
        var mostrar = filasVisibles < 20 && $(this).text().toLowerCase().indexOf(valorFiltro) > -1;

        // Actualizar el contador solo si la fila se muestra
        if (mostrar) {
            filasVisibles++;
        }

        $(this).toggle(mostrar);
    });
});

$('.search_list').click(function() {
    cargarCoti($(this).data("id")); //
});

$('#add-modal').click(function() {
    cargarCoti(0);
});
</script>
<div id="response" class="hidden">

</div>
<div id="coti_list_all">
    <div class="relative overflow-x-auto pb-5 pt-3 mx-5">
        <div class="w-full mb-3 mt-6 bg-white rounded">
            <div class="py-2 px-2">
                <div class="flex">
                    <div class="flex-1 pt-1">
                        <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="buscador_cot"
                                class="block w-full p-3 ps-10  text-sm text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 border-0"
                                placeholder="Buscar por Codigo, Nombre, Valor" required>
                        </div>
                    </div>
                    <div class="flex-2">
                        <button type="button" id="add-modal"
                            class="p-2.5  mx-4 my-2 bg-green-600 hover:bg-green-500 rounded text-white"><i
                                class='bx bx-plus'></i></button>
                    </div>
                </div>

            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="table_cotiz">
            <tbody>
                <?php
               foreach ($result_task as $row) {
                ?>
                <tr class="bg-white rounded border-b hover:bg-gray-100 search_list" data-id="<?php echo $row['id'];?>">
                    <td class="px-2 py-4 text-center">
                    <i class='bx bx-file-blank 
                    <?php 
                    switch ($row['estado']) {
                        case '2':
                            echo 'bg-green-700';
                            break;
                        case '3':
                            echo 'bg-red-700';
                             break;
                        default:
                            echo 'bg-slate-900';
                            break;
                    }
                    ?> text-white rounded p-2 '></i>
                    </td>
                    <td class="px-2 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        CT-<?php echo $row['id']; ?>
                    </td>
                    <td class="px-3 py-4">
                        <?php echo $row['usuario']; ?>
                    </td>
                    <td class="px-3 py-4">
                        <?php echo $row['tercero']; ?>
                    </td>
                   
                    <td class="px-3 py-4">
                        <?php echo $row['valor_total']; ?>
                    </td>
                    <td class="px-3 py-4">
                        <?php echo $row['fecha']; ?>
                    </td>
                   
                  
                </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>

</div>