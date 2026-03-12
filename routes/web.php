<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MensajesController;
use App\Models\Proyecto;

// --- VISTAS PÚBLICAS ---
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/proyecto', function () {
    $proyectosEnProceso = Proyecto::where('id_estado', 1)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });

    $proyectosConstruidos = Proyecto::where('id_estado', 2)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });
    
    return view('partials.project_detail', compact('proyectosEnProceso', 'proyectosConstruidos')); 
})->name('project.detail');

Route::get('/info', function () {
    return view('agregados.informacion.info'); 
})->name('info');

Route::get('/contacto', function () {
    return view('agregados.contacto.contacto'); 
})->name('contacto');

// --- SISTEMA DE MENSAJES (PÚBLICO) ---
// Cambiamos el nombre a 'contacto.mensaje.store' para que coincida con la vista
Route::post('/enviar-mensaje', [MensajesController::class, 'guardarMensaje'])->name('contacto.mensaje.store');

// --- AUTENTICACIÓN ---
Route::get('/login', function () {
    return view('dashboard.login.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/registro', function () {
    return view('dashboard.login.registro');
})->name('registro.index');
Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');

// --- DASHBOARD (PROTEGIDO) ---
Route::middleware('auth')->prefix('dashboard')->group(function () {
    
    Route::get('/main', function () {
        $totalProyectos = Proyecto::count();
        $inversionTotal = Proyecto::sum('costo_inicial');

        $proyectosEnProceso = Proyecto::where('id_estado', 1)->take(3)->get()->map(function ($proyecto) {
            $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                    ->where('id_proyecto', $proyecto->id_proyecto)
                                    ->value('url_imagen');
            return $proyecto;
        });

        $proyectosFuturos = Proyecto::where('id_estado', 2)->take(2)->get()->map(function ($proyecto) {
            $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                    ->where('id_proyecto', $proyecto->id_proyecto)
                                    ->value('url_imagen');
            return $proyecto;
        });

        return view('dashboard.dash.main', compact('totalProyectos', 'inversionTotal', 'proyectosEnProceso', 'proyectosFuturos'));
    })->name('dashboard.main');

    // OPCIONES
    Route::get('/opciones', [OpcionesController::class, 'index'])->name('dashboard.opciones');
    Route::put('/opciones/perfil', [OpcionesController::class, 'updatePerfil'])->name('opciones.perfil.update');
    Route::put('/opciones/publicos', [OpcionesController::class, 'updatePublicos'])->name('opciones.publicos.update');

    // PROYECTOS
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('dashboard.proyectos');
    Route::post('/proyectos', [ProjectController::class, 'store'])->name('proyectos.store');
    Route::put('/proyectos/{id}', [ProjectController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{id}', [ProjectController::class, 'destroy'])->name('proyectos.destroy');
    Route::get('/proyectos/{id}/historia', [ProjectController::class, 'historias'])->name('proyectos.historias');
    Route::post('/proyectos/{id}/historia', [ProjectController::class, 'storeHistoria'])->name('proyectos.historias.store');
    Route::delete('/proyectos/historia/{id_imagen}', [ProjectController::class, 'destroyHistoria'])->name('proyectos.historias.destroy');

    // QUIENES SOMOS / EQUIPO
    Route::get('/quienes-somos', [EquipoController::class, 'index'])->name('dashboard.equipo.quienes_somos');
    Route::post('/equipo', [EquipoController::class, 'store'])->name('equipo.store');
    Route::put('/equipo/{id}', [EquipoController::class, 'update'])->name('equipo.update');
    Route::delete('/equipo/{id}', [EquipoController::class, 'destroy'])->name('equipo.destroy');

    // USUARIOS
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('dashboard.usuarios');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // MENSAJES (GESTIÓN)
    Route::get('/mensajes', [MensajesController::class, 'index'])->name('mensajes');
    Route::post('/responder-mensaje/{id}', [MensajesController::class, 'responder'])->name('responder.mensaje');
    Route::delete('/eliminar-mensaje/{id}', [MensajesController::class, 'eliminar'])->name('eliminar.mensaje');
});

// DETALLE EXTERNO
Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');