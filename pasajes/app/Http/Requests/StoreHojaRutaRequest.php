<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHojaRutaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Oficinista']);
    }

    public function rules(): array
    {
        return [
            'frecuencia_id' => ['required', 'exists:frecuencias,id'],
            'bus_id'        => [
                'required',
                'exists:buses,id',
                // Un bus no puede tener dos hojas de ruta el mismo día para la misma frecuencia
                'unique:hojas_ruta,bus_id,NULL,id,frecuencia_id,' . $this->frecuencia_id . ',fecha,' . $this->fecha,
            ],
            'chofer_id'     => ['nullable', 'exists:users,id'],
            'fecha'         => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'frecuencia_id.required' => 'Debe seleccionar una frecuencia.',
            'bus_id.required'        => 'Debe seleccionar un bus.',
            'bus_id.unique'          => 'Este bus ya tiene una hoja de ruta para esa frecuencia en la fecha indicada.',
            'fecha.required'         => 'La fecha es obligatoria.',
            'fecha.after_or_equal'   => 'La fecha no puede ser en el pasado.',
        ];
    }
}
