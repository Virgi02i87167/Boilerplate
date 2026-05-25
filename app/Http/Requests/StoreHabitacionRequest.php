<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitacionRequest extends FormRequest
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
        return [
            'numero_habitacion' => 'required|string|max:50|unique:habitaciones,numero_habitacion',
            'tipo' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'image|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_habitacion.unique' => 'Este número de habitación ya está registrado',
            'numero_habitacion.required' => 'El número de habitación es obligatorio',
            'precio.required' => 'El precio es obligatorio',
            'imagenes.*.image' => 'Cada archivo seleccionado debe ser una imagen válida (jpeg, png, webp, etc.)',
            'imagenes.*.max' => 'Las imágenes de la habitación no deben pesar más de 10 MB cada una',
        ];
    }
}

