<?php
if (isset($_SESSION['key'])) {
  session_destroy();
  echo '<script>location.reload();</script>';
}


?>
<script>
$(document).ready(function() {
    updateCaptchaImage();


    $('#form_login').submit(function() {
        // Obtener los valores de los campos
        var user = $('#usuario').val().trim();
        var empresa = $('#empresa').val().trim();
        var userInput = $('#captcha_input').val().trim();
        var contraseña = $('#contraseña').val().trim();

        // Limpiar los campos con valores sin espacios vacíos
        $('#usuario').val(user);
        $('#contraseña').val(contraseña);
        $.ajax({
            type: "POST",
            url: "./components/moduls/login/verifycaptcha.php",
            data: {
                captcha: userInput
            },
            success: function(response) {
                if (response === "true") {
                    $.ajax({
                        type: "POST",
                        url: "./components/moduls/login/validation.php",
                        data: {
                            user,
                            contraseña,
                            empresa
                        },
                        success: function(response) {
                            if (response ==
                                'Usuario autenticado correctamente') {
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Captcha incorrecto. Intenta de nuevo.",
                    });
                    $("#captchaImg").attr("src",
                        "./components/moduls/login/captcha.php"); // Recargar el captcha
                    // Aquí puedes recargar el captcha o realizar otras acciones.
                }
            }
        });
        return false;
    });

    function updateCaptchaImage() {
        // Actualizar el atributo src del elemento img con la ruta de la imagen del captcha
        $('#captchaImage').attr('src', './components/moduls/login/captcha.php');
    }
});
</script>
<style>
/* Estilos para la imagen del captcha */
#captchaImage {
    border-radius: 6px;
    width: 100%;
    padding: 0px;
}
</style>
<div class="basis-12/12" style="margin-top: 6vh;">
    <form class="max-w-md mx-auto p-5 bg-white rounded shadow-lg" id="form_login">
        <div class="pt-5">
            <label for="empresa" class="block mb-2 text-md font-medium text-slate-700">Empresa</label>
            <input type="text" id="empresa" name="empresa"
                class="bg-gray-100 border border-gray-300 text-slate-700 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder="" required>
        </div>
        <div class=" pt-5">
            <label for="usuario" class="block mb-2 text-md font-medium text-slate-700">Usuario</label>
            <input type="text" id="usuario"
                class="bg-gray-100 border border-gray-300 text-slate-700 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder=""" required>
        </div>
        <div class=" py-3">
            <label for="contraseña" class="block mb-2 text-md font-medium text-slate-700">Contraseña</label>
            <input type="password" id="contraseña"
                class="bg-gray-100 border border-gray-300 text-slate-700 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder="*****" required>
        </div>
        <div class="py-3">
            <div class="text-center">
                <img src="captcha.php" class="mb-3 border border-gray-200" id="captchaImage" alt="Captcha Image">
            </div>
            <label for="captcha" class="block mb-2 text-md font-medium text-slate-700">Captcha</label>
            <input type="text" id="captcha_input" name="captcha"
                class="bg-gray-100 border border-gray-300 text-slate-700 text-md rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                placeholder="4d907d" required>
        </div>

        <button type="submit"
            class="text-white bg-slate-900 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 mt-2 text-center">Login</button>
    </form>

</div>