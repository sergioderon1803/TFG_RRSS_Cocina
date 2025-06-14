@extends('layouts.app')

@section('titulo', 'Listado de recetas')

@section('content')
    <div class="container-fluid my-3 px-3 mb-5 margenesResponsive">
        <div class="d-flex flex-column align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="/images/logo_black.svg" alt="Logo WeCook" class="img-fluid" style="height: 80px;">
                <span class="fs-4 d-none d-md-inline ms-2" id="sidebarLogoText">WeCook</span>
            </div>
            <p class="text-center text-muted mt-2" style="font-size: 0.8rem; max-width: 600px;">
                Explora nuestras deliciosas recetas y encuentra tu próxima inspiración culinaria.
            </p>
        </div>
        <div class="row gx-5 gy-4">
            <!-- Columna de recetas -->
            <div class="col-12 col-sm-9 col-md-9 col-xl-9 margenesResponsive">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4" id="listado">

                </div>
            </div>

            <!-- Columna de filtros -->
            <div class="col-12 col-sm-3 col-md-3 col-xl-3 margenesResponsive">
                <form id="formBusqueda" action="{{ route('usuario.busqueda') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="input-group">
                            <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Buscar usuario o receta">
                            <button type="submit" class="btn botonVerMas">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div id="busquedaUsuarios"></div>
                    </div>
                </form>

                <div class="card mb-3">
                    <div class="card-header colorPrincipalOscuro text-white">
                        <strong>Elija tipo de receta:</strong>
                    </div>
                    <div class="card-body colorPrincipal">
                        <!-- Contenido del filtro 1 -->
                        <select id="tipoRecetas" class="form-select btn-cancelar text-white">
                            <option value="Todas" selected>Todas</option>
                            <option value="Postres y dulces">Postres y dulces</option>
                            <option value="Arroz">Arroz</option>
                            <option value="Pasta">Pasta</option>
                            <option value="Carnes y aves">Carnes y aves</option>
                            <option value="Pescado y marisco">Pescado y marisco</option>
                            <option value="Verduras y hortalizas">Verduras y hortalizas</option>
                            <option value="Ensaladas">Ensaladas</option>
                            <option value="Huevos y tortillas">Huevos y tortillas</option>
                            <option value="Tapas y aperitivos">Tapas y aperitivos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--SCRIPT BUSCADOR, SE PUEDE MOVER PERO DE MOMENTO LO DEJO AQUÍ-->

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


    <!--SCRIPT DEL LISTADO DE RECETAS (CREO QUE NO SE PODRÁ MOVER A OTRO)-->

    <script>

        var ids = [];
        var filtroTipo = $('#tipoRecetas').val();

        $(document).ready(function() {

            var storageBase = "{{ asset('storage') }}";
            var defaultImg = "{{ asset('images/default-img.jpg') }}";
            var defaultProfile = "{{ asset('images/default-profile.jpg') }}";

            $.ajax({
                url: "{{ route('recetas.listarRecetasPrincipalAjax') }}",
                method: 'POST',
                data: {
                    tipo: filtroTipo,
                    _token: '{{ csrf_token() }}',
                }
            }).done(function(res) {
                var arreglo = JSON.parse(res);

                // Impresión del listado de recetas

                var listado = ``;

                //--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                for (var x = 0; x < arreglo.length; x++) {

                    ids.push(arreglo[x].id);

                    listado += `<div class="col recetaListada">
                        <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3 recetaResponsive" style="cursor: pointer;">
                            <img src="` + storageBase + `/` + arreglo[x].imagen + `"
                                class="card-img-top"
                                alt="Imagen de ` + arreglo[x].titulo + `"
                                style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'"
                                onerror="this.onerror=null;this.src='` + defaultImg + `';">

                            <div class="card-body d-flex flex-column justify-content-between p-2">
                                <div class="mb-2 text">
                                    <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                        <a href="{{ url('perfil/`+arreglo[x].autor_receta+`') }}" 
                                        class="text-decoration-none text-muted">
                                            <img src="` + (arreglo[x].imgAutor ? storageBase + '/' + arreglo[x].imgAutor : defaultProfile) + `"
                                                alt="Imagen de perfil"
                                                class="rounded-circle me-2"
                                                style="width: 25px; height: 25px; object-fit: cover;"
                                                onerror="this.onerror=null;this.src='` + defaultProfile + `';">
                                            ` + arreglo[x].nombreAutor + `
                                        </a>
                                    </div>
                                    <h6 class="card-title mb-1" style="font-size: 0.95rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'">
                                        <strong>` + arreglo[x].titulo.substring(0, 40) + `</strong>
                                    </h6>
                                </div>
                                <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                <button id="btnLike` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].like ? `Quitar me gusta` : `Dar me gusta`) + `">
                                    <i data-id="` + arreglo[x].id + `" class="bi bi-heart` + (arreglo[x].like ? `-fill` : ``) + ` text-danger darLike"></i>
                                    <small id="gustas` + arreglo[x].id + `">` + arreglo[x].meGustas + `</small>
                                </button>

                                <button id="btnGuardado` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].guardado ? `Quitar de guardadas` : `Guardar receta`) + `">
                                    <i data-id="` + arreglo[x].id + `" class="bi bi-bookmark` + (arreglo[x].guardado ? `-fill` : ``) + ` text-success guardados"></i>
                                    <small id="guardados` + arreglo[x].id + `">` + arreglo[x].vecesGuardados + `</small>
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>`;

                }


                listado += `</div>`;

                $("#listado").append(listado);

                // Botón "Ver más"
                $("#listado").after(`
                    <div class="text-center mt-4">
                        <button id="verMas" class="btn botonVerMas">Ver más...</button>
                        <p id="noHayRecetas" class="text-center fw-bold" hidden>No se encontraron recetas</p>
                    </div>
                `);

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

                //--------------------------------------------------------------------------VER MÁS-------------------------------------------------------------------------------

                $("#verMas").on("click", function() {

                    $('.guardados').off('click');
                    $('.darLike').off('click');

                    $.ajax({
                        url: "{{ route('recetas.listarRecetasPrincipalAjax') }}",
                        method: 'POST',
                        data: {
                            recetas: ids,
                            tipo: filtroTipo,
                            _token: '{{ csrf_token() }}',
                        }
                    }).done(function(res) {
                        var arreglo = JSON.parse(res);

                        // Impresión del listado de recetas

                        var listado = ``;

                        //--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                        if(arreglo.length == 0){

                        if(!$('#verMas').attr('hidden')){

                            $('#verMas').attr('hidden',true);
                            $('#noHayRecetas').attr('hidden',false);

                        }

                        }else{

                            if($('#verMas').attr('hidden')){

                                $('#verMas').attr('hidden',false);
                                $('#noHayRecetas').attr('hidden',true);
                            }

                        

                            for (var x = 0; x < arreglo.length; x++) {

                                ids.push(arreglo[x].id);


                                listado += `<div class="col recetaListada">
                                    <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3" style="cursor: pointer;">
                                        <img src="`+ storageBase + `/` + arreglo[x].imagen +`"
                                            class="card-img-top"
                                            alt="Imagen de ` + arreglo[x].titulo + `"
                                            style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'"
                                            onerror="this.onerror=null;this.src='` + defaultImg + `';">

                                        <div class="card-body d-flex flex-column justify-content-between p-2">
                                            <div class="mb-2 text">
                                                <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                                    <a href="{{ url('perfil/`+arreglo[x].autor_receta+`') }}" 
                                                    class="text-decoration-none text-muted">
                                                        <img src="` + (arreglo[x].imgAutor ? storageBase + '/' + arreglo[x].imgAutor : defaultProfile) + `"
                                                            alt="Imagen de perfil"
                                                            class="rounded-circle me-2"
                                                            style="width: 25px; height: 25px; object-fit: cover;"
                                                            onerror="this.onerror=null;this.src='` + defaultProfile + `';">
                                                        ` + arreglo[x].nombreAutor + `
                                                    </a>
                                                </div>
                                                <h6 class="card-title mb-1" style="font-size: 0.95rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'">
                                                    <strong>` + arreglo[x].titulo.substring(0, 40) + `</strong>
                                                </h6>
                                            </div>
                                            </div>
                                                <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                                <button id="btnLike` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].like ? `Quitar me gusta` : `Dar me gusta`) + `">
                                                    <i data-id="` + arreglo[x].id + `" class="bi bi-heart` + (arreglo[x].like ? `-fill` : ``) + ` text-danger darLike"></i>
                                                    <small id="gustas` + arreglo[x].id + `">` + arreglo[x].meGustas + `</small>
                                                </button>

                                                <button id="btnGuardado` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].guardado ? `Quitar de guardadas` : `Guardar receta`) + `">
                                                    <i data-id="` + arreglo[x].id + `" class="bi bi-bookmark` + (arreglo[x].guardado ? `-fill` : ``) + ` text-success guardados"></i>
                                                    <small id="guardados` + arreglo[x].id + `">` + arreglo[x].vecesGuardados + `</small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>`;

                                }
                        }
                        


                        listado += `</div>`;

                        $("#listado").append(listado);

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


                    });
                });
            })

            
        });

        //-----------------------------------------------------------------------EVENTO SELECT------------------------------------------------------------------------

         $('#tipoRecetas').on('click',function(){
            if(filtroTipo != $('#tipoRecetas').val()){

                var storageBase = "{{ asset('storage') }}";
                var defaultImg = "{{ asset('images/default-img.jpg') }}";
                var defaultProfile = "{{ asset('images/default-profile.jpg') }}";

                filtroTipo = $('#tipoRecetas').val();
                $('.recetaListada').remove();
                ids = [];

                $(document).ready(function() {
                $.ajax({
                    url: "{{ route('recetas.listarRecetasPrincipalAjax') }}",
                    method: 'POST',
                    data: {
                        tipo: filtroTipo,
                        _token: '{{ csrf_token() }}',
                    }
                }).done(function(res) {
                    var arreglo = JSON.parse(res);

                    // Impresión del listado de recetas

                    var listado = ``;

                    //--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                    if(arreglo.length == 0){

                        if(!$('#verMas').attr('hidden')){

                            $('#verMas').attr('hidden',true);
                            $('#noHayRecetas').attr('hidden',false);

                        }

                    }else{

                        if($('#verMas').attr('hidden')){

                            $('#verMas').attr('hidden',false);
                            $('#noHayRecetas').attr('hidden',true);
                        }

                        for (var x = 0; x < arreglo.length; x++) {

                            ids.push(arreglo[x].id);

                            listado += `<div class="col recetaListada">
                                <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3 recetaResponsive" style="cursor: pointer;">
                                    <img src="` + storageBase + `/` + arreglo[x].imagen + `"
                                        class="card-img-top"
                                        alt="Imagen de ` + arreglo[x].titulo + `"
                                        style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'"
                                        onerror="this.onerror=null;this.src='` + defaultImg + `';">

                                    <div class="card-body d-flex flex-column justify-content-between p-2">
                                        <div class="mb-2 text">
                                            <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                                <a href="{{ url('perfil/`+arreglo[x].autor_receta+`') }}" 
                                                class="text-decoration-none text-muted">
                                                    <img src="` + (arreglo[x].imgAutor ? storageBase + '/' + arreglo[x].imgAutor : defaultProfile) + `"
                                                        alt="Imagen de perfil"
                                                        class="rounded-circle me-2"
                                                        style="width: 25px; height: 25px; object-fit: cover;"
                                                        onerror="this.onerror=null;this.src='` + defaultProfile + `';">
                                                    ` + arreglo[x].nombreAutor + `
                                                </a>
                                            </div>
                                            <h6 class="card-title mb-1" style="font-size: 0.95rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`') }}'">
                                                <strong>` + arreglo[x].titulo.substring(0, 40) + `</strong>
                                            </h6>
                                        </div>
                                        <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                        <button id="btnLike` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].like ? `Quitar me gusta` : `Dar me gusta`) + `">
                                            <i data-id="` + arreglo[x].id + `" class="bi bi-heart` + (arreglo[x].like ? `-fill` : ``) + ` text-danger darLike"></i>
                                            <small id="gustas` + arreglo[x].id + `">` + arreglo[x].meGustas + `</small>
                                        </button>

                                        <button id="btnGuardado` + arreglo[x].id + `" class="btn p-0 border-0 bg-transparent" title="` + (arreglo[x].guardado ? `Quitar de guardadas` : `Guardar receta`) + `">
                                            <i data-id="` + arreglo[x].id + `" class="bi bi-bookmark` + (arreglo[x].guardado ? `-fill` : ``) + ` text-success guardados"></i>
                                            <small id="guardados` + arreglo[x].id + `">` + arreglo[x].vecesGuardados + `</small>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                            </div>`;

                        }
                    }


                    listado += `</div>`;

                    $("#listado").append(listado);

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

                })

                
            });
                
            }
        });
    </script>

@endsection
