@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalle de Reserva #{{ $reservacion->id }}</h1>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-100 dark:bg-[#1C1C1B] dark:text-white rounded-lg hover:bg-gray-200 transition text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir
            </button>
            <a href="{{ route('reservaciones.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm">Volver</a>
        </div>
    </div>

    <div id="ticket-imprimible" class="bg-white dark:bg-[#161615] rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-[#2a2a2a]">
        <!-- ENCABEZADO TICKET -->
        <div class="bg-indigo-600 p-8 text-white flex justify-between items-start">
            <div>
                <div class="text-3xl font-black uppercase tracking-tighter mb-1">COMPROBANTE</div>
                <div class="opacity-80 text-sm">Reserva Registrada: {{ $reservacion->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold">HOTEL POS</div>
                <div class="text-xs opacity-75">San Salvador, El Salvador</div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- DATOS CLIENTE Y HABITACION -->
            <div class="grid grid-cols-2 gap-8 border-b dark:border-[#2a2a2a] pb-8">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Cliente</h3>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $reservacion->cliente->nombre }} {{ $reservacion->cliente->apellido }}</div>
                    <div class="text-sm text-gray-500">DUI: {{ $reservacion->cliente->dui }}</div>
                    <div class="text-sm text-gray-500">{{ $reservacion->cliente->correo_electronico }}</div>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Habitación</h3>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">Número #{{ $reservacion->habitacion->numero_habitacion }}</div>
                    <div class="text-sm text-gray-500">Tipo: {{ ucfirst($reservacion->habitacion->tipo) }}</div>
                    <div class="text-sm text-gray-500">Estado Reserva: {{ ucfirst($reservacion->estado) }}</div>
                </div>
            </div>

            <!-- FECHAS -->
            <div class="grid grid-cols-3 gap-4 bg-gray-50 dark:bg-[#1C1C1B] p-6 rounded-2xl text-center">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase mb-1">Llegada</div>
                    <div class="text-lg font-black dark:text-white">{{ $reservacion->fecha_entrada->format('d M, Y') }}</div>
                </div>
                <div class="flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase mb-1">Salida</div>
                    <div class="text-lg font-black dark:text-white">{{ $reservacion->fecha_salida->format('d M, Y') }}</div>
                </div>
            </div>

            <!-- TOTAL -->
            <div class="flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                <div>
                    <div class="text-indigo-600 dark:text-indigo-400 font-bold uppercase text-xs">Total a Pagar</div>
                    <div class="text-sm text-gray-500">Incluye impuestos y precios por temporada</div>
                </div>
                <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400">
                    ${{ number_format($reservacion->precio_total, 2) }}
                </div>
            </div>

            @if($reservacion->notas)
            <div class="pt-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Notas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $reservacion->notas }}"</p>
            </div>
            @endif
        </div>
        
        <!-- FOOTER TICKET -->
        <div class="bg-gray-50 dark:bg-[#1C1C1B] p-6 text-center text-[10px] text-gray-400 uppercase tracking-widest">
            Gracias por su preferencia • Este documento es un comprobante de reserva interna
        </div>
    </div>
</div>

<style>
@media print {
    /* Ocultar elementos de navegación y de la app de forma definitiva (alta especificidad) */
    aside, header, nav, .flex.gap-2, a, button, svg, h1,
    aside.flex, header.flex, aside.flex-col, header.flex-col {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
    
    /* Reset de la estructura de la página para impresión continua */
    html, body, #app, main, body > div, main > div {
        background: white !important;
        color: black !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;
        overflow: visible !important;
        display: block !important;
        width: 100% !important;
    }

    /* Contenedor principal del ticket */
    #ticket-imprimible {
        background: white !important;
        color: black !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 4px !important;
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        display: block !important;
        font-family: 'Courier New', Courier, monospace !important;
        font-size: 11px !important;
        line-height: 1.3 !important;
    }

    /* Forzar que todos los textos del ticket sean negros y sin fondo */
    #ticket-imprimible * {
        color: black !important;
        background-color: transparent !important;
        box-shadow: none !important;
        text-shadow: none !important;
        font-family: 'Courier New', Courier, monospace !important;
    }

    /* Cabecera del ticket */
    #ticket-imprimible .bg-indigo-600 {
        border-bottom: 2px dashed black !important;
        padding: 8px 0 !important;
        margin-bottom: 12px !important;
        text-align: center !important;
        display: block !important;
        width: 100% !important;
    }

    #ticket-imprimible .text-3xl {
        font-size: 15px !important;
        font-weight: bold !important;
        letter-spacing: 1px !important;
        text-align: center !important;
        display: block !important;
        margin-bottom: 2px !important;
    }

    #ticket-imprimible .text-xl {
        font-size: 12px !important;
        font-weight: bold !important;
        text-align: center !important;
        display: block !important;
        margin-bottom: 2px !important;
    }

    #ticket-imprimible .text-right, 
    #ticket-imprimible .opacity-80, 
    #ticket-imprimible .text-xs {
        text-align: center !important;
        float: none !important;
        font-size: 9px !important;
        display: block !important;
        margin: 0 !important;
    }

    /* Cuerpo del ticket y espaciados */
    #ticket-imprimible .p-8, 
    #ticket-imprimible .p-6 {
        padding: 6px 2px !important;
    }

    #ticket-imprimible .space-y-8 > * + * {
        margin-top: 10px !important;
    }

    /* Grid de datos Cliente/Habitación a flujo vertical */
    #ticket-imprimible .grid-cols-2 {
        display: block !important;
        width: 100% !important;
        border-bottom: 1px dashed black !important;
        padding-bottom: 8px !important;
        margin-bottom: 8px !important;
    }

    #ticket-imprimible .grid-cols-2 > div {
        display: block !important;
        width: 100% !important;
        margin-bottom: 8px !important;
    }

    #ticket-imprimible .grid-cols-2 > div:last-child {
        margin-bottom: 0 !important;
    }

    #ticket-imprimible h3 {
        font-size: 9px !important;
        font-weight: bold !important;
        margin-bottom: 2px !important;
        text-transform: uppercase !important;
    }

    #ticket-imprimible .text-lg {
        font-size: 11px !important;
        font-weight: bold !important;
    }

    #ticket-imprimible .text-sm {
        font-size: 10px !important;
    }

    /* Fechas alineadas verticalmente sin columnas ni iconos */
    #ticket-imprimible .grid-cols-3 {
        display: block !important;
        width: 100% !important;
        border-bottom: 1px dashed black !important;
        padding: 6px 0 !important;
        margin-bottom: 8px !important;
    }

    #ticket-imprimible .grid-cols-3 > div {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        margin-bottom: 4px !important;
    }

    #ticket-imprimible .grid-cols-3 > div:nth-child(2) {
        display: none !important;
    }

    #ticket-imprimible .grid-cols-3 .text-xs {
        font-size: 9px !important;
        font-weight: bold !important;
        display: inline !important;
    }

    #ticket-imprimible .grid-cols-3 .text-xs::after {
        content: ": " !important;
    }

    #ticket-imprimible .grid-cols-3 .text-lg {
        font-size: 11px !important;
        display: inline !important;
        font-weight: normal !important;
    }

    /* Caja de Total a Pagar */
    #ticket-imprimible .bg-indigo-50, 
    #ticket-imprimible .dark\:bg-indigo-900\/20, 
    #ticket-imprimible .border-indigo-100 {
        border: 1px dashed black !important;
        padding: 6px !important;
        margin-top: 10px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-direction: row !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    #ticket-imprimible .text-indigo-600 {
        font-size: 10px !important;
        font-weight: bold !important;
    }

    #ticket-imprimible .text-4xl {
        font-size: 14px !important;
        font-weight: bold !important;
    }

    /* Sección de Notas */
    #ticket-imprimible .italic {
        font-size: 10px !important;
        margin-top: 4px !important;
    }

    /* Footer del ticket */
    #ticket-imprimible .bg-gray-50, 
    #ticket-imprimible .dark\:bg-\[\#1C1C1B\] {
        border-top: 2px dashed black !important;
        padding: 8px 0 !important;
        margin-top: 12px !important;
        font-size: 8px !important;
        text-align: center !important;
        display: block !important;
        width: 100% !important;
    }
}
</style>
@endsection

