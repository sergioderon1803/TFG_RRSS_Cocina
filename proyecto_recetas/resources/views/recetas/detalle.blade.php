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

@section('titulo', 'Detalle de la receta ')
    
@section('detalle')
<div class="container mt-4">
    <h2>{{ $receta->titulo }}</h2>
    <p><strong>Tipo:</strong> {{ $receta->tipo }}</p>

    @if ($receta->imagen)
        <img src="{{ asset('storage/' . $receta->imagen) }}" class="img-fluid mb-3" alt="Imagen de {{ $receta->titulo }}">
    @endif

    <h4>Ingredientes</h4>
    <p>{{ $receta->ingredientes }}</p>

    <h4>Procedimiento</h4>
    <p>{{ $receta->procedimiento }}</p>

    <hr>

    {{-- Lógica condicional según autor --}}
    @if ($receta->autor === 'sergio@email.com') {{-- Reemplaza esto con auth()->user()->email si usas auth --}}
        <form action="{{ url('recetas/' . $receta->id . '/editar') }}" method="GET" style="display:inline;">
            <button class="btn btn-warning">Editar</button>
        </form>

        <form action="{{ url('recetas/' . $receta->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    @else
        <form method="POST" action="#">
            @csrf
            <button class="btn btn-outline-primary">¡Me gusta!</button>
            <button class="btn btn-outline-secondary">Guardar</button>
        </form>
    @endif
</div>
@endsection