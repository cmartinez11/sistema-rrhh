<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchUploadBoletaRequest;
use App\Http\Requests\StoreBoletaRequest;
use App\Models\Boleta;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BoletaController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->input('periodo_mes', (int) date('n'));
        $anio = $request->input('periodo_anio', (int) date('Y'));
        $tipoPeriodo = $request->input('tipo_periodo'); // opcional: primera_quincena, segunda_quincena o null (todos)
        $estado = $request->input('estado');

        $query = Boleta::with(['empleado.area', 'empleado.cargo', 'ultimoEnvio'])
            ->where('periodo_mes', $mes)
            ->where('periodo_anio', $anio);

        if ($tipoPeriodo) {
            $query->where('tipo_periodo', $tipoPeriodo);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $boletas = $query->paginate(15)->withQueryString();

        $statsQuery = Boleta::where('periodo_mes', $mes)->where('periodo_anio', $anio);
        if ($tipoPeriodo) {
            $statsQuery->where('tipo_periodo', $tipoPeriodo);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pendientes' => (clone $statsQuery)->where('estado', 'pendiente')->count(),
            'enviadas' => (clone $statsQuery)->where('estado', 'enviada')->count(),
            'errores' => (clone $statsQuery)->where('estado', 'error')->count(),
        ];

        $empleados = Empleado::where('estado', 'activo')->orderBy('apellidos')->get();

        return view('boletas.index', compact('boletas', 'mes', 'anio', 'tipoPeriodo', 'estado', 'stats', 'empleados'));
    }

    public function store(StoreBoletaRequest $request)
    {
        $empleado = Empleado::findOrFail($request->empleado_id);
        $mes = str_pad($request->periodo_mes, 2, '0', STR_PAD_LEFT);
        $anio = $request->periodo_anio;
        $tipoPeriodo = $request->tipo_periodo;
        $file = $request->file('archivo_pdf');

        $relPath = "boletas/{$anio}/{$mes}/{$tipoPeriodo}/{$empleado->dni}.pdf";

        // Guardar en el disco personalizado 'boletas'
        Storage::disk('boletas')->putFileAs(
            "boletas/{$anio}/{$mes}/{$tipoPeriodo}",
            $file,
            "{$empleado->dni}.pdf"
        );

        Boleta::updateOrCreate(
            [
                'empleado_id' => $empleado->id,
                'periodo_mes' => $request->periodo_mes,
                'periodo_anio' => $request->periodo_anio,
                'tipo_periodo' => $tipoPeriodo,
            ],
            [
                'ruta_pdf' => $relPath,
                'estado' => 'pendiente',
                'created_by' => auth()->id(),
            ]
        );

        return redirect()->route('boletas.index', [
            'periodo_mes' => $request->periodo_mes,
            'periodo_anio' => $request->periodo_anio,
            'tipo_periodo' => $tipoPeriodo,
        ])->with('success', "Boleta cargada correctamente para {$empleado->nombre_completo}.");
    }

    public function batchUpload(BatchUploadBoletaRequest $request)
    {
        $mes = str_pad($request->periodo_mes, 2, '0', STR_PAD_LEFT);
        $anio = $request->periodo_anio;
        $tipoPeriodo = $request->tipo_periodo;
        $files = $request->file('archivos');

        $procesados = 0;
        $noEncontrados = [];

        foreach ($files as $file) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $dni = trim($filename);

            $empleado = Empleado::where('dni', $dni)->first();

            if (!$empleado) {
                $noEncontrados[] = $file->getClientOriginalName();
                continue;
            }

            $relPath = "boletas/{$anio}/{$mes}/{$tipoPeriodo}/{$empleado->dni}.pdf";

            Storage::disk('boletas')->putFileAs(
                "boletas/{$anio}/{$mes}/{$tipoPeriodo}",
                $file,
                "{$empleado->dni}.pdf"
            );

            Boleta::updateOrCreate(
                [
                    'empleado_id' => $empleado->id,
                    'periodo_mes' => $request->periodo_mes,
                    'periodo_anio' => $request->periodo_anio,
                    'tipo_periodo' => $tipoPeriodo,
                ],
                [
                    'ruta_pdf' => $relPath,
                    'estado' => 'pendiente',
                    'created_by' => auth()->id(),
                ]
            );

            $procesados++;
        }

        $mensaje = "Carga masiva completada: {$procesados} boletas registradas correctamente.";
        if (count($noEncontrados) > 0) {
            $mensaje .= " Archivos no emparejados (DNI no encontrado): " . implode(', ', $noEncontrados);
        }

        return redirect()->route('boletas.index', [
            'periodo_mes' => $request->periodo_mes,
            'periodo_anio' => $request->periodo_anio,
            'tipo_periodo' => $tipoPeriodo,
        ])->with(count($noEncontrados) > 0 ? 'warning' : 'success', $mensaje);
    }

    public function streamPdf(Boleta $boleta)
    {
        Gate::authorize('view', $boleta);

        if (!Storage::disk('boletas')->exists($boleta->ruta_pdf)) {
            abort(404, 'El archivo PDF de la boleta no se encuentra en el almacenamiento.');
        }

        $fullPath = Storage::disk('boletas')->path($boleta->ruta_pdf);
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Boleta_' . $boleta->empleado->dni . '.pdf"'
        ]);
    }

    public function destroy(Boleta $boleta)
    {
        Gate::authorize('delete', $boleta);

        if (Storage::disk('boletas')->exists($boleta->ruta_pdf)) {
            Storage::disk('boletas')->delete($boleta->ruta_pdf);
        }

        $boleta->delete();

        return back()->with('success', 'Boleta eliminada correctamente.');
    }
}
