<?php
include '../../db/cn.php';
session_start();

$usuario = $_SESSION['key']['usuario'];
$rol = $_SESSION['key']['rol'];

if ($rol == 'Empleado') {
    $query = "SELECT * FROM solicitudes WHERE usuario='$usuario' ORDER BY id desc";
}elseif ($rol == 'Jefe de Unidad' ) {
    $query = "SELECT * FROM solicitudes WHERE estado = 'Pendiente' OR usuario='$usuario' ORDER BY id desc";
}elseif ($rol == 'Jefe de Servicios' ) {
    $query = "SELECT * FROM solicitudes WHERE estado != 'Cancelado' OR usuario='$usuario' ORDER BY id desc";
}elseif ($rol == 'Empleado de Servicios'){
    $query = "SELECT * FROM solicitudes WHERE estado = 'Aprobado' OR asignado = '$usuario' ORDER BY id desc";
}
else{
    $query = "SELECT * FROM solicitudes WHERE estado != 'Cancelado' ORDER BY id desc";
}
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
        url: "./components/moduls/tickets/funciones",
        data: formData,
        success: function(response) {
            if (response == 'Registro Creado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                $('#view-1').fadeIn('slow');
                solicitudes();
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
        url: "./components/moduls/tickets/funciones",
        data: formData,
        success: function(response) {
            if (response == 'Registro Actualizado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                solicitudes();
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
$('.form-coments').submit(function() {
    var formData = $(this).serialize() + "&action=create";
    $.ajax({
        type: "POST",
        url: "./components/moduls/tickets/comentarios",
        data: formData,
        success: function(response) {
            if (response == 'Comentario Creado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                var id = formData.split("&").find(pair => pair.startsWith("1="))?.split("=")[1];
                solicitudes(c = 'coment', id = id);
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
$('.cancelar').click(function() {
    var id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "./components/moduls/tickets/funciones",
        data: {
            id: id,
            action: 'cancelar'
        },
        success: function(response) {
            if (response == 'Registro Actualizado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                solicitudes();
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

$('.eliminar').click(function() {
    var id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: "./components/moduls/tickets/funciones",
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
                solicitudes();
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

$('.estatus').click(function() {
    var id = $(this).attr('data-id');
    var action = $(this).attr('data-action');
    $.ajax({
        type: "POST",
        url: "./components/moduls/tickets/funciones",
        data: {
            id: id,
            action: action
        },
        success: function(response) {
            if (response == 'Registro Actualizado') {
                Swal.fire({
                    icon: "success",
                    title: "Ok",
                    text: response,
                });
                solicitudes();
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
<div id="tickets">
    <div class="basis-12/12 py-5" id="view-1">
        <div class="max-w-7xl mx-auto bg-white p-2 rounded shadow mb-4">
            <div class="py-2 px-2">
                <div class="flex flex-row">
                    <div class="basis-11/12 pr-8">
                        <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="buscador"
                                class="block w-full p-3 ps-10 bg-gray-50 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 border-0"
                                placeholder="Buscar por Rol, Nombre, etc" required>
                        </div>
                    </div>
                    <div class="basis-1/12 pl-8 <?php if ($rol != 'Empleado' and $rol !='Jefe de Servicios') {
                   echo 'hidden';
                } ?>">
                        <i id="add" class='bx bx-mail-send bg-gray-900 text-white px-3 py-1 rounded text-lg'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto bg-white p-5 rounded shadow">
            <?php if (mysqli_num_rows($result_task) > 0) 
    {
    ?>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-900" id="tabla">
                    <tbody>
                        <?php
                    foreach ($result_task as $row) {
                    ?>
                        <tr class="bg-white border-b hover:bg-gray-50 text-justify">
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">
                                TCK-<?php echo $row['id'];  ?>
                            </th>
                            <td class="px-4 py-4">
                                <?php echo $row['usuario'];  ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php echo substr($row['detalle'], 0, 200); ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php echo $row['fecha'];  ?>
                            </td>
                            <td class="px-4 py-4">
                                <i class="bx <?php 
                                if($row['estado'] == 'Pendiente'){
                                    echo 'bx-time bg-yellow-400';
                                }
                                elseif($row['estado'] == 'Cancelado'){
                                    echo 'bx-lock-alt bg-red-700';
                                }
                                elseif($row['estado'] == 'Rechazado'){
                                    echo 'bx-error bg-red-700';
                                }
                                elseif($row['estado'] == 'Aprobado'){
                                    echo 'bx-search bg-blue-600';
                                }elseif($row['estado'] == 'Asignar'){
                                    echo 'bx-archive-out bg-purple-500';
                                }
                                else{echo 'bx-check bg-green-600';} 
                                ?> rounded p-2 text-white"></i>
                            </td>
                            <td class="px-2 py-4 text-center">
                                <i data-id="<?php echo $row['id'];?>"
                                    class='editar bx bx-low-vision bg-slate-800 text-white rounded p-2'></i>
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
            <p>No hay registros existentes, Estas al dia.</p>
            <?php 
    }
    ?>
        </div>
    </div>

    <?php
foreach ($result_task as $row) {
?>
    <div class="basis-12/12 py-6 hidden form-update" id="form-<?php echo $row['id']; ?>">
        <form class="relative  max-w-4xl mx-auto bg-white p-6 mb-3 rounded shadow form-edit">
            <?php 
        if ($rol == 'Empleado' and $row['estado'] == 'Pendiente') {
        ?>
            <div class="absolute top-0 right-0 flex space-x-2 p-5">
                <i class='bx bx-lock bg-slate-900 px-3 py-2 rounded text-white cancelar'
                    data-id="<?php echo $row['id']; ?>"></i>
                <i class='bx bx-trash-alt bg-red-600 px-3 py-2 rounded text-white eliminar'
                    data-id="<?php echo $row['id']; ?>"></i>
            </div>
            <?php 
        }
        ?>
            <?php 
        if ($row['estado'] == 'Pendiente' AND $rol == 'Jefe de Unidad') {
        ?>
            <div class="absolute top-0 right-0 flex space-x-2 p-5">
                <i class='bx bx-archive-out bg-purple-600 px-3 py-2 rounded text-white estatus'
                    data-id="<?php echo $row['id']; ?>" data-action='asignar'></i>
                <i class='bx bx-error bg-red-600 px-3 py-2 rounded text-white estatus'
                    data-id="<?php echo $row['id']; ?>" data-action='rechazar'></i>
            </div>
            <?php 
        }
        ?>
            <?php 
        if ($row['estado'] == 'Asignar' AND $rol == 'Jefe de Servicios') {
        ?>
            <div class="absolute top-0 right-0 flex space-x-2 p-5">
                <i class='bx bx-search bg-blue-600 px-3 py-2 rounded text-white estatus'
                    data-id="<?php echo $row['id']; ?>" data-action='aprobar'></i>
                <i class='bx bx-error bg-red-600 px-3 py-2 rounded text-white estatus'
                    data-id="<?php echo $row['id']; ?>" data-action='rechazar'></i>
            </div>
            <?php 
        }
        ?>
            <?php 
        if ($row['estado'] == 'Aprobado' AND $rol == 'Empleado de Servicios' 
        or $row['estado'] == 'Aprobado' AND $rol == 'Jefe de Servicios') {
        ?>
            <div class="absolute top-0 right-0 flex space-x-2 p-5">
                <i class='bx bx-check bg-green-600 px-3 py-2 rounded text-white estatus'
                    data-id="<?php echo $row['id']; ?>" data-action='finalizar'></i>
                <i class='bx bx-error bg-red-600 px-3 py-2 rounded text-white estatus <?php if($row['estado'] == 'Aprobado' AND $rol == 'Empleado de Servicios' ){echo 'hidden';} ?>'
                    data-id="<?php echo $row['id']; ?>" data-action='rechazar'></i>
            </div>
            <?php 
        }
        ?>

            <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
            <div class="mb-5">
                <p>Ticket #<?php echo $row['id']; ?></p>
                <p>Subido por: <?php echo $row['usuario']; ?></p>
                <p>Realizo el: <?php echo $row['fecha']; ?></p>
                <p>Estado: <?php echo $row['estado']; ?></p>
            </div>
            <div class="mb-5 pt-3">
                <textarea name="1" id="1" class="w-full border border-blue-600  bg-gray-50 p-3"
                    <?php if($row['estado'] == 'Cancelado'){echo 'disabled';} ?>
                    <?php if($usuario != $row['usuario'] or $row['estado'] != 'Pendiente'){echo 'disabled';} ?>
                    style="height: 400px;"><?php echo $row['detalle']; ?></textarea>
            </div>
            <button type="submit"
                <?php if($usuario != $row['usuario'] or $row['estado'] != 'Pendiente'){echo 'hidden';} ?>
                class="text-white bg-slate-800 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Actualizar
                Ticket</button>
        </form>
        <form class="relative  max-w-4xl mx-auto bg-white p-6 rounded shadow form-coments">
            <p>Añadir comentarios al ticket:</p>
            <input type="hidden" name="1" id="1" value="<?php echo $row['id'] ?>">
            <input type="hidden" name="2" id="2" value="<?php echo $usuario; ?>">
            <textarea name="3" id="3" class="w-full border border-blue-600 mt-3 bg-gray-50 p-3" cols="30"
                rows="2"></textarea>
            <button type="submit"
                class="text-white bg-slate-900 mt-4 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Guardar
                comentario</button>
        </form>
        <?php
    $id_sol = $row['id'];
    $comentarios = "SELECT * FROM comentarios WHERE solicitud = '$id_sol' ORDER BY fecha DESC";
    $result_task2 = mysqli_query($conn, $comentarios);

    foreach ($result_task2 as $row) 
    {
    ?>
        <div class="relative my-6 max-w-4xl mx-auto bg-white p-6 rounded shadow form-coments text-sm text-justify">
            <p class="font-semibold">
                <?php echo $row['usuario']; ?>
            </p>
            <p>
                <?php echo $row['fecha']; ?>
            </p>
            <p class="pt-3">
                <?php echo $row['texto']; ?>
            </p>
        </div>
        <?php 
    }
    ?>

    </div>

    <?php
}
?>

    <div class="basis-12/12 py-6 hidden" id="view-2">
        <form class="max-w-4xl mx-auto bg-white p-6 rounded shadow-sm" id="form">
            <div class="mb-5 pt-2">
                <p>Creacion de Ticket</p>
                <p>Usuario: <?php echo $_SESSION['key']['usuario']; ?></p>
            </div>
            <div class="mb-5 pt-3">
                <textarea name="1" id="1" class="w-full bg-gray-50 border-0 p-3" style="height: 400px;"></textarea>
                <input type="hidden" name="2" id="2" value="<?php echo $_SESSION['key']['usuario']; ?>">
            </div>
            <button type="submit"
                class="text-white bg-slate-900 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Crear
                Ticket</button>
        </form>
    </div>
</div>