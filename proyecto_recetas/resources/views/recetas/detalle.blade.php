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

@section('titulo', 'Detalle de la receta')

@section('detalle')
<div class="container mt-4">
    <div class="row">
        <!-- Columna izquierda -->
        <div class="col-md-5">
            @if ($receta->imagen)
                <img src="{{ asset('storage/' . $receta->imagen) }}" class="img-fluid mb-3" alt="Imagen de {{ $receta->titulo }}">
            @endif

            @auth
                @if (auth()->id() === $receta->autor)
                    <div class="d-flex gap-2">
                        <form action="{{ route('recetas.editar', $receta->id) }}" method="GET" style="display:inline;">
                            <button type="submit" class="btn btn-warning mb-2">Editar</button>
                        </form>

                        <form action="{{ route('recetas.eliminar', $receta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mb-3"
                                    onclick="return confirm('¿Estás seguro de eliminar esta receta?');">Eliminar</button>
                        </form>
                    </div>
                @endif
            @endauth

            @auth
                @if(auth()->id() !== $receta->autor)
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ $guardada ? route('recetas.guardar.eliminar', $receta->id) : route('recetas.guardar', $receta->id) }}">
                            @csrf
                            @if($guardada)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="btn {{ $guardada ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                                {{ $guardada ? 'Quitar de guardadas' : 'Guardar' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ $gustada ? route('recetas.gustar.eliminar', $receta->id) : route('recetas.gustar', $receta->id) }}">
                            @csrf
                            @if($gustada)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="btn {{ $gustada ? 'btn-danger' : 'btn-outline-success' }}">
                                {{ $gustada ? 'Quitar me gusta' : 'Me gusta' }}
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-3">
                    <a href="{{ route('login') }}">Inicia sesión</a> para guardar o dar me gusta a esta receta.
                </div>
            @endauth

        </div>

        <!-- Columna derecha -->
        <div class="col-md-7">
            <h1>{{ $receta->titulo }}</h1>
            <h4>Ingredientes</h4>
            <p>{{ $receta->ingredientes }}</p>
            <p>Autor: {{ $receta->autor }}</p>
            <h5 class="text-muted">Tipo: {{ $receta->tipo }}</h5>
            <hr>
            <h4>Procedimiento</h4>
            <p>{{ $receta->procedimiento }}</p>
        </div>
    </div>

    <!-- Comentarios abajo en una fila aparte -->
    <div class="row mt-4">
        <div class="col-12">
            <strong><h2>Comentarios</h2></strong>
            {{-- Formulario para nuevo comentario --}}
            @auth
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#comentar">
                    Comentar
                </button>
            @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}">Inicia sesión</a> para comentar.
                </div>
            @endauth
            <br>
            @foreach ($receta->comentarios as $comentario)
                <div class="mb-3 border p-2 rounded">
                    <strong>{{ $comentario->user->perfil->name ?? $comentario->user->email }}:</strong>
                    <p>{{ $comentario->contenido }}</p>

                    {{-- Formulario para responder al comentario --}}
                    @auth
                    <form action="{{ route('respuestas.store') }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="id_receta" value="{{ $receta->id }}">
                        <input type="hidden" name="id_comentario" value="{{ $comentario->id }}">
                        <input type="hidden" name="id_user_respondido" value="{{ $comentario->id_user }}">
                        <div class="input-group">
                            <input type="text" name="contenido" class="form-control" placeholder="Responder a {{ $comentario->user->perfil->name ?? $comentario->user->email }}" required>
                            <button type="submit" class="btn btn-outline-primary">Responder</button>
                        </div>
                    </form>
                    @endauth

                    {{-- Mostrar respuestas --}}
                    @if ($comentario->respuestas->count())
                        <div class="ms-3">
                            @foreach ($comentario->respuestas as $respuesta)
                                <div class="border-start ps-2 mb-2">
                                    <strong>{{ $respuesta->user->perfil->name ?? $respuesta->user->email }}:</strong>
                                    <p>{{ $respuesta->contenido }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="comentar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Comentar receta {{$receta->id}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('comentarios.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="id_receta" value="{{ $receta->id }}">
            <div class="mb-3">
                <textarea name="contenido" class="form-control" rows="3" placeholder="Escribe un comentario..." required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Comentar</button>
        </div>
    </form>
    </div>
  </div>
</div>

@endsection
