<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidarComprobanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Oficinista']);
    }

    public function rules(): array
    {
        return [
            'comprobante_id'  => ['required', 'exists:comprobantes,id'],
            'estado'          => ['required', 'in:Aprobado,Rechazado'],
            'observaciones'   => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'comprobante_id.required' => 'El comprobante es requerido.',
            'estado.required'         => 'Debe seleccionar Aprobar o Rechazar.',
            'estado.in'               => 'Estado de validación inválido.',
        ];
    }
}
