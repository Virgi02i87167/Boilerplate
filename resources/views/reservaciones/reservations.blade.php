@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Listado de Reservas</h1>
        <a href="{{ route('reservaciones.create') }}"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition text-sm font-bold uppercase shadow-lg">
            Nueva Reserva
        </a>
    </div>

    <!-- TABLA DE RESERVAS -->
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-100 dark:border-[#2a2a2a] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-[#1C1C1B] border-b dark:border-[#3E3E3A]">
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Cliente</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Habitación</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Fechas</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-right">Total</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Estado</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-[#3E3E3A]">
                @forelse($reservaciones as $res)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-[#1C1C1B]/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $res->cliente->nombre }} {{ $res->cliente->apellido }}</div>
                        <div class="text-xs text-gray-500">{{ $res->cliente->dui }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded text-xs font-bold block mb-1">
                            #{{ $res->habitacion->numero_habitacion }} - {{ $res->habitacion->tipo }}
                        </span>
                        @php
                            $iconosPago = [
                                'efectivo' => '💵 Efectivo',
                                'tarjeta' => '💳 Tarjeta',
                                'transferencia' => '🏛️ Transferencia',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 bg-gray-50 dark:bg-[#1C1C1B] border border-gray-100 dark:border-[#2a2a2a] text-gray-600 dark:text-gray-400 rounded text-[10px] font-semibold capitalize inline-block">
                            {{ $iconosPago[$res->metodo_pago] ?? $res->metodo_pago }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm dark:text-gray-300">
                        <div class="font-medium">{{ $res->fecha_entrada->format('d M, Y') }}</div>
                        <div class="text-xs text-gray-400">al {{ $res->fecha_salida->format('d M, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400">
                        ${{ number_format($res->precio_total, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                        $coloresEstado = [
                            'pendiente' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                            'confirmada' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'cancelada' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'completada' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $coloresEstado[$res->estado] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($res->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3 text-sm">
                            <a href="{{ route('reservaciones.show', $res) }}" class="text-indigo-500 hover:text-indigo-700 font-medium">Detalles</a>
                             <form action="{{ route('reservaciones.destroy', $res) }}" method="POST" class="inline confirm-form" data-confirm="¿Eliminar reserva?" data-confirm-subtitle="Esta acción cancelará y eliminará permanentemente la reserva.">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                        No se encontraron reservaciones registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection