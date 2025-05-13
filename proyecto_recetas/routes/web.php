<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', function () {
    return view('admin.admin');
});

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
Route::get('recetas/crear', [RecetaController::class, 'crearReceta']);
Route::post('recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.store');

// Mostrar formulario de edición
Route::get('recetas/{id}/editar', [RecetaController::class, 'editarReceta'])->name('recetas.editar');

// Procesar actualización
Route::put('recetas/{id}', [RecetaController::class, 'actualizarReceta'])->name('recetas.actualizar');

// Eliminar receta
Route::delete('recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('recetas.eliminar');