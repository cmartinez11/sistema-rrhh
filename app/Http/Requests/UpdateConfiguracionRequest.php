<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('gestionar-configuracion');
    }

    public function rules(): array
    {
        return [
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'integer'],
            'mail_username' => ['required', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['required', 'in:ssl,tls,none'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
            'mail_plantilla_asunto' => ['required', 'string'],
            'mail_plantilla_cuerpo' => ['required', 'string'],
            'mail_throttle_per_minute' => ['required', 'integer', 'min:1', 'max:100'],
            'boletas_storage_path' => ['required', 'string'],
        ];
    }
}
