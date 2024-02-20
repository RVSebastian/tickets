<?php
include '../../db/cn.php';
session_start();
$empresa = $_SESSION['key']['empresa'];
if ($empresa == 'ADMINISTRADOR') {
    $query = "select * from inventario order by descripcion asc";
}else{
    $query = "select * from inventario where empresa='$empresa' order by descripcion asc";
}

$result_task = mysqli_query($conn, $query);
$row_count = mysqli_num_rows($result_task);
?>
<script>
$(document).on('keydown', function(e) {
    // Verifica si la tecla presionada es Escape (código 27)
    if (e.which === 27) {
        // Oculta el elemento con el id coti_search
        $('#coti_search').hide();
    }
});
$('.close_modal').click(function() {
    $('#coti_search').hide();
});
$('.search_click').click(function() {
    var id = $(this).data("id");
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

$('.add_click').click(function() {
    if ($('#input_nombre').val().trim() == "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Tercero no existente, por favor añadirlo desde el modulo de terceros o volver a consultar en Nit/documento',

        });
    } else {
        var id = $(this).data("id");
        var descripcion = $(this).data("descripcion");
        var stock = $(this).data("existencia");
        var precio = $(this).data("precio");
        var nit_tercero = $('#input_document').val();
        var descuento = $(this).data("descuento");
        var iva = $(this).data("iva");
        Swal.fire({
            title: "Inserta la cantidad del item",
            text: descripcion + ' / COD: ' + id,
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Añadir"
        }).then((result) => {
            if (result.isConfirmed) {
                const cantidad = result.value;
                if (!isNaN(cantidad)) {
                    if (cantidad > stock) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Stock insuficiente en el inventario'
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "./components/moduls/cotizacion/functions.php",
                            data: {
                                id,
                                cantidad,
                                stock,
                                precio,
                                descuento,
                                iva,
                                nit_tercero,
                                status: 'insert',
                            },
                            success: function(response) {
                                // Manejar la respuesta de la petición AJAX si es necesario
                                if (response ==
                                    'Error: Ya existe ese articulo en la cotizacion.') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error: Ya existe ese articulo en la cotizacion.'
                                    });
                                } else {
                                    cargarCoti(response);
                                }

                            }
                        });
                    }
                } else {
                    // La cantidad no es un número, puedes manejar esto como desees
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, ingresa una cantidad válida (número).'
                    });
                }
            }
        });
    }
});



$("#buscador2").on("input", function() {
    var valorFiltro = $(this).val().toLowerCase();

    // Contador para rastrear el número de filas visibles
    var filasVisibles = 0;

    $("#table_coti tbody tr").filter(function(index) {
        // Mostrar solo las primeras 10 filas que coincidan con la búsqueda
        var mostrar = filasVisibles < 20 && $(this).text().toLowerCase().indexOf(valorFiltro) > -1;

        // Actualizar el contador solo si la fila se muestra
        if (mostrar) {
            filasVisibles++;
        }

        $(this).toggle(mostrar);
    });
});
</script>
<style>
.table-container {
    height: 650px;
    width: 900px;
    /* Ajusta la altura según tus necesidades */
    overflow-x: hidden;
    overflow-y: auto;
    /* Se oculta el desplazamiento vertical */
}

.table-container table {
    min-width: 100%;
    /* Asegura que la tabla ocupe al menos el ancho del contenedor */
}

.table-container::-webkit-scrollbar {
    height: 4px;
    width: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 5px;
}

.table-container::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}
</style>

<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 overflow-x-hidden z-50 h-sm"
    id="coti_search">
    <!-- Fondo oscuro para modal -->
    <div class="bg-white p-4 mx-6 w-7xl h-lg rounded-lg shadow-xl overflow-y-auto">
        <!-- Contenido del modal -->
        <div class="w-full  mb-2">
            <div class="py-2 px-2">
                <div class="flex">
                    <div class="flex-1">
                        <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="buscador2"
                                class="block w-full p-3 ps-10 bg-gray-100 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 border-0"
                                placeholder="Buscar por Codigo, Nombre, Valor" required>
                        </div>
                    </div>
                    <div class="flex-2">
                        <p class='close_modal p-2 mx-4 my-2 rounded bg-slate-900 text-white'><i class='bx bx-x'></i></p>
                    </div>
                </div>

            </div>
        </div>
        <div class="relative overflow-x-auto pt-2 pb-5 mx-2">
            <div class="table-container bg-slate-50">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="table_coti">
                    <tbody>
                        <?php
                     $counter = 0;
               foreach ($result_task as $row) {
                $counter++;
                ?>
                        <tr class="rounded border-b hover:bg-gray-100">

                            <td class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap ">
                                <?php echo $row['parte']; ?>
                            </td>
                            <td class="px-2 py-1 font-medium text-gray-900 whitespace-nowrap ">
                                <?php echo strtoupper($row['descripcion']); ?>
                            </td>

                            <td class="px-2 py-1">
                                <?php echo $row['marca']; ?>
                            </td>
                            <td class="px-2 py-1">
                                <?php echo $row['tipo']; ?>
                            </td>
                            <td class="px-2 py-1">
                                <?php echo $row['existencia']; ?>
                            </td>
                            <td class="px-2 py-1">
                                <?php echo $row['porcentaje_iva']; ?>%
                            </td>
                            <td class="px-2 py-1">
                                <?php echo $row['porcentaje_descuento']; ?>%
                            </td>
                            <td class="px-2 py-1">
                                <?php echo $row['precio']; ?>
                            </td>
                            <!--
                            <td class="px-2 py-1 text-center">
                                <i data-id="<?php echo $row['parte'];?>"
                                    class='search_click bx bx-search-alt-2 bg-slate-900 text-white rounded p-2 hover:bg-slate-500'></i>
                            </td>
                             -->
                            <td class="px-2 py-1 text-center">
                                <i data-id="<?php echo $row['parte'];?>"
                                    data-descripcion="<?php echo $row['descripcion'];?>"
                                    data-precio="<?php echo $row['precio'];?>"
                                    data-existencia="<?php echo $row['existencia'];?>"
                                    data-descuento="<?php echo $row['porcentaje_descuento'];?>"
                                    data-iva="<?php echo $row['porcentaje_iva'];?>"
                                    class='add_click bx bx-cube-alt bg-green-600 text-white rounded p-2 hover:bg-slate-500'></i>
                            </td>
                        </tr>
                        <?php
                }
                ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>