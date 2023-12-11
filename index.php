<?php
include './components/header.php';
?>

<script>
function list() {
    $('#view-1').show();
    $('#view-2').hide();
    $('.form-update').hide();
    $('#content').hide();
    $.ajax({
        type: "POST",
        url: "./components/moduls/usuarios/list.php",
        success: function(response) {
            $('#content').html(response);
            $('#content').fadeIn('slow');
            $('#usuarios').show();
        }
    });
}

function solicitudes(c, id) {
    $('#view-1').show();
    $('#view-2').hide();
    $('.form-update').hide();
    $('#content').hide();
    $.ajax({
        type: "POST",
        url: "./components/moduls/tickets/list.php",
        success: function(response) {
            $('#content').html(response);
            if (c == 'fast') {
                $('#content').show();
            }else{
                $('#content').fadeIn('slow');
            }
            if (c == 'coment') {
                $('#view-1').hide();
                $('#form-' + id).show();
            }
        }
    });
}

$(document).ready(function() {
    solicitudes();

    setInterval(function() {
        if ($('#content #tickets #view-1').is(':visible')) {
            solicitudes(c='fast');
        }
    }, 10000);


    $('#users').click(function() {
        list();
        return false;
    });

});
</script>

<nav class="bg-white shadow-lg">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-2 px-5">
        <a href="./index" class="flex items-center space-x-4 rtl:space-x-reverse">
            <img src="./img/logo.jpeg" style="width:70px;" alt="">
            <span
                class="self-center text-lg font-semibold whitespace-nowrap "><?php echo $_SESSION['key']['rol']; ?></span>

        </a>
        <button data-collapse-toggle="navbar-default" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg  md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 bg-gray-50 md:bg-white">
                <li>
                    <a href="" id="home"
                        class="block py-2 px-3  text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-500 md:p-0 "
                        aria-current="page">Home</a>
                </li>
                <li>
                    <a href="" id="users"
                        class="<?php if($_SESSION['key']['rol'] != 'Jefe de Servicios'){echo 'hidden';}    ?> block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-500 md:p-0 ">Usuarios</a>
                </li>
                <li>
                    <a href="./destroy"
                        class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-500 md:p-0 ">Salir
                        </i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="px-4 pt-3 pb-2 md:px-0" id="content">

</section>

<?php
include './components/footer.php';

?>