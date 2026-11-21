<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\SolicitudController as AdminSolicitudController;
use App\Http\Controllers\Solicitante\SolicitudController as SolicitanteSolicitudController;
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta principal que redirige según el rol después de iniciar sesión
Route::get('/dashboard', function () {
    if (Auth::user()->rol->value === 'administrador') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('solicitante.dashboard');
})->middleware(['auth'])->name('dashboard');

// Ruta global para notificaciones
Route::middleware(['auth'])->get('/api/notificaciones', function () {
    $user = auth()->user();
    
    if ($user->rol->value === 'administrador') {
        // Admin: Cuenta las solicitudes pendientes
        $count = \App\Models\Solicitud::where('estado', 'pendiente')->count();
    } else {
        // Solicitante: Cuenta sus solicitudes que ya fueron respondidas (aprobadas o rechazadas)
        $count = \App\Models\Solicitud::where('usuario_id', $user->id)
            ->whereIn('estado', ['aprobada', 'rechazada'])
            ->count();
    }
    
    return response()->json(['count' => $count]);
})->name('api.notificaciones');


/*
|--------------------------------------------------------------------------
| Rutas del Administrador (Soporte Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard / Panel Principal
    Route::get('/dashboard', [PanelController::class, 'index'])->name('dashboard');

    // Módulo de Inventario (CRUD Completo)
    Route::resource('equipos', EquipoController::class);

    // Módulo de Solicitudes (Listado, Ver, Aprobar, Rechazar)
    Route::get('/solicitudes', [AdminSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/{solicitud}', [AdminSolicitudController::class, 'show'])->name('solicitudes.show');
    Route::post('/solicitudes/{solicitud}/aprobar', [AdminSolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [AdminSolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
    Route::resource('equipos', EquipoController::class);
    Route::resource('usuarios', \App\Http\Controllers\Admin\UsuarioController::class)->except(['show']);
   
    // Módulo de Reportes
    Route::get('/reportes/asignaciones', [ReporteController::class, 'asignacionesPorOficina'])->name('reportes.asignaciones');
    Route::get('/reportes/asignaciones', [\App\Http\Controllers\ReporteController::class, 'asignacionesPorOficina'])->name('reportes.asignaciones');
    Route::get('/reportes/asignaciones/exportar-csv', [\App\Http\Controllers\ReporteController::class, 'exportarCSV'])->name('reportes.exportar.csv'); 


    
});


/*
|--------------------------------------------------------------------------
| Rutas del Solicitante (Jefe de Oficina)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:solicitante'])->prefix('solicitante')->name('solicitante.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Solicitante\PanelController::class, 'index'])->name('dashboard');

    // Módulo de Solicitudes (Solo crear y ver las propias)
    Route::get('/solicitudes', [SolicitanteSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/crear', [SolicitanteSolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/solicitudes', [SolicitanteSolicitudController::class, 'store'])->name('solicitudes.store');    
    Route::get('/solicitudes/{solicitud}', [SolicitanteSolicitudController::class, 'show'])->name('solicitudes.show');
    });