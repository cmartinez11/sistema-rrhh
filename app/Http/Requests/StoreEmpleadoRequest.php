<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('gestionar-empleados');
    }

    public function rules(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:20', 'unique:empleados,dni'],
            'cargo_id' => ['required', 'exists:cargos,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'fecha_ingreso' => ['required', 'date'],
            'estado' => ['required', 'in:activo,inactivo'],
            'email' => ['required', 'email', 'max:255', 'unique:empleados,email'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'dni.unique' => 'El DNI ya se encuentra registrado.',
            'email.unique' => 'El correo electrónico ya se encuentra registrado.',
            'cargo_id.exists' => 'El cargo seleccionado no es válido.',
            'area_id.exists' => 'El área seleccionada no es válida.',
        ];
    }
}
