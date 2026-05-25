@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Precios por Temporada</h1>
        <a href="{{ route('precios-temporada.create') }}" 
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition text-sm font-bold uppercase">
            Nueva Temporada
        </a>
    </div>

    <div class="bg-white dark:bg-[#161615] rounded-xl shadow border dark:border-[#3E3E3A] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-[#1C1C1B] border-b dark:border-[#3E3E3A]">
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Habitación</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Temporada</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Rango</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-right">Precio</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-[#3E3E3A]">
                @forelse($precios as $precio)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1C1C1B]/50 transition">
                    <td class="px-6 py-4 text-sm font-bold dark:text-white">#{{ $precio->habitacion->numero_habitacion }}</td>
                    <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $precio->descripcion }}</td>
                    <td class="px-6 py-4 text-sm dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($precio->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($precio->fecha_fin)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-right text-indigo-600 dark:text-indigo-400">
                        ${{ number_format($precio->precio, 2) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('precios-temporada.edit', $precio) }}" class="text-blue-500 hover:text-blue-700">Editar</a>
                            <form action="{{ route('precios-temporada.destroy', $precio) }}" method="POST" class="inline confirm-form" data-confirm="¿Borrar este precio de temporada?" data-confirm-subtitle="Esta acción eliminará el precio configurado de forma permanente.">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay precios configurados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

