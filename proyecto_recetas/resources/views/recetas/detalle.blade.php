@extends('layouts.app')

@section('titulo', 'Detalle de la receta')

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">

            <!-- Tarjeta principal -->
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                    @if ($receta->estado == 1)
                        <div class="alert alert-danger custom-alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Esta receta está oculta, póngase en contacto con un administrador para más detalles
                        </div>
                    @endif
                    <div class="row">

                        <!-- Columna izquierda: Imagen + botones -->
                        <div class="col-md-5 position-relative">
                            @if ($receta->imagen)
                                <img src="{{ asset(Str::startsWith($receta->imagen, 'recetas/') ? 'storage/' . $receta->imagen : $receta->imagen) }}"
                                    class="img-fluid mb-3 rounded-4 shadow-sm w-100" alt="Imagen de {{ $receta->titulo }}" onerror="this.onerror=null;this.src='{{ asset('images/default-img.jpg') }}';">

                                <!-- Botones para autor: posición absoluta encima de la imagen -->
                                @auth
                                    @if (auth()->id() === $receta->autor_receta)
                                        <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">

                                            <!-- Botón editar -->
                                            <button type="button"
                                                class="btn p-0 border-0 bg-white bg-opacity-75 rounded-circle shadow"
                                                data-bs-toggle="modal" data-bs-target="#editarReceta" title="Editar receta"
                                                style="width: 36px; height: 36px;">
                                                <i
                                                    class="bi bi-pencil-square fs-5 text-warning d-flex justify-content-center align-items-center w-100 h-100"></i>
                                            </button>

                                            <!-- Botón eliminar -->
                                            <form class="formBorrar" action="{{ route('recetas.eliminar', $receta->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn p-0 border-0 bg-white bg-opacity-75 rounded-circle shadow"
                                                    title="Eliminar receta" style="width: 36px; height: 36px;">
                                                    <i
                                                        class="bi bi-trash-fill fs-5 text-danger d-flex justify-content-center align-items-center w-100 h-100"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            @endif

                            <div class="d-flex gap-3 align-items-center flex-wrap mb-3">
                                @auth
                                    @if (auth()->id() !== $receta->autor_receta)
                                        <button id="btnLike" type="submit" class="btn btn-sm p-0 border-0 bg-transparent"
                                            title="{{ $gustada ? 'Quitar me gusta' : 'Dar me gusta' }}">
                                            <i data-id="{{ $receta->id }}" id="darLike"
                                                class="bi bi-heart{{ $gustada ? '-fill' : '' }} fs-5"
                                                style="color: #F07B3F;"></i>
                                        </button>
                                        <span id="contMeGusta">{{ $receta->usuariosQueGustaron->count() }}</span>

                                        <!-- Botón guardar -->
                                        <button id="btnGuardar" type="submit" class="btn btn-sm p-0 border-0 bg-transparent"
                                            title="{{ $guardada ? 'Quitar de guardadas' : 'Guardar receta' }}">
                                            <i data-id="{{ $receta->id }}" id="guardarReceta" class="bi bi-bookmark{{ $guardada ? '-fill' : '' }} fs-5"
                                                style="color: #2A9D8F;"></i>
                                        </button>
                                        <span id="contGuardado">{{ $receta->usuariosQueGuardaron->count() }}</span>
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
                                        @foreach (explode("\n", $receta->ingredientes) as $ingrediente)
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
                    <h1 class="list-group-item text-center modal-title fs-5 text-decoration-none col-12 py-3 m-0 rounded-0" style="color:white;" id="exampleModalLabel">Comentar receta de {{ '@' . Str::slug($receta->autor->perfil->name) }}</h1>
                </div>
                <form action="{{ route('comentarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_receta" value="{{ $receta->id }}">
                        <div class="mb-3">
                            <textarea name="contenido" class="form-control" rows="3" placeholder="Escribe un comentario..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between flex-nowrap p-1 m-1">
                        <button type="submit" class="btn btn-guardar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2">Comentar</button>
                        <button type="button" class="btn btn-cancelar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" data-bs-dismiss="modal">Cancelar</button>
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
                    <h1 class="list-group-item text-center modal-title fs-4 text-decoration-none col-12 py-3 m-0 rounded-0" style="color:white;" id="exampleModalLabel">Editar Receta</h1>
                </div>
                <form action="{{ route('recetas.actualizar', $receta->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="titulo" class="form-label">Título:</label>
                                    <input type="text" name="titulo" id="titulo" value="{{ $receta->titulo }}"
                                        class="form-control" placeholder="Título" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">Tipo:</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option value="" selected disabled>Selecciona tipo de receta</option>
                                        <option value="Postres y dulces">Postres y dulces</option>
                                        <option value="Arroz">Arroz</option>
                                        <option value="Pasta">Pasta</option>
                                        <option value="Carnes y aves">Carnes y aves</option>
                                        <option value="Pescado y marisco">Pescado y marisco</option>
                                        <option value="Verduras y hortalizas">Verduras y hortalizas</option>
                                        <option value="Ensaladas">Ensaladas</option>
                                        <option value="Huevos y tortillas">Huevos y tortillas</option>
                                        <option value="Tapas y aperitivos">Tapas y aperitivos</option>
                                        <!-- etc -->
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <label for="imagen" class="form-label">Imagen:</label>
                                <img id="preview" class="mt-2 imagenPrevia"
                                    style="max-width: 250px; max-height: 250px;"
                                    src="{{ asset('storage/' . $receta->imagen) }}" alt="Imagen previa">
                                <input type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" id="imgInput" name="imagen"
                                    class="form-control">
                            </div>


                            <div class="row g-3">
                                <!-- Ingredientes -->
                                <div class="col-md-6">
                                    <label for="ingredientes" class="form-label">Ingredientes:</label>
                                    <textarea name="ingredientes" id="ingredientes" class="form-control" rows="6" required>{{ $receta->ingredientes }}</textarea>
                                </div>

                                <!-- Procedimiento -->
                                <div class="col-md-6">
                                    <label for="procedimiento" class="form-label">Procedimiento:</label>
                                    <textarea name="procedimiento" id="procedimiento" class="form-control" rows="6" required>{{ $receta->procedimiento }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between flex-nowrap p-0">
                        <button type="submit" class="btn btn-guardar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" style="background-color:#2A9D8F; color:white;">Guardar cambios</button>
                        <button type="button" class="btn btn-cancelar btn-sm fs-6 text-decoration-none col-4 py-3 rounded-2" style="background-color:#E76F51; color:white;" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!--Botón dar like-->
    <script>
        $('#btnLike').on('click', function() {

            let valorMeGusta = parseInt($('#contMeGusta').text());

            const recetaId = $('#darLike').data('id');

            if ($('#darLike').hasClass('bi-heart')) {

                $.ajax({
                    url: `{{ url('/recetas/darMeGusta/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                valorMeGusta++;

                $('#darLike').removeClass('bi-heart');
                $('#darLike').addClass('bi-heart-fill');

                $('#contMeGusta').text(valorMeGusta.toString());

                $('#btnLike').attr('title', "Quitar me gusta");


            } else {

                Swal.fire({
                    title: "¿Estás seguro de que ya no te gusta esta receta?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2A9D8F",
                    cancelButtonColor: "#E76F51",
                    confirmButtonText: "No me gusta"
                }).then((result) => {

                    if (result.isConfirmed) { // Si acepta borrarla, hago un ajax

                        $.ajax({
                            url: `{{ url('/recetas/quitarMeGusta/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                            },

                            // Si acepta y la respuesta es la que mando en el controlador, lanzo un pop up
                            success: function(response) {
                                if (response.status === 'success') {

                                    Swal.fire({
                                        title: "Ya no te gusta esta receta",
                                        text: "",
                                        icon: "success"
                                    });

                                } else {
                                    Swal.fire(
                                        'No se ha podido completar la solicitud',
                                        '', 'warning');
                                }
                            },
                            error: function(error) {
                                Swal.fire('Se ha producido un error',
                                    '', 'error');
                            }
                        })

                        valorMeGusta--;

                        $('#darLike').removeClass('bi-heart-fill');
                        $('#darLike').addClass('bi-heart');

                        $('#contMeGusta').text(valorMeGusta.toString());

                        $('#btnLike').attr('title', "Dar me gusta");
                    }


                });


            }

        });
    </script>

    <!--Botón guardar-->
    <script>
        $('#btnGuardar').on('click', function() {

            let valorGuardados = parseInt($('#contGuardado').text());

            const recetaId = $('#guardarReceta').data('id');

            if ($('#guardarReceta').hasClass('bi-bookmark')) {

                $.ajax({
                    url: `{{ url('/recetas/guardarReceta/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                valorGuardados++;

                $('#guardarReceta').removeClass('bi-bookmark');
                $('#guardarReceta').addClass('bi-bookmark-fill');

                $('#contGuardado').text(valorGuardados.toString());

                $('#btnGuardar').attr('title', "Quitar de guardadas");


            } else {

                Swal.fire({
                    title: "¿Estás seguro de que ya no quieres guardar esta receta?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2A9D8F",
                    cancelButtonColor: "#E76F51",
                    confirmButtonText: "Quitar de guardados"
                }).then((result) => {

                    if (result.isConfirmed) { // Si acepta borrarla, hago un ajax

                        $.ajax({
                            url: `{{ url('/recetas/quitarGuardado/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                            },

                            // Si acepta y la respuesta es la que mando en el controlador, lanzo un pop up
                            success: function(response) {
                                if (response.status === 'success') {

                                    Swal.fire({
                                        title: "Ya no tienes guardada esta receta",
                                        text: "",
                                        icon: "success"
                                    });

                                } else {
                                    Swal.fire(
                                        'No se ha podido completar la solicitud',
                                        '', 'warning');
                                }
                            },
                            error: function(error) {
                                Swal.fire('Se ha producido un error',
                                    '', 'error');
                            }
                        })

                        valorGuardados--;

                        $('#guardarReceta').removeClass('bi-bookmark-fill');
                        $('#guardarReceta').addClass('bi-bookmark');

                        $('#contGuardado').text(valorGuardados.toString());

                        $('#btnGuardar').attr('title', "Guardar receta");
                    }


                });


            }

        });
    </script>


    <!--Doble verificación borrar-->

    <script>
        forms = document.querySelectorAll('.formBorrar');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                Swal.fire({
                    title: "¿Estás seguro de que deseas borrar la receta?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2A9D8F",
                    cancelButtonColor: "#E76F51",
                    confirmButtonText: "Eliminar"
                }).then((result) => {

                    if (result
                        .isConfirmed) { // Si se acepta, se lanza el otro popup y se hace el submit
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
