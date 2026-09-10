<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
            <svg class="w-7 h-7 me-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Configuración General del Sistema
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <form action="{{ route('configuracion.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Panel SMTP & Remitente -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center">
                            <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Credenciales & Servidor SMTP cPanel
                        </h3>
                        <p class="text-xs text-slate-500">Parámetros de conexión al hosting cPanel para salida de correos</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Host SMTP *</label>
                            <input type="text" name="mail_host" value="{{ old('mail_host', $configuraciones['mail_host']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Puerto *</label>
                            <input type="number" name="mail_port" value="{{ old('mail_port', $configuraciones['mail_port']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Encriptación *</label>
                            <select name="mail_encryption" required class="w-full rounded-xl border-slate-300 text-sm">
                                <option value="ssl" {{ $configuraciones['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL (Puerto 465)</option>
                                <option value="tls" {{ $configuraciones['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS (Puerto 587)</option>
                                <option value="none" {{ $configuraciones['mail_encryption'] == 'none' ? 'selected' : '' }}>Ninguna (25)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Usuario SMTP *</label>
                            <input type="text" name="mail_username" value="{{ old('mail_username', $configuraciones['mail_username']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Contraseña SMTP</label>
                            <input type="password" name="mail_password" placeholder="••••••••" class="w-full rounded-xl border-slate-300 text-sm">
                            <span class="text-[10px] text-slate-400">Dejar en blanco para mantener contraseña actual</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Throttling (Correos/Minuto) *</label>
                            <input type="number" name="mail_throttle_per_minute" value="{{ old('mail_throttle_per_minute', $configuraciones['mail_throttle_per_minute']) }}" min="1" max="100" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Correo Remitente *</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $configuraciones['mail_from_address']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nombre Remitente *</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $configuraciones['mail_from_name']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Panel Almacenamiento -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center">
                            <svg class="w-5 h-5 me-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                            Carpeta Compartida de Boletas
                        </h3>
                        <p class="text-xs text-slate-500">Ubicación del servidor donde se almacenan y leen los PDFs de boletas de pago</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Ruta Absoluta de Almacenamiento Compartido *</label>
                        <input type="text" name="boletas_storage_path" value="{{ old('boletas_storage_path', $configuraciones['boletas_storage_path']) }}" required class="w-full rounded-xl border-slate-300 text-sm font-mono bg-slate-50">
                        <span class="text-xs text-slate-500 mt-1 block">Estructura automática: <code class="text-indigo-600 font-bold">/boletas/{año}/{mes}/{dni_empleado}.pdf</code></span>
                    </div>
                </div>

                <!-- Panel Plantilla de Correo -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center">
                            <svg class="w-5 h-5 me-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Plantilla Editable de Correo Electrónico
                        </h3>
                        <p class="text-xs text-slate-500">Personalice el asunto y cuerpo del correo que se enviará automáticamente a los empleados</p>
                    </div>

                    <!-- Etiquetas Dinámicas -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <span class="text-xs font-bold text-slate-700 uppercase">Variables dinámicas disponibles para usar en el texto:</span>
                        <div class="flex flex-wrap gap-2 text-xs font-mono pt-1">
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{nombre}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{dni}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{periodo}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{periodo_mes}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{periodo_anio}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{cargo}</span>
                            <span class="px-2 py-1 bg-white border rounded text-cyan-700">{area}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Asunto del Correo *</label>
                        <input type="text" name="mail_plantilla_asunto" value="{{ old('mail_plantilla_asunto', $configuraciones['mail_plantilla_asunto']) }}" required class="w-full rounded-xl border-slate-300 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Cuerpo del Correo (HTML) *</label>
                        <textarea name="mail_plantilla_cuerpo" rows="8" required class="w-full rounded-xl border-slate-300 text-sm font-mono">{{ old('mail_plantilla_cuerpo', $configuraciones['mail_plantilla_cuerpo']) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition">
                        Guardar Toda la Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
