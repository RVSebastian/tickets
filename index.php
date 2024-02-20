<?php
include './components/header.php';

$usuario = $_SESSION['key']['usuario'];
$rol = $_SESSION['key']['rol'];
$empresa = $_SESSION['key']['empresa'];

?>
<?php
if ($empresa == 'ADMINISTRADOR') {
?>
<style>
.border-green-600 {
    border-color: #9B59B6 !important;
}

.text-green-600 {
    color: #9B59B6 !important;
}

.text-green-600:hover {
    color: #9B59B6 !important;
}

.bg-green-600 {
    background-color: #9B59B6 !important;
}

.bg-green-600:hover {
    background-color: #9B59B6 !important;
}
</style>
<?php  
}
?>
<script>
var solicitudesAjax = []; // Array para almacenar todas las solicitudes AJAX activas

// Función para realizar solicitudes AJAX y almacenarlas en el array
function cargarContenido(url, successCallback) {
    $('#tickets_search').hide();
    $('#content').fadeOut(40, function() {
        cancelarTodasLasSolicitudes();

        var solicitud = $.ajax({
            type: "POST",
            url: url,
            success: function(response) {
                // Usar fadeIn para mostrar el nuevo contenido suavemente
                $('#content').html(response).fadeIn(200, function() {
                    if (successCallback) {
                        successCallback();
                    }
                });
            },
            error: function(xhr, status, error) {
                // Manejar errores si es necesario
                console.error(error);
            }
        });

        // Almacena la solicitud en el array
        solicitudesAjax.push(solicitud);
    });
}

// Función para cancelar todas las solicitudes AJAX almacenadas
function cancelarTodasLasSolicitudes() {
    $.each(solicitudesAjax, function(index, solicitud) {
        solicitud.abort(); // Cancela la solicitud AJAX
    });

    solicitudesAjax = []; // Limpia el array de solicitudes
}

// Funciones específicas para cargar contenido
function list() {
    cargarContenido("./components/moduls/usuarios/list.php");
}

function home() {
    cargarContenido("./components/moduls/dashboard/home.php");
}

function terceros() {
    cargarContenido("./components/moduls/terceros/home.php");
}

function recibos() {
    cargarContenido("./components/moduls/recibos/home.php");
}

function inventario() {
    cargarContenido("./components/moduls/inventario/home.php");
}

function perfil() {
    cargarContenido("./components/moduls/perfil/home.php");
}

function pagos() {
    cargarContenido("./components/moduls/pagos/usuario/home.php");
}

function cotizaciones() {
    cargarContenido("./components/moduls/cotizacion/home.php");
}



function home() {
    $('#login').hide();
    $('#app,#app_nav , #logo-sidebar').show();
    $('#loading').hide();
    $('#cont').fadeIn(900);
    $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
    $('.listas #home').toggleClass('border-b-2 border-green-600');
    cargarContenido("./components/moduls/dashboard/home.php");
}

function login() {
    $('#loading').show();
    $('#login').hide();

    $.ajax({
        type: "POST",
        url: "./components/moduls/login/section.php",
        success: function(response) {
            $('#login').html(response);
            $('#login').fadeIn(1400, function() {
                $('#loading').fadeOut(1200);
            });
        }
    });
    $('.cont').toggleClass('hidden');
    $('body').toggleClass('bg-white');
}


$(document).ready(function() {
    <?php if (!isset($_SESSION['key']['empresa']) OR !isset($_SESSION['key']['usuario'])) : ?>
    login();
    <?php else: ?>
    home();
    <?php endif; ?>

    $('#inventario').click(function() {
        inventario();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#users').click(function() {
        list();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#perfil').click(function() {
        perfil();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#pagos').click(function() {
        pagos();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });

    $('#recibos').click(function() {
        recibos();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#terceros').click(function() {
        terceros();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#home').click(function() {
        home();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#home2').click(function() {
        home();
        return false;
    });
    $('#cotizaciones').click(function() {
        cotizaciones();
        $('.listas').find('.border-b-2.border-green-600').removeClass('border-b-2 border-green-600');
        $(this).toggleClass('border-b-2 border-green-600');
        return false;
    });
    $('#destroy').click(function() {
        $.ajax({
            type: "POST",
            url: "./destroy.php",
            success: function(response) {
                location.reload();
            }
        });
        return false;
    });
});
</script>

<div id="loading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; overflow: hidden;">
    <div class="flex items-center justify-center bg-black bg-opacity-85 h-full">
        <div class="loader ease-linear rounded-full border-t-4 border-green-300 h-12 w-12 mb-4 animate-spin"></div>
    </div>
</div>


<div id="login" class="hidden" style="height: 100%; overflow:hidden"></div>

<nav class="fixed top-0 z-50 w-full bg-white border-b-4 border-green-600 shadow-md hidden" id="app_nav">
    <div class="px-3 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-slate-900 rounded-lg sm:hidden  focus:outline-none focus:ring-0 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 22 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="" id="home2" class="flex ms-4 md:me-24 ">
                    <span class="self-center text-xl font-semibold whitespace-nowrap "><span
                            class="text-2xl text-green-600 font-bold">S</span>tockify</span>
                    <span class="text-md text-slate-700 font-semibold mt-2"> -
                        <?php echo $_SESSION['key']['empresa'] ?></span>
                </a>

            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-5">
                    <div>
                        <button type="button" class="flex text-sm hover:text-green-700 px-2 mr-5 rounded-full"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <svg class="w-5 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M1 5h1.424a3.228 3.228 0 0 0 6.152 0H19a1 1 0 1 0 0-2H8.576a3.228 3.228 0 0 0-6.152 0H1a1 1 0 1 0 0 2Zm18 4h-1.424a3.228 3.228 0 0 0-6.152 0H1a1 1 0 1 0 0 2h10.424a3.228 3.228 0 0 0 6.152 0H19a1 1 0 0 0 0-2Zm0 6H8.576a3.228 3.228 0 0 0-6.152 0H1a1 1 0 0 0 0 2h1.424a3.228 3.228 0 0 0 6.152 0H19a1 1 0 0 0 0-2Z" />
                            </svg>
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow "
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <span
                                class="self-center text-sm font-semibold  whitespace-nowrap "><?php echo strtoupper($_SESSION['key']['empresa']); ?></span>
                            <p class="text-sm text-gray-900 " role="none">
                                <?php echo $_SESSION['key']['nombre']; ?>
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate " role="none">
                                <?php echo $_SESSION['key']['usuario']; ?>
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="" id="perfil"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white"
                                    role="menuitem">Configuracion</a>
                            </li>
                            <li>
                                <a href="" id="destroy"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white"
                                    role="menuitem">Salir</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full mt-4 border-r border-gray-200 shadow-lg  sm:translate-x-0 bg-slate-900 hidden"
    aria-label="Sidebar">
    <div class="h-full px-5 pb-4 overflow-y-auto listas">
        <ul class="space-y-6 font-normal text-sm">
            <li>
                <a href="" id="home" class="flex items-center p-2 text-gray-100   hover:bg-slate-800  group">
                    <svg class="w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="" id="inventario" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M19 0H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM2 6v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H2Zm11 3a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8a1 1 0 0 1 2 0h2a1 1 0 0 1 2 0v1Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Inventario</span>
                </a>
            </li>
            <li>
                <a href="" id="terceros" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z" />
                        <path
                            d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Terceros</span>
                </a>
            </li>
            <li>
                <a href="" id="cotizaciones" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Cotizaciones</span>
                </a>
            </li>

            <li>
                <a href="" id="1pagos" class="flex items-center p-2 text-gray-50  hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M13.383.076a1 1 0 0 0-1.09.217L11 1.586 9.707.293a1 1 0 0 0-1.414 0L7 1.586 5.707.293a1 1 0 0 0-1.414 0L3 1.586 1.707.293A1 1 0 0 0 0 1v18a1 1 0 0 0 1.707.707L3 18.414l1.293 1.293a1 1 0 0 0 1.414 0L7 18.414l1.293 1.293a1 1 0 0 0 1.414 0L11 18.414l1.293 1.293A1 1 0 0 0 14 19V1a1 1 0 0 0-.617-.924ZM10 15H4a1 1 0 1 1 0-2h6a1 1 0 0 1 0 2Zm0-4H4a1 1 0 1 1 0-2h6a1 1 0 1 1 0 2Zm0-4H4a1 1 0 0 1 0-2h6a1 1 0 1 1 0 2Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap text-">App mis pagos</span>
                </a>
            </li>
            <li>
                <a href="" id="recibos" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
                        <path
                            d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Mercancia</span>
                </a>
            </li>

            <li>
                <a href="" id="envios" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M19.9 6.58c0-.009 0-.019-.006-.027l-2-4A1 1 0 0 0 17 2h-4a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v9a1 1 0 0 0 1 1h.3c-.03.165-.047.332-.051.5a3.25 3.25 0 1 0 6.5 0A3.173 3.173 0 0 0 7.7 12h4.6c-.03.165-.047.332-.051.5a3.25 3.25 0 1 0 6.5 0 3.177 3.177 0 0 0-.049-.5h.3a1 1 0 0 0 1-1V7a.99.99 0 0 0-.1-.42ZM16.382 4l1 2H13V4h3.382ZM4.5 13.75a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Zm11 0a1.25 1.25 0 1 1 0-2.5 1.25 1.25 0 0 1 0 2.5Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Envios</span>
                </a>
            </li>
            <!--
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-100 transition duration-75 group hover:bg-gray-600  "
                    aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M18 5H0v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5Zm-7.258-2L9.092.8a2.009 2.009 0 0 0-1.6-.8H2.049a2 2 0 0 0-2 2v1h10.693Z" />
                    </svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Reportes</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 22 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-example" class="hidden py-2 space-y-2">
                    <li>
                        <a href="#"
                            class="flex items-center w-full p-2 text-gray-100 transition duration-75 rounded-lg pl-11 group hover:bg-gray-600 ">En
                            construccion</a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center w-full p-2 text-gray-100 transition duration-75 rounded-lg pl-11 group hover:bg-gray-600 ">En
                            construccion</a>
                    </li>
                </ul>
            </li>
-->
            <li>
                <a href="" id="users" class="flex items-center p-2 text-gray-100   hover:bg-gray-600  group">
                    <svg class="flex-shrink-0 w-5 h-5 text-white transition duration-75 group-hover:text-gray-100 "
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 24">
                        <path
                            d="M7.324 9.917A2.479 2.479 0 0 1 7.99 7.7l.71-.71a2.484 2.484 0 0 1 2.222-.688 4.538 4.538 0 1 0-3.6 3.615h.002ZM7.99 18.3a2.5 2.5 0 0 1-.6-2.564A2.5 2.5 0 0 1 6 13.5v-1c.005-.544.19-1.072.526-1.5H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h7.687l-.697-.7ZM19.5 12h-1.12a4.441 4.441 0 0 0-.579-1.387l.8-.795a.5.5 0 0 0 0-.707l-.707-.707a.5.5 0 0 0-.707 0l-.795.8A4.443 4.443 0 0 0 15 8.62V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.12c-.492.113-.96.309-1.387.579l-.795-.795a.5.5 0 0 0-.707 0l-.707.707a.5.5 0 0 0 0 .707l.8.8c-.272.424-.47.891-.584 1.382H8.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1.12c.113.492.309.96.579 1.387l-.795.795a.5.5 0 0 0 0 .707l.707.707a.5.5 0 0 0 .707 0l.8-.8c.424.272.892.47 1.382.584v1.12a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1.12c.492-.113.96-.309 1.387-.579l.795.8a.5.5 0 0 0 .707 0l.707-.707a.5.5 0 0 0 0-.707l-.8-.795c.273-.427.47-.898.584-1.392h1.12a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5ZM14 15.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Usuarios</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<div class="p-4 sm:ml-64 cont" style="overflow:hidden">
    <div class="p-4 rounded-lg mt-7">
        <div id="app" class="hidden">
            <section class="px-5 pt-3 pb-2 md:px-0 w-full" id="content"></section>
        </div>


    </div>
</div>



<?php
include './components/footer.php';

?>