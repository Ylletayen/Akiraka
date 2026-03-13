<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MensajesController;
use App\Http\Controllers\ObjetoController;
use App\Models\Proyecto;
use App\Http\Controllers\PublicacionController;
use App\Models\Publicacion;

// --- VISTAS PÚBLICAS ---
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/proyecto', function () {
    $proyectosEnProceso = Proyecto::where('id_estado', 1)->orderBy('orden', 'asc')->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });

    $proyectosConstruidos = Proyecto::where('id_estado', 2)->orderBy('orden', 'asc')->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });

    // CORRECCIÓN: Los objetos ya están libres y ordenados por año
    $objetos = \App\Models\Objeto::orderBy('anio', 'desc')->get()->map(function ($objeto) {
        $objeto->portada = \Illuminate\Support\Facades\DB::table('imagenes_objeto')
                                ->where('id_objeto', $objeto->id_objeto)
                                ->value('url_imagen');
        return $objeto;
    });
     // obtener publicaciones
    $publicaciones = Publicacion::orderBy('fecha','desc')->get();
    
    return view('partials.project_detail', compact('proyectosEnProceso', 'proyectosConstruidos', 'objetos', 'publicaciones')); 
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
    Route::put('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'updateRol'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // MENSAJES (GESTIÓN)
    Route::get('/mensajes', [MensajesController::class, 'index'])->name('mensajes');
    Route::post('/responder-mensaje/{id}', [MensajesController::class, 'responder'])->name('responder.mensaje');
    Route::delete('/eliminar-mensaje/{id}', [MensajesController::class, 'eliminar'])->name('eliminar.mensaje');

    // OBJETOS (Catálogo)
    Route::get('/objetos', [ObjetoController::class, 'index'])->name('dashboard.objetos');
    Route::post('/objetos', [ObjetoController::class, 'store'])->name('objetos.store');
    Route::delete('/objetos/{id}', [ObjetoController::class, 'destroy'])->name('objetos.destroy');
    
    //(HISTORIA) DE LOS OBJETOS
    Route::get('/objetos/{id}/historia', [ObjetoController::class, 'historias'])->name('objetos.historias');
    Route::post('/objetos/{id}/historia', [ObjetoController::class, 'storeHistoria'])->name('objetos.historias.store');
    Route::delete('/objetos/historia/{id_imagen}', [ObjetoController::class, 'destroyHistoria'])->name('objetos.historias.destroy');
});

// DETALLE EXTERNO
Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');
Route::get('/objeto/{id}', [ObjetoController::class, 'show'])->name('objeto.main');


////publicaciones
Route::get('/publicaciones', [PublicacionController::class,'index'])
->name('publicaciones');

Route::get('/publicaciones/{id}', [PublicacionController::class,'show'])
->name('publicaciones.show');