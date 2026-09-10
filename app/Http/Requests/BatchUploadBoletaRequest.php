<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchUploadBoletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('gestionar-boletas');
    }

    public function rules(): array
    {
        return [
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'periodo_anio' => ['required', 'integer', 'min:2000', 'max:2099'],
            'tipo_periodo' => ['required', 'in:primera_quincena,segunda_quincena'],
            'archivos' => ['required', 'array', 'min:1'],
            'archivos.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
