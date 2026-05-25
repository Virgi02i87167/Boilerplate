@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
        Gestión de Habitaciones
    </h1>

    <a href="{{ route('habitaciones.create') }}"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">
        + Nueva Habitación
    </a>
</div>

<div class="bg-white dark:bg-[#161615] rounded-xl shadow overflow-hidden">

    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300">

        <!-- HEADER -->
        <thead class="bg-gray-100 dark:bg-[#1C1C1B] text-gray-700 dark:text-gray-300 uppercase text-xs">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Imagen</th>
                <th class="px-6 py-3">Número</th>
                <th class="px-6 py-3">Tipo</th>
                <th class="px-6 py-3">Precio</th>
                <th class="px-6 py-3">Estado</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr>
        </thead>

        <!-- BODY -->
        <tbody>
            @forelse ($habitaciones as $habitacion)
                <tr class="border-b dark:border-[#2a2a2a] hover:bg-gray-50 dark:hover:bg-[#1C1C1B] transition">

                    <!-- ID -->
                    <td class="px-6 py-4">{{ $habitacion->id }}</td>

                    <!-- Imagen -->
                    <td class="px-6 py-4">
                        @if($habitacion->ruta_imagen)
                            <img src="{{ asset('storage/' . $habitacion->ruta_imagen) }}" alt="Habitación" class="w-12 h-12 object-cover rounded shadow">
                        @else
                            <div class="w-12 h-12 bg-gray-200 dark:bg-[#3E3E3A] rounded flex items-center justify-center text-[10px] text-gray-400">N/A</div>
                        @endif
                    </td>

                    <!--numero de habitacion-->
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        {{ $habitacion->numero_habitacion }}
                    </td>

                    <!-- Tipo -->
                    <td class="px-6 py-4 capitalize">
                        {{ $habitacion->tipo }}
                    </td>

                    <!-- Precio -->
                    <td class="px-6 py-4">
                        $ {{ number_format($habitacion->precio, 2) }}
                    </td>

                    <!-- Estado con colores -->
                    <td class="px-6 py-4">
                        @if ($habitacion->estado == 'disponible')
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-600 rounded-full dark:bg-green-900 dark:text-green-300">
                                Disponible
                            </span>
                        @elseif ($habitacion->estado == 'ocupada')
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full dark:bg-red-900 dark:text-red-300">
                                Ocupada
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-600 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                Mantenimiento
                            </span>
                        @endif
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4 text-right flex justify-end gap-2">

                        <!-- Editar -->
                        <a href="{{ route('habitaciones.edit', $habitacion) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition">
                            Editar
                        </a>

                        <!-- Eliminar -->
                        <form action="{{ route('habitaciones.destroy', $habitacion) }}" method="POST"
                            class="confirm-form" data-confirm="¿Eliminar habitación?" data-confirm-subtitle="Se eliminarán los registros relacionados con esta habitación y no se podrá deshacer.">
                            @csrf
                            @method('DELETE')

                            <button
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow transition">
                                Eliminar
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7"
                        class="text-center px-6 py-6 text-gray-400 dark:text-gray-500">
                        No hay habitaciones registradas
                    </td>
                </tr>
            @endforelse
        </tbody>


    </table>
</div>

@endsection