<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecetaController extends Controller {
    
    public function listarRecetas(){
        return view('recetas/listarReceta');
    }

    public function mostrarRecetaIndividual($id){
        return view('recetas.recetaIndividual', ['id' => $id]);
        // return view('recetas.recetaIndividual', compact('id')); Es lo mismo
    }
}
