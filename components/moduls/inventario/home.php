<?php
include '../../db/cn.php';
session_start();
$empresa = $_SESSION['key']['empresa'];
if ($empresa == 'ADMINISTRADOR') {
    $query = "select * from inventario";
}else{
    $query = "select * from inventario where empresa='$empresa'";
}

$result_task = mysqli_query($conn, $query);
$row_count = mysqli_num_rows($result_task);
?>

<script>
$("#buscador").on("input", function() {
    var valorFiltro = $(this).val().toLowerCase();

    $("table tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(valorFiltro) > -1);
    });
});

$('.editar').click(function() {
    var id = $(this).data("id");
    $('#table').hide();
    $('#response').hide();
    $.ajax({
        type: "POST",
        url: "./components/moduls/inventario/article.php",
        data: {
            id: id
        },
        success: function(response) {
            $('#response').html(response);
            $('#response').show();
        }
    });
});



</script>

<div id="response"></div>
<div id="table">
    <div class="w-fill mx-auto bg-white rounded p-2 rounded mt-6">
        <div class="py-2 px-2">
            <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="buscador"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 border-0"
                    placeholder="Buscar por Codigo, Nombre, Valor" required>
            </div>
        </div>
    </div>
    <?php 
    if ($row_count > 1) {
    ?>
    <div class="relative overflow-x-auto py-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <tbody>
                <?php
               foreach ($result_task as $row) {
                ?>
                <tr class="editar bg-white border-b hover:bg-gray-100 hover:cursor-pointer	" data-id="<?php echo $row['id'];?>">
                    <td class="px-4 py-4 text-center">
                        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo $row['parte']; ?>&code=Code128&translate-esc=on"
                            class="w-auto h-16 my-3 text-center" id="codigo_barra" alt="Código de Barras">
                    </td>
                    <td scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        <?php echo $row['parte']; ?>
                    </td>
                    <td class="px-2 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        <?php echo strtoupper($row['descripcion']); ?>
                    </td>

                    <td class="px-6 py-4">
                        <?php echo $row['marca']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo $row['tipo']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo $row['existencia']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php echo $row['porcentaje_iva']; ?>%
                    </td>
                    <td class="px-6 py-4">
                        <?php echo $row['precio']; ?>
                    </td>
                </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    <?php 
    }else{
    ?>
    <div class="p-5 bg-white mt-5">No se Encontro ningun Registro</div>
    <?php
    }
    ?>
</div>