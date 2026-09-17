<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Boleta;
use App\Models\Contrato;
use App\Models\DocumentoLaboral;
use App\Models\EnvioBoleta;
use App\Models\EnvioContrato;
use App\Models\EnvioDocumento;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');
        $today = now()->startOfDay();

        // 1. KPIs Generales
        $totalEmpleados = Empleado::count();
        $empleadosActivos = Empleado::where('estado', 'activo')->count();

        // Semáforo de Contratos de Empleados
        $contratosPorVencer = Empleado::where('estado', 'activo')
            ->whereNotNull('fecha_fin_contrato')
            ->whereDate('fecha_fin_contrato', '>=', $today)
            ->whereDate('fecha_fin_contrato', '<=', $today->copy()->addDays(30))
            ->count();

        $contratosVencidos = Empleado::where('estado', 'activo')
            ->whereNotNull('fecha_fin_contrato')
            ->whereDate('fecha_fin_contrato', '<', $today)
            ->count();

        $contratosVigentes = Empleado::where('estado', 'activo')
            ->where(function ($q) use ($today) {
                $q->whereNull('fecha_fin_contrato')
                  ->orWhereDate('fecha_fin_contrato', '>', $today->copy()->addDays(30));
            })
            ->count();

        // Emisiones del mes actual
        $boletasMes = Boleta::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $contratosMes = Contrato::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $documentosMes = DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $documentosEmitidosMes = $boletasMes + $contratosMes + $documentosMes;

        // Envíos del mes actual
        $enviosBoletasMes = EnvioBoleta::whereYear('fecha_envio', $currentYear)->whereMonth('fecha_envio', $currentMonth)->get();
        $enviosContratosMes = EnvioContrato::whereYear('fecha_envio', $currentYear)->whereMonth('fecha_envio', $currentMonth)->get();
        $enviosDocsMes = EnvioDocumento::whereYear('fecha_envio', $currentYear)->whereMonth('fecha_envio', $currentMonth)->get();

        $enviosExitososMes = $enviosBoletasMes->where('estado_envio', 'exito')->count()
            + $enviosContratosMes->where('estado_envio', 'exito')->count()
            + $enviosDocsMes->where('estado_envio', 'exito')->count();

        $enviosFallidosMes = $enviosBoletasMes->where('estado_envio', 'fallido')->count()
            + $enviosContratosMes->where('estado_envio', 'fallido')->count()
            + $enviosDocsMes->where('estado_envio', 'fallido')->count();

        // 2. Desglose por Tipo de Documento (Mes Actual)
        $desgloseDocumentos = [
            'boletas' => $boletasMes,
            'contratos' => $contratosMes,
            'rit' => DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->where('tipo_documento', 'reglamento_interno')->count(),
            'politicas' => DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->where('tipo_documento', 'politicas_empresa')->count(),
            'memorandums' => DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->where('tipo_documento', 'memorandum')->count(),
            'no_renovacion' => DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->where('tipo_documento', 'no_renovacion')->count(),
            'despido' => DocumentoLaboral::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->where('tipo_documento', 'despido')->count(),
        ];

        // 3. Últimos Envíos Registrados (Feed Multidocumento)
        $bLogs = EnvioBoleta::with(['boleta.empleado', 'usuario'])->latest('fecha_envio')->take(8)->get()->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'tipo_key' => 'boletas',
                'tipo_nombre' => 'Boleta de Pago',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                'empleado_nombre' => $item->boleta->empleado->nombre_completo ?? 'N/A',
                'empleado_dni' => $item->boleta->empleado->dni ?? '-',
                'empleado_email' => $item->boleta->empleado->email ?? '',
                'detalle' => $item->boleta->periodo_formateado ?? 'Boleta de Pago',
                'fecha_envio' => $item->fecha_envio,
                'estado_envio' => $item->estado_envio,
                'operador' => $item->usuario->name ?? 'Sistema (Auto)',
                'mensaje_error' => $item->mensaje_error,
            ];
        });

        $cLogs = EnvioContrato::with(['contrato.empleado', 'usuario'])->latest('fecha_envio')->take(8)->get()->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'tipo_key' => 'contratos',
                'tipo_nombre' => 'Contrato Laboral',
                'badge_class' => 'bg-cyan-100 text-cyan-800 border border-cyan-200',
                'empleado_nombre' => $item->contrato->empleado->nombre_completo ?? 'N/A',
                'empleado_dni' => $item->contrato->empleado->dni ?? '-',
                'empleado_email' => $item->contrato->empleado->email ?? '',
                'detalle' => 'Contrato Laboral',
                'fecha_envio' => $item->fecha_envio,
                'estado_envio' => $item->estado_envio,
                'operador' => $item->usuario->name ?? 'Sistema (Auto)',
                'mensaje_error' => $item->mensaje_error,
            ];
        });

        $dLogs = EnvioDocumento::with(['documento.empleado', 'usuario'])->latest('fecha_envio')->take(8)->get()->map(function ($item) {
            $tipoNombre = $item->documento->tipo_nombre ?? 'Documento Laboral';
            $tipoDoc = $item->documento->tipo_documento ?? '';
            $badgeClass = match($tipoDoc) {
                'reglamento_interno' => 'bg-purple-100 text-purple-800 border border-purple-200',
                'politicas_empresa' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                'memorandum' => 'bg-amber-100 text-amber-800 border border-amber-200',
                'no_renovacion' => 'bg-rose-100 text-rose-800 border border-rose-200',
                'despido' => 'bg-red-100 text-red-800 border border-red-200',
                default => 'bg-slate-100 text-slate-800 border border-slate-200',
            };

            return (object) [
                'id' => $item->id,
                'tipo_key' => $tipoDoc,
                'tipo_nombre' => $tipoNombre,
                'badge_class' => $badgeClass,
                'empleado_nombre' => $item->documento->empleado->nombre_completo ?? 'N/A',
                'empleado_dni' => $item->documento->empleado->dni ?? '-',
                'empleado_email' => $item->documento->empleado->email ?? '',
                'detalle' => $item->documento->asunto_motivo ?? $tipoNombre,
                'fecha_envio' => $item->fecha_envio,
                'estado_envio' => $item->estado_envio,
                'operador' => $item->usuario->name ?? 'Sistema (Auto)',
                'mensaje_error' => $item->mensaje_error,
            ];
        });

        $ultimosEnviosMultidocumento = $bLogs->concat($cLogs)->concat($dLogs)
            ->sortByDesc(function ($item) {
                return $item->fecha_envio ? $item->fecha_envio->timestamp : 0;
            })
            ->take(8)
            ->values();

        $tasaConfirmacion = null;

        return view('dashboard', compact(
            'totalEmpleados',
            'empleadosActivos',
            'contratosVigentes',
            'contratosPorVencer',
            'contratosVencidos',
            'documentosEmitidosMes',
            'enviosExitososMes',
            'enviosFallidosMes',
            'tasaConfirmacion',
            'desgloseDocumentos',
            'ultimosEnviosMultidocumento',
            'currentMonth',
            'currentYear'
        ));
    }
}
