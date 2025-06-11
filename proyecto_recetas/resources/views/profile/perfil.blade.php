@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('content')
@php
    // Comprobar si hay imagen de perfil y banner y si no, se ponen las imagenes por defecto
    $imgPerfil = Str::startsWith($perfil->img_perfil, 'perfiles/')
        ? asset('storage/' . $perfil->img_perfil)
        : asset('images/default-profile.jpg');

    $imgBanner = Str::startsWith($perfil->img_banner, 'perfiles/')
        ? asset('storage/' . $perfil->img_banner)
        : asset('images/default-banner.jpg');
@endphp

<div class="position-relative mb-5">
    {{-- Banner --}}
    <div class="w-100" style="height: 250px; background: url('{{ $imgBanner }}') no-repeat center center; background-size: cover;">
    </div>

    {{-- Imagen de perfil --}}
    <div class="position-absolute top-100 start-0 translate-middle-y ps-4">
        <img src="{{ $imgPerfil }}"
            class="rounded-circle border border-black shadow"
            style="width: 120px; height: 120px; object-fit: cover;"
            alt="Perfil de {{ $perfil->name }}">
    </div>
    {{-- Botón de editar o seguir, según el caso --}}
    @auth
        <div class="position-absolute posicionBoton p-4">
            @if (Auth::id() === $perfil->id_user)
                <button type="button" class="btn btn-outline-primary fs-6 btn-sm" data-bs-toggle="modal" data-bs-target="#editarPeril">
                    Editar perfil
                </button>
            @else
                <button data-id="{{$perfil->id_user}}" id="seguirUsuario" type="submit" class='btn fs-6 btn-{{$seguido ? "outline-dark" : "dark"}} btn-sm'>{{$seguido ? "Siguiendo" : "Seguir"}}</button>
            @endif
        </div>
    @endauth
</div>

{{-- Info del usuario y contadores --}}
<div class="d-flex justify-content-between align-items-start px-4 mt-4">
    {{-- Info del usuario --}}
    <div>
        <br>
        <p class="fw-bold mb-3">{{ '@' . Str::slug($perfil->name) }}</p>
        <p>{{ $perfil->biografia ?? '¡Compartiendo recetas en WeCook!' }}</p>
    </div>

    {{-- Seguidores / Seguidos (alineado a la derecha) --}}
    <div class="d-flex gap-4 text-center">
        <div class="d-flex flex-column">
            <strong id="contSeguidores" class="text-dark">{{ $perfil->user->seguidores->count() }}</strong>
            <a href="{{ route('profile.seguidores', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidores</a>
        </div>
        <div class="d-flex flex-column">
            <strong class="text-dark">{{ $perfil->user->seguidos->count() }}</strong>
            <a href="{{ route('profile.seguidos', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidos</a>
        </div>
    </div>
</div>

<hr class="mt-4">

<h5 class="fw-bold mb-3">
    <div class="d-flex w-100">
        <i class="bi bi-person-fill w-50 tamañoSecciones seleccionado text-center" id="clickRecetas"></i>
        <i class="bi bi-heart w-50 tamañoSecciones text-center" id="clickMeGustas"></i>
    </div>
</h5>

<!-- Donde se van a listar las recetas -->
<div class="row" id="listado">
</div>

{{-- MODALS EXTERNOS --}}
@include('modals.editar-perfil', ['perfil' => $perfil])
@include('modals.recetas-guardadas', ['perfil' => $perfil])

@endsection



{{-- HAY QUE REFACTORIZAR ESTO Y PONERLO EN UN JS EXTERNO A SER POSIBLE --}}
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Seguir usuario--}}

    <script>
        $('#seguirUsuario').on('click', function(){

            let numSeguidores = parseInt($('#contSeguidores').text());

            const idUsuario = $(this).data('id');

            if($(this).hasClass('btn-dark')){
                $.ajax({
                    url: `{{ url('usuario/seguirUsuario/') }}/${idUsuario}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                numSeguidores++;

                $(this).removeClass('btn-dark');
                $(this).addClass('btn-outline-dark');

                $(this).text("Siguiendo");
                $('#contSeguidores').text(numSeguidores.toString());

            }
            else
            {
                Swal.fire({
                    title: "¿Estás seguro de que deseas dejar de seguir a este usuario?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2A9D8F",
                    cancelButtonColor: "#E76F51",
                    confirmButtonText: "Dejar de seguir"
                }).then((result) => {

                    if (result.isConfirmed) { // Si acepta borrarla, hago un ajax

                        $.ajax({
                            url: `{{ url('usuario/dejarSeguirUsuario/') }}/${idUsuario}`, // Llamo al controlador y le paso el ID
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                            },

                            // Si acepta y la respuesta es la que mando en el controlador, lanzo un pop up
                            success: function(response) {
                                if (response.status === 'success') {

                                    Swal.fire({
                                        title: "Dejaste de seguir a este usuario",
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

                        numSeguidores--;

                        $(this).removeClass('btn-outline-dark');
                        $(this).addClass('btn-dark');

                        $(this).text("Seguir");
                        $('#contSeguidores').text(numSeguidores.toString());
                    }


                });
            }
        });
    </script>

    <script>

        // Recojo la URL y pillo el ID del usuario
        const url = window.location.href.split("/");
        const idUsuario = parseInt(url[url.length-1]);

        // Por defecto, pillo las recetas publicadas

        $(document).ready(function(){

            var storageBase = "{{ asset('storage') }}";
            var defaultImg = "{{ asset('images/default-img.jpg') }}";

            $.ajax({
                url:"{{route('recetas.listaRecetasAjax')}}",
                method: 'POST',
                data:{
                    id: idUsuario,
                    _token: '{{csrf_token()}}',
                }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                // Impresión del listado de recetas

                var listado = `<div class="row" id="recetasListadas">`;

                if(arreglo.length == 0){

                    listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                }else{ 

                    for(var x = 0; x<arreglo.length;x++){

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm">
                                    <img src="` + storageBase + `/` + arreglo[x].imagen + `"
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + arreglo[x].id +`') }}" class="text-decoration-none text-dark">
                                            `+arreglo[x].titulo+`
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>`;

                    }
                }

                listado += `</div>`;

                $("#listado").append(listado);

                // Le meto el onclick al otro listado, borro todo el div que acabo de crear

                $('#clickMeGustas').on('click',function(){
                
                    $('#clickMeGustas').off('click');

                    document.getElementById('recetasListadas').remove();

                    document.getElementById("clickMeGustas").classList.add('seleccionado');
                    document.getElementById("clickMeGustas").classList.remove('bi-heart');
                    document.getElementById("clickMeGustas").classList.add('bi-heart-fill');

                    document.getElementById("clickRecetas").classList.remove('seleccionado');
                    document.getElementById("clickRecetas").classList.remove('bi-person-fill');
                    document.getElementById("clickRecetas").classList.add('bi-person');

                    crearListadoMeGustas();
                });

            })
        });

// A partir de aquí, es la misma lógica para los dos listados, guardo en funciones lo que hacen para poder gestionar los eventos

//-----------------------------------------------LISTADO ME GUSTAS-----------------------------------------------

        function crearListadoMeGustas(){

            $(document).ready(function(){

                var storageBase = "{{ asset('storage') }}";
                var defaultImg = "{{ asset('images/default-img.jpg') }}";

                $.ajax({
                    url:"{{route('recetas.listarMeGustaAjax')}}",
                    method: 'POST',
                    data:{
                        id: idUsuario,
                        _token: '{{csrf_token()}}',
                    }
                }).done(function(res){
                    var arreglo = JSON.parse(res);

                    var listado = `<div class="row" id="recetasListadas">`;

                    if(arreglo.length == 0){

                        listado += `<p class="text-muted">A este usuario no le gusta ninguna receta de momento.</p>`;

                    }else{ 

                        for(var x = 0; x<arreglo.length;x++){

                            listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm">
                                    <img src="` + storageBase + `/` + arreglo[x].imagen + `"
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + arreglo[x].id +`') }}" class="text-decoration-none text-dark">
                                            `+arreglo[x].titulo+`
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>`;

                        }
                    }

                    listado += `</div>`;

                    $("#listado").append(listado);

                    $('#clickRecetas').on('click',function(){
                
                        $('#clickRecetas').off('click');
                        
                        document.getElementById('recetasListadas').remove();
                        
                        document.getElementById("clickMeGustas").classList.remove('seleccionado');
                        document.getElementById("clickMeGustas").classList.remove('bi-heart-fill');
                        document.getElementById("clickMeGustas").classList.add('bi-heart');

                        document.getElementById("clickRecetas").classList.add('seleccionado');
                        document.getElementById("clickRecetas").classList.remove('bi-person');
                        document.getElementById("clickRecetas").classList.add('bi-person-fill');

                        crearListadoRecetasPublicadas();
                    });

                })
            });

        }

        //--------------------------------------------------------------------------------------------------------------------


        function crearListadoRecetasPublicadas(){

            $(document).ready(function(){

                var storageBase = "{{ asset('storage') }}";
                var defaultImg = "{{ asset('images/default-img.jpg') }}";

                $.ajax({
                    url:"{{route('recetas.listaRecetasAjax')}}",
                    method: 'POST',
                    data:{
                        id: idUsuario,
                        _token: '{{csrf_token()}}',
                    }
                }).done(function(res){
                    var arreglo = JSON.parse(res);

                    var listado = `<div class="row" id="recetasListadas">`;

                    if(arreglo.length == 0){

                        listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                    }else{ 

                        for(var x = 0; x<arreglo.length;x++){

                            listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm">
                                    <img src="` + storageBase + `/` + arreglo[x].imagen + `"
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + arreglo[x].id +`') }}" class="text-decoration-none text-dark">
                                            `+arreglo[x].titulo+`
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>`;

                        }
                    }

                    listado += `</div>`;

                    $("#listado").append(listado);

                    $('#clickMeGustas').on('click',function(){
                    
                        $('#clickMeGustas').off('click');

                        document.getElementById('recetasListadas').remove();

                        document.getElementById("clickMeGustas").classList.add('seleccionado');
                        document.getElementById("clickMeGustas").classList.remove('bi-heart');
                        document.getElementById("clickMeGustas").classList.add('bi-heart-fill');

                        document.getElementById("clickRecetas").classList.remove('seleccionado');
                        document.getElementById("clickRecetas").classList.remove('bi-person-fill');
                        document.getElementById("clickRecetas").classList.add('bi-person');

                        crearListadoMeGustas();
                    });

                    })
                });
        }
    </script>

    <script>
        document.getElementById('img_perfil').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'flex';
            } else {
            preview.src = '';
            preview.style.display = 'none';
            }
        });

        document.getElementById('img_banner').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewBanner');

            if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'flex';
            } else {
            preview.src = '';
            preview.style.display = 'none';
            }
        });
    </script>
@endsection
