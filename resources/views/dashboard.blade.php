<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Panel Principal de RRHH - Plásticos Fénix
            </h2>
            <span class="text-sm font-medium text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                Periodo Actual: <strong class="text-slate-800">{{ strftime('%B', mktime(0, 0, 0, $currentMonth, 1)) ?: 'Mes '.$currentMonth }} {{ $currentYear }}</strong>
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Metric Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Empleados -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Empleados</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalEmpleados }}</h3>
                            <p class="text-xs text-emerald-600 font-medium mt-1">{{ $empleadosActivos }} activos en planilla</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Boletas Pendientes -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Boletas Pendientes</p>
                            <h3 class="text-3xl font-extrabold text-amber-600 mt-2">{{ $boletasPendientes }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Listas para envío masivo</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Boletas Enviadas -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Boletas Enviadas</p>
                            <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $boletasEnviadas }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Notificadas a empleados</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Envíos con Error -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Envíos Fallidos</p>
                            <h3 class="text-3xl font-extrabold text-rose-600 mt-2">{{ $boletasError }}</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Requieren reintento</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Banners -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1">
                    <h4 class="text-xl font-bold flex items-center">
                        <svg class="w-6 h-6 me-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Envío de Boletas del Periodo {{ $currentMonth }}/{{ $currentYear }}
                    </h4>
                    <p class="text-sm text-slate-300">
                        Procesa de forma automática los envíos en segundo plano vía SMTP cPanel sin bloquear la interfaz.
                    </p>
                </div>
                <div class="flex items-center space-x-3 w-full md:w-auto">
                    @can('enviar-boletas')
                    <form action="{{ route('envios.dispatch') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="periodo_mes" value="{{ $currentMonth }}">
                        <input type="hidden" name="periodo_anio" value="{{ $currentYear }}">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/25 transition flex items-center justify-center">
                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Enviar Boletas Pendientes
                        </button>
                    </form>
                    @endcan

                    <a href="{{ route('boletas.index') }}" class="w-full md:w-auto px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl border border-slate-700 transition flex items-center justify-center">
                        Ver Gestión de Boletas
                    </a>
                </div>
            </div>

            <!-- Recent Mail Audit Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Últimos Envíos Registrados</h3>
                        <p class="text-xs text-slate-500">Historial en tiempo real de correos procesados</p>
                    </div>
                    @can('ver-auditoria')
                    <a href="{{ route('auditoria.index') }}" class="text-sm font-semibold text-cyan-600 hover:text-cyan-700 flex items-center">
                        Ver historial completo &rarr;
                    </a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Empleado</th>
                                <th class="px-6 py-3.5">Periodo</th>
                                <th class="px-6 py-3.5">Fecha y Hora</th>
                                <th class="px-6 py-3.5">Estado</th>
                                <th class="px-6 py-3.5">Enviado Por</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($ultimosEnvios as $envio)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $envio->boleta->empleado->nombre_completo ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">{{ $envio->boleta->empleado->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $envio->boleta->periodo_formateado ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i:s') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($envio->estado_envio === 'exito')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Éxito
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800" title="{{ $envio->mensaje_error }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> Fallido
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ $envio->usuario->name ?? 'Sistema (Auto)' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                    No se registran envíos recientes de boletas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
