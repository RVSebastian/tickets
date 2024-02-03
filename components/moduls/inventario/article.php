<?php
include '../../db/cn.php';
session_start();

$id = $_POST['id'];
$query = "select * from inventario where id='$id' or parte='$id'";
$result_task = mysqli_query($conn, $query);
$row_count = mysqli_num_rows($result_task);

?>

<script>
$('.atras').click(function() {
    cargarContenido("./components/moduls/inventario/home.php");
});
</script>

<?php
foreach ($result_task as $row){
?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white p-5 mt-7 rounded">
        <form class="px-3 pt-2 pb-4 mt-2">
            <p class="font-semibold pb-4 atras border-b-2 mb-4 border-slate-800"><i class='bx bx-chevron-left'></i> Atras</p>
            <div class="mb-3">
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Codigo</label>
                <input type="text" id="first_name" value="<?php echo $row['parte'] ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                    required>
            </div>
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Descripcion</label>
                <input type="text" id="first_name" value="<?php echo $row['descripcion'] ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                    required>
            </div>

            <div class="grid gap-8 mb-8 md:grid-cols-4 mt-4">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">% iva
                        venta</label>
                    <input type="text" id="first_name" value="<?php echo $row['porcentaje_iva'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">% iva
                        compra</label>
                    <input type="text" id="first_name" value="<?php echo $row['porcentaje_iva_compra'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">% de descuento</label>
                    <input type="text" id="first_name" value="<?php echo $row['porcentaje_descuento'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>

                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Proveedor</label>
                    <input type="text" id="last_name" value="AUTOMARCOL S.A.S"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
            </div>
            <div class="grid gap-8 mb-8 md:grid-cols-2 mt-4">
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Costo Unitario</label>
                    <input type="text" id="last_name" value="<?php echo $row['costo_unitario'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Precio de
                        Venta</label>
                    <input type="text" id="last_name" value="<?php echo $row['precio'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Tipo</label>
                    <input type="text" id="last_name" value="<?php echo $row['tipo'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Marca</label>
                    <input type="text" id="last_name" value="<?php echo $row['marca'] ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        required>
                </div>
               
            </div>
            <button type="submit"
                class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Guardar</button>
        </form>

    </div>
</div>
<?php
}
?>