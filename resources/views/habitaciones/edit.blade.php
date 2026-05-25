@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- CABECERA -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('habitaciones.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-[#A1A09A] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#1C1C1B] rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Editar Habitación: {{ $habitacion->numero_habitacion }}
        </h1>
    </div>

    <!-- ERRORES -->
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-bold">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CARD PRINCIPAL -->
    <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
        
        <div class="p-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-gray-50/50 dark:bg-[#1C1C1B]/50">
            <h2 class="text-sm font-bold text-gray-800 dark:text-[#EDEDEC] uppercase tracking-wider">Modificar Especificaciones y Galería</h2>
            <p class="text-xs text-gray-500 dark:text-[#A1A09A] mt-1">Edita los detalles de la habitación o gestiona las imágenes asignadas.</p>
        </div>

        <form action="{{ route('habitaciones.update', $habitacion) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Fila 1: Número y Tipo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Número -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Número de Habitación
                    </label>
                    <input type="text" name="numero_habitacion" value="{{ old('numero_habitacion', $habitacion->numero_habitacion) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Tipo de Habitación
                    </label>
                    <select name="tipo" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                        <option value="individual" {{ old('tipo', $habitacion->tipo) == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="familiar" {{ old('tipo', $habitacion->tipo) == 'familiar' ? 'selected' : '' }}>Familiar</option>
                        <option value="suite" {{ old('tipo', $habitacion->tipo) == 'suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                </div>
            </div>

            <!-- Fila 2: Precio -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                    Precio por Noche ($)
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-400 dark:text-[#A1A09A]">$</span>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio', $habitacion->precio) }}" required
                        class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                </div>
            </div>

            <!-- Fila 3: Descripción -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                    ¿Qué contiene la habitación? (Camas, baño, etc.)
                </label>
                <textarea name="descripcion" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">{{ old('descripcion', $habitacion->descripcion) }}</textarea>
            </div>

            <!-- Fila 4: Galería Actual Interactiva (Borrado AJAX) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-3">
                    Galería de Fotos Asignadas
                </label>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <!-- Imagen Principal -->
                    @if($habitacion->ruta_imagen)
                        <div class="relative rounded-xl overflow-hidden shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] group h-28 bg-gray-50 dark:bg-[#1C1C1B]">
                            <img src="{{ asset('storage/' . $habitacion->ruta_imagen) }}" alt="Principal" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-indigo-500 text-white text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Principal</span>
                            
                            <!-- Botón de eliminar principal -->
                            <button 
                                type="button"
                                class="delete-image-btn absolute top-2 right-2 p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow"
                                data-url="{{ route('habitaciones.destroyMainImagen', $habitacion) }}"
                                data-main="true"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- Imágenes Adicionales -->
                    @foreach($habitacion->imagenes as $img)
                        <div id="image-card-{{ $img->id }}" class="relative rounded-xl overflow-hidden shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] group h-28 bg-gray-50 dark:bg-[#1C1C1B]">
                            <img src="{{ asset('storage/' . $img->ruta_imagen) }}" alt="Adicional" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 bg-gray-800/80 text-[#EDEDEC] border border-[#3E3E3A] text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Galería</span>
                            
                            <!-- Botón de eliminar adicional -->
                            <button 
                                type="button"
                                class="delete-image-btn absolute top-2 right-2 p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow"
                                data-url="{{ route('habitaciones.destroyAdditionalImagen', $img) }}"
                                data-main="false"
                                data-card-id="image-card-{{ $img->id }}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Fila 5: Carga de fotos adicionales (AlpineJS Drag & Drop) -->
            <div x-data="imageUploader()" class="space-y-3">
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider">
                    Añadir Nuevas Fotos a la Galería
                </label>
                
                <!-- Área de Drag & Drop -->
                <div 
                    @dragover.prevent="dragOver = true"
                    @dragleave.prevent="dragOver = false"
                    @drop.prevent="handleDrop($event)"
                    @click="$refs.fileInput.click()"
                    :class="dragOver ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/10' : 'border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-indigo-400 dark:hover:border-indigo-500'"
                    class="border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all flex flex-col items-center justify-center gap-3 bg-gray-50/20 dark:bg-[#161615]/20 group relative"
                >
                    <input 
                        type="file" 
                        name="imagenes[]" 
                        x-ref="fileInput" 
                        @change="handleFileSelect($event)" 
                        accept="image/*" 
                        multiple 
                        class="hidden"
                    >
                    
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <div>
                        <span class="text-sm font-bold text-gray-800 dark:text-white block">Arrastra tus fotos adicionales aquí, o <span class="text-indigo-600 dark:text-indigo-400 underline decoration-2">búscalas</span></span>
                        <span class="text-xs text-gray-400 dark:text-[#A1A09A] block mt-1">Soporta múltiples imágenes (Máx. 2MB por foto)</span>
                    </div>
                </div>

                <!-- Grilla de Previsualizaciones Nuevas -->
                <div x-show="previews.length > 0" class="mt-4" x-transition>
                    <span class="text-xs font-bold text-gray-500 dark:text-[#A1A09A] uppercase tracking-wider block mb-2">Vista previa de las nuevas fotos seleccionadas</span>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative rounded-xl overflow-hidden shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] group h-28 bg-gray-50 dark:bg-[#1C1C1B]">
                                <img :src="preview.url" class="w-full h-full object-cover">
                                <span class="bg-green-600 text-white absolute top-2 left-2 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Nuevo</span>
                                
                                <!-- Botón de eliminar preview -->
                                <button 
                                    type="button"
                                    @click.stop="removeFile(index)"
                                    class="absolute top-2 right-2 p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Fila 6: Estado y Fecha de disponibilidad -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                <!-- Estado -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Estado
                    </label>
                    <select name="estado" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                        <option value="disponible" {{ old('estado', $habitacion->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="ocupada" {{ old('estado', $habitacion->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                        <option value="mantenimiento" {{ old('estado', $habitacion->estado) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                </div>

                <!-- Disponible desde -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Disponible Desde
                    </label>
                    <input type="date" name="disponible_desde" value="{{ old('disponible_desde', $habitacion->disponible_desde ? \Carbon\Carbon::parse($habitacion->disponible_desde)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <a href="{{ route('habitaciones.index') }}"
                    class="px-5 py-2.5 bg-white dark:bg-[#1C1C1B] border border-[#e3e3e0] dark:border-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] font-bold rounded-xl text-xs tracking-wider uppercase transition-colors hover:bg-gray-50 dark:hover:bg-[#252524]">
                    Volver al Listado
                </a>

                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs tracking-wider uppercase shadow-lg shadow-indigo-600/10 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V4a1 1 0 10-2 0v7.586l-1.293-1.293z" />
                        <path d="M5 17a2 2 0 01-2-2V7a2 2 0 012-2 1 1 0 010 2v8a1 1 0 001 1h8a1 1 0 001-1V7a1 1 0 112 0v8a2 2 0 01-2 2H5z" />
                    </svg>
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// Delegación de eventos para eliminar imágenes
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-image-btn');
    if (btn) {
        e.preventDefault();
        const url = btn.getAttribute('data-url');
        const isMain = btn.getAttribute('data-main') === 'true';
        const cardId = btn.getAttribute('data-card-id');
        deleteImage(url, isMain, cardId);
    }
});

// Manejo asíncrono para eliminar imágenes de la galería en caliente
function deleteImage(url, isMain, cardId = null) {
    const isDark = document.documentElement.classList.contains('dark');
    
    Swal.fire({
        title: '¿Eliminar fotografía?',
        text: isMain 
            ? 'Esta es la imagen principal. Si la eliminas, la siguiente imagen de la galería (si existe) se promocionará automáticamente a Principal.'
            : 'Esta imagen se eliminará permanentemente de la habitación.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-white border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl shadow-xl font-sans p-6 max-w-md w-11/12',
            title: 'text-xl font-bold text-gray-900 dark:text-white mt-4',
            htmlContainer: 'text-sm text-gray-500 dark:text-[#A1A09A] mt-2 mb-4',
            confirmButton: 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs tracking-wide uppercase transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-[#161615]',
            cancelButton: 'px-4 py-2 bg-white dark:bg-[#1C1C1B] border border-[#e3e3e0] dark:border-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] hover:bg-gray-50 dark:hover:bg-[#252524] font-bold rounded-lg text-xs tracking-wide uppercase transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-[#161615] mr-3'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isMain) {
                        // Si eliminamos la principal, recargamos la página para visualizar la nueva imagen principal autopromocionada
                        window.location.reload();
                    } else if (cardId) {
                        // Si es una imagen adicional, la removemos del DOM con suavidad
                        const card = document.getElementById(cardId);
                        if (card) {
                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            setTimeout(() => card.remove(), 300);
                        }
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Imagen eliminada de la galería',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl shadow-lg'
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar la imagen.',
                        customClass: {
                            popup: 'bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-white border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl shadow-xl'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la conexión.',
                    customClass: {
                        popup: 'bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-white border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl shadow-xl'
                    }
                });
            });
        }
    });
}

function imageUploader() {
    return {
        dragOver: false,
        previews: [],
        
        handleFileSelect(e) {
            this.addFiles(e.target.files);
        },
        
        handleDrop(e) {
            this.dragOver = false;
            if (e.dataTransfer.files) {
                this.addFiles(e.dataTransfer.files);
                this.$refs.fileInput.files = e.dataTransfer.files;
            }
        },
        
        addFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.startsWith('image/')) continue;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push({
                        url: e.target.result,
                        file: file
                    });
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeFile(index) {
            this.previews.splice(index, 1);
            
            const dt = new DataTransfer();
            const files = this.$refs.fileInput.files;
            
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            this.$refs.fileInput.files = dt.files;
        }
    }
}
</script>

@endsection
