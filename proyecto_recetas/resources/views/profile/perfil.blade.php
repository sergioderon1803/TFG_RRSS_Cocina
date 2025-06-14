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
                <button type="button" class="btn btn-seguir fs-6 btn-sm" data-bs-toggle="modal" data-bs-target="#editarPeril">
                    Editar perfil
                </button>
            @else
                <button data-id="{{$perfil->id_user}}" id="seguirUsuario" type="submit" class='btn fs-6 btn-{{$seguido ? "siguiendo" : "seguir"}} btn-sm'>{{$seguido ? "Siguiendo" : "Seguir"}}</button>
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
    <div class="d-flex gap-4 text-center mt-3">
        <div class="d-flex flex-column">
            <strong id="contSeguidores" class="text-dark"></strong>
            <p id="btnSeguidores" class="text-decoration-none text-muted" style="cursor: pointer;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Seguidores</p>
        </div>
        <div class="d-flex flex-column">
            <strong id="contSeguidos" class="text-dark"></strong>
            <p id="btnSeguidos" class="text-decoration-none text-muted" style="cursor: pointer;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Seguidos</p>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-body">
    <div class="d-flex-inline">
        <h5 class="fw-bold mb-3">
            <div class="d-flex w-100 justify-content-evenly">
                <span id="seguidores" style="cursor: pointer;text-decoration: underline;">Seguidores</span>
                <span id="seguidos" style="cursor: pointer;text-decoration: underline;">Seguidos</span>
            </div>
        </h5>
        <hr>

        <div id="listadoUsuarios" class="container py-4">
        </div>
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

            if($(this).hasClass('btn-seguir')){
                $.ajax({
                    url: `{{ url('usuario/seguirUsuario/') }}/${idUsuario}`, // Llamo al controlador y le paso el ID
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Le paso el token de la sesión, si no, no me deja hacerlo
                    }
                })

                $.ajax({
                    url: `{{ url('/perfil/seguidores/') }}/${idUsuario}`,
                    method: 'POST',
                    data:{
                        _token: '{{csrf_token()}}',
                }
                }).done(function(res){
                    var arreglo = JSON.parse(res);

                    seguidoresArray = arreglo;
                })

                numSeguidores++;

                $(this).removeClass('btn-seguir');
                $(this).addClass('btn-siguiendo');

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

                                    $.ajax({
                                        url: `{{ url('/perfil/seguidores/') }}/${idUsuario}`,
                                        method: 'POST',
                                        data:{
                                            _token: '{{csrf_token()}}',
                                        }
                                    }).done(function(res){
                                        var arreglo = JSON.parse(res);

                                        seguidoresArray = arreglo;
                                    })

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

                        $(this).removeClass('btn-siguiendo');
                        $(this).addClass('btn-seguir');

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

        var storageBase = "{{ asset('storage') }}";
        var defaultImg = "{{ asset('images/default-img.jpg') }}";
        var defaultImgPerfil = "{{ asset('images/default-profile.jpg') }}";

        var seguidoresArray = [];
        var seguidosArray = [];
        var recetasPublicadas = [];
        var recetasMeGusta = [];

        $(document).ready(function(){
            $.ajax({
                url: `{{ url('/perfil/seguidos/') }}/${idUsuario}`,
                method: 'POST',
                data:{
                    _token: '{{csrf_token()}}',
                }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                seguidosArray = arreglo;

                $('#contSeguidos').text(seguidosArray.length.toString());
            })

            $.ajax({
                url: `{{ url('/perfil/seguidores/') }}/${idUsuario}`,
                method: 'POST',
                data:{
                    _token: '{{csrf_token()}}',
            }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                seguidoresArray = arreglo;

                $('#contSeguidores').text(seguidoresArray.length.toString());
            })

            $.ajax({
                url:"{{route('recetas.listarMeGustaAjax')}}",
                method: 'POST',
                data:{
                    id: idUsuario,
                    _token: '{{csrf_token()}}',
                }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                recetasMeGusta = arreglo;
            })
        });


        function listarSeguidores(){

            $('#seguidores').addClass('seleccionado');
            $('#seguidos').removeClass('seleccionado');

            $('#usuariosListados').remove();

            var listado = `<div class="list-group" id="usuariosListados">`;
            
            if(seguidoresArray.length == 0){

                listado += `<div class="alert alert-info text-center">
                                Este usuario no tiene seguidores.
                            </div>`;

            }else{ 

                for(var x = 0; x<seguidoresArray.length;x++){


                    listado += `<a href="{{ url('perfil/` + seguidoresArray[x].perfil.id_user +`') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                            <img src="` + storageBase + `/` + seguidoresArray[x].perfil.img_perfil + `" 
                                class="rounded-circle shadow-sm" 
                                style="width: 50px; height: 50px; object-fit: cover;" 
                                alt="Imagen de perfil de X" onerror="this.onerror=null;this.src='` + defaultImgPerfil + `';">
                            {{-- Nombre --}}
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">`+ seguidoresArray[x].perfil.name +`</h6>
                                <p>`+ seguidoresArray[x].perfil.biografia.substring(0, 40) +`</p>
                            </div>
                    </a>`;

                }
            }

            listado += `</div>`;

            $('#listadoUsuarios').append(listado);
            
        }

        function listarSeguidos(){

            $('#seguidos').addClass('seleccionado');
            $('#seguidores').removeClass('seleccionado');

            $('#usuariosListados').remove();

            var listado = `<div class="list-group" id="usuariosListados">`;
            
            if(seguidosArray.length == 0){

                listado += `<div class="alert alert-info text-center">
                                Este usuario no sigue a nadie.
                            </div>`;

            }else{ 

                for(var x = 0; x<seguidosArray.length;x++){

                    listado += `<a href="{{ url('perfil/` + seguidosArray[x].perfil.id_user +`') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                            <img src="` + storageBase + `/` + seguidosArray[x].perfil.img_perfil + `" 
                                class="rounded-circle shadow-sm" 
                                style="width: 50px; height: 50px; object-fit: cover;" 
                                alt="Imagen de perfil de ` + seguidosArray[x].perfil.id_user +`" onerror="this.onerror=null;this.src='` + defaultImgPerfil + `';">
                            {{-- Nombre --}}
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">`+ seguidosArray[x].perfil.name +`</h6>
                                <p>`+ seguidosArray[x].perfil.biografia.substring(0, 40) +`</p>
                            </div>
                    </a>`;

                }
            }

            listado += `</div>`;

            $('#listadoUsuarios').append(listado);

        }

        $('#seguidores').click(listarSeguidores);
        $('#seguidos').click(listarSeguidos);

        $('#btnSeguidos').click(listarSeguidos);
        $('#btnSeguidores').click(listarSeguidores);


        // Por defecto, pillo las recetas publicadas

        $(document).ready(function(){

            $.ajax({
                url:"{{route('recetas.listaRecetasAjax')}}",
                method: 'POST',
                data:{
                    id: idUsuario,
                    _token: '{{csrf_token()}}',
                }
            }).done(function(res){
                var arreglo = JSON.parse(res);

                recetasPublicadas = arreglo;

                // Impresión del listado de recetas

                var listado = `<div class="row" id="recetasListadas">`;

                if(recetasPublicadas.length == 0){

                    listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                }else{ 

                    for(var x = 0; x<recetasPublicadas.length;x++){

                        var fondo = '';
                        var icono = '';

                        if(recetasPublicadas[x].estado == 1){
                            fondo = 'bg-danger bg-opacity-25';
                            icono = '<i class="bi bi-exclamation-triangle"></i>';
                        }

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm" style="cursor:pointer;">
                                    <img src="` + storageBase + `/` + recetasPublicadas[x].imagen + `"
                                            class="card-img-top" 
                                            alt="Imagen de `+ recetasPublicadas[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;" onclick="window.location='{{ url('receta/` + recetasPublicadas[x].id+`')}}'"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';" style="cursor:pointer;">
                                <div class="card-body `+fondo+` d-flex flex-column justify-content-between">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + recetasPublicadas[x].id +`') }}" class="text-decoration-none text-dark">
                                            `+recetasPublicadas[x].titulo+`
                                        </a>
                                        `+icono+`
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

                var listado = `<div class="row" id="recetasListadas">`;

                if(recetasMeGusta.length == 0){

                    listado += `<p class="text-muted">A este usuario no le gusta ninguna receta de momento.</p>`;

                }else{ 

                    for(var x = 0; x<recetasMeGusta.length;x++){
                        

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                        <div class="card h-100 shadow-sm" style="cursor:pointer;">
                                <img src="` + storageBase + `/` + recetasMeGusta[x].imagen + `"
                                        class="card-img-top" 
                                        alt="Imagen de `+ recetasMeGusta[x].titulo + `" 
                                        style="height: 180px; object-fit: cover;" onclick="window.location='{{ url('receta/` + recetasMeGusta[x].id+`')}}'"
                                        onerror="this.onerror=null;this.src='` + defaultImg + `';">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h6 class="card-title">
                                    <a href="{{ url('receta/` + recetasMeGusta[x].id +`') }}" class="text-decoration-none text-dark">
                                        `+recetasMeGusta[x].titulo+`
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

                
            });

        }

        //--------------------------------------------------------------------------------------------------------------------


        function crearListadoRecetasPublicadas(){

            $(document).ready(function(){

                var storageBase = "{{ asset('storage') }}";
                var defaultImg = "{{ asset('images/default-img.jpg') }}";

                var listado = `<div class="row" id="recetasListadas">`;

                if(recetasPublicadas.length == 0){

                    listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                }else{ 

                    for(var x = 0; x<recetasPublicadas.length;x++){

                        var fondo = '';
                        var icono = '';

                        if(recetasPublicadas[x].estado == 1){
                            fondo = 'bg-danger bg-opacity-25';
                            icono = '<i class="bi bi-exclamation-triangle"></i>';
                        }

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm" style="cursor:pointer;">
                                    <img src="` + storageBase + `/` + recetasPublicadas[x].imagen + `"
                                            class="card-img-top" 
                                            alt="Imagen de `+ recetasPublicadas[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;" onclick="window.location='{{ url('receta/` + recetasPublicadas[x].id+`')}}'"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';">
                                <div class="card-body `+fondo+` d-flex flex-column justify-content-between">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + recetasPublicadas[x].id +`') }}" class="text-decoration-none text-dark">
                                            `+recetasPublicadas[x].titulo+`
                                        </a>
                                        `+icono+`
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

            });
        }
    </script>

    <script>
        document.getElementById('img_perfil').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImgPerfil');

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
