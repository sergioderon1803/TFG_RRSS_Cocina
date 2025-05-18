<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\RespuestaController;
use Illuminate\Support\Facades\Auth;

// Ruta raíz pública
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('recetas.lista');
    }
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas públicas
Route::view('/about', 'about')->name('about');
Route::get('/perfil/{id}', [ProfileController::class, 'ver'])->name('perfil.ver');
Route::get('/perfil/{id}/editar', [ProfileController::class, 'editar'])->name('perfil.edicionPerfil');
Route::post('/perfil/{id}/actualizar', [ProfileController::class, 'actualizar'])->name('perfil.actualizar');
Route::get('/usuarios/{id}', [UserController::class, 'mostrarPerfil'])->name('usuarios.perfil');

Route::get('recetas', [RecetaController::class, 'listarRecetas'])->name('recetas.lista');
Route::get('receta/{id}', [RecetaController::class, 'mostrarRecetaIndividual']);

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Comentarios y respuestas
    Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::post('/respuestas', [RespuestaController::class, 'store'])->name('respuestas.store');

    // Perfil usuario autenticado
    Route::get('/perfil', [UserController::class, 'mostrarPerfilAutenticado'])->name('usuario.perfil');

    // Guardar y gustar recetas
    Route::post('/recetas/{id}/guardar', [RecetaController::class, 'guardarRecetaUsuario'])->name('recetas.guardar');
    Route::delete('/recetas/{id}/guardar', [RecetaController::class, 'eliminarGuardado'])->name('recetas.guardar.eliminar');
    Route::post('/recetas/{id}/gustar', [RecetaController::class, 'gustarRecetaUsuario'])->name('recetas.gustar');
    Route::delete('/recetas/{id}/gustar', [RecetaController::class, 'eliminarMeGusta'])->name('recetas.gustar.eliminar');

    // Edición perfil usuario (usa profile.edit, update, destroy)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Crear receta (puedes agregar middleware si quieres)
    Route::get('recetas/crear', [RecetaController::class, 'formularioReceta'])->name('recetas.crear');
    Route::post('recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.store');

    // Edición receta
    Route::get('recetas/{id}/editar', [RecetaController::class, 'editarReceta'])->name('recetas.editar');
    Route::put('recetas/{id}', [RecetaController::class, 'actualizarReceta'])->name('recetas.actualizar');

    // Eliminar receta (puede que admin y usuario tengan diferente permiso, controla con políticas)
    Route::delete('recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('recetas.eliminar');
});

// Rutas para usuarios autenticados y verificados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin', [AdminController::class, 'index'])->name('admin');
    Route::get('admin/recetas', [AdminController::class, 'listaRecetas'])->name('admin.recetas');
    Route::get('admin/usuarios', [AdminController::class, 'listaUsuarios'])->name('admin.usuarios');

    // Eliminaciones admin
    Route::delete('recetas/admin/{id}', [RecetaController::class, 'eliminarRecetaAdmin'])->name('recetas.eliminarAdmin');
    Route::delete('usuario/admin/{id}', [UserController::class, 'eliminarUsuarioAdmin'])->name('usuario.eliminarAdmin');
});

// Autenticación
require __DIR__.'/auth.php';
