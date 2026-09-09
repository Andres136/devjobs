<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Notificaciones') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-145px)] bg-gray-900 px-3 pt-12 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="min-h-[115px] overflow-hidden rounded-md bg-gray-800 pb-8">
                <div class="pt-7 text-center">
                    <h1 class="text-base font-bold text-white my-10">Mis Notificaciones</h1>

              <div class=""divid-y divide-gray-200"></div>
                @forelse ( $notificaciones as $notificacion )
                    <div class="mx-5 mb-4 flex items-center justify-between rounded-lg  bg-white p-5 text-left text-gray-800 shadow-sm">
                        <div>
                            <p>Tienes un nuevo candidato en:
                                <span class="font-bold">
                                    {{ data_get($notificacion->data, 'nombre_vacante')
                                        ?? data_get($notificacion->data, 'nombre_vacantes')
                                         }}
                                </span>
                            </p>
                            <p><span class="font-bold">{{ $notificacion->created_at->diffForHumans() }}</span></p>
                        </div>
                        <a href="{{ route('candidatos.index', $notificaciones->data['vacante_id']) }}" class="ml-4 shrink-0 rounded-lg bg-indigo-500 p-3 text-sm font-bold uppercase text-white">
                            Ver Candidatos</a>
                    </div>
                @empty
                    <p class=" text-center  text-gray-600">No Hay notificaciones nuevas.</p>
                @endforelse
              </div>   
             </div>  
            </div>
        </div>
    </div>
</x-app-layout>
