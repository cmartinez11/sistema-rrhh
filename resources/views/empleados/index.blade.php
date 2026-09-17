<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Gestión de Trabajadores
            </h2>
            <a href="{{ route('empleados.create') }}" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo Trabajador
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Gerencia KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Activos -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Activos</p>
                        <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $metrics['total_activos'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>

                <!-- Contratos Vigentes -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Vigentes (> 30 días)</p>
                        <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $metrics['vigentes'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Contratos Por Vencer -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Por Vencer (≤ 30 días)</p>
                        <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ $metrics['por_vencer'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Contratos Vencidos -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Contratos Vencidos</p>
                        <p class="text-2xl font-extrabold text-rose-700 mt-1">{{ $metrics['vencidos'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('empleados.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Apellido, DNI o Correo..." class="w-full text-sm rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>

                    <!-- Filter Area -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Área</label>
                        <select name="area_id" class="w-full text-sm rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Todas las Áreas</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Cargo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cargo</label>
                        <select name="cargo_id" class="w-full text-sm rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Todos los Cargos</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->id }}" {{ request('cargo_id') == $cargo->id ? 'selected' : '' }}>{{ $cargo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Semáforo Contrato -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Semáforo Contrato</label>
                        <select name="estado_contrato" class="w-full text-sm rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Todos</option>
                            <option value="vigente" {{ request('estado_contrato') == 'vigente' ? 'selected' : '' }}>Vigentes (> 30 días)</option>
                            <option value="por_vencer" {{ request('estado_contrato') == 'por_vencer' ? 'selected' : '' }}>Por Vencer (≤ 30 días)</option>
                            <option value="vencido" {{ request('estado_contrato') == 'vencido' ? 'selected' : '' }}>Vencidos</option>
                            <option value="sin_contrato" {{ request('estado_contrato') == 'sin_contrato' ? 'selected' : '' }}>Sin fecha fin</option>
                        </select>
                    </div>

                    <!-- Filter Estado -->
                    <div class="flex items-end space-x-2">
                        <div class="w-full">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Estado</label>
                            <select name="estado" class="w-full text-sm rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Employees Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">DNI</th>
                                <th class="px-6 py-3.5">Nombre</th>
                                <th class="px-6 py-3.5">Área / Cargo</th>
                                <th class="px-6 py-3.5">Vigencia Contrato</th>
                                <th class="px-6 py-3.5">Estado Contrato</th>
                                <th class="px-6 py-3.5">Correo Registrado</th>
                                <th class="px-6 py-3.5">Estado</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($empleados as $emp)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-800">
                                    {{ $emp->dni }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $emp->nombre_completo }}</div>
                                    <div class="text-xs text-slate-400">Ingreso: {{ $emp->fecha_ingreso ? $emp->fecha_ingreso->format('d/m/Y') : '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $emp->area->nombre ?? 'N/A' }}
                                    </span>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $emp->cargo->nombre ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-600 whitespace-nowrap">
                                    @if ($emp->fecha_inicio_contrato || $emp->fecha_fin_contrato)
                                        <div>
                                            {{ $emp->fecha_inicio_contrato ? $emp->fecha_inicio_contrato->format('d/m/Y') : 'S/F' }}
                                            <span class="text-slate-400 mx-1">-</span>
                                            {{ $emp->fecha_fin_contrato ? $emp->fecha_fin_contrato->format('d/m/Y') : 'S/F' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">No registrada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $alerta = $emp->alerta_contrato; @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shadow-sm border {{ $alerta['badge'] }}">
                                        {{ $alerta['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    <div>{{ $emp->email }}</div>
                                    <div class="text-xs text-slate-400">Tel: {{ $emp->telefono ?? 'Sin teléfono' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($emp->estado === 'activo')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Activo
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Inactivo
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('empleados.edit', $emp) }}" class="inline-flex items-center p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('empleados.destroy', $emp) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este empleado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-400 italic">
                                    No se encontraron empleados registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($empleados->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $empleados->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
