@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('reservaciones.index') }}" class="p-2 bg-gray-100 dark:bg-[#1C1C1B] rounded-lg hover:bg-gray-200 dark:hover:bg-[#2a2a2a] transition">
            <svg class="w-5 h-5 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Nueva Reservación</h1>
    </div>

    <form action="{{ route('reservaciones.store') }}" method="POST"
        x-data="{
            habitacionId: '{{ $preselectedId }}',
            fechaEntrada: '{{ old('fecha_entrada', date('Y-m-d')) }}',
            fechaSalida: '{{ old('fecha_salida', date('Y-m-d', strtotime('+1 day'))) }}',
            total: 0,
            noches: 0,
            loading: false,
            cotizarUrl: '{{ route('reservaciones.cotizar') }}',
            
            calcularTotal() {
                if (!this.habitacionId || !this.fechaEntrada || !this.fechaSalida) {
                    this.total = 0;
                    this.noches = 0;
                    return;
                }
                
                if (this.fechaEntrada >= this.fechaSalida) {
                    this.total = 0;
                    this.noches = 0;
                    return;
                }

                this.loading = true;
                
                let url = `${this.cotizarUrl}?habitacion_id=${this.habitacionId}&fecha_entrada=${this.fechaEntrada}&fecha_salida=${this.fechaSalida}`;
                
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.total = data.total;
                            this.noches = data.noches;
                        } else {
                            this.total = 0;
                            this.noches = 0;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.total = 0;
                        this.noches = 0;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            init() {
                this.calcularTotal();
                this.$watch('habitacionId', () => this.calcularTotal());
                this.$watch('fechaEntrada', () => this.calcularTotal());
                this.$watch('fechaSalida', () => this.calcularTotal());
            }
        }"
        class="grid grid-cols-1 md:grid-cols-3 gap-6"
    >
        @csrf

        <!-- COLUMNA IZQUIERDA: DATOS -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-gray-100 dark:border-[#2a2a2a] p-6 space-y-4">
                
                <!-- Cliente (Buscador Dinámico) -->
                <div x-data="{
                    open: false,
                    search: '',
                    selectedId: '{{ old('cliente_id') }}',
                    selectedName: '{{ old('cliente_id') && $clientes->find(old('cliente_id')) ? $clientes->find(old('cliente_id'))->nombre . ' ' . $clientes->find(old('cliente_id'))->apellido . ' (' . $clientes->find(old('cliente_id'))->dui . ')' : '' }}',
                    clients: {{ $clientes->map(fn($c) => ['id' => $c->id, 'name' => $c->nombre . ' ' . $c->apellido, 'dui' => $c->dui])->toJson() }}
                }" class="relative">
                    <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2 tracking-tight">Cliente</label>
                    
                    <div @click="open = !open" 
                        class="w-full p-2.5 border rounded-xl dark:bg-[#1C1C1B] dark:border-[#3E3E3A] dark:text-white cursor-pointer flex justify-between items-center transition hover:border-indigo-400 group"
                        :class="open ? 'ring-2 ring-indigo-500 border-transparent' : 'border-gray-200 dark:border-[#3E3E3A]'">
                        <span x-text="selectedName || 'Seleccione un cliente...'" :class="!selectedName && 'text-gray-400'"></span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <input type="hidden" name="cliente_id" :value="selectedId" required>

                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        @click.away="open = false" 
                        class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1C1C1B] border dark:border-[#3E3E3A] rounded-2xl shadow-2xl overflow-hidden">
                        
                        <div class="p-3 border-b dark:border-[#3E3E3A] bg-gray-50/50 dark:bg-[#252524]/50">
                            <div class="relative">
                                <input type="text" x-model="search" placeholder="Buscar por nombre o DUI..." autofocus
                                    class="w-full pl-9 pr-4 py-2 bg-white dark:bg-[#1C1C1B] border border-gray-200 dark:border-[#3E3E3A] rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm dark:text-white outline-none transition">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="client in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()) || c.dui.includes(search))" :key="client.id">
                                <div @click="selectedId = client.id; selectedName = client.name + ' (' + client.dui + ')'; open = false; search = ''"
                                    class="px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 cursor-pointer transition flex flex-col border-b last:border-0 border-gray-50 dark:border-[#252524]">
                                    <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="client.name"></span>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium" x-text="'DUI: ' + client.dui"></span>
                                </div>
                            </template>
                            
                            <div x-show="clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()) || c.dui.includes(search)).length === 0"
                                class="px-4 py-10 text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 text-sm">No se encontraron clientes que coincidan.</p>
                            </div>
                        </div>
                    </div>
                    @error('cliente_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Habitación (Selección por Cards / Confirmación) -->
                @php
                    $preselectedId = request('habitacion_id', old('habitacion_id'));
                    $selectedRoom = $preselectedId ? $habitaciones->firstWhere('id', $preselectedId) : null;
                    $preciosEspeciales = $selectedRoom ? $selectedRoom->preciosTemporada
                        ->where('fecha_fin', '>=', now()->format('Y-m-d'))
                        ->values() : collect();
                @endphp

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block font-bold text-gray-700 dark:text-gray-300 text-lg">
                            {{ $selectedRoom ? 'Habitación Seleccionada' : 'Seleccione una Habitación Disponible' }}
                        </label>
                        @if($selectedRoom)
                            <a href="{{ route('reservaciones.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Cambiar Habitación
                            </a>
                        @endif
                    </div>
                    
                    <input type="hidden" name="habitacion_id" :value="habitacionId" required>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @if($selectedRoom)
                            <!-- Mostrar solo la habitación preseleccionada de forma elegante y estática con carrusel -->
                            <div class="border-2 border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10 rounded-2xl overflow-hidden shadow-sm flex flex-col sm:flex-row h-auto min-h-[160px]">
                                
                                <!-- Carrusel en Reserva -->
                                <div x-data="{ 
                                    current: 0, 
                                    images: [
                                        @if($selectedRoom->ruta_imagen)
                                            '{{ asset('storage/' . $selectedRoom->ruta_imagen) }}'
                                        @else
                                            '{{ asset('img/no-room.jpg') }}'
                                        @endif
                                        @if($selectedRoom->imagenes->isNotEmpty())
                                            @foreach($selectedRoom->imagenes as $img)
                                                , '{{ asset('storage/' . $img->ruta_imagen) }}'
                                            @endforeach
                                        @endif
                                    ]
                                }" class="relative w-full sm:w-56 h-40 sm:h-auto shrink-0 overflow-hidden group">
                                    
                                    <!-- Contenedor de Imagen -->
                                    <div class="w-full h-full relative">
                                        <template x-for="(img, idx) in images" :key="idx">
                                            <img 
                                                :src="img" 
                                                x-show="current === idx"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                class="absolute inset-0 w-full h-full object-cover"
                                            >
                                        </template>
                                    </div>

                                    <div class="absolute top-2 left-2 px-2 py-1 bg-indigo-600 rounded-lg text-[10px] font-bold text-white uppercase z-10">
                                        {{ $selectedRoom->tipo }}
                                    </div>

                                    <!-- Controles del Carrusel (Flechas en Hover) -->
                                    <template x-if="images.length > 1">
                                        <div>
                                            <!-- Botón Anterior (Izquierda) -->
                                            <button 
                                                type="button"
                                                x-show="current > 0"
                                                @click.stop.prevent="current--"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-white/90 dark:bg-[#161615]/90 border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center text-gray-800 dark:text-white shadow hover:scale-105 transition-all opacity-0 group-hover:opacity-100 duration-200 z-10"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Botón Siguiente (Derecha) -->
                                            <button 
                                                type="button"
                                                x-show="current < images.length - 1"
                                                @click.stop.prevent="current++"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-white/90 dark:bg-[#161615]/90 border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center text-gray-800 dark:text-white shadow hover:scale-105 transition-all opacity-0 group-hover:opacity-100 duration-200 z-10"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Puntos Indicadores (Dots) -->
                                    <template x-if="images.length > 1">
                                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex items-center gap-1 z-10 bg-black/35 px-2 py-0.5 rounded-full backdrop-blur-[2px]">
                                            <template x-for="(img, idx) in images" :key="idx">
                                                <span 
                                                    :class="current === idx ? 'bg-white scale-110' : 'bg-white/50'"
                                                    class="w-1 h-1 rounded-full transition-all duration-200"
                                                ></span>
                                            </template>
                                        </div>
                                    </template>

                                </div>

                                <!-- Detalles de la habitación preseleccionada -->
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h4 class="font-bold text-gray-900 dark:text-white text-lg">Habitación #{{ $selectedRoom->numero_habitacion }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-relaxed line-clamp-1 mt-0.5">
                                                    {{ $selectedRoom->descripcion ?? 'Disfrute de una estancia placentera con todas las comodidades.' }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-bold tracking-wider">Precio Base</span>
                                                <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">${{ number_format($selectedRoom->precio, 2) }} <span class="text-xs font-normal text-gray-500">noche</span></span>
                                            </div>
                                        </div>

                                        <!-- Tarifas de Temporada Especiales -->
                                        @if($preciosEspeciales->isNotEmpty())
                                            <div class="mt-3 p-3 bg-red-50/50 dark:bg-red-950/10 border border-red-100 dark:border-red-900/30 rounded-xl">
                                                <span class="text-[10px] font-bold text-[#E25C3E] dark:text-[#F38A75] uppercase tracking-wider block mb-1.5 flex items-center gap-1">
                                                    <span>🔥</span> Tarifas Especiales Configuradas:
                                                </span>
                                                <div class="space-y-1">
                                                    @foreach($preciosEspeciales as $precioEsp)
                                                        <div class="flex items-center justify-between text-xs text-gray-700 dark:text-gray-300">
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-[#E25C3E]"></span>
                                                                <span class="font-bold text-[#E25C3E] dark:text-[#F38A75]">{{ $precioEsp->descripcion }}</span>
                                                                <span class="text-gray-400 dark:text-[#6E6E6A]">({{ $precioEsp->fecha_inicio->format('d/m') }} al {{ $precioEsp->fecha_fin->format('d/m') }})</span>
                                                            </div>
                                                            <span class="font-extrabold text-gray-900 dark:text-white">${{ number_format($precioEsp->precio, 2) }} / noche</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mt-3 sm:mt-2">
                                        Confirmada para Reserva Inmediata
                                    </div>
                                </div>

                            </div>
                        @else
                            <!-- Mostrar listado completo si no hay preselección -->
                            @forelse($habitaciones as $habitacion)
                                <div 
                                    @click="habitacionId = '{{ $habitacion->id }}'"
                                    :class="habitacionId == '{{ $habitacion->id }}' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-gray-200 dark:border-[#2a2a2a] hover:border-indigo-300'"
                                    class="cursor-pointer group bg-white dark:bg-[#1C1C1B] border rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1 shadow-sm"
                                >
                                    <div class="relative h-32 overflow-hidden">
                                        <img src="{{ $habitacion->ruta_imagen ? asset('storage/' . $habitacion->ruta_imagen) : asset('img/no-room.jpg') }}" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute top-2 right-2 px-2 py-1 bg-white/90 dark:bg-black/80 rounded-lg text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format($habitacion->precio, 2) }}
                                        </div>
                                        <div class="absolute bottom-2 left-2 px-2 py-1 bg-indigo-600 rounded-lg text-[10px] font-bold text-white uppercase">
                                            {{ $habitacion->tipo }}
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-bold text-gray-900 dark:text-white">Habitación #{{ $habitacion->numero_habitacion }}</h4>
                                            <div x-show="habitacionId == '{{ $habitacion->id }}'" class="text-indigo-500">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 line-clamp-1 mt-1">
                                            {{ $habitacion->descripcion ?? 'Sin descripción disponible.' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full p-8 text-center bg-gray-50 dark:bg-[#1C1C1B] rounded-2xl border-2 border-dashed border-gray-200 dark:border-[#2a2a2a]">
                                    <p class="text-gray-500">No hay habitaciones disponibles en este momento.</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                    @error('habitacion_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">Registro</label>
                        <input type="date" name="fecha_entrada" x-model="fechaEntrada" required
                            class="w-full p-2.5 border rounded-xl dark:bg-[#1C1C1B] dark:border-[#3E3E3A] dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">Salida</label>
                        <input type="date" name="fecha_salida" x-model="fechaSalida" required
                            class="w-full p-2.5 border rounded-xl dark:bg-[#1C1C1B] dark:border-[#3E3E3A] dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>

                <!-- Método de Pago -->
                <div>
                    <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">Método de Pago</label>
                    <select name="metodo_pago" required
                        class="w-full p-2.5 border border-gray-200 dark:border-[#3E3E3A] rounded-xl dark:bg-[#1C1C1B] dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition cursor-pointer">
                        <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                        <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>💳 Tarjeta de Crédito / Débito</option>
                        <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>🏛️ Transferencia Bancaria</option>
                    </select>
                    @error('metodo_pago') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Notas -->
                <div>
                    <label class="block font-bold text-gray-700 dark:text-gray-300 mb-2">Notas Especiales</label>
                    <textarea name="notas" rows="3" class="w-full p-2.5 border rounded-xl dark:bg-[#1C1C1B] dark:border-[#3E3E3A] dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ old('notas') }}</textarea>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: RESUMEN Y BOTÓN -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-2xl shadow-lg p-6 text-white space-y-4">
                <h3 class="font-bold text-lg border-b border-indigo-400 pb-2">Resumen de Reserva</h3>
                
                <div class="space-y-3 text-sm">
                    <div x-show="loading" class="animate-pulse space-y-2 py-2">
                        <div class="h-4 bg-indigo-500/50 rounded w-3/4"></div>
                        <div class="h-6 bg-indigo-500/50 rounded w-1/2"></div>
                    </div>
                    <div x-show="!loading && total > 0" class="divide-y divide-indigo-400/30">
                        <div class="pb-2 flex justify-between items-center">
                            <span class="opacity-80">Noches:</span>
                            <span class="font-bold text-base" x-text="noches"></span>
                        </div>
                        <div class="pt-2 flex justify-between items-end">
                            <span class="opacity-80">Total Estimado:</span>
                            <span class="text-2xl font-black text-white" x-text="'$' + parseFloat(total).toFixed(2)"></span>
                        </div>
                    </div>
                    <div x-show="!loading && !total" class="opacity-80 space-y-1.5">
                        <p>• Selecciona habitación y fechas válidas.</p>
                        <p>• Las tarifas por temporada se aplicarán automáticamente.</p>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-white text-indigo-600 rounded-xl font-bold uppercase tracking-wider hover:bg-gray-100 transition shadow-md">
                    Confirmar Reserva
                </button>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-2xl p-6 border border-gray-100 dark:border-[#2a2a2a] text-sm text-gray-500">
                <p>⚠️ Asegúrese de que las fechas sean correctas. El sistema validará si la habitación ya está ocupada.</p>
            </div>
        </div>
    </form>
</div>
@endsection

