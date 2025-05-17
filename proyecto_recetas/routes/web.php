<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\RespuestaController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
Route::post('/respuestas', [RespuestaController::class, 'store'])->name('respuestas.store');

Route::get('/perfil', [UserController::class, 'mostrarPerfilAutenticado'])->middleware(['auth'])->name('usuario.perfil');
Route::get('/perfil/{id}', [ProfileController::class, 'ver'])->name('perfil.ver');
Route::get('/perfil/{id}/editar', [ProfileController::class, 'editar'])->name('perfil.edicionPerfil');
Route::post('/perfil/{id}/actualizar', [ProfileController::class, 'actualizar'])->name('perfil.actualizar');

Route::get('/usuarios/{id}', [UserController::class, 'mostrarPerfil'])->name('usuarios.perfil');

Route::get('admin', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('admin');

Route::view('/about', 'about')->name('about');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('recetas', [RecetaController::class, 'listarRecetas'])->name('recetas.lista'); // ('URI', [Controlador, 'metodo']);
Route::get('receta/{id}', [RecetaController::class, 'mostrarRecetaIndividual']);
Route::get('recetas/crear', [RecetaController::class, 'formularioReceta']);
Route::post('recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.store');

// Guardar receta
Route::post('/recetas/{id}/guardar', [RecetaController::class, 'guardarRecetaUsuario'])->middleware('auth')->name('recetas.guardar');
Route::delete('/recetas/{id}/guardar', [RecetaController::class, 'eliminarGuardado'])->middleware('auth')->name('recetas.guardar.eliminar');

// Me gusta receta
Route::post('/recetas/{id}/gustar', [RecetaController::class, 'gustarRecetaUsuario'])->middleware('auth')->name('recetas.gustar');
Route::delete('/recetas/{id}/gustar', [RecetaController::class, 'eliminarMeGusta'])->middleware('auth')->name('recetas.gustar.eliminar');

// Mostrar formulario de edición
Route::get('recetas/{id}/editar', [RecetaController::class, 'editarReceta'])->name('recetas.editar');

// Procesar actualización
Route::put('recetas/{id}', [RecetaController::class, 'actualizarReceta'])->name('recetas.actualizar');

// Eliminar receta
Route::delete('recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('recetas.eliminar');

// Eliminar receta admin
Route::delete('recetas/admin/{id}', [RecetaController::class, 'eliminarRecetaAdmin'])->name('recetas.eliminarAdmin');

// Eliminar usuario admin
Route::delete('usuario/admin/{id}', [UserController::class, 'eliminarUsuarioAdmin'])->name('usuario.eliminarAdmin');