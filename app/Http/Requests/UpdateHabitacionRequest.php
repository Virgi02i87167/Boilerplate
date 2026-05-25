<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('gestionar-habitaciones');
    }

    public function rules(): array
    {
        $habitacion = $this->route('habitacion');
        return [
            'numero_habitacion' => 'required|string|max:50|unique:habitaciones,numero_habitacion,' . $habitacion->id,
            'tipo' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|string|in:disponible,ocupada,mantenimiento',
            'disponible_desde' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'image|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_habitacion.unique' => 'Este número de habitación ya está en uso',
            'imagenes.*.image' => 'Cada archivo seleccionado debe ser una imagen válida (jpeg, png, webp, etc.)',
            'imagenes.*.max' => 'Las imágenes de la habitación no deben pesar más de 10 MB cada una',
        ];
    }
}

