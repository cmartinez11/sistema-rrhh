<?php

namespace App\Http\Controllers;

use App\Jobs\SendDocumentoLaboralEmailJob;
use App\Models\DocumentoLaboral;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoLaboralController extends Controller
{
    /**
     * Helper genérico para listar documentos por tipo
     */
    private function getDocumentosPorTipo(Request $request, string $tipoDocumento)
    {
        $empleadoId = $request->input('empleado_id');
        $estadoEnvio = $request->input('estado_envio');

        $query = DocumentoLaboral::with(['empleado.area', 'empleado.cargo', 'ultimoEnvio', 'creador'])
            ->where('tipo_documento', $tipoDocumento)
            ->orderBy('fecha_emision', 'desc')
            ->orderBy('id', 'desc');

        if ($empleadoId) {
            $query->where('empleado_id', $empleadoId);
        }

        if ($estadoEnvio) {
            $query->where('estado_envio', $estadoEnvio);
        }

        $documentos = $query->paginate(15)->withQueryString();
        $empleados = Empleado::where('estado', 'activo')->orderBy('apellidos')->get();

        return compact('documentos', 'empleados', 'empleadoId', 'estadoEnvio');
    }

    /**
     * 1. Reglamento Interno (RIT)
     */
    public function rit(Request $request)
    {
        $data = $this->getDocumentosPorTipo($request, 'reglamento_interno');
        return view('documentos.rit', $data);
    }

    /**
     * 2. Políticas de la Empresa
     */
    public function politicas(Request $request)
    {
        $data = $this->getDocumentosPorTipo($request, 'politicas_empresa');
        return view('documentos.politicas', $data);
    }

    /**
     * 3. Memorándums
     */
    public function memorandums(Request $request)
    {
        $data = $this->getDocumentosPorTipo($request, 'memorandum');
        return view('documentos.memorandums', $data);
    }

    /**
     * 4. Carta de No Renovación
     */
    public function noRenovacion(Request $request)
    {
        $data = $this->getDocumentosPorTipo($request, 'no_renovacion');
        return view('documentos.no_renovacion', $data);
    }

    /**
     * 5. Carta de Despido
     */
    public function despido(Request $request)
    {
        $data = $this->getDocumentosPorTipo($request, 'despido');
        return view('documentos.despido', $data);
    }

    /**
     * Registrar nuevo documento laboral (soporta empleado individual o despacho general)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empleado_id' => ['nullable'],
            'despacho_general' => ['nullable'],
            'tipo_documento' => ['required', 'in:reglamento_interno,politicas_empresa,memorandum,no_renovacion,despido'],
            'fecha_emision' => ['required', 'date'],
            'asunto_motivo' => ['nullable', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $isDespachoGeneral = $request->boolean('despacho_general') || $request->input('empleado_id') === 'todos';

        if ($isDespachoGeneral) {
            $empleados = Empleado::where('estado', 'activo')->get();
            if ($empleados->isEmpty()) {
                return back()->with('error', 'No hay empleados activos registrados para realizar el despacho general.');
            }

            $filename = "{$validated['tipo_documento']}_general_" . time() . ".pdf";
            $path = Storage::disk('boletas')->putFileAs(
                'documentos/' . $validated['tipo_documento'],
                $request->file('pdf'),
                $filename
            );

            $count = 0;
            foreach ($empleados as $emp) {
                DocumentoLaboral::create([
                    'empleado_id' => $emp->id,
                    'tipo_documento' => $validated['tipo_documento'],
                    'fecha_emision' => $validated['fecha_emision'],
                    'asunto_motivo' => $validated['asunto_motivo'] ?? 'Políticas de la Empresa - PLASTICOS FENIX',
                    'ruta_pdf' => $path,
                    'estado_envio' => 'pendiente',
                    'created_by' => auth()->id(),
                ]);
                $count++;
            }

            return back()->with('success', "Se emitieron exitosamente {$count} registros de Políticas de la Empresa para todo el personal activo.");
        } else {
            $request->validate([
                'empleado_id' => ['required', 'exists:empleados,id'],
            ]);

            $empleado = Empleado::findOrFail($request->input('empleado_id'));

            $filename = "{$validated['tipo_documento']}_{$empleado->dni}_" . time() . ".pdf";
            $path = Storage::disk('boletas')->putFileAs(
                'documentos/' . $validated['tipo_documento'],
                $request->file('pdf'),
                $filename
            );

            DocumentoLaboral::create([
                'empleado_id' => $empleado->id,
                'tipo_documento' => $validated['tipo_documento'],
                'fecha_emision' => $validated['fecha_emision'],
                'asunto_motivo' => $validated['asunto_motivo'],
                'ruta_pdf' => $path,
                'estado_envio' => 'pendiente',
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Documento laboral registrado exitosamente.');
        }
    }

    /**
     * Enviar documento por correo electrónico
     */
    public function send(DocumentoLaboral $documento)
    {
        $documento->loadMissing('empleado');

        if (!$documento->existeArchivo()) {
            return back()->with('error', 'No se puede enviar el correo porque el archivo PDF del documento no existe.');
        }

        if (empty($documento->empleado->email)) {
            return back()->with('error', 'El empleado seleccionado no tiene una dirección de correo electrónico válida.');
        }

        SendDocumentoLaboralEmailJob::dispatch($documento->id, auth()->id());

        return back()->with('success', 'Se ha encolado el envío del documento por correo electrónico.');
    }

    /**
     * Previsualizar PDF de forma segura
     */
    public function streamPdf(DocumentoLaboral $documento)
    {
        if (!$documento->existeArchivo()) {
            abort(404, 'El archivo PDF del documento no se encuentra disponible.');
        }

        return Storage::disk('boletas')->response(
            $documento->ruta_pdf,
            "{$documento->tipo_documento}_{$documento->empleado->dni}.pdf",
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $documento->tipo_documento . '_' . $documento->empleado->dni . '.pdf"',
            ]
        );
    }

    /**
     * Eliminar registro de documento laboral
     */
    public function destroy(DocumentoLaboral $documento)
    {
        if (!empty($documento->ruta_pdf) && Storage::disk('boletas')->exists($documento->ruta_pdf)) {
            // Verificar si el archivo es compartido por otros registros antes de borrar
            $otrosConMismaRuta = DocumentoLaboral::where('ruta_pdf', $documento->ruta_pdf)
                ->where('id', '!=', $documento->id)
                ->count();
            if ($otrosConMismaRuta === 0) {
                Storage::disk('boletas')->delete($documento->ruta_pdf);
            }
        }

        $documento->delete();

        return back()->with('success', 'El registro de documento laboral ha sido eliminado correctamente.');
    }
}
