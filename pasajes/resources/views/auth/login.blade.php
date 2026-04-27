<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-white mb-2">Bienvenido de vuelta</h2>
        <p class="text-gray-400">Ingresa tus credenciales para continuar.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors shadow-sm" placeholder="ejemplo@correo.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand-400 hover:text-brand-300 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors shadow-sm" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-brand-500 focus:ring-brand-500 focus:ring-offset-gray-950">
            <label for="remember_me" class="ml-2 block text-sm text-gray-400 cursor-pointer">
                Recordar mis datos
            </label>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-brand-500/30 transition-all active:scale-[0.98]">
            Iniciar Sesión
        </button>

        <div class="pt-4 text-center border-t border-gray-800 mt-6">
            <p class="text-sm text-gray-400 mt-4">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="font-bold text-white hover:text-brand-400 transition-colors">Regístrate aquí</a>
            </p>
        </div>
    </form>
</x-guest-layout>
