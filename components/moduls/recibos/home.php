<div id="terceros">
    <div class="bg-white mx-5 p-5 mt-6 rounded">
        <p class="border-b-2 border-slate-900 text-xl text-slate-700 font-semibold p-4 mb-6">Terceros</p>
        <form class="px-3 py-4 mt-2">
            <div class="grid gap-8 mb-8 md:grid-cols-2 ">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Nit / Numero de
                        Documento</label>
                    <input type="text" id="first_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="John" required>
                </div>
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">Nombres</label>
                    <input type="text" id="first_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="John" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Apellidos</label>
                    <input type="text" id="last_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="Doe" required>
                </div>
                <div>
                    <label for="company" class="block mb-2 text-sm font-medium text-gray-900 ">Tipo de Documento</label>
                    <select name="" id=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        requiered>
                        <option value="">Tarjeta de Identidad</option>
                        <option value="">Tarjeta de Extranjeria</option>
                        <option value="">Nit</option>
                        <option value="">Cedula de Ciudadania</option>
                        <option value="">Cedula de Extranjeria</option>
                        <option value="">Permiso Especial PPE</option>
                        <option value="">Otro</option>
                    </select>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 ">Numero Telefonico</label>
                    <input type="tel" id="phone"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
                <div>
                    <label for="website" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                    <input type="mail" id="website"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="flowbite.com" required>
                </div>
                <div>
                    <label for="visitors" class="block mb-2 text-sm font-medium text-gray-900 ">Direccion</label>
                    <input type="text" id="visitors"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" required>
                </div>
                <div>
                    <label for="visitors" class="block mb-2 text-sm font-medium text-gray-900 ">Fecha de
                        Cumpleaños</label>
                    <input type="date" id="visitors"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="" required>
                </div>

            </div>
            <button type="submit"
                class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Guardar</button>
        </form>

    </div>
</div>