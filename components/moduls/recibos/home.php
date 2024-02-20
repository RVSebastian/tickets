<script>
$('#add-modal').click(function() {
    var documentValue = $('#nit').val().trim();
    if (documentValue.length === 0) {
        Swal.fire({
            title: 'Confirmación',
            text: 'No se encontro un tercero registrado, por favor añadelo antes de cotizar.',
            icon: 'warning',
        })
    } else {
        $.ajax({
            type: "POST",
            url: "./components/moduls/recibos/add.php",
            success: function(response) {
                $('#modal-add').html(response);
            }
        });
    }

});

$('#nit').change(function() {
    var nit = $(this).val();
    $.ajax({
        type: 'POST',
        url: "./components/moduls/terceros/controls.php",
        data: {
            action: 'search',
            nit: nit,
        }, // Concatenate the action parameter
        success: function(response) {
            if (response == null) {
                $('#nombres').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tercero no registrado'
                });
                $('.items').slideUp('slow');
            } else {
                $('#nombres').val(response.nombres + ' ' + response.apellidos);
                $('.items').slideDown('slow');
            }

        },
        error: function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tercero no registrado'
            });
        }
    });
});

$('#codigo').change(function() {
    search_rep();
});

// Array para almacenar los datos
var dataArray = [];

function updateDataTable() {
    // Limpiar la tabla antes de actualizar
    $("#dataTable tbody").empty();

    // Recorrer los datos en dataArray y agregar filas a la tabla
    dataArray.forEach(function(data) {
        var total = (data.precio * data.cantidad);
        var row = "<tr><td class='py-4 px-4'>" + data.codigo + "</td><td class='py-2 px-4'>" + data.des +
            "</td><td class='py-2 px-4'>" + data
            .precio + "</td><td class='py-2 px-4'>" +
            data.cantidad + "</td>" + "</td><td class='py-2 px-4'>" + total + "</td></tr>";
        // Agrega otras celdas según tus necesidades

        $("#dataTable tbody").append(row);
    });
}

function search_rep() {
    var codigo = $('#codigo').val();
    $.ajax({
        type: 'POST',
        url: "./components/moduls/terceros/controls.php",
        data: {
            action: 'search_rep',
            codigo: codigo,
        }, // Concatenate the action parameter
        success: function(response) {
            if (response == null) {
                $('#nombres').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Código no registrado',
                    showCancelButton: true,
                    confirmButtonText: 'Añadir Artículo',
                    cancelButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigir a otra página en otra pestaña
                        window.open('./añadir', '_blank');
                    }
                });

                $("#codigo, #des, #precio, #cantidad").val('');

            } else {
                $('#des').val(response.descripcion);
                $('#precio').val(response.costo_unitario);
            }

        },
        error: function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Codigo no registrado'
            });
        }
    });
}
// Función para agregar datos al array
function agregarDatos() {
    var nuevoDato = {
        codigo: $("#codigo").val(),
        des: $("#des").val(),
        precio: $("#precio").val(),
        cantidad: $("#cantidad").val(),
    };
    if (!nuevoDato.codigo || !nuevoDato.des || !nuevoDato.precio || !nuevoDato.cantidad) {
        // Mostrar SweetAlert con el mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son obligatorios'
        });
    } else {
        $("#codigo, #des, #precio, #cantidad, #descuento, #iva").val('');
        dataArray.push(nuevoDato);
        console.log(dataArray);
        $('.items-2').slideDown('slow');
        updateDataTable();
    }
}

// Evento click del botón flotante
$("#btnAdd").on("click", function() {
    // Llama a la función para agregar datos
    agregarDatos();
});
</script>
<div id="modal-add"></div>
<div id="terceros">
    <div class="bg-white mx-5 p-5 mt-6 rounded">
        <p class="border-b-2 border-slate-900 text-xl text-slate-700 font-semibold px-4 py-2 mb-6">Ingreso de mercancia
        </p>
        <div class="px-3 py-4 mt-2">
            <div class="grid gap-5 mb-4 md:grid-cols-2 ">
                <div>
                    <label for="nit" class="block mb-1 text-sm font-medium text-gray-900 ">Nit Provedor</label>
                    <input type="text" id="nit"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" required>
                </div>
                <div>
                    <label for="nombres" class="block mb-1 text-sm font-medium text-gray-900 ">Nombres</label>
                    <input type="text" id="nombres" readonly
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" required>
                </div>
            </div>
            <div class="items hidden">
                <div class="bg-yellow-100 my-8 p-4 grid gap-5 mb-2 md:grid-cols-4">
                    <div>
                        <label for="codigo" class="block mb-1 text-sm font-medium text-gray-900 ">Codigo</label>
                        <input type="text" id="codigo"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="" required>
                        <i id="add-modal" class="bx bx-search bg-gray-800 facturado my-2 text-white rounded p-2"></i>
                    </div>
                    <div>
                        <label for="des" class="block mb-1 text-sm font-medium text-gray-900 ">Descripcion</label>
                        <input type="text" id="des"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="" required>
                    </div>
                    <div>
                        <label for="precio" class="block mb-1 text-sm font-medium text-gray-900 ">Precio
                            Unitario</label>
                        <input type="text" id="precio"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="" required>
                    </div>
                    <div>
                        <label for="cantidad" class="block mb-1 text-sm font-medium text-gray-900 ">Cantidad Entrante
                            (Unidad)</label>
                        <input type="text" id="cantidad"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="" required>
                    </div>

                </div>
                <button type="button" id="btnAdd"
                    class="my-2 text-white bg-gray-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm w-full sm:w-auto px-5 py-2.5 text-center">Añadir</button>
            </div>
            <div class="relative overflow-x-auto items-2 hidden">
                <table id="dataTable"
                    class="mt-8 p-4 bg-gray-100 w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-sm text-gray-700 uppercase p-4">
                        <tr class="p-4">
                            <th class="py-3 px-2">Código</th>
                            <th class="py-3 px-2">Descripción</th>
                            <th class="py-3 px-2">Precio U</th>
                            <th class="py-3 px-2">Cantidad</th>
                            <th class="py-3 px-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <button type="submit" id="gen-doc"
                class="items-2 hidden my-2 text-white bg-gray-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm w-full sm:w-auto px-5 py-2.5 text-center">Generar
                documento</button>
        </div>

    </div>
    <div class="items hidden">

    </div>
</div>