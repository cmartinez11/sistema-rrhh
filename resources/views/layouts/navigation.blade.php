<nav x-data="{ open: false }" class="border-b border-slate-800 text-white shadow-md" style="background-color: #15803d;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo Plásticos Fénix -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <img src="{{ asset('logo2.png') }}" alt="PLASTICOS FENIX" class="h-16 w-auto object-contain" style="margin-bottom: 20px">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-cyan-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    @can('gestionar-empleados')
                    <a href="{{ route('empleados.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('empleados.*') ? 'bg-slate-800 text-cyan-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Trabajadores
                    </a>
                    @endcan

                    <!-- Dropdown Emitir Documentos -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative inline-flex items-center">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('boletas.*') || request()->routeIs('contratos.*') || request()->routeIs('documentos.*') ? 'bg-slate-800 text-cyan-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Emitir Documentos
                            <svg class="w-4 h-4 ms-1 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-60 rounded-2xl bg-white shadow-xl border border-slate-200/80 py-2 z-50 text-slate-800">
                            <a href="{{ route('boletas.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('boletas.*') ? 'text-emerald-700 bg-emerald-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Boletas de Pago
                            </a>
                            <a href="{{ route('contratos.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('contratos.*') ? 'text-emerald-700 bg-emerald-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Contratos Laborales
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('documentos.rit.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('documentos.rit.*') ? 'text-emerald-700 bg-emerald-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Reglamento Interno (RIT)
                            </a>
                            <a href="{{ route('documentos.politicas.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('documentos.politicas.*') ? 'text-indigo-700 bg-indigo-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7m4 0V5"/></svg>
                                Políticas de la Empresa
                            </a>
                            <a href="{{ route('documentos.memorandums.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('documentos.memorandums.*') ? 'text-amber-700 bg-amber-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Memorándum
                            </a>
                            <a href="{{ route('documentos.no-renovacion.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('documentos.no-renovacion.*') ? 'text-cyan-700 bg-cyan-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Carta de No Renovación
                            </a>
                            <a href="{{ route('documentos.despido.index') }}" class="flex items-center px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50 {{ request()->routeIs('documentos.despido.*') ? 'text-rose-700 bg-rose-50/50' : 'text-slate-700' }}">
                                <svg class="w-4 h-4 me-2.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Carta de Despido
                            </a>
                        </div>
                    </div>

                    @can('ver-auditoria')
                    <a href="{{ route('auditoria.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('auditoria.*') ? 'bg-slate-800 text-cyan-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Auditoría
                    </a>
                    @endcan

                    @if(auth()->user()->hasAnyRole(['Administrador']))
                        @can('gestionar-configuracion')
                        <a href="{{ route('configuracion.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('configuracion.index') ? 'bg-slate-800 text-cyan-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Configuración
                        </a>
                        @endcan
                    @endif
                </div>
            </div>

            <!-- User Dropdown & Role Badge -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                @if(Auth::user()->roles->first())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-cyan-950 text-cyan-400 border border-cyan-800/60 shadow-sm">
                    {{ Auth::user()->roles->first()->name }}
                </span>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm font-medium rounded-lg text-slate-200 bg-slate-800 hover:bg-slate-700 hover:text-white focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @role('Administrador')
                        <x-dropdown-link :href="route('configuracion.usuarios.index')">
                            {{ __('Gestión de Usuarios') }}
                        </x-dropdown-link>
                        <div class="border-t border-slate-100 dark:border-slate-700"></div>
                        @endrole

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @can('gestionar-empleados')
            <x-responsive-nav-link :href="route('empleados.index')" :active="request()->routeIs('empleados.*')">
                Empleados
            </x-responsive-nav-link>
            @endcan

            <!-- Móvil: Dropdown Emitir Documentos -->
            <div x-data="{ openDocs: false }" class="space-y-1">
                <button @click="openDocs = !openDocs" class="w-full flex items-center justify-between px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-lg">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Emitir Documentos
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': openDocs}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openDocs" x-cloak class="ps-6 space-y-1">
                    <x-responsive-nav-link :href="route('boletas.index')" :active="request()->routeIs('boletas.*')">
                        Boletas de Pago
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')">
                        Contratos Laborales
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('documentos.rit.index')" :active="request()->routeIs('documentos.rit.*')">
                        Reglamento Interno (RIT)
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('documentos.politicas.index')" :active="request()->routeIs('documentos.politicas.*')">
                        Políticas de la Empresa
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('documentos.memorandums.index')" :active="request()->routeIs('documentos.memorandums.*')">
                        Memorándum
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('documentos.no-renovacion.index')" :active="request()->routeIs('documentos.no-renovacion.*')">
                        Carta de No Renovación
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('documentos.despido.index')" :active="request()->routeIs('documentos.despido.*')">
                        Carta de Despido
                    </x-responsive-nav-link>
                </div>
            </div>

            @can('ver-auditoria')
            <x-responsive-nav-link :href="route('auditoria.index')" :active="request()->routeIs('auditoria.*')">
                Auditoría
            </x-responsive-nav-link>
            @endcan

            @can('gestionar-configuracion')
            <x-responsive-nav-link :href="route('configuracion.index')" :active="request()->routeIs('configuracion.index')">
                Configuración
            </x-responsive-nav-link>
            @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-cyan-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @role('Administrador')
                <x-responsive-nav-link :href="route('configuracion.usuarios.index')" :active="request()->routeIs('configuracion.usuarios.*')">
                    Gestión de Usuarios
                </x-responsive-nav-link>
                @endrole

                <x-responsive-nav-link :href="route('profile.edit')">
                    Mi Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
