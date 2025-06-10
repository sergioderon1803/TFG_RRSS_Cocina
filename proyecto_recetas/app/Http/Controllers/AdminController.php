<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->user_type !== 1) {
            abort(403, 'Acceso denegado');
        }
        return view('admin.admin');
    }

    public function listaRecetasAjax(Request $request)
    {
        if (auth()->user()->user_type !== 1) {
            abort(403);
        }
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
        if (auth()->user()->user_type !== 1) {
            abort(403);
        }
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
