<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Registrar Nuevo Empleado
            </h2>
            <a href="{{ route('empleados.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                &larr; Volver al Listado
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm">
                <form action="{{ route('empleados.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombres -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nombres *</label>
                            <input type="text" name="nombres" value="{{ old('nombres') }}" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @error('nombres') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Apellidos *</label>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @error('apellidos') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- DNI -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">DNI (Documento de Identidad) *</label>
                            <input type="text" name="dni" value="{{ old('dni') }}" maxlength="20" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500 font-mono">
                            @error('dni') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Correo Electrónico (Registrado) *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @error('email') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Área -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Área *</label>
                            <select name="area_id" id="area_select" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">Seleccione Área...</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                            @error('area_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cargo -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Cargo *</label>
                            <select name="cargo_id" id="cargo_select" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">Seleccione Cargo...</option>
                                @foreach ($cargos as $cargo)
                                    <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>{{ $cargo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('cargo_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha Ingreso -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Fecha de Ingreso *</label>
                            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            @error('fecha_ingreso') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Estado *</label>
                            <select name="estado" required class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Teléfono / Celular</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 987654321" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('empleados.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-md transition">
                            Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
