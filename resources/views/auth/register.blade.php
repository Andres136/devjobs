<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        {{-- Bloque de errores al inicio --}}
        @if ($errors->any())
            <div class="mb-4">
                @foreach ($errors->all() as $error)
                    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 px-4 py-2 mb-2 text-sm text-red-700 dark:text-red-400 rounded">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />

                  <x-input-label for="rol" :value="__('¿que tipo de cuentas desea en Devjobs')" />
                <select id="rol" name="rol" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 block mt-1 w-full">
                    <option value="">-- Selecciona un rol</option>
                    <option value="1">Developer - Obtener Empleo</option>
                    <option value="2">Recruiter - Publicar Empleo</option>
                </select>
                 
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Repetir password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
        </div>

          <div class="flex justify-between my-5">
        
            <x-link :href="route('login')">
                Iniciar sesión
            </x-link>

                <x-link :href="route('password.request')">
                Olvidaste tu contraseña
            </x-link>
        </div>
        <x-primary-button class="w-full justify-center">
                {{ __('Crear cuenta') }}
            </x-primary-button>
    </form>
</x-guest-layout>
