@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('listado')
    
    {{-- 
    <x-alerta type="danger">
        Variable slot ($slot es la predeterminada)

        <x-slot name="otraVariable">
            Esta es otra variable
        </x-slot>

        <x-slot name="variableTernaria">
            Variable ternaria manual
        </x-slot>
    </x-alerta>
    --}}

    <h2>Listado de recetas</h2>

    @foreach ($recetas as $receta)
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h3>
                <a href="{{ url('receta/' . $receta->id) }}">{{ $receta->titulo }}</a>
            </h3>

            @if ($receta->imagen)
                <img src="{{ asset('storage/' . $receta->imagen) }}" alt="Imagen de la receta" style="max-width: 200px;">
            @endif

            <p><strong>Tipo:</strong> {{ $receta->tipo }}</p>
            <p><strong>Ingredientes:</strong> {{ $receta->ingredientes }}</p>
            <p><strong>Procedimiento:</strong> {{ Str::limit($receta->procedimiento, 100, '...') }}</p>
            <p><strong>Autor:</strong> {{ $receta->autor }}</p>
            <p><strong>Creado el:</strong> {{ $receta->f_creacion }}</p>

            @if ($receta->autor === 'sergio@email.com') {{-- Simulación de usuario actual --}}
                <a href="{{ url('recetas/' . $receta->id . '/editar') }}" class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ url('recetas/' . $receta->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            @endif
        </div>
    @endforeach

    {{-- Paginación --}}
    <div>
        {{ $recetas->links('pagination::bootstrap-5') }}
    </div>

@endsection