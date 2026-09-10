<?php

namespace App\Http\Controllers;

use App\Jobs\SendContratoEmailJob;
use App\Models\Contrato;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $empleadoId = $request->input('empleado_id');
        $estado = $request->input('estado');
        $alerta = $request->input('alerta'); // vigente, por_vencer, vencido

        $today = now()->startOfDay();

        $query = Contrato::with(['empleado.area', 'empleado.cargo', 'ultimoEnvio', 'creador'])
            ->orderBy('fecha_fin', 'asc');

        if ($empleadoId) {
            $query->where('empleado_id', $empleadoId);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($alerta === 'vencido') {
            $query->whereDate('fecha_fin', '<', $today);
        } elseif ($alerta === 'por_vencer') {
            $query->whereDate('fecha_fin', '>=', $today)
                ->whereDate('fecha_fin', '<=', $today->copy()->addDays(30));
        } elseif ($alerta === 'vigente') {
            $query->whereDate('fecha_fin', '>', $today->copy()->addDays(30));
        }

        $contratos = $query->paginate(15)->withQueryString();

        // Cálculo de Métricas Semafóricas
        $stats = [
            'total' => Contrato::count(),
            'vigentes' => Contrato::whereDate('fecha_fin', '>', $today->copy()->addDays(30))->count(),
            'por_vencer' => Contrato::whereDate('fecha_fin', '>=', $today)
                ->whereDate('fecha_fin', '<=', $today->copy()->addDays(30))->count(),
            'vencidos' => Contrato::whereDate('fecha_fin', '<', $today)->count(),
        ];

        $empleados = Empleado::where('estado', 'activo')->orderBy('apellidos')->get();

        return view('contratos.index', compact('contratos', 'stats', 'empleados', 'empleadoId', 'estado', 'alerta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empleado_id' => ['required', 'exists:empleados,id'],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $empleado = Empleado::findOrFail($validated['empleado_id']);

        $filename = "{$empleado->dni}_" . time() . ".pdf";
        $path = Storage::disk('boletas')->putFileAs('contratos', $request->file('pdf'), $filename);

        Contrato::create([
            'empleado_id' => $validated['empleado_id'],
            'fecha_ingreso' => $validated['fecha_ingreso'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'ruta_pdf' => $path,
            'estado' => 'activo',
            'estado_envio' => 'pendiente',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Contrato laboral registrado correctamente.');
    }

    public function send(Contrato $contrato)
    {
        $contrato->loadMissing('empleado');

        if (!$contrato->existeArchivo()) {
            return back()->with('error', 'No se puede enviar el correo porque el PDF del contrato no existe.');
        }

        if (empty($contrato->empleado->email)) {
            return back()->with('error', 'El empleado no tiene una dirección de correo electrónico registrada.');
        }

        SendContratoEmailJob::dispatch($contrato->id, auth()->id());

        return back()->with('success', 'Se ha encolado el envío del contrato por correo electrónico.');
    }

    public function streamPdf(Contrato $contrato)
    {
        if (!$contrato->existeArchivo()) {
            abort(404, 'El archivo PDF del contrato no se encuentra disponible.');
        }

        return Storage::disk('boletas')->response(
            $contrato->ruta_pdf,
            "Contrato_{$contrato->empleado->dni}.pdf",
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Contrato_' . $contrato->empleado->dni . '.pdf"',
            ]
        );
    }

    public function destroy(Contrato $contrato)
    {
        if (!empty($contrato->ruta_pdf) && Storage::disk('boletas')->exists($contrato->ruta_pdf)) {
            Storage::disk('boletas')->delete($contrato->ruta_pdf);
        }

        $contrato->delete();

        return back()->with('success', 'El registro de contrato ha sido eliminado exitosamente.');
    }
}
