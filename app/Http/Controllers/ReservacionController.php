<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreReservacionRequest;

class ReservacionController extends Controller
{
    public function index()
    {
        Gate::authorize('gestionar-reservaciones');
        $habitaciones = Habitacion::with('imagenes')->get();
        return view('reservaciones.index', compact('habitaciones'));
    }

    public function reservations()
    {
        Gate::authorize('gestionar-reservaciones');
        $reservaciones = Reservacion::with(['cliente', 'habitacion'])->latest()->get();
        return view('reservaciones.reservations', compact('reservaciones'));
    }

    public function create()
    {
        Gate::authorize('gestionar-reservaciones');
        $habitaciones = Habitacion::where('estado', 'disponible')->with(['imagenes', 'preciosTemporada'])->get();
        $clientes = Cliente::all();
        return view('reservaciones.create', compact('habitaciones', 'clientes'));
    }

    public function store(StoreReservacionRequest $request)
    {

        try {
            return DB::transaction(function () use ($request) {
                // Bloquear la habitación para evitar cambios concurrentes
                $habitacion = Habitacion::where('id', $request->habitacion_id)->lockForUpdate()->firstOrFail();

                // Nuevo algoritmo de solapamiento (Industria):
                // (Entrada < Nueva_Salida) AND (Salida > Nueva_Entrada)
                $overlap = Reservacion::where('habitacion_id', $request->habitacion_id)
                    ->whereIn('estado', ['confirmada', 'completada'])
                    ->where('fecha_entrada', '<', $request->fecha_salida)
                    ->where('fecha_salida', '>', $request->fecha_entrada)
                    ->exists();

                if ($overlap) {
                    return back()->withInput()->with('error', 'La habitación ya no está disponible para estas fechas (alguien más pudo haberla tomado).');
                }

                // Calcular precio total
                $precioTotal = Reservacion::calculateTotal($request->habitacion_id, $request->fecha_entrada, $request->fecha_salida);

                $reservacion = Reservacion::create([
                    'cliente_id' => $request->cliente_id,
                    'habitacion_id' => $request->habitacion_id,
                    'fecha_entrada' => $request->fecha_entrada,
                    'fecha_salida' => $request->fecha_salida,
                    'precio_total' => $precioTotal,
                    'estado' => 'confirmada',
                    'notas' => $request->notas,
                ]);

                // Sincronizar estado de la habitación de inmediato
                $habitacion->syncStatus();

                return redirect()->route('reservaciones.index')
                    ->with('success', 'Reserva #' . $reservacion->id . ' confirmada. Total: $' . number_format($precioTotal, 2));
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al procesar la reserva: ' . $e->getMessage());
        }
    }

    public function show(Reservacion $reservacion)
    {
        Gate::authorize('gestionar-reservaciones');
        return view('reservaciones.show', compact('reservacion'));
    }

    public function edit(Reservacion $reservacion)
    {
        Gate::authorize('gestionar-reservaciones');
        $habitaciones = Habitacion::all();
        $clientes = Cliente::all();
        return view('reservaciones.edit', compact('reservacion', 'habitaciones', 'clientes'));
    }

    public function update(Request $request, Reservacion $reservacion)
    {
        Gate::authorize('gestionar-reservaciones');
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'notas' => 'nullable|string',
        ]);

        $reservacion->update($request->only(['estado', 'notas']));

        return redirect()->route('reservaciones.index')
            ->with('success', 'Reserva actualizada correctamente');
    }

    public function destroy(Reservacion $reservacion)
    {
        Gate::authorize('gestionar-reservaciones');
        $reservacion->delete();
        return redirect()->route('reservaciones.index')
            ->with('success', 'Reserva eliminada correctamente');
    }
}

