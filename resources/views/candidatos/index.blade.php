<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Candidatos Vacantes') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-145px)] bg-gray-900 px-3 pt-12 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="min-h-[115px] overflow-hidden rounded-md bg-gray-800 pb-8">
                <div class="pt-7 text-center">
                    <h1 class="text-base font-bold text-white my-10">Candidatos Vacante:
                         {{ $vacante->titulo }}</h1>
                </div>

                <div class="mt-9 text-center">
                   <ul class="divide-y divide-gray-200 w-full">
                    @forelse ( $vacante->candidatos as $candidato )
                        <li class="p-3 flex items-center">
                        <div class="flex-1">
                            <p class="text-xl font-mediun tex-gray-800"> {{ $candidato->user->name }}</p>
                            <p class="text-sm  text-gray-600"></p>{{ $candidato->user->email }}</p>
                            <p class="text-sm font-medium text-gray-600">
                                Dia que se postulo:<span class="font-normal"> {{ $candidato->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                        <div>

                        </div>
                        </li>
                    @empty
                        <p class="p-3 text-center text-sm tex-gray-600">No hay candidatos aun</p>
                    @endforelse
                   </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>