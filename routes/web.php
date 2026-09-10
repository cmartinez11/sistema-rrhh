<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\BoletaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentoLaboralController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EnvioBoletaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Módulo de Gestión de Empleados
    Route::middleware(['can:gestionar-empleados'])->group(function () {
        Route::resource('empleados', EmpleadoController::class);
        Route::get('/api/areas/{area}/cargos', [EmpleadoController::class, 'getCargosPorArea'])->name('api.areas.cargos');
    });

    // 2. Módulo de Gestión de Boletas (Listar accesible por Asistentes y Jefes)
    Route::middleware(['can:gestionar-boletas'])->group(function () {
        Route::get('/boletas', [BoletaController::class, 'index'])->name('boletas.index');
        Route::post('/boletas', [BoletaController::class, 'store'])->name('boletas.store');
        Route::post('/boletas/batch', [BoletaController::class, 'batchUpload'])->name('boletas.batch');
        Route::delete('/boletas/{boleta}', [BoletaController::class, 'destroy'])->name('boletas.destroy');
    });

    // Ruta protegida de previsualización/descarga de PDF (Valida permisos en Controller mediante Policy)
    Route::get('/boletas/{boleta}/stream', [BoletaController::class, 'streamPdf'])->name('boletas.stream');

    // 3. Módulo de Envío Masivo y Reintentos (SOLO Jefe de RRHH o Admin)
    Route::middleware(['can:enviar-boletas'])->group(function () {
        Route::post('/envios/enviar-periodo', [EnvioBoletaController::class, 'dispatchBatch'])->name('envios.dispatch');
        Route::post('/envios/reintentar-fallidas', [EnvioBoletaController::class, 'retryFailed'])->name('envios.retry');
    });

    // 4. Módulo de Auditoría de Envíos
    Route::middleware(['can:ver-auditoria'])->group(function () {
        Route::get('/auditoria', [EnvioBoletaController::class, 'index'])->name('auditoria.index');
        Route::get('/auditoria/envios/{envio}', [EnvioBoletaController::class, 'show'])->name('auditoria.envios.show');
    });

    // 5. Módulo de Gestión y Envío de Contratos Laborales
    Route::middleware(['can:gestionar-contratos'])->group(function () {
        Route::get('/contratos', [ContratoController::class, 'index'])->name('contratos.index');
        Route::post('/contratos', [ContratoController::class, 'store'])->name('contratos.store');
        Route::delete('/contratos/{contrato}', [ContratoController::class, 'destroy'])->name('contratos.destroy');
        Route::get('/contratos/{contrato}/stream', [ContratoController::class, 'streamPdf'])->name('contratos.stream');
    });

    Route::middleware(['can:enviar-contratos'])->group(function () {
        Route::post('/contratos/{contrato}/enviar', [ContratoController::class, 'send'])->name('contratos.send');
    });

    // 5.1. Módulo Unificado de Emisión y Envío de Documentos Laborales (RIT, Políticas, Memorándums, No Renovación, Despido)
    Route::prefix('documentos')->name('documentos.')->group(function () {
        Route::get('/rit', [DocumentoLaboralController::class, 'rit'])->name('rit.index');
        Route::get('/politicas', [DocumentoLaboralController::class, 'politicas'])->name('politicas.index');
        Route::get('/memorandums', [DocumentoLaboralController::class, 'memorandums'])->name('memorandums.index');
        Route::get('/no-renovacion', [DocumentoLaboralController::class, 'noRenovacion'])->name('no-renovacion.index');
        Route::get('/despido', [DocumentoLaboralController::class, 'despido'])->name('despido.index');

        Route::post('/', [DocumentoLaboralController::class, 'store'])->name('store');
        Route::get('/{documento}/stream', [DocumentoLaboralController::class, 'streamPdf'])->name('stream');
        Route::post('/{documento}/enviar', [DocumentoLaboralController::class, 'send'])->name('send');
        Route::delete('/{documento}', [DocumentoLaboralController::class, 'destroy'])->name('destroy');
    });

    // 6. Módulo de Configuración del Sistema
    Route::middleware(['can:gestionar-configuracion'])->group(function () {
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    });

    // 7. Gestión de Áreas, Cargos y Usuarios del Sistema (Exclusivo Administrador)
    Route::middleware(['role:Administrador'])->prefix('configuracion')->name('configuracion.')->group(function () {
        Route::resource('areas', AreaController::class)->except(['create', 'edit', 'show']);
        Route::resource('cargos', CargoController::class)->except(['create', 'edit', 'show']);
        Route::resource('usuarios', UsuarioController::class)->except(['create', 'edit', 'show']);
    });

});

require __DIR__.'/auth.php';
