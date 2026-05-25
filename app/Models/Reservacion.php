<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservacion extends Model
{
    use HasFactory;

    protected $table = 'reservaciones';

    protected $fillable = [
        'cliente_id',
        'habitacion_id',
        'fecha_entrada',
        'fecha_salida',
        'precio_total',
        'estado',
        'metodo_pago',
        'tipo_documento',
        'razon_social',
        'nrc',
        'nit_dui',
        'giro',
        'numero_referencia',
        'banco_destino',
        'numero_comprobante',
        'notas'
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida' => 'date',
        'precio_total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'habitacion_id');
    }

    /**
     * Calcula el precio total basado en el precio base y temporadas.
     */
    public static function calculateTotal($habitacionId, $fechaEntrada, $fechaSalida)
    {
        $habitacion = Habitacion::findOrFail($habitacionId);
        $start = Carbon::parse($fechaEntrada);
        $end = Carbon::parse($fechaSalida);
        $total = 0;

        // Obtener todos los precios estacionales relevantes en una sola consulta
        $preciosTemporada = $habitacion->preciosTemporada()
            ->where('fecha_inicio', '<=', $end->format('Y-m-d'))
            ->where('fecha_fin', '>=', $start->format('Y-m-d'))
            ->get();

        // Iterar por cada noche (desde start hasta antes de end)
        for ($date = $start->copy(); $date->lt($end); $date->addDay()) {
            $currentDateStr = $date->format('Y-m-d');
            
            // Buscar en la colección cargada si hay un precio de temporada para esta fecha
            $seasonal = $preciosTemporada->first(function ($price) use ($currentDateStr) {
                return $price->fecha_inicio <= $currentDateStr && $price->fecha_fin >= $currentDateStr;
            });

            if ($seasonal) {
                $total += $seasonal->precio;
            } else {
                $total += $habitacion->precio;
            }
        }

        return $total;
    }
}

