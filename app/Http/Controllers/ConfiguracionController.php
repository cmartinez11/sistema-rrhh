<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuraciones = [
            'mail_host' => env('MAIL_HOST', 'mail.plasticosfenix.com'),
            'mail_port' => env('MAIL_PORT', 465),
            'mail_username' => env('MAIL_USERNAME', 'boletas@plasticosfenix.com'),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', 'boletas@plasticosfenix.com'),
            'mail_from_name' => env('MAIL_FROM_NAME', 'PLASTICOS FENIX - RRHH'),
            'mail_plantilla_asunto' => Configuracion::obtener('mail_plantilla_asunto', 'Boleta de Pago - {periodo} - PLASTICOS FENIX'),
            'mail_plantilla_cuerpo' => Configuracion::obtener('mail_plantilla_cuerpo', "<p>Estimado(a) <strong>{nombre}</strong>,</p>\n<p>Adjunto a este correo encontrará su <strong>Boleta de Pago correspondiente a {periodo}</strong>.</p>\n<p>Si tiene alguna consulta referente a su boleta, por favor comuníquese con el departamento de Recursos Humanos.</p>\n<br>\n<p>Atentamente,</p>\n<p><strong>Área de Recursos Humanos</strong><br>PLASTICOS FENIX</p>"),
            'mail_throttle_per_minute' => env('MAIL_THROTTLE_PER_MINUTE', 10),
            'boletas_storage_path' => env('BOLETAS_STORAGE_PATH', config('filesystems.disks.boletas.root')),
        ];

        return view('configuracion.index', compact('configuraciones'));
    }

    public function update(UpdateConfiguracionRequest $request)
    {
        // Guardar plantilla y parámetros flexibles en BD (JSONB)
        Configuracion::establecer('mail_plantilla_asunto', $request->mail_plantilla_asunto, 'Asunto editable del correo de boletas');
        Configuracion::establecer('mail_plantilla_cuerpo', $request->mail_plantilla_cuerpo, 'Cuerpo HTML editable del correo de boletas');
        Configuracion::establecer('mail_remitente_nombre', $request->mail_from_name, 'Nombre del remitente');
        Configuracion::establecer('mail_remitente_email', $request->mail_from_address, 'Correo del remitente');
        Configuracion::establecer('mail_throttle_per_minute', $request->mail_throttle_per_minute, 'Límite de envíos por minuto');
        Configuracion::establecer('boletas_storage_path', $request->boletas_storage_path, 'Ruta del disco de boletas');

        // Actualizar variables dinámicas en el archivo .env
        $this->actualizarEnv([
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => '"' . $request->mail_from_address . '"',
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
            'MAIL_THROTTLE_PER_MINUTE' => $request->mail_throttle_per_minute,
            'BOLETAS_STORAGE_PATH' => '"' . $request->boletas_storage_path . '"',
        ]);

        if ($request->filled('mail_password')) {
            $this->actualizarEnv(['MAIL_PASSWORD' => '"' . $request->mail_password . '"']);
        }

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración del sistema actualizada correctamente.');
    }

    private function actualizarEnv(array $data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);

        foreach ($data as $key => $val) {
            $pattern = "/^{$key}=.*/m";
            $replace = "{$key}={$val}";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replace, $content);
            } else {
                $content .= "\n{$key}={$val}";
            }
        }

        file_put_contents($path, $content);
    }
}
