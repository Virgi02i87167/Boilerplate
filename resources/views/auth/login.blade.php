<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md px-8 py-10 bg-white/70 dark:bg-[#161615]/70 backdrop-blur-xl border border-white/20 dark:border-white/5 shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] overflow-hidden sm:rounded-2xl transition-all duration-300">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <x-input name="email" type="email" label="Correo Electrónico" :value="old('email')" required autofocus
                    autocomplete="username" placeholder="ejemplo@correo.com" />

                <!-- Password -->
                <x-input name="password" type="password" label="Contraseña" required autocomplete="current-password"
                    placeholder="Tu contraseña segura" />

                <!-- Remember Me & Forgot Password -->
                <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                    <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                        <input id="remember_me" type="checkbox"
                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 transition-all cursor-pointer"
                            name="remember">
                        <span
                            class="ms-2 text-sm text-gray-600 dark:text-[#A1A09A] group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors"
                            href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <x-primary-button
                        class="w-full justify-center py-3 text-sm font-bold shadow-indigo-200/50 dark:shadow-none">
                        Iniciar Sesión
                    </x-primary-button>
                </div>

                <div class="flex items-center justify-center pt-4 border-t border-gray-100 dark:border-[#3E3E3A]">
                    <span class="text-sm text-gray-600 dark:text-[#A1A09A]">
                        ¿No tienes cuenta?
                        <a class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline ml-1 transition-all"
                            href="{{ route('register') }}">Regístrate gratis</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>