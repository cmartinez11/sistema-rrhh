<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-[#15803d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Gestión de Contratos Laborales
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Control de vigencias, alertas semafóricas de vencimiento y distribución de contratos en PDF.
                </p>
            </div>

            @can('gestionar-contratos')
            <div x-data="{ openCreateModal: false }">
                <button @click="openCreateModal = true" class="inline-flex items-center px-4 py-2.5 bg-[#15803d] hover:bg-[#166534] active:bg-[#14532d] text-white font-semibold text-sm rounded-xl shadow-md transition duration-150 gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Registrar Contrato
                </button>

                <!-- Modal de Registro de Contrato -->
                <div x-show="openCreateModal" 
                     x-cloak 
                     class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    
                    <div @click.away="openCreateModal = false" class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                        <div class="bg-[#15803d] px-6 py-4 flex items-center justify-between text-white">
                            <h3 class="font-bold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Registrar Nuevo Contrato Laboral
                            </h3>
                            <button @click="openCreateModal = false" class="text-slate-200 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('contratos.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                            @csrf

                            <!-- Empleado -->
                            <div>
                                <label for="empleado_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                    Empleado <span class="text-red-500">*</span>
                                </label>
                                <select id="empleado_id" name="empleado_id" required class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d] focus:border-[#15803d]">
                                    <option value="">-- Seleccionar Empleado --</option>
                                    @foreach($empleados as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->apellidos }}, {{ $emp->nombres }} (DNI: {{ $emp->dni }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fecha de Ingreso a la Empresa -->
                            <div>
                                <label for="fecha_ingreso" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha de Ingreso a la Empresa <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d] focus:border-[#15803d]">
                            </div>

                            <!-- Vigencia: Fecha Inicio y Fecha Fin -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_inicio" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                        Inicio Contrato <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" required class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d] focus:border-[#15803d]">
                                </div>
                                <div>
                                    <label for="fecha_fin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                        Término Contrato <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" required class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d] focus:border-[#15803d]">
                                </div>
                            </div>

                            <!-- Archivo PDF -->
                            <div>
                                <label for="pdf" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                    Archivo Contrato (PDF) <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="pdf" name="pdf" accept=".pdf" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-[#15803d] hover:file:bg-emerald-100 cursor-pointer">
                                <p class="text-[11px] text-slate-400 mt-1">Formato PDF únicamente (máx. 10MB).</p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                                <button type="button" @click="openCreateModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white transition">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-[#15803d] hover:bg-[#166534] text-white font-semibold text-xs rounded-xl shadow-md transition uppercase tracking-wider">
                                    Guardar Contrato
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alertas Flash -->
            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-xl shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Tarjetas de Métricas Semafóricas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Contratos</span>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-between p-3 text-slate-600 dark:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>

                <!-- Vigentes (Verde) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Vigentes (>30 días)</span>
                        <h3 class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-400 mt-1">{{ $stats['vigentes'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center text-emerald-600 border border-emerald-200 dark:border-emerald-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Por Vencer (Ámbar) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Por Vencer (≤30 días)</span>
                        <h3 class="text-2xl font-extrabold text-amber-700 dark:text-amber-400 mt-1">{{ $stats['por_vencer'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950 flex items-center justify-center text-amber-600 border border-amber-200 dark:border-amber-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Vencidos (Rojo) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-red-600 dark:text-red-400">Contratos Vencidos</span>
                        <h3 class="text-2xl font-extrabold text-red-700 dark:text-red-400 mt-1">{{ $stats['vencidos'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-950 flex items-center justify-center text-red-600 border border-red-200 dark:border-red-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Filtros -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700">
                <form method="GET" action="{{ route('contratos.index') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                    <!-- Empleado -->
                    <div>
                        <label for="filter_empleado" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Empleado</label>
                        <select id="filter_empleado" name="empleado_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d]">
                            <option value="">Todos los Empleados</option>
                            @foreach($empleados as $emp)
                                <option value="{{ $emp->id }}" {{ $empleadoId == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->apellidos }}, {{ $emp->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alerta de Vencimiento -->
                    <div>
                        <label for="filter_alerta" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Alerta Vencimiento</label>
                        <select id="filter_alerta" name="alerta" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d]">
                            <option value="">Todas las Alertas</option>
                            <option value="vigente" {{ $alerta === 'vigente' ? 'selected' : '' }}>🟢 Vigentes (>30 días)</option>
                            <option value="por_vencer" {{ $alerta === 'por_vencer' ? 'selected' : '' }}>🟡 Por Vencer (≤30 días)</option>
                            <option value="vencido" {{ $alerta === 'vencido' ? 'selected' : '' }}>🔴 Vencidos</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="w-full py-2 px-4 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow transition">
                            Filtrar
                        </button>
                        <a href="{{ route('contratos.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-200 text-xs font-semibold rounded-xl transition">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabla de Contratos -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="p-4">ID</th>
                                <th class="p-4">Empleado</th>
                                <th class="p-4">F. Ingreso</th>
                                <th class="p-4">Vigencia Contrato</th>
                                <th class="p-4">Alerta Vencimiento</th>
                                <th class="p-4">Estado Envío</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                            @forelse($contratos as $contrato)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition">
                                <td class="p-4 font-mono text-xs text-slate-500">#{{ $contrato->id }}</td>
                                <td class="p-4">
                                    <div class="font-semibold text-slate-900 dark:text-white">
                                        {{ $contrato->empleado->apellidos }}, {{ $contrato->empleado->nombres }}
                                    </div>
                                    <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                                        <span>DNI: {{ $contrato->empleado->dni }}</span>
                                        <span>•</span>
                                        <span>{{ $contrato->empleado->cargo->nombre ?? 'Sin Cargo' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($contrato->fecha_ingreso)->format('d/m/Y') }}
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    <span>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</span>
                                    <span class="text-slate-400 mx-1">al</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-xs {{ $contrato->alerta_badge_class }}">
                                        @if($contrato->alerta_vencimiento === 'vencido')
                                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                        @elseif($contrato->alerta_vencimiento === 'por_vencer')
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        @endif
                                        {{ $contrato->alerta_label }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($contrato->estado_envio === 'enviado')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Enviado
                                        </span>
                                    @elseif($contrato->estado_envio === 'error')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                            Error
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Ver PDF Streaming -->
                                        <a href="{{ route('contratos.stream', $contrato) }}" target="_blank" title="Previsualizar PDF" class="p-2 text-slate-500 hover:text-slate-800 dark:hover:text-white bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @can('enviar-contratos')
                                        <!-- Enviar por Correo -->
                                        <form method="POST" action="{{ route('contratos.send', $contrato) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Enviar Contrato por Correo" onclick="return confirm('¿Desea enviar el contrato laboral por correo al empleado?')" class="p-2 text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition border border-emerald-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endcan

                                        @can('gestionar-contratos')
                                        <!-- Eliminar -->
                                        <form method="POST" action="{{ route('contratos.destroy', $contrato) }}" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este registro de contrato?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar Contrato" class="p-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No se encontraron contratos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($contratos->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $contratos->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
