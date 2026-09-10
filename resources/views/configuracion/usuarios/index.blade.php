<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Gestión de Usuarios del Sistema
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        openCreate: false,
        openEdit: false,
        editUser: { id: null, name: '', email: '', rol: '' },
        setEdit(user) {
            this.editUser = {
                id: user.id,
                name: user.name,
                email: user.email,
                rol: user.rol || ''
            };
            this.openEdit = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 text-sm space-y-1">
                    <div class="font-bold flex items-center">
                        <svg class="w-5 h-5 me-2 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Por favor corregir los siguientes errores:
                    </div>
                    <ul class="list-disc list-inside ps-7 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Pestañas de Navegación de Estructura y Configuración -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex space-x-2 border-b border-slate-100 w-full sm:w-auto overflow-x-auto">
                    <a href="{{ route('configuracion.areas.index') }}" class="px-5 py-2.5 text-sm font-bold rounded-xl transition {{ request()->routeIs('configuracion.areas.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        Gestión de Áreas
                    </a>
                    <a href="{{ route('configuracion.cargos.index') }}" class="px-5 py-2.5 text-sm font-bold rounded-xl transition {{ request()->routeIs('configuracion.cargos.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        Gestión de Cargos
                    </a>
                    <a href="{{ route('configuracion.usuarios.index') }}" class="px-5 py-2.5 text-sm font-bold rounded-xl transition {{ request()->routeIs('configuracion.usuarios.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                        Usuarios del Sistema
                    </a>
                </div>

                <button @click="openCreate = true" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Nuevo Usuario
                </button>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">Nombre Completo</th>
                                <th class="px-6 py-3.5">Correo Electrónico</th>
                                <th class="px-6 py-3.5">Rol Asignado</th>
                                <th class="px-6 py-3.5">Fecha de Registro</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($usuarios as $usuario)
                            @php
                                $userRole = $usuario->roles->first()?->name ?? 'Sin Rol';
                                $isSelf = auth()->id() === $usuario->id;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $isSelf ? 'bg-amber-50/30' : '' }}">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                                    #{{ $usuario->id }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-extrabold uppercase shrink-0">
                                        {{ substr($usuario->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div>{{ $usuario->name }}</div>
                                        @if($isSelf)
                                            <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">(Tu cuenta)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                    {{ $usuario->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($userRole === 'Administrador')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            <svg class="w-3 h-3 me-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            {{ $userRole }}
                                        </span>
                                    @elseif($userRole === 'Jefe de RRHH')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            {{ $userRole }}
                                        </span>
                                    @elseif($userRole === 'Asistente de RRHH')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                            {{ $userRole }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $userRole }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                                    {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="setEdit({{ json_encode(['id' => $usuario->id, 'name' => $usuario->name, 'email' => $usuario->email, 'rol' => $userRole]) }})" class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition" title="Editar Usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    @if ($isSelf)
                                        <button disabled type="button" class="p-2 text-slate-300 cursor-not-allowed rounded-lg" title="No puedes eliminar tu propia cuenta">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <form action="{{ route('configuracion.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar al usuario \'{{ $usuario->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar Usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 italic">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($usuarios->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $usuarios->links() }}
                </div>
                @endif
            </div>

            <!-- Modal Crear Usuario -->
            <div x-show="openCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="openCreate = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Registrar Nuevo Usuario</h3>
                        <button @click="openCreate = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
                    </div>
                    <form action="{{ route('configuracion.usuarios.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre Completo *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ej. Juan Pérez">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Correo Electrónico *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="juan.perez@fenix.com.pe">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Contraseña *</label>
                                <input type="password" name="password" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mínimo 8 caracteres">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Repita la contraseña">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Rol del Sistema *</label>
                            <select name="rol" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="" disabled selected>Seleccione un rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('rol') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="openCreate = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Cancelar</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition">Guardar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Editar Usuario -->
            <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="openEdit = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Editar Usuario</h3>
                        <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
                    </div>
                    <form :action="'{{ url('configuracion/usuarios') }}/' + editUser.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre Completo *</label>
                            <input type="text" name="name" x-model="editUser.name" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Correo Electrónico *</label>
                            <input type="email" name="email" x-model="editUser.email" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Rol del Sistema *</label>
                            <select name="rol" x-model="editUser.rol" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <span class="block text-xs font-bold text-slate-700 uppercase">Cambiar Contraseña (Opcional)</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Nueva Contraseña</label>
                                    <input type="password" name="password" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Dejar en blanco si no cambia">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Confirmar Contraseña</label>
                                    <input type="password" name="password_confirmation" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Repita la nueva contraseña">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                            <button type="button" @click="openEdit = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Cancelar</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
