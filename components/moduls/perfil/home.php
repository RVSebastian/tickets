<?php

include '../../db/cn.php';
session_start();

$id = $_SESSION['key']['id'];

$query = "SELECT * FROM usuarios WHERE id = '$id'";

$result_task = mysqli_query($conn, $query);

?>

<?php
foreach ($result_task as $row) {
?>
<div id="terceros">
    <div class="py-2 px-0 mt-6">
        <div class="md:flex">
            <div class="md:basis-4/12 md:px-5">
                <div class="bg-white rounded h-full p-4 text-center">
                    <div class="py-5 mt-5">
                        <img src="./img/logo.png" class="mx-auto max-w-60" alt="">
                    </div>
                    <p class="text-slate-700 font-semibold text-lg"><?php echo $row['nombre'] ?></p>
                    <p><?php echo $row['rol'] ?></p>
                    <p><?php echo $row['empresa'] ?></p>
                </div>
            </div>
            <div class="md:basis-8/12 md:px-5">
                <div class="bg-white rounded p-5">
                    <div class="p-4">
                        <p class="border-b-2 border-slate-900 text-2xl text-slate-700 font-semibold p-2">Perfil</p>
                        <div class="mt-8">
                            <p class="text-lg text-slate-700 font-semibold">About</p>
                            <p class="mt-2 mb-8">Sistema de inventario Automatizado</p>
                            <p class="text-lg text-slate-700 font-semibold">Detalles del Perfil</p>
                            <p class="mt-2 mb-4"><span class="text-slate-900 font-semibold">Nombre:</span> <?php echo $row['nombre'] ?></p>
                            <p class="mb-4"><span class="text-slate-900 font-semibold">Correo:</span> Ejemplo@gmail.com</p>
                            <p class="mb-4"><span class="text-slate-900 font-semibold">Fecha de Registro:</span> <?php echo $row['fecha_subida'] ?>
                            </p>
                            <p class="mb-4"><span class="text-slate-900 font-semibold">Tipo de usuario:</span> <?php echo $row['rol'] ?>
                            </p>
                            <p class="text-sm text-center py-5">Copyright <span class="font-semibold">UIAX Dev</span> 2023</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="basis-12/12 h-full  px-5 py-5 mt-3">
            <div class="bg-white rounded h-full p-4">
                <p class="border-b-2 border-slate-900 text-2xl p-3 text-slate-700 font-semibold mb-2">Logs</p>
                <div class="p-5">
                    <?php
                    for ($i=0; $i < 13 ; $i++) { 
                    echo '<p class="py-2 mb-3 border-b border-gray-200 hover:bg-gray-100"> <i class="bx bxs-calendar"></i> Usuario juancbastianrv@gmail.com ingreso al modulo de inventario el 26/12/2023 a las 9:00 pm</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}
?>