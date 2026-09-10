<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Gestión de Documentos — Reglamento Interno (RIT)
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ openUpload: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Pestañas de Navegación de Documentos Laborales -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex space-x-1 border-b border-slate-100 w-full lg:w-auto overflow-x-auto">
                    <a href="{{ route('boletas.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        Boletas de Pago
                    </a>
                    <a href="{{ route('contratos.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        Contratos Laborales
                    </a>
                    <a href="{{ route('documentos.rit.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition bg-emerald-600 text-white shadow-md whitespace-nowrap">
                        Reglamento Interno (RIT)
                    </a>
                    <a href="{{ route('documentos.politicas.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        Políticas de la Empresa
                    </a>
                    <a href="{{ route('documentos.memorandums.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        Memorándums
                    </a>
                    <a href="{{ route('documentos.no-renovacion.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        No Renovación
                    </a>
                    <a href="{{ route('documentos.despido.index') }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition text-slate-600 hover:bg-slate-100 whitespace-nowrap">
                        Carta de Despido
                    </a>
                </div>

                <button @click="openUpload = true" class="w-full lg:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Emisión RIT
                </button>
            </div>

            <!-- Filtros de Búsqueda -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('documentos.rit.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Empleado</label>
                        <select name="empleado_id" class="w-full rounded-xl border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Todos los empleados</option>
                            @foreach($empleados as $emp)
                                <option value="{{ $emp->id }}" {{ $empleadoId == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->apellidos }}, {{ $emp->nombres }} ({{ $emp->dni }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Estado de Envío</label>
                        <select name="estado_envio" class="w-full rounded-xl border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ $estadoEnvio == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="enviado" {{ $estadoEnvio == 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="error" {{ $estadoEnvio == 'error' ? 'selected' : '' }}>Error</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm shadow transition">
                            Filtrar
                        </button>
                        <a href="{{ route('documentos.rit.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de Documentos -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">Empleado</th>
                                <th class="px-6 py-3.5">Área / Cargo</th>
                                <th class="px-6 py-3.5">Fecha Emisión</th>
                                <th class="px-6 py-3.5">Estado Envío</th>
                                <th class="px-6 py-3.5">Último Envío</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($documentos as $doc)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                                    #{{ $doc->id }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <div>{{ $doc->empleado->nombre_completo }}</div>
                                    <div class="text-xs text-slate-400 font-mono">DNI: {{ $doc->empleado->dni }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    <div>{{ $doc->empleado->area->nombre ?? 'N/A' }}</div>
                                    <div class="text-slate-400">{{ $doc->empleado->cargo->nombre ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono">
                                    {{ $doc->fecha_emision ? $doc->fecha_emision->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($doc->estado_envio === 'enviado')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Enviado
                                    </span>
                                    @elseif ($doc->estado_envio === 'error')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> Error
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 me-1.5"></span> Pendiente
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                                    {{ $doc->ultimoEnvio && $doc->ultimoEnvio->fecha_envio ? $doc->ultimoEnvio->fecha_envio->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('documentos.stream', $doc) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition" title="Previsualizar PDF">
                                        <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver PDF
                                    </a>

                                    <form action="{{ route('documentos.send', $doc) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea enviar el Reglamento Interno (RIT) al correo de {{ $doc->empleado->nombre_completo }}?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition" title="Enviar por correo">
                                            <svg class="w-3.5 h-3.5 me-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Enviar
                                        </button>
                                    </form>

                                    <form action="{{ route('documentos.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar el registro de este documento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar Documento">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400 italic">
                                    No se encontraron registros de Reglamento Interno (RIT).
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($documentos->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $documentos->links() }}
                </div>
                @endif
            </div>

            <!-- Modal Subir RIT -->
            <div x-show="openUpload" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="openUpload = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Registrar Reglamento Interno (RIT)</h3>
                        <button @click="openUpload = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
                    </div>
                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tipo_documento" value="reglamento_interno">

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Empleado *</label>
                            <select name="empleado_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="" disabled selected>Seleccione empleado...</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->apellidos }}, {{ $emp->nombres }} (DNI: {{ $emp->dni }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Fecha de Emisión *</label>
                            <input type="date" name="fecha_emision" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Observación / Asunto (Opcional)</label>
                            <input type="text" name="asunto_motivo" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ej. Entrega de RIT 2026">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Archivo PDF (Máx. 10MB) *</label>
                            <input type="file" name="pdf" accept=".pdf" required class="w-full text-sm rounded-xl border-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>

                        <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="openUpload = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Cancelar</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition">Subir Documento</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
