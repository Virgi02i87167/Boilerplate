<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Models\ImagenHabitacion;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreHabitacionRequest;
use App\Http\Requests\UpdateHabitacionRequest;

class HabitacionController extends Controller
{
    // LISTAR
    public function index()
    {
        Gate::authorize('gestionar-habitaciones');

        $habitaciones = Habitacion::all();

        return view('habitaciones.index', compact('habitaciones'));
    }

    // FORM CREAR
    public function create()
    {
        Gate::authorize('gestionar-habitaciones');

        return view('habitaciones.create');
    }

    // GUARDAR
    public function store(StoreHabitacionRequest $request)
    {
        $data = $request->validated();
        $data['estado'] = 'disponible';

        $habitacion = Habitacion::create($data);

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $ruta = $imagen->store('habitaciones', 'public');
                
                // La primera imagen será la principal
                if ($index === 0) {
                    $habitacion->update(['ruta_imagen' => $ruta]);
                } else {
                    $habitacion->imagenes()->create(['ruta_imagen' => $ruta]);
                }
            }
        }

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación registrada correctamente');
    }

    // FORM EDITAR
    public function edit(Habitacion $habitacion)
    {
        Gate::authorize('gestionar-habitaciones');
        $habitacion->load('imagenes');

        return view('habitaciones.edit', compact('habitacion'));
    }

    // ACTUALIZAR
    public function update(UpdateHabitacionRequest $request, Habitacion $habitacion)
    {
        $data = $request->validated();
        
        $habitacion->update($data);

        // Subir nuevas imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $ruta = $imagen->store('habitaciones', 'public');
                
                // Si la habitación no tiene imagen principal, la primera subida se asigna como principal
                if (!$habitacion->ruta_imagen && $index === 0) {
                    $habitacion->update(['ruta_imagen' => $ruta]);
                } else {
                    $habitacion->imagenes()->create(['ruta_imagen' => $ruta]);
                }
            }
        }

        // Autopromoción: Si la imagen principal sigue siendo nula pero hay imágenes en la galería,
        // promovemos la primera de la galería como principal.
        if (!$habitacion->ruta_imagen) {
            $siguiente = $habitacion->imagenes()->first();
            if ($siguiente) {
                $habitacion->update(['ruta_imagen' => $siguiente->ruta_imagen]);
                $siguiente->delete();
            }
        }

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación actualizada correctamente');
    }

    // ELIMINAR
    public function destroy(Habitacion $habitacion)
    {
        Gate::authorize('gestionar-habitaciones');

        if ($habitacion->reservaciones()->exists()) {
            return redirect()->route('habitaciones.index')
                ->with('error', 'No se puede eliminar la habitación porque tiene reservaciones registradas.');
        }

        // Eliminar imagen principal físicamente
        if ($habitacion->ruta_imagen) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($habitacion->ruta_imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($habitacion->ruta_imagen);
            }
        }

        // Eliminar imágenes adicionales físicamente
        foreach ($habitacion->imagenes as $img) {
            if ($img->ruta_imagen) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($img->ruta_imagen)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($img->ruta_imagen);
                }
            }
        }

        $habitacion->delete();

        return redirect()->route('habitaciones.index')
            ->with('success', 'Habitación eliminada correctamente');
    }

    // ELIMINAR IMAGEN PRINCIPAL (AJAX)
    public function destroyMainImagen(Habitacion $habitacion)
    {
        Gate::authorize('gestionar-habitaciones');

        if ($habitacion->ruta_imagen) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($habitacion->ruta_imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($habitacion->ruta_imagen);
            }

            // Buscar si hay otra imagen para promocionar
            $siguiente = $habitacion->imagenes()->first();
            if ($siguiente) {
                $habitacion->update(['ruta_imagen' => $siguiente->ruta_imagen]);
                $siguiente->delete();
            } else {
                $habitacion->update(['ruta_imagen' => null]);
            }
        }

        return response()->json(['success' => true]);
    }

    // ELIMINAR IMAGEN ADICIONAL (AJAX)
    public function destroyAdditionalImagen(ImagenHabitacion $imagen)
    {
        Gate::authorize('gestionar-habitaciones');

        if ($imagen->ruta_imagen) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagen->ruta_imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagen->ruta_imagen);
            }
        }

        $imagen->delete();

        return response()->json(['success' => true]);
    }
}