<x-app-layout>
    <x-slot name="header">
        Panel Principal
    </x-slot>

    <div class="space-y-6">
        <x-card>
            <x-slot name="header">
                ¡Bienvenido, {{ Auth::user()->name }}!
            </x-slot>

            <p class="text-lg">
                Has iniciado sesión correctamente. La plataforma base está totalmente configurada, unificada y
                optimizada con el sistema de diseño premium. Todos los componentes de autenticación son
                ahora funcionales y coherentes.
            </p>
        </x-card>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card>
                <x-slot name="header">Estado del Sistema</x-slot>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">LISTO</p>
                <p class="text-sm text-[#A1A09A]">Base de Proyecto Optimizada</p>
            </x-card>

            <x-card>
                <x-slot name="header">Usuario Actual</x-slot>
                <p class="font-semibold dark:text-white">{{ Auth::user()->email }}</p>
                <p class="text-sm text-[#A1A09A]">ID: {{ Auth::id() }}</p>
            </x-card>
        </div>
    </div>
</x-app-layout>