<form class="mx-auto w-full max-w-md text-left space-y-5" wire:submit.prevent='crearVacante'>
    <div>
        <x-input-label for="titulo" :value="__('Titulo Vacante')" />
        <x-text-input
            id="titulo"
            class="block mt-1 w-full"
            type="text"
            wire:model.live="titulo"
            :value="old('titulo')"
            placeholder="Titulo Vacante"
        />
        @error('titulo')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="salario" :value="__('Salario Mensual')" />

        <select
            id="salario"
            wire:model.live="salario"
            class="rounded-md shadow-sm border-gray-300
            focus:border-indigo-300 focus:ring-indigo-300 dark:bg-gray-800 dark:border-gray-700
            dark:text-gray-300 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 block mt-1 w-full"
        >
            <option value="">-- Seleccione --</option>
            @foreach ($salarios as $salario)
                <option value="{{ $salario->id }}">{{ $salario->salario }}</option>
            @endforeach
        </select>
         @error('salario')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="categoria" :value="__('Categoria')" />

        <select
            id="categoria"
            wire:model.live="categoria"
            class="rounded-md shadow-sm border-gray-300
            focus:border-indigo-300 focus:ring-indigo-300 dark:bg-gray-800 dark:border-gray-700
            dark:text-gray-300 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 block mt-1 w-full"
        >
            <option value="">-- Seleccione --</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
            @endforeach
        </select>
          @error('categoria')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="empresa" :value="__('Empresa')" />

        <x-text-input
            id="empresa"
            class="block mt-1 w-full"
            type="text"
            wire:model.live="empresa"
            :value="old('empresa')"
            placeholder="Empresa: ej. Netflix, Uber, Shopify"
        />
          @error('empresa')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="ultimo_dia" :value="__('Ultimo Dia para postularse')" />

        <x-text-input
            id="ultimo_dia"
            class="block mt-1 w-full"
            type="date"
            wire:model.live="ultimo_dia"
            :value="old('ultimo_dia')"
            placeholder="Ultimo Dia: ej. 2024-12-31"
        />
          @error('ultimo_dia')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="descripcion" :value="__('Descripcion del puesto')" />

        <textarea
            id="descripcion"
            wire:model.live="descripcion"
            placeholder="Descripcion general del puesto, experiencia"
            class="rounded-md shadow-sm border-gray-300
            focus:border-indigo-300 focus:ring-indigo-300 dark:bg-gray-800 dark:border-gray-700
            dark:text-gray-300 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 block mt-1 w-full"
            style="height: 18rem;"
        ></textarea>
          @error('descripcion')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="imagen" :value="__('Imagen')" />

        <x-text-input
            id="imagen"
            class="block mt-1 w-full"
            type="file"
            wire:model="imagen"
            accept="image/*"
        />

        <div class="my-5">
            @if ($imagen)
                Imagen:
                <img src="{{ $imagen->temporaryUrl() }}">
            @endif
        </div>

        @error('imagen')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <x-primary-button>
        Crear Vacante
    </x-primary-button>
</form>
