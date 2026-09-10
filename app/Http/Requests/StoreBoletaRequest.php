<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('gestionar-boletas');
    }

    public function rules(): array
    {
        return [
            'empleado_id' => ['required', 'exists:empleados,id'],
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'periodo_anio' => ['required', 'integer', 'min:2000', 'max:2099'],
            'tipo_periodo' => ['required', 'in:primera_quincena,segunda_quincena'],
            'archivo_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'], // Max 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'archivo_pdf.mimes' => 'El archivo adjunto debe ser de tipo PDF.',
            'archivo_pdf.max' => 'El tamaño máximo del archivo PDF es de 10 MB.',
        ];
    }
}
