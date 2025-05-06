<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Models\Usuario;

// -------------------- EJEMPLOS -------------------- //

// Route::get('/', function () {
//     return view('welcome');
// });

// Ruta con varias variables opcionales (EL ORDEN EN EL QUE SE COLOCAN LAS RUTAS IMPORTA)
Route::get('variable/{post}/{id}/{contenido?}', function ($post, $id, $contenido = "vacío") {
    return "Post {$post} con ID {$id} y contenido {$contenido}";
});

// Ruta con variable
Route::get('variable/{post}', function ($post) {
    return "Probando variables {$post}";
});

// Ruta con varias variables
Route::get('variable/{post}/{id}', function ($post, $id) {
    return "Post {$post} con ID {$id}";
});




// -------------------- CODIGO UTIL -------------------- //

Route::get('/', [RecetaController::class, 'listarRecetas'])->name('recetas.lista'); // ('URI', [Controlador, 'metodo']);
Route::get('receta/{id}', [RecetaController::class, 'mostrarRecetaIndividual']);
Route::get('recetas/crear', [RecetaController::class, 'crearReceta']);
Route::post('recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.store');

// Mostrar formulario de edición
Route::get('recetas/{id}/editar', [RecetaController::class, 'editarReceta'])->name('recetas.editar');

// Procesar actualización
Route::put('recetas/{id}', [RecetaController::class, 'actualizarReceta'])->name('recetas.actualizar');

// Eliminar receta
Route::delete('recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('recetas.eliminar');

Route::get('usuarios', function() {
    $usuarios = Usuario::all();

    return $usuarios;
});

Route::get('registrarUsuarios', function() {
    $usuario = new Usuario();

    $usuario->email = 'sergio@email.com';
    $usuario->password = 'abc123';
    $usuario->f_registro = now();

    $usuario->save();
    return $usuario;
});