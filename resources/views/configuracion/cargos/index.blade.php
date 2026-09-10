<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center">
                <svg class="w-7 h-7 me-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Estructura Organizacional — Cargos
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        openCreate: false,
        openEdit: false,
        editCargo: { id: null, nombre: '' },
        setEdit(cargo) {
            this.editCargo = { id: cargo.id, nombre: cargo.nombre };
            this.openEdit = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-messages />

            <!-- Pestañas de Navegación de Estructura Organizacional -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
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

                <button @click="openCreate = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Cargo
                </button>
            </div>

            <!-- Tabla de Cargos -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">Nombre del Cargo</th>
                                <th class="px-6 py-3.5">Empleados Asignados</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($cargos as $cargo)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                                    #{{ $cargo->id }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $cargo->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $cargo->empleados_count }} empleado(s)
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="setEdit({{ json_encode($cargo) }})" class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition" title="Editar Cargo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('configuracion.cargos.destroy', $cargo) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar el cargo \'{{ $cargo->nombre }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Eliminar Cargo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">
                                    No hay cargos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($cargos->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $cargos->links() }}
                </div>
                @endif
            </div>

            <!-- Modal Crear Cargo -->
            <div x-show="openCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="openCreate = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Registrar Nuevo Cargo</h3>
                        <button @click="openCreate = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>
                    <form action="{{ route('configuracion.cargos.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre del Cargo *</label>
                            <input type="text" name="nombre" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ej. Asistente Administrativo">
                        </div>
                        <div class="flex justify-end space-x-2 pt-4">
                            <button type="button" @click="openCreate = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold">Cancelar</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md">Guardar Cargo</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Editar Cargo -->
            <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="openEdit = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Editar Cargo</h3>
                        <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>
                    <form :action="'{{ url('configuracion/cargos') }}/' + editCargo.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre del Cargo *</label>
                            <input type="text" name="nombre" x-model="editCargo.nombre" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div class="flex justify-end space-x-2 pt-4">
                            <button type="button" @click="openEdit = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold">Cancelar</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md">Actualizar Cargo</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
