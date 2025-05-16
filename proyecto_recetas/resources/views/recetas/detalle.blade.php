@extends('layouts.app')

@push('css')
    <style>
        body {
            background-color: bisque;
        }
        p {
            color: teal;
        }
    </style>
@endpush

@section('titulo', 'Detalle de la receta ')
    
@section('detalle')
<div class="container mt-4">
    <div class="row">
        <!-- Columna izquierda -->
        <div class="col-md-5">

            {{-- Botones de edición/eliminación si el usuario es autor --}}
            @if ($receta->autor === 1) {{-- Reemplaza con auth()->id() === $receta->user_id si usas auth --}}
                <form action="{{ url('recetas/' . $receta->id . '/editar') }}" method="GET" style="display:inline;">
                    <button class="btn btn-warning mb-2">Editar</button>
                </form>

                <form action="{{ url('recetas/' . $receta->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger mb-3">Eliminar</button>
                </form>
            @endif

            {{-- Imagen --}}
            @if ($receta->imagen)
                <img src="{{ asset('storage/' . $receta->imagen) }}" class="img-fluid mb-3" alt="Imagen de {{ $receta->titulo }}">
            @endif

            {{-- Ingredientes --}}
            <h4>Ingredientes</h4>
            <p>{{ $receta->ingredientes }}</p>

            {{-- Botones de interacción --}}
            <form method="POST" action="#">
                @csrf
                <button class="btn btn-outline-primary mb-2">¡Me gusta!</button>
                <button class="btn btn-outline-secondary mb-2">Guardar</button>
            </form>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-7">
            <h2>{{ $receta->titulo }}</h2>

            <p>
                <strong>Tipo:</strong> {{ $receta->tipo }} |
                <strong>Autor:</strong> {{ $receta->autor }}
            </p>

            <h4>Procedimiento</h4>
            <p>{{ $receta->procedimiento }}</p>
        </div>
    </div>

    <!-- Sección de comentarios (placeholder) -->
    <div class="row mt-5">
        <div class="col-12">
            <h4>Comentarios</h4>
            <p class="text-muted">Aquí se mostrarán los comentarios de los usuarios sobre esta receta.</p>
            <!-- Formulario para añadir comentario -->
            @auth
                <form method="POST" action="{{ route('comentarios.store') }}">
                    @csrf
                    <input type="hidden" name="id_receta" value="{{ $receta->id }}">
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Añadir comentario:</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Comentar</button>
                </form>
            @endauth
            <hr>
            @foreach ($receta->comentarios as $comentario)
                <div class="mb-3">
                    <strong>{{ $comentario->user->email }}</strong> 
                    <span class="text-muted">({{ $comentario->f_creacion }})</span>
                    <p>{{ $comentario->contenido }}</p>
                    {{-- Respuestas al comentario --}}
                    @if($comentario->respuestas && $comentario->respuestas->count() > 0)
                        @foreach ($comentario->respuestas as $respuesta)
                            <div class="ms-4 ps-3 border-start mb-2">
                                <strong>{{ $respuesta->user->email }}</strong>
                                <span class="text-muted">({{ $respuesta->f_creacion }})</span>
                                <p>{{ $respuesta->contenido }}</p>
                            </div>
                        @endforeach
                    @endif
                    {{-- Formulario para responder al comentario --}}
                    @auth
                        <form method="POST" action="{{ route('respuestas.store') }}" class="ms-4 mt-2">
                            @csrf
                            <input type="hidden" name="id_user" value="1"> {{-- Value fijo para probar --}}
                            <input type="hidden" name="id_comentario" value="{{ $comentario->id }}">
                            <input type="hidden" name="id_receta" value="{{ $receta->id }}">
                            <input type="hidden" name="id_user_respondido" value="{{ $comentario->id_user }}">
                            <div class="mb-2">
                                <textarea name="contenido" class="form-control" rows="2" placeholder="Escribe una respuesta..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Responder</button>
                        </form>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection