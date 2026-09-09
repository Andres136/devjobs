<div class="bg-gray-100 py-8">
    <h2 class="mb-5 text-center text-xl font-extrabold text-gray-700 md:text-2xl">
        Buscar y Filtrar Vacantes
    </h2>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <form
         wire:submit.prevent="leerDatosFormulario"
        >
            <div class="grid gap-4 md:grid-cols-3">
                <div class="mb-5">
                    <label 
                        class="mb-1 block text-xs font-bold uppercase text-gray-700"
                        for="termino">Término de Búsqueda
                    </label>
                    <input 
                        id="termino"
                        type="text"
                        placeholder="Buscar por Término: ej. Laravel"
                        class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        wire:model="termino"
                    />
                </div>

                <div class="mb-5">
                    <label class="mb-1 block text-xs font-bold uppercase text-gray-700">Categoría</label>
                    <select wire:model="categoria" class="w-full rounded-md border-gray-300 p-2 text-sm">
                        <option value="">--Seleccione--</option>
            
                        @foreach ($categorias as $categoria )
                            <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="mb-1 block text-xs font-bold uppercase text-gray-700">Salario Mensual</label>
                    <select wire:model="salario" class="w-full rounded-md border-gray-300 p-2 text-sm">
                        <option value="">-- Seleccione --</option>
                        @foreach ($salarios as $salario)
                            <option value="{{ $salario->id }}">{{$salario->salario}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <input 
                    type="submit"
                    class="w-full cursor-pointer rounded bg-indigo-600 px-8 py-2 text-xs font-bold uppercase text-white transition-colors hover:bg-indigo-700 md:w-auto"
                    value="Buscar"
                />
            </div>
        </form>
    </div>
</div>
