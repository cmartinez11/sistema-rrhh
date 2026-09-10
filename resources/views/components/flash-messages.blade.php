@if (session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center shadow-sm">
        <svg class="w-5 h-5 me-3 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 flex items-center shadow-sm">
        <svg class="w-5 h-5 me-3 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span class="font-medium text-sm">{{ session('warning') }}</span>
    </div>
@endif

@if (session('info'))
    <div class="mb-6 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-700 dark:text-cyan-400 flex items-center shadow-sm">
        <svg class="w-5 h-5 me-3 shrink-0 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('info') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 flex items-center shadow-sm">
        <svg class="w-5 h-5 me-3 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('error') }}</span>
    </div>
@endif
