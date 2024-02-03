<div id="terceros" class="mt-6">
    <div class="grid gap-8 mb-2 md:grid-cols-3 ">
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">$504.431</p>
            <p>Facturado</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">$504.431</p>
            <p>Valor del Inventario</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">$504.431</p>
            <p>Valor Cotizado</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">55</p>
            <p>Repuestos Vendidos</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">204</p>
            <p>Repuestos en Stock</p>
        </div>
        <div class="bg-white rounded p-5">
            <p class="text-lime-600 text-lg font-semibold">89</p>
            <p>Cotizaciones realizadas</p>
        </div>
    </div>

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
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <tbody>
                <?php
                for ($i=1; $i < 60 ; $i++) { 
                ?>
                <tr class="bg-white border-b hover:bg-gray-100">
                    <th scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        FV-<?php echo intval($i*48/21); ?>
                    </th>
                    <th scope="row" class="px-0 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        Apple MacBook Pro 17"
                    </th>
                    <td class="px-6 py-4">
                        Juan Sebastian Rodriguez Vargaz
                    </td>
                    <td class="px-6 py-4">
                        1096538459
                    </td>
                    <td class="px-6 py-4">
                        juancbastianrv@gmail.com
                    </td>
                    <td class="px-6 py-4">
                        Laptop
                    </td>
                    <td class="px-6 py-4">
                        $2999
                    </td>
                </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
</div>