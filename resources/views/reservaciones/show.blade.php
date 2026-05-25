@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ modalOpen: false, tipoDoc: 'ticket', metodoPago: 'efectivo' }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalle de Reserva #{{ $reservacion->id }}</h1>
        <div class="flex gap-2">
            @if($reservacion->estado === 'completada')
                <button onclick="window.print()" class="px-4 py-2 bg-gray-100 dark:bg-[#1C1C1B] dark:text-white rounded-lg hover:bg-gray-200 transition text-sm font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir
                </button>
            @else
                <button @click="modalOpen = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition text-sm font-bold flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Procesar Pago
                </button>
            @endif
            <a href="{{ route('reservaciones.listado') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm">Volver</a>
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
                    <div class="text-sm text-gray-500 flex items-center gap-1.5">
                        <span class="text-gray-400">Estado:</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold capitalize {{ $reservacion->estado === 'completada' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                            {{ ucfirst($reservacion->estado) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($reservacion->estado === 'completada')
            <!-- DETALLE DE FACTURACIÓN Y PAGO -->
            <div class="grid grid-cols-2 gap-8 border-b dark:border-[#2a2a2a] pb-8">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Comprobante Fiscal</h3>
                    <div class="text-base font-bold text-gray-900 dark:text-white mb-2">
                        {{ $reservacion->tipo_documento === 'credito_fiscal' ? '📄 Crédito Fiscal (DTE)' : '📄 Factura Simplificada (Ticket)' }}
                    </div>
                    @if($reservacion->tipo_documento === 'credito_fiscal')
                        <div class="text-xs text-gray-500 mt-2 space-y-1 bg-gray-50 dark:bg-[#1C1C1B] p-4 rounded-2xl border border-gray-100 dark:border-[#2a2a2a] billing-card">
                            <div><strong class="text-gray-700 dark:text-gray-300">Razón Social:</strong> {{ $reservacion->razon_social }}</div>
                            <div><strong class="text-gray-700 dark:text-gray-300">NRC:</strong> {{ $reservacion->nrc }}</div>
                            <div><strong class="text-gray-700 dark:text-gray-300">NIT/DUI:</strong> {{ $reservacion->nit_dui }}</div>
                            <div><strong class="text-gray-700 dark:text-gray-300">Giro:</strong> {{ $reservacion->giro }}</div>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Detalle de Pago</h3>
                    <div class="text-base font-bold text-gray-900 dark:text-white capitalize">
                        @if($reservacion->metodo_pago === 'efectivo')
                            💵 Efectivo
                        @elseif($reservacion->metodo_pago === 'tarjeta')
                            💳 Tarjeta
                        @elseif($reservacion->metodo_pago === 'transferencia')
                            🏛️ Transferencia
                        @endif
                    </div>
                    @if($reservacion->metodo_pago === 'tarjeta')
                        <div class="text-xs text-gray-500 mt-2 bg-gray-50 dark:bg-[#1C1C1B] p-3 rounded-2xl border border-gray-100 dark:border-[#2a2a2a] billing-card">
                            <strong class="text-gray-700 dark:text-gray-300">Voucher / Referencia:</strong> {{ $reservacion->numero_referencia }}
                        </div>
                    @elseif($reservacion->metodo_pago === 'transferencia')
                        <div class="text-xs text-gray-500 mt-2 space-y-1 bg-gray-50 dark:bg-[#1C1C1B] p-3 rounded-2xl border border-gray-100 dark:border-[#2a2a2a] billing-card">
                            <div><strong class="text-gray-700 dark:text-gray-300">Banco de Destino:</strong> {{ $reservacion->banco_destino }}</div>
                            <div><strong class="text-gray-700 dark:text-gray-300">Comprobante Nº:</strong> {{ $reservacion->numero_comprobante }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

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
                    <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                        <div>Incluye impuestos y tarifas por temporada</div>
                        @if($reservacion->estado === 'completada')
                            <div>Método de Pago: <span class="font-bold capitalize text-indigo-600 dark:text-indigo-400">{{ $reservacion->metodo_pago }}</span></div>
                        @else
                            <div class="text-amber-500 dark:text-amber-400 font-bold">⚠️ Pendiente de Pago</div>
                        @endif
                    </div>
                </div>
                <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400">
                    ${{ number_format($reservacion->precio_total, 2) }}
                </div>
            </div>

            @if($reservacion->notes)
            <div class="pt-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Notas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $reservacion->notes }}"</p>
            </div>
            @endif
        </div>
        
        <!-- FOOTER TICKET -->
        <div class="bg-gray-50 dark:bg-[#1C1C1B] p-6 text-center text-[10px] text-gray-400 uppercase tracking-widest">
            Gracias por su preferencia • Este documento es un comprobante de reserva interna
        </div>
    </div>

    <!-- MODAL DE PROCESAR PAGO (AlpineJS) -->
    <div x-show="modalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">
        
        <!-- Modal Card -->
        <div @click.away="modalOpen = false" 
            class="bg-white dark:bg-[#161615] rounded-3xl border border-gray-100 dark:border-[#2a2a2a] p-6 space-y-6 shadow-2xl overflow-y-auto max-h-[90vh]"
            style="width: 100%; max-width: 512px;"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="flex items-center justify-between border-b dark:border-[#2a2a2a] pb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>💵</span> Procesar Pago
                </h3>
                <button @click="modalOpen = false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-[#252524] text-gray-400 dark:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('reservaciones.procesarPago', $reservacion) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Tipo de Comprobante / Tabs -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tipo de Documento</label>
                    <div class="grid grid-cols-2 gap-2 bg-gray-50 dark:bg-[#1C1C1B] p-1.5 rounded-2xl border dark:border-[#2a2a2a]">
                        <button type="button" @click="tipoDoc = 'ticket'" 
                            :class="tipoDoc === 'ticket' ? 'bg-white dark:bg-[#252524] text-indigo-600 dark:text-indigo-400 shadow-sm font-extrabold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white font-medium'"
                            class="py-2.5 rounded-xl text-sm transition-all text-center">
                            Consumidor Final
                        </button>
                        <button type="button" @click="tipoDoc = 'credito_fiscal'" 
                            :class="tipoDoc === 'credito_fiscal' ? 'bg-white dark:bg-[#252524] text-indigo-600 dark:text-indigo-400 shadow-sm font-extrabold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white font-medium'"
                            class="py-2.5 rounded-xl text-sm transition-all text-center">
                            Crédito Fiscal
                        </button>
                    </div>
                    <input type="hidden" name="tipo_documento" :value="tipoDoc">
                </div>

                <!-- Campos de Crédito Fiscal (Condicionales) -->
                <div x-show="tipoDoc === 'credito_fiscal'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-4 p-4 border border-indigo-100 dark:border-indigo-900/30 bg-indigo-50/20 dark:bg-indigo-950/5 rounded-2xl">
                    
                    <div class="relative">
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">Razón Social <span class="text-red-500">*</span></label>
                        <input type="text" name="razon_social" :required="tipoDoc === 'credito_fiscal'" placeholder="Nombre de la empresa"
                            class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">NRC <span class="text-red-500">*</span></label>
                            <input type="text" name="nrc" :required="tipoDoc === 'credito_fiscal'" placeholder="000000-0"
                                class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">NIT / DUI <span class="text-red-500">*</span></label>
                            <input type="text" name="nit_dui" :required="tipoDoc === 'credito_fiscal'" placeholder="0000-000000-000-0"
                                class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">Giro / Actividad Económica <span class="text-red-500">*</span></label>
                        <select name="giro" :required="tipoDoc === 'credito_fiscal'"
                            class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition cursor-pointer">
                            <option value="">-- Buscar actividad (Hacienda) --</option>
                            <option value="Servicios de Hotelería y Alojamiento">Servicios de Hotelería y Alojamiento</option>
                            <option value="Servicios de Restaurante y Alimentación">Servicios de Restaurante y Alimentación</option>
                            <option value="Servicios Comerciales y Minoristas">Servicios Comerciales y Minoristas</option>
                            <option value="Servicios de Transporte y Turismo">Servicios de Transporte y Turismo</option>
                            <option value="Servicios Médicos y Farmacéuticos">Servicios Médicos y Farmacéuticos</option>
                            <option value="Actividad Comercial General">Actividad Comercial General</option>
                        </select>
                    </div>
                </div>

                <!-- Forma de Pago -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Forma de Pago</label>
                    <select name="metodo_pago" x-model="metodoPago" required
                        class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition cursor-pointer">
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta de Crédito / Débito</option>
                        <option value="transferencia">🏛️ Transferencia Bancaria</option>
                    </select>
                </div>

                <!-- Campo de Rastreabilidad para Tarjeta (Condicional) -->
                <div x-show="metodoPago === 'tarjeta'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-3 p-4 border border-indigo-100 dark:border-indigo-900/30 bg-indigo-50/20 dark:bg-indigo-950/5 rounded-2xl">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">Número de Referencia / Voucher <span class="text-red-500">*</span></label>
                        <input type="text" name="numero_referencia" :required="metodoPago === 'tarjeta'" placeholder="Ej. 12345678"
                            class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition">
                    </div>
                </div>

                <!-- Campos de Rastreabilidad para Transferencia (Condicional) -->
                <div x-show="metodoPago === 'transferencia'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-4 p-4 border border-indigo-100 dark:border-indigo-900/30 bg-indigo-50/20 dark:bg-indigo-950/5 rounded-2xl">
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">Banco de Destino <span class="text-red-500">*</span></label>
                        <select name="banco_destino" :required="metodoPago === 'transferencia'"
                            class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition cursor-pointer">
                            <option value="">-- Seleccionar Banco --</option>
                            <option value="Banco Agrícola">Banco Agrícola</option>
                            <option value="BAC Credomatic">BAC Credomatic</option>
                            <option value="Banco Cuscatlán">Banco Cuscatlán</option>
                            <option value="Banco Davivienda">Banco Davivienda</option>
                            <option value="Banco Promerica">Banco Promerica</option>
                            <option value="Banco de Fomento Agropecuario">Banco de Fomento Agropecuario</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">Número de Comprobante <span class="text-red-500">*</span></label>
                        <input type="text" name="numero_comprobante" :required="metodoPago === 'transferencia'" placeholder="Ej. TR-987654"
                            class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition">
                    </div>
                </div>

                <!-- Footer del Formulario -->
                <div class="flex items-center justify-end gap-3 border-t dark:border-[#2a2a2a] pt-4 mt-2">
                    <button type="button" @click="modalOpen = false"
                        class="px-5 py-2.5 bg-gray-100 dark:bg-[#252524] text-gray-700 dark:text-white font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-[#3E3E3A] transition text-sm">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg shadow-emerald-500/20 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        CONFIRMAR
                    </button>
                </div>
            </form>
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

    /* Ocultar bordes de tarjetas secundarias de facturas y auditoría en la impresión térmica */
    #ticket-imprimible .billing-card {
        border: none !important;
        padding: 4px 0 !important;
        margin: 0 !important;
        background: transparent !important;
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

