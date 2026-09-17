<x-guest-layout>
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[580px] my-auto">
        <!-- Columna Izquierda (Login Form) -->
        <div class="p-8 sm:p-12 flex flex-col justify-between">
            <!-- Header / Logotipo Corporativo -->
            <div class="flex flex-col items-center justify-center text-center">
                <img src="{{ asset('logo2.png') }}" alt="PLASTICOS FENIX" class="h-32 w-auto object-contain mb-4">
            </div>

            <!-- Form Body -->
            <div class="my-6">
                

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address Field -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="Correo Electrónico"
                                   class="pl-11 pr-4 py-3 border border-slate-300 rounded-full w-full focus:ring-2 focus:ring-[#15803d] focus:border-transparent outline-none text-slate-700 text-sm transition duration-150 shadow-sm" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 pl-4" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="Contraseña"
                                   class="pl-11 pr-4 py-3 border border-slate-300 rounded-full w-full focus:ring-2 focus:ring-[#15803d] focus:border-transparent outline-none text-slate-700 text-sm transition duration-150 shadow-sm" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 pl-4" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between px-2 pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-[#15803d] shadow-sm focus:ring-[#15803d] h-4 w-4" name="remember">
                            <span class="ms-2 text-xs text-slate-600 font-medium">{{ __('Recordarme') }}</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="bg-[#15803d] hover:bg-[#166534] active:bg-[#14532d] text-white font-semibold py-3.5 px-6 rounded-full transition duration-200 shadow-lg shadow-emerald-900/20 hover:shadow-xl w-full flex items-center justify-center gap-2 cursor-pointer text-sm uppercase tracking-wider">
                            <span>Ingresar al Sistema</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer / Copyright -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400 font-medium">
                    © {{ date('Y') }} Plásticos Fénix - Todos los derechos reservados.
                </p>
            </div>
        </div>

        <!-- Columna Derecha (Hero) -->
        <div class="hidden md:flex flex-col justify-between p-10 bg-gradient-to-br from-slate-900 via-emerald-950 to-teal-800 text-white relative overflow-hidden">
            <!-- Formas suaves difuminadas (blur-3xl) -->
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-emerald-500/20 rounded-full filter blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-teal-500/20 rounded-full filter blur-3xl pointer-events-none"></div>

            <!-- Centro: Impacto Tipográfico -->
            <div class="relative z-10 my-auto py-8">
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4 leading-tight">
                    Bienvenido.
                </h1>
                <p class="text-slate-300 text-base leading-relaxed max-w-sm">
                    Plataforma corporativa de gestión de recursos humanos y distribución digital de boletas de pago y contratos de FENIX.
                </p>
            </div>

            <!-- Pie Informativo -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400 font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Conexión Segura
                </span>
                <span>v1.0.0</span>
            </div>
        </div>
    </div>
</x-guest-layout>
