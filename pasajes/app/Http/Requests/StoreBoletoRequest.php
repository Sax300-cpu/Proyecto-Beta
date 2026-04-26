<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoletoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hoja_ruta_id'        => ['required', 'exists:hojas_ruta,id'],
            'asiento_id'          => ['required', 'exists:asientos,id'],
            'nombre_pasajero'     => ['required', 'string', 'max:255'],
            'cedula_pasajero'     => ['required', 'string', 'size:10'],
            'tipo_pasajero'       => ['required', 'in:Normal,Niño,Tercera Edad,Discapacitado'],
            'origen_abordaje'     => ['required', 'string', 'max:100'],
            'destino_desembarque' => ['required', 'string', 'max:100'],
            'fecha_nacimiento_pasajero' => [
                'required',
                'date',
                'before:' . now()->subYears(13)->format('Y-m-d'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'hoja_ruta_id.required'        => 'Debe seleccionar una hoja de ruta.',
            'asiento_id.required'           => 'Debe seleccionar un asiento.',
            'nombre_pasajero.required'      => 'El nombre del pasajero es obligatorio.',
            'cedula_pasajero.required'      => 'La cédula del pasajero es obligatoria.',
            'cedula_pasajero.size'          => 'La cédula debe tener exactamente 10 dígitos.',
            'tipo_pasajero.required'        => 'Debe indicar el tipo de pasajero.',
            'tipo_pasajero.in'              => 'El tipo de pasajero no es válido.',
            'origen_abordaje.required'      => 'El punto de abordaje es obligatorio.',
            'destino_desembarque.required'  => 'El destino de desembarque es obligatorio.',
            'fecha_nacimiento_pasajero.before' => 'El pasajero debe ser mayor de 13 años para comprar un boleto.',
        ];
    }
}
