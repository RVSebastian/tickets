<?php
include '../../db/cn.php';
session_start();
$query = "SELECT * FROM USUARIOS ORDER BY rol DESC";
$result_task = mysqli_query($conn, $query);
?>

<script>
$("#buscador").on("input", function() {
    var valorFiltro = $(this).val().toLowerCase();

    $("#tabla tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(valorFiltro) > -1);
    });
});

$('#add').click(function() {
    $('#view-1').hide();
    $('#view-2').fadeIn('slow');
});

$('#form').submit(function() {
    var formData = $(this).serialize() + "&action=create";
    $.ajax({
        type: "POST",
        url: "./components/moduls/usuarios/funciones",
        data: formData,
        success: function(response) {
            if (response == 'Registro Creado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                $('#view-1').fadeIn('slow');
                list();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response,
                });
            }

        }
    });
    return false;
});
$('.form-edit').submit(function() {
    var formData = $(this).serialize() + "&action=update";
    $.ajax({
        type: "POST",
        url: "./components/moduls/usuarios/funciones",
        data: formData,
        success: function(response) {
            if (response == 'Registro Actualizado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                list();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response,
                });
            }

        }
    });
    return false;
});
$('.eliminar').click(function() {
    var id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "./components/moduls/usuarios/funciones",
        data: {
            id: id,
            action: 'delete'
        },
        success: function(response) {
            if (response == 'Registro Eliminado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                list();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response,
                });
            }

        }
    });
});
$('.editar').click(function() {
    var id = $(this).attr('data-id');
    $('#form-' + id).fadeIn('slow');
    $('#view-1').hide();
    $('#view-2').hide();
});
</script>

<div id="usuarios" class="hidden">
    <div class="basis-12/12 py-5" id="view-1">
        <div class="max-w-6xl mx-auto bg-white p-2 rounded shadow mb-4">
            <div class="py-2 px-2">
                <div class="flex flex-row">
                    <div class="basis-11/12">
                        <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="buscador"
                                class="block w-full p-2 ps-10 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 border-0"
                                placeholder="Buscar por Rol, Nombre, etc" required>
                        </div>
                    </div>
                    <div class="basis-1/12 px-4">
                        <i id="add" class='bx bxs-user-plus bg-gray-900 text-white px-3 py-1 rounded text-lg'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto bg-white p-5 rounded shadow">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-900" id="tabla">
                    <tbody>
                        <?php
                    foreach ($result_task as $row) {
                    ?>
                        <tr class="bg-white border-b hover:bg-gray-100">
                            <td class="px-0 py-4">
                                <i class='bx bx-user 
                            <?php
                            if ($row['rol'] == 'Jefe de Unidad') {
                               echo 'bg-slate-500';
                            }
                            if ($row['rol'] == 'Jefe de Servicios') {
                                echo 'bg-slate-400';
                             }
                             
                            ?>
                            bg-gray-300 text-black rounded p-2'></i>
                            </td>
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <?php echo $row['usuario'];  ?>
                            </th>
                            <td class="px-4 py-4">
                                <?php echo $row['nombre'];  ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php echo $row['rol'];  ?>
                            </td>
                            <td class="px-2 py-4 text-center">
                                <i data-id="<?php echo $row['id'];?>"
                                    class='editar bx bx-edit-alt bg-slate-800 text-white rounded p-2'></i>
                            </td>
                            <td class="px-2 py-4 text-center">
                                <i data-id="<?php echo $row['id'];?>"
                                    class='eliminar bx bx-trash-alt bg-red-600 text-white rounded p-2'></i>
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

    <?php
foreach ($result_task as $row) {
?>
    <div class="basis-12/12 py-6 hidden form-update" id="form-<?php echo $row['id']; ?>">
        <form class="max-w-md mx-auto bg-white p-6 rounded shadow-sm form-edit">
            <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
            <div class="mb-5 pt-3">
                <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900">Usuario</label>
                <input type="text" id="1" name="1"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    value="<?php echo $row['usuario']; ?>" required>
            </div>
            <div class="mb-5">
                <label for="contraseña" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                <input type="text" id="2" name="2"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    value="<?php echo $row['nombre']; ?>" required>
            </div>
            <div class="mb-5">
                <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                <select name="3" id="3"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5">
                    <option value="<?php echo $row['rol']; ?>"><?php echo $row['rol']; ?></option>
                    <option value="Jefe de Unidad">Jefe de Unidad</option>
                    <option value="Jefe de Servicios">Jefe de Servicios</option>
                    <option value="Empleado de Servicios">Empleado de Servicios</option>
                    <option value="Empleado">Empleado</option>
                </select>
            </div>
            <div class="mb-5">
                <label for="contraseña" class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                <input type="text" id="4" name="4"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    value="<?php echo $row['contraseña']; ?>" required>
            </div>
            <button type="submit"
                class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Actualizar
                Usuario</button>
        </form>
    </div>

    <?php
}
?>
    <div class="basis-12/12 py-6 hidden" id="view-2">
        <form class="max-w-md mx-auto bg-white p-6 rounded shadow-sm" id="form">
            <div class="mb-5 pt-3">
                <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900">Usuario</label>
                <input type="text" id="1" name="1"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="VARGAZS" required>
            </div>
            <div class="mb-5">
                <label for="contraseña" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                <input type="text" id="2" name="2"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="Sebastian Vargaz" required>
            </div>
            <div class="mb-5">
                <label for="usuario" class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
                <select name="3" id="3"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5">
                    <option value="Jefe de Unidad">Jefe de Unidad</option>
                    <option value="Jefe de Servicios">Jefe de Servicios</option>
                    <option value="Empleado de Servicios">Empleado de Servicios</option>
                    <option value="Empleado">Empleado</option>
                </select>
            </div>
            <div class="mb-5">
                <label for="contraseña" class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
                <input type="text" id="4" name="4"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="***" required>
            </div>
            <button type="submit"
                class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Crear
                Usuario</button>
        </form>
    </div>
</div>