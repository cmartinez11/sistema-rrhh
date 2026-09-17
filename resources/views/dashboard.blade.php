<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                    <div class="p-2 bg-green-700 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    Panel Principal de Gestión de RRHH
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Consolidado de personal, vigencias contractuales y emisiones masivas.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-700 text-white">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    Periodo: {{ Str::upper(now()->locale('es')->translatedFormat('F Y')) }}
                </span> 
            </div>
        </div>
    </x-slot>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <x-flash-messages />

        <!-- 1. Fila Superior: 4 KPIs Principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            
            <!-- Card 1: Total Colaboradores -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Colaboradores</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalEmpleados }}</h3>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-emerald-700 font-semibold">{{ $empleadosActivos }} activos</span>
                    <a href="{{ route('empleados.index') }}" class="font-bold text-[#15803d] hover:underline inline-flex items-center gap-1">
                        Ver empleados &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: Vigencia de Contratos -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Vigencia de Contratos</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @if($contratosPorVencer > 0)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                {{ $contratosPorVencer }} por vencer
                            </span>
                        @endif
                        @if($contratosVencidos > 0)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                {{ $contratosVencidos }} vencidos
                            </span>
                        @endif
                        @if($contratosPorVencer == 0 && $contratosVencidos == 0)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                ✓ Todos al día
                            </span>
                        @endif
                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Alertas de venc.</span>
                    <a href="{{ route('empleados.index') }}" class="font-bold text-[#15803d] hover:underline inline-flex items-center gap-1">
                        Ver lista de contratos &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 3: Emisiones del Mes -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Emisiones del Mes</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-indigo-700 tracking-tight">{{ $documentosEmitidosMes }}</h3>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-indigo-600 font-semibold">Total despachados</span>
                    <span class="text-slate-400">Salida de correos</span>
                </div>
            </div>

            <!-- Card 4: Servidor SMTP -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Servidor</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $enviosExitososMes }} <span class="text-sm font-semibold text-slate-400">exitosos</span></h3>
                </div>
                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    @if($enviosFallidosMes > 0)
                        <span class="text-rose-600 font-bold flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            {{ $enviosFallidosMes }} fallidos
                        </span>
                    @else
                        <span class="text-emerald-700 font-semibold flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Servidor activo
                        </span>
                    @endif
                    <a href="{{ route('auditoria.index') }}" class="font-bold text-[#15803d] hover:underline inline-flex items-center gap-1">
                        Ver auditoría &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Acciones Rápidas de Emisión -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 tracking-tight">Acciones Rápidas</h3>
                    <p class="text-xs text-slate-500">Accesos directos para la gestión de documentos laborales.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-[#15803d] rounded-lg border border-emerald-200">
                    Gestión RRHH
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Botón 1: Boletas de Pago -->
                <a href="{{ route('boletas.index') }}" class="group p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-300 transition-all duration-200 flex flex-col justify-between space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100/80 text-[#15803d] flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-extrabold text-sm text-slate-900 group-hover:text-[#15803d]">Enviar Boletas de Pago</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Enviar las boletas a los trabajadores por correo.</span>
                    </div>
                </a>

                <!-- Botón 2: Contrato Laboral -->
                <a href="{{ route('contratos.index') }}" class="group p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-cyan-50/60 hover:border-cyan-300 transition-all duration-200 flex flex-col justify-between space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-cyan-100/80 text-cyan-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-extrabold text-sm text-slate-900 group-hover:text-cyan-700">Enviar Contrato Laboral</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Envio de contratos de trabajadores por correo.</span>
                    </div>
                </a>

                <!-- Botón 3: Memorándums -->
                <a href="{{ route('documentos.memorandums.index') }}" class="group p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-amber-50/60 hover:border-amber-300 transition-all duration-200 flex flex-col justify-between space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100/80 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-extrabold text-sm text-slate-900 group-hover:text-amber-700">Enviar Memorándum</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Envio de amonestaciones y comunicaciones por correo.</span>
                    </div>
                </a>

                <!-- Botón 4: RIT / Políticas -->
                <a href="{{ route('documentos.rit.index') }}" class="group p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50/60 hover:border-purple-300 transition-all duration-200 flex flex-col justify-between space-y-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100/80 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-extrabold text-sm text-slate-900 group-hover:text-purple-700">RIT / Políticas</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Reglamento interno y normativas.</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- 2. Fila Inferior: Grid Responsivo xl:grid-cols-12 -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

            <!-- Columna Izquierda: Categorías (xl:col-span-4) -->
            <div class="xl:col-span-4 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 tracking-tight">Emisiones por Categoría</h3>
                    <p class="text-xs text-slate-500">Desglose consolidado del periodo actual.</p>
                </div>

                <div class="space-y-2.5">
                    <!-- Item 1: Boletas -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Boletas de Pago
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $desgloseDocumentos['boletas'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 2: Contratos -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Contratos Laborales
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-cyan-100 text-cyan-800 border border-cyan-200">
                            {{ $desgloseDocumentos['contratos'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 3: RIT -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span> Reglamento Interno (RIT)
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                            {{ $desgloseDocumentos['rit'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 4: Políticas -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Políticas de Empresa
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            {{ $desgloseDocumentos['politicas'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 5: Memorándums -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Memorándums
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                            {{ $desgloseDocumentos['memorandums'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 6: No Renovación -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Cartas de No Renovación
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                            {{ $desgloseDocumentos['no_renovacion'] ?? 0 }}
                        </span>
                    </div>

                    <!-- Item 7: Despidos -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/80 transition">
                        <span class="font-semibold text-xs text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-600"></span> Cartas de Despido
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-200">
                            {{ $desgloseDocumentos['despido'] ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Tabla Últimos Envíos (xl:col-span-8) -->
            <div class="xl:col-span-8 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 tracking-tight">Últimos Envíos Procesados</h3>
                            <p class="text-xs text-slate-500">Feed multidocumento en tiempo real de notificaciones despachadas.</p>
                        </div>
                        @can('ver-auditoria')
                        <a href="{{ route('auditoria.index') }}" class="text-xs font-extrabold text-[#15803d] hover:underline inline-flex items-center gap-1 whitespace-nowrap">
                            Auditoría Completa &rarr;
                        </a>
                        @endcan
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 w-32 whitespace-nowrap">DOCUMENTO</th>
                                    <th class="py-3 px-4 w-48">COLABORADOR</th>
                                    <th class="py-3 px-4 min-w-[200px]">ASUNTO / DETALLE</th>
                                    <th class="py-3 px-4 w-36 whitespace-nowrap">FECHA Y HORA</th>
                                    <th class="py-3 px-4 w-24 text-center">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($ultimosEnviosMultidocumento as $envio)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <!-- DOCUMENTO -->
                                    <td class="py-3.5 px-4 w-32 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $envio->badge_class ?? 'bg-slate-100 text-slate-800' }}">
                                            {{ $envio->tipo_nombre }}
                                        </span>
                                    </td>

                                    <!-- COLABORADOR -->
                                    <td class="py-3.5 px-4 w-48">
                                        <div class="font-bold text-slate-900 text-xs">{{ $envio->empleado_nombre }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">DNI: {{ $envio->empleado_dni }}</div>
                                    </td>

                                    <!-- ASUNTO / DETALLE -->
                                    <td class="py-3.5 px-4 min-w-[200px] text-xs text-slate-700 font-medium">
                                        {{ $envio->detalle }}
                                    </td>

                                    <!-- FECHA Y HORA -->
                                    <td class="py-3.5 px-4 w-36 whitespace-nowrap text-xs font-mono text-slate-500">
                                        {{ $envio->fecha_envio ? $envio->fecha_envio->setTimezone('America/Lima')->format('d/m/Y H:i') : 'N/A' }}
                                    </td>

                                    <!-- ESTADO -->
                                    <td class="py-3.5 px-4 w-24 text-center whitespace-nowrap">
                                        @if ($envio->estado_envio === 'exito')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Éxito
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800" title="{{ $envio->mensaje_error }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> Fallido
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500">No se registran envíos recientes de documentos.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @can('ver-auditoria')
                <div class="p-4 bg-slate-50 border-t border-slate-100 text-center rounded-b-2xl">
                    <a href="{{ route('auditoria.index') }}" class="text-xs font-extrabold text-[#15803d] hover:underline inline-flex items-center gap-1">
                        Ver historial completo de envíos en auditoría &rarr;
                    </a>
                </div>
                @endcan
            </div>

        </div>

    </div>
</x-app-layout>
