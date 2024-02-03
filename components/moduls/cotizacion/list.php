<?php
include '../../db/cn.php';
session_start();

$empresa = $_SESSION['key']['empresa'];
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $_SESSION['coti'] = $_POST['id'];
}else{
    $id = $_POST['id'] = $_SESSION['coti'];
}
    $query = "select dt.*,dt.id as id_detall,iv.*,dt.descuento as descuento_linea,dt.iva as iva_linea,ev.tercero as t_nit,tc.nombres as t_nombres,tc.apellidos as t_apellidos,tc.email as t_correos,ev.estado as enc_estado
    from detall_coti as dt 
    LEFT OUTER JOIN inventario as iv on iv.parte = dt.codigo 
    LEFT OUTER JOIN encabeza_coti as ev on ev.id = dt.id_coti
    LEFT OUTER JOIN terceros as tc on tc.nit = ev.tercero
    where id_coti='$id'
    and dt.estado in(1,0)
    ";
    
    $result_task = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result_task);
    $row_count = mysqli_num_rows($result_task);
?>
<script>
$('.linead').click(function() {
    // Obtener los datos de la fila actual
    var idRepuesto = $(this).closest('tr').find('td:eq(1)').text().trim();
    var nombreRepuesto = $(this).closest('tr').find('td:eq(2)').text();
    var cantidad = limpiarTexto($(this).closest('tr').find('td:eq(6)').text());
    var precio = limpiarTexto($(this).closest('tr').find('td:eq(9)').text());
    var descuento = limpiarTexto($(this).closest('tr').find('td:eq(8)').text());
    var iva = limpiarTexto($(this).closest('tr').find('td:eq(7)').text());
    // Crear el formulario dentro del modal
    Swal.fire({
        title: 'Modificar Producto',
        html: `<div class="bg-white p-4 rounded-md shadow-md text-left">
                            <div class="mb-4 flex">
                                <label for="swal-cantidad" class="block text-sm font-medium text-gray-700 w-1/3 pr-4">Cantidad:</label>
                                <input id="swal-cantidad" class="form-input flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" value="${cantidad}">
                            </div>
                            <div class="mb-4 flex">
                                <label for="swal-precio" class="block text-sm font-medium text-gray-700 w-1/3 pr-4">Precio:</label>
                                <input id="swal-precio" class="form-input flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" value="${precio}">
                            </div>
                            <div class="mb-4 flex">
                                <label for="swal-descuento" class="block text-sm font-medium text-gray-700 w-1/3 pr-4">Descuento (%):</label>
                                <input id="swal-descuento" class="form-input flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" value="${descuento}">
                            </div>
                            <div class="flex">
                                <label for="swal-iva" class="block text-sm font-medium text-gray-700 w-1/3 pr-4">IVA (%):</label>
                                <input id="swal-iva" class="form-input flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" value="${iva}">
                            </div>
                        </div>`,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            // Obtener los valores de los inputs
            var nuevaCantidad = Swal.getPopup().querySelector('#swal-cantidad').value;
            var nuevoPrecio = Swal.getPopup().querySelector('#swal-precio').value;
            var nuevoDescuento = Swal.getPopup().querySelector('#swal-descuento').value;
            var nuevoIva = Swal.getPopup().querySelector('#swal-iva').value;

            $.ajax({
                type: "POST",
                url: "./components/moduls/cotizacion/functions.php",
                data: {
                    idrepuesto: idRepuesto,
                    nuevaCantidad: nuevaCantidad,
                    nuevoPrecio: nuevoPrecio,
                    nuevoDescuento: nuevoDescuento,
                    nuevoIva: nuevoIva,
                    status: "update_prices_uni"
                },
                success: function(response) {
                    Swal.fire('¡Datos actualizados!', '', 'success');
                    console.log(response);
                    cargarCoti(response);

                },
                error: function(error) {
                    // Manejar errores, si es necesario
                    console.error('Error en la solicitud AJAX', error);
                }
            });
        }
    });

    function limpiarTexto(texto) {
        // Eliminar caracteres especiales
        return texto.replace(/[$,%,.]/g, '').trim();
    }
});

$('#add-modal').click(function() {
    var documentValue = $('#input_nombre').val().trim();
    if (documentValue.length === 0) {
        Swal.fire({
            title: 'Confirmación',
            text: 'No se encontro un tercero registrado, por favor añadelo antes de cotizar.',
            icon: 'warning',
        })
    } else {
        $.ajax({
            type: "POST",
            url: "./components/moduls/cotizacion/add.php",
            success: function(response) {
                $('#modal-add').html(response);
            }
        });
    }

});

$('.enc_fac').click(function() {
    var id = $(this).data("id");
    Swal.fire({
        title: 'Confirmación',
        text: '¿Estás seguro de querer realizar esta acción?, no podras modificar nada despues',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar'
    }).then((result) => {
        if (result.isConfirmed) {
            if ($('#total_autorizado').val() > 0) {
                $.ajax({
                    type: "POST",
                    data: {
                        id: id,
                        status: 'enc_fac',
                    },
                    url: "./components/moduls/cotizacion/functions.php",
                    success: function(response) {
                        cargarCoti(response);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Confirmación',
                    text: 'No se encontraron articulos autorizados, por favor autorizarlos antes de cotizar.',
                    icon: 'warning',
                })
            }
        }
    });
});
$('.enc_del').click(function() {
    var id = $(this).data("id");
    Swal.fire({
        title: 'Confirmación',
        text: '¿Estás seguro de querer realizar esta acción?, no podras modificar nada despues',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    status: 'enc_del',
                },
                url: "./components/moduls/cotizacion/functions.php",
                success: function(response) {
                    cargarCoti(response);
                }
            });
        }
    });
});

$('.enc_reop').click(function() {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            id: id,
            status: 'enc_reop',
        },
        url: "./components/moduls/cotizacion/functions.php",
        success: function(response) {
            cargarCoti(response);
        }
    });
});

$('.autorizated_item').click(function() {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            id: id,
            status: 'autorizate_item',
        },
        url: "./components/moduls/cotizacion/functions.php",
        success: function(response) {
            cargarCoti(response);
        }
    });
});
$('.desautori_item').click(function() {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            id: id,
            status: 'desautori_item',
        },
        url: "./components/moduls/cotizacion/functions.php",
        success: function(response) {
            cargarCoti(response);
        }
    });
});
$('.delete_item').click(function() {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            id: id,
            status: 'delete_item',
        },
        url: "./components/moduls/cotizacion/functions.php",
        success: function(response) {
            cargarCoti(response);
        }
    });
});
$('#input_document').on('change', function() {
    var id = $(this).val();
    $.ajax({
        type: "POST",
        data: {
            id: id,
            status: 'search_id',
        },
        url: "./components/moduls/cotizacion/functions.php",
        success: function(response) {
            try {
                // Parse the JSON response into a JavaScript object
                var data = JSON.parse(response);

                // Assuming 'desired_key' is the data you want to insert into the input

                console.log(data);
                // Insert the value into the input field
                $('#input_nombre').val(data.nombres);
                $('#input_correo').val(data.email);
                $('#buton-añadir').show();

            } catch (error) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Inserte un numero de documento valido',
                });
            }
        }
    });
    if ($('#input_document').val().trim() !== "") {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            data: {
                id: id,
                status: 'search_id',
            },
            url: "./components/moduls/cotizacion/functions.php",
            success: function(response) {
                try {
                    // Parse the JSON response into a JavaScript object
                    var data = JSON.parse(response);

                    // Assuming 'desired_key' is the data you want to insert into the input

                    console.log(data);
                    // Insert the value into the input field
                    $('#input_nombre').val(data.nombres);
                    $('#input_correo').val(data.email);
                    $('#buton-añadir').show();

                } catch (error) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Inserte un numero de documento valido',
                    });
                }
            }
        });

    }
});

$('#back-coti').click(function() {
    cotizaciones();
});
</script>
<div id="modal-add"></div>
<div id="coti">
    <div class="bg-white mx-5 p-5 mt-6">
        <p id="back-coti" class="py-3 font-semibold hover:text-gray-600"><i class='bx bx-chevron-left'></i> Regresar al
            listado</p>
        <div class="px-3 py-2 mt-2">
            <div class="grid gap-8 mb-2 md:grid-cols-3 ">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-900 ">Nit / Numero de
                        Documento</label>
                    <input type="text" id="input_document" <?php if($id != 0){echo 'readonly';} ?>
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="1096538459" value="<?php if(isset($data['t_nit'])){echo $data['t_nit'];}  ?>"
                        required>
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-900 ">Nombre del
                        tercero</label>
                    <input type="text" id="input_nombre" readonly
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder=""
                        value="<?php if(isset($data['t_nombres'])){echo $data['t_nombres'].' '.$data['t_apellidos'];}  ?>"
                        required>
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-900 ">Email</label>
                    <input type="text" id="input_correo" readonly
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" value="<?php if(isset($data['t_correos'])){echo $data['t_correos'];}  ?>"
                        required>
                </div>
            </div>
            <div class="grid gap-8 mb-2 md:grid-cols-2 ">
                <div class="pt-2">
                    <p><span class="font-semibold pt-3">Vendedor:</span>
                        <?php  if(isset($data['usuario'])){echo $data['usuario'];}else{echo $_SESSION['key']['usuario'];} ?>
                    </p>
                </div>
                <div class="text-right pt-2">
                    <i data-id="<?php echo $id; ?>"
                        class='enc_fac bx bxs-file-plus bg-green-600 text-white rounded p-2 hover:bg-slate-500 mr-4 <?php if($data['enc_estado'] != 1){echo 'hidden';} ?>'></i>
                    <i data-id="<?php echo $id; ?>"
                        class='enc_del bx bxs-lock bg-red-700 text-white rounded p-2 hover:bg-slate-500 <?php if($data['enc_estado'] != 1){echo 'hidden';} ?>'></i>
                    <i data-id="<?php echo $id; ?>"
                        class='enc_reop bx bx-lock-open-alt bg-purple-700 text-white rounded p-2 hover:bg-slate-500 <?php if($data['enc_estado'] != 3){echo 'hidden';} ?>'></i>
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto py-5 mx-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <?php
    if ($row_count > 0) {
    ?>
            <tbody>
                <?php
                $total_descuento = 0;
                $total_iva = 0;
                $total = 0;
                $total_t = 0;

                $total_descuento_autorizado = 0;
                $total_iva_autorizado = 0;
                $total_autorizado = 0;
                $total_t_autorizado = 0;
                $items_autorizados = 0;

               foreach ($result_task as $row) {
                $unidad = $row['precio'] * $row['cantidad'];
                $descuento = round($unidad*($row['descuento_linea']/100));
                $bruto = round($unidad-$descuento); 
                $iva = round($bruto * $row['iva_linea']/100);
                $total = $bruto+$iva;

                $total_descuento += $descuento;
                $total_iva += $iva;
                $total_t += $total;
                if ($row['estado'] == 1) {
                    $items_autorizados+=$row['cantidad'];
                    $total_descuento_autorizado += $descuento;
                    $total_iva_autorizado += $iva;
                    $total_autorizado += $total;
                    $total_t_autorizado += $total;
                }
                ?>
                <tr 
                    class="bg-white rounded border-b hover:bg-gray-100 <?php if ($data['enc_estado'] == 2 and $row['estado'] != 1) {echo 'hidden';} ?>">
                    <td class="px-4 py-1 linead">
                        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo $row['codigo']; ?>&code=Code128&translate-esc=on"
                            class="w-auto h-16 my-3" id="codigo_barra" alt="Código de Barras">
                    </td>
                    <td class="px-2 py-1 font-medium text-gray-900 whitespace-nowrap linead">
                        <?php echo $row['codigo']; ?>
                    </td>
                    <td class="px-2 py-1 font-medium text-gray-900 whitespace-nowrap linead ">
                        <?php echo strtoupper($row['descripcion']); ?>
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['sucursal']; ?>
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['marca']; ?>
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['tipo']; ?>
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['cantidad']; ?>
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['iva_linea']; ?>%
                    </td>
                    <td class="px-2 py-1 linead">
                        <?php echo $row['descuento']; ?>%
                    </td>
                    <td class="px-1 py-1 linead">
                        <?php echo '$' . number_format($row['precio'], 0, '.', ','); ?>
                    </td>
                    <td class="px-1 py-1 text-gray-900 linead">
                        <?php echo '$' . number_format($descuento, 0, '.', ','); ?>
                    </td>
                    <td class="px-1 py-1 text-gray-900 linead">
                        <?php echo '$' . number_format($iva, 0, '.', ','); ?>
                    </td>
                    <td class="px-1 py-1 text-gray-900 linead">
                        <?php  echo '$' . number_format($total, 0, '.', ','); ?>
                    </td>

                    <td class="px-0 py-1 text-center">
                        <i data-id="<?php echo $row['id_detall'];?>"
                            class='autorizated_item bx bx-check bg-green-600 text-white rounded p-2 hover:bg-slate-500 mr-4 <?php if($row['estado'] == 1 OR $row['enc_estado'] != 1){echo 'hidden';} ?>'></i>
                        <i data-id="<?php echo $row['id_detall'];?>"
                            class='delete_item bx bx-x bg-red-600 text-white rounded p-2 hover:bg-slate-500 <?php if($row['estado'] == 1 OR $row['enc_estado'] != 1){echo 'hidden';} ?>'></i>
                        <i data-id="<?php echo $row['id_detall'];?>"
                            class='desautori_item bx bx-reset bg-yellow-400 text-white rounded p-2 hover:bg-slate-500 <?php if($row['estado'] == 1){echo ''; }else{echo 'hidden';} ?>'></i>
                    </td>
                </tr>

                <?php
                }
                ?>
                <?php
                $items = 0;
               
               foreach ($result_task as $row) {
                $items += $row['cantidad'];
               }
                ?>
                <!--- CAMPO DE LOS TOTALES -->
                <tr class="bg-gray-50 border rounded <?php if ($data['enc_estado'] == 2) {echo 'hidden';} ?>">
                    <td class="px-4 py-3">
                        Total lineas:
                    </td>
                    <td class="px-4 py-3">
                    </td>
                    <td class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap ">
                    </td>
                    <td class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap ">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Items: <?php echo $items; ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Descuento: <?php  echo '$' . number_format($total_descuento, 0, '.', ','); ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Iva: <?php echo '$' . number_format($total_iva, 0, '.', ','); ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Total: <?php echo '$' . number_format($total_t, 0, '.', ',');  ?>
                    </td>

                </tr>

                <tr class="bg-gray-50 border rounded ">
                    <td class="px-4 py-3">
                        Total Autorizado:
                    </td>
                    <td class="px-4 py-3">
                    </td>
                    <td class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap ">
                    </td>
                    <td class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap ">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-1">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-3">
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Items: <?php echo $items_autorizados; ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Descuento: <?php  echo '$' . number_format($total_descuento_autorizado, 0, '.', ','); ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Iva: <?php echo '$' . number_format($total_iva_autorizado, 0, '.', ','); ?>
                    </td>
                    <td class="px-2 py-3 border text-gray-900">
                        Total: <?php echo '$' . number_format($total_t_autorizado, 0, '.', ',');  ?>
                    </td>
                    <input type="hidden" id="total_autorizado" value="<?php echo $total_t_autorizado; ?>">

                </tr>
            </tbody>
            <?php
     }
    ?>
        </table>
        <div id="buton-añadir"
            class="grid justify-items-end mt-4 <?php if(isset($row['enc_estado']) AND $row['enc_estado'] != 1){echo 'hidden';} ?>">
            <button type="button" id="add-modal" class="p-2.5 bg-green-600 hover:bg-green-500 rounded text-white"><i
                    class='bx bx-plus'></i></button>
        </div>
    </div>

</div>