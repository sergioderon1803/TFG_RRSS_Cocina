@extends('layouts.app')

@section('titulo', 'Recetas Guardadas')

@section('content')

    <!-- Donde se van a listar las recetas -->
    <div class="row" id="listado">
    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('recetas.listarRecetasGuardadasAjax') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                }
            }).done(function(res) {
                var arreglo = JSON.parse(res);

                // Impresión del listado de recetas

                var listado = `<div class="row" id="recetasListadas">`;

                if (arreglo.length == 0) {

                    listado += `<p class="text-muted">No tienes guardada ninguna receta</p>`;

                } else {

//--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                    for (var x = 0; x < arreglo.length; x++) {

                        listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3">
                                    <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}" 
                                            class="card-img-top" 
                                            alt="Imagen de ` + arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;">
                                <div class="card-body d-flex flex-column justify-content-between p-2">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + arreglo[x].id +`') }}" class="text-decoration-none text-dark">
                                            ` + arreglo[x].titulo + `
                                        </a>
                                    </h6>
                                </div>
                                 <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                    <i data-id="` + arreglo[x].id + `" class="bi ` + (arreglo[x].like ?
                            `bi-heart-fill` : `bi-heart`) + ` text-danger darLike"></i>

                                    <small id="` + arreglo[x].id + `">` + arreglo[x].meGustas + `</small>

                                    <i data-id="` + arreglo[x].id + `" class="bi bi-bookmark-fill fs-5 guardados" style="color: #2A9D8F;"></i>

                                    <small>` + arreglo[x].vecesGuardados + `</small>
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

                    Swal.fire({
                        title: "¿Estás seguro de que ya no quieres guardar esta receta?",
                        text: "",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
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

                                        document.getElementById('recetasListadas').remove();
                                        imprimirRecetasGuardadas();
                                    } else {
                                        Swal.fire(
                                            'No se ha podido completar la solicitud','', 'warning');
                                    }
                                },
                                error: function(error) {
                                    Swal.fire('Se ha producido un error', '','error');
                                }
                            })
                        }


                    });



                });

//-------------------------------------------------------------------------------------ME GUSTAS--------------------------------------------------------------------------------------------------

                $(".darLike").on("click", function() {

                    const recetaId = $(this).data('id');

                    let etiqueta = `#${recetaId}`; // Guardo el id de la etiqueta small

                    let valorMegusta = parseInt($(etiqueta).text()); // Convierto en número el txto que tiene

                    if ($(this).hasClass('bi-heart-fill')) {

                        Swal.fire({
                            title: "¿Estás seguro de que ya no te gusta esta receta",
                            text: "",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
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
                                                'No se ha podido completar la solicitud','', 'warning');
                                        }
                                    },
                                    error: function(error) {
                                        Swal.fire('Se ha producido un error','', 'error');
                                    }
                                })
                                
                                // Le quito una clase y le pongo la otra

                                $(this).removeClass('bi-heart-fill');
                                $(this).addClass('bi-heart');

                                // Cambiu el valor del html por la nueva cantidad de me gusta

                                valorMegusta--;

                                document.getElementById(`${recetaId}`).innerHTML = valorMegusta.toString();
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

                        // Cambiu el valor del html por la nueva cantidad de me gusta

                        valorMegusta++;
                        document.getElementById(`${recetaId}`).innerHTML = valorMegusta.toString();
                    }



                });
            })
        });

//------------------------------------------------------------------------FUNCIÓN PARA IMPRIMIR EL LISTADO AL RECARGAR------------------------------------------------------------------

        function imprimirRecetasGuardadas() {
            $(document).ready(function() {
                $.ajax({
                    url: "{{ route('recetas.listarRecetasGuardadasAjax') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    }
                }).done(function(res) {
                    var arreglo = JSON.parse(res);

                    console.log(arreglo);

// Impresión del listado de recetas

                    var listado = `<div class="row" id="recetasListadas">`;

                    if (arreglo.length == 0) {

                        listado += `<p class="text-muted">No tienes guardada ninguna receta</p>`;

                    } else {

//--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                        for (var x = 0; x < arreglo.length; x++) {

                            listado += `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3">
                                    <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}" 
                                            class="card-img-top" 
                                            alt="Imagen de ` + arreglo[x].titulo + `" 
                                            style="height: 180px; object-fit: cover;">
                                <div class="card-body d-flex flex-column justify-content-between p-2">
                                    <h6 class="card-title">
                                        <a href="{{ url('receta/` + arreglo[x].id +`') }}" class="text-decoration-none text-dark">
                                            ` + arreglo[x].titulo + `
                                        </a>
                                    </h6>
                                </div>
                                 <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                    <i data-id="` + arreglo[x].id + `" class="bi ` + (arreglo[x].like ?
                                `bi-heart-fill` : `bi-heart`) + ` text-danger darLike"></i>

                                    <small id="` + arreglo[x].id + `">` + arreglo[x].meGustas + `</small>

                                    <i data-id="` + arreglo[x].id + `" class="bi bi-bookmark-fill fs-5 guardados" style="color: #2A9D8F;"></i>

                                    <small>` + arreglo[x].vecesGuardados + `</small>
                                </div>
                            </div>
                        </div>`;

                        }
                    }

                    listado += `</div>`;

                    $("#listado").append(listado);

//------------------------------------------------------------------------------------------------------------------------------------------------------------

                    $(".guardados").on("click", function() {

                        const recetaId = $(this).data('id');

                        Swal.fire({
                            title: "¿Estás seguro de que ya no quieres guardar esta receta?",
                            text: "",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
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

                                            document.getElementById('recetasListadas').remove();
                                            imprimirRecetasGuardadas();
                                        } else {
                                            Swal.fire(
                                                'No se ha podido completar la solicitud','', 'warning');
                                        }
                                    },
                                    error: function(error) {
                                        Swal.fire('Se ha producido un error','', 'error');
                                    }
                                })
                            }


                        });



                    });

//-------------------------------------------------------------------------------------ME GUSTAS--------------------------------------------------------------------------------------------------

                    $(".darLike").on("click", function() {

                        const recetaId = $(this).data('id');

                        let etiqueta = `#${recetaId}`;

                        let valorMegusta = parseInt($(etiqueta).text());

                        if ($(this).hasClass('bi-heart-fill')) {

                            Swal.fire({
                                title: "¿Estás seguro de que ya no te gusta esta receta",
                                text: "",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "No me gusta"
                            }).then((result) => {

                                if (result
                                    .isConfirmed) { // Si acepta borrarla, hago un ajax

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
                                            Swal.fire(
                                                'Se ha producido un error',
                                                '', 'error');
                                        }
                                    })

                                    $(this).removeClass('bi-heart-fill');
                                    $(this).addClass('bi-heart');

                                    valorMegusta--;

                                    document.getElementById(`${recetaId}`).innerHTML =
                                        valorMegusta.toString();
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

                            $(this).removeClass('bi-heart');
                            $(this).addClass('bi-heart-fill');

                            valorMegusta++;
                            document.getElementById(`${recetaId}`).innerHTML = valorMegusta
                                .toString();
                        }



                    });
                })
            });
        }
    </script>

@endsection
