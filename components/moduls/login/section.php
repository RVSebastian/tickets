<?php
if (isset($_SESSION['key'])) {
  echo '<script>location.reload();</script>';
}
?>
<script>
$(document).ready(function() {
    
    $('#form_login').submit(function() {
        var user = $('#usuario').val();
        var contraseña = $('#contraseña').val();
        $.ajax({
            type: "POST",
            url: "./components/moduls/login/validation.php",
            data: {
                user,
                contraseña
            },
            success: function(response) {
                if (response == 'Usuario autenticado correctamente') {
                    window.location.href = './index';
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

});
</script>
<div class="basis-12/12" style="margin-top: 20vh;">
    <form class="max-w-sm mx-auto bg-white p-5 rounded shadow" id="form_login">
        <div class="mb-5 pt-5">
            <label for="usuario" class="block mb-2 text-md font-medium text-gray-900">Usuario</label>
            <input type="text" id="usuario"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder="juancbastianrv@gmail.com" required>
        </div>
        <div class="mb-5 py-3">
            <label for="contraseña" class="block mb-2 text-md font-medium text-gray-900">Contraseña</label>
            <input type="text" id="contraseña"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder="*****" required>
        </div>

        <button type="submit"
            class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Login</button>
    </form>

</div>