<?php
session_start();
?>
<script>
var currentPage = 1; // O asigna el valor inicial que desees
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "./components/moduls/dashboard/totales.php",
        success: function(response) {
            $('#res-p').html(response);
        }
    });
    // Función para cargar y mostrar datos mediante Ajax
    function cargarDatos(pagina, query) {
        if (currentPage == 1) {
            $('.back').hide();
        } else {
            $('.back').show();
        }
        $.ajax({
            url: './components/moduls/dashboard/facturas.php',
            method: 'GET',
            data: {
                pagina: pagina,
                q: query
            },
            dataType: 'json',
            success: function(data) {
                // Procesar y mostrar los datos en la tabla y paginación
                mostrarFacturas(data);
                actualizarTotalPaginas();
            },
            error: function(error) {
                console.error('Error en la petición AJAX', error);
            }
        });
    }

    function actualizarTotalPaginas() {
        $.ajax({
            url: './components/moduls/dashboard/facturas.php',
            method: 'GET',
            data: {
                getTotalPages: true
            },
            dataType: 'json',
            success: function(data) {
                // Actualizar el número total de páginas
                totalPages = data.totalPages;

            },
            error: function(error) {
                console.error('Error al obtener el número total de páginas', error);
            }
        });
    }
    // Función para mostrar facturas en la tabla
    function mostrarFacturas(datos) {
        // Limpiar la tabla antes de agregar nuevas filas
        $('#facturasTable tbody').empty();

        // Agregar filas a la tabla
        for (var i = 0; i < datos.length; i++) {
            var factura = datos[i];
            $('#facturasTable tbody').append(
                '<tr class="bg-white border-b hover:bg-gray-100"><td class="px-4 py-4">  <i class="bx bx-file-blank bg-green-600 facturado text-white rounded p-2"></i><td><td>CT-' +
                factura.id_cot +
                '</td><td>' + factura.usuario +
                '</td><td>' + factura.nombres + factura.apellidos +
                '</td><td>' + factura.telefono +
                '</td><td>' + factura.email +
                '</td><td>' + factura.fecha +
                '</td><td>$' + factura.valor_total_cotizacion.toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP'
                }) + '</td>' + '</td><td>' + factura.empresa + '</td>' + '><td class="px-4 py-4">  <i data-id="'+factura.id+'" class="bx bx-printer bg-gray-900 print_fac text-white rounded p-2"></i><td></tr>');
        }
        if (datos.length < 10) {
            $('.next').hide();
        } else {
            $('.next').show();
        }
        tippy('.facturado', {
            content: 'Cotizacion Facturada',
        });
    }

    // Inicializar la tabla y la paginación con la primera página y sin término de búsqueda
    cargarDatos(1, '');

    // Escuchar cambios en el input de búsqueda
    $('#buscador').change(function() {
        var query = $(this).val().toLowerCase();
        cargarDatos(1, query);
    });

    // Escuchar clics en los enlaces de paginación
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var pagina = $(this).text().trim();

        if (pagina === 'Anterior') {
            currentPage = Math.max(1, currentPage - 1);
        } else if (pagina === 'Siguiente') {
            currentPage = Math.min(currentPage + 1);
        }
        console.log(currentPage);
        var query = $('#buscador').val().toLowerCase();
        cargarDatos(currentPage, query);
    });
});
tippy('.print_fac', {
    content: 'Imprimir Cotizacion',
});
</script>

<div id="terceros" class="mt-6">
    <div class="grid gap-8 mb-2 md:grid-cols-3 text-center">
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold">$<span id="facturado">--</span></p>
            <p>Facturado</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold">$<span id="valor_rep">--</span></p>
            <p>Valor del Inventario</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold">$<span id="valor_cot">--</span></p>
            <p>Valor Cotizado</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold"><span id="rep-vendidos">--</span></p>
            <p>Repuestos Vendidos</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold"><span id="rep_stock">--</span></p>
            <p>Repuestos en Stock</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-green-600 text-lg font-semibold"><span id="cot_totales">--</span></p>
            <p>Cotizaciones realizadas</p>
        </div>
    </div>
    <div id="res-p"></div>
    <div class="w-fill mx-auto bg-white rounded p-2 rounded mt-6">
        <div class="py-2 px-2">
            <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="buscador"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 border-0"
                    placeholder="Buscar por Codigo, Nombre, Factura" required>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto py-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="facturasTable">
            <tbody class="p-4 bg-white rounded"></tbody>
        </table>
    </div>
    <div class="flex items-center justify-center my-4">
        <ul class="pagination flex space-x-4">
            <!-- Utiliza la clase "flex" y "space-x-4" para espacio horizontal -->
            <li class="page-item bg-white rounded px-2 py-1 hover:bg-gray-50 back">
                <a class="page-link" href="#" aria-label="Previous" @click="cargarDatos(currentPage - 1, query)"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                    <i class='bx bx-chevron-left'></i>
                    <span class="hidden md:inline-block ml-2">Anterior</span>
                </a>
            </li>
            <template x-for="page in totalPages" :key="page">
                <li class="page-item" :class="{ 'active': currentPage === page }" @click="cargarDatos(page, query)">
                    <a class="page-link" href="#" x-text="page"></a>
                </li>
            </template>
            <li class="page-item bg-white rounded px-2 py-1 hover:bg-gray-50 next">
                <a class="page-link" href="#" aria-label="Next" @click="cargarDatos(currentPage + 1, query)"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }">
                    <span class="hidden md:inline-block mr-2">Siguiente</span>
                    <i class='bx bx-chevron-right'></i>
                </a>
            </li>
        </ul>
    </div>
</div>