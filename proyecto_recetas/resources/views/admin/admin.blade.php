@extends('layouts.app')

@section('titulo', 'Administración')

@section('admin')
    <div>
        <div class="d-flex justify-content-between mt-3 mx-5 mb-5">
            <input type="button" class="btn btn-hover-animate fs-2 tamañoBoton" value="Recetas">
            <h1 class="titulo">Recetas</h1>
            <input type="button" class="btn btn-hover-animate fs-2 tamañoBoton" value="Usuarios">
        </div>
        <div class="mx-2 d-flex justify-content-center ">
            <form action="" method="post">
                <input type="text" id="id" class="mx-1" placeholder="Id">
                <input type="text" id="alias" class="mx-1" placeholder="Alias">
                <input type="text" id="username" class="mx-1" placeholder="@Username">
                <input type="text" id="fechaCreacion" class="mx-1" placeholder="F_Creación">
                <input type="submit" class="btn mx-1 bg-primary" value="Filtrar">
            </form>
        </div>
        <div>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </div>
    </div>

    

@endsection