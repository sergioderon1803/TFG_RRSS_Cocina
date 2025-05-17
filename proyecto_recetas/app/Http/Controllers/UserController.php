<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Receta;

class UserController extends Controller {

    public function mostrarPerfilAutenticado() {
        $user = Auth::user(); // Usuario logado
        $perfil = $user->perfil;
        $recetas = $user->recetas;

        return view('profile.perfil', compact('user', 'perfil', 'recetas'));
    }

    // Eliminar usuario
    public function eliminarUsuarioAdmin($id) {
        /*$usuario = User::findOrFail($id);
        $usuario->delete();
        */
        $usuarios = User::paginate(5);

        return redirect()->route('admin',array('tipo' => 'usuarios'))->with('success', 'Usuario eliminado.');
    }

}
