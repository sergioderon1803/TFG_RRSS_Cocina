@extends('layouts.app')

@section('titulo', 'Perfil de ' . $perfil->name)

@section('css')
<style>
        .clickar {
            cursor: pointer;
            text-decoration: underline;
        }
</style>
@endsection

@section('content')
@php
    // Comprobar si hay imagen de perfil y banner y si no, se ponen las imagenes por defecto
    $imgPerfil = Str::startsWith($perfil->img_perfil, 'perfiles/')
        ? asset('storage/' . $perfil->img_perfil)
        : asset($perfil->img_perfil);

    $imgBanner = Str::startsWith($perfil->img_banner, 'perfiles/')
        ? asset('storage/' . $perfil->img_banner)
        : asset($perfil->img_banner);
@endphp

<div class="position-relative mb-5">
    {{-- Banner --}}
    <div class="w-100" style="height: 250px; background: url('{{ $imgBanner }}') no-repeat center center; background-size: cover;">
    </div>

    {{-- Imagen de perfil --}}
    <div class="position-absolute top-100 start-0 translate-middle-y ps-4">
        <img src="{{ $imgPerfil }}"
            class="rounded-circle border border-white shadow"
            style="width: 120px; height: 120px; object-fit: cover;"
            alt="Perfil de {{ $perfil->name }}">
    </div>

    {{-- Botón de editar o seguir, según el caso --}}
    @auth
        <div class="position-absolute top-0 end-0 p-3">
            @if (Auth::id() === $perfil->id_user)
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarPeril">
                    Editar perfil
                </button>
            @else
                <form id="seguir" method="POST" 
                    action="{{ $seguido ? route('usuario.dejarSeguir', $perfil->id_user) : route('usuario.seguir', $perfil->id_user) }}">
                    @csrf
                    @if ($seguido)
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Dejar de seguir</button>
                    @else
                        <button type="submit" class="btn btn-success btn-sm">Seguir</button>
                    @endif
                </form>
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
            <strong class="text-dark">{{ $perfil->user->seguidores->count() }}</strong>
            <a href="{{ route('profile.seguidores', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidores</a>
        </div>
        <div class="d-flex flex-column">
            <strong class="text-dark">{{ $perfil->user->seguidos->count() }}</strong>
            <a href="{{ route('profile.seguidos', $perfil->id_user) }}" class="text-decoration-none text-muted">Seguidos</a>
        </div>
    </div>
</div>


@if (Auth::id() === $perfil->id_user)
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#recetasGuardadas">
        Ver recetas guardadas
    </button>
@endif


<hr class="mt-4">

<h5 class="fw-bold mb-3"><span class="clickar" style="color:#F07B3F;" id="clickRecetas">Recetas publicadas</span> | <span class="clickar" id="clickMeGustas">Me gustas</span></h5>

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

@if($seguido)
    <script>
        const form = document.getElementById('seguir');

        if(form != null){  // ESTA COMPROBACIÓN ES NECESARIA, SI SE QUITA, DA ERROR CUANDO ENTRAS A TU PROPIO PERFIL, NO PETA LA PÁGINA PERO EL ERROR LO DA

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                Swal.fire({
                title: "¿Estás seguro de que deseas dejar de seguir a este usuario?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Dejar de seguir"
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    title: "Dejaste de seguir al usuario",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                    });

                    setTimeout(() => {
                    form.submit();
                    }, 600);
                }
                });
            });
        }
    </script>
@endif

    <script>

        // Recojo la URL y pillo el ID del usuario
        const url = window.location.href.split("/");
        const idUsuario = parseInt(url[url.length-1]);

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

                // Impresión del listado de recetas

                var listado = `<div class="row" id="recetasListadas">`;

                if(arreglo.length == 0){

                    listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                }else{ 

                    for(var x = 0; x<arreglo.length;x++){

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}" 
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;">
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

                    document.getElementById("clickMeGustas").setAttribute('style','color:#F07B3F');
                    document.getElementById("clickRecetas").removeAttribute('style');

                    crearListadoMeGustas();
                });

            })
        });

// A partir de aquí, es la misma lógica para los dos listados, guardo en funciones lo que hacen para poder gestionar los eventos

//-----------------------------------------------LISTADO ME GUSTAS-----------------------------------------------

        function crearListadoMeGustas(){

            $(document).ready(function(){

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
                                    <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}" 
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;">
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

                        document.getElementById("clickRecetas").setAttribute('style','color:#F07B3F');
                        document.getElementById("clickMeGustas").removeAttribute('style');


                        crearListadoRecetasPublicadas();
                    });

                })
            });

        }

        //--------------------------------------------------------------------------------------------------------------------


        function crearListadoRecetasPublicadas(){

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

                    var listado = `<div class="row" id="recetasListadas">`;

                    if(arreglo.length == 0){

                        listado += `<p class="text-muted">Este usuario aún no ha publicado ninguna receta.</p>`;

                    }else{ 

                        for(var x = 0; x<arreglo.length;x++){

                            listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}" 
                                            class="card-img-top" 
                                            alt="Imagen de `+ arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;">
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

                        document.getElementById("clickMeGustas").setAttribute('style','color:#F07B3F');
                        document.getElementById("clickRecetas").removeAttribute('style');

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