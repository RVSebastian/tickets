<script>
$(document).ready(function() {

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
                console.log(response);
                if (response == null) {
                    $('#nombres').val('');
                    $('#apellidos').val('');
                    $('#tipodocu').val('');
                    $('#telefono').val('');
                    $('#email').val('');
                    $('#direccion').val('');
                    $('#cumpleaños').val('');
                } else {
                    $('#nombres').val(response.nombres);
                    $('#apellidos').val(response.apellidos);
                    $('#tipodocu').val(response.tipodocu);
                    $('#telefono').val(response.telefono);
                    $('#email').val(response.email);
                    $('#direccion').val(response.direccion);
                    $('#cumpleaños').val(response.cumpleaños);
                }

            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    $('#form_terceros').submit(function(e) {
        e.preventDefault();
        var datapacked = $('#form_terceros').serialize();
        $.ajax({
            type: 'POST',
            url: "./components/moduls/terceros/controls.php",
            data: datapacked + "&action=insert", // Concatenate the action parameter
            success: function(response) {
                console.log(response);
                if (response == 'ok') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Se insertó o actualizó correctamente.',
                    });
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

});
</script>
<div id="terceros" class="max-w-8xl mx-auto">
    <div class="bg-white p-5 mt-7 rounded">
        <p class="border-b-2 border-slate-900 text-xl text-slate-700 font-semibold p-4 mb-6"><img src="../"
                alt="">Terceros</p>
        <form class="px-3 py-4 mt-2" id="form_terceros">
            <div class="grid gap-8 mb-8 md:grid-cols-2 ">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Nit / Numero de
                        Documento</label>
                    <input type="text" id="nit" name="nit"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="John" required>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Nombres</label>
                    <input type="text" id="nombres" name="nombres"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="John" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="Doe" required>
                </div>
                <div>
                    <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Tipo de Documento</label>
                    <select id="tipodocu" name="tipodocu"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        requiered>
                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                        <option value="Tarjeta de Extranjeria">Tarjeta de Extranjeria</option>
                        <option value="Nit">Nit</option>
                        <option value="Cedula de Ciudadania">Cedula de Ciudadania</option>
                        <option value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                        <option value="Permiso Especial PPE">Permiso Especial PPE</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 ">Numero Telefonico</label>
                    <input type="tel" id="telefono" name="telefono"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="123-45-678" required>
                </div>
                <div>
                    <label for="website" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                    <input type="mail" id="email" name="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="flowbite.com" required>
                </div>
                <div>
                    <label for="visitors" class="block mb-2 text-sm font-medium text-gray-900 ">Direccion</label>
                    <input type="text" id="direccion" name="direccion"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" required>
                </div>
                <div>
                    <label for="visitors" class="block mb-2 text-sm font-medium text-gray-900 ">Fecha de
                        Cumpleaños</label>
                    <input type="date" id="cumpleaños" name="cumpleaños"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="">
                </div>

            </div>
            <button type="submit"
                class="text-white bg-gray-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Guardar</button>
        </form>

    </div>
</div>