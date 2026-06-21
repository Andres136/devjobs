<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        @if ($errors->any())
            <div class="mb-4 space-y-2">
                @foreach ($errors->all() as $error)
                    <div class="w-full bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 px-4 py-3 text-sm font-semibold text-red-700 dark:text-red-400 rounded">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="current-password" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex justify-between my-5">
        
            <x-link :href="route('register')">
                Crear cuenta
            </x-link>

                <x-link :href="route('password.request')">
                Olvidaste tu contraseña
            </x-link>
        </div>
        <x-primary-button class="w-full justify-center">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
    </form>
</x-guest-layout>
