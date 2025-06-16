<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\RespuestaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MailController;

// Ruta raíz
Route::get('/', function () {
    return Auth::check() ? redirect()->route('recetas.lista') : view('welcome');
})->name('home');

// Rutas legales
Route::prefix('legal')->group(function () {
    Route::view('/terminos', 'legal.terminos')->name('terminos');
    Route::view('/privacidad', 'legal.privacidad')->name('privacidad');
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/accesibilidad', 'legal.accesibilidad')->name('accesibilidad');
});

// Rutas básicas
Route::view('/about', 'about')->name('about');

// Contacto
Route::controller(MailController::class)->group(function () {
    Route::get('/contacto', 'index')->name('contacto');
    Route::post('/contacto', 'enviar')->name('contacto.enviar');
});

// Ruta pública para ver receta individual
Route::get('receta/{id}', [RecetaController::class, 'mostrarRecetaIndividual'])->name('recetas.mostrar');

// Rutas que requieren autenticación
Route::middleware('auth', 'verified')->group(function () {
    // Rutas de perfil
    Route::prefix('perfil')->group(function () {
        Route::get('/{id}', [ProfileController::class, 'ver'])->name('perfil.ver');
        Route::get('/meGustas/{id}', [ProfileController::class, 'verMeGustas'])->name('perfil.verMeGustas');
        Route::get('/{id}/editar', [ProfileController::class, 'editar'])->name('perfil.edicionPerfil');
        Route::post('/{id}/actualizar', [ProfileController::class, 'actualizar'])->name('perfil.actualizar');
        Route::get('/{id}/seguidores', [ProfileController::class, 'verSeguidores'])->name('profile.seguidores');
        Route::get('/{id}/seguidos', [ProfileController::class, 'verSeguidos'])->name('profile.seguidos');

        Route::post('/seguidores/{id}', [ProfileController::class, 'verSeguidoresAjax'])->name('profile.seguidoresAjax');
        Route::post('/seguidos/{id}', [ProfileController::class, 'verSeguidosAjax'])->name('profile.seguidosAjax');
    });

    Route::get('/usuarios/{id}', [UserController::class, 'mostrarPerfil'])->name('usuarios.perfil');

    // Rutas de recetas que requieren autenticación
    Route::controller(RecetaController::class)->group(function () {
        Route::get('recetas', 'listarRecetas')->name('recetas.lista');
        Route::get('recetasGuardadas', 'recetasGuardadasVista')->name('recetas.recetasGuardadas');
        
        // Rutas Ajax
        Route::post('recetas/listarAjax', 'listarRecetasAjax')->name('recetas.listaRecetasAjax');
        Route::post('recetas/listarRecetasPrincipalAjax', 'listarRecetasPrincipalAjax')->name('recetas.listarRecetasPrincipalAjax');
        Route::post('recetas/listarMeGustaAjax', 'listarMeGustaAjax')->name('recetas.listarMeGustaAjax');
        Route::post('recetas/listarRecetasGuardadasAjax', 'listarRecetasGuardadasAjax')->name('recetas.listarRecetasGuardadasAjax');
    });

    // Comentarios y respuestas
    Route::post('/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::post('/respuestas', [RespuestaController::class, 'store'])->name('respuestas.store');

    // Perfil usuario autenticado
    Route::get('/perfil', [UserController::class, 'mostrarPerfilAutenticado'])->name('usuario.perfil');

    Route::get('/busqueda', [ProfileController::class, 'buscarPerfiles'])->name('usuario.buscarPerfiles');

    Route::post('/buscar', [ProfileController::class, 'busqueda'])->name('usuario.busqueda');

    // Guardar y gustar recetas
    Route::post('/recetas/{id}/guardar', [RecetaController::class, 'guardarRecetaUsuario'])->name('recetas.guardar');
    Route::delete('/recetas/{id}/guardar', [RecetaController::class, 'eliminarGuardado'])->name('recetas.guardar.eliminar');

    Route::post('/recetas/guardarReceta/{id}', [RecetaController::class, 'guardarRecetaUsuarioAjax'])->name('recetas.guardarRecetaUsuarioAjax');
    Route::delete('/recetas/quitarGuardado/{id}', [RecetaController::class, 'eliminarGuardadoAjax'])->name('recetas.quitarGuardadoAjax');

    Route::post('/recetas/darMeGusta/{id}', [RecetaController::class, 'gustarRecetaUsuarioAjax'])->name('recetas.darMeGustaAjax');
    Route::delete('/recetas/quitarMeGusta/{id}', [RecetaController::class, 'eliminarMeGustaAjax'])->name('recetas.eliminarMeGustaAjax');

    Route::post('/recetas/{id}/gustar', [RecetaController::class, 'gustarRecetaUsuario'])->name('recetas.gustar');
    Route::delete('/recetas/{id}/gustar', [RecetaController::class, 'eliminarMeGusta'])->name('recetas.gustar.eliminar');

    // Crear receta (puedes agregar middleware si quieres)
    Route::post('recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.store');

    // Edición receta
    Route::get('recetas/{id}/editar', [RecetaController::class, 'editarReceta'])->name('recetas.editar');
    Route::put('recetas/{id}', [RecetaController::class, 'actualizarReceta'])->name('recetas.actualizar');

    // Eliminar receta (puede que admin y usuario tengan diferente permiso, controla con políticas)
    Route::delete('recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('recetas.eliminar');

    // Añadir estas nuevas rutas dentro del grupo de autenticación
    Route::get('/ajustes/cuenta', [ProfileController::class, 'ajustesCuenta'])->name('ajustes.cuenta');
    Route::put('/ajustes/email', [ProfileController::class, 'actualizarEmail'])->name('ajustes.actualizar-email');
    Route::put('/ajustes/password', [ProfileController::class, 'actualizarPassword'])->name('ajustes.actualizar-password');
});

// Rutas para usuarios autenticados y verificados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin', [AdminController::class, 'index'])->name('admin');
    // Rutas Ajax para los listados de admin
    Route::get('admin/recetasAjax', [AdminController::class, 'listaRecetasAjax'])->name('admin.recetasAjax');
    Route::get('admin/usuariosAjax', [AdminController::class, 'listaUsuariosAjax'])->name('admin.usuariosAjax');

    // Eliminaciones admin
    Route::delete('recetas/admin/{id}', [RecetaController::class, 'eliminarRecetaAdmin'])->name('recetas.eliminarAdmin');
    Route::delete('usuario/admin/{id}', [UserController::class, 'eliminarUsuarioAdmin'])->name('usuario.eliminarAdmin');

    // Actualizar estado receta
    Route::post('recetas/ocultarReceta/{id}', [RecetaController::class, 'ocultarReceta'])->name('recetas.ocultarReceta');

    // Actualizar rol usuario
    Route::post('usuario/hacerAdmin/{id}', [UserController::class, 'hacerAdmin'])->name('usuario.hacerAdmin');
    Route::post('usuario/banearUsuario/{id}', [UserController::class, 'banearUsuario'])->name('usuario.banearUsuario');

    //Seguir usuario
    Route::post('usuario/{id}/seguir', [UserController::class, 'SeguirUsuario'])->middleware('auth')->name('usuario.seguir');
    Route::delete('usuario/{id}/dejarSeguir', [UserController::class, 'DejarDeSeguir'])->middleware('auth')->name('usuario.dejarSeguir');

    Route::post('usuario/seguirUsuario/{id}', [UserController::class, 'SeguirUsuarioAjax'])->middleware('auth')->name('usuario.seguirAjax');
    Route::delete('usuario/dejarSeguirUsuario/{id}', [UserController::class, 'dejarSeguirUsuarioAjax'])->middleware('auth')->name('usuario.dejarSeguirAjax');
});

// Autenticación
require __DIR__.'/auth.php';
