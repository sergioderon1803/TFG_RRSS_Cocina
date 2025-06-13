@extends('layouts.app')

@section('titulo', 'Búsqueda')

@section('css')

    <style>
        .usuarioCoincidencia:hover{
            background-color:rgba(238,114,71,255); 
            color:white;
        }
        
    </style>

@endsection

@section('content')
    <div class="container-fluid my-3 px-3 mb-5">
        <div class="d-flex align-items-center mb-4">
            <strong class="mx-auto p-2 rounded-2">Búsqueda: "{{$filtro}}"</strong>
            <form id="formBusqueda" action="{{ route('usuario.busqueda') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-block">
                    <div class="input-group">
                        <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Buscar usuario o receta">
                        <button type="submit" class="btn botonVerMas">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div id="busquedaUsuarios">
                    </div>
                </div>
            </form>
        </div>
        <div class="row gx-5 gy-4">
            <!-- Columna de recetas -->
            <div class="col-12 col-sm-9 col-md-9 col-xl-9 margenesResponsive">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4" id="listado">
                    @forelse($recetas as $receta)
                        <div class="col recetaListada">
                            <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3 recetaResponsive" style="cursor: pointer;">
                                <img src="{{ $receta->imagen ? asset('storage/' . $receta->imagen) : asset('images/default-img.jpg') }}"
                                    class="card-img-top"
                                    alt="Imagen de {{$receta->titulo}}"
                                    style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;" onclick="window.location='{{ url('receta/'. $receta->imagen) }}'"
                                    onerror="this.onerror=null;this.src='{{ asset('images/default-img.jpg') }}';">

                                <div class="card-body d-flex flex-column justify-content-between p-2">
                                    <div class="mb-2 text">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                            <a href="{{ url('perfil/'.$receta->autor_receta) }}" 
                                            class="text-decoration-none text-muted">
                                                <img src="{{ asset('images/default-profile.jpg') }}"
                                                    alt="Imagen de perfil"
                                                    class="rounded-circle me-2"
                                                    style="width: 25px; height: 25px; object-fit: cover;">
                                                {{$receta->autor->perfil->name}}
                                            </a>
                                        </div>
                                        <h6 class="card-title mb-1" style="font-size: 0.95rem;" onclick="window.location='{{ url('receta/'. $receta->id) }}'">
                                            <strong>{{$receta->titulo}} </strong>
                                        </h6>
                                    </div>
                                    <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                        <button id="btnLike{{$receta->id}}" class="btn p-0 border-0 bg-transparent" title="{{$receta->like ? "Quitar me gusta" : "Dar me gusta"}}">
                                            <i data-id="{{$receta->id}}" class="bi bi-heart{{$receta->like ? "-fill" : ""}} text-danger darLike"></i>
                                            <small id="gustas{{$receta->id}}">{{$receta->usuariosQueGustaron->count()}}</small>
                                        </button>

                                        <button id="btnGuardado{{$receta->id}}" class="btn p-0 border-0 bg-transparent" title="{{$receta->guardado ? "Quitar de guardadas" : "Guardar receta"}}">
                                            <i data-id="{{$receta->id}}" class="bi bi-bookmark{{$receta->guardado ? "-fill" : ""}} text-success guardados"></i>
                                            <small id="guardados{{$receta->id}}">{{$receta->usuariosQueGuardaron->count()}}</small>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No se encontraron recetas</p>
                    @endforelse
                </div>
            </div>

            <!-- Columna de filtros -->
            <div class="col-12 col-sm-3 col-md-3 col-xl-3 margenesResponsive">
                <div class="card mb-3 colorPrincipal">
                    <div class="card-header bg-light colorPrincipalOscuro">
                        <strong class="text-center text-white">Perfiles</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" style="display:block;position:relative;z-index:0;">
                            @forelse($usuarios as $user)
                                <li class="list-group-item usuarioCoincidencia btn-cancelar rounded-1 p-0 usuarioCoincidencia">
                                    <a href="{{ url('perfil/' . $user->id_user) }}" class="text-decoration-none text-muted d-flex align-items-center w-100 p-2">
                                        <img src="{{ asset('storage/' . $user->img_perfil) }}"
                                            class="rounded-circle shadow-sm me-2"
                                            onerror="this.onerror=null;this.src='{{ asset('images/default-profile.jpg') }}';"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <p class="pb-0 mb-0 mb-0">{{ $user->name }}</p>
                                    </a>
                                </li>
                            @empty
                                <p class="text-center colorPrincipal text-white">No se encontraron perfiles</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){

            $('#formBusqueda').on('submit',function(e){
                if($('#busqueda').val() == ""){
                    e.preventDefault();
                }
            })

            // Cada vez que el usuario teclea en el buscador

            $('#busqueda').on('keyup',function(){
                var input = $(this).val();

                // Si el valor es distinto de vacío, hace Ajax y sustituye lo recogido por el contenido del html, si está vacío, borra todo el contenido

                if(input != ""){
                    $.ajax({
                        url:"{{ route('usuario.buscarPerfiles')}}",
                        method:"GET",
                        data: {
                            input: input,
                            _token: '{{ csrf_token() }}',
                        },

                        success:function(datos){
                            $('#busquedaUsuarios').html(datos);
                        }
                    })
                }else{
                    $('#busquedaUsuarios').html("");
                }
            });
        });
    </script>

    <script>
//-------------------------------------------------------------------------------------GUARDADOS--------------------------------------------------------------------------------------------------

        $(".guardados").on("click", function() {

            const recetaId = $(this).data('id');

            let etiqueta = `#guardados${recetaId}`; // Guardo el id de la etiqueta small

            let etiquetaBtn = `#btnGuardado${recetaId}`;

            let valorGuardados = parseInt($(etiqueta).text()); // Convierto en número el txto que tiene

            if ($(this).hasClass('bi-bookmark-fill')) {


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
                                        title: "Ya no la tienes guardada",
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

                        $(this).removeClass('bi-bookmark-fill');
                        $(this).addClass('bi-bookmark');

                        $(etiquetaBtn).attr('title', "Guardar receta");

                        // Cambiu el valor del html por la nueva cantidad de guardados

                        valorGuardados--;

                        $(etiqueta).text(valorGuardados.toString());
                    }


                });

            } else {
                $.ajax({
                    url: `{{ url('/recetas/guardarReceta/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                // Le quito una clase y le pongo la otra

                $(this).removeClass('bi-bookmark');
                $(this).addClass('bi-bookmark-fill');

                $(etiquetaBtn).attr('title', "Quitar de guardadas");

                // Cambiu el valor del html por la nueva cantidad de guardados

                valorGuardados++;
                $(etiqueta).text(valorGuardados.toString());
            }



        });

        //-------------------------------------------------------------------------------------ME GUSTAS--------------------------------------------------------------------------------------------------

        $(".darLike").on("click", function() {

            const recetaId = $(this).data('id');

            let etiqueta = `#gustas${recetaId}`; // Guardo el id de la etiqueta small

            let etiquetaBtn = `#btnLike${recetaId}`;

            let valorMegusta = parseInt($(etiqueta).text()); // Convierto en número el txto que tiene

            if ($(this).hasClass('bi-heart-fill')) {

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

                        // Le quito una clase y le pongo la otra

                        $(this).removeClass('bi-heart-fill');
                        $(this).addClass('bi-heart');

                        $(etiquetaBtn).attr('title', "Dar me gusta");

                        // Cambio el valor del html por la nueva cantidad de me gusta

                        valorMegusta--;

                        
                        $(etiqueta).text(valorMegusta.toString());
                    }


                });

            } else {

                $.ajax({
                    url: `{{ url('/recetas/darMeGusta/') }}/${recetaId}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                // Le quito una clase y le pongo la otra

                $(this).removeClass('bi-heart');
                $(this).addClass('bi-heart-fill');

                $(etiquetaBtn).attr('title', "Quitar me gusta");

                // Cambiu el valor del html por la nueva cantidad de me gusta

                valorMegusta++;
                $(etiqueta).text(valorMegusta.toString());
            }



        });
    </script>

@endsection