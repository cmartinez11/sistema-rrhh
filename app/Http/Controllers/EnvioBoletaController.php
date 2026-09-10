<?php

namespace App\Http\Controllers;

use App\Jobs\SendBoletaEmailJob;
use App\Models\Boleta;
use App\Models\EnvioBoleta;
use App\Models\EnvioContrato;
use App\Models\EnvioDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnvioBoletaController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->input('tipo', 'todos');
        $estadoFilter = $request->input('estado');

        if ($tipo === 'boletas') {
            $query = EnvioBoleta::with(['boleta.empleado', 'usuario']);
            if ($request->filled('estado')) {
                $query->where('estado_envio', $estadoFilter);
            }
            $paginador = $query->latest('fecha_envio')->paginate(15)->withQueryString();
            $paginador->getCollection()->transform(function ($item) {
                $item->origen_tipo = 'boletas';
                $item->tipo_nombre = 'Boleta de Pago';
                $item->badge_class = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                $item->empleado_ref = $item->boleta->empleado ?? null;
                $item->detalle_resumen = $item->boleta->periodo_formateado ?? 'Boleta de Pago';
                return $item;
            });
            $envios = $paginador;
        } elseif ($tipo === 'contratos') {
            $query = EnvioContrato::with(['contrato.empleado', 'usuario']);
            if ($request->filled('estado')) {
                $query->where('estado_envio', $estadoFilter);
            }
            $paginador = $query->latest('fecha_envio')->paginate(15)->withQueryString();
            $paginador->getCollection()->transform(function ($item) {
                $item->origen_tipo = 'contratos';
                $item->tipo_nombre = 'Contrato Laboral';
                $item->badge_class = 'bg-cyan-100 text-cyan-800 border border-cyan-200';
                $item->empleado_ref = $item->contrato->empleado ?? null;
                $item->detalle_resumen = 'Contrato Laboral';
                return $item;
            });
            $envios = $paginador;
        } elseif (in_array($tipo, ['rit', 'politicas', 'memorandums', 'no_renovacion', 'despido'])) {
            $mapSubtipo = [
                'rit' => 'reglamento_interno',
                'politicas' => 'politicas_empresa',
                'memorandums' => 'memorandum',
                'no_renovacion' => 'no_renovacion',
                'despido' => 'despido',
            ];
            $subtipo = $mapSubtipo[$tipo];

            $query = EnvioDocumento::with(['documento.empleado', 'usuario'])
                ->whereHas('documento', fn($q) => $q->where('tipo_documento', $subtipo));

            if ($request->filled('estado')) {
                $query->where('estado_envio', $estadoFilter);
            }

            $paginador = $query->latest('fecha_envio')->paginate(15)->withQueryString();
            $paginador->getCollection()->transform(function ($item) {
                $item->origen_tipo = 'documentos';
                $item->tipo_nombre = $item->documento->tipo_nombre ?? 'Documento Laboral';
                $item->badge_class = match($item->documento->tipo_documento ?? '') {
                    'reglamento_interno' => 'bg-purple-100 text-purple-800 border border-purple-200',
                    'politicas_empresa' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                    'memorandum' => 'bg-amber-100 text-amber-800 border border-amber-200',
                    'no_renovacion' => 'bg-rose-100 text-rose-800 border border-rose-200',
                    'despido' => 'bg-red-100 text-red-800 border border-red-200',
                    default => 'bg-slate-100 text-slate-800 border border-slate-200',
                };
                $item->empleado_ref = $item->documento->empleado ?? null;
                $item->detalle_resumen = $item->documento->asunto_motivo ?? $item->tipo_nombre;
                return $item;
            });
            $envios = $paginador;
        } else {
            // 'todos' (Todas las Emisiones)
            $tipo = 'todos';

            $bQuery = EnvioBoleta::with(['boleta.empleado', 'usuario']);
            $cQuery = EnvioContrato::with(['contrato.empleado', 'usuario']);
            $dQuery = EnvioDocumento::with(['documento.empleado', 'usuario']);

            if ($request->filled('estado')) {
                $bQuery->where('estado_envio', $estadoFilter);
                $cQuery->where('estado_envio', $estadoFilter);
                $dQuery->where('estado_envio', $estadoFilter);
            }

            $boletasLogs = $bQuery->latest('fecha_envio')->take(100)->get()->map(function ($item) {
                $item->origen_tipo = 'boletas';
                $item->tipo_nombre = 'Boleta de Pago';
                $item->badge_class = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                $item->empleado_ref = $item->boleta->empleado ?? null;
                $item->detalle_resumen = $item->boleta->periodo_formateado ?? 'Boleta de Pago';
                return $item;
            });

            $contratosLogs = $cQuery->latest('fecha_envio')->take(100)->get()->map(function ($item) {
                $item->origen_tipo = 'contratos';
                $item->tipo_nombre = 'Contrato Laboral';
                $item->badge_class = 'bg-cyan-100 text-cyan-800 border border-cyan-200';
                $item->empleado_ref = $item->contrato->empleado ?? null;
                $item->detalle_resumen = 'Contrato Laboral';
                return $item;
            });

            $documentosLogs = $dQuery->latest('fecha_envio')->take(100)->get()->map(function ($item) {
                $item->origen_tipo = 'documentos';
                $item->tipo_nombre = $item->documento->tipo_nombre ?? 'Documento Laboral';
                $item->badge_class = match($item->documento->tipo_documento ?? '') {
                    'reglamento_interno' => 'bg-purple-100 text-purple-800 border border-purple-200',
                    'politicas_empresa' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                    'memorandum' => 'bg-amber-100 text-amber-800 border border-amber-200',
                    'no_renovacion' => 'bg-rose-100 text-rose-800 border border-rose-200',
                    'despido' => 'bg-red-100 text-red-800 border border-red-200',
                    default => 'bg-slate-100 text-slate-800 border border-slate-200',
                };
                $item->empleado_ref = $item->documento->empleado ?? null;
                $item->detalle_resumen = $item->documento->asunto_motivo ?? $item->tipo_nombre;
                return $item;
            });

            $mergedCollection = $boletasLogs->concat($contratosLogs)->concat($documentosLogs)
                ->sortByDesc(fn($item) => $item->fecha_envio ? $item->fecha_envio->timestamp : 0)
                ->values();

            $page = (int) $request->input('page', 1);
            $perPage = 15;
            $sliced = $mergedCollection->slice(($page - 1) * $perPage, $perPage)->values();

            $envios = new \Illuminate\Pagination\LengthAwarePaginator(
                $sliced,
                $mergedCollection->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('auditoria.index', compact('envios', 'tipo'));
    }

    public function dispatchBatch(Request $request)
    {
        Gate::authorize('batchSend', Boleta::class);

        $request->validate([
            'periodo_mes' => ['required', 'integer', 'between:1,12'],
            'periodo_anio' => ['required', 'integer'],
            'tipo_periodo' => ['nullable', 'in:primera_quincena,segunda_quincena'],
        ]);

        $mes = $request->input('periodo_mes');
        $anio = $request->input('periodo_anio');
        $tipoPeriodo = $request->input('tipo_periodo');

        $query = Boleta::with(['empleado'])
            ->where('periodo_mes', $mes)
            ->where('periodo_anio', $anio)
            ->whereIn('estado', ['pendiente', 'error']);

        if ($tipoPeriodo) {
            $query->where('tipo_periodo', $tipoPeriodo);
        }

        $boletasPendientes = $query->get();

        if ($boletasPendientes->isEmpty()) {
            return back()->with('info', 'No hay boletas pendientes o con error para el periodo seleccionado.');
        }

        $throttleSeconds = (int) env('MAIL_THROTTLE_SECONDS', 40);
        $delayAcumulado = 0;
        $count = 0;
        $invalidCount = 0;

        foreach ($boletasPendientes as $boleta) {
            if (!$boleta->empleado || empty($boleta->empleado->email) || !filter_var($boleta->empleado->email, FILTER_VALIDATE_EMAIL)) {
                $boleta->update(['estado' => 'error']);
                EnvioBoleta::create([
                    'boleta_id' => $boleta->id,
                    'fecha_envio' => now(),
                    'estado_envio' => 'fallido',
                    'mensaje_error' => 'El empleado no tiene un correo electrónico válido asignado.',
                    'enviado_por' => auth()->id(),
                ]);
                $invalidCount++;
                continue;
            }

            SendBoletaEmailJob::dispatch($boleta->id, auth()->id())
                ->delay(now()->addSeconds($delayAcumulado));

            $delayAcumulado += $throttleSeconds;
            $count++;
        }

        if ($count === 0 && $invalidCount > 0) {
            return back()->with('warning', "Se revisaron {$invalidCount} boleta(s), pero todas fueron marcadas con error por carecer de correo válido.");
        }

        $tiempoEstimado = (int) ceil(max(0, $delayAcumulado - $throttleSeconds) / 60);

        $msg = "Se han encolado exitosamente {$count} trabajos de envío de boletas en segundo plano. El proceso tardará aproximadamente {$tiempoEstimado} minuto(s) en completarse respetando el límite de cPanel.";
        if ($invalidCount > 0) {
            $msg .= " ({$invalidCount} boletas marcadas con error por falta de correo válido).";
        }

        return back()->with('success', $msg);
    }

    public function retryFailed(Request $request)
    {
        Gate::authorize('batchSend', Boleta::class);

        $mes = $request->input('periodo_mes');
        $anio = $request->input('periodo_anio');
        $tipoPeriodo = $request->input('tipo_periodo');

        $query = Boleta::with(['empleado'])->where('estado', 'error');

        if ($mes && $anio) {
            $query->where('periodo_mes', $mes)->where('periodo_anio', $anio);
        }

        if ($tipoPeriodo) {
            $query->where('tipo_periodo', $tipoPeriodo);
        }

        $boletasFallidas = $query->get();

        if ($boletasFallidas->isEmpty()) {
            return back()->with('info', 'No hay boletas fallidas para reintentar.');
        }

        $throttleSeconds = (int) env('MAIL_THROTTLE_SECONDS', 40);
        $delayAcumulado = 0;
        $count = 0;
        $invalidCount = 0;

        foreach ($boletasFallidas as $boleta) {
            if (!$boleta->empleado || empty($boleta->empleado->email) || !filter_var($boleta->empleado->email, FILTER_VALIDATE_EMAIL)) {
                $invalidCount++;
                continue;
            }

            SendBoletaEmailJob::dispatch($boleta->id, auth()->id())
                ->delay(now()->addSeconds($delayAcumulado));

            $delayAcumulado += $throttleSeconds;
            $count++;
        }

        if ($count === 0 && $invalidCount > 0) {
            return back()->with('warning', "Se detectaron {$invalidCount} boletas fallidas, pero ninguna cuenta con un correo electrónico válido para reintentar.");
        }

        $tiempoEstimado = (int) ceil(max(0, $delayAcumulado - $throttleSeconds) / 60);

        $msg = "Se han re-encolado exitosamente {$count} boletas fallidas para envío. Tiempo estimado de finalización: {$tiempoEstimado} minuto(s).";
        if ($invalidCount > 0) {
            $msg .= " ({$invalidCount} omitidas por correo inválido).";
        }

        return back()->with('success', $msg);
    }

    public function show(Request $request, string $id)
    {
        if (!auth()->user()->hasPermissionTo('ver-auditoria')) {
            abort(403);
        }

        $tipo = $request->input('tipo');

        if ($tipo === 'contratos') {
            $envio = EnvioContrato::with(['contrato.empleado', 'usuario'])->find($id);
            $empleado = $envio?->contrato?->empleado;
            $defaultSubject = 'Contrato Laboral - PLASTICOS FENIX';
        } elseif ($tipo === 'documentos' || in_array($tipo, ['rit', 'politicas', 'memorandums', 'no_renovacion', 'despido'])) {
            $envio = EnvioDocumento::with(['documento.empleado', 'usuario'])->find($id);
            $empleado = $envio?->documento?->empleado;
            $defaultSubject = ($envio?->documento?->tipo_nombre ?? 'Documento Laboral') . ' - PLASTICOS FENIX';
        } else {
            $envio = EnvioBoleta::with(['boleta.empleado', 'usuario'])->find($id);
            if (!$envio) {
                // Intento fallback en EnvioContrato o EnvioDocumento
                $envio = EnvioContrato::with(['contrato.empleado', 'usuario'])->find($id);
                if ($envio) {
                    $empleado = $envio->contrato?->empleado;
                    $defaultSubject = 'Contrato Laboral - PLASTICOS FENIX';
                    $tipo = 'contratos';
                } else {
                    $envio = EnvioDocumento::with(['documento.empleado', 'usuario'])->find($id);
                    $empleado = $envio?->documento?->empleado;
                    $defaultSubject = ($envio?->documento?->tipo_nombre ?? 'Documento Laboral') . ' - PLASTICOS FENIX';
                    $tipo = 'documentos';
                }
            } else {
                $empleado = $envio?->boleta?->empleado;
                $defaultSubject = 'Boleta de Pago - PLASTICOS FENIX';
                $tipo = 'boletas';
            }
        }

        if (!$envio) {
            abort(404, 'Registro de auditoría no encontrado.');
        }

        $headers = $envio->headers_raw ?? [];

        // Formatear fecha asegurando la conversión a America/Lima
        $fechaFormateada = $envio->fecha_envio 
            ? $envio->fecha_envio->setTimezone('America/Lima')->format('d/m/Y H:i:s') 
            : 'N/A';

        $deAddress = $headers['from'] ?? config('mail.from.address', 'notificaciones@plasticosfenix.com');
        $deName = $headers['from_name'] ?? config('mail.from.name', 'PLASTICOS FENIX - RRHH');
        $de = "{$deName} <{$deAddress}>";

        $para = $headers['to'] ?? ($empleado->email ?? 'N/A');
        $asunto = $headers['subject'] ?? $defaultSubject;
        $messageId = $envio->message_id ?? ('msg-' . $envio->id);

        $cuerpoHtml = $envio->cuerpo_html;
        if (empty($cuerpoHtml)) {
            if ($tipo === 'boletas' && isset($envio->boleta) && $envio->boleta->empleado) {
                try {
                    $cuerpoHtml = (new \App\Mail\BoletaPagoMail($envio->boleta))->render();
                } catch (\Throwable $e) {}
            } elseif ($tipo === 'contratos' && isset($envio->contrato) && $envio->contrato->empleado) {
                try {
                    $cuerpoHtml = (new \App\Mail\ContratoLaboralMail($envio->contrato))->render();
                } catch (\Throwable $e) {}
            } elseif (isset($envio->documento) && $envio->documento->empleado) {
                try {
                    $cuerpoHtml = (new \App\Mail\DocumentoLaboralMail($envio->documento))->render();
                } catch (\Throwable $e) {}
            }
        }

        // Fuente MIME cruda o fallback estructurado
        $rawSource = $headers['raw_source'] ?? ($envio->mensaje_error ?? null);
        if (empty($rawSource)) {
            $rawSource = "Delivered-To: {$para}\n" .
                "Received: by 10.2.3.4 with SMTP id cpanel-smtp-id-{$envio->id}; {$fechaFormateada} -0500\n" .
                "Message-ID: <{$messageId}>\n" .
                "Date: " . ($envio->fecha_envio ? $envio->fecha_envio->setTimezone('America/Lima')->toRfc2822String() : date('r')) . "\n" .
                "From: {$de}\n" .
                "To: {$para}\n" .
                "Subject: {$asunto}\n" .
                "MIME-Version: 1.0\n" .
                "Content-Type: text/html; charset=UTF-8\n" .
                "X-Mailer: System-RRHH / PLASTICOS FENIX Mailer\n\n" .
                ($cuerpoHtml ?? "No HTML Body Content");
        }

        return response()->json([
            'id' => $envio->id,
            'tipo' => $tipo,
            'message_id' => $messageId,
            'fecha' => $fechaFormateada,
            'de' => $de,
            'para' => $para,
            'asunto' => $asunto,
            'estado' => $envio->estado_envio,
            'error' => $envio->mensaje_error,
            'cuerpo_html' => $cuerpoHtml ?? '<div style="padding:15px; font-family:sans-serif; color:#64748b;">No hay contenido HTML guardado para este registro de envío.</div>',
            'raw_source' => $rawSource,
            'operador' => $envio->usuario->name ?? 'Sistema (Auto)',
        ]);
    }
}
