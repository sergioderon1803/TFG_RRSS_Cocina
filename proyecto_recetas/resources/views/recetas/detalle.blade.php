@extends('layouts.app')

@section('titulo', 'Detalle de la receta')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">

        <!-- Tarjeta principal -->
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 p-4 bg-white">
                <div class="row">

                    <!-- Columna izquierda: Imagen + botones -->
                    <div class="col-md-5 position-relative">
                        @if ($receta->imagen)
                            <img src="{{ asset(Str::startsWith($receta->imagen, 'recetas/') ? 'storage/' . $receta->imagen : $receta->imagen) }}"
                                 class="img-fluid mb-3 rounded-4 shadow-sm w-100"
                                 alt="Imagen de {{ $receta->titulo }}">
                        
                            <!-- Botones para autor: posición absoluta encima de la imagen -->
                            @auth
                                @if (auth()->id() === $receta->autor_receta)
                                    <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">

                                        <!-- Botón editar -->
                                        <button type="button" class="btn p-0 border-0 bg-white bg-opacity-75 rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#editarReceta" title="Editar receta" style="width: 36px; height: 36px;">
                                            <i class="bi bi-pencil-square fs-5 text-warning d-flex justify-content-center align-items-center w-100 h-100"></i>
                                        </button>

                                        <!-- Botón eliminar -->
                                        <form class="formBorrar" action="{{ route('recetas.eliminar', $receta->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn p-0 border-0 bg-white bg-opacity-75 rounded-circle shadow" title="Eliminar receta" style="width: 36px; height: 36px;">
                                                <i class="bi bi-trash-fill fs-5 text-danger d-flex justify-content-center align-items-center w-100 h-100"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        @endif

                        <div class="d-flex gap-3 align-items-center flex-wrap mb-3">
                            @auth
                                @if(auth()->id() !== $receta->autor_receta)

                                    <!-- Botón me gusta -->
                                    <form id="meGusta" method="POST" action="{{ $gustada ? route('recetas.gustar.eliminar', $receta->id) : route('recetas.gustar', $receta->id) }}" class="d-flex align-items-center gap-2">
                                        @csrf
                                        @if($gustada) @method('DELETE') @endif
                                        <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="{{ $gustada ? 'Quitar me gusta' : 'Dar me gusta' }}">
                                            <i class="bi bi-heart{{ $gustada ? '-fill' : '' }} fs-5" style="color: #F07B3F;"></i>
                                        </button>
                                        <span>{{ $receta->usuariosQueGustaron->count() }}</span>
                                    </form>

                                    <!-- Botón guardar -->
                                    <form id="guardarReceta" method="POST" action="{{ $guardada ? route('recetas.guardar.eliminar', $receta->id) : route('recetas.guardar', $receta->id) }}" class="d-flex align-items-center gap-2">
                                        @csrf
                                        @if($guardada) @method('DELETE') @endif
                                        <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="{{ $guardada ? 'Quitar de guardadas' : 'Guardar receta' }}">
                                            <i class="bi bi-bookmark{{ $guardada ? '-fill' : '' }} fs-5" style="color: #2A9D8F;"></i>
                                        </button>
                                        <span>{{ $receta->usuariosQueGuardaron->count() }}</span>
                                    </form>
                                    
                                @else

                                    <!-- Solo conteo para el autor -->
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-heart-fill fs-5" style="color: #F07B3F;"></i>
                                            <span>{{ $receta->usuariosQueGustaron->count() }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-bookmark-fill fs-5" style="color: #2A9D8F;"></i>
                                            <span>{{ $receta->usuariosQueGuardaron->count() }}</span>
                                        </div>
                                    </div>
                                @endif
                            @else

                                <!-- Solo conteo para invitados -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-heart-fill text-danger fs-5" style="color: #F07B3F;"></i>
                                        <span>{{ $receta->usuariosQueGustaron->count() }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-bookmark-fill text-primary fs-5" style="color: #2A9D8F;"></i>
                                        <span>{{ $receta->usuariosQueGuardaron->count() }}</span>
                                    </div>
                                </div>
                            @endauth
                        </div>

                        <!-- Ingredientes -->
                        <div class="card mt-4 shadow-sm border-0">
                            <div class="card-header bg-white border-0">
                                <strong>Ingredientes</strong>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    @foreach(explode("\n", $receta->ingredientes) as $ingrediente)
                                        <li>{{ $ingrediente }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: Información de la receta -->
                    <div class="col-md-7">
                        <div class="mb-2 text-muted">
                            <a href="{{ route('perfil.ver', ['id' => $receta->autor_receta]) }}"
                               class="text-decoration-none text-primary fw-semibold">
                               {{ '@' . Str::slug($receta->autor->perfil->name) }}
                            </a>
                        </div>

                        <h2 class="fw-bold mb-3">{{ $receta->titulo }}</h2>

                        <p class="mb-3">
                            <span class="badge bg-secondary">Tipo: {{ $receta->tipo }}</span>
                        </p>

                        <div>
                            <h5 class="text-success">Procedimiento</h5>
                            <p class="fs-6" style="white-space: pre-line;">{{ $receta->procedimiento }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMENTARIOS -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h3 class="mb-4">Comentarios ({{ $receta->comentarios->count() }})</h3>

                @auth
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#comentar">
                        Comentar
                    </button>
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}">Inicia sesión</a> para comentar.
                    </div>
                @endauth

                @foreach ($receta->comentarios as $comentario)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <strong>{{ $comentario->user->perfil->name ?? $comentario->user->email }}:</strong>
                        <p>{{ $comentario->contenido }}</p>

                        @auth
                            <form action="{{ route('respuestas.store') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="id_receta" value="{{ $receta->id }}">
                                <input type="hidden" name="id_comentario" value="{{ $comentario->id }}">
                                <input type="hidden" name="id_user_respondido" value="{{ $comentario->id_user }}">
                                <div class="input-group">
                                    <input type="text" name="contenido" class="form-control"
                                           placeholder="Responder a {{ $comentario->user->perfil->name ?? $comentario->user->email }}"
                                           required>
                                    <button type="submit" class="btn btn-outline-primary">Responder</button>
                                </div>
                            </form>
                        @endauth

                        @if ($comentario->respuestas->count())
                            <div class="ms-3 mt-2">
                                @foreach ($comentario->respuestas as $respuesta)
                                    <div class="border-start ps-3 mb-2">
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
