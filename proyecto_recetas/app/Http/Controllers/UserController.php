<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Receta;

class UserController extends Controller {

    public function mostrarPerfilAutenticado()
    {
        $user = Auth::user(); // Usuario logado
        $perfil = $user->perfil;
        $recetas = $user->recetas;

        return view('profile.perfil', compact('user', 'perfil', 'recetas'));
    }

}
