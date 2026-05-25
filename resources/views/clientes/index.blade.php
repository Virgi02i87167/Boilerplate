<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <span>Listado de Clientes</span>
            @can('gestionar-clientes')
                <a href="{{ route('clientes.create') }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    NUEVO CLIENTE
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="bg-white dark:bg-[#161615] shadow-sm sm:rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-[#1C1C1B] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-[#A1A09A] uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-[#A1A09A] uppercase tracking-wider">Ubicación</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-[#A1A09A] uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse ($clientes as $cliente)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1C1C1B]/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 dark:text-[#EDEDEC]">{{ $cliente->distrito }}, {{ $cliente->municipio }}</div>
                            <div class="text-[10px] text-gray-400 dark:text-[#A1A09A] uppercase">{{ $cliente->departamento }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('clientes.edit', $cliente) }}" class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline confirm-form" data-confirm="¿Eliminar cliente?" data-confirm-subtitle="Se borrarán permanentemente sus registros de cliente.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-[#A1A09A]">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-gray-300 dark:text-[#3E3E3A]" style="width: 48px; height: 48px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-lg font-bold">No hay clientes registrados</span>
                                <p class="text-sm mt-1">Comienza agregando tu primer cliente al sistema.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
