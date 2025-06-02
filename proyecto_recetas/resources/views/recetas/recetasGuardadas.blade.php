@extends('layouts.app')

@section('titulo', 'Recetas Guardadas')

@section('content')

    <!-- Donde se van a listar las recetas -->
    <div class="row" id="listado">
    </div>

    <div class="row row-cols-1 row-cols-sm-3 g-3">
            
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

                    listado += `<p class="text-muted text-center">No tienes guardada ninguna receta</p>`;

                } else {

                    //--------------------------------------------------------------------------IMPRESIÓN DEL CADA RECETA-------------------------------------------------------------------------------

                    for (var x = 0; x < arreglo.length; x++) {

                        listado += `<div id="rece`+arreglo[x].id+`" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 recetaLista">
                            <div class="card h-100 shadow-sm d-flex flex-column border-0 rounded-3" style="cursor: pointer;">
                                <img src="{{ asset('storage/` + arreglo[x].imagen +`') }}"
                                    class="card-img-top"
                                    alt="Imagen de `+arreglo[x].titulo+`"
                                    style="height: 130px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`')}}'">
 
                                <div class="card-body d-flex flex-column justify-content-between p-2">
                                    <div class="mb-2 text">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                                            <a href="{{ url('perfil/`+arreglo[x].autor_receta+`') }}" 
                                            class="text-decoration-none text-muted">
                                                <img src="{{ asset('images/default-profile.jpg') }}"
                                                    alt="Imagen de perfil"
                                                    class="rounded-circle me-2"
                                                    style="width: 25px; height: 25px; object-fit: cover;">
                                                `+arreglo[x].nombreAutor+`
                                            </a>
                                        </div>
                                        <h6 class="card-title mb-1" style="font-size: 0.95rem;" onclick="window.location='{{ url('receta/` + arreglo[x].id+`')}}'">
                                            <strong>`+arreglo[x].titulo.substring(0,40)+`</strong>
                                        </h6>
                                    </div>
                                </div>
                                 <div class="d-flex justify-content-between mt-auto pt-2 px-1">
                                    <button id="btnLike" class="btn p-0 border-0 bg-transparent" title="`+(arreglo[x].like ? `Quitar me gusta`: `Dar me gusta` )+`">
                                        <i data-id="`+arreglo[x].id+`" class="bi bi-heart`+(arreglo[x].like ? `-fill`: `` )+` text-danger darLike"></i>
                                        <small id="`+arreglo[x].id+`">`+arreglo[x].meGustas+`</small>
                                    </button>

                                    <button class="btn p-0 border-0 bg-transparent" title="Quitar de guardadas">
                                        <i data-id="`+arreglo[x].id+`" class="bi bi-bookmark-fill text-success guardados"></i>
                                        <small>`+arreglo[x].vecesGuardados+`</small>
                                    </button>
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

                                        let receABorrar = "rece" + recetaId;

                                        document.getElementById(receABorrar).remove();

                                        var listaRecetasGuardadas = document.querySelectorAll(".recetaLista");

                                        if(listaRecetasGuardadas.length == 0){

                                            var noHayGuardados = `<p class="text-muted text-center">No tienes guardada ninguna receta</p>`;

                                            $("#listado").append(noHayGuardados);
                                        }

                                    } else {
                                        Swal.fire(
                                            'No se ha podido completar la solicitud',
                                            '', 'warning');
                                    }
                                },
                                error: function(error) {
                                    Swal.fire('Se ha producido un error', '',
                                        'error');
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
                            title: "¿Estás seguro de que ya no te gusta esta receta?",
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

                                $('#btnLike').attr('title', "Dar me gusta");

                                // Cambiu el valor del html por la nueva cantidad de me gusta

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

                        // Le quito una clase y le pongo la otra

                        $(this).removeClass('bi-heart');
                        $(this).addClass('bi-heart-fill');

                        $('#btnLike').attr('title', "Quitar me gusta");

                        // Cambiu el valor del html por la nueva cantidad de me gusta

                        valorMegusta++;
                        document.getElementById(`${recetaId}`).innerHTML = valorMegusta.toString();
                    }



                });
            })
        });
  
    </script>

@endsection
