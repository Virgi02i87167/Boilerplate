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
                    'metodo_pago' => $request->metodo_pago,
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

    public function procesarPago(Request $request, Reservacion $reservacion)
    {
        Gate::authorize('gestionar-reservaciones');

        $rules = [
            'tipo_documento' => 'required|string|in:ticket,credito_fiscal',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia',
        ];

        // Validaciones condicionales de Crédito Fiscal
        if ($request->tipo_documento === 'credito_fiscal') {
            $rules['razon_social'] = 'required|string|max:255';
            $rules['nrc'] = 'required|string|max:50';
            $rules['nit_dui'] = 'required|string|max:50';
            $rules['giro'] = 'required|string|max:255';
        }

        // Validaciones condicionales de Rastreabilidad Bancaria/Voucher
        if ($request->metodo_pago === 'tarjeta') {
            $rules['numero_referencia'] = 'required|string|max:100';
        } elseif ($request->metodo_pago === 'transferencia') {
            $rules['banco_destino'] = 'required|string|max:100';
            $rules['numero_comprobante'] = 'required|string|max:100';
        }

        $validated = $request->validate($rules);

        // Guardar información de pago y completar reservación
        $updateData = [
            'estado' => 'completada',
            'metodo_pago' => $validated['metodo_pago'],
            'tipo_documento' => $validated['tipo_documento'],
        ];

        if ($request->tipo_documento === 'credito_fiscal') {
            $updateData['razon_social'] = $validated['razon_social'];
            $updateData['nrc'] = $validated['nrc'];
            $updateData['nit_dui'] = $validated['nit_dui'];
            $updateData['giro'] = $validated['giro'];
        } else {
            $updateData['razon_social'] = null;
            $updateData['nrc'] = null;
            $updateData['nit_dui'] = null;
            $updateData['giro'] = null;
        }

        if ($request->metodo_pago === 'tarjeta') {
            $updateData['numero_referencia'] = $validated['numero_referencia'];
            $updateData['banco_destino'] = null;
            $updateData['numero_comprobante'] = null;
        } elseif ($request->metodo_pago === 'transferencia') {
            $updateData['numero_referencia'] = null;
            $updateData['banco_destino'] = $validated['banco_destino'];
            $updateData['numero_comprobante'] = $validated['numero_comprobante'];
        } else {
            $updateData['numero_referencia'] = null;
            $updateData['banco_destino'] = null;
            $updateData['numero_comprobante'] = null;
        }

        $reservacion->update($updateData);

        // Sincronizar el estado de la habitación de inmediato
        $reservacion->habitacion->syncStatus();

        return redirect()->route('reservaciones.show', $reservacion)
            ->with('success', 'Pago procesado y facturado con éxito. La reserva ha sido completada.');
    }
}

