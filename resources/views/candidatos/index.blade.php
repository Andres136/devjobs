<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Candidatos Vacantes') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-145px)] bg-gray-100 px-3 py-12 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-md bg-white px-6 py-8 shadow-sm dark:bg-gray-800 sm:px-10">
                <h1 class="py-2 text-center text-base font-bold text-gray-900 dark:text-white">
                    Candidatos Vacante: {{ $vacante->titulo }}
                </h1>

                <ul class="mt-10 w-full divide-y divide-gray-200">
                    @forelse ($vacante->candidatos as $candidato)
                        <li class="flex items-center justify-between gap-4 px-2 py-3 sm:px-4">
                            <div>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $candidato->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $candidato->user->email }}</p>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Día que se postuló:
                                    <span class="font-normal">{{ $candidato->created_at->diffForHumans() }}</span>
                                </p>
                            </div>

                            <div class="shrink-0">
                                <a
                                   class="inline-flex items-center rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm
                                    hover:bg-gray-50"
                                    href="{{ route('candidatos.cv', $candidato) }}"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    Ver CV
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-sm text-gray-600 dark:text-gray-300">No hay candidatos aún</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
