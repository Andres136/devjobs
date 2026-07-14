<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Vacante') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-145px)] bg-gray-900 px-3 pt-12 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="min-h-[115px] overflow-hidden rounded-md bg-gray-800 pb-8">
                <div class="pt-7 text-center">
                    <h1 class="text-base font-bold text-white my-10">Editar Vacante: {{ $vacante->titulo }}</h1>
                </div>

                <div class="mt-9 text-center">
                    <livewire:editar-vacante />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
