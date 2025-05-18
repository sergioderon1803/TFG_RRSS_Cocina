@extends('layouts.app')

@section('titulo', 'Administración')

@section('admin')

    <div>
        <div class="d-flex justify-content-between mt-3 mx-5 mb-5">
            <a href="{{ url('admin/recetas') }}" class="btn btn-hover-animate fs-2 tamañoBoton">Recetas</a>
            <h1 class="titulo">Elija el listado</h1>
            <a href="{{ url('admin/usuarios') }}" class="btn btn-hover-animate fs-2 tamañoBoton">Usuarios</a>
        </div>
    </div>

@endsection