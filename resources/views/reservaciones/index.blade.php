@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Catálogo de Habitaciones</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Seleccione una habitación para iniciar una nueva reservación.</p>
        </div>
        <a href="{{ route('reservaciones.listado') }}"
            class="px-5 py-2.5 bg-gray-100 dark:bg-[#1C1C1B] text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 transition text-sm font-bold shadow-sm border border-gray-200 dark:border-[#3E3E3A]">
            Ver Listado de Reservas
        </a>
    </div>

    <!-- LISTADO DE HABITACIONES (Airbnb Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($habitaciones as $habitacion)
        <a href="{{ route('reservaciones.create', ['habitacion_id' => $habitacion->id]) }}" class="group cursor-pointer block">
            <!-- Imagen con Carrusel AlpineJS -->
            <div x-data="{ 
                current: 0, 
                images: [
                    @if($habitacion->ruta_imagen)
                        '{{ asset('storage/' . $habitacion->ruta_imagen) }}'
                    @else
                        '{{ asset('img/no-room.jpg') }}'
                    @endif
                    @if($habitacion->imagenes->isNotEmpty())
                        @foreach($habitacion->imagenes as $img)
                            , '{{ asset('storage/' . $img->ruta_imagen) }}'
                        @endforeach
                    @endif
                ]
            }" class="relative aspect-square overflow-hidden rounded-2xl mb-4 shadow-md transition-all duration-300 group-hover:shadow-xl">
                
                <!-- Contenedor de Imagen -->
                <div class="w-full h-full relative">
                    <template x-for="(img, idx) in images" :key="idx">
                        <img 
                            :src="img" 
                            x-show="current === idx"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        >
                    </template>
                </div>

                <!-- Badge -->
                <div class="absolute top-4 left-4 bg-white/95 dark:bg-black/90 backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-lg z-10">
                    <span class="text-[11px] font-extrabold text-gray-900 dark:text-white uppercase tracking-widest">
                        {{ $habitacion->estado == 'disponible' ? 'Disponible' : 'Ocupado' }}
                    </span>
                </div>

                <!-- Heart Icon (Decoration) -->
                <div class="absolute top-4 right-4 text-white/90 hover:text-red-500 transition-colors drop-shadow-lg z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>

                <!-- Controles del Carrusel (Flechas en Hover) -->
                <template x-if="images.length > 1">
                    <div>
                        <!-- Botón Anterior (Izquierda) -->
                        <button 
                            type="button"
                            x-show="current > 0"
                            @click.stop.prevent="current--"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/90 dark:bg-[#161615]/90 border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center text-gray-800 dark:text-white shadow hover:scale-105 transition-all opacity-0 group-hover:opacity-100 duration-200 z-10"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Botón Siguiente (Derecha) -->
                        <button 
                            type="button"
                            x-show="current < images.length - 1"
                            @click.stop.prevent="current++"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/90 dark:bg-[#161615]/90 border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center text-gray-800 dark:text-white shadow hover:scale-105 transition-all opacity-0 group-hover:opacity-100 duration-200 z-10"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </template>

                <!-- Puntos Indicadores (Dots) -->
                <template x-if="images.length > 1">
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10 bg-black/35 px-2.5 py-1 rounded-full backdrop-blur-[2px]">
                        <template x-for="(img, idx) in images" :key="idx">
                            <span 
                                :class="current === idx ? 'bg-white scale-110' : 'bg-white/50'"
                                class="w-1.5 h-1.5 rounded-full transition-all duration-200"
                            ></span>
                        </template>
                    </div>
                </template>

            </div>

            <!-- Detalles -->
            <div class="space-y-1.5 px-1">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">Habitación #{{ $habitacion->numero_habitacion }}</h3>
                    <div class="flex items-center gap-1.5 text-sm">
                        <span class="text-yellow-400">★</span>
                        <span class="font-bold text-gray-900 dark:text-white">5.0</span>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 font-medium">{{ $habitacion->tipo }} • {{ $habitacion->descripcion ?? 'Confort y elegancia asegurada' }}</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Sistema de reserva inmediata</p>
                <div class="pt-1.5 flex items-baseline gap-1">
                    <span class="font-black text-gray-900 dark:text-white text-lg">${{ number_format($habitacion->precio, 2) }}</span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm font-bold">noche</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection