<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;

// -------------------- EJEMPLOS -------------------- //

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('recetas', [RecetaController::class, 'listarRecetas']); // ('URI', [Controlador, 'metodo']);

//Ruta con variable enviada a una vista
Route::get('receta/{id}', [RecetaController::class, 'mostrarRecetaIndividual']);
