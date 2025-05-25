<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{

    // Ver de quitar, ahora mismo no es útil
    public function index(Request $request)
    {
        return view('admin.admin');
    }

    public function listaRecetasAjax(Request $request)
    {
        $recetas = Receta::query();

        return Datatables::eloquent($recetas) // Le mando la query al Datatable
        
        // Hago que la columna de fecha se muestre como yo quiero
        ->addColumn('created_at', function($receta){
            return Carbon::parse($receta->created_at)->format('d-m-Y');
        })

        // Hago que en vez del id me muestre el autor
        ->addColumn('autor_receta', function($receta){
            return $receta->autor->perfil->name;
        })
        
        // Añado una columna de acciones y creo los botones que quiera
        ->addColumn('action', function($receta){
            return '<button data-id="'.$receta->id.'" class="btn btn-danger btn-sm delete-receta">Eliminar</button>';
        })

        ->rawColumns(['action'])

        ->make(true);
    }

    public function listaUsuariosAjax(Request $request)
    {
        $usuarios = User::query();

        return Datatables::eloquent($usuarios)
        
        ->addColumn('created_at', function($user){
            return Carbon::parse($user->created_at)->format('d-m-Y');
        })
        
        ->addColumn('action', function($user){
            return '<button data-id="'.$user->id.'" class="btn btn-danger btn-sm delete-user">Eliminar</button>';
        })

        ->rawColumns(['action'])

        ->make(true);
    }

}
