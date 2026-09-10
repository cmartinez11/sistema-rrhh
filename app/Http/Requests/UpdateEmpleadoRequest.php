<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('gestionar-empleados');
    }

    public function rules(): array
    {
        $empleadoId = $this->route('empleado') ? $this->route('empleado')->id : $this->input('id');

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:20', Rule::unique('empleados', 'dni')->ignore($empleadoId)],
            'cargo_id' => ['required', 'exists:cargos,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'fecha_ingreso' => ['required', 'date'],
            'estado' => ['required', 'in:activo,inactivo'],
            'email' => ['required', 'email', 'max:255', Rule::unique('empleados', 'email')->ignore($empleadoId)],
            'telefono' => ['nullable', 'string', 'max:30'],
        ];
    }
}
