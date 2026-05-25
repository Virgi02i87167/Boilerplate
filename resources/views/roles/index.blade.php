<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <span>Roles y Permisos del Sistema</span>
            @can('gestionar-roles')
                <a href="{{ route('roles.create') }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    NUEVO ROL
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Tarjetas de Roles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($roles as $role)
                <x-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $role->name }}</span>
                            <div class="flex items-center gap-2">
                                @can('gestionar-roles')
                                    <a href="{{ route('roles.edit', $role) }}"
                                        class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endcan
                                @can('gestionar-roles')
                                    @if($role->name !== 'Admin')
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline confirm-form"
                                            data-confirm="¿Eliminar este rol?" data-confirm-subtitle="Esta acción puede afectar a los usuarios asignados a este rol y no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                    {{ $role->permissions->count() }} Permisos
                                </span>
                            </div>
                        </div>
                    </x-slot>

                    <div class="p-4 space-y-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse ($role->permissions as $permission)
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-600 dark:bg-[#1C1C1B] dark:text-[#A1A09A] border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 italic">No tiene permisos asignados</span>
                            @endforelse
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Información Adicional -->
        <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-900/30">
            <div class="flex gap-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300">Gestión de Acceso</h4>
                    <p class="text-sm text-blue-800 dark:text-blue-400 opacity-80 mt-1">
                        Los roles definen qué acciones pueden realizar los usuarios dentro del sistema. Actualmente esta
                        vista es solo de consulta de permisos asignados por base de datos.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>