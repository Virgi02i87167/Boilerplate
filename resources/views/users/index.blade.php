<x-app-layout>
    <x-slot name="header">
        Gestión de Usuarios
    </x-slot>

    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <span>Usuarios del Sistema</span>
                @can('gestionar-users')
                    <a href="{{ route('users.create') }}"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        NUEVO
                    </a>
                @endcan
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            N°</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Nombre</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Apellidos</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Email</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Teléfono</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Rol</th>
                        <th class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                            Estado</th>
                        <th
                            class="py-4 px-4 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider text-center">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @foreach ($users as $user)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-[#1C1C1B] transition-colors">
                            <td class="py-4 px-4 text-sm text-gray-500 dark:text-[#6E6E6A]">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 dark:text-[#A1A09A]">{{ $user->last_name ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600 dark:text-[#A1A09A]">{{ $user->email }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 dark:text-[#A1A09A]">{{ $user->phone ?? '-' }}</td>
                            <td class="py-4 px-4">
                                @foreach($user->roles as $role)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('users.show', $user) }}"
                                        class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                        title="Ver Detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @can('gestionar-users')
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="p-2 text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                            title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('eliminar-cuenta')
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            class="inline confirm-form" data-confirm="¿Desactivar usuario?" data-confirm-subtitle="Este usuario ya no podrá iniciar sesión en la plataforma.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Eliminar/Desactivar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>