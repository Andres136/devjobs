<div class="bg-gray-100 pb-10 pt-5">
    <livewire:filtrar-vacantes />
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <h3 class="mb-7 text-2xl font-extrabold text-gray-800">
            Nuestras Vacantes Disponibles
        </h3>

        <div class="overflow-hidden rounded-lg bg-white px-3 py-1 shadow-sm sm:px-6 sm:py-3">
            @forelse ($vacantes as $vacante)
                <div class="flex flex-col gap-3 border-b border-gray-200 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between sm:gap-5 sm:py-5">
                    <div>
                        <a 
                        class="block text-lg font-extrabold text-gray-700"
                        href="{{ route('vacantes.show', $vacante->id) }}">
                            {{ $vacante->titulo }}
                        </a>
                        <p class="text-xs text-gray-600">{{ $vacante->empresa }}</p>
                        <p class="font-bold text-xs text-gray-600">
                            Ultimo dia para postularse:
                            <span class="font-normal">{{ optional($vacante->ultimo_dia)->format('d/m/Y') }}</span>
                        </p>
                    </div>
                   
                    <div class="w-full sm:w-auto">
                    <a
                        class="block w-full rounded-md bg-indigo-600 px-4 py-3 text-center text-xs font-bold uppercase text-white transition hover:bg-indigo-700 sm:w-auto"
                        href="{{ route('vacantes.show', $vacante->id) }}"
                
                    >
                        Ver Vacante
                    </a>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-gray-600">No hay vacantes disponibles</p>
            @endforelse
        </div>
    </div>
</div>
