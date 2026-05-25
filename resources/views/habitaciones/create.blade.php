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
            Registrar Nueva Habitación
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
            <h2 class="text-sm font-bold text-gray-800 dark:text-[#EDEDEC] uppercase tracking-wider">Datos Técnicos y Galería</h2>
            <p class="text-xs text-gray-500 dark:text-[#A1A09A] mt-1">Ingresa las especificaciones y fotografías de la nueva habitación.</p>
        </div>

        <form action="{{ route('habitaciones.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Fila 1: Número y Tipo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Número -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Número de Habitación
                    </label>
                    <input type="text" name="numero_habitacion" value="{{ old('numero_habitacion') }}" placeholder="Ej: 101, A-12" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-[#6E6E6A]">
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                        Tipo de Habitación
                    </label>
                    <select name="tipo" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                        <option value="individual" {{ old('tipo') == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="familiar" {{ old('tipo') == 'familiar' ? 'selected' : '' }}>Familiar</option>
                        <option value="suite" {{ old('tipo') == 'suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                </div>
            </div>

            <!-- Fila 2: Precio -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                    Precio Base por Noche ($)
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-400 dark:text-[#A1A09A]">$</span>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" placeholder="0.00" required
                        class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all">
                </div>
            </div>

            <!-- Fila 3: Descripción (Contenido) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider mb-2">
                    ¿Qué contiene la habitación? (Camas, baño, etc.)
                </label>
                <textarea name="descripcion" rows="3" placeholder="Ej: 2 camas matrimoniales, aire acondicionado, baño privado, TV por cable..."
                    class="w-full px-4 py-3 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1C1C1B] text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-[#161615] outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-[#6E6E6A]">{{ old('descripcion') }}</textarea>
            </div>

            <!-- Fila 4: Fotografía Premium (AlpineJS Drag & Drop) -->
            <div x-data="imageUploader()" class="space-y-3">
                <label class="block text-xs font-bold text-gray-700 dark:text-[#A1A09A] uppercase tracking-wider">
                    Galería de Fotos
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
                        <span class="text-sm font-bold text-gray-800 dark:text-white block">Arrastra tus fotos aquí, o <span class="text-indigo-600 dark:text-indigo-400 underline decoration-2">búscalas</span></span>
                        <span class="text-xs text-gray-400 dark:text-[#A1A09A] block mt-1">Soporta múltiples imágenes (Máx. 2MB por foto)</span>
                    </div>
                </div>

                <!-- Grilla de Previsualizaciones -->
                <div x-show="previews.length > 0" class="mt-4" x-transition>
                    <span class="text-xs font-bold text-gray-500 dark:text-[#A1A09A] uppercase tracking-wider block mb-2">Vista previa de las fotos seleccionadas</span>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative rounded-xl overflow-hidden shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] group h-28 bg-gray-50 dark:bg-[#1C1C1B]">
                                <img :src="preview.url" class="w-full h-full object-cover">
                                
                                <!-- Etiquetas -->
                                <span 
                                    x-text="index === 0 ? 'Principal' : 'Galería'"
                                    :class="index === 0 ? 'bg-indigo-500 text-white' : 'bg-[#1C1C1B]/80 text-[#EDEDEC] border border-[#3E3E3A]'"
                                    class="absolute top-2 left-2 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider"
                                ></span>
                                
                                <!-- Botón de eliminar -->
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

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <a href="{{ route('habitaciones.index') }}"
                    class="px-5 py-2.5 bg-white dark:bg-[#1C1C1B] border border-[#e3e3e0] dark:border-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] font-bold rounded-xl text-xs tracking-wider uppercase transition-colors hover:bg-gray-50 dark:hover:bg-[#252524]">
                    Volver al Listado
                </a>

                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs tracking-wider uppercase shadow-lg shadow-indigo-600/10 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Registrar Habitación
                </button>
            </div>

        </form>
    </div>
</div>

<script>
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
                
                // Sincronizar archivos al input de archivos
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
            
            // Reconstruir FileList en el input
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