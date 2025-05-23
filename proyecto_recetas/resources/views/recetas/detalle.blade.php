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
                @if (auth()->id() === $receta->autor_receta)
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#editarReceta">Editar</button>

                        <form class="formBorrar" action="{{ route('recetas.eliminar', $receta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mb-3">Eliminar</button>
                        </form>
                    </div>
                @endif
            @endauth

            @auth
                @if(auth()->id() !== $receta->autor_receta)
                    <div class="d-flex gap-2">
                        <form id="guardarReceta" method="POST" action="{{ $guardada ? route('recetas.guardar.eliminar', $receta->id) : route('recetas.guardar', $receta->id) }}">
                            @csrf
                            @if($guardada)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="btn {{ $guardada ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                                {{ $guardada ? 'Quitar de guardadas' : 'Guardar' }}
                            </button>
                        </form>

                        <form id="meGusta" method="POST" action="{{ $gustada ? route('recetas.gustar.eliminar', $receta->id) : route('recetas.gustar', $receta->id) }}">
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
            
            <!--Cantidad de me gustas-->
            <p>Me gusta: {{$receta->usuariosQueGustaron->count()}}</p>	

            <!--Cantidad de guardados-->
            <p>Guardados: {{$receta->usuariosQueGuardaron->count()}}</p>
        </div>

        <!-- Columna derecha -->
        <div class="col-md-7">
            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h1 class="card-title m-0 d-inline">{{ $receta->titulo }}</h1>
                        <span class="text-muted ms-2 small">
                            <a href="{{ route('perfil.ver', ['id' => $receta->autor_receta]) }}"
                                class="text-primary fw-semibold hover-underline">
                                de {{ $receta->autor->perfil->name }} </a> <!--Cojo el autor, del autor, cojo el perfil y, de su perfil, el nombre-->
                        </span>
                    </div>
                    <span class="badge bg-light text-dark mb-3"> Tipo: {{ $receta->tipo }} </span>
                    <div class="mb-4">
                        <h4 class="text-primary">Ingredientes</h4>
                        <p class="fs-5">{{ $receta->ingredientes }}</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="text-success">Procedimiento</h4>
                        <p class="fs-5">{{ $receta->procedimiento }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Comentarios abajo en una fila aparte -->
    <div class="row mt-4">
        <div class="col-12">
            <strong><h2>Comentarios: {{$receta->comentarios->count()}} </h2></strong>
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

<!-- Modal Editar Receta-->
<div class="modal fade" id="editarReceta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Receta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('recetas.actualizar', $receta->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row g-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Título:</label>
                            <input type="text" name="titulo" id="titulo" value="{{$receta->titulo}}" class="form-control" placeholder="Título" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <input type="text" name="tipo" value="{{$receta->tipo}}" id="tipo" class="form-control" required>
                        </div>
                    </div>
                    <div class="justify-center">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <br/>
                        <img id="preview" class="mt-2 imagenPrevia" style="max-width: 250px; max-height: 250px;" src="{{ asset('storage/' . $receta->imagen) }}" alt="Imagen previa">
                        <input type="file" accept="image/*" id="imgInput" name="imagen" class="form-control">
                    </div>
                    <div class="row g-3">
                        <!-- Ingredientes -->
                        <div class="col-md-6">
                            <label for="ingredientes" class="form-label">Ingredientes:</label>
                            <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required>{{$receta->ingredientes}}</textarea>
                        </div>

                        <!-- Procedimiento -->
                        <div class="col-md-6">
                            <label for="procedimiento" class="form-label">Procedimiento:</label>
                            <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required>{{$receta->procedimiento}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Editar receta</button>
            </div>
        </form>
    </div>
  </div>
</div>

@endsection


@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Doble verificación dejar de gustar-->
    @if($gustada)
        <script>
            meGusta = document.getElementById('meGusta'); 

            meGusta.addEventListener('submit', (e) =>{
                e.preventDefault(); 

                Swal.fire({
                title: "¿Estás seguro de que ya no te gusta esta receta?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya no me gusta"
                }).then((result) => {

                if (result.isConfirmed) { 
                    Swal.fire({
                    title: "Ya no te gusta esta receta",
                    text: "",
                    icon: "success"
                    });
                    
                    setTimeout(() => {
                        meGusta.submit();
                    }, 500);
                }
                });
            })

        </script>
    @endif

    <!--Doble verificación quitar de guardados-->
    @if($guardada)
        <script>
            guardarReceta = document.getElementById('guardarReceta'); 

            guardarReceta.addEventListener('submit', (e) =>{
                e.preventDefault(); 

                Swal.fire({
                title: "¿Estás seguro de que ya no quieres guardar esta receta?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Quitar de guardados"
                }).then((result) => {

                if (result.isConfirmed) { 
                    Swal.fire({
                    title: "Ya no la tienes guardada",
                    text: "",
                    icon: "success"
                    });
                    
                    setTimeout(() => {
                        guardarReceta.submit();
                    }, 500);
                }
                });
            })

        </script>
    @endif

    <!--Doble verificación borrar-->

    <script>
        forms = document.querySelectorAll('.formBorrar');

        forms.forEach(form => {
            form.addEventListener('submit', (e) =>{
                e.preventDefault();

                Swal.fire({
                title: "¿Estás seguro de que deseas borrar la receta?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar"
                }).then((result) => {

                if (result.isConfirmed) { // Si se acepta, se lanza el otro popup y se hace el submit
                    Swal.fire({
                    title: "Registro eliminada",
                    text: "",
                    icon: "success"
                    });
                    
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }
                });
            })
        })
    </script>
@endsection
