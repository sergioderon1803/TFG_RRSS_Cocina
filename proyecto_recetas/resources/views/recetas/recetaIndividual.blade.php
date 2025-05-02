@extends('layouts.app')

@push('css')
    <style>
        body {
            background-color: bisque;
        }
    </style>
@endpush

@push('css')
    <style>
        p {
            color:teal;
        }
    </style>
@endpush

@section('titulo', 'Receta ' . $id)
    
@section('receta')
    <h2>Título de la receta {{ $id }}</h2>
    <p>Ingredientes de la receta {{ $id }}</p>
    <p>Procedimiento de la receta {{ $id }}</p>

    @if (true) 
        <!-- Botones para autor de la receta-->
        <form>
            <button>Editar</button>
            <button>Eliminar</button>
        </form>
    @elseif (false)
        <!-- Botones para resto de usuarios -->
        <form>
            <button>¡Me gusta!</button>
            <button>Guardar</button>
        </form>
    @endif
@endsection