<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-black tracking-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Auditoría
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Trazabilidad de correos enviados.
                </p>
            </div>

            @can('enviar-boletas')
            @if($tipo === 'boletas')
            <form action="{{ route('envios.retry') }}" method="POST" class="inline" onsubmit="return confirm('¿Desea reintentar el envío de todas las boletas registradas con estado de error?');">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reintentar Fallidos (Boletas)
                </button>
            </form>
            @endif
            @endcan
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        openModal: false,
        loading: false,
        activeModalTab: 'preview',
        copied: false,
        detail: null,
        fetchDetail(id, tipoEnvio) {
            this.openModal = true;
            this.loading = true;
            this.activeModalTab = 'preview';
            this.copied = false;
            this.detail = null;
            fetch('/auditoria/envios/' + id + '?tipo=' + tipoEnvio)
                .then(res => res.json())
                .then(data => {
                    this.detail = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        },
        copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text);
            } else {
                let textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }
            this.copied = true;
            setTimeout(() => this.copied = false, 3000);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Pestañas de Filtro Rápido de Auditoría -->
            <div class="flex items-center space-x-1.5 border-b border-slate-200 dark:border-slate-700 pb-2 overflow-x-auto">
                <a href="{{ route('auditoria.index', ['tipo' => 'todos']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'todos' ? 'bg-slate-900 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Todas las Emisiones
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'boletas']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'boletas' ? 'bg-[#15803d] text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Boletas de Pago
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'contratos']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'contratos' ? 'bg-cyan-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Contratos Laborales
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'rit']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'rit' ? 'bg-purple-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Reglamento Interno (RIT)
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'politicas']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'politicas' ? 'bg-indigo-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Políticas de la Empresa
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'memorandums']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'memorandums' ? 'bg-amber-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Memorándums
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'no_renovacion']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'no_renovacion' ? 'bg-rose-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    No Renovación
                </a>

                <a href="{{ route('auditoria.index', ['tipo' => 'despido']) }}" 
                   class="px-3.5 py-2 font-bold text-xs rounded-xl transition duration-150 whitespace-nowrap {{ $tipo === 'despido' ? 'bg-red-700 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 border border-slate-200 dark:border-slate-700' }}">
                    Cartas de Despido
                </a>
            </div>

            <!-- Filtros de Búsqueda -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <form method="GET" action="{{ route('auditoria.index') }}" class="flex flex-wrap items-center gap-4">
                    <input type="hidden" name="tipo" value="{{ $tipo }}">

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Estado de Envío</label>
                        <select name="estado" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-[#15803d]">
                            <option value="">Todos los Estados</option>
                            <option value="exito" {{ request('estado') === 'exito' ? 'selected' : '' }}>Éxito</option>
                            <option value="fallido" {{ request('estado') === 'fallido' ? 'selected' : '' }}>Fallido</option>
                        </select>
                    </div>

                    @if(request('estado'))
                    <div class="self-end">
                        <a href="{{ route('auditoria.index', ['tipo' => $tipo]) }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                            Limpiar Filtros
                        </a>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Tabla de Auditoría -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="p-4">ID</th>
                                <th class="p-4">Tipo de Documento</th>
                                <th class="p-4">Empleado / DNI</th>
                                <th class="p-4">Fecha y Hora Envío</th>
                                <th class="p-4">Estado</th>
                                <th class="p-4">Respuesta SMTP / Log</th>
                                <th class="p-4">Operador</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse ($envios as $envio)
                            @php
                                $emp = $envio->empleado_ref;
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition">
                                <td class="p-4 font-mono text-xs font-bold text-slate-400">
                                    #{{ $envio->id }}
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $envio->badge_class }}">
                                        {{ $envio->tipo_nombre }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $emp ? $emp->nombre_completo : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        DNI: {{ $emp->dni ?? '-' }} | {{ $emp->email ?? '-' }}
                                    </div>
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600 dark:text-slate-300 font-mono">
                                    {{ $envio->fecha_envio ? $envio->fecha_envio->setTimezone('America/Lima')->format('d/m/Y H:i:s') : 'N/A' }}
                                </td>
                                <td class="p-4">
                                    @if ($envio->estado_envio === 'exito')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Éxito
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> Fallido
                                    </span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs max-w-xs truncate">
                                    @if ($envio->mensaje_error)
                                        <span class="text-rose-600 dark:text-rose-400 font-mono" title="{{ $envio->mensaje_error }}">{{ $envio->mensaje_error }}</span>
                                    @else
                                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">Entregado a cPanel SMTP</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-slate-600 dark:text-slate-300">
                                    {{ $envio->usuario->name ?? 'Sistema (Auto)' }}
                                </td>
                                <td class="p-4 text-right">
                                    <button @click="fetchDetail({{ $envio->id }}, '{{ $envio->origen_tipo }}')" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-[#15803d] font-semibold rounded-xl text-xs transition border border-emerald-200/80 shadow-xs gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Detalle Mail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 italic">
                                    No se registran auditorías de envío para esta sección.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($envios->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    {{ $envios->links() }}
                </div>
                @endif
            </div>

            <!-- Modal Rediseñado: Detalle Técnico del Correo (Estilo Gmail + Código Fuente) -->
            <div x-show="openModal" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div @click.away="openModal = false" class="bg-white dark:bg-slate-800 rounded-2xl max-w-4xl w-full p-6 shadow-2xl space-y-5 border border-slate-200 dark:border-slate-700 my-auto">
                    
                    <!-- Header Modal -->
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950 rounded-xl text-[#15803d] border border-emerald-200 dark:border-emerald-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-3">
                                    Detalle Técnico del Correo
                                    <template x-if="detail">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full"
                                              :class="detail.estado === 'exito' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300'"
                                              x-text="detail.estado === 'exito' ? 'Éxito' : 'Fallido'"></span>
                                    </template>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Inspección de encabezados SMTP, cPanel Message-ID y fuente RFC 2822.</p>
                            </div>
                        </div>
                        <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Spinner Loading -->
                    <template x-if="loading">
                        <div class="py-16 text-center space-y-3">
                            <div class="inline-block w-8 h-8 border-4 border-[#15803d] border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Obteniendo metadatos SMTP y código fuente del servidor...</p>
                        </div>
                    </template>

                    <!-- Content Loaded -->
                    <template x-if="!loading && detail">
                        <div class="space-y-5">
                            <!-- Ficha Técnica de Encabezados (Estilo Gmail) -->
                            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-4 font-mono text-xs text-slate-700 dark:text-slate-300 space-y-2 leading-relaxed">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-1">
                                    <span class="font-bold text-slate-500 uppercase md:col-span-1">ID Mensaje:</span>
                                    <span class="text-[#15803d] font-bold select-all md:col-span-5" x-text="detail.message_id"></span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-1">
                                    <span class="font-bold text-slate-500 uppercase md:col-span-1">Fecha (Perú):</span>
                                    <span class="text-slate-800 dark:text-slate-200 md:col-span-5" x-text="detail.fecha"></span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-1">
                                    <span class="font-bold text-slate-500 uppercase md:col-span-1">De:</span>
                                    <span class="text-slate-800 dark:text-slate-200 md:col-span-5" x-text="detail.de"></span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-1">
                                    <span class="font-bold text-slate-500 uppercase md:col-span-1">Para:</span>
                                    <span class="text-slate-800 dark:text-slate-200 md:col-span-5" x-text="detail.para"></span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-1">
                                    <span class="font-bold text-slate-500 uppercase md:col-span-1">Asunto:</span>
                                    <span class="font-bold text-slate-900 dark:text-white md:col-span-5" x-text="detail.asunto"></span>
                                </div>
                            </div>

                            <!-- Error si existe -->
                            <template x-if="detail.error">
                                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-mono">
                                    <span class="font-bold uppercase tracking-wider block text-rose-800 mb-1">Error SMTP / Entrega:</span>
                                    <div x-text="detail.error"></div>
                                </div>
                            </template>

                            <!-- Pestañas de Modal (Vista Previa vs Código Fuente Original) -->
                            <div>
                                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                                    <div class="flex items-center space-x-2">
                                        <button @click="activeModalTab = 'preview'" 
                                                class="px-4 py-2 font-bold text-xs rounded-xl transition gap-2 inline-flex items-center"
                                                :class="activeModalTab === 'preview' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Vista Previa del Correo
                                        </button>

                                        <button @click="activeModalTab = 'source'" 
                                                class="px-4 py-2 font-bold text-xs rounded-xl transition gap-2 inline-flex items-center"
                                                :class="activeModalTab === 'source' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                            Ver Código Fuente Original
                                        </button>
                                    </div>

                                    <!-- Botón Copiar cuando está en vista Código Fuente -->
                                    <template x-if="activeModalTab === 'source'">
                                        <button @click="copyToClipboard(detail.raw_source)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-[#15803d] font-bold text-xs rounded-lg border border-emerald-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                            <span x-text="copied ? '¡Copiado al Portapapeles!' : 'Copiar en el portapapeles'"></span>
                                        </button>
                                    </template>
                                </div>

                                <!-- Contenido Tab 1: Vista Previa -->
                                <div x-show="activeModalTab === 'preview'" class="border border-slate-200 dark:border-slate-700 rounded-xl bg-white p-2 min-h-[300px] shadow-inner">
                                    <iframe :srcdoc="detail.cuerpo_html" class="w-full min-h-[320px] rounded-lg border-0"></iframe>
                                </div>

                                <!-- Contenido Tab 2: Código Fuente Original -->
                                <div x-show="activeModalTab === 'source'">
                                    <pre class="bg-slate-950 text-slate-200 p-4 rounded-xl text-xs font-mono overflow-x-auto max-h-96 whitespace-pre-wrap leading-relaxed border border-slate-800 select-all" x-text="detail.raw_source"></pre>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
