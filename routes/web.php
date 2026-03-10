<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UsuarioController;
use App\Models\Proyecto;
use App\Http\Controllers\MensajesController;


Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/proyecto', function () {
    // Jalamos los proyectos y les adjuntamos su primera imagen de la historia (como portada)
    $proyectosEnProceso = \App\Models\Proyecto::where('id_estado', 1)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen'); // Toma solo la primera imagen que encuentre
        return $proyecto;
    });

    $proyectosConstruidos = \App\Models\Proyecto::where('id_estado', 2)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });
    
    return view('partials.project_detail', compact('proyectosEnProceso', 'proyectosConstruidos')); 
})->name('project.detail');

// PROYECTOS
Route::prefix('dashboard')->middleware('auth')->group(function () {
    
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('dashboard.proyectos');
    
    // Rutas CRUD para el modal
    Route::post('/proyectos', [ProjectController::class, 'store'])->name('proyectos.store');
    Route::put('/proyectos/{id}', [ProjectController::class, 'update'])->name('proyectos.update');
    Route::delete('/proyectos/{id}', [ProjectController::class, 'destroy'])->name('proyectos.destroy');

    // Rutas para la Historia de cada Proyecto
    Route::get('/proyectos/{id}/historia', [ProjectController::class, 'historias'])->name('proyectos.historias');
    Route::post('/proyectos/{id}/historia', [ProjectController::class, 'storeHistoria'])->name('proyectos.historias.store');
    Route::delete('/proyectos/historia/{id_imagen}', [ProjectController::class, 'destroyHistoria'])->name('proyectos.historias.destroy');
});

Route::get('/info', function () {
    return view('agregados.informacion.info'); 
})->name('info');

Route::get('/contacto', function () {
    return view('agregados.contacto.contacto'); 
})->name('contacto');

Route::get('/login', function () {
    return view('dashboard.login.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Ruta del Dashboard
Route::get('/dashboard/main', function () {
    // 1. Estadísticas rápidas
    $totalProyectos = \App\Models\Proyecto::count();
    $inversionTotal = \App\Models\Proyecto::sum('costo_inicial');

    // 2. Traer 3 proyectos en proceso con su portada
    $proyectosEnProceso = \App\Models\Proyecto::where('id_estado', 1)->take(3)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });

    // 3. Traer 2 proyectos construidos/futuros (asumiendo estado 2)
    $proyectosFuturos = \App\Models\Proyecto::where('id_estado', 2)->take(2)->get()->map(function ($proyecto) {
        $proyecto->portada = \Illuminate\Support\Facades\DB::table('imagenes_proyecto')
                                ->where('id_proyecto', $proyecto->id_proyecto)
                                ->value('url_imagen');
        return $proyecto;
    });

    return view('dashboard.dash.main', compact('totalProyectos', 'inversionTotal', 'proyectosEnProceso', 'proyectosFuturos'));
})->middleware('auth')->name('dashboard.main');

Route::get('/registro', function () {
    return view('dashboard.login.registro');
})->name('registro.index');

Route::post('/registro', [AuthController::class, 'store'])->name('registro.store');

Route::get('/proyecto/{id}', [ProjectController::class, 'show'])->name('project.main');

Route::get('/dashboard/opciones', [OpcionesController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.opciones');

Route::put('/dashboard/opciones/perfil', [OpcionesController::class, 'updatePerfil'])
    ->middleware('auth')
    ->name('opciones.perfil.update');

Route::put('/dashboard/opciones/publicos', [OpcionesController::class, 'updatePublicos'])
    ->middleware('auth')
    ->name('opciones.publicos.update');

// QUIENES SOMOS
Route::get('/dashboard/quienes-somos', [EquipoController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.equipo.quienes_somos'); // <-- Aquí está la magia, ya coincide con tu vista

Route::post('/dashboard/equipo', [EquipoController::class, 'store'])
    ->middleware('auth')
    ->name('equipo.store');

Route::put('/dashboard/equipo/{id}', [EquipoController::class, 'update'])
    ->middleware('auth')
    ->name('equipo.update');

Route::delete('/dashboard/equipo/{id}', [EquipoController::class, 'destroy'])
    ->middleware('auth')
    ->name('equipo.destroy');
    

///dash mensajes 


Route::get('/mensajes', [MensajesController::class, 'index'])
    ->name('mensajes');

// USUARIOS
Route::prefix('dashboard')->middleware('auth')->group(function () {
    
    // El nombre de esta ruta DEBE coincidir con el route('dashboard.usuarios') de tu sidebar
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('dashboard.usuarios');
    
    // Rutas para el modal de crear, actualizar y eliminar
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

