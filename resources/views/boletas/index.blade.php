<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Gestión de Boletas de Pago
            </h2>
            <div class="flex items-center space-x-3" x-data="{ openSingle: false, openBatch: false }">
                @can('gestionar-boletas')
                <!-- Botón Carga Individual -->
                <button @click="openSingle = true" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-sm transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Carga Individual PDF
                </button>

                <!-- Botón Carga Masiva -->
                <button @click="openBatch = true" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition shadow-sm flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Carga Masiva (Varios PDFs)
                </button>
                @endcan

                @can('enviar-boletas')
                <!-- Botón Enviar Boletas del Periodo -->
                <form action="{{ route('envios.dispatch') }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma enviar por correo todas las boletas pendientes para este periodo?');">
                    @csrf
                    <input type="hidden" name="periodo_mes" value="{{ $mes }}">
                    <input type="hidden" name="periodo_anio" value="{{ $anio }}">
                    @if ($tipoPeriodo)
                        <input type="hidden" name="tipo_periodo" value="{{ $tipoPeriodo }}">
                    @endif
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-md transition flex items-center">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Enviar Boletas del Periodo
                    </button>
                </form>
                @endcan

                <!-- Modal Carga Individual -->
                <div x-show="openSingle" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-lg font-bold text-slate-800">Cargar Boleta Individual</h3>
                            <button @click="openSingle = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                        </div>
                        <form action="{{ route('boletas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Trabajador *</label>
                                <select name="empleado_id" required class="w-full text-sm rounded-xl border-slate-300">
                                    <option value="">Seleccionar Trabajador...</option>
                                    @foreach ($empleados as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nombre_completo }} (DNI: {{ $emp->dni }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Quincena / Periodo *</label>
                                    <select name="tipo_periodo" required class="w-full text-xs rounded-xl border-slate-300">
                                        <option value="primera_quincena" {{ $tipoPeriodo === 'primera_quincena' ? 'selected' : '' }}>1ra Quincena</option>
                                        <option value="segunda_quincena" {{ ($tipoPeriodo === 'segunda_quincena' || !$tipoPeriodo) ? 'selected' : '' }}>Fin de Mes</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Mes *</label>
                                    <select name="periodo_mes" required class="w-full text-xs rounded-xl border-slate-300">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>{{ strftime('%B', mktime(0, 0, 0, $m, 1)) ?: 'Mes '.$m }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Año *</label>
                                    <input type="number" name="periodo_anio" value="{{ $anio }}" required class="w-full text-xs rounded-xl border-slate-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Archivo PDF *</label>
                                <input type="file" name="archivo_pdf" accept="application/pdf" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                            </div>
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" @click="openSingle = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-sm font-bold shadow-md">Subir Boleta</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Carga Masiva -->
                <div x-show="openBatch" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-lg font-bold text-slate-800">Carga Masiva de PDFs</h3>
                            <button @click="openBatch = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                        </div>
                        <p class="text-xs text-slate-500">
                            Suba múltiples archivos PDF nombrados con el DNI del empleado (ej. <code class="bg-slate-100 text-cyan-600 px-1 py-0.5 rounded">12345678.pdf</code>).
                        </p>
                        <form action="{{ route('boletas.batch') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Quincena / Periodo *</label>
                                    <select name="tipo_periodo" required class="w-full text-xs rounded-xl border-slate-300">
                                        <option value="primera_quincena" {{ $tipoPeriodo === 'primera_quincena' ? 'selected' : '' }}>1ra Quincena</option>
                                        <option value="segunda_quincena" {{ ($tipoPeriodo === 'segunda_quincena' || !$tipoPeriodo) ? 'selected' : '' }}>Fin de Mes</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Mes *</label>
                                    <select name="periodo_mes" required class="w-full text-xs rounded-xl border-slate-300">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>{{ strftime('%B', mktime(0, 0, 0, $m, 1)) ?: 'Mes '.$m }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Año *</label>
                                    <input type="number" name="periodo_anio" value="{{ $anio }}" required class="w-full text-xs rounded-xl border-slate-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Seleccionar PDFs (Múltiples) *</label>
                                <input type="file" name="archivos[]" accept="application/pdf" multiple required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" @click="openBatch = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md">Procesar Carga Masiva</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Selector de Periodo y Filtros -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('boletas.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div>
                        <select name="tipo_periodo" onchange="this.form.submit()" class="text-sm font-semibold rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Todas las Quincenas</option>
                            <option value="primera_quincena" {{ $tipoPeriodo === 'primera_quincena' ? 'selected' : '' }}>1ra Quincena</option>
                            <option value="segunda_quincena" {{ $tipoPeriodo === 'segunda_quincena' ? 'selected' : '' }}>Fin de Mes</option>
                        </select>
                    </div>
                    <div>
                        <select name="periodo_mes" onchange="this.form.submit()" class="text-sm font-semibold rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                                    {{ strftime('%B', mktime(0, 0, 0, $m, 1)) ?: 'Mes '.$m }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <select name="periodo_anio" onchange="this.form.submit()" class="text-sm font-semibold rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @for ($y = date('Y'); $y >= 2023; $y--)
                                <option value="{{ $y }}" {{ $y == $anio ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <select name="estado" onchange="this.form.submit()" class="text-sm font-semibold rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Todos los Estados</option>
                            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="enviada" {{ $estado === 'enviada' ? 'selected' : '' }}>Enviada</option>
                            <option value="error" {{ $estado === 'error' ? 'selected' : '' }}>Error</option>
                        </select>
                    </div>
                </form>

                <!-- Micro Stats Bar -->
                <div class="flex items-center space-x-4 text-xs font-semibold text-slate-600">
                    <span class="bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">Total: <strong>{{ $stats['total'] }}</strong></span>
                    <span class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg border border-amber-200">Pendientes: <strong>{{ $stats['pendientes'] }}</strong></span>
                    <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-200">Enviadas: <strong>{{ $stats['enviadas'] }}</strong></span>
                    <span class="bg-rose-50 text-rose-700 px-3 py-1.5 rounded-lg border border-rose-200">Errores: <strong>{{ $stats['errores'] }}</strong></span>
                </div>
            </div>

            <!-- Tabla de Boletas -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Trabajador / DNI</th>
                                <th class="px-6 py-3.5">Área</th>
                                <th class="px-6 py-3.5">Periodo / Quincena</th>
                                <th class="px-6 py-3.5">Estado Envío</th>
                                <th class="px-6 py-3.5">Último Intento</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($boletas as $boleta)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $boleta->empleado->nombre_completo ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400 font-mono">DNI: {{ $boleta->empleado->dni }} | {{ $boleta->empleado->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                    {{ $boleta->empleado->area->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $boleta->periodo_formateado }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($boleta->estado === 'enviada')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Enviada
                                    </span>
                                    @elseif ($boleta->estado === 'error')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> Error
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 me-1.5"></span> Pendiente
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    @if ($boleta->ultimoEnvio)
                                        {{ $boleta->ultimoEnvio->fecha_envio ? $boleta->ultimoEnvio->fecha_envio->format('d/m/Y H:i') : '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <!-- Visualizar PDF Protegido -->
                                    <a href="{{ route('boletas.stream', $boleta) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition" title="Previsualizar PDF de forma segura">
                                        <svg class="w-3.5 h-3.5 me-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver PDF
                                    </a>

                                    @can('gestionar-boletas')
                                    <form action="{{ route('boletas.destroy', $boleta) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta boleta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">
                                    No hay boletas registradas para el periodo seleccionado.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($boletas->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $boletas->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
