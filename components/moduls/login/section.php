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


<div class="flex flex-row" style="height: 100%; overflow:hidden">
    <div class="basis-6/12 shadow-lg flex flex-col justify-center p-20"  style="background-color: #FDFEFE">
        <form class="p-20 mx-20" id="form_login">
            <div class="py-3">
                <label for="empresa" class="block mb-4 text-md font-medium text-slate-700">Empresa</label>
                <input type="text" autocomplete="off" id="empresa" name="empresa"
                    class="bg-gray-50 border border-gray-300 text-black text-md rounded focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="" required>
            </div>
            <div class=" py-3">
                <label for="usuario" class="block mb-4 text-md font-medium text-slate-700">Usuario</label>
                <input type="text" autocomplete="off" id="usuario"
                    class="bg-gray-50 border border-gray-300 text-black text-md rounded focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder=""" required>
        </div>
        <div class=" py-3">
                <label for="contraseña" class="block mb-4 text-md font-medium text-slate-700">Contraseña</label>
                <input type="password" autocomplete="off" id="contraseña"
                    class="bg-gray-50 border border-gray-300 text-black text-md rounded focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="*****" required>
            </div>
            <div class="py-3">
                <div class="text-center">
                    <img src="captcha.php" class="mt-2 mb-5 border border-slate-800" id="captchaImage" alt="Captcha Image">
                </div>
                <label for="captcha" class="block mb-4 text-md font-medium text-slate-700">Captcha</label>
                <input type="text" autocomplete="off" id="captcha_input" name="captcha"
                    class="bg-gray-50 border border-gray-300 text-black text-md rounded focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5"
                    placeholder="4d907d" required>
            </div>

            <button type="submit"
                class="text-white bg-gray-700 hover:bg-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded text-sm w-full sm:w-auto px-5 py-2.5 mt-2 text-center">Login</button>
        </form>

    </div>
    <div class="basis-12/12 md:basis-6/12 bg-gray-50 relative">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <img src="./components/moduls/login/img.jpg"
            class="w-full h-full object-cover" alt="">
    </div>
</div>